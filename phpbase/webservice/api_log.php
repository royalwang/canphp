<?php 
class api_log{
	protected $app_id;
	protected $cache_key;
	protected $length = 100; //数据条数
	public function __construct($params){
		if( empty($params['app_id']) ) exit('-1');
		$this->app_id = $params['app_id'];
		$this->cache_key= 'api_log_'.$this->app_id.'_key';
		if( !empty($params['log']) ){
			$this->write($params['log']);
		}else{
			$this->read();
		}		
	}
	
	public function write($log){
		$value = (int)$this->_get($this->cache_key);
		$value = ($value%$this->length)+1;
		$log_key = $this->cache_key . $value;
		$this->_set($this->cache_key, $value, 86400);
		$this->_set($log_key, $log, 86400);	
	}
	
	public function read(){
		$arr = array();
		for($i=1; $i<=$this->length; $i++){
			$key = $this->cache_key . $i;
			$value = $this->_get($key);
			if( !empty($value) && !empty($value['time']) ){
				$value['msg'] = htmlspecialchars($value['msg']);
				$arr[] = $value;
				//$this->_set($key, '', 1);
			}
		}
		$arr = $this->quickSort($arr);
		echo json_encode($arr);
	}
	
	protected function quickSort($data){
		$len = count($data);
		for($i=0; $i<$len; $i++){
			for($j=$i; $j<$len; $j++){
				if( $data[$i]['time']<$data[$j]['time'] ){
					$temp = $data[$i];
					$data[$i] = $data[$j];
					$data[$j] = $temp;
				}
			}		
		}
		return $data;
	}
	
	protected function _get($key){
		return apc_fetch($key);
	}
	
	protected function _set($key, $value, $expire){
		return apc_store($key, $value, $expire);
	}
}

function curlPost($url, $data, $ip){
	$urlArr = parse_url($url);
	$domain = $urlArr['host'];
	$url = str_replace("http://".$domain, "http://".$ip, $url);
	$ch = curl_init(); 
	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true, 
		CURLOPT_POST => true,
		CURLOPT_NOSIGNAL=>true,
		CURLOPT_CONNECTTIMEOUT_MS => 200,
		CURLOPT_TIMEOUT_MS => 200,
		CURLOPT_HTTPHEADER=>array("Host: " . $domain),
		CURLOPT_POSTFIELDS => http_build_query($data),
	)); 	
	$ret = curl_exec($ch); 
	curl_close($ch);
	return $ret;	
}
if( '183.61.6.41'==$_SERVER['SERVER_ADDR']){
	new api_log($_REQUEST);
}else{
	echo curlPost('http://webapi.duowan.com/api_log.php', $_REQUEST, '183.61.6.41');
}
