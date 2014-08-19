<?php
@session_start();
include_once('../include/smartyconfig.php');
include_once('class.login.php');
include_once('maininc.php');
include_once('functions.php');
include_once('class.soc.php');
include_once('class.team.php');
include_once('class.point.php');

$objLogin = new login();
if (!$objLogin -> checkLogin()) {
	unset($objLogin);
	header('Location: ../admin/');
	exit;
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case 'leaderboard':
			showLeaderboard();
			break;
		case 'teams':
			showTeamsPage();
			break;
		case 'comission':
			$comissionUpdate = array('status' => $_POST['value']);
			$dbcon->update_record('aus_soc_referrer', $comissionUpdate, 'WHERE id='.$_POST['id']);
			echo 'updated';
			break;
	}
	exit;
}

function ordinal($cdnl) {
    $test_c = abs($cdnl) % 10;
    $ext = ((abs($cdnl) %100 < 21 && abs($cdnl) %100 > 4) ? 'th'
            : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1)
            ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
    return $cdnl.$ext;
}

function showTeamsPage() {
	$teams = Team::getList();
	echo '
		<table class="scores">
			<thead>
				<tr>
					<th>Rank</th>
					<th>Team</th>
					<th>Members</th>
					<th>Total Points Score</th>
					<th>View</th>					
				</tr>
			</thead>
			<tbody>';
			$i = 1;
			if (isset($teams['list']) && is_array($teams['list']) && (count($teams['list']) > 0)) {
				foreach ($teams['list'] as $team) {	
					echo '<tr tag="'.$team->team_id.'" class="'.(($team->rank == 1) ? 'first' : (($i % 2 != 0) ? 'odd' : '')).' team_row">';
					echo '	<td>';
					if ($team->rank == 1) {
						echo '<img src="/skin/red/images/race/cup-1.png" /> 1st';
					} else {
						echo '<div style="margin-left:16px;color:#FFF;font-weight:bold;">'.ordinal($team->rank).'</div>';
					}
					echo '</td>';
					echo '<td>'.$team->team_name.'</td>';
					echo '<td>'.$team->members.'</td>';
					echo '<td class="points">'.$team->points.'</td>';
					echo '<td><a href="index.php?act=leaderboard&team='.$team->team_id.'">View</a></td>';
					echo '</tr>';
					$i++;
				}
			} else {
				echo '
					<tr>
						<td colspan="5">There are no teams</td>
					</tr>';
			}
	echo '
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5">
						<div style="float:left;">';
						if (!empty($teams['last_p'])) {		
							echo '<img class="page_button" id="team_prev" src="/skin/red/images/race/prev-img.jpg"/>';
						}
	echo 	'			</div>
						<div style="float:right;">';
						if (!empty($teams['next_p'])) {	
							echo '<img class="page_button" id="team_next" src="/skin/red/images/race/next-im.jpg" />';
						}
	echo	'
						</div>
					</td>
				</tr>
			</tfoot>
			<script>
				function load_teams(page) {
					$.ajax({
						url: "leaderboard.php",
						type: "POST",
						data: {action: "teams", p: page} 					
					}).done(function(data) {
						$("#teams").html(data);
					});
				}
				$(document).ready(function() {
					$("#team_prev").click(function() {
						load_teams('.$teams['last_p'].');
					});
					$("#team_next").click(function() {
						load_teams('.$teams['next_p'].');
					});
				});
			</script>
		</table>
	';
}

function showLeaderboard() {
	$objPoint = new Point();
	$points = $objPoint->getRecordLists();
	if (isset($points['list'])) {
		$counter = 1;
		echo '
		<table class="scores">
					<thead>
						<tr>
							<th>Rank</th>
							<th>Nickname</th>
							<th>Suburb</th>
							<th>State</th>
							<th>Total Points Score</th>
							<th>View</th>
						</tr>
					</thead>
					<tbody>';
					if (is_array($points['list'])) {
						foreach ($points['list'] as $l) {
							echo '<tr class="'.(($l['rank'] == 1)?'first':(($l['rank'] == 2)?'second':(($l['rank'] == 3)?'third':(($counter % 2 != 0) ? 'odd' : '')))).'">';
							echo '<td>';
							if ($l['rank'] == 1) {
								echo '<img src="/skin/red/images/race/cup-1.png" /> 1st';
							} else if ($l['rank'] == 2) {
								echo '<img src="/skin/red/images/race/cup-2.png" /> 2nd';
							} else if ($l['rank'] == 3) {
								echo '<img src="/skin/red/images/race/cup-3.png" /> 3rd';
							} else {
								echo '<div style="margin-left:16px;color:#FFF;font-weight:bold;">'.ordinal($l['rank']).'</div>';
							}
							echo '</td>';
							echo '<td><a target="_blank" href="/'.$l['bu_urlstring'].'" title="'.$l['bu_nickname'].'">'.$l['bu_nickname'].'</a></td>';
							echo '<td>'.$l['bu_suburb'].'</td>';
							echo '<td>'.$l['stateName'].'</td>';
							echo '<td class="points">'.$l['total_points'].'</td>';
							echo '<td><a href="index.php?act=leaderboard&show='.$l['StoreID'].'">View</a></td>';
							echo '</tr>';
							$counter++;
						}
					} else {
						echo '<tr><td colspan="6">There are no individual points</td></tr>';
					}
		echo '
					</tbody>
					<tfoot>
						<tr>
							<td colspan="6">
								<div style="float:left;">
								';
								if (!empty($points['last_p'])) {						
									echo '<img class="page_button" id="leaderboard_prev" src="/skin/red/images/race/prev-img.jpg"/>';
								}
		echo '
								</div>
								<div style="float:right;">';
								if (!empty($points['next_p'])) {	
									echo '<img class="page_button" id="leaderboard_next" src="/skin/red/images/race/next-im.jpg" />';
								}
		echo '
								</div>
							</td>
						</tr>
					</tfoot>
					<script>
						function load_leaderboard(page) {
							$.ajax({
								url: "leaderboard.php",
								type: "POST",
								data: {action: "leaderboard", p: page} 					
							}).done(function(data) {
								$("#individual_scores").html(data);
							});
						}
						$(document).ready(function() {
							$("#leaderboard_prev").click(function() {
								load_leaderboard('.$points['last_p'].');
							});
							$("#leaderboard_next").click(function() {
								load_leaderboard('.$points['next_p'].');
							});
						});
					</script>
				</table>';
	} else {
		echo '
		<tr>
			<td colspan="5">No Records</td>
		</tr>';
	}
}

function showCommissions() {
	global $dbcon;
	$fetch_referrals = "SELECT ref.* FROM aus_soc_referrer ref INNER JOIN aus_soc_bu_detail detail ON detail.StoreID = ref.StoreID WHERE ref.status = '0'";
	$dbcon->execute_query($fetch_referrals);
	$referrals_result = $dbcon->fetch_records();
	if (isset($referrals_result)) {	
		echo '
		<table class="records">
				<thead>
					<tr>
						<th>Details</th>
						<th>Amount</th>
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
					<td>'.$referral['details'].'</td>
					<td>'.$referral['amount'].'</td>
					<td>
						<select class="status" tag="'.$referral['id'].'">
							<option '.(($referral['status'] == 0)? 'selected="selected"' : '').' value="0">UNPAID</option>
							<option '.(($referral['status'] == 1)? 'selected="selected"' : '').' value="1">PAID</option>
						</select>
					</td>
					<td><a href="index.php?act=leaderboard&show='.$referral['StoreID'].'">View</a></td>
				</tr>';
				$balance += $referral['amount'];
			}
			echo '</tbody>';
			echo '<tfoot>
					<tr>
						<td></td>
						<td colspan="2">$'.$balance.'</td>
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

if (isset($_GET['team'])) {
	$team = Team::fetch($_GET['team']);
	if (isset($team)) {
		$members = $team->fetchMembers();
		$smarty->assign('team', true);
		$smarty->assign('team_name', $team->team_name);
		$smarty->assign('members', $members);	
	}
}

if (isset($_GET['show'])) {
	$detail_result = $dbcon->getOne("SELECT * FROM aus_soc_bu_detail WHERE StoreID = '".$_GET['show']."'");
	if (isset($detail_result)) {
		$name = $detail_result['bu_name'];
		
		$objPoint = new Point();
		$points = $objPoint->getPointInfo($_GET['show']);
		
		$fetch_referrals = "SELECT * FROM aus_soc_referrer WHERE StoreID = '".$_GET['show']."'";
		$dbcon->execute_query($fetch_referrals);
		$referrals_result = $dbcon->fetch_records();
		
		$account_query = "SELECT * FROM bank_details WHERE user_id = '".$_GET['show']."'";
		$bank_result = $dbcon->getOne($account_query);
		
		$smarty->assign('show', true);
		$smarty->assign('points', $points);
		$smarty->assign('referrals', $referrals_result);
		$smarty->assign('bank_details', $bank_result); 
		$smarty->assign('name', $name);
	}
}
		
$content = $smarty->fetch('admin_leaderboard.tpl');
$smarty->assign('content', $content);
$smarty->display('index.tpl');
unset($smarty);
exit;
?>