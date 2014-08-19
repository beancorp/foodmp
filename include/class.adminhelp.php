<?php
/**
 * Wed Oct 08 21:18:33 GMT+08:00 2008 21:18:33
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * main class of admin
 * ------------------------------------------------------------
 * include\class.adminmain.php
 */

class adminHelp extends common {
	var $dbcon 	= 	null;
	var $table	=	'';
	var $smarty = 	null;
	var $lang	=	null;

	/**
	 * @return void 
	 */
	public function __construct()
	{
		$this -> dbcon  = &$GLOBALS['dbcon'];
		$this -> table	= &$GLOBALS['table'];
		$this -> smarty = &$GLOBALS['smarty'];
		$this -> lang	= &$GLOBALS['_LANG'];
	}

	/**
    * @return void 
    */
	public function __destruct(){
		unset($this->dbcon,$this -> table,$this->smarty,$this -> lang);
	}

	/**
	 * get CMS name of items
	 *
	 * @return array
	 */
	public function getHelpItemName(){
		$arrResult = null;

		$_query = "select id,title from ".$this->table."help order by title ASC";
		$this -> dbcon -> execute_query($_query);
		$arrTemp	=	$this -> dbcon ->fetch_records(true) ;
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp;
		}

		return $arrResult;
	}
	
	public function getHelpItemlist($keyword=""){
		$arrResult = null;
		$tempResult = null;
		$tmpkeyword = null;
		if(trim($keyword)!=""){
			$tmpkeyword = split(' ',$keyword);
			$tmpkeyword = array_unique($tmpkeyword); 

			if(!get_magic_quotes_gpc()){
				$keyword = addslashes($keyword);
			}
			$keywhere = " where title like '%{$keyword}%' ";
			$k = 0;
			foreach ($tmpkeyword as $pass){
				if($pass==""||strlen($pass)<3) continue;
				$k++;
				$pass = preg_replace('/[<|>|\/|\\\|\$|%]/','',$pass);
				$pass = preg_replace('/\'/','\\\'',$pass);
				$keywhere .= " or title like '%$pass%' or body like '%$pass%' ";
			}
		}
		$cquery = "select COUNT(*) from ".$this->table."help $keywhere order by title ASC";
		$this->dbcon->execute_query($cquery);
		$total = $this->dbcon->fetch_records(true);
		$total = $total[0]['COUNT(*)'];
		$pageSize = 15;
		$clsPage = new Page($total, $pageSize);
		$wheresql = $clsPage->get_limit();
			
		$_query = "select id,title from ".$this->table."help $keywhere order by title ASC $wheresql";

		$this -> dbcon -> execute_query($_query);
		$arrTemp	=	$this -> dbcon ->fetch_records(true) ;
		if (is_array($arrTemp)) {
			foreach ($arrTemp as $key=>$val){
				$tempResult[$key]['id'] = $val['id'];
				$tempResult[$key]['title'] = $val['title'];
				if(is_array($tmpkeyword)){
					$arySeach = array();
					$aryReplace = array();
					$arySeach[0] = '/('.preg_replace('/([\/|\\\|\(|\)|\*|\?|\.|\+|\=|\]|\[|\$|\{|\}|\||\^])/','\\\\\1',stripslashes($keyword)).')/i';
					$aryReplace[] = '<b>\1</b>';
					foreach ($tmpkeyword as $pass){
						if($pass==""||strlen($pass)<3) continue;
						$pass = preg_replace('/[<|>|\/|\\\|\(|\)|\*|\?|\.|\+|\=|\]|\[|\$|\{|\}|\||\^]/','',$pass);
						$arySeach[] = '/('.$pass.')/i';
						$aryReplace[] = '<b>\1</b>';
					}
					$tempResult[$key]['title'] = preg_replace($arySeach,$aryReplace,$tempResult[$key]['title']);
				}
			}
		}
		$arrResult['list'] = $tempResult;
		$arrResult['searNum'] = $k;
		$arrResult['linkStr']  = $clsPage->get_link('soc.php?cp=newfaq&helpkeywords='.urlencode($keyword), $pageSize);
		return $arrResult;
	}

	/**
	 * get Help Pages infomation
	 *
	 * @param int $id
	 * @return array
	 */
	public function getHelpInfo($id=0){
		$arrResult	= null;

		$_query	=	"select * from ".$this->table."help where id = '$id'";
		$this -> dbcon -> execute_query($_query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
			$arrResult['body'] = $arrResult['body'];
		}

		return $arrResult;
	}
	public function getHelpItemInfo($id=0,$keyword=""){
		$arrResult	= null;
		$tmpkeyword = null;
		if($keyword!=""){
			$tmpkeyword = split(' ',$keyword);
			$tmpkeyword = array_unique($tmpkeyword); 
		}
		if(!get_magic_quotes_gpc()){
			$keyword = addslashes($keyword);
		} 
		$_query	=	"select * from ".$this->table."help where id = '$id'";
		$this -> dbcon -> execute_query($_query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp[0];
			if(is_array($tmpkeyword)){
				$arySeach = array();
				$aryReplace = array();
				$arySeach[0] = '/('.preg_replace('/([\/|\\\|\(|\)|\*|\?|\.|\+|\=|\]|\[|\$|\{|\}|\||\^])/','\\\\\1',stripslashes($keyword)).')/i';
				$aryReplace[0] = '<b>\1</b>';
				foreach ($tmpkeyword as $pass){
					if($pass==""||strlen($pass)<3) continue;
					$pass = preg_replace('/[<|>|\/|\\\|\(|\)|\*|\?|\.|\+|\=|\]|\[|\$|\{|\}|\||\^]/','',$pass);
					$arySeach[] = '/('.$pass.')/i';
					$aryReplace[] = '<b>\1</b>';
				}
				$arrResult['title'] = preg_replace($arySeach,$aryReplace,$arrResult['title']);
			    $arrResult['body'] = preg_replace($arySeach,$aryReplace,$arrResult['body']);
			}
		}

		return $arrResult;
	}
	/**
	 * save data fo cms
	 *
	 * @param array $data
	 */
	public function setHelpInfo($data){
		$boolean = false;
		if (is_array($data)) {
			if(isset($data[id])&&$data[id]!=""){
				$boolean = $this -> dbcon -> update_query($this->table."help" , $data,"where id='$data[id]'");
			}else{
				$boolean = $this -> dbcon -> insert_query($this->table."help",$data);
			}
		}
		return $boolean;
	}
	
	function deleteHelpItem($id=0){
		$query = "delete from {$this->table}help where id={$id} limit 1 ";
		return $this -> dbcon -> execute_query($query);
	}
	
	function getlistHelp(){
		$strhtml = "<ul>";
		if(is_array($this->getHelpItemName())){
			foreach ($this -> getHelpItemName() as $pass){
				$strhtml .="<li onclick=\"xajax_displayHelpItem('$pass[id]');\" style=\"cursor:hand;\" title=\"$pass[title]\"><a href=\"#\">$pass[title]</a></li>";
			}
		}
		$strhtml .= "</ul>";
		return $strhtml;
	}

}


/*********************
* xajax function
**********************/

/**
 * load cms pages content
 *
 * @param int $id
 * @return objResponse
 */
function displayHelpItem($id){
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminHelp 	= &$GLOBALS['objAdminHelp'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	$objResponse -> assign('submitButton','disabled',"disabled");

	if ($id) {
		$arrTemp = $objAdminHelp -> getHelpInfo($id);

		$objResponse -> assign('id','value',$arrTemp['id']);
		$objResponse -> assign('title','value',$arrTemp['title']);
		$objResponse -> assign('answer','value',$arrTemp['body']);
		$objResponse -> assign('linknew','style.display',"");
		$objResponse -> assign('delbut','style.display',"");
		$messages	=	$arrTemp['title'];
	}

	$objResponse -> clear('submitButton','disabled');
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	return $objResponse;
}

/**
 * save cms pages content
 *
 * @param htmlForm $objForm
 * @return objResponse
 */
function saveHelpItem($objForm){
	$lang 			= &$GLOBALS['_LANG'];
	$objAdminHelp 	= &$GLOBALS['objAdminHelp'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	$objResponse -> assign('submitButton','disabled',"disabled");
	if(trim($objForm['title']) == ""){
		$objResponse ->alert("Question is required.");
		$objResponse -> clear('submitButton','disabled');
		return $objResponse;
	}
	if(trim($objForm['answer']) == ""){
		$objResponse ->alert("Answer is required.");
		$objResponse -> clear('submitButton','disabled');
		return $objResponse;
	}
	
	if ($objForm['id']) {
		$data = array('id'=>$objForm['id'] ,'title'=>addslashes($objForm['title']),'body' => addslashes($objForm['answer']));
		$opt = "updated";
		$objResponse -> assign('id','value',"");
	}else{
		$data = array('title'=>addslashes($objForm['title']),'body' => addslashes($objForm['answer']));
		$opt = "created";
	}

	if($objAdminHelp -> setHelpInfo($data)){
		$messages = $objForm['title'].' has been '.$opt.' successfully.';
	}else {
		$messages = $objForm['title'].' '.$opt.' unsuccessfully';
	}
	$objResponse -> clear('submitButton','disabled');
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	
	$strhtml = $objAdminHelp->getlistHelp();
	$objResponse -> assign('list_sub','innerHTML',$strhtml);
	
	$objResponse -> assign('linknew','style.display',"none");
	$objResponse -> assign('delbut','style.display',"none");
	$objResponse -> assign('title','value',"");
	$objResponse -> assign('answer','value',"");
	
	$objResponse -> alert($messages);
	return $objResponse;
}

function deleteItem($id){
	$objAdminHelp 	= &$GLOBALS['objAdminHelp'];
	$messages		=	'';
	$objResponse = new xajaxResponse();
	$arrTemp = $objAdminHelp -> getHelpInfo($id);
	if($objAdminHelp->deleteHelpItem($id)){
		$messages = "{$arrTemp['title']} deleted successfully.";
	}else{
		$messages = "{$arrTemp['title']} deleted unsuccessfully.";
	}
	$strhtml = $objAdminHelp->getlistHelp();
	$objResponse -> assign('list_sub','innerHTML',$strhtml);
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> assign('linknew','style.display',"none");
	$objResponse -> assign('delbut','style.display',"none");
	$objResponse -> assign('id','value',"");
	$objResponse -> assign('title','value',"");
	$objResponse -> assign('answer','value',"");
	
	$objResponse -> alert($messages);
	return $objResponse;
}
?>