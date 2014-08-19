<?php
@session_start();
include_once ('../include/smartyconfig.php');
require_once 'Pager/Pager.php';
$params = array(
	'perPage'    => 15,
    'delta'      => 10,
    'totalItems' => 100,
    'currentPage'=> 5,
	'append'    => false,
    'urlVar'    => 'pageno',
	'path'      => 'javascript:void(0);',
    'fileName'  => '%d',  //Pager replaces "%d" with page number...
	'onclick'	 => 'alert(\'%d\');return false;',
);
$pager = & Pager::factory($params);
$data  = $pager->getPageData();
$links = $pager->getLinks();
//$links is an ordered+associative array with 'back'/'pages'/'next'/'first'/'last'/'all' links.
//NB: $links['all'] is the same as $pager->links;

//echo links to other pages:
echo '<br><br>'.$links['all'];