<?php
/*  
 * xml和array互转类
 * 
 * 使用方法；
 * 
 include('dwXmlArray.php');
$_dwXmlArray=new dwXmlArray();
//xml转为数组
$arrayData=$_dwXmlArray->xmlToArray("example.xml");
var_export($arrayData);
//数组转为xml
$arr=array ( 'root' => array ( 'node1' => array ( 'value' => 'Some text', ), 'node2a' => array ( 'node2b' => array ( 'node2c' => array ( 'value' => 'Some text', ), ), ), ), );
$xmlData=$_dwXmlArray->arrayToXml($arr);
echo $xmlData;
 * 
 * */
class dwXmlArray
{
	private $_struct;
	private $_count;
	private $xml;
	
	public function __construct() {
		$this->_struct = array();
	}
	
	public function xmlToArray($file)
	{
		$data=file_get_contents($file) or die("Can't open file $file for reading!");
		$parser = xml_parser_create('utf-8');
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, $data, $this->_struct);
		xml_parser_free($parser);
		$this->_count = count($this->_struct);
		return $this->getArray();
	}
	
	public function getArray() {
		$i = 0;
        $tree = array();
        $tree = $this->addNode(
            $tree,
            $this->_struct[$i]['tag'],
            (isset($this->_struct[$i]['value'])) ? $this->_struct[$i]['value'] : '',
            (isset($this->_struct[$i]['attributes'])) ? $this->_struct[$i]['attributes'] : '',
            $this->getChild($i)
        );
        unset($this->_struct);
        return ($tree);         
	}
		
	private function getChild(&$i) {
		// contain node data
		$children = array();
		// loop
		while (++$i < $this->_count) {
			// node tag name
			$tagname = $this->_struct[$i]['tag'];
			$value = isset($this->_struct[$i]['value']) ? $this->_struct[$i]['value'] : '';
			$attributes = isset($this->_struct[$i]['attributes']) ? $this->_struct[$i]['attributes'] : '';
			switch ($this->_struct[$i]['type']) {
				case 'open':
					// node has more children
					$child = $this->getChild($i);
					// append the children data to the current node
					$children = $this->addNode($children, $tagname, $value, $attributes, $child);
					break;
				case 'complete':
					// at end of current branch
					$children = $this->addNode($children, $tagname, $value, $attributes);
					break;
				case 'cdata':
					// node has CDATA after one of it's children
					$children['value'] .= $value;
					break;
				case 'close':
					// end of node, return collected data
					return $children;
					break;
			}
	
		}
	}
	
	private function addNode($target, $key, $value = '', $attributes = '', $child = '') {
		if (!isset($target[$key]['value']) && !isset($target[$key][0])) {
			if ($child != '') {
				$target[$key] = $child;
			}
			if ($attributes != '') {
				foreach ($attributes as $k => $v) {
					$target[$key][$k] = $v;
				}
			}
			if ($value != '') {
				$target[$key]['value'] = $value;
			}			
		}else {
			if (!isset($target[$key][0])) {
				$oldvalue = $target[$key];
				$target[$key] = array();
				$target[$key][0] = $oldvalue;
				$index = 1;
			} else {
				$index = count($target[$key]);
			}
			if ($child != '') {
				$target[$key][$index] = $child;
			}
			if ($attributes != '') {
				foreach ($attributes as $k => $v) {
					$target[$key][$index][$k] = $v;
				}
			}
			if ($value != '') {
				$target[$key][$index]['value'] = $value;
			}			
		}
		return $target;
	}	
	
	/* ------------------------数组转为XML------------------------------------ */
	public function arrayToXml($array,$encoding='utf-8') {
		$this->xml='<?xml version="1.0" encoding="'.$encoding.'"?>';
		$this->xml.=$this->_array2xml($array);
		return $this->getXml();
	}
	
	public function getXml() {
		return $this->xml;
	}
	
	private function _array2xml($array)
		{
			$xml='';
			foreach($array as $key=>$val){
				if(is_numeric($key)){
					$key="item";
				}else{
					//去掉空格，只取空格之前文字为key
					list($key,)=explode(' ',$key);
				}
				$xml.="<$key>";
				$xml.=is_array($val)?$this->_array2xml($val):$val;
				//去掉空格，只取空格之前文字为key
				list($key,)=explode(' ',$key);
				$xml.="</$key>";
			}
			return $xml;
		}	
}
?>