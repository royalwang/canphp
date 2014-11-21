<?php
if(!defined('STORAGE_TEST'))define('STORAGE_TEST', TRUE);
class dwFile
{
	// 文件服务器集群地址
	private $_clusters = array();
	// 当前能用的文件服务器地址
	private $_server_addr = '';
	
	// 链接重试次数
	private $_try_connect_max = 3;

	// 临时目录
	private $_tmp_dir = '/tmp';
	
	// 默认组名
	private $_group_name;
	
	// 本地测试
	private $_local_dir = '';
	private $_local_url = '';
	
	// 远程配置
	private $_remote_url  = '';
	
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
			}while( (!$file_id || preg_match('#<| #',$file_id))  && $i < $this->_try_connect_max);
		}
		if(!$file_id || preg_match('#<| #',$file_id)) return false;
		
		return array(
			'file_id' => $file_id,
			'file_url' => $this->getUrl($file_id),
		);
	}
	
	public function deleteFile($remoteFileId)
	{
		if(STORAGE_TEST){
			$file=$this->_local_dir.'/'.$this->_group_name.'/'.$remoteFileId;
			@unlink($file);
		}else{
			$this->_post($this->_server_addr, array(
				'op'=>'delete', 
				'file_id' => $remoteFileId,
				'group_name' => $this->_group_name,
			));
		}
	}
	
	public function downToFile($remoteFileId, $localFileAddr)
	{
		if(STORAGE_TEST){
			return copy($this->_local_dir.'/'.$this->_group_name.'/'.$remoteFileId , $localFileAddr);
		}else{
			$filecontent = $this->_post($this->_server_addr, array(
					'op'=>'download',
					'file_id' => $remoteFileId,
					'group_name' => $this->_group_name,
			));
			file_put_contents($localFileAddr, $filecontent);
			return !empty($filecontent);
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
		return $url.'/'.$remoteFile;
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
                        'group_name' => $this->_group_name,
		));
	}

	private function _post($url, $post = array()){
		$c = curl_init(); 
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($c, CURLOPT_URL, $url); 
		curl_setopt($c, CURLOPT_POST, true); 
		curl_setopt($c, CURLOPT_TIMEOUT, 15); 
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
