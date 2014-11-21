<?php
class Test extends Model{
	protected $table_name = 'test';
	
	public function querySql(){;
		echo 'querySql:<br/>';
		$res = $this->query(' SELECT * from test where name=:name AND contents=:contents LIMIT 1',array(':name'=>'测试名字2',':contents'=>'测试内容2'));
		var_dump($res);	
		echo '<br/><br/>';
	}
	
	public function executeSql(){
		echo 'executeSql:<br/>';
		$contents= '测试~!@#$%^&*()_+==-=!!FSETE$E#@$%$^%%&^*&^&*^&*^&*^&UHFGDGDGEAWEAWEAWSDWDWDWVVEE<>?:.jlkiusfuaiufieif	89894500op[mm nhjhyuyyuryut内容3';
		for( $i=0;$i<1000;$i++){
			$contents .= dechex(mt_rand(1,100000));	
		}
		//$res = $this->execute('INSERT INTO test (name,contents,created) VALUES (:name,:contents,:created)',array(":contents"=>$contents,":name"=>'测试标题3333',":created"=>time()));
		$res = $this->execute('UPDATE test set contents=:contents,created=:created WHERE name=:name',array(':contents'=>'测试内容fsefsefsfesf;;;2',':name'=>'测试标题',':created'=>time()));
		//$res = $this->execute('DELETE FROM test  WHERE contents=:contents AND name=:name',array(':contents'=>'测试内!!#@$""""""#@$#%^**LUL<?sFSFS	~!$RWT容2',':name'=>'测试名字1'));
		var_dump($res);	
		echo '<br/><br/>';
	}
	
	public function create(){
		$res = $this->execute('CREATE TABLE IF NOT EXISTS `test` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `contents` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `created` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');	
	}
	
	public function insertData(){
		echo 'insertData:<br/>';
		echo $this->insert(array('name'=>'测试名字'.mt_rand(1,5),'contents'=>'测试内!!#@$""""""#@$#%^**LUL<?sFSFS	~!$RWT?>容'.mt_rand(1,5),'created'=>time()));
		echo '<br/><br/>';
	}
	
	public function selectData(){
		echo 'selectData:<br/>';
		$res = $this->select(array('name'=>'测试名字2'),'*','id desc','2');
		var_dump($res);
		echo '<br/><br/>';
	}
	
	public function findData(){
		echo 'findData:<br/>';
		$res = $this->find(array('name'=>'测试名字2'),'name,contents,created','id DESC');
		var_dump($res);
		echo '<br/><br/>';
	}
	
	public function updateData(){
		echo 'updateData:<br/>';
		$res = $this->update(array('id'=>'10'),array('name'=>'测试名字4','contents'=>'测试内容444','created'=>time()));
		var_dump($res);
		echo '<br/><br/>';
	}
	
	public function deleteData(){
		echo 'updateData:<br/>';
		$res = $this->delete(array('name'=>'测试名字4'));
		var_dump($res);
		echo '<br/><br/>';
	}
	
	
}
?>