<?php 
/**
 * 单文件上传类
 * 
 * 使用方法：
 * 
include('dwUpload.php');
$upload=new dwUpload;
//上传单个文件
$upload->uploadFile($_FILES['filename'],array('gif','doc'));
//上传多个文件
$upload->uploadFile($_FILES['filename'],array('gif','doc'));
 */

	class dwUpload{
			/***
			*$allowExt  外部用限定文件类型array('gif','jpg')
			*$maxSize  初始设定允许文件大小为2M,以字节计算
			*
			*/
			public function uploadFile($FILES,$allowExt=array(),$maxSize=2097152){
				$length = count($FILES['name']);
				for($i=0;$i<$length;$i++){
					foreach($FILES as $key=>$data){
						if($length == 1)
							$file[$i][$key] = $data;
						else
							$file[$i][$key] = $data[$i];
					}
					$tempArr =pathinfo($file[$i]['name']);
					$file[$i]['ext'] = strtolower($tempArr["extension"]);
					if(!empty($allowExt) && (!in_array($file[$i]['ext'],$allowExt))) $file[$i]['error'] = 8;
					if($maxSize && $file[$i]['size'] > $maxSize) $file[$i]['error'] = 2;
					if($file[$i]['ext'] == 'gif'){
						$gif= file_get_contents($file[$i]["tmp_name"]);
						$rs = preg_match('/<\/?(script){1}>/i',$gif);
						if($rs) $file[$i]['error'] = 9;
					}
				}
				return $file;
			}
	}
?>