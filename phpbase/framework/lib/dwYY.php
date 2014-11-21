<?php
/*
功能： YY系统消息发送
用法： obj('dwSMS')->send('uid', '可包含html代码的内容');

接口返回值 : 
   0 发送成功
   -100 IP不在白名单内
   -101 参数错误
*/

class dwYY{
	private $_url = 'http://webapi.duowan.com/api_yy.php';
		
	//发送短信,$phone，可为字符串或数组
	public function send($uid, $msg, $title='温馨提示', $button_text='关闭', $button_action=''){	
		if( empty($uid) || empty($msg) ) return -101;
		
		$data['uid'] = $uid;
		$data['msg'] = $msg;
		$data['title'] = $title;
		$data['button_text'] = $button_text;
		$data['button_action'] = $button_action;
		
		return $this->curlPost($this->_url, $data);
	}
	
	//通过curl post数据
	protected function curlPost($url, $data = array()) {
		$c = curl_init(); 
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($c, CURLOPT_URL, $url); 
		curl_setopt($c, CURLOPT_POST, true); 
		curl_setopt($c, CURLOPT_TIMEOUT, 5); 
		curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($data)); 	
		$ret = curl_exec($c); 
		curl_close($c); 
		return $ret;
	}
	
}