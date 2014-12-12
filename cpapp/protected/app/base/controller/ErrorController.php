<?php
namespace app\base\controller;
class ErrorController extends BaseController{
	
	public function error404($e=null){
		header('HTTP/1.1 404 Not Found'); 
		header("status: 404 Not Found");
		$this->error($e);
	}
	
	public function error($e=null){
		if( false!==stripos(get_class($e), 'Exception')  ){
			$this->errorMessage = $e->getMessage();
			$this->errorCode = $e->getCode();
			$this->errorFile = $e->getFile();
			$this->errorLine = $e->getLine();
			$this->errorLevel = $this->_level($this->errorCode);
			$this->trace = $e->getTrace();		
		}
				
		//关闭调试或者是线上版本，不显示详细错误
		if( false==config('DEBUG') || 'production'==config('ENV') ){
			$tpl = 'error_production';
			//记录错误日志
			
		}else{
			$tpl = 'error_development';
		}
		$this->display('app/base/view/'.$tpl);
	}
		
	//处理信息处理
	protected function _level($errorCode) {
		$LevelArr = array(	
			1=> '致命错误(E_ERROR)',
			2 => '警告(E_WARNING)',
			4 => '语法解析错误(E_PARSE)',  
			8 => '提示(E_NOTICE)',  
			16 => 'E_CORE_ERROR',  
			32 => 'E_CORE_WARNING',  
			64 => '编译错误(E_COMPILE_ERROR)', 
			128 => '编译警告(E_COMPILE_WARNING)',  
			256 => '致命错误(E_USER_ERROR)',  
			512 => '警告(E_USER_WARNING)', 
			1024 => '提示(E_USER_NOTICE)',  
			2047 => 'E_ALL', 
			2048 => 'E_STRICT',
			404  =>  '404错误',
			500  =>  '异常错误',
		);
		return isset( $LevelArr[$errorCode] ) ? $LevelArr[$errorCode]  : $errorCode;
	}
}