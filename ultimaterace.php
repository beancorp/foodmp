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

$socObj = new socClass();
$socstoreObj = new socstoreClass();

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
					<th align="center" style="width: 100px;">Rank</th>
					<th width="50%">Team</th>
					<th>Members</th>
					<th align="right">Total Points Score</th>
				</tr>
			</thead>
			<tbody>';
			$i = 1;
			if (isset($teams['list']) && is_array($teams['list']) && (count($teams['list']) > 0)) {
				foreach ($teams['list'] as $team) {	
					echo '<tr tag="'.$team->team_id.'" class="'.(($team->rank == 1) ? 'first' : (($i % 2 != 0) ? 'odd' : '')).' team_row">';
					echo '	<td class="rank">';
					if ($team->rank == 1) {
						echo '<img src="/skin/red/images/race/cup-1.png" /> 1st';
					} else {
						echo '<div style="margin-left:16px;color:#FFF;font-weight:bold;">'.ordinal($team->rank).'</div>';
					}
					echo '</td>';
					echo '<td>'.$team->team_name.'</td>';
					echo '<td>'.$team->members.'</td>';
					echo '<td class="points">'.$team->points.'</td>';
					echo '</tr>';
					$i++;
				}
			} else {
				echo '
					<tr>
						<td colspan="4">There are no teams</td>
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
						url: "ultimaterace.php",
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

function showLeaderBoardPage() {
	$objPoint = new Point();
	$points = $objPoint->getRecordLists();
	if (isset($points['list'])) {
		$counter = 1;
		echo '
		<table class="scores">
					<thead>
						<tr>
							<th align="center" style="width: 100px;">Rank</th>
							<th>Nickname</th>
							<th>Suburb</th>
							<th>State</th>
							<th align="right">Total Points Score</th>
						</tr>
					</thead>
					<tbody>';
					if (is_array($points['list'])) {
						foreach ($points['list'] as $l) {
							echo '<tr class="'.(($l['rank'] == 1)?'first':(($l['rank'] == 2)?'second':(($l['rank'] == 3)?'third':(($counter % 2 != 0) ? 'odd' : '')))).'">';
							echo '<td class="rank">';
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
							
							echo '<td>';
							if ((isset($l['CustomerType']) && $l['CustomerType'] == 'seller') && (!empty($l['bu_urlstring']))) {
								echo '<a target="_blank" href="/'.$l['bu_urlstring'].'" title="'.$l['bu_nickname'].'">'.$l['bu_nickname'].'</a>';
							} else {
								echo $l['bu_nickname'];
							}
							echo '</td>';
							
							echo '<td>'.$l['bu_suburb'].'</td>';
							echo '<td>'.$l['stateName'].'</td>';
							echo '<td class="points">'.$l['total_points'].'</td>';
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
							<td colspan="5">
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
								url: "ultimaterace.php",
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
	}
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case 'members':
			if (isset($_POST['team_id'])) {
				$team = Team::fetch($_POST['team_id']);
				if (isset($team)) {
					echo '
						<div id="team_members_header">
							<div id="team_name">'.$team->team_name.'</div>
							<div id="close_button"></div>
						</div>
						<table class="scores">
							<thead>
								<tr>
									<th>Name</th>
									<th>Suburb</th>
									<th>State</th>
									<th align="right">Points</th>
								</tr>
							</thead>
							<tbody>
							';
					$members = $team->fetchMembers();
					$i = 0;
					$total = 0;
					foreach ($members as $member) {
						$even = (($i % 2) == 0);
						echo '
								<tr '.((!$even) ? 'class="odd"' : '').'>
									<td>'.((strlen($member->name) > 30) ? substr($member->name, 0, 30).'...' : $member->name).'</td>
									<td>'.$member->suburb.'</td>
									<td>'.$member->state.'</td>
									<td align="right">'.$member->points.'</td>
								</tr>
								';
						$total += $member->points;
						$i++;
					}
					echo '
							</tbody>
							<tfoot>
							<tr>
								<td colspan="3" style="text-align: right;">
									Total Points Score
								</td>
								<td>
								 '.$total.'
								</td>
							</tr>
							</tfoot>
						</table>';	
						
				}
			}
			break;
		case 'leaderboard':
			showLeaderBoardPage();
			break;
		case 'teams':
			showTeamsPage();
			break;
	}
	exit;
}

if (isset($_GET['terms'])) {
	$smarty->assign('show_terms', true);
	$terms_page = $socObj->displayPageFromCMS(122);
	$smarty->assign('terms_page', $terms_page['aboutPage']);
}

if (isset($_SESSION['StoreID'])) {
	$smarty->assign('show_myrace', true);
}

$race_information_page = $socObj->displayPageFromCMS(123);
$smarty->assign('race_information_page', $race_information_page['aboutPage']);

$commission_text = $socObj->displayPageFromCMS(124);
$smarty->assign('commission_text', $commission_text['aboutPage']);

$about_page = $socObj->displayPageFromCMS(121);
$smarty->assign('about_page', $about_page['aboutPage']);

function signup_commission($amount, $percentage) {
	$value = floatval($amount*$percentage/100);
	return (ceil($value/5) * 5);
}

$ref_configs = $socObj->getRefconfig();
$commission_percentage = $ref_configs['percent'];

$signup_retailer = signup_commission(365, $commission_percentage);
$signup_link = signup_commission(250, $commission_percentage);

$smarty->assign('commission_percentage', $commission_percentage);
$smarty->assign('signup_retailer', $signup_retailer);
$smarty->assign('signup_link', $signup_link);

$query	= "SELECT id, stateName, description FROM aus_soc_state ORDER BY description";
$result	= $dbcon->execute_query($query);
$state_list = $dbcon->fetch_records();
$suburb_data = array();

foreach($state_list as $state) {
	$query	= "SELECT suburb_id, suburb FROM aus_soc_suburb WHERE state = '".$state['stateName']."' ORDER BY suburb ASC";
	$result	= $dbcon->execute_query($query);
	$suburbs = $dbcon->fetch_records();
	$output = '';
	foreach($suburbs as $suburb) {
		$output .= '<option value="'.addslashes($suburb['suburb']).'">'.addslashes($suburb['suburb']).'</option>';
	}
	$suburb_data[$state['stateName']] = $output;
}

$smarty->assign('state_list', $state_list);
$smarty->assign('suburb_data', $suburb_data);

$smarty->assign('pageTitle','Race');
$smarty->assign('race_page', true);
$smarty->assign('contentStyle', 'float: left;width: 930px!important;padding: 0px; margin: 0px;');
$content = $smarty->fetch('race/leaderboard.tpl');
$smarty->assign('sidebar', 0);
$smarty->assign('hideLeftMenu', 1);
$smarty->assign('show_left_cms', 0);
$smarty -> assign('content', $content);
$smarty->assign('is_content',1);
$smarty->assign('session', $_SESSION);
$smarty->assign('ocp', 'signup');
$smarty -> display($template_tpl);
unset($smarty);
?>