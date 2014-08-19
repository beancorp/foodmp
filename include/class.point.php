<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Point extends common
{
	var $dbcon 	= 	null;
	var $table	=	'';
	var $smarty = 	null;
	var $lang	=	null;
	var $race_on = true;
	var $point_type = 0;
	var $ref_point_type = 0;
	var $year_first_item = false;
	
    function __construct()
    {
            $this -> dbcon  = &$GLOBALS['dbcon'];
            $this -> time_zone_offset = &$GLOBALS['time_zone_offset'];
            $this -> table	= &$GLOBALS['table'];
            $this -> smarty = &$GLOBALS['smarty'];
            $this -> lang	= &$GLOBALS['_LANG'];
        	$this -> race_on = $this->getRaceOn();
        	
    }
    
    public function getRaceOn()
    {
    	$sql = "SELECT * FROM {$this->table}config WHERE type='race' AND name='race_on'";
    	$res = $this->dbcon->getOne($sql);
    	return $res['value'];
    }
    
    public function addPointRecords($StoreID=0, $feetype='product', $pid=0, $reduce=false)
    {
		/*
    	$StoreID = $StoreID ? $StoreID : $_SESSION['StoreID'];
    	
        $_REQUEST['StoreID'] = $StoreID;
        $_REQUEST['proid'] = $pid;
        $_REQUEST['pre'] = 1;
        $socObj = new socClass();
        $req	=	$socObj -> displayStoreProduct(true);
    	if ($req['info']['attribute'] != 3 && $req['items']["product"][0]['images']['uploadCount'] < 1) {
    		$reduce = true;
    	}    	
    	
    	if (!$this->checkCanOperPoint($StoreID, $feetype, $pid, $reduce)) {
    		return false;
    	}
    	
        $store_info = $socObj->getStoreInfo($StoreID);
        
        if($store_info['attribute'] == 0 || $store_info['attribute'] == 5) {
        	$market_type = $store_info['attribute'];
        } else {
        	$market_type = 1;
        }
        
        if ($feetype != 'subscriber') {
        	$product_count = $this->addListsRecordAndGetCount($StoreID, $pid, $reduce);
        }
        
        $addrefpoint = true;
        $point_type_id = 0;
        $product_count = $reduce ? $product_count+1 : $product_count;
        
        $canaddpoint = ($product_count <= 100 || ($product_count <= 500 && $product_count % 10 == 0) || ($product_count > 500 && $product_count % 20 == 0)) ? true : false;
      	         
        if ($market_type == 0) {
        	if ($product_count == 1 && $store_info['israceold'] == 0) {
        		$point_type_id = 1;
        	} elseif ($canaddpoint) {
        		$point_type_id = 2;
        	}
        } elseif ($market_type == 1) {
        	if ($feetype == 'year') {
        		$this->checkYearListExist($StoreID, 4);
        		if (($product_count == 1 || (!$reduce && $this->year_first_item)) && $store_info['israceold'] == 0) {
        			$point_type_id = 4;
        		} elseif ($canaddpoint) {
        			$point_type_id = 5;
        		}
        	} elseif ($product_count == 1) {
        		if ($store_info['israceold'] == 1) {
        			$point_type_id = 5;
        		} else {
        			$point_type_id = 3;
        		}        		
        	}
        	
	        if ($reduce && ($this->point_type == 3 || $this->point_type == 0)) {
	        		$point_type_id = $this->point_type;
	        } else {
	        	$this->updateListRecordPointType($StoreID, $pid, false, $point_type_id);
	        }
        } elseif ($market_type == 5) {
        	if ($feetype == 'subscriber') {//Cancel the unique email alert subscribers points
        		//$point_type_id = 7;
        	} elseif ($product_count == 4 && $store_info['israceold'] == 0) {
        		$point_type_id = 6;
        	} elseif (($product_count > 4 || $store_info['israceold'] == 1) && $canaddpoint) {
        		$point_type_id = 16;        		
        	}
        }
        
        if ($point_type_id) {
	        $point_type_info = $this->getPointTypeInfo($point_type_id);
	        $list_points = $reduce ? '-'.$point_type_info['point'] : $point_type_info['point'];
	        
	        //Get referer StoreID AND add RP
	        $referer_id = $this->getRefererStoreIDByRefname($store_info['referrer']); 
	        
	        $point_data = array(
	        	'StoreID' 			=> 	$StoreID,
	        	'ref_store_id'		=>	$referer_id ? $referer_id : 0,
	        	'type'  			=> 	$point_type_id,
	        	'point'				=>	$list_points,
	        	'timestamp'			=> time()
	        );
	        
	        if($this->dbcon->insert_record($this->table.'point_records', $point_data) && $addrefpoint) {
	        	if (!$reduce) {
	        		include_once(SOC_INCLUDE_PATH.'/class.socstore.php');
					$stostoreObj = new socstoreClass();					
               	 	$stostoreObj->updateStoreRef($StoreID);
	        	}
	        	$this->_addPointByReferer($referer_id, $StoreID, $pid, $feetype, $product_count, $market_type, $reduce, $store_info['israceold']);
	        } else {
	        	return false;
	        }	
        }
		*/
        
        return true;
    }
	
	
    
    public function checkCanOperPoint($StoreID, $feetype, $pid, $reduce)
    {
    	if (!$this->race_on || empty($StoreID)) {
    		return false;
    	}
    	
    	$sql = "SELECT SUM(point) AS sum_point FROM {$this->table}point_records WHERE StoreID='$StoreID'";
    	$res = $this->dbcon->getOne($sql);
    	
    	if ($reduce && $res['sum_point'] <= 0) {
    		$sql = "DELETE FROM {$this->table}point_list_records WHERE StoreID='$StoreID' AND pid='$pid'";
    		$res = $this->dbcon->execute_query($sql);

    		return false;
    	}
    	
    	if ($feetype == 'subscriber') {
    		return true;
    	}
    	
    	if (empty($pid)) {
    		return false;
    	}
    	
    	if (!$reduce) {
	        $_REQUEST['StoreID'] = $StoreID;
	        $_REQUEST['proid'] = $pid;
	        $_REQUEST['pre'] = 1;
	        $socObj = new socClass();
	        $req	=	$socObj -> displayStoreProduct();
	    	if ($req['info']['attribute'] != 3 && ($req['items']["product"][0]['images']['uploadCount'] < 1 || $req['items']["product"][0]['israceold'] == 1)) {
	    		return false;
	    	}    		
    	}
    	
    	if ($reduce && !$this->checkProductExist($StoreID, $pid)) {
    		return false;
    	}
    	
    	if (!$reduce && $this->checkProductExist($StoreID, $pid)) {
    		return false;
    	}
    	
    	return true;
    }
    
    private function _addPointByReferer($referer_id=0, $StoreID=0, $pid=0, $feetype='product', $product_count=0, $market_type=0, $reduce, $olduser=0)
    {    	
    	if (!$this->race_on || empty($referer_id) || empty($StoreID)) {
    		return false;
    	}
        
        if ($market_type == 0) {
        	if ($product_count == 1 && !$olduser) {
        		$point_type_id = 8;
        	} else {
        		$point_type_id = 9;
        	}
        } elseif ($market_type == 1) {
        	if ($feetype == 'year') {
        		if (($product_count == 1 || (!$reduce && $this->year_first_item)) && !$olduser) {
        			$point_type_id = 10;
        		} else {
        			$point_type_id = 11;
        		}
        	} elseif ($product_count == 1) {
        		if ($olduser) {
        			$point_type_id = 11;
        		} else {
        			$point_type_id = 12;
        		}
        	}
        	
	        if ($reduce && ($this->point_type == 3 || $this->point_type == 0)) {
	        		$point_type_id = $this->ref_point_type;
	        } else {
	        	$this->updateListRecordPointType($StoreID, $pid, true, $point_type_id);
	        }
        } elseif ($market_type == 5) {
        	if ($feetype == 'subscriber') {
        		$point_type_id = 14;
        	} elseif ($product_count == 4 && !$olduser) {
        		$point_type_id = 13;
        	} elseif ($product_count > 4 || $olduser) {
        		$point_type_id = 17;
        	}	        	
        }
        
        $point_type_info = $this->getPointTypeInfo($point_type_id);
        $ref_points = $reduce ? '-'.$point_type_info['point'] : $point_type_info['point'];
        
        $point_data = array(
        	'StoreID' 			=> 	$referer_id,
        	'ref_store_id'		=>	$StoreID,
        	'type'  			=> 	$point_type_id,
        	'point'				=>	$ref_points,
        	'timestamp'			=>  time()
        );
        $this->dbcon->insert_record($this->table.'point_records', $point_data);
        
    	if (!$reduce) {
    		include_once(SOC_INCLUDE_PATH.'/class.socstore.php');
			$stostoreObj = new socstoreClass();					
       	 	$stostoreObj->updateStoreRef($referer_id);
    	}
    	
    	return true;
    }
    
    public function addListsRecordAndGetCount($StoreID=0, $pid=0, $reduce=false)
    {
    	if (empty($StoreID) || empty($pid)) {
    		return false;
    	}
    	
    	if ($reduce) {
    		$this->point_type = $this->getRecordPointType($StoreID, $pid);
    		$this->ref_point_type = $this->getRecordPointType($StoreID, $pid, true);
    		$sql = "DELETE FROM {$this->table}point_list_records WHERE StoreID='$StoreID' AND pid='$pid'";
    		$this->dbcon->execute_query($sql);
    	} else {
	    	$list_data = array(
	    		'StoreID' 		=> 	$StoreID,
	    		'pid'			=> 	$pid,
	    		'timestamp'		=> 	time()
	    	);
	    	$this->dbcon->insert_record($this->table.'point_list_records', $list_data);	
    	}
    	    	
    	$sql = "SELECT COUNT(*) AS count FROM {$this->table}point_list_records WHERE StoreID='$StoreID'";
    	$res = $this->dbcon->getOne($sql);    	
    	$count = $res ? $res['count'] : 0;
    	
    	//seller info
		$_query = "SELECT * FROM ".$this-> table."bu_detail where StoreID='$StoreID'";
		$arrTemp = $this->dbcon->getOne($_query);				    	
    	   	
    	if ($arrTemp['product_feetype'] == 'year' && ($arrTemp['attribute'] == 1 || $arrTemp['attribute'] == 2 || $arrTemp['attribute'] == 3)) {
    		$sql = "SELECT COUNT(*) AS num FROM {$this->table}point_list_records WHERE StoreID='$StoreID' AND timestamp<'{$arrTemp['product_renewal_time']}'";
    		$pre_year = $this->dbcon->getOne($sql);
    		if ($pre_year) {
    			$count -= $pre_year['num'];
    		}    		
    	}
    	
    	return $count;
    }
    
    public function getProductListRecordsCount($StoreID)
    {
    	$sql = "SELECT COUNT(*) AS count FROM {$this->table}point_list_records WHERE StoreID='$StoreID'";
    	$res = $this->dbcon->getOne($sql);
    	$count = $res ? $res['count'] : 0;
    	
    	return $count;
    }
    
    public function getPointTypeInfo($id=0) 
    {
    	$sql = "SELECT * FROM {$this->table}point_type WHERE id='$id'";
    	$res = $this->dbcon->getOne($sql);
    	return $res;
    }
    
    public function getRefererStoreIDByRefname($ref_name='') 
    {
    	if (empty($ref_name)) {
    		return ;
    	}
    	
    	$sql = "SELECT StoreID FROM {$this->table}bu_detail WHERE ref_name='$ref_name'";
    	$res = $this->dbcon->getOne($sql);
    	
    	return $res['StoreID'];
    }
	
    public function checkProductExist($StoreID, $pid)
    {
    	$sql = "SELECT * FROM {$this->table}point_list_records WHERE StoreID='$StoreID' AND pid='$pid'";
    	$res = $this->dbcon->getOne($sql);
    	
    	return $res;
    }
    
    function updateListRecordPointType($StoreID, $pid, $ref=false, $point_type='')
    {
    	$boolResult = false;
    	$data = array();
    	$strCondition ="where StoreID='$StoreID' and pid='$pid'";
    	if ($ref && $point_type) {
    		$data['ref_point_type'] = $point_type;
    	} elseif ($point_type) {
    		$data['point_type'] = $point_type;
    	}
    	
    	if ($data) {
    		$boolResult = $this->dbcon-> update_record($this->table."point_list_records", $data, $strCondition);
    	}
		
    	return $boolResult;
    }
    
    function getRecordPointType($StoreID, $pid, $ref=false) 
    {    	
    	$sql = "SELECT * FROM {$this->table}point_list_records WHERE StoreID='$StoreID' AND pid='$pid'";
    	$res = $this->dbcon->getOne($sql);
    	
    	if ($ref) {
    		return $res['ref_point_type'];
    	} else {
    		return $res['point_type'];
    	}
    }
    
    function checkYearListExist($StoreID, $point_type)
    {
    	$sql = "SELECT * FROM {$this->table}point_list_records WHERE StoreID='$StoreID' AND point_type='$point_type'";
    	$res = $this->dbcon->getOne($sql);
    	
    	$this->year_first_item = $res ? false : true;
    }
    
    public function getRecordLists()
    {
    	$sql = "SELECT COUNT(*) AS count FROM (SELECT SUM(point) AS total_points FROM {$this->table}point_records record, {$this->table}bu_detail detail WHERE record.StoreID=detail.StoreID GROUP BY record.StoreID) AS tmp WHERE 1";
    	$res = $this->dbcon->getOne($sql);
    	
    	$perPage = 20;	   
		$pageno		=	empty($_REQUEST['p']) ? 1 :$_REQUEST['p'];
    	$count		=	$res['count'];
		
		$pageno = ($pageno * $perPage > $count)? ceil($count/$perPage) : 1;
		
		$start	= ($pageno-1) * $perPage;		
		$end = $start + $perPage;
		
    	$sql = " SELECT detail.StoreID, SUM(point) AS total_points, MAX(record.timestamp) AS record_timestamp, \n".
    		   " detail.bu_urlstring, detail.CustomerType, detail.bu_nickname, detail.bu_suburb, state.stateName, state.description \n".
    		   " FROM {$this->table}point_records record,{$this->table}bu_detail detail \n".
			   " LEFT JOIN {$this->table}login lg ON(detail.StoreID = lg.StoreID) \n".
    		   " LEFT JOIN {$this->table}state state ON(detail.bu_state=state.id) \n".
    		   " WHERE record.StoreID=detail.StoreID AND lg.suspend=0 GROUP BY record.StoreID ORDER BY total_points DESC, record_timestamp ASC LIMIT $start, $perPage";
		$this -> dbcon -> execute_query($sql);
		$res = $this -> dbcon -> fetch_records(true);
		//echo $sql;
		
		
		$point = 0;
		$rank = $perPage * ($pageno - 1);
		if ($res) {
			foreach ($res as $key => $val) {
				if (0 && $point == $val['total_points']) {
					$val['rank'] = $rank;
				} else {
					$val['rank'] = $perPage * ($pageno - 1) + $key + 1;
					$rank = $val['rank'];
					$point = $val['total_points'];
				}
				
				$res[$key] = $val;
			}	
		}
		
		$last_p = ($pageno - 1) > 0 ? ($pageno - 1) : 0;
		$next_p = ($pageno * $perPage < $count) ? ($pageno + 1) : 0;
		$info = array(
			'last_p' => $last_p,
			'next_p' => $next_p,
			'list' => $res
		);
		
		return $info;
    }
    
    // public function getPointInfo($StoreID, $isref=false)
    // {
    	// //Item list points
    	// $sql = " SELECT SUM(record.point) AS total_list_points \n".
    		   // " FROM {$this->table}point_records record \n".
    		   // " LEFT JOIN {$this->table}point_type pointtype ON record.type=pointtype.id \n".
    		   // " WHERE record.StoreID='$StoreID' AND pointtype.point_type='LP' ORDER BY total_list_points DESC";
		// $point_info = $this->dbcon->getOne($sql);
		
		// $socObj = new socClass();
		// //$store_info = $socObj->displayStoreWebside();
		// //$res = $store_info['info'];
		// //select from product table
		// //$business_info = $socObj->getBusinessHome($StoreID);
		// //$res['total_items'] = $business_info['product']['count'];
		// $res = array();
		// $res['total_items'] = $this->getActiveProdNum($StoreID);
		// $res['total_list_points'] = $this->getTotalPoints($StoreID, 'LP');	

		// if (($res['attribute'] == 1 || $res['attribute'] == 2 || $res['attribute'] == 3) && $res['total_items'] <= 100) {
			// $res['total_items'] = ($res['total_list_points'] - 122) / 2 + 1;
			// $res['total_items'] = $res['total_items'] > 0 ? $res['total_items'] : 0;
			
			// if ($res['israceold']) {
				// $res['total_items'] = $res['total_list_points'] / 2;
			// }
		// }
		
		// //spec list point
		// $sql = " SELECT SUM(record.point) AS point \n".
    		   // " FROM {$this->table}point_records record \n".
    		   // " WHERE record.StoreID='$StoreID' AND type=3";
    	// $spec_point = $this->dbcon->getOne($sql);
    	// if ($spec_point) {
    		// $res['is_spec'] = true;
    		// $res['spec'] = $spec_point;
    		// $res['total_items'] = $res['total_items'];
    	// }
		
		// //Total Point (LP, RP AND BP)
    	// $sql = " SELECT SUM(point) AS total_points \n".
    		   // " FROM {$this->table}point_records record \n".
    		   // " WHERE record.StoreID='$StoreID' ORDER BY total_points DESC";
		// $point_info = $this->dbcon->getOne($sql);
		// $res['total_points'] = $this->getTotalPoints($StoreID);
		// $res['rank'] = $this->getRank($StoreID);
		
		// //RP User lists
		// $sql_tmp = " SELECT SUM(record.point) AS total_rp_points,SUM(IF(pointtype.list_type='register',record.point,0)) AS ref_points, record.*, detail.bu_name, detail.bu_nickname, detail.bu_urlstring, detail.CustomerType \n".
    		   // " FROM {$this->table}point_records record \n".
    		   // " LEFT JOIN {$this->table}bu_detail detail ON record.ref_store_id=detail.StoreID \n".
    		   // " LEFT JOIN {$this->table}point_type pointtype ON record.type=pointtype.id \n".
    		   // " WHERE record.StoreID='$StoreID' AND pointtype.point_type='RP' ";
    	// $sql = $sql_tmp . " AND pointtype.id != 12 GROUP BY record.ref_store_id ORDER BY record.timestamp DESC";
		// $this -> dbcon -> execute_query($sql);
		// $rp_list = $this->dbcon->fetch_records(true);
		// $total_rp_points = 0;
		
		// $sql = $sql_tmp . " AND pointtype.id = 12 GROUP BY record.ref_store_id ORDER BY record.timestamp DESC";
		// $this -> dbcon -> execute_query($sql);
		// $rp_list12 = $this->dbcon->fetch_records(true);
		
		// if ($rp_list12) {
			// $rp_list = $rp_list ? $rp_list : array();
			// foreach ($rp_list12 as $key => $val) {
				// $val['is_spec'] = true;
				// $rp_list12[$key] = $val;
			// }
			// $rp_list = array_merge($rp_list12, $rp_list);
		// }
		
		// if ($rp_list) {
			// foreach ($rp_list as $key => $rp) {
				
				// //select from product table
				// $store_info = $socObj->getBusinessHome($rp['ref_store_id']);
				// //$rp['total_items'] = $store_info['product']['count'];
				// $total_items = $this->getActiveProdNum($rp['ref_store_id']);
				// $rp['total_items'] = $rp['is_spec'] ? ($rp['total_rp_points'] > 0 ? 1 : 0) : $total_items;
			   	// $rp['total_points'] = $this->getTotalPoints($rp['ref_store_id']);
			   	
			   	// switch ($store_info['detail']['attribute']) {
			   		// case '0':
			   			// $rp['market'] = 'Auctions + Buy Now';
			   			// break;
			   		// case '1':
			   			// $rp['market'] = 'Real Estate';
			   			// break;
			   		// case '2':
			   			// $rp['market'] = 'Auto';
			   			// break;
			   		// case '3':
			   			// $rp['market'] = 'Careers';
			   			// break;
			   		// case '5':
			   		// default:
			   			// $rp['market'] = 'Food & Wine';
			   			// break;
			   	// }
				// if (($store_info['detail']['attribute'] == 1 || $store_info['detail']['attribute'] == 2 || $store_info['detail']['attribute'] == 3) && !$rp['is_spec'] && $rp['total_items'] <= 100) {
					// $rp['total_items'] = $rp['total_rp_points'] - 121 + 1;
					// $rp['total_items'] = $rp['total_items'] > 0 ? $rp['total_items'] : 0;
					
					// if ($store_info['detail']['israceold']) {
						// $rp['total_items'] = $rp['total_rp_points'];
					// }
				// }
				
				// $rp_list[$key] = $rp;
				// $total_rp_points += $rp['total_rp_points'];
			// }	
		// }
		
	
		// $res['ref_list'] = $rp_list;
		// $res['lp_points'] = $res['spec']['point'] + $res['total_list_points'] + $total_rp_points;
		
		
    	// //BP Total points
		// $sql = " SELECT SUM(record.point) AS bp_points \n".
    		   // " FROM {$this->table}point_records record \n".
    		   // " LEFT JOIN {$this->table}point_type pointtype ON record.type=pointtype.id \n".
    		   // " WHERE record.StoreID='$StoreID' AND pointtype.point_type='BP'";
    	// $bp_info = $this->dbcon->getOne($sql);
    		   
    	// //BP Records
		// $sql = " SELECT record.point, answer.is_correct, question.question, site.site_name, pointtype.id AS type \n".
    		   // " FROM {$this->table}point_records record \n".
    		   // " LEFT JOIN {$this->table}point_answer_records answer ON record.question_record_id=answer.id \n".
    		   // " LEFT JOIN {$this->table}point_question question ON answer.question_id=question.id \n".
    		   // " LEFT JOIN {$this->table}point_site site ON answer.site_id=site.id \n".
    		   // " LEFT JOIN {$this->table}point_type pointtype ON record.type=pointtype.id \n".
    		   // " WHERE record.StoreID='$StoreID' AND pointtype.point_type='BP' ORDER BY record.timestamp DESC";
    		   
		// $this -> dbcon -> execute_query($sql);
		// $list = $this->dbcon->fetch_records(true);
		
		// $res['bp_points'] = $bp_info['bp_points'] ? $bp_info['bp_points'] : 0;
		// $res['bp_list'] = $list;		
		
		// return $res;
    // }
	
    public function getPointInfo($StoreID, $isref=false)
    {
		$query_points = "SELECT pr.*, IFNULL(detail.bu_name, 'Bonus') As name 
						FROM aus_soc_point_records pr
						LEFT JOIN aus_soc_bu_detail detail ON pr.ref_store_id = detail.StoreID
						WHERE pr.StoreID = '".$StoreID."'";
		$this->dbcon->execute_query($query_points);
		$rp_list = $this->dbcon->fetch_records(true);
		$res = array();
		$res['ref_list'] = $rp_list;
		return $res;
    }
    
    function getTotalPoints($StoreID, $point_type='')
    {
    	$point_type = strtoupper($point_type);
    	if ($point_type == '') {//Total Point (LP, RP AND BP)
    		
    		$sql = " SELECT SUM(point) AS points \n".
    		   " FROM {$this->table}point_records record \n".
    		   " WHERE record.StoreID='$StoreID' ORDER BY points DESC";
    	} else {//Get total point by type(LP, RP, BP)
    		
    		$sql = " SELECT SUM(record.point) AS points \n".
    		   " FROM {$this->table}point_records record \n".
    		   " LEFT JOIN {$this->table}point_type pointtype ON record.type=pointtype.id \n".
    		   " WHERE record.StoreID='$StoreID' AND pointtype.point_type='$point_type' AND pointtype.id!=3 ORDER BY points DESC";
    	}
    	
    	$res = $this->dbcon->getOne($sql);
    	return $res['points'] ? $res['points'] : 0;
    }
    
    function getReferrer($StoreID)
    {
		$sql = " SELECT * FROM {$this->table}store_referrer WHERE StoreID='$StoreID' LIMIT 1";
    	
    	$res = $this->dbcon->getOne($sql);
    	return $res;
    }
    
    public function getRandQuestion($StoreID=0, $domain=0, $question_id=0)
    {
    	$StoreID = $StoreID ? $StoreID : $_SESSION['StoreID'];
    	
    	$site_info = $this->getSiteInfo($domain);
    	if (!$site_info) {
    		return 1;
    	}
    	
    	if ($question_id) {
    		$random_questionid = $question_id;
    	} else {
	    	$sql = "SELECT id FROM {$this->table}point_question WHERE id NOT IN (SELECT question_id FROM {$this->table}point_answer_records WHERE StoreID='$StoreID') AND site_id='{$site_info['id']}' AND deleted='0'";
			$this -> dbcon -> execute_query($sql);
			$questionid_ary = $this->dbcon->fetch_records(true);
			
			if (!$questionid_ary) {
				return 2;
			}
			
			shuffle($questionid_ary);
			$random_key = array_rand($questionid_ary, 1);
			$random_questionid = $questionid_ary[$random_key]['id'];
    	}
    	
		$sql = "SELECT * FROM {$this->table}point_question WHERE id='$random_questionid'";
		$res = $this->dbcon->getOne($sql);
		
		$sql = " SELECT * FROM {$this->table}point_answer WHERE question_id='$random_questionid' AND deleted='0' ORDER BY `order` ASC";
		$this -> dbcon -> execute_query($sql);
		$res['answer_list'] = $this->dbcon->fetch_records(true);
		
		return $res;
    }
    
    function addStoreQuestion($StoreID, $domain, $question_id)
    {
    	$StoreID = $StoreID ? $StoreID : $_SESSION['StoreID'];
    	
    	$site_info = $this->getSiteInfo($domain);
    	if (!$site_info || empty($question_id) || empty($StoreID)) {
    		return false;
    	}
    	
    	$data['StoreID'] 		= 	$StoreID;
    	$data['site_id'] 		= 	$site_info['id'];    	
    	$data['question_id'] 	= 	$question_id;    	
    	$data['start_time'] 	= 	time(); 
    	$data['ip'] 			= 	getip(); 
    	$this->dbcon->insert_record($this->table.'point_answer_records', $data);
    	
    	$question_record_id = $this->dbcon->insert_id();
    	$data = array(
    		'StoreID' 					=> 	$StoreID,
    		'question_record_id' 		=> 	$question_record_id,
    		'type'						=> 	15,
    		'point'						=> 	0,
    		'timestamp'					=> 	time(),
    	);
		$this->dbcon->insert_record($this->table.'point_records', $data);   
			
		return true;
    }
    
    public function getSiteInfo($domain='', $sid=0)
    {
    	if (empty($domain) && empty($sid)) {
    		return false;
    	}
    	
    	if ($domain) {
    		$sql_where = "domain='$domain'";
    	} elseif ($sid) {
    		$sql_where = "id='$sid'";
    	}
    	
    	$sql = "SELECT * FROM {$this->table}point_site WHERE $sql_where AND deleted='0'";
    	$res = $this->dbcon->getOne($sql);   
		$res['status'] = $res ? true : false; 	
		$res['left_time'] = $res['max_time'];
		$res['max_minute'] = floor($res['max_time'] / 60);
		$res['max_second'] = abs($res['max_time'] % 60);
    	
    	return $res;
    }
    
    public function checkAnswer($question_id, $answer)
    {
    	if (empty($answer) || !is_array($answer)) {
			$is_correct = 0;
    	} else {
			$sql = " SELECT id FROM {$this->table}point_answer WHERE question_id='$question_id' AND status='1' AND deleted='0' ORDER BY `order` ASC";
			$this -> dbcon -> execute_query($sql);
			$tmp_ary = $this->dbcon->fetch_records(true);
			if (!$tmp_ary || !is_array($tmp_ary)) {
					$is_correct = 0;
			} else {
				foreach ($tmp_ary as $value) {
					$correct_answer_ary[] = $value['id'];
				}
				
				$cmp = array_diff($correct_answer_ary, $answer);
				if (!$cmp || empty($cmp)) {
					$is_correct = 1;
				} else {
					$is_correct = 0;
				}	
			}
    	}
		
		return $is_correct;
    }
    
    public function addBRPoint($domain, $question_id, $is_correct=1, $answer=array(), $StoreID=0)
    {
    	$StoreID = $StoreID ? $StoreID : $_SESSION['StoreID'];
    	if (empty($domain) || empty($StoreID)) {
    		return false;
    	}
    	
    	$site_info = $this->getSiteInfo($domain);
    	$point = $is_correct ? $site_info['point'] : 0;
    	$question_record_info = $this->getAnswerRecordInfo($question_id, $domain, $StoreID);
    	
    	//Update point_answer_records
    	$answer_ids = implode(',', $answer);
		$data = array(
			'is_correct'	=> 	$is_correct,
			'answer'		=> 	$answer_ids,
			'end_time'		=> 	time(),
			'ip'			=> 	getip(),
			'finish'		=> 	1,
		);
    	$this->dbcon->update_record($this->table.'point_answer_records', $data, "WHERE id='{$question_record_info['id']}'");
    	unset($data);
		
    	//Update point_records
    	$data = array(
    		'point'	 		=> 	$point
    	);
		return $this->dbcon->update_record($this->table.'point_records', $data, "WHERE StoreID='$StoreID' AND question_record_id='{$question_record_info['id']}' AND type='15'");
    }
    
    public function getAnswerRecordInfo($question_id, $domain, $StoreID)
    {    	
    	$StoreID = $StoreID ? $StoreID : $_SESSION['StoreID'];
    	if (empty($question_id) || empty($domain)) {
    		return false;
    	}
    	
    	$site_info = $this->getSiteInfo($domain);
    	$sql = "SELECT * FROM {$this->table}point_answer_records WHERE site_id='{$site_info['id']}' AND question_id='$question_id' AND StoreID='$StoreID'";
    	$res = $this->dbcon->getOne($sql);
    	
    	return $res;
    }
    
    public function getAnswerInfoByChoose($question_id, $answer=array())
    {    	
    	if ($question_id && $answer && is_array($answer)) {
	    	$answer_str = implode(',', $answer);
			$sql = " SELECT * FROM {$this->table}point_answer WHERE question_id='$question_id' AND id IN ($answer_str) AND deleted='0' ORDER BY `order` ASC";
			$this -> dbcon -> execute_query($sql);
			
			return $this->dbcon->fetch_records(true);	
    	}
    	
    	return array();
    }
    
    public function getCorrectAnswerInfo($question_id)
    {    	
    	if ($question_id) {
			$sql = " SELECT * FROM {$this->table}point_answer WHERE question_id='$question_id' AND status='1' AND deleted='0' ORDER BY `order` ASC";
			$this -> dbcon -> execute_query($sql);
			
			return $this->dbcon->fetch_records(true);	
    	}
    	
    	return array();
    }
    
    public function checkHasAnswer($domain='', $StoreID=0)
    {
    	$StoreID = $StoreID ? $StoreID : $_SESSION['StoreID'];
    	if (empty($domain) || empty($StoreID)) {
    		return true;
    	}
    	
    	$site_info = $this->getSiteInfo($domain);
    	
    	$sql = "SELECT * FROM {$this->table}point_answer_records WHERE site_id='{$site_info['id']}' AND StoreID='$StoreID'";
    	$res = $this->dbcon->getOne($sql);
    	
    	return $res;
    }
    
    public function getBPPointInfo($StoreID)
    {
    	//BP Total points
		$sql = " SELECT SUM(record.point) AS bp_points \n".
    		   " FROM {$this->table}point_records record \n".
    		   " LEFT JOIN {$this->table}point_type pointtype ON record.type=pointtype.id \n".
    		   " WHERE record.StoreID='$StoreID' AND pointtype.point_type='BP'";
    	$res = $this->dbcon->getOne($sql);
    	$bp_points = $res['bp_points'];
    	unset($res);
    		   
    	//BP Records
		$sql = " SELECT record.point, answer.is_correct, question.question, site.site_name \n".
    		   " FROM {$this->table}point_records record \n".
    		   " LEFT JOIN {$this->table}point_answer_records answer ON record.question_record_id=answer.id \n".
    		   " LEFT JOIN {$this->table}point_question question ON answer.question_id=question.id \n".
    		   " LEFT JOIN {$this->table}point_site site ON answer.site_id=site.id \n".
    		   " LEFT JOIN {$this->table}point_type pointtype ON record.type=pointtype.id \n".
    		   " WHERE record.StoreID='$StoreID' AND pointtype.point_type='BP' ORDER BY record.timestamp DESC";
    		   
		$this -> dbcon -> execute_query($sql);
		$list = $this->dbcon->fetch_records(true);
		
		$res['bp_points'] = $bp_points;
		$res['bp_list'] = $list;		
		$res['total_points'] = $this->getTotalPoints($StoreID);
		
		return $res;
    }
    
    public function getRank($StoreID=0, $point=0)
    {
    	if (empty($StoreID)) {
    		return 0;
    	}
    	if (empty($point)) {
    		$point = $this->getTotalPoints($StoreID);
    	}
    	/*$ref_info = $this->getReferrer($StoreID);    	
    	$sql = "SELECT COUNT(record.point) AS total_point \n".
	    	  "FROM {$this->table}point_records record, {$this->table}store_referrer referrer \n".
	    	  "WHERE record.StoreID=referrer.StoreID \n".
	    	  "AND total_point>'{$point}' \n".
	    	  "OR ( \n".
	    	  "total_point='{$point}' AND (referrer.ref>'{$ref_info['ref']}' OR (referrer.ref-referrer.ref0)>'({$ref_info['ref']}-{$ref_info['ref0']})') \n".
	    	  ")";*/
	    	  
    	$sql = " SELECT SUM(point) AS total_points, \n".
    		   " IF(referrer.ref, referrer.ref, 0) AS refer1, IF(referrer.ref, referrer.ref-referrer.ref0, 0) AS refer2, \n".
    		   " detail.StoreID, detail.bu_nickname, detail.bu_suburb, state.stateName, state.description \n".
    		   " FROM {$this->table}point_records record,{$this->table}bu_detail detail \n".
			   " LEFT JOIN {$this->table}login lg ON(detail.StoreID = lg.StoreID) \n".
    		   " LEFT JOIN {$this->table}state state ON(detail.bu_state=state.id) \n".
    		   " LEFT JOIN {$this->table}store_referrer referrer ON(detail.StoreID=referrer.StoreID) \n".
    		   " WHERE record.StoreID=detail.StoreID AND lg.suspend=0 GROUP BY record.StoreID HAVING total_points>='{$point}' ORDER BY total_points DESC, refer1 DESC, refer2 DESC, detail.bu_nickname DESC, record.id ASC";
		
    	$this -> dbcon -> execute_query($sql);
		$res = $this -> dbcon -> fetch_records(true);
    	
		$rank = 1;
		foreach ($res as $key => $val) {
			if ($val['StoreID'] == $StoreID) {
				break;
			}
			$rank++;
		}
		
		return $rank;
    }
    
    public function getActiveProdNum($StoreID)
    {
    	$_REQUEST['StoreID'] = $StoreID;
    	
    	//seller info
		$_query = "SELECT * FROM ".$this-> table."bu_detail where StoreID='$StoreID'";
		$arrTemp = $this->dbcon->getOne($_query);
		
    	//product info
		switch ($arrTemp['attribute']){
			case 0:
				$_query	= "select pid from ".$this->table."product where StoreID='$StoreID' and deleted != 'YES' ";
				break;
			case 1:
				$_query	= "select pid from ".$this->table."product_realestate where StoreID='$StoreID' and deleted=0";
				break;
			case 2:
				$_query	= "select pid from ".$this->table."product_automotive where StoreID='$StoreID' and deleted=0";
				break;
			case 5:
				$_query	= "select pid from ".$this->table."product_foodwine where StoreID='$StoreID' and deleted=0";
				break;

			default:
				$attribute = $arrTemp['attribute'];
				$subAttrib = $arrTemp['subAttrib'];
				if($attribute == 3 && $subAttrib == 1){
					$job_where = " and category=1 ";
				}else{
					$job_where = " and category=2 ";
				}
				$_query	= "select pid from ".$this->table."product_job where StoreID='$StoreID' and deleted=0 $job_where";
				break;
		}
		$this->dbcon->execute_query($_query);
		$product_list = $this->dbcon->fetch_records(true);
		$product_count = 0;
		if (is_array($product_list)){
			$socObj = new socClass();
			foreach ($product_list as $product) {
				$_REQUEST['proid'] = $product['pid'];
		        $_REQUEST['pre'] = 1;
		        $req	=	$socObj -> displayStoreProduct();
		    	if ($req['items']['product'][0]['images']['uploadCount'] >= 1 && $req['items']['product'][0]['israceold'] != 1) {
		    		$product_count++;
		    	}
			}
		}
		    	
    	if ($arrTemp['product_feetype'] == 'year' && ($arrTemp['attribute'] == 1 || $arrTemp['attribute'] == 2 || $arrTemp['attribute'] == 3)) {
    		$sql = "SELECT COUNT(*) AS num FROM {$this->table}point_list_records WHERE StoreID='$StoreID' AND timestamp<'{$arrTemp['product_renewal_time']}'";
    		$pre_year = $this->dbcon->getOne($sql);
    		if ($pre_year) {
    			$product_count -= $pre_year['num'];
    		}    		
    	}
		
		if ($product_count <= 100) {
			$product_count = $product_count;
		} elseif ($product_count <= 500) {
			$product_count = floor($product_count / 10) * 10;
		} elseif ($product_count > 500) {
			$product_count = floor($product_count / 20) * 20;
		}
		
		if ($arrTemp['attribute'] == 5 && $product_count < 4) {
			$product_count = 0;
		}
		
		return $product_count;
    }
}