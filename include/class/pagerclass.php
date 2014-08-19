<?php
/**
 * Thu Nov 20 06:31:36 GMT 2008 06:31:36
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * pager extend class and function
 * ------------------------------------------------------------
 * include\class\pagerclass.php
 */
 
require_once ('Pager/Pager.php');
class pagerClass extends Pager {

        public $preImg = '';
        public $nextImg = '';

    /**
	 * @return void 
	 */
	public function __construct(){
		
	}

	/**
    * @return void 
    */
	public function __destruct(){
		
	}

	/**
	 * ajax pager link
	 *
	 * @param int $pageno
	 * @param int $totalCount
	 * @param int $perPage
	 * @param string $onclick
	 * @param int $dalta
	 * @return string
	 */
	public function getLinkAjax($pageno,$totalCount,$perPage,$onclick='', $dalta=15){
		$arrResult	=	null;
		
		$params = array(
		'perPage'    => $perPage,
		'totalItems' => $totalCount,
		'currentPage'=> $pageno,
		'delta'      => $dalta,
		'onclick'	 => $onclick ? "javascript:".$onclick." return false;" : '' ,
		'append'     => false,
		'urlVar'     => 'pageno',
		'path'		 => '#',
		'fileName'   => '%d',
		);
		
		$objPager = & Pager::factory($params);
		$arrResult['links'] 	= $objPager->getLinks();
		$arrResult['linksAll']	= "[ ".$objPager ->numItems() . " / " .$objPager ->numPages()." ] ". $arrResult['links']['all'];
		$arrResult['searchparam']	=  $strParam;
		$arrResult['pageno']	=  $pageno;
		$arrResult['perpage']	=  $perPage;
		$arrResult['has']		=  true;
		unset($objPager,$params);
		
		return $arrResult;
	}
	
	/**
	 * jump pager link
	 *
	 * @param int $pageno
	 * @param int $totalCount
	 * @param int $perPage
	 * @param string $urlVar
	 * @param int $dalta
	 * @return string
	 */
	public function getLink($pageno, $totalCount, $perPage,$urlVar='', $dalta=20){
		$arrResult	=	null;
		
		$params = array(
		'perPage'    => $perPage,
		'totalItems' => $totalCount,
		'currentPage'=> $pageno,
		'delta'      => $dalta,
		'append'     => true,
		'urlVar'     => $urlVar,
		'fileName'   => '%d',
		
		'firstPagePre'	=>	"",
		'firstPageText'	=>	"&lt;&lt;Start",
		'firstPagePost'	=>	"",
		'lastPagePre'	=>	"",
		'lastPageText'	=>	"End&gt;&gt;",
		'lastPagePost'	=>	"",
		'prevImg'	=>	$this->preImg ? $this->preImg : "&lt;Prev",
		'nextImg'	=>	$this->nextImg ? $this->nextImg : "Next&gt;",
		);
		
		$objPager = & Pager::factory($params);
		$arrResult['links'] 	= $objPager->getLinks();
		$arrResult['linksAll']	= "[ ".$objPager ->numItems() . " / " .$objPager ->numPages()." ] ". $arrResult['links']['first'].$arrResult['links']['back']."&nbsp;". $arrResult['links']['pages']."&nbsp;".$arrResult['links']['next']."&nbsp;".$arrResult['links']['last'];
		$arrResult['linksAllFront']	=$arrResult['links']['first'].$arrResult['links']['back']."&nbsp;". $arrResult['links']['pages']."&nbsp;".$arrResult['links']['next']. "&nbsp;".$arrResult['links']['last'];
		$arrResult['searchparam']	=  $strParam;
		$arrResult['pageno']	=  $pageno;
		$arrResult['perpage']	=  $perPage;
		$arrResult['has']		=  true;
		unset($objPager,$params);
		
		return $arrResult;
	}
}

?>