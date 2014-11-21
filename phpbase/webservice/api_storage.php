<?php
error_reporting(0);
define('STORAGE_BASEPATH', '/data1/webapps/www.dwstatic.com');

$allows = array( // the IPs allow to access the remoteAction
	//'127.0.0.1', '121.9.221.*', '113.108.232.34', '192.168.0.*', '183.60.177.228',
	//'183.61.6.*','183.60.177.*', //'210.21.125.*','183.61.6.11','183.61.6.12,','183.61.6.13','183.61.6.49','183.60.177.*',
);

class remoteAction
{
    private $_stor_path = '';
    private $_group_name = '';
    
    function __construct(){
            Global $allows;
            $ip = $_SERVER['REMOTE_ADDR'];
            $passed = 0;
            foreach($allows as $allowip)if(preg_match('/'.str_replace('.','\.',$allowip).'/', $ip))$passed = 1;
            //if(!$passed){echo $ip;exit(0);}
            
            if(isset($_REQUEST['group_name'])){
                $this->_group_name = $_REQUEST['group_name'];
            }else{
                $this->_group_name = 'group1';
            }
            $this->_stor_path = STORAGE_BASEPATH.'/'.$this->_group_name;
            
    }
    function ping(){
            return 1;
    }
    function upload(){
            if($_FILES['filedata']['size'] > 0){
                    if(isset($_REQUEST['path']) && !empty($_REQUEST['path'])){
                            $filename = basename($_REQUEST['path']);
                            $dir = trim(dirname($_REQUEST['path']), '/');
                    }else{
                            $fileext = str_replace('.', '', $_REQUEST['fileext']);
                            $filename = md5(time().mt_rand()*99999).mt_rand(1,10000).'.'.$fileext;
                            $dir = strtoupper(substr($filename, 0, 2)).'/'.strtoupper(substr($filename, 2, 2));
                    }
                    $this->__mkdirs($this->_stor_path.'/'.$dir);
                    file_put_contents($this->_stor_path.'/'.$dir.'/'.$filename, file_get_contents($_FILES['filedata']['tmp_name']));
                    return $this->_group_name.'/'.$dir.'/'.$filename;
            }else{
                    return false;
            }
    }
    function download(){
            $file_id = substr($_REQUEST['file_id'], strlen($this->_group_name)+1);
            return file_get_contents($this->_stor_path.'/'.$file_id);
    }
    function delete(){
            $file_id = substr($_REQUEST['file_id'], strlen($this->_group_name)+1);
            $file = $this->_stor_path.'/'.$file_id;
            @unlink($file);
    }
    function __mkdirs($dir, $mode = 0777)
    {
            if (!is_dir($dir)) {
                    $this->__mkdirs(dirname($dir), $mode);
                    return @mkdir($dir, $mode);
            }
            return true;
    }
}
$rq = new remoteAction;
if(isset($_REQUEST['op']) && method_exists($rq, $_REQUEST['op'])){
	echo $rq->{$_REQUEST['op']}();
}else{
	exit(0);
}
