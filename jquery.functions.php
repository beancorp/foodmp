<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
@session_start();
include_once ('include/maininc.php');
include_once('include/smartyconfig.php');
include_once ('functions.php');

switch ($_REQUEST['cp']){
	case 'autotype':
		echo getSectorListForJquery($_REQUEST['objHTML'],$_REQUEST['values'],0,$_REQUEST['level'],$_REQUEST['addOptions'],$_REQUEST['addOptions']);
		exit;
		break;

	case 'jobtype':
		echo getSectorListForJquery($_REQUEST['objHTML'],$_REQUEST['values'],1,$_REQUEST['level'],$_REQUEST['addOptions'],$_REQUEST['addOptions']);
		exit;
		break;

	case 'checkJobSeache':
		if ($_REQUEST['category'] == 2){
			if($_SESSION['attribute'] == 3 && ($_SESSION['subAttrib'] == 1 || $_SESSION['subAttrib'] == 2)) {
				echo '$(\'#' . $_REQUEST['formID'] . '\').submit();';
			}else {
				if(isset($_REQUEST['keyword'])&&$_REQUEST['keyword']!=""){
					echo '$(\'#' . $_REQUEST['formID'] . '\').submit();';
				}else{
					echo 'alert("Only Employers can Search Resumes. Please signup for an Employer account, or log in to your Employer account now.")';
				}
			}
		}else {
			echo '$(\'#' . $_REQUEST['formID'] . '\').submit();';
		}
		exit;
		break;
        
                
                
        //ajax get city
         case 'getCityList' :
            echo '<option value="">All</option>';
            if('-1' == $_REQUEST['state_name']) {
                exit;
            }
            $string = '';
            //$re = _getSubburb($_REQUEST['state_name']);
            $re = getSuburbArray($_REQUEST['state_name'],$_REQUEST['preselect']);
            if(is_array($re)) {
                foreach($re as $k=>$value) {
                    $string .= '<option value="' . $value['bu_suburb'];
                    if($_REQUEST['type'] == '_E_') {
                        $string .= '.' . $value['zip'] . '.' . $value['suburb_id'];
                    }
                    $string .= '"';
                    if ($value['selected']) $string .=' selected="selected"';
                    $string .= '>' . $value['bu_suburb'] . '</option>';
                }
            }
            exit($string);
            exit;
        break;
                
                
        //ajax get council
         case 'getCouncilList' :
            echo '<option value="">'.$_LANG['labelCouncil'].'</option>';
            if('-1' == $_REQUEST['state_name']) {
                exit;
            }
            $string = '';
            //$re = _getSubburb($_REQUEST['state_name']);
            $re = getCouncilArray($_REQUEST['state_name']);
            if(is_array($re)) {
                foreach($re as $k=>$value) {
                    $string .= '<option value="' . $value['bu_council'];
                    $string .= '">' . $value['bu_council'] . '</option>';
                }
            }
            exit($string);
            exit;
        break;


        case 'free_renew':
            if(empty($_SESSION['email']) or empty($_SESSION['StoreID'])) {
                exit('_F_');
            }
            include_once (dirname(__FILE__) . '/include/class.paymentipg.php');
            include_once (dirname(__FILE__) . '/include/class.emailClass.php');
            if('buysell' == $_POST['type']) {
                $data['bu_name'] = $_POST['bu_name'];
            }
            
            $data['bu_urlstring'] = $_POST['bu_urlstring'];
            $data['amount'] = 10;
            $data['cardNumber'] = '';
            $data['StoreID'] = $_SESSION['StoreID'];
            $isUp = ($_SESSION['attribute']==3 and $_SESSION['subAttrib'] == 3) ? true : false;
            $IPG = new paymentIPG();
            $_titels		=	"t2.StoreID, t2.bu_name, t2.bu_email, t2.renewalDate, t2.bu_repid, t2.CustomerType, t2.bu_nickname, t2.bu_name, t2.bu_address, t2.bu_state, t2.bu_suburb, t2.bu_postcode, t2.bu_area, t2.bu_phone, t1.password";
            $_query		=	"select $_titels from ".$table."login as t1 ".
                                        "left join ".$table."bu_detail as t2 on t1.StoreID=t2.StoreID ".
                                        "where t1.`user`='".$_SESSION['email']."' and t1.attribute='". $_SESSION['attribute'] ."'";
            $dbcon->execute_query($_query);
            $arrUser 	=	$dbcon->fetch_records(true);
            $rs = $IPG->keepupUserInfo(array_merge($arrUser[0], $data),true);

            if($rs) {
                if($isUp) exit('_U_');
                exit('_S_');
            }
            exit('_F_');
        break;
}

//jquery function
function getSectorListForJquery($objHTML, $id, $ctype=0, $level=2, $hasAny='', $hasOther=false){
	$strResult	=	'';
	if (!empty($id)) {

		if(!empty($hasAny)){
			$addOption[]	=	array('name'=>$hasAny,'id'=>'-1','place'=>0);
		}

		if ($hasOther) {
			$addOption[]	=	array('name'=>'Other','id'=>'-2','place'=>1);
		}

		$arrDate	=	getSectorListFromDB($id, $ctype ,$addOption);
		$strResult	=	jqueryComboxLinkageElement($arrDate, $objHTML, $level);
	}

	unset($arrDate);
	return $strResult;
}


function _getSubburb($state=''){
	global $dbcon ;
	if($state!=''){
		$QUERY			=	"SELECT * FROM ".$GLOBALS["table"]."state where stateName='$state'";
		$result			=	$dbcon->execute_query($QUERY) ;
		$grid			=	$dbcon->fetch_records() ;
		$stateID		=	$grid[0]['id'];
		$sqlQuery	="  state_id ='$stateID' ";
	}else{
		$sqlQuery	='';
	}
	$QUERY	=	"SELECT * FROM ".$GLOBALS["table"]."suburb WHERE $sqlQuery ORDER BY suburb";
	//echo $QUERY;
	$result			=	$dbcon->execute_query($QUERY) ;
        return $dbcon->fetch_records();
}

exit;
?>