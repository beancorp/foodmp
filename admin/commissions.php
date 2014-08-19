<?php
@session_start();
include_once('../include/smartyconfig.php');
include_once('class.login.php');
include_once('maininc.php');
include_once('functions.php');
include_once('class.soc.php');

$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case 'comission':
			$comissionUpdate = array('status' => $_POST['value']);
			$dbcon->update_record('aus_soc_referrer', $comissionUpdate, 'WHERE id='.$_POST['id']);
			break;
	}
	exit;
}

function showCommissions() {
	global $dbcon;
	$fetch_referrals = "SELECT detail.bu_name, detail.bu_nickname, ref.* FROM aus_soc_referrer ref INNER JOIN aus_soc_bu_detail detail ON detail.StoreID = ref.StoreID WHERE ref.status = '0'";
	$dbcon->execute_query($fetch_referrals);
	$referrals_result = $dbcon->fetch_records();
	if (isset($referrals_result)) {	
		echo '
		<table class="records">
				<thead>
					<tr>
						<th>Username</th>
						<th>Details</th>
						<th>Amount</th>
						<th>Time</th>
						<th>Status</th>
						<th>View Member</th>
					</tr>
				</thead>';
		if (!empty($referrals_result)) {
			echo '<tbody>';
			$balance = 0;
			foreach ($referrals_result as $referral) {
				echo '
				<tr>
					<td>'.$referral['bu_name'].'</td>
					<td>'.$referral['details'].'</td>
					<td>'.$referral['amount'].'</td>
					<td>'.date('d/m/Y', $referral['addtime']).'</td>
					<td>
						<select class="status" tag="'.$referral['id'].'">
							<option '.(($referral['status'] == 0)? 'selected="selected"' : '').' value="0">UNPAID</option>
							<option '.(($referral['status'] == 1)? 'selected="selected"' : '').' value="1">PAID</option>
						</select>
					</td>
					<td><a href="index.php?act=commissions&show='.$referral['StoreID'].'">View</a></td>
				</tr>';
				$balance += $referral['amount'];
			}
			echo '</tbody>';
			echo '<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td colspan="2">$'.$balance.'</td>
						<td></td>
						<td></td>
					  </tr>
				  </tfoot>
			';
		} else {
			echo '
				<tfoot>
				<tr>	
					<td colspan="4">No Referrals</td>
				</tr>
				</tfoot>';
		}
		echo '
			</table>
			<script>
				$(document).ready(function() {
					$(".status").change(function() {
						var id = $(this).attr("tag");
						$.ajax({
							url: "leaderboard.php",
							type: "POST",
							data: {action: "comission", id: id, value: $(this).val()} 					
						}).done(function(data) {
						});
					});
				});
			</script>';
	}
}

if (isset($_GET['show'])) {
	$detail_result = $dbcon->getOne("SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_GET['show']."'");
	if (isset($detail_result)) {
		$name = $detail_result['bu_name'];
		
		$fetch_referrals = "SELECT * FROM aus_soc_referrer WHERE StoreID = '".$_GET['show']."'";
		$dbcon->execute_query($fetch_referrals);
		$referrals_result = $dbcon->fetch_records();
		
		$account_query = "SELECT * FROM bank_details WHERE user_id = '".$_GET['show']."'";
		$bank_result = $dbcon->getOne($account_query);
		
		$smarty->assign('show', true);
		$smarty->assign('referrals', $referrals_result);
		$smarty->assign('bank_details', $bank_result); 
		$smarty->assign('name', $name);
	}
}
		
$content = $smarty->fetch('admin_commissions.tpl');
$smarty->assign('content', $content);
$smarty->display('index.tpl');
unset($smarty);
exit;
?>