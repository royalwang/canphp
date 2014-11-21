<?php
//php性能分析
class dwXhprof{
	protected $app_id='';
	protected $is_remote = true;
	protected $remote_url='http://xhprof.duowan.com/xhprof_html/api_save.php';
	protected $remote_ip = '183.61.6.98';
	
	//性能分析开始
	public function enable($app_id, $xhprof=0, $is_remote=true){
		if ( function_exists('xhprof_enable') && $app_id && mt_rand(1, $xhprof) == 1 ){
			$this->app_id = $app_id;
			$this->is_remote = $is_remote;
			xhprof_enable();
			xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
			register_shutdown_function(array($this, 'saveData'));
		} 	
	}
	
	public function saveData(){
		$xhprof_data = xhprof_disable();  
		$xhprof_data = serialize($xhprof_data);
		$file_name = uniqid().'.'.$this->app_id.'.xhprof';
		if($this->is_remote){
			$data = array('app_id'=>$this->app_id, 'file_name'=>$file_name, 'xhprof_data'=>$xhprof_data);
			$this->curlPost($this->remote_url, $data, $this->remote_ip);
		}else{
			$file = ini_get("xhprof.output_dir").'/'.$file_name;
			file_put_contents($file, $xhprof_data);		
		}		
	}
	
	protected function curlPost($url, $data, $ip=''){		
		$urlArr = parse_url($url);
		$domain = $urlArr['host'];
		if($ip){
			$url = str_replace("http://".$domain, "http://".$ip, $url);
		}
		$ch = curl_init(); 
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_POST => true,
			CURLOPT_NOSIGNAL=>true,
			CURLOPT_CONNECTTIMEOUT_MS => 200,
			CURLOPT_TIMEOUT_MS => 2000,
			CURLOPT_HTTPHEADER => array("Host: " . $domain),
			CURLOPT_POSTFIELDS => http_build_query($data),
		)); 	
		$ret = curl_exec($ch); 
		curl_close($ch); 		
		return $ret;	
	}
}