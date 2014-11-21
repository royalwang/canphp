<?php
class dwPage {
	    
    public $rollPage = 5;			// 分页栏每页显示的页数    
    public $parameter  ;			// 页数跳转时要带的参数    
    public $listRows = 20;			// 默认列表每页显示行数    
    public $firstRow	;			// 起始行数    
	public $tag = '&nbsp;';			// 分隔符
	public $theme_key = 'left';		// 选择css样式，样式范围对应下面的$theme属性。
	
	public $showGoform = false;  	// 是否显示跳转框
	public $showRounded = false; 	// 边框是否显示为圆角效果
	public $showTotalRows = false;	// 是否显示记录总数
	
    protected $totalPages  ;		// 分页总页面数    
    protected $totalRows  ;			// 总行数
    protected $nowPage    ;			// 当前页数
    protected $coolPages   ;		// 分页的栏的总页数
    protected $varPage;				// 默认分页变量名
	private $prefix;				// 链接前缀
	private $suffix;				// 链接后缀
	// 分页显示定制
    protected $config =	array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>'');
	private $theme = array(
		'left' => array('theme'=>'%upPage% %first% %linkPage% %end% %downPage%'),
		'center' => array('theme'=>'%upPage% %first% %linkPage% %end% %downPage%'),
		'right' => array('theme'=>'%upPage% %first% %linkPage% %end% %downPage%'),
		'default' => array('theme'=>'%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%'),
	);

    /**
     * @param Int $totalRows  总的记录数
     * @param Int $listRows  每页显示记录数
     * @param String $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$parameter='',$theme='left',$varPage='p') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->varPage = $varPage ;
        if(!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = $this->_getNowPage();
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
		$this->_initConfig($theme);
    }

    /*
	 * 外部调用设置config的值
	 */
	public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }
	
	/*
	 * 根据选择的类型初始化设置config
	 */
	private function _initConfig($theme='left'){
		//
		$this->theme_key = !empty($theme) && isset($this->theme[$theme]) ? $theme : 'left';
		$this->config['theme'] = $this->theme[$this->theme_key]['theme'];
		$this->tag = '';
		switch($this->theme_key){
			case 'right':
				$this->showGoform = true;
				break;	
			case 'center':
				$this->showTotalRows = true;
				break;
			case 'default':
				$this->showTotalRows = true;
				break;
		}
		if(  $this->theme_key !='default' ){
			$this->config['last'] = $this->totalPages;
			$this->config['first'] = 1;
		}
	}

    /**
     * 分页显示输出
     */
    public function show() {
        if(0 == $this->totalRows) return '';
		if($this->listRows >= $this->totalRows ) return '';
        $p = $this->varPage;
        $nowCoolPage = ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href='".$url."&".$p."=$upRow'>".$this->config['prev']."</a>";
        }else{
            $upPage="<a href='#' onclick='return false;' >".$this->config['prev']."</a>";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a href='".$url."&".$p."=$downRow'>".$this->config['next']."</a>";
        }else{
            $downPage="<a href='#' onclick='return false;' >".$this->config['next']."</a>";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<a href='".$url."&".$p."=$preRow' >上".$this->rollPage."页</a>";
            $theFirst = "<a href='".$url."&".$p."=1' >".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='".$url."&".$p."=$nextRow' >下".$this->rollPage."页</a>";
            $theEnd = "<a href='".$url."&".$p."=$theEndRow' >".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
		//计算启始页
		$page_start = ($nowCoolPage-1)*$this->rollPage;
		if( ($page_start+floor($this->rollPage/2))>=$this->totalPages ){
		    $page_start = $this->totalPages-$this->rollPage;
		}
		//echo $page_start;exit;
		if( $page_start<0 ) $page_start=0;
        for($i=1;$i<=$this->rollPage;$i++){
            $page=$page_start+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= $this->tag."<a href='".$url."&".$p."=$page'>".$this->tag.$page.$this->tag."</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= $this->tag."<a href='#' class='current'>".$page."</a>";
                }
            }
        }
        $pageStr	 =	 str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        return $this->_addPrefix().$pageStr.$this->_addSuffix($url);
    }
	
	private function _addPrefix(){
		$this->prefix = '<!-- 分页{ --><div class="'.$this->theme_key.$this->_addRounded().'"><div class="mod-page">';
		if( $this->showTotalRows === true ) $this->prefix .= '<span>共'.$this->totalRows.'条/'.$this->totalPages.'页</span>';
		return $this->prefix;
	}
	
	private function _addSuffix($url){
		if ( $this->showGoform ===true){
			$this->suffix = ' <ins>
					<form action="'.$url.'" method="post" >
						<span>共'.$this->totalPages.'页 到第</span>
						<input type="text" id="jumpto" name="jumpto" />
						<span>页</span>
						<button type="submit">确定</button>
					</form>
				</ins>';	
		}
		return $this->suffix.'</div></div><!-- }分页 -->';
	}
	
	private function _addRounded(){
		return $this->showRounded===true ? ' rounded '  : '';	
	}
	
	private function _getNowPage(){
		if( !empty($_GET[$this->varPage]) ){
			return 	(int)$_GET[$this->varPage];
		}elseif( isset($_POST['jumpto']) && !empty($_POST['jumpto']) ){
			return 	(int)$_POST['jumpto'];
		}else{
			return 1;
		}
	}

}