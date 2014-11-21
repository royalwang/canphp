<?php
class dwLog{
	public function write($msg, $label=''){
		if( empty($GLOBALS['app_id']) ) return false;
		$data = array(
			'app_id' =>$GLOBALS['app_id'],
			'log' => array(
						'server_ip' => $_SERVER['SERVER_ADDR'],
						'url' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
						'msg' => print_r($msg, true),
						'time' => time(),
						'label'=>$label,
					),
		);
		$ch = curl_init(); 
		curl_setopt_array($ch, array(
			CURLOPT_URL => 'http://webapi.duowan.com/api_log.php',
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_POST => true,
			CURLOPT_NOSIGNAL=>true,
			CURLOPT_CONNECTTIMEOUT_MS => 200,
			CURLOPT_TIMEOUT_MS => 200,
			CURLOPT_POSTFIELDS => http_build_query($data),
		)); 	
		$ret = curl_exec($ch); 
		curl_close($ch); 
	}
}