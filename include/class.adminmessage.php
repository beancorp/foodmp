<?php

/**
 * Wed Oct 08 21:18:33 GMT+08:00 2008 21:18:33
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * main class of Message Center
 * ------------------------------------------------------------
 * include\class.adminmessage.php
 */
class adminMsg extends common
{
	var $dbcon  = null;
	var $table  = '';
	var $smarty = null;
	var $lang   = null;

	/**
	 * @return void
	 */
	public function __construct()
	{
		$this->dbcon  = &$GLOBALS['dbcon'];
		$this->table  = &$GLOBALS['table'];
		$this->smarty = &$GLOBALS['smarty'];
		$this->lang   = &$GLOBALS['_LANG'];
	}

	/**
	 * @return void 
	 */
	public function __destruct()
	{
		unset( $this->dbcon, $this->table, $this->smarty, $this -> lang );
	}

	/**
	 * @return array
	 */
	public function viewAllStores()
	{
		$arrStores = $this->getStores(true);
		foreach ($arrStores as $store)
		{
			$strStores .= $divide . $store['StoreID'];
			$divide = ',';
		}

		$arrResult = array
		(
		'header' => $this->lang['header']['allstores'],
		'title'  => $_SESSION['msg_sent'] == 'success' ? $this->lang['msg']['sentsuccessfully'] :
		($_SESSION['msg_sent'] == 'fail' ? $this->lang['msg']['sentfailed'] : ''),
		'input'  => array
		(
		'stores'  => $strStores,
		'message' => $this->initEditor('message', '', 'adminDefault', array('100%', '250'))
		)
		);

		unset($_SESSION['msg_sent']);

		return $arrResult;
	}

	/**
	 * @return array
	 */
	public function viewSelectedStores()
	{
		$xajax               = &$GLOBALS['xajax'];
		$xajaxListSuburbs    = &$GLOBALS['xajaxListSuburbs'];
		$xajaxListRecipients = &$GLOBALS['xajaxListRecipients'];

		$arrResult = array
		(
		'header' => $this->lang['header']['selectedstores'],
		'title'  => $_SESSION['msg_sent'] == 'success' ? $this->lang['msg']['sentsuccessfully'] :
		($_SESSION['msg_sent'] == 'fail' ? $this->lang['msg']['sentfailed'] : ''),
		'js'     => array
		(
		'xajaxInt'      => $xajax->getJavascript('/include/xajax') .
		"\n<script type=\"text/javascript\">\n".
		"/* <![CDATA[ */\n".
		"window.onload = function () { ". $xajaxListSuburbs->getScript() ."; }\n".
		"/* ]]> */\n".
		"</script>",
		'state'         => $xajaxListSuburbs->getScript(),
		'displayStores' => $xajaxListRecipients->getScript()
		),
		'input'  => array
		(
		'states'     => $this->getStates(),
		//'categories' => $this->getCategories(),
		'message'    => $this->initEditor('message', '', 'adminDefault', array('100%', '250'))
		)
		);

		unset($_SESSION['msg_sent']);

		return $arrResult;
	}

	/**
	 * @return array
	 */
	public function viewIndividualStores()
	{
		$arrResult = array
		(
		'header' => $this->lang['header']['individualstores'],
		'title'  => $_SESSION['msg_sent'] == 'success' ? $this->lang['msg']['sentsuccessfully'] :
		($_SESSION['msg_sent'] == 'fail' ? $this->lang['msg']['sentfailed'] : ''),
		'input'  => array
		(
		'stores'  => $this->getStores(),
		'message' => $this->initEditor('message', '', 'adminDefault', array('100%', '250'))
		)
		);

		unset($_SESSION['msg_sent']);

		return $arrResult;
	}

	/**
	 * @param int $intPage
	 * @param string $strParam
	 * @return array
	 */
	public function viewPreviousMessage()
	{
		$xajax            = &$GLOBALS['xajax'];
		$xajaxListMessage = &$GLOBALS['xajaxListMessage'];

		$arrResult = array
		(
		'header' => $this->lang['header']['previousmessage'],
		'title'  => $_SESSION['msg_deleted'] == 'success' ? $this->lang['msg']['deletedsuccessfully'] :
		($_SESSION['msg_deleted'] == 'fail' ? $this->lang['msg']['deletedfailed'] : ''),
		'js'     => array
		(
		'xajaxInt' => $xajax->getJavascript('/include/xajax') .
		"\n<script type=\"text/javascript\">\n".
		"/* <![CDATA[ */\n".
		"window.onload = function () { ". $xajaxListMessage->getScript() ."; }\n".
		"/* ]]> */\n".
		"</script>",
		)
		);

		unset($_SESSION['msg_deleted']);

		return $arrResult;
	}

	/**
	 * @param int $intPage
	 * @param string $strParam
	 * @return array
	 */
	public function viewListMessage($intPage = 1,$field="",$order='asc')
	{
		$xajaxListMessage = &$GLOBALS['xajaxListMessage'];

		$query = "SELECT COUNT(*) \n".
		"FROM  (SELECT * FROM `". $this->table ."message` group by subject,message,msg_multi) as tt \n";

		$this->dbcon->execute_query($query);
		$count = $this->dbcon->fetch_records();
		$count = $count[0][0];
		$whorder = "";
		switch ($field){
			case 'subject':
				$whorder .= " order by subject $order ";
				break;
			case 'date':
				$whorder .= " order by date $order ";
				break;
			default:
				$whorder = "";
				break;
		}

		if ($count)
		{
			$intEach  = 15;
			$intStart = ($intPage - 1) * $intEach;

			$query ="SELECT * FROM (SELECT * FROM `". $this->table ."message` group by subject,message,msg_multi) as tt $whorder \n".
			"LIMIT ". $intStart .", ". $intEach;

			$this->dbcon->execute_query($query);
			$arrTmp = $this->dbcon->fetch_records(true);
			for ($num = 0; $num < count($arrTmp); $num++)
			{
				$arrTmp[$num]['alert'] = $num + 1;
				$arrTmp[$num]['date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)), $arrTmp[$num]['date']);
				$arrTmp[$num]['js'] = array
				(
				'view'   => 'xajax_viewMessage('. $arrTmp[$num]['messageID'] .', '. $intPage .');',
				'delete' => 'if (confirm(\''. $this->lang['pub_clew']['delete'] .'\')) { document.getElementById(\'messageId_'. $arrTmp[$num]['alert'] .'\').checked = true; this.form.submit(); }'
				);
			}

			$params = array
			(
			'append'      => false,
			'currentPage' => $intPage,
			'delta'       => 15,
			'fileName'    => '%d',
			'onclick'     => 'xajax_listMessage(%d,\''.$field.'\',\''.$order.'\');',
			'path'        => '#',
			'perPage'     => $intEach,
			'totalItems'  => $count,
			'urlVar'      => 'pageno'
			);
			$pager = &Pager::factory($params);

			$arrResult = array
			(
			'header'      => $this->lang['header']['previousmessage'],
			'links'       => $pager->getLinks(),
			'list'        => $arrTmp,
			'js'          => array
			(
			'delete' => 'var obj = document.getElementsByName(\'messageId[]\'); for (var i = 0; i < obj.length; i++) { if (obj[i].checked) { var checked = true; break; } } if (!checked) { alert(\''. $this->lang['pub_clew']['pleaseselect'] .'\'); } else if (confirm(\''. $this->lang['pub_clew']['delete'] .'\')) { this.form.submit(); }'
			)
			);
		}
		$arrResult['sort']['field'] = $field;
		$arrResult['sort']['order'] = $order;
		$arrResult['sort']['page'] = $intPage;

		return $arrResult;
	}

	/**
	 * @param int $intId
	 * @param int $intPage
	 * @return array
	 */
	public function viewMessage($intId, $intPage)
	{
		$query = "SELECT * \n".
		"FROM `". $this->table ."message` \n".
		"WHERE `messageID` = '". $intId ."'";

		$this->dbcon->execute_query($query);
		$arrTmp = $this->dbcon->fetch_records();
		$arrTmp = $arrTmp[0];
		$arrTmp['date'] = date(str_replace('-','/',str_replace('%','',DATAFORMAT_DB)), $arrTmp['date']);

		if($arrTmp['msg_multi']!=""){
			$query = "SELECT StoreID FROM  `{$this->table}message` WHERE msg_multi = '{$arrTmp['msg_multi']}'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$storeid ="";
			foreach ($result as $pass){
				$storeid .= ($storeid==""?"":",")."'{$pass['StoreID']}'";
			}
		}else{
			$storeid = "'{$arrTmp['StoreID']}'";
		}
		$query = "SELECT bu_name FROM `{$this->table}bu_detail` WHERE StoreID in ($storeid)";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		$Recipients = "";
		foreach ($result as $pass){
			$Recipients .= ($Recipients==""?"":"; ").$pass['bu_name'];
		}
		$arrResult = array
		(
		'recipients'=>$Recipients,
		'data' => $arrTmp,
		'js'   => array
		(
		'delete' => 'if (confirm(\''. $this->lang['pub_clew']['delete'] .'\')) { this.form.submit(); }',
		'back'   => 'xajax_listMessage('. $intPage .');',
		)
		);

		return $arrResult;
	}

	public function getStoreInfo($sid){
		$query = "SELECT * FROM `". $this->table ."bu_detail` \n".
		"WHERE StoreID in($sid) ";

		$this->dbcon->execute_query($query);
		return $this->dbcon->fetch_records();
	}
	/**
	 * @return array
	 */
	public function process()
	{
		switch ($_POST['opt'])
		{
			case 'all':
				$_SESSION['msg_sent'] = $this->save('all') ? 'success' : 'fail';
				header("location: ?act=msg&cp=all");
				break;

			case 'selected':
				$_SESSION['msg_sent'] = $this->save() ? 'success' : 'fail';
				header("location: ?act=msg&cp=selected");
				break;

			case 'individual':
				$_SESSION['msg_sent'] = $this->save() ? 'success' : 'fail';
				header("location: ?act=msg&cp=individual");
				break;

			case 'sendreminder':
				$_SESSION['msg_sent'] = $this->sendreminder() ? 'success' : 'fail';
				header("location: ?act=msg&cp=sendreminder&msg=".$_SESSION['msg_sent']);
				break;

			case 'pre':
				$_SESSION['msg_deleted'] = $this->delete() ? 'success' : 'fail';
				header("location: ?act=msg&cp=pre");
				break;
		}
	}

	/**
	 * @param string $strState
	 * @return string
	 */
	public function getSuburbsHTML($strState)
	{
		$arrSuburbs = $this->getSuburbs($strState);

		$strHTML = '<select id="suburb" name="suburb">';
		$strHTML .= '<option value="">Select Suburb</option>';
		foreach ($arrSuburbs as $suburb)
		{
			$strHTML .= '<option value="'. $suburb['suburb'] .'">'. $suburb['suburb'] .'</option>';
		}
		$strHTML .= '</select>';

		return $strHTML;
	}

	/**
	 * @param string $strState
	 * @param string $strSuburb
	 * @return array
	 */
	public function getRecipients($strState='', $strSuburb = '')
	{
		$state = $this->getStates($strState);
		$state = $state[0]['id'];

		$query = "SELECT `StoreID`, `bu_name` FROM `". $this->table ."bu_detail` WHERE CustomerType='seller' \n" ;

		$query .= (!empty($state) ? ("AND `bu_state` = '". $state ."' \n") : '') ;
		$query .= (!empty($strSuburb) ? ("AND `bu_suburb` = '". $strSuburb ."' \n") : '') ;
		$query .= "ORDER BY `bu_name` ASC";

		$this->dbcon->execute_query($query);
		$arrTmp = $this->dbcon->fetch_records();
		if (is_array($arrTmp))
		{
			foreach ($arrTmp as $row)
			{
				$arrRecipients['id']   .= $divide1 . $row['StoreID'];
				$divide1 = ',';

				$arrRecipients['name'] .= $divide2 . $row['bu_name'];
				$divide2 = '; ';
			}
		}
		else
		{
			$arrRecipients = array( 'id' => '', 'name' => '' );
		}


		return $arrRecipients;
	}

	/**
	 * @param array/string $name
	 * @return array
	 */
	private function getStates($name = NULL)
	{
		$where = empty($name) ? '' :
		(is_array($id) ? ("WHERE `stateName` IN ('". implode("', '", $name) ."') \n") :
		("WHERE `stateName` = '". $name ."' \n"));

		$query = "SELECT * FROM `". $this->table ."state` $where ORDER BY stateName ASC";

		$this->dbcon->execute_query($query);
		return $this->dbcon->fetch_records();
	}

	/**
	 * @param string $state
	 * @return array
	 */
	private function getSuburbs($state)
	{
		$state = $this->getStates($state);
		$state = $state[0]['id'];

		$sqlQuery	=" AND state_id ='$state' ";
		$query	=	"SELECT DISTINCT suburb FROM ".$this->table."suburb WHERE suburb <>'' $sqlQuery ORDER BY suburb";

		$this->dbcon->execute_query($query);
		return $this->dbcon->fetch_records();
	}

	/**
	 * @param boolean $boolAll
	 * @return array
	 */
	private function getStores($boolAll = false)
	{
		$query = "SELECT `StoreID`, `bu_name`,`outerEmail`,`bu_email` \n".
		"FROM `". $this->table ."bu_detail` \n".
		"WHERE CustomerType='seller' \n " .
		"AND is_popularize_store=0 \n " .
		"ORDER BY `bu_name` ASC";

		$this->dbcon->execute_query($query);
		return $this->dbcon->fetch_records();
	}

	/**
	 * @return boolean
	 */
	private function save($send = "")
	{
		$subject = trim($_POST['subject']);
		$message = trim($_POST['message']);
		$isouteremail = (isset($_POST['isouter'])&&$_POST['isouter']==1)?true:false;
		$storeid = $_POST['storeid'];
		$date    = time();
		$strand = time().randStr(6);
		$toemail = "";
		if (empty($subject) || empty($message) || empty($storeid))
		{
			return false;
		}
		if($send=='all'){
			$arrStores = $this->getStores(true);
		}else{
			$arrStores = $this->getStoreInfo($storeid);
		}
		$query = "INSERT INTO `". $this->table ."message` (`subject`, `message`, `StoreID`, `date`,`msg_multi`,`emailaddress`,`fromtoname`)VALUES ";
		foreach ($arrStores as $store){
			$vals .= ($vals ==""?"":", ")."('". $subject ."', '". $message ."', '".$store['StoreID']."', '". $date ."','".$strand."', 'SYSTEM','SYSTEM')";
			if($store['outerEmail']>0&&trim($store['bu_email'])!=""){
				$toemail .= ($toemail==""?"":", ").$store['bu_email'];
			}
		}
		$query .=$vals;
		if($toemail!=""&&$isouteremail){
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: Admin<noreply@TheSOCExchange.com>' . "\r\n";
			$headers .= 'Bcc: '.$toemail. "\r\n";
			if(get_magic_quotes_gpc()){
				$subject = stripslashes($subject);
				$message = stripslashes($message);
			}
			$arrParams	=	array('display'=>'adminsend','message'=>$message,'webside_link'=>'http://'.$_SERVER['HTTP_HOST']);
			$this->smarty->assign('req',$arrParams);
			$content = $this->smarty->fetch('../email_contact_seller.tpl');
			mail('noreply@thesocexchange.com',$subject,getEmailTemplate($content, array('banner_best'=>1)),FixEOL($headers));
		}

		return $this->dbcon->execute_query($query);
	}

	/**
	 * @return boolean
	 */
	private function sendreminder()
	{
		$subject = trim($_POST['subject']);
		$message = trim($_POST['message']);
		$date    = time();
		$strand = time().randStr(6);
		$toemail = "";
		if (empty($subject) || empty($message))
		{
			return false;
		}

		$query = "select email,subscribe_date from {$this->table}report_subscribe where 1";
		$this->dbcon->execute_query($query);
		$arrStores	=	$this->dbcon->fetch_records(true);
		foreach ($arrStores as $store){
			if(trim($store['email'])!=""){
				$toemail .= ($toemail==""?"":", ").$store['email'];
			}
		}
		if($toemail!=""){
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: SOC exchange<noreply@TheSOCExchange.com>' . "\r\n";
			$headers .= 'Bcc: '.$toemail. "\r\n";
			if(get_magic_quotes_gpc()){
				$subject = stripslashes($subject);
				$message = stripslashes($message);
			}
			/*$arrParams	=	array('display'=>'adminsend','message'=>$message,'webside_link'=>'http://'.$_SERVER['HTTP_HOST']);
			$this->smarty->assign('req',$arrParams);
			$content = $this->smarty->fetch('../email_contact_seller.tpl');*/
			$content = $message;
			mail('noreply@thesocexchange.com',$subject,getEmailTemplate($content, array('foodwine_new'=>1)),FixEOL($headers));
		}

		return true;
	}

	/**
	 * @return boolean
	 */
	private function delete()
	{
		$messageId = is_array($_POST['messageId']) ? implode("', '", $_POST['messageId']) : $_POST['messageId'];

		$query = "SELECT * from `{$this->table}message` where `messageID` IN('{$messageId}')";
		$this->dbcon->execute_query($query);
		$result = $this->dbcon->fetch_records(true);
		$msg_id = "";
		$msg_multi = "";
		foreach ($result as $pass){
			if($pass['msg_multi']!=""){
				$msg_multi .= ($msg_multi!=""?",":"")."'".$pass['msg_multi']."'";
			}else{
				$msg_id .= ($msg_id!=""?",":"")."'".$pass['messageID']."'";
			}
		}
		if($msg_multi!=""){
			$query = "DELETE FROM `{$this->table}message` WHERE `msg_multi` IN($msg_multi)";
			if($this->dbcon->execute_query($query)){
				$bl = true;
			}
		}
		if($msg_id!=""){
			$query = "DELETE FROM `". $this->table ."message` WHERE `messageID` IN (". $msg_id .")";
			if($this->dbcon->execute_query($query)){
				$bl = true;
			}
		}
		return $bl;
	}
}



/******************************
* xajax registered functions *
******************************/

/**
 * list suburbs of selected state
 *
 * @param string $strState
 * @return objResponse
 */
function listSuburbs($strState)
{
	$objAdminMsg = &$GLOBALS['objAdminMsg'];

	$objResponse = new xajaxResponse();
	$objResponse->assign('suburbfarme', 'innerHTML', $objAdminMsg->getSuburbsHTML($strState));

	return $objResponse;
}

/**
 * list recipients of selected suburb or category
 *
 * @param string $state
 * @param string $strSuburb
 * @return objResponse
 */
function listRecipients($strState = '', $strSuburb = '')
{
	$objAdminMsg   = &$GLOBALS['objAdminMsg'];
	$arrRecipients = $objAdminMsg->getRecipients($strState,$strSuburb);

	$objResponse = new xajaxResponse();
	$objResponse->assign('storeid', 'value', $arrRecipients['id']);
	$objResponse->assign('recipients', 'innerHTML', $arrRecipients['name']);

	return $objResponse;
}

/**
 * list message
 *
 * @param int $intPage
 * @return objResponse
 */
function listMessage($intPage = '1',$field='',$order='asc')
{
	$objAdminMsg = &$GLOBALS['objAdminMsg'];
	$smarty      = &$GLOBALS['smarty'];

	// main content
	$req = $objAdminMsg->viewListMessage($intPage,$field,$order);
	$smarty->assign('req', $req);

	$objResponse = new xajaxResponse();
	$objResponse->assign('admin_msg', 'innerHTML', $smarty->fetch('admin_msg_previous_list.tpl'));

	return $objResponse;
}

/**
 * list recipients of selected suburb or category
 *
 * @param string $strSuburb
 * @param int $intId
 * @return objResponse
 */
function viewMessage($intId, $intPage)
{
	$smarty      = &$GLOBALS['smarty'];
	$objAdminMsg = &$GLOBALS['objAdminMsg'];

	$req = $objAdminMsg->viewMessage($intId, $intPage);
	$smarty->assign('req', $req);

	$objResponse = new xajaxResponse();
	$objResponse->assign('admin_msg', 'innerHTML', $smarty->fetch('admin_msg_previous_view.tpl'));

	return $objResponse;
}

?>