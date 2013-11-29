<?php
class indexController extends adminController{
	protected $layout = 'layout';
	
	public function index(){
		//$this->list = model('demo')->select();
		$this->display();
	}
	
	public function add(){
		if( !$this->isPost() ){
			$this->display();
		}else{
			$data = $_POST;
			if( model('demo')->insert($data)){
				$this->alert('添加成功', url('index/index'));
			}else{
				$this->alert('添加失败');
			}		
		}
	}
	
	public function edit(){
		if( !$this->isPost() ){
			$id = intval($_GET['id']);
			$info = model('demo')->find( array('id'=>$id) );
			if( empty($info) ){
				$this->alert('该条数据不存在或者已被删除');
			}
			$this->info = $info;
			$this->display();
			
		}else{
			$id = intval($_POST['id']);
			$data = $_POST;
			if( model('demo')->update(array('id'=>$id), $data) ){
				$this->alert('修改成功', url('index/index'));
			}else{
				$this->alert('修改失败');
			}
		}
	}
	
	public function del(){
		$id = intval($_GET['id']);
		$info = model('demo')->find( array('id'=>$id) );
		if( empty($info) ){
			$this->alert('该条数据不存在或者已被删除');
		}
		if( model('demo')->delete( array('id'=>$id) ) ){
			$this->alert('删除成功', url('index/index'));
		}else{
			$this->alert('删除失败');
		}
	}
		
	public function config(){
		$this->display();
	}
}