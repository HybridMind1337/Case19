<?php
/*
	Библиотека за странициране
*/

function pagination($results, $properties = array()) {
	$defaultProperties = array(
		'get_vars'	=> array(),
		'per_page' 	=> 15,
		'per_side'	=> 4,
		'get_name'	=> 'page'
	);
	
	foreach($defaultProperties as $name => $default) { $properties[$name] = (isset($properties[$name])) ? $properties[$name] : $default; }
	
	foreach($properties['get_vars'] as $name => $value) {
		if (isset($_GET[$name]) && $name != $properties['get_name']) {
			$GETItems[] = $name.'='.$value;
		}
	}
	$l = (empty($GETItems)) ? '?'.$properties['get_name'].'=' : '?'.implode('&', $GETItems).'&'.$properties['get_name'].'=';
	
	$totalPages		= ceil($results / $properties['per_page']);
	$currentPage 	= (isset($_GET[$properties['get_name']]) && $_GET[$properties['get_name']] > 1) ? $_GET[$properties['get_name']] : 1;
	$currentPage 	= ($currentPage > $totalPages) ? $totalPages : $currentPage;
	
	$previousPage 	= $currentPage - 1;
	$nextPage 		= $currentPage + 1;
	
	// calculate which pages to show
	if ($totalPages <= ($properties['per_side'] * 2) + 1) {
		$loopStart = 1;
		$loopRange = $totalPages;
	} else {
		$loopStart = $currentPage - $properties['per_side'];
		$loopRange = $currentPage + $properties['per_side'];
		
		$loopStart = ($loopStart < 1) ? 1 : $loopStart;
		while ($loopRange - $loopStart < $properties['per_side'] * 2) { $loopRange++; }
		
		$loopRange = ($loopRange > $totalPages) ? $totalPages : $loopRange;
		while ($loopRange - $loopStart < $properties['per_side'] * 2) { $loopStart--; }
	}

	// start placing data to output
	$output = '';
	$output .= '
	<div class="text-center">
	<a class="btn btn-default btn-block toggle-pagination"><i class="glyphicon glyphicon-plus"></i> Toggle Pagination</a>
	<ul class="pagination pagination-responsive">
	 ';
	
	
	// first and previous page
	if ($currentPage != 1) {
		$output	.= '<li class="page-item"><a class="page-link" href=\''.$l.'1\'>&#171;</a></li>';
		$output .= '<li class="page-item"><a class="page-link" href=\''.$l.$previousPage.'\'>‹</a></li>';
	} else {
		$output .= '<li class="page-item"><span class=\'inactive\'>&#171;</span></li>';
		$output .= '<li class="page-item"><span class=\'inactive\'>‹</span></li>';
	}
	
	
	// add the pages
	for ($p = $loopStart; $p <= $loopRange; $p++) {
		if ($p != $currentPage) {
			$output .= '<li class="page-item"><a class="page-link" href=\''.$l.$p.'\'>'.$p.'</a></li>';
		} else {
			$output .= '<li class="page-item"><a class="page-link" href="#">'.$p.'</a></li>';
		}
	}
	// next and last page
	if ($currentPage != $totalPages) {
		$output .= '<li class="page-item"><a class="page-link" href=\''.$l.$nextPage.'\' class=\'active\'>›</a></li>';
		$output .= '<li class="page-item"><a class="page-link" href=\''.$l.$totalPages.'\' class=\'active\'>&#187;</a></li>';
	} else {
		$output .= '<li class="page-item"><span class=\'inactive\'>›</span></li>';
		$output .= '<li class="page-item"><span class=\'inactive\'>&#187;</span></li>';
	}
	
	$output .= '</ul>
   </div>';
	// end of output
	
	return array(
		'limit' => array(
			'first' 	=> $previousPage * $properties['per_page'],
			'second' 	=> $properties['per_page']
		),
		
		'output' => $output
	);
}