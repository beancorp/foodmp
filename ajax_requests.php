<?php
@session_start();
include_once ('include/config.php');
include_once ('include/smartyconfig.php');
include_once ('include/maininc.php');

if (isset($_GET['action'])) {
	switch($_GET['action']) {
		case 1:
			if (isset($_POST['state'])) {
				$state = mysql_real_escape_string($_POST['state']);
				$query	= "SELECT suburb_id, suburb FROM aus_soc_suburb WHERE state = '".$state."' ORDER BY suburb ASC";
				$result	= $dbcon->execute_query($query);
				$suburbs = $dbcon->fetch_records();
				$output = '';
				foreach($suburbs as $suburb) {
					$output .= '<option value="'.$suburb['suburb_id'].'">'.$suburb['suburb'].'</option>';
				}
				echo $output;
			}		
			break;
			
		case 2:
			$valid = true;
			if (isset($_POST['value'])) {
				if (isset($_POST['store_id'])) {
					$additional_query = ' AND StoreID != '.$_POST['store_id'];
				} else {
					$additional_query = '';
				}
			
				$query	= "SELECT * FROM aus_soc_bu_detail WHERE bu_urlstring = '".$_POST['value']."'".$additional_query;
				$result	= $dbcon->execute_query($query);
				if ($result) {
					$data = $dbcon->fetch_records();
					if ($data) {
						$valid = false;
					}
				}			
			}
			echo json_encode(array('valid' => $valid));
			break;
			
		case 3:
			$valid = true;
			if (isset($_POST['email'])) {
				$query	= "SELECT * FROM aus_soc_bu_detail WHERE bu_email = '".$_POST['email']."'";
				$result	= $dbcon->execute_query($query);
				if ($result) {
					$data = $dbcon->fetch_records();
					if ($data) {
						$valid = false;
					}
				}			
			}
			echo json_encode(array('valid' => $valid));
			break;
			
		case 4:
			$valid = true;
			if (isset($_POST['value'])) {
				if (isset($_POST['store_id'])) {
					$additional_query = ' AND StoreID != '.$_POST['store_id'];
				} else {
					$additional_query = '';
				}
			
				$query	= "SELECT * FROM aus_soc_bu_detail WHERE bu_nickname = '".$_POST['value']."'".$additional_query;
				$result	= $dbcon->execute_query($query);
				if ($result) {
					$data = $dbcon->fetch_records();
					if ($data) {
						$valid = false;
					}
				}			
			}
			echo json_encode(array('valid' => $valid));
			break;
		case 5:
			function getRemoteData($url) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				  'Auth-Key: 46fe5d77-4871-43cc-a6f0-41edbff6c9b2'
				));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$contents = curl_exec ($ch);
				curl_close ($ch);
				return $contents;
			}
			
			$suburb = strtoupper($_POST['suburb']);
			$state = strtoupper($_POST['state']);
			
			if (isset($state)) {
				if ($state == strtoupper('Australian Capital Territory')) {
					$state = 'ACT';
				} else if ($state == strtoupper('New South Wales')) {
					$state = 'NSW';
				} else if ($state == strtoupper('Northern Territory')) {
					$state = 'NT';
				} else if ($state == strtoupper('Queensland')) {
					$state = 'QLD';
				} else if ($state == strtoupper('South Australia')) {
					$state = 'SA';
				} else if ($state == strtoupper('Tasmania')) {
					$state = 'TAS';
				} else if ($state == strtoupper('Victoria')) {
					$state = 'VIC';
				} else if ($state == strtoupper('Western Australia')) {
					$state = 'WA';
				}
			}
			
			$result = getRemoteData('https://auspost.com.au/api/postcode/search?q='.$suburb.'&state='.$state);
			$data = explode("\n", $result);
			foreach($data as $row) {
				$value = explode("|", $row);
				if ($value[2] == $suburb) {
					echo $value[1];
					break;
				}
			}
			die();
			break;
		case 6:
			$values = array();
			$delivery = array();
			if (isset($_POST['delivery_text']) && isset($_POST['delivery_value'])) {
				foreach ($_POST['delivery_text'] as $id => $text) {
					$values[] = $_POST['delivery_value'][$id];
					$delivery[] = $text;
				}
			}
			
			$update_delivery = "UPDATE aus_soc_bu_detail SET bu_delivery_text = '".serialize($delivery)."', bu_delivery = '".serialize($values)."' WHERE StoreID = '".$_SESSION['StoreID']."'";
			
			if ($dbcon->execute_query($update_delivery)) {
				echo '<table cellpadding="4" style="background:#F1F1F1">';
				foreach($delivery as $key => $delivery_text) {
					echo '<tr>
							<td>
								<input class="ck_deliveryMethod" type="checkbox" onclick="enableCost(\'postage['.$key.']\',this);" title="'.$delivery_text.'" value="0" name="deliveryMethod['.$key.']">
								'.$delivery_text.'
							</td>
							<td valign="middle">  $</td>
							<td>
								<input class="inputB input_postage" type="text" style="width:40px;margin-right:5px;" value="'.$values[$key].'" disabled="" name="postage['.$key.']">
							</td>
						</tr>';
				}
				echo '</table>';
			}
			
			break;
	}

}
?>