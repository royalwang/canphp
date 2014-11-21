<?php 
require(dirname(__FILE__).'/config.php');

$udbObj = new udb;
if( 'checklogin' == $_GET['do']){
	echo serialize($udbObj->checklogin($_GET['loginuser'], $_GET['loginpass'], $_GET['ip'],$_GET['user_agent']));
}elseif( 'checkonline' == $_GET['do'] ){
	echo $udbObj->checkonline($_GET['username'], $_GET['password'], $_GET['osinfo'], $_GET['user_agent']);
}else{
	echo '-2';
}

class udb{
	var $ukey = array();
	var $ukey_path = null;
	
	public function __construct() {
		$this->ukey_path = "/tmp/cache_ukey.php";
	}
	
	public function checklogin($loginuser, $loginpass, $IP, $user_agent){
		//$loginpass=sha1($loginpass);
		$result = @file_get_contents("http://udb.duowan.com/message/login?username={$loginuser}&passwd={$loginpass}&encrypt=1&userip={$IP}");
		if(1 == $result){
			return array(
				'status' => 'succeed',
				'cookie' => $this->logincookie($loginuser, $user_agent),
			);
		}else{
			return array(
				'status' => 'failed',
				'msg'    => $this->errormsg($result),
			);
		}
	}

	public function checkonline($username, $password, $osinfo, $user_agent){
		if(!$this->getKey())return false;
		
		if( strtolower($password)==sha1(urlencode($username).$this->ukey['key0']) && strtolower($osinfo)==sha1($user_agent.urlencode($username).$this->ukey['key0']) ) {
		    return '1';
		}elseif( strtolower($password)==sha1(urlencode($username).$this->ukey['key1']) && strtolower($osinfo)==sha1($user_agent.urlencode($username).$this->ukey['key1']) ){		
			return '2';
		}else{
			return '0';
		}
	}
	
	private function logincookie($loginuser, $user_agent, $time=0){
		$time=$time>0 ? $time+time() : 0;
		if(!$this->getKey())return false;
		return array(
			'osinfo' => strtoupper(sha1($user_agent.urlencode($loginuser).$this->ukey['key1'])),
			'passwd' => strtoupper(sha1(urlencode($loginuser).$this->ukey['key1'])),
		);
	}
	
	private function makeKey()
	{
		$contents = @file_get_contents("http://udb.duowan.com/message/getudbkey");
		if(empty($contents))return false;
		@list($key0, $key1, $cachetime) = explode(';',trim($contents));
		return file_put_contents($this->ukey_path, "<?php return array('key0'=>'{$key0}','key1'=>'{$key1}','cachetime'=>'{$cachetime}');?>");
	}

	private function getKey(){
		$ukey = file_exists($this->ukey_path) ? require($this->ukey_path) : null;
		if(empty($ukey) || time() > ($ukey['cachetime']/1000) || empty($ukey['key0']) || empty($ukey['key1']))
		{
			$keyhandle= $this->makeKey();
			if(!$keyhandle) return false;
			$ukey = require($this->ukey_path); 
		}
		$this->ukey = $ukey;
		return true;
	}
	
	private function errormsg($result){
		switch ($result) {
			case -1:
				return '没有访问权限';
			case -12:
				return '登录出错太多';
			case -2:
				return '用户不存在';
			case -3:
				return '密码错误';
			case -11:
				return '访问太频繁';
			case -12:
				return '登录出错太多';
			case -411:
				return 'userip不合法';
			default:
				return '未知错误';
		}
	}
}
