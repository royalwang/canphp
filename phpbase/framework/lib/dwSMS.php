<?php
/*
功能：手机短信发送
用法：
	单条发送：obj('dwSMS')->send('手机号码', '测试内容');
	群发：obj('dwSMS')->send(array('手机1','手机2','手机3'), '测试内容');

接口返回值 : 
   1 短信发送成功
　　-1 mac值验证错误
   -2 参数信息不完整
   -3 信息入库失败
   -4 发送接口失败
   -5 项目编号错误
   -6 手机号码错误
   -100 IP不在白名单内
   -101 参数错误
   -102 手机号码为空
*/
class dwSMS{
	private $_smsUrl = 'http://webapi.duowan.com/api_sms.php';
		
	//发送短信,$phone，可为字符串或数组
	public function send($phone = "", $content = ""){	
		if( empty($phone) || empty($content) ) return 0;
		
		$data['phone'] = serialize($phone);
		$data['content'] = $content;
		
		return $this->curlPost($this->_smsUrl, $data);
	}
	
	//通过curl post数据
	protected function curlPost($url, $data = array()) {
		$c = curl_init(); 
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($c, CURLOPT_URL, $url); 
		curl_setopt($c, CURLOPT_POST, true); 
		curl_setopt($c, CURLOPT_TIMEOUT, 30); 
		curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($data)); 	
		$ret = curl_exec($c); 
		curl_close($c); 
		return $ret;
	}
	
}