<?php
//if(!defined('STORAGE_TEST'))define('STORAGE_TEST', TRUE);
class dwStorage
{
	// 文件服务器集群地址
	private $_clusters = array();
	// 当前能用的文件服务器地址
	private $_server_addr = '';
	
	// 链接重试次数
	private $_try_connect_max = 10;

	// 临时目录
	private $_tmp_dir = '/tmp';
	
	// 默认组名
	private $_group_name;
	
	// 本地测试
	private $_local_dir = '';
	private $_local_url = '';
	
	// 远程配置
	private $_remote_url  = '';
	
	// 远程COOKIE文件
	private $_remote_cookie;
	
	public function __construct(){
		$vars = get_class_vars(__CLASS__);
		if( isset($GLOBALS['storage']) ){
			$params = $GLOBALS['storage'];
			foreach($vars as $var => $v){
				if(key_exists($var, $params)){
					$this->{$var} = $params[$var];
				}
			}
		}else{
			Global $storage;
			foreach($vars as $var){
				if(key_exists('_'.$var, $storage))$this->{'_'.$var} = $storage['_'.$var];
			}
		}
		if(!STORAGE_TEST)$this->_connect();
	}

	/** 
	 * 上传本地文件
	 * @param  $localFile 本地文件路径
	 * @param  $fileExt   网络文件扩展名
	 * @param  $path      远程路径信息
	 */
	public function uploadFile($localFile, $fileExt = '', $path = null)
	{
		if(STORAGE_TEST){
			if(null != $path){
				$filename = basename($path);
				$filedir = $this->_local_dir.'/'.$this->_group_name.'/'.dirname($path);
				$file_id = $path;
			}else{
				$filename = md5(time().mt_rand(200, 300)).'.'. $fileExt;
				$filedir  = $this->_local_dir.'/'.$this->_group_name;
				$file_id = $filename;
			}
			$this->__mkdirs($filedir);
			copy($localFile, $filedir.'/'.$filename);
		}else{
			$i = 0;
			do{
				$file_id = $this->_post($this->_server_addr, array(
					'op'=>'upload', 
					'filedata' => '@'.$localFile,
					'fileext'  => $fileExt,
					'path' => $path,
					'group_name' => $this->_group_name,
				));
				$i++;
			}while($file_id > 0 || $i > 10);
		}
		return array(
			'file_id' => $file_id,
			'file_url' => $this->getUrl($file_id),
		);
	}

	/**
	 * 上传变量数据
	 * @param  $var       本地变量内容
	 * @param  $fileExt   网络文件扩展名
	 * @param  $path      远程路径信息
	 */
	public function uploadVar($var, $fileExt = '', $path = null)
	{
		$filename = md5(time().mt_rand(200, 300));
		$localFile = file_put_contents($this->_tmp_dir.'/'.$filename, $var);
		return $this->uploadFile($localFile, $fileExt, $path);
	}
	
	/**
	 * 删除网络文件
	 * @param  $remoteFile 远程文件名称
	 */
	public function deleteFile($remoteFile)
	{
		if(STORAGE_TEST){
			$file=$this->_local_dir.'/'.$this->_group_name.'/'.$remoteFile;
			@unlink($file);
		}else{
			$this->_post($this->_server_addr, array(
				'op'=>'delete', 
				'file_id' => $remoteFile,
				'group_name' => $this->_group_name,
			));
		}
	}
	
	/**
	 * 从网络生成本地文件
	 * @param  $remoteFile 远程文件名称
	 * @param  $localFile  本地待生成文件名
	 */
	public function downToFile($remoteFile, $localFile)
	{
		file_put_contents($localFile, $this->downToVar($remoteFile));
	}

	/**
	 * 从网络直接的读取文件内容
	 * @param  $remoteFile 远程文件名称
	 */
	public function downToVar($remoteFile)
	{
		if(STORAGE_TEST){
			return file_get_contents($this->_local_dir.'/'.$this->_group_name.'/'.$remoteFile);
		}else{
			return $this->_post($this->_server_addr, array(
				'op'=>'download', 
				'file_id' => $remoteFile,
				'group_name' => $this->_group_name,
			));
		}
	}

	/**
	 * 获取文件URL访问路径
	 * @param  $remoteFile 远程文件名称
	 */
	public function getUrl($remoteFile)
	{
		if(STORAGE_TEST){
			$url = $this->_local_url;
		}else{
			$url = $this->_remote_url;
		}
		if(substr($url, 0, 7) != 'http://')$url = 'http://'.$url;
		$url .= '/'.$this->_group_name.'/'.$remoteFile;
		return $url;
	}

	private function _connect(){
		foreach($this->_clusters as $clusters){
			$i = 0;
			do{
				if ($this->_ping($clusters)){
					$this->_server_addr = $clusters; 
					return;
				}
				$i++;
			}while($i < $this->try_connect_max);
		}
		throw new Exception('无法链接文件服务器集群');
	}
	private function _ping($url){
		return $this->_post($url, array(
			'op' => 'ping',
		));
	}

	private function _post($url, $post = array()){
		$c = curl_init(); 
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($c, CURLOPT_URL, $url); 
		curl_setopt($c, CURLOPT_POST, true); 
		curl_setopt($c, CURLOPT_TIMEOUT, 900); 
		//$this->_remote_cookie or curl_setopt($c, CURLOPT_COOKIEFILE, $this->_remote_cookie); 
		//$this->_remote_cookie or curl_setopt($c, CURLOPT_COOKIEJAR, $this->_remote_cookie); 
		curl_setopt($c, CURLOPT_POSTFIELDS, $post); 
		$data = curl_exec($c); 
		curl_close($c); 
		return $data;
	}
	
	private function __mkdirs($dir, $mode = 0777)
	{
		if (!is_dir($dir)) {
			$this->__mkdirs(dirname($dir), $mode);
			return @mkdir($dir, $mode);
		}
		return true;
	}
}
