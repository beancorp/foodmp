<?php
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
include_once ('include/config.php');
@session_start();
include_once('include/smartyconfig.php');
include_once('class.team.php');
include_once('class.point.php');

include_once('maininc.php');
include_once('class.soc.php');
include_once('functions.php');
require_once('class.socstore.php');
include_once('class.emailClass.php');
include_once('class.processcsv.php');

$socObj = new socClass();
$socstoreObj = new socstoreClass();

if (!isset($_SESSION['StoreID'])) {
	header("Location: soc.php?cp=login&reurl=".SOC_HTTPS_HOST."myrace.php");
}

if (isset($_GET['bonus'])) {
	if (isset($_SESSION['StoreID'])) {
		$facebook_user = $facebook->getUser();
		if ($facebook_user) {
			try {
				$likes = $facebook->api("/me/likes/1417039495177479");
				if (!empty($likes['data'])) {
					
					$account_query = "SELECT * FROM bank_details WHERE user_id = '".$_SESSION['StoreID']."'";
					$result = $dbcon->getOne($account_query);
					
					if (isset($result)) {
						$point_query = "SELECT * FROM aus_soc_point_records WHERE StoreID = '".$_SESSION['StoreID']."' AND point = '300'";
						$point_result = $dbcon->getOne($point_query);
						if (!isset($point_result)) {
							$point_records = array(
								'StoreID' => $_SESSION['StoreID'],
								'point' => 300
							);
							$dbcon->insert_query('aus_soc_point_records', $point_records);
							// points added
							$smarty->assign('bonus_message', 'Bonus Points are added for liking the page.');
						} else {
							// points already exist
							$smarty->assign('bonus_message', 'Error: Bonus Points already exist');
						}
						$smarty->assign('tab', 'tabpoints');
					} else {
						// add bank account
						$smarty->assign('tab', 'tabrefer');
						$smarty->assign('bonus_message', 'Error: Must add bank account first');
					}
				} else {
					$smarty->assign('bonus_message', 'Error: Must like page');
					$smarty->assign('tab', 'tabpoints');
				}
			} catch (FacebookApiException $e) {
				$facebook_user = null;
			}
			$smarty->assign('bonus_points', true);
		} else {
			header("Location: " . $facebook->getLoginUrl(array('req_perms'=>'read_stream', 'canvas' => 1, 'fbconnect' => 0)));
			exit;
		}
	}
}

$team = Team::fetchMyTeam($_SESSION['StoreID']);

function myPoints() {
	global $team;
	$objPoint = new Point();
	$points = $objPoint->getPointInfo($_SESSION['StoreID']);
	$total = 0;
	echo '
		<table class="records">
					<thead>
						<tr>
							<th>Referral</th>
							<th>Date/ Time</th>
							<th align="right" style="padding-right: 10px;">Points</th>
						</tr>
					</thead>
					<tbody>';
	if (!empty($points['ref_list'])) {
		foreach ($points['ref_list'] as $point) {
		$total += $point['point'];
		echo '	<tr class="odd">
					<td>'.$point['name'].'</td>
					<td>'.date('d/m/Y', strtotime($point['timestamp'])).'</td>
					<td class="points">'.$point['point'].'</td>
				</tr>';
		}
	} else {
		echo '<tr><td colspan="3">No referral points.</td></tr>';
	}
	echo '
					</tbody>';
	if (!empty($points['ref_list'])) {				
		echo '
						<tfoot>
							<tr>
								<td colspan="2"></td>
								<td class="points">'.$total.'</td>
							</tr>
						</tfoot>';
	}
	echo '
		</table>';
}

function commission() {
	global $dbcon;
	$fetch_referrals = "SELECT * FROM aus_soc_referrer WHERE StoreID = '".$_SESSION['StoreID']."'";
	$dbcon->execute_query($fetch_referrals);
	$referrals_result = $dbcon->fetch_records();
	if (isset($referrals_result)) {	
		echo '
		<table class="records">
				<thead>
					<tr>
						<th>Details</th>
						<th align="right" style="padding-right: 10px;">Amount</th>
						<th>Status</th>
					</tr>
				</thead>';
		if (!empty($referrals_result)) {
			echo '<tbody>';
			$balance = 0;
			foreach ($referrals_result as $referral) {
				echo '
				<tr>
					<td>'.$referral['details'].'</td>
					<td class="points">'.$referral['amount'].'</td>
					<td>'.(($referral['status'] == 0)? 'UNPAID' : (($referral['status'] == 1)? 'PAID' : '')).'</td>
				</tr>';
				$balance += $referral['amount'];
			}
			echo '</tbody>';
			echo '<tfoot>
					<tr>
						<td></td>
						<td class="points">$'.$balance.'</td>
						<td></td>
					  </tr>
				  </tfoot>
			';
		} else {
			echo '
				<tfoot>
				<tr>	
					<td colspan="3">No Referrals</td>
				</tr>
				</tfoot>';
		}
		echo '
				
			</table>';
	}

}

function inviteList() {
	global $team;
	$invites = TeamEmailInvite::getList($team->team_id);
	echo '
		<table id="invite_list">
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Invite Code</th>
					<th>Remove</th>
				</tr>
			</thead>
			<tbody>';
	if (count($invites) > 0) {
		foreach ($invites as $invite) {
			echo '
				<tr>
					<td>'.$invite->name.'</td>
					<td>'.$invite->email.'</td>
					<td>'.$invite->code.'</td>
					<td><a href="myrace.php?action=remove_invite&code='.$invite->code.'">Remove</a></td>
				</tr>
			';
		}
	} else {
			echo '
				<tr>
					<td colspan="2">No invites sent</td>
				</tr>
			';
	}
	
	echo '
			</tbody>
		</table>	
	';
}

if (!empty($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'create':
			if (!isset($team)) {
				if (!empty($_POST['team_name'])) {
					$team = new Team(0, $_POST['team_name']);
					$team->captain = 1;
					if ($team->save()) {
						$team->addMember($_SESSION['StoreID'], 1);
					}
				}
			}
			break;
			
		case 'promote':
			if (isset($team)) {
				if ($team->captain) {
					if (isset($_GET['user'])) {
						TeamMember::promote($_SESSION['StoreID'], $_GET['user']);
					}
				}
			}
			header('Location: myrace.php');
			break;
			
		case 'change_name':
			if (isset($team)) {
				if (!empty($_POST['team_name'])) {
					$team->team_name = $_POST['team_name'];
					$team->save();
				}
			}
			break;
			
		case 'join':
			if (!isset($team)) {
				if (!empty($_POST['invite_code'])) {
					if (TeamEmailInvite::accept($_POST['invite_code'], $_SESSION['StoreID'])) {
						$team = Team::fetchMyTeam($_SESSION['StoreID']);
					}
				}
			}
			break;
			
		case 'preview':
			if (isset($team)) {
				$member = TeamMember::fetch($_SESSION['StoreID']);
				$html = Team::emailTemplate('[NAME]', $member->name, $team->team_name, '[CODE]', (isset($_POST['greeting']) ? $_POST['greeting'] : ''));
				echo $html;
			}
			exit;
			
		case 'invite':
			if (isset($team)) {
				$greeting = (isset($_POST['personal_greeting']) ? $_POST['personal_greeting'] : '');
				for ($i = 0; $i < count($_POST['invite_address']); $i++) {
					if (isset($_POST['invite_name'][$i]) && isset($_POST['invite_address'][$i])) {
						$name = $_POST['invite_name'][$i];
						$email = $_POST['invite_address'][$i];
						$team->invite($name, $email, $greeting);
					}
				}
			}
			break;
			
		case 'remove_invite':
			if (isset($team)) {
				if (!empty($_GET['code'])) {
					TeamEmailInvite::remove($team->team_id, $_GET['code']);
				}
			}			
			header('Location: myrace.php?tab=tabinvite');			
			exit;
		
		case 'leave':
			if (TeamMember::leave($_SESSION['StoreID'])) {
				$team = NULL;
			}
			
			break;
	}
}

if (isset($_GET['invite_code'])) {
	$smarty->assign('invite_code', $_GET['invite_code']);
}

$detail_result = $dbcon->getOne("SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_SESSION['StoreID']."'");
if (isset($detail_result)) {
	$ref_name = $detail_result['ref_name'];
	$smarty->assign('ref_name', $ref_name);
}

$account_query = "SELECT * FROM bank_details WHERE user_id = '".$_SESSION['StoreID']."'";
$bank_result = $dbcon->getOne($account_query);

if (isset($_POST['account_submit'])) {
	if (isset($bank_result)) {
		$strCondition = "WHERE user_id = '".$_SESSION['StoreID']."'";
		$account_data = array(
			'account_name'		=>	$_POST['account_name'],
			'account_bsb'  		=> 	$_POST['account_bsb'],
			'account_number'	=>	$_POST['account_number']
		);
		if ($dbcon->update_record('bank_details', $account_data, $strCondition)) {
			$bank_result = $dbcon->getOne($account_query);
		}
	} else {
		$account_data = array(
			'user_id' 			=> 	$_SESSION['StoreID'],
			'account_name'		=>	$_POST['account_name'],
			'account_bsb'  		=> 	$_POST['account_bsb'],
			'account_number'	=>	$_POST['account_number']
		);
		
		if ($dbcon->insert_record('bank_details', $account_data)) {
			$bank_result = $dbcon->getOne($account_query);
			
			$point_query = "SELECT * FROM aus_soc_point_records WHERE StoreID = '".$_SESSION['StoreID']."' AND point = '200'";
			$point_result = $dbcon->getOne($point_query);
			if (!isset($point_result)) {
				$point_records = array(
					'StoreID' => $_SESSION['StoreID'],
					'point' => 200
				);
				$dbcon->insert_query('aus_soc_point_records', $point_records);
			}		
		}
	}
}

if (isset($bank_result)) {
	$smarty->assign('account_name', $bank_result['account_name']);
	$smarty->assign('account_bsb', $bank_result['account_bsb']);
	$smarty->assign('account_number', $bank_result['account_number']);
	$smarty->assign('has_bankaccount', true);
} else {
	$smarty->assign('tab', 'tabbonus');
}

$sid              = $_SESSION['ShopID'];
$req['own_name']  = str_replace( "\"", "&quot;", $_SESSION['NickName'] );
$req['own_email'] = $_SESSION['email'];
$refID            = $socObj->getdetailinfo( $sid );

if (isset($_REQUEST['optval']) && $_REQUEST['optval'] == "preview") {
    $inscription   = strip_tags( $_POST['inscription'] );
    $personal_note = strip_tags( $_POST['personal_note'] );
    $own_name      = $_REQUEST['own_name'] != "" ? $_REQUEST['own_name'] : $_SESSION['NickName'];
    $own_email     = $_REQUEST['own_email'] != "" ? $_REQUEST['own_email'] : $_SESSION['email'];
    
    $arrParams = array(
        'reftype' => 'referrs',
        'To' => '',
        'Subject' => 'Your friend invites you to join Food Marketplace',
        'fromName' => $own_name,
        'From' => $own_email,
        'nickname' => '[UserName]',
        'refID' => $refID,
        'webside_link' => SOC_HTTP_HOST,
        'inscription' => stripslashes( $inscription ),
        'personal_note' => stripslashes( $personal_note ),
        'preview' => true 
    );
    $smarty->assign( 'req', $arrParams );
    $message = $smarty->fetch( 'email_template/email_referer.tpl' );
    exit( $message );
}

if (isset($_REQUEST['optval']) && $_REQUEST['optval'] == "send") {
    $inscription   = strip_tags( $_POST['inscription'] );
    $personal_note = strip_tags( $_POST['personal_note'] );
    
    $own_name  = $_REQUEST['own_name'] != "" ? $_REQUEST['own_name'] : $_SESSION['NickName'];
    $own_email = $_REQUEST['own_email'] != "" ? $_REQUEST['own_email'] : $_SESSION['email'];
    
    if ($_POST['is_choose_upload'] == 1) {
        $procsv = new processcsv();
        $csvAry = $procsv->showCSVEmail( $_SESSION['ShopID'], 'referrer' );
        $k      = 0;
        if ( $csvAry ) {
            foreach ( $csvAry as $pass ) {
                $arrParams   = array(
                    'reftype' => 'referrs',
                    'To' => $pass['emailAddress'],
                    'Subject' => 'Your friend invites you to join Food Marketplace',
                    'fromname' => $own_name,
                    'From' => $own_email,
                    'nickname' => stripslashes( $pass['emailName'] ),
                    'refID' => $refID,
                    'webside_link' => 'http://' . $_SERVER['HTTP_HOST'],
                    'inscription' => stripslashes( $inscription ),
                    'personal_note' => stripslashes( $personal_note ),
                    'is_in_website' => true,
                    'hide_padtop' => true 
                );
                $arraySeting = array(
                    'StoreID' => $sid,
                    'nickname' => $pass['emailName'],
                    'email' => $pass['emailAddress'],
                    'addtime ' => time(),
                    'inscription' => $inscription,
                    'personal_note' => $personal_note 
                );
                $objEmail    = new emailClass();
                if ( @$objEmail->send( $arrParams, 'email_template/email_referer.tpl' ) ) {
                    $k++;
                    $socObj->sendemailintoDB( $arraySeting );
                }
                unset( $arraySeting );
                unset( $objEmail );
            }
        }
    } else {
        for ($i = 0, $k = 0; $i < 10; $i++) {
            if (filter_var( $_REQUEST['emailaddress'][$i], FILTER_VALIDATE_EMAIL)) {
                $arrParams   = array(
                     'reftype' => 'referrs',
                    'To' => $_REQUEST['emailaddress'][$i],
                    'Subject' => 'Your friend invites you to join Food Marketplace',
                    'fromname' => $own_name,
                    'From' => $own_email,
                    'nickname' => stripslashes( $_REQUEST['nickname'][$i] ),
                    'refID' => $refID,
                    'webside_link' => 'http://' . $_SERVER['HTTP_HOST'],
                    'inscription' => stripslashes( $inscription ),
                    'personal_note' => stripslashes( $personal_note ),
                    'is_in_website' => true,
                    'hide_padtop' => true 
                );
                $arraySeting = array(
                     'StoreID' => $sid,
                    'nickname' => $_REQUEST['nickname'][$i],
                    'email' => $_REQUEST['emailaddress'][$i],
                    'addtime ' => time(),
                    'inscription' => $inscription,
                    'personal_note' => $personal_note 
                );
                $smarty->assign('req', $arrParams);
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type: text/html; charset=" . PB_CHARSET . "" . "\r\n";
                $headers .= "To: " . $arrParams['nickname'] . " <" . $arrParams['To'] . ">" . "\r\n";
                $headers .= "From: " . $arrParams['fromname'] . " <" . $arrParams['From'] . ">" . "\r\n";
                $message   = $smarty->fetch( 'email_template/email_referer.tpl' );
                $mail_sent = mail( $arrParams['To'], $arrParams['Subject'], $message, $headers );
                if ( $mail_sent ) {
                    $k++;
                    $socObj->sendemailintoDB( $arraySeting );
                }
                unset( $arraySeting );
            }
        }
    }
    if ($k > 1) {
        $req['msg'] = "$k emails have been sent.";
    } else {
        $req['msg'] = "$k email has been sent.";
    }
    $url = '/myrace.php?tab=tabrefer&msg=' . urlencode($req['msg']);
    header( 'Location: ' . $url );
    exit;
}

if (isset($team)) {
	$smarty->assign('team_name', $team->team_name);
	$smarty->assign('captain', $team->captain);
	$smarty->assign('team_members', $team->fetchMembers());
} else {
	$smarty->assign('no_team', true);
}

function signup_commission($amount, $percentage) {
	$value = floatval($amount*$percentage/100);
	return (ceil($value/5) * 5);
}


$ref_configs = $socObj->getRefconfig($_SESSION['StoreID']);
$commission_percentage = $ref_configs['percent'];

$signup_retailer = signup_commission(365, $commission_percentage);
$signup_link = signup_commission(250, $commission_percentage);

$commission_text = $socObj->displayPageFromCMS(124);
$smarty->assign('commission_text', $commission_text['aboutPage']);

$smarty->assign('commission_percentage', $commission_percentage);
$smarty->assign('signup_retailer', $signup_retailer);
$smarty->assign('signup_link', $signup_link);

$bonus_points_page = $socObj->displayPageFromCMS(125);
$smarty->assign('bonus_points_page', $bonus_points_page['aboutPage']);

$smarty->assign('req', $req);

if (isset($_REQUEST['tab'])) {
	$smarty->assign('tab', $_REQUEST['tab']);
}

$smarty->assign('race_page', true);
$smarty->assign('pageTitle', 'Race');
$smarty->assign('contentStyle', 'float: left;width: 930px!important;padding: 0px; margin: 0px;');
$content = $smarty->fetch('race/myrace.tpl');
$smarty->assign('sidebar', 0);
$smarty->assign('hideLeftMenu', 1);
$smarty->assign('show_left_cms', 0);
$smarty -> assign('content', $content);
$smarty->assign('is_content',1);
$smarty->assign('session', $_SESSION);
$smarty -> display($template_tpl);
unset($smarty);
?>