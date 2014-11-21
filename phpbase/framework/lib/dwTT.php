<?php
/**
 *  TokyoTyrant操作
 *
 * 使用方法
include('dwTT.php');
$tt = new dwTT('test');
$tt->set('word', 'hello world');
echo $tt->get('word');
$tt->delete('word');
echo $tt->get('word');
 */
class dwTT extends dwaeObject
{
	private $tt = null;
	private $domain = null;
    function __construct($domain){
        try{
            $this->tt = new TokyoTyrant(DW_TT_HOST, DW_TT_PORT);
        }catch(TokyoTyrantException $e){
            $this->tt = false;
        }
		$this->domain = $domain;
    }

    function set($key, $var){
		if(!$this->tt)return;
		return $this->tt->put($this->domain.'_'.$key, $var);
    }
	
	function get($key){
		if(!$this->tt)return;
		return $this->tt->get($this->domain.'_'.$key);
	}
	
	function delete($key){
		if(!$this->tt)return;
		return $this->tt->out($this->domain.'_'.$key);
	}
}
?>