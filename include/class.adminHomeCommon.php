<?php
/**
 * Tue Jan 06 15:08:39 GMT 2009 15:08:39
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * admin home common
 * ------------------------------------------------------------
 * include\class.adminHomeCommon.php
 */

class adminHomeCommon extends common {
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

	function saveOuterEmail(&$msg, $intVal = 0){
		$boolResult	=	false;

		//$msg = $this->replaceLangVar($this->lang['pub_clew']['faild'],array('The forwarding function',$this->lang['operation'][($intVal == 1 ? 'on' : 'off')]));
		$msg = $this->lang['operation'][($intVal == 1 ? 'on' : 'off')];

		$arrSetting	=	array(
		'outerEmail'		=>	$intVal
		);
		$strCondition	=	" where StoreID='".$_SESSION['ShopID']."'";

		if($this->dbcon->update_record($this->table."bu_detail", $arrSetting, $strCondition)){
			$boolResult	=	true;
			$_SESSION['outerEmail']	=	$intVal;
			
			//$msg = $this->replaceLangVar($this->lang['pub_clew']['successful'],array('The forwarding function',$this->lang['operation'][($intVal == 1 ? 'on' : 'off')]));
			$msg = $this->lang['operation'][($intVal == 1 ? 'on' : 'off')];
		}

		return $boolResult;
	}

}

//------------------------------------------------------------
// xajax function
//------------------------------------------------------------

function saveOuterEmail($objHTMLVal, $objHTMLID){
	$objResponse = new xajaxResponse();
	$objAdminHomeCommon	= new adminHomeCommon();

	$setHTMLVal	=	($objHTMLVal == 1 ? "0" : "1");
	if ($objAdminHomeCommon -> saveOuterEmail($msg, $setHTMLVal)) {
		$objResponse -> assign($objHTMLID,'value', $setHTMLVal);
	}else{
		$objResponse -> assign($objHTMLID, 'checked', ($objHTMLVal==1 ? 'checked' : ''));
	}

	$objResponse -> assign($objHTMLID."Clew", 'innerHTML', $msg);


	return $objResponse;
}

?>