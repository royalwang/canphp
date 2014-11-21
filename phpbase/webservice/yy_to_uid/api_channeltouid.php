<?php
namespace imweb\php;
error_reporting(0);
//ip白名单
$white_ip_array = array(
	'127.0.0.1',//本地测试
	'172.16.43.113',//本地测试
	'119.97.153.227',
	'183.60.177.224/27',//公司内网
	'210.21.125.40/29',//公司内网
	'113.108.232.32/28',//公司内网 50号大楼
);

require_once __DIR__.'/../Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(dirname(__FILE__)).'/gen-php/';

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ . '/../');
$loader->registerDefinition('server', $GEN_DIR);
$loader->register();

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TFramedTransport;
use Thrift\Exception\TException;

function check_ip($ip_array = array(), $remote_ip = ''){
	$remote_ip = empty($remote_ip) ? $_SERVER['REMOTE_ADDR'] : $remote_ip;
	//判断ip是否在白名单
	foreach($ip_array as $ip){
		$ip_info = explode('/', $ip);
		$mask = isset($ip_info[1]) ? $ip_info[1] : 32;
		if(substr(sprintf("%032b", ip2long($ip_info[0])), 0, $mask) === substr(sprintf("%032b", ip2long($remote_ip)), 0, $mask)){
			return true;
		}
	}
	return false;
}

$result = array('code'=>0, 'data'=>0);

//ip白名单判断
if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
if( !check_ip($white_ip_array, $_SERVER['REMOTE_ADDR']) ){
	$result['code'] = -1; //ip不再白名单内
	//echo json_encode($result);
	//exit;
}

try {
	/*yy频道号转OW uid接口
	udb对接人：余冠彬
	测试环境(无ip白名单)：121.11.71.251 8090
	生产环境(有ip白名单):
			主： 113.106.100.8 8090
			备： 183.61.2.139 8090
	
	*/
	$socket = new TSocket('113.106.100.8', 8090);
	$transport = new TFramedTransport($socket, 1024, 1024);
	$protocol = new TBinaryProtocol($transport);
	$transport->open();
	
	//业务代码begin
	$client = new \server\webdb\gateway\webdb_gateway_serviceClient($protocol);
	$channel_id = trim($_REQUEST['channel_id']);
	$ret = $client->get_session_info($channel_id, array('ownerid'), 0);
	if( isset($ret->dataset) && !empty($ret->dataset[0][0]) ) {
		$result['code'] = 1;
		$result['data'] = $ret->dataset[0][0];
	}
	//业务代码end
	
	$transport->close();
} catch (TException $tx) {
  $result['code'] = -100; //thrift异常
}
echo json_encode($result);
?>
