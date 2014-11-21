<?php
//珠海通道参数配置
define('ZH_URL', 'http://sms.duowan.com/send/smssending_emay.jsp');//接口地址，由短信接口提供
define('ZH_KEY', 'PqgsTuFRYItb05J79W4A');//密钥，由短信接口提供
define('ZH_SUBUSER', '08020100');//部门项目功能编号，由短信接口提供
define('ZH_USERID', 'dw_zhongxiaofa');//发送短信的多玩通行证，请自行修改

//广州通道参数配置
define('GZ_URL', 'http://gossip.sysop.duowan.com:9900'); //已分配id，但尚未开通短信发送功能
if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
if(in_array($_SERVER['REMOTE_ADDR'], array('183.61.143.8','183.61.143.27','183.61.143.28'))){
	//元宝商城
	define('GZ_APPID',  '42');  
	define('GZ_APPKEY', 'secretCaptcha'); 
}else if(in_array($_SERVER['REMOTE_ADDR'], array('183.136.131.210'))){
	//推广短信
	define('GZ_APPID',  '44'); 
	define('GZ_APPKEY',  'secretInvite'); 
}else{ //5153项目
	define('GZ_APPID',  '69'); 
	define('GZ_APPKEY',  '5153Captcha'); 
}
define('LOG_PATH', './tmp/');//短信发送日志存放目录，后面带斜杠"/"

//ip白名单
$white_ip_array = array(
	'127.0.0.1',//本地测试
	'119.97.153.227',
	'183.60.177.224/27',//公司内网
	'210.21.125.40/29',//公司内网
	'113.108.232.32/28',//公司内网 50号大楼
	'183.61.6.41',
	'183.61.6.11',
	'183.61.6.85',
	'183.61.6.24',
	'183.61.6.93',//大神带玩，测试
	'183.61.6.98',//大神带玩，测试
	'115.231.33.147',//大神带玩，正式
	'115.231.33.148',//大神带玩，正式
	'115.231.33.149',//大神带玩，正式
	
	'183.61.12.190',//lol
	'183.61.12.188',//lol
	'183.61.12.110',//lol
	'183.61.12.109',//lol
	
	'183.61.12.172',//wotbox
	
	'183.61.6.155', //lol战绩任务队列
	'183.61.6.156', //lol战绩任务队列
	'113.106.100.12', //lol战绩任务队列
	'113.106.100.13', //lol战绩任务队列
	
	'183.136.131.210', //5253
	
	'14.17.107.86',//手机盒子后台监控
	'14.17.107.87',//手机盒子后台监控
	
	'14.17.109.174', //私密圈
	'14.17.109.175',
	'14.17.107.106',
	
	'183.136.136.72', //发布器视频项目
	
	'183.61.6.28', //图库后台

	'183.61.143.8', //元宝商城
	'183.61.143.27', //元宝商城
	'183.61.143.28', //元宝商城

	'113.108.228.214', //运维监控
	'58.248.181.86',  //运维监控
	
	'172.16.53.148/12',
);

class sms{
	protected $sendChannel = 'zhSend';
	public function __construct($ipList=array(), $sendChannel='zhSend'){
		if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		
		if( !$this->checkIp($ipList, $_SERVER['REMOTE_ADDR']) ){
			$this->returnCode(-100);
		}
		
		if( !empty($sendChannel) ){
			$this->sendChannel = $sendChannel;
		}
	}
	
	public function send($phone, $content){
		$phone = trim($phone );
		$content = trim($content);
		if(empty($phone) || empty($content )){
			$this->returnCode(-101);
		}
		
		if( strlen($phone)>11 ){
			$phone = unserialize($phone);
			if( empty($phone) ){
				$this->returnCode(-102);
			}
		}

		$ret = -102;
		if( is_array($phone) ){
			foreach($phone as $v){
				$ret = call_user_func(array($this, $this->sendChannel), $v, $content);
			}
		}else{
			$ret = call_user_func(array($this, $this->sendChannel), $phone, $content);
		}
		$this->returnCode( $ret );	
	}
	
	/*  
	功能：广州YY通道发送，建议单个发送
	返回值：
		0 : ok，入列成功
		1 : be force to resend，一次调用传递的短信数量超出限制，或处于限流控制中，需要推迟若干时间再重送（客户端自己决定时间）
		2 : too much mobiles
		3 : size of message content is too large
		4 : rejected，短信被拒绝，见desc
		5 : duplicated, 短信之前已经发送过（根据muid判断）
		6 : failed, 短信入列失败，见desc
		-1: 程序发送异常
	*/
	public function gzSend($phone, $content){
		$data = array();
		$data['appId'] = GZ_APPID;
		$data['appKey'] = GZ_APPKEY;
		$data['mobile'] = $phone;
		$data['message'] = $content;
		$data['muid'] = substr($phone, 0, 11).date("ymdhis"); //相同muid，不会重新发送
		
		for($i=0; $i<2; $i++){ //超时重试2次
			$ret = $this->curlPost(GZ_URL, $data);
			if( strlen($ret)>0 ) break;
		}

		$arr = json_decode($ret, true);
		$status = isset($arr['code']) ? $arr['code']: -1;
		if(0==$status) $status=1;
		
		$this->writeLog($phone, $content, $status);
		return $status;
	}
	
	//珠海通道发送
	public function zhSend($phone, $content){
		$time = date('YmdHis');
		$mac = strtolower( md5(ZH_USERID . $phone . ZH_SUBUSER . $time . ZH_KEY) );
		
		$data['phone'] = $phone;
		$data['userid'] = ZH_USERID;
		$data['subuser'] = ZH_SUBUSER;
		$data['time'] = $time;
		$data['mac'] = $mac;
		$data['content'] = $content;
		
		$status = $this->curlPost(ZH_URL, $data);
		$status = trim($status);
		$this->writeLog($phone, $content, $status);
		return $status;	
	}
	
	function checkIp($ip_array = array(), $remote_ip = ''){
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
	
	//返回输出代码
	protected function returnCode($code){
		echo $code;
		exit;
	}
	
	//写入日志
	protected function writeLog($phone, $content, $status){
		$file = LOG_PATH . 'sms_' . date("Y-m") . '.log';
		$date = date('Y-m-d H:i:s');
		$log_info = "$phone\t $content\t $date\t $status\r\n";
		file_put_contents($file, $log_info, FILE_APPEND);	
	}
	
	//post请求
	protected function curlPost($url, $data = array()) {
		$ch = curl_init(); 
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_POST => true,
			CURLOPT_NOSIGNAL=>true,
			CURLOPT_CONNECTTIMEOUT_MS => 200,
			CURLOPT_TIMEOUT_MS => 2000,
			CURLOPT_POSTFIELDS => http_build_query($data),
		));

		$ret = curl_exec($ch); 
		curl_close($ch); 		
		return $ret;
	}
}

$sms = new sms($white_ip_array, 'gzSend');
$sms->send($_REQUEST['phone'], $_REQUEST['content']);
?>
