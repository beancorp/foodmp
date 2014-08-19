<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Facelike extends common
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
    
    public function getCurRaceInfo($race=1)
    {
		$sql = "SELECT * FROM {$this->table}facelike_race WHERE 1 ORDER BY id DESC, modify_date DESC LIMIT 1";
		return $this->dbcon->getOne($sql);
    }
    
    public function getCurRaceRecords($race=1, $type='item')
    {
    	$race_info = $this->getCurRaceInfo($race);
    	$race_info['description'] = nl2br($race_info['description']);
    	$race_info['enddate_format'] = date("ga T ".str_replace('%', '', DATAFORMAT_DB), $race_info['end_date']);
    	$race_info['lefttime'] = $race_info['end_date'] - time();
    	$race_info['lefttime'] = $race_info['lefttime'] > 0 ? $race_info['lefttime'] : 0;
    	
		$perPage	=	20;
		$maxPage    = 	3;		
		$pageno		=	empty($_REQUEST['p']) ? 1 :$_REQUEST['p'];
		$pageno		=   $pageno > $maxPage ? $maxPage : $pageno;
		
        $sqlWhere = ' WHERE 1';
        if ($type) {
        	$sqlWhere .= " AND facelike.type='$type' \n";
        }
        
        $product_table_ary = array(
        	'0'		=> 		$this->table.'product',
        	'1'		=> 		$this->table.'product_realestate',
        	'2'		=> 		$this->table.'product_automotive',
        	'3'		=> 		$this->table.'product_job',
        	'5'		=> 		$this->table.'product_foodwine',
        );
        
        $sql = "SELECT SUM( facelike.num ) AS total, facelike.pid, MAX(facelike.timestamp) AS ts, detail.*, state.stateName, state.description \n".
					" FROM ".$this->table."facelike_records facelike,".$this->table."bu_detail detail \n".
		   			" LEFT JOIN {$this->table}state state ON(detail.bu_state=state.id) \n".
					" $sqlWhere \n".
					" AND facelike.StoreID=detail.StoreID \n".
					" AND facelike.timestamp>='{$race_info['start_date']}' \n".
					" AND facelike.timestamp<='{$race_info['end_date']}' \n".
					" GROUP BY facelike.url ORDER BY total DESC, ts ASC \n";

		$this -> dbcon -> execute_query($sql);
		$tmp_ary = $this -> dbcon -> fetch_records(true);
		$i = 1;
    	$num = $perPage * $maxPage;
		$store_ary = $tmp_list = $list = array();        
		if ($tmp_ary) {
			foreach ($tmp_ary as $val) {
				if (in_array($val['StoreID'], $store_ary)) {
					continue;
				}
				$product_table = $product_table_ary[$val['attribute']];
				$sql = "SELECT product.*, detail.bu_urlstring FROM $product_table AS product, {$this->table}bu_detail AS detail WHERE product.StoreID=detail.StoreID AND product.pid='{$val['pid']}'";
				$res = $this->dbcon->getOne($sql);
				if (!$res) {
					continue;
				}
				
				$val['rank'] = $i;
				$val['item_name'] = $res['item_name'];
				$val['bu_urlstring'] = $res['bu_urlstring'];
				$val['url_item_name'] = $res['url_item_name'];
				$tmp_list[]  = $val;
				$store_ary[] = $val['StoreID'];
				$i++;
				if ($i > $num) {
					break;
				}
			}
		}
				
		$count		=	$i - 1;
		($pageno * $perPage > $count) ? $pageno = ceil($count/$perPage) : '';
		$start	= ($pageno-1) * $perPage;		
		$end = $start + $perPage;
		foreach ($tmp_list as $key => $val) {
			if ($key >= $start && $key < $end) {
				$list[] = $val;
			}
			if ($key >= $end) {
				break;
			}
		}
		$res['list'] = $list;
		$res['race_info'] = $race_info;
		$res['total_page'] = ceil($count/$perPage);
		
		
		$pager		=	& new pagerClass();
		$res['pager'] =	$pager -> getLink($pageno,$count,$perPage,'pageno');
		unset($pager);
						
        return $res;
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
    	
    	if ($reduce && !$this->checkProductExist($StoreID, $pid)) {
    		return false;
    	}
    	
    	if (!$reduce && $this->checkProductExist($StoreID, $pid)) {
    		return false;
    	}
    	
    	return true;
    }
    
    private function _addPointByReferer($referer_id=0, $StoreID=0, $pid=0, $feetype='product', $product_count=0, $market_type=0, $reduce)
    {    	
    	if (!$this->race_on || empty($referer_id) || empty($StoreID)) {
    		return false;
    	}
    	
    	/*$socObj = new socClass();
        $store_info = $socObj->getStoreInfo($referer_id);
        
        if($store_info['attribute'] == 0 || $store_info['attribute'] == 5) {
        	$market_type = $store_info['attribute'];
        } else {
        	$market_type = 1;
        }*/
        
        if ($market_type == 0) {
        	if ($product_count == 1) {
        		$point_type_id = 8;
        	} else {
        		$point_type_id = 9;
        	}
        } elseif ($market_type == 1) {
        	if ($feetype == 'year') {
        		if ($product_count == 1 || (!$reduce && $this->year_first_item)) {
        			$point_type_id = 10;
        		} else {
        			$point_type_id = 11;
        		}
        	} elseif ($product_count == 1) {
        		$point_type_id = 12;
        	}
        	
	        if ($reduce) {
	        		$point_type_id = $this->ref_point_type;
	        } else {
	        	$this->updateListRecordPointType($StoreID, $pid, true, $point_type_id);
	        }
        } elseif ($market_type == 5) {
        	if ($feetype == 'subscriber') {
        		$point_type_id = 14;
        	} elseif ($product_count == 4) {
        		$point_type_id = 13;
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
    	$sql = " SELECT SUM(point) AS total_points, detail.bu_nickname, detail.bu_suburb, state.stateName, state.description \n".
    		   " FROM {$this->table}point_records record,{$this->table}bu_detail detail \n".
    		   " LEFT JOIN {$this->table}state state ON(detail.bu_state=state.id) \n".
    		   " WHERE record.StoreID=detail.StoreID GROUP BY record.StoreID ORDER BY total_points DESC";
		$this -> dbcon -> execute_query($sql);
		$res = $this -> dbcon -> fetch_records(true);
		
		if ($res) {
			foreach ($res as $key => $val) {
				$val['rank'] = $key + 1;
				$res[$key] = $val;
			}	
		}
				
		return $res;
    }
    
    public function getPointInfo($StoreID, $isref=false)
    {
    	//Item list points
    	$sql = " SELECT SUM(record.point) AS total_list_points \n".
    		   " FROM {$this->table}point_records record \n".
    		   " LEFT JOIN {$this->table}point_type pointtype ON record.type=pointtype.id \n".
    		   " WHERE record.StoreID='$StoreID' AND pointtype.point_type='LP' ORDER BY total_list_points DESC";
		$point_info = $this->dbcon->getOne($sql);
		
		$socObj = new socClass();
		$store_info = $socObj->displayStoreWebside();
		$res = $store_info['info'];
		//select from product table
		if ($store_info['info']['attribute'] == 5 || $store_info['info']['attribute'] == 0) {
			$business_info = $socObj->getBusinessHome($StoreID);
			$res['total_items'] = $business_info['count']['product'] ? $business_info['count']['product'] : $business_info['product']['count'];
		} else {				
			$res['total_items'] = $this->getProductListRecordsCount($StoreID);
		}
		$res['total_list_points'] = $this->getTotalPoints($StoreID, 'LP');	

		//Total Point (LP, RP AND BP)
    	$sql = " SELECT SUM(point) AS total_points \n".
    		   " FROM {$this->table}point_records record \n".
    		   " WHERE record.StoreID='$StoreID' ORDER BY total_points DESC";
		$point_info = $this->dbcon->getOne($sql);
		$res['total_points'] = $this->getTotalPoints($StoreID);
		
		//RP User lists
		$sql = " SELECT SUM(record.point) AS total_rp_points,SUM(IF(pointtype.list_type='register',record.point,0)) AS ref_points, record.*, detail.bu_nickname \n".
    		   " FROM {$this->table}point_records record \n".
    		   " LEFT JOIN {$this->table}bu_detail detail ON record.ref_store_id=detail.StoreID \n".
    		   " LEFT JOIN {$this->table}point_type pointtype ON record.type=pointtype.id \n".
    		   " WHERE record.StoreID='$StoreID' AND pointtype.point_type='RP' AND pointtype.point_type='RP' GROUP BY record.ref_store_id ORDER BY record.timestamp DESC";
		$this -> dbcon -> execute_query($sql);
		$rp_list = $this->dbcon->fetch_records(true);
		$total_rp_points = 0;
		
		if ($rp_list) {
			foreach ($rp_list as $key => $rp) {
				
				//select from product table
				$store_info = $socObj->getBusinessHome($rp['ref_store_id']);
				if ($store_info['detail']['attribute'] == 5 || $store_info['detail']['attribute'] == 0) {
					$rp['total_items'] = $store_info['count']['product'] ? $store_info['count']['product'] : $store_info['product']['count'];
				} else {				
				   	//select from point_list_records table
				   	$rp['total_items'] = $this->getProductListRecordsCount($rp['ref_store_id']);
				}
			   $rp['total_points'] = $this->getTotalPoints($rp['ref_store_id']);
				
				$rp_list[$key] = $rp;
				$total_rp_points += $rp['total_rp_points'];
			}	
		}
		
	
		$res['ref_list'] = $rp_list;
		$res['sub_points'] = $res['total_list_points'] + $total_rp_points;
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
    		   " WHERE record.StoreID='$StoreID' AND pointtype.point_type='$point_type' ORDER BY points DESC";
    	}
    	
    	$res = $this->dbcon->getOne($sql);
    	return $res['points'] ? $res['points'] : 0;
    }
}