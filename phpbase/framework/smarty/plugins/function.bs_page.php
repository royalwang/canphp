<?php
//适用于bootstap的分页
function smarty_function_bs_page($params) {
	if( empty($params['page']) ) return '';

	$page = $params['page'];
	$p = empty($params['_p']) ? 'p' : $params['_p'];
	$route = $params['r'];
	unset($params['page'], $params['_p'], $params['r']);

	$page_str='<div class="pagination"><ul>';
	if($page['current_page']>1) {
		$params[$p] = $page['prev_page'];
		$page_str .= '<li><a href="' . url($route, $params) . '" rel="prev" title="上一页">上一页</a></li>';
		unset($params[$p]);
	}
	
	foreach($page['all_pages'] as $value) {
		if($value>1) $params[$p]=$value;
		if( $value == $page['current_page'] ) {
			$current = 'title="已经是当前页" class="current" ';
			$page_str .= '<li class="active"><a href="' . url($route, $params) . '" ' . $current . '>' . $value . '</a></li>';
		} else {
			$current = 'title="第'. $value .'页" ';
			$page_str .= '<li><a href="' . url($route, $params) . '" ' . $current . '>' . $value . '</a></li>';
		}
		
	}
	if($page['current_page']<$page['total_page']){
		$params[$p] = $page['next_page'];
		$page_str .= '<li><a href="' . url($route, $params) . '" rel="next" title="下一页">下一页</a></li>';	
	}

	return $page_str."</ul></div>";
}