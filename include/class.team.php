<?php
class DB extends mysqli {
	public function __construct() {
		global $host, $user, $pass, $database;
		parent::__construct($host, $user, $pass, $database);
	}
}

class TeamInvites {
	public $invite_id;
	public $team_id;
	public $user_id;
	public $status;
	
	public function __construct($invite_id, $team_id, $user_id, $status) {
		$this->invite_id = $invite_id;
		$this->team_id = $team_id;
		$this->user_id = $user_id;
		$this->status = $status;
	}
	
	public static function received($user_id) {
		$invites = array();
		$db = new DB();
		$statement = $db->prepare("SELECT * FROM team_invites WHERE user_id = ?");
		$statement->bind_param("i", $this->user_id);
		$statement->execute();
		$statement->bind_result($invite_id, $team_id, $user_id, $status);
		while($statement->fetch()) {
			$invites[] = new TeamInvites($invite_id, $team_id, $user_id, $status);
		}
		return $invites;
	}
	
	public static function sent($team_id) {
		$invites = array();
		$db = new DB();
		$statement = $db->prepare("SELECT * FROM team_invites WHERE team_id = ?");
		$statement->bind_param("i", $this->user_id);
		$statement->execute();
		$statement->bind_result($invite_id, $team_id, $user_id, $status);
		while($statement->fetch()) {
			$invites[] = new TeamInvites($invite_id, $team_id, $user_id, $status);
		}
		return $invites;
	}
	
	public static function remove($team_id) {
		$db = new DB();
		$statement = $db->prepare("DELETE FROM team_invites WHERE team_id = ?");
		$statement->bind_param("i", $team_id);
		$statement->execute();
		$statement->close();
	}
	
	public static function accept($invite_id) {
		$db = new DB();
		$statement = $db->prepare("SELECT team_id, user_id FROM team_invites WHERE invite_id = ? LIMIT 1");
		$statement->bind_param("i", $invite_id);
		$statement->execute();
		$statement->bind_result($team_id, $user_id);
		if ($statement->fetch()) {
			$statement->close();
			
			if (TeamMember::checkExists($user_id)) {
				if (TeamMember::leave($user_id)) {
					$member = new TeamMember(0, $team_id, $user_id);
					$member->save();
					self::remove($invite_id);
				}
			} else {
				$member = new TeamMember(0, $team_id, $user_id);
				$member->save();
				self::remove($invite_id);
			}
		}
	}	
}

class TeamEmailInvite {
	public $invite_id;
	public $team_id;
	public $name;
	public $email;
	public $code;
	
	public function __construct($invite_id, $team_id, $name, $email, $code = NULL) {
		$this->invite_id = $invite_id;
		$this->team_id = $team_id;
		$this->name = $name;
		$this->email = $email;
		if (isset($code)) {
			$this->code = $code;
		} else {
			$this->code = $this->getUniqueCode();
		}
	}
	
	public function save() {
		$db = new DB();
		if ($this->invite_id == 0) {
			$statement = $db->prepare("INSERT INTO team_email_invites SET team_id = ?, name = ?, email = ?, code = ?");
			$statement->bind_param("isss", $this->team_id, $this->name, $this->email, $this->code);
			if ($statement->execute()) {
				$this->invite_id = $db->insert_id;
				return true;
			}
		}
		return false;
	}
	
	public function getUniqueCode() {
		$code = strtoupper(substr(sha1(uniqid(rand(), true)), 0, 5));
		$db = new DB();
		$statement = $db->prepare("SELECT * FROM team_email_invites WHERE code = ? LIMIT 1");
		$statement->bind_param("s", $code);
		$statement->execute();
		if ($statement->fetch()) {
			return $this->getUniqueCode();
		}
		return $code;
	}
	
	public static function remove($team_id, $code) {
		$db = new DB();
		$statement = $db->prepare("DELETE FROM team_email_invites WHERE team_id = ? AND code = ?");
		$statement->bind_param("is", $team_id, $code);
		$statement->execute();
		$statement->close();
	}
	
	public static function removeAll($team_id) {
		$db = new DB();
		$statement = $db->prepare("DELETE FROM team_email_invites WHERE team_id = ?");
		$statement->bind_param("i", $team_id);
		$statement->execute();
		$statement->close();
	}
	
	public static function accept($code, $user_id) {
		$db = new DB();
		$statement = $db->prepare("SELECT team_id FROM team_email_invites WHERE code = ? LIMIT 1");
		$statement->bind_param("s", $code);
		$statement->execute();
		$statement->bind_result($team_id);
		if ($statement->fetch()) {
			if (!TeamMember::checkExists($user_id)) {
				$team = Team::fetch($team_id);
				if ($team->members < 10) {
					$member = new TeamMember(0, $team->team_id, $user_id);
					$member->save();
					self::remove($team->team_id, $code);
					return true;
				} else {
					return false;
				}
			}
		}
		return false;
	}
	
	public static function getList($team_id) {
		$invites = array();
		$db = new DB();
		$statement = $db->prepare("SELECT * FROM team_email_invites WHERE team_id = ?");
		$statement->bind_param("i", $team_id);
		$statement->execute();
		$statement->bind_result($invite_id, $team_id, $name, $email, $code);
		while($statement->fetch()) {
			$invites[] = new TeamEmailInvite($invite_id, $team_id, $name, $email, $code);
		}
		return $invites;
	}
}

class TeamMember {
	public $member_id;
	public $team_id;
	public $user_id;
	public $captain;
	
	public $name;
	public $points;
	
	public $suburb;
	public $state;
	
	public function __construct($member_id, $team_id, $user_id, $captain = 0) {
		$this->member_id = $member_id;
		$this->team_id = $team_id;
		$this->user_id = $user_id;
		$this->captain = $captain;
	}
	
	public function save() {
		$db = new DB();
		if ($this->member_id == 0) {
			$statement = $db->prepare("INSERT INTO team_member SET team_id = ?, user_id = ?, captain = ?");
			$statement->bind_param("iii", $this->team_id, $this->user_id, $this->captain);
			if ($statement->execute()) {
				$this->member_id = $db->insert_id;
				$statement->close();
				return true;
			}
		} else {
			$statement = $db->prepare("UPDATE team_member SET team_id = ?, user_id = ?, captain = ? WHERE member_id = ?");
			$statement->bind_param("iiii", $this->team_id, $this->user_id, $this->captain, $this->member_id);
			if ($statement->execute()) {
				$statement->close();
				return true;
			}
		}
		return false;
	}
	
	public function fetch($user_id) {
		$db = new DB();
		$statement = $db->prepare("SELECT tm.*, d.bu_nickname, SUM(point) As points, d.bu_suburb, s.stateName
		FROM team_member tm 
		INNER JOIN aus_soc_bu_detail d ON tm.user_id = d.StoreID 
		LEFT JOIN aus_soc_state s ON s.id = d.bu_state
		INNER JOIN aus_soc_point_records points ON points.StoreID = tm.user_id 
		WHERE tm.user_id = ?");
			
		$statement->bind_param("i", $user_id);
		$statement->execute();
		$statement->bind_result($member_id, $team_id, $user_id, $captain, $name, $points, $suburb, $state);
		if ($statement->fetch()) {
			$member = new TeamMember($member_id, $team_id, $user_id, $captain);
			$member->name = $name;
			$member->points = $points;
			$member->suburb = $suburb;
			$member->state = $state;
			return $member;
		}
		return NULL;
	}
	
	public static function promote($from, $to) {
		$member1 = self::fetch($from);
		$member2 = self::fetch($to);
		if ($member1->captain) {
			if ($member1->team_id == $member2->team_id) {
				$member1->captain = 0;
				$member1->save();
				
				$member2->captain = 1;
				$member2->save();
				
				return true;
			}
		}
		return false;
	}
	
	public static function leave($user_id) {
		$member = self::fetch($user_id);
		if (isset($member)) {
			$team = Team::fetch($member->team_id);
			if (isset($team)) {
				if (($member->captain == 0) 
					|| ($team->members == 1)) {
					
					$db = new DB();
					$statement = $db->prepare("DELETE FROM team_member WHERE member_id = ?");
					$statement->bind_param("i", $member->member_id);
					$statement->execute();
					$statement->close();

					if ($team->members == 1) {
						Team::remove($team->team_id);
						TeamEmailInvite::removeAll($team->team_id);
					}
					
					return true;
				}
			}
		}
		return false;
	}
	
	public static function checkExists($user_id) {
		$db = new DB();
		$statement = $db->prepare("SELECT team_id FROM team_member WHERE user_id = ?");
		$statement->bind_param("i", $user_id);
		$statement->execute();
		$statement->bind_result($team_id);
		if ($statement->fetch()) {
			$statement->close();
			return true;
		}
		$statement->close();
		return false;
	}
}

class Team {
	public $team_id;
	public $team_name;
	
	public $captain;
	
	public $members;
	public $points;
	
	public $rank;
	
	public function __construct($team_id, $team_name, $members = 0, $points = 0) {
		$this->team_id = $team_id;
		$this->team_name = $team_name;
		$this->members = $members;
		$this->points = $points;
	}
	
	public function addMember($user_id, $captain = 0) {
		$member = new TeamMember(0, $this->team_id, $user_id, $captain);
		return $member->save();
	}
	
	public function invite($name, $email, $greeting = '') {
		$invite = new TeamEmailInvite(0, $this->team_id, $name, $email);
		
		$member = TeamMember::fetch($_SESSION['StoreID']);
		
		
		$message = self::emailTemplate($name, $member->name, $this->team_name, $invite->code, $greeting);
		
					
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: FoodMarketplace <no-reply@'.EMAIL_DOMAIN.'>' . "\r\n";
		$mail = mail($email, 'Race Invite', $message, $headers);
		$invite->save();
	}
	
	public function fetchMembers($withoutCaptain = false) {
		$db = new DB();
		$members = array();
        $statement = $db->prepare("SELECT tm.*, d.bu_nickname, SUM(point) As points, d.bu_suburb, s.stateName
									FROM team_member tm 
									INNER JOIN aus_soc_bu_detail d ON tm.user_id = d.StoreID 
									LEFT JOIN aus_soc_state s ON s.id = d.bu_state
									LEFT JOIN aus_soc_point_records points ON points.StoreID = tm.user_id 
									WHERE tm.team_id = ? ".(($withoutCaptain) ? "AND tm.captain = 0" : "")." GROUP BY tm.user_id");
									
		$statement->bind_param("i", $this->team_id);
		$statement->execute();
		$statement->bind_result($member_id, $team_id, $user_id, $captain, $name, $points, $suburb, $state);
		while($statement->fetch()) {
			$member = new TeamMember($member_id, $team_id, $user_id, $captain);
			$member->name = $name;
			$member->points = ((!empty($points)) ? $points : 0);
			$member->suburb = $suburb;
			$member->state = $state;
			$members[] = $member;
		}
        return $members;
	}
	
	public function save() {
		$db = new DB();
		if ($this->team_id == 0) {
			$statement = $db->prepare("INSERT INTO team SET team_name = ?");
			$statement->bind_param("s", $this->team_name);
			if ($statement->execute()) {
				$this->team_id = $db->insert_id;
				return true;
			}
		} else {
			$statement = $db->prepare("UPDATE team SET team_name = ? WHERE team_id = ?");
			$statement->bind_param("si", $this->team_name, $this->team_id);
			if ($statement->execute()) {
				return true;
			}
		}
		return false;
	}
	
	public static function emailTemplate($name, $sender, $team, $code, $greeting = '') {
		$html = '
			<html>
				<head>
					<style>											
						#email_content {
							border-radius: 10px;
							margin: 0 25px 25px;
							padding: 10px;
							overflow: hidden;
						}
						
						.team_code {
							font-size: 16pt;
						}
					</style>
				</head>
				<body>
				<table>
					<tr>
						<td style="width: 200px;">
							<a href="'.SOC_HTTPS_HOST.'">
								<img src="'.SOC_HTTPS_HOST.'skin/red/images/logo-main.png" height="60px" width="183px">
							</a>
						</td>
						<td style="width: 400px;">&nbsp;</td>
						<td style="width: 200px;">
							<a href="'.SOC_HTTPS_HOST.'ultimaterace.php">
								<img src="'.SOC_HTTPS_HOST.'skin/red/images/race_logo.png">
							</a>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<div id="email_content">
								Hi '.$name.',
								
								'.((!empty($greeting)) ? '<br /><br />'.$greeting : '').'
								
								<br /><br />
								You have been invited by <strong>'.$sender.'</strong> to join their team, <strong>'.$team.'</strong>, in The Ultimate Race.
								
								<br /><br />
								By simply referring your local food retailers to join '.SITENAME.' you will earn yourself a cash <u>commission</u>, as well as earning yourself
								and your team points in <a href="'.SOC_HTTPS_HOST.'ultimaterace.php">The Ultimate Race</a>.
								
								<br /><br />
								To join '.$sender.'\'s team and compete in <a href="'.SOC_HTTPS_HOST.'ultimaterace.php">The Ultimate Race</a>, simply click on the link below to register.
								
								<br /><br />
								<em><a href="'.SOC_HTTPS_HOST.'soc.php?cp=register&team='.$code.'">Join Here</a></em>
								
								<br /><br />
								<strong>OR</strong>
								
								<br /><br />
								If you are already a member, you can join this team by logging in, and entering the following code in the <a href="'.SOC_HTTPS_HOST.'myrace.php?invite_code='.$code.'">Join Team</a> section:
								
								<br /><br />
								<strong class="team_code"><a href="'.SOC_HTTPS_HOST.'myrace.php?invite_code='.$code.'">INVITE CODE: '.$code.'</a></strong>
								
								<br /><br />
								Once you have successfully entered the team code you will be a member of <strong>'.$team.'</strong> and competing in <a href="'.SOC_HTTPS_HOST.'ultimaterace.php">The Ultimate Race</a>.
								
								<br /><br />
								Come and join the fun!
							</div>
						</td>
					</tr>				
				</table>
				</body>
			</html>
		';
		return $html;
	}
	
	public static function remove($team_id) {
		$db = new DB();
		$statement = $db->prepare("DELETE FROM team WHERE team_id = ?");
		$statement->bind_param("i", $team_id);
		$statement->execute();
		$statement->close();
	}
	
	public static function fetch($team_id) {
		$db = new DB();
		$statement = $db->prepare("SELECT t.*, (SELECT COUNT(*) FROM team_member WHERE team_id = t.team_id) As members FROM team t WHERE t.team_id = ?");
		$statement->bind_param("i", $team_id);
		$statement->execute();
		$statement->bind_result($team_id, $team_name, $members);
		if ($statement->fetch()) {
			return new Team($team_id, $team_name, $members);
		}
		return NULL;
	}
	
	public static function fetchMyTeam($user_id) {
		$db = new DB();
		$statement = $db->prepare("SELECT t.*, m.captain FROM team t INNER JOIN team_member m ON m.team_id = t.team_id WHERE m.user_id = ?");
		$statement->bind_param("i", $user_id);
		$statement->execute();
		$statement->bind_result($team_id, $team_name, $captain);
		if ($statement->fetch()) {
			$team = new Team($team_id, $team_name);
			$team->captain = $captain;
			return $team;
		}
		return NULL;
	}
	
	public static function getList() {
		
		$db = new DB();
		$count_statement = $db->prepare("SELECT COUNT(*) As count FROM team");
		$count_statement->execute();
		$count_statement->bind_result($count);
		if ($count_statement->fetch()) {
			$count_statement->close();
			
			$perPage = 20;	   
			$pageno	= empty($_REQUEST['p']) ? 1 :$_REQUEST['p'];
			if ($pageno * $perPage > $count) {
				$pageno = ceil($count/$perPage);
			}
			$start	= ($pageno-1) * $perPage;		
			$end = $start + $perPage;
			
			$db = new DB();
			$query = "SELECT t.*, (SELECT COUNT(*) FROM team_member WHERE team_id = t.team_id) As members, SUM(point) As points FROM team t INNER JOIN team_member tm ON t.team_id = tm.team_id
			LEFT JOIN aus_soc_point_records points ON points.StoreID = tm.user_id
			GROUP BY t.team_id HAVING members > 1 ORDER BY points DESC LIMIT $start, $perPage";
			
			$statement = $db->prepare($query);
			if (is_object($statement)) {
				$statement->execute();
				$statement->bind_result($team_id, $team_name, $members, $points);
				
				$teams = array();
				$i = 0;
				while($statement->fetch()) {
					$team = new Team($team_id, $team_name, $members, ((!empty($points)) ? $points : 0));
					$team->rank = $perPage * ($pageno - 1) + $i + 1;
					$teams[] = $team;
					$i++;
				}
			
				$last_p = ($pageno - 1) > 0 ? ($pageno - 1) : 0;
				$next_p = ($pageno * $perPage < $count) ? ($pageno + 1) : 0;
				$info = array(
					'last_p' => $last_p,
					'next_p' => $next_p,
					'list' => $teams
				);
				return $info;
			}
		}
		return NULL;
	}
}
?>