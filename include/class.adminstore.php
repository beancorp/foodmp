<?php

/**
 * Thu Oct 16 17:29:05 GMT+08:00 2008 17:29:05
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * Store function and class
 * ------------------------------------------------------------
 * \include\class.adminstore.php
 */
class adminStore extends common {

    var $dbcon = null;
    var $table = '';
    var $smarty = null;
    var $lang = null;

    /**
     * @return void
     */
    public function __construct() {
        $this->dbcon = &$GLOBALS['dbcon'];
        $this->table = &$GLOBALS['table'];
        $this->smarty = &$GLOBALS['smarty'];
        $this->lang = &$GLOBALS['_LANG'];
    }

    /**
     * @return void
     */
    public function __destruct() {
        unset($this->dbcon, $this->table, $this->smarty, $this->lang);
    }

    /**
     * get store list
     *
     * @param int $pageno
     * @param string $strParam
     * @param boolean $notOld
     * @return array
     */
    public function getStoreList($pageno=1, $strParam='', $notOld = true, $field="", $orders="asc") {
        $arrResult = null;
        $pageno = $pageno > 0 ? $pageno : 1;
        $perPage = 18;

        $sqlWhere = "where (detail.customerType='seller' OR detail.customerType='listing') ";

        if ($strParam) {
            $arrParam = unserialize($strParam);
            if (!get_magic_quotes_runtime()) {
                $arrParam = striaddslashes_deep($arrParam);
            }
            if (isset($arrParam['state']) && $arrParam['state'] != '') {
                $sqlWhere .= " And detail.bu_state ='$arrParam[state]'";
            }
            if (isset($arrParam['suburb']) && $arrParam['suburb'] != '') {
                $sqlWhere .= " AND  detail.bu_suburb ='$arrParam[suburb]'";
            }
            if (isset($arrParam['attribute']) && $arrParam['attribute'] != '') {
                $sqlWhere .= " And detail.attribute ='$arrParam[attribute]'";
            }
            if (isset($arrParam['refferID']) && $arrParam['refferID'] != '') {
                $sqlWhere .= " And detail.ref_name like '%" . trim($arrParam['refferID']) . "%'";
            }
            if (isset($arrParam['bu_email']) && $arrParam['bu_email'] != '') {
                $sqlWhere .= " And detail.bu_email like '%" . trim($arrParam['bu_email']) . "%'";
            }
            if (isset($arrParam['suspend']) && $arrParam['suspend'] != '') {
                $sqlWhere .= " And lg.suspend='{$arrParam['suspend']}' ";
            }
            if (isset($arrParam['bu_name']) && $arrParam['bu_name'] != '') {
                $sqlWhere .= " And detail.bu_name like '%{$arrParam['bu_name']}%' ";
            }
			if (isset($arrParam['status']) && $arrParam['status'] != '') {
				if ($arrParam['status'] == 0 || $arrParam['status'] == 1) {
					$sqlWhere .= " And detail.status='{$arrParam['status']}' ";
				} else if ($arrParam['status'] == 2 || $arrParam['status'] == 3) {
					if ($arrParam['status'] == 2) {
						$sqlWhere .= " AND detail.status = 1 AND FROM_UNIXTIME(detail.launch_date) >= DATE(NOW() - INTERVAL 90 DAY) ";
					} else {
						$sqlWhere .= " AND detail.status = 1 AND FROM_UNIXTIME(detail.launch_date) <= DATE(NOW() - INTERVAL 90 DAY) ";
					}
				}
            }
        }

        switch ($field) {
            case 'bu_name':
                $order = " order by detail.bu_name $orders ";
                break;
            case 'bu_nickname':
                $order = " order by detail.bu_nickname $orders ";
                break;
            case 'launch_date':
                $order = " order by detail.launch_date $orders ";
                break;
            case 'ref_name':
                $order = " order by detail.ref_name $orders ";
                break;
            default:
                $order = "";
                break;
        }

        $query = "select count(*) from " . $this->table . "login lg," . $this->table . "bu_detail detail $sqlWhere and lg.StoreID=detail.StoreID order by detail.StoreID ";
        $this->dbcon->execute_query($query);
        $totalNum = $this->dbcon->fetch_records();
        $totalNum = $totalNum[0][0];

        ($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum / $perPage) : '';
        $start = ($pageno - 1) * $perPage;

        if ($totalNum) {
            $query = "select detail.ref_name,detail.StoreID,detail.bu_name,detail.renewalDate,detail.CustomerType,detail.bu_nickname, \n" .
                    " FROM_UNIXTIME(detail.launch_date,'" . DATAFORMAT_DB . "') as DateAdd,detail.launch_date,lg.suspend \n" .
                    " from " . $this->table . "login lg," . $this->table . "bu_detail detail \n" .
                    " $sqlWhere and lg.StoreID=detail.StoreID $order limit $start,$perPage";
            $arrResult['query'] = $query;
            $this->dbcon->execute_query($query);
            $arrTemp = $this->dbcon->fetch_records(true);

            if (is_array($arrTemp)) {
                $arrResult['list'] = & $arrTemp;

                //pager
                $params = array(
                    'perPage' => $perPage,
                    'totalItems' => $totalNum,
                    'currentPage' => $pageno,
                    'delta' => 15,
                    'onclick' => 'javascript:xajax_getStoreList(\'%d\',xajax.getFormValues(\'mainForm\'),' . $notOld . ',\'' . $field . '\',\'' . $orders . '\');return false;',
                    'append' => false,
                    'urlVar' => 'pageno',
                    'path' => '#',
                    'fileName' => '%d',
                );
                $pager = & Pager::factory($params);
                $arrResult['links'] = $pager->getLinks();
                $arrResult['links']['all'] = "[ " . $pager->numItems() . "/" . $pager->numPages() . " ] " . $arrResult['links']['all'];
            }
        }
        $arrResult['query'] = $query;
        $arrResult['sort']['page'] = $pageno;
        $arrResult['sort']['field'] = $field;
        $arrResult['sort']['notold'] = $notOld;
        $arrResult['sort']['order'] = $orders;
        unset($arrTemp, $params, $sqlWhere);

		return $arrResult;
    }
    /**
     * get store list
     *
     * @param int $pageno
     * @param string $strParam
     * @param boolean $notOld
     * @return array
     */
    public function getDuplicateList($pageno=1, $strParam='', $notOld = true, $field="", $orders="asc") {
        $arrResult = null;
        $pageno = $pageno > 0 ? $pageno : 1;
        $perPage = 180;

        $sqlWhere = "where detail.customerType='seller' ";
        $sqlWhere .= " And detail.attribute ='5'";

        if ($strParam) {
            $arrParam = unserialize($strParam);
            if (!get_magic_quotes_runtime()) {
                $arrParam = striaddslashes_deep($arrParam);
            }
            if (isset($arrParam['state']) && $arrParam['state'] != '') {
                $sqlWhere .= " And detail.bu_state ='$arrParam[state]'";
            }
            if (isset($arrParam['suburb']) && $arrParam['suburb'] != '') {
                $sqlWhere .= " AND  detail.bu_suburb ='$arrParam[suburb]'";
            }
            if (isset($arrParam['attribute']) && $arrParam['attribute'] != '') {
                $sqlWhere .= " And detail.attribute ='$arrParam[attribute]'";
            }
            if (isset($arrParam['refferID']) && $arrParam['refferID'] != '') {
                $sqlWhere .= " And detail.ref_name like '%" . trim($arrParam['refferID']) . "%'";
            }
            if (isset($arrParam['bu_email']) && $arrParam['bu_email'] != '') {
                $sqlWhere .= " And detail.bu_email like '%" . trim($arrParam['bu_email']) . "%'";
            }
            if (isset($arrParam['suspend']) && $arrParam['suspend'] != '') {
                $sqlWhere .= " And lg.suspend='{$arrParam['suspend']}' ";
            }
            if (isset($arrParam['bu_name']) && $arrParam['bu_name'] != '') {
                $sqlWhere .= " And detail.bu_name like '%{$arrParam['bu_name']}%' ";
            }
        }

        switch ($field) {
            case 'bu_name':
                $order = " order by detail.bu_name $orders ";
                break;
            case 'bu_nickname':
                $order = " order by detail.bu_nickname $orders ";
                break;
            case 'launch_date':
                $order = " order by detail.launch_date $orders ";
                break;
            case 'ref_name':
                $order = " order by detail.ref_name $orders ";
                break;
            default:
                $order = " order by detail.bu_name";
                break;
        }

        $query = "select count(*) from " . $this->table . "login lg," . $this->table . "bu_detail detail $sqlWhere and lg.StoreID=detail.StoreID order by detail.StoreID ";
        $this->dbcon->execute_query($query);
        $totalNum = $this->dbcon->fetch_records();
        $totalNum = $totalNum[0][0];

        ($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum / $perPage) : '';
        $start = ($pageno - 1) * $perPage;

        if ($totalNum) {
            $query = "select detail.ref_name,detail.StoreID,detail.bu_name, detail.subAttrib, detail.bu_address, detail.bu_urlstring,
                    detail.bu_state, detail.bu_suburb, detail.renewalDate,detail.CustomerType,detail.bu_nickname, \n" .
                    " FROM_UNIXTIME(detail.launch_date,'" . DATAFORMAT_DB . "') as DateAdd,detail.launch_date,lg.suspend \n" .
                    " from " . $this->table . "login lg," . $this->table . "bu_detail detail \n" .
                    " $sqlWhere and lg.StoreID=detail.StoreID $order limit $start,$perPage";
            $arrResult['query'] = $query;

            $this->dbcon->execute_query($query);
            $arrTemp = $this->dbcon->fetch_records(true);

            if (is_array($arrTemp)) {
                $arrResult['list'] = & $arrTemp;

                //pager
                $params = array(
                    'perPage' => $perPage,
                    'totalItems' => $totalNum,
                    'currentPage' => $pageno,
                    'delta' => 15,
                    'onclick' => 'javascript:xajax_getDuplicateList(\'%d\',xajax.getFormValues(\'mainForm\'),' . $notOld . ',\'' . $field . '\',\'' . $orders . '\');return false;',
                    'append' => false,
                    'urlVar' => 'pageno',
                    'path' => '#',
                    'fileName' => '%d',
                );
                $pager = & Pager::factory($params);
                $arrResult['links'] = $pager->getLinks();
                $arrResult['links']['all'] = "[ " . $pager->numItems() . "/" . $pager->numPages() . " ] " . $arrResult['links']['all'];
            }
        }
        $arrResult['query'] = $query;
        $arrResult['sort']['page'] = $pageno;
        $arrResult['sort']['field'] = $field;
        $arrResult['sort']['notold'] = $notOld;
        $arrResult['sort']['order'] = $orders;
        unset($arrTemp, $params, $sqlWhere);

        return $arrResult;
    }

    public function test(){
        return array(
            array("f1"=>"a1"),
            array("f1"=>"a2"),
            array("f1"=>"a3"),
        );

    }

    public function getDuplicateSubList($item){
//        return array(
//            array("f1"=>"a1"),
//            array("f1"=>"a2"),
//            array("f1"=>"a3"),
//        );
        $arrResult = $arrResult1 = $arrResult2 = null;
        $perPage = 5;
        $sqlWhere = "where detail.customerType='seller' ";
        $sqlWhere .= " And detail.attribute ='5'";
        $sqlWhere .= " And detail.bu_state ='{$item['bu_state']}'";
        $sqlWhere .= " AND  detail.bu_suburb ='{$item['bu_suburb']}'";

        $patterns = array('/\-/', '/\//', '/\,/', '/\bStreet\b/', '/\bSt\b/', '/\bRoad\b/', '/\bRd\b/');
        $replacements = array('', '', '', '');
        $key = preg_replace($patterns, $replacements, $item['bu_address']);
        $sqlWhere .= " And detail.StoreID not in (select login.StoreID from {$this->table}login as login )";

        $sqlWhere1 = $sqlWhere;
        $sqlWhere1 .= " And detail.bu_address like '%{$key}%'";
        $sqlWhere2 = $sqlWhere;
        $sqlWhere2 .= " And detail.bu_address like '%{$item['bu_address']}%'";

        $query = "select detail.ref_name,detail.StoreID,detail.bu_name, detail.subAttrib, detail.bu_address, detail.bu_urlstring, detail.renewalDate,detail.CustomerType,detail.bu_nickname, \n" .
                " FROM_UNIXTIME(detail.launch_date,'" . DATAFORMAT_DB . "') as DateAdd,detail.launch_date" .
                " from " . $this->table . "bu_detail detail" .
                " $sqlWhere1 order by bu_name limit $perPage";
        $arrResult['query'] = $query;
        $this->dbcon->execute_query($query);
        $arrResult1 = $this->dbcon->fetch_records(true);

        $query = "select detail.ref_name,detail.StoreID,detail.bu_name, detail.subAttrib, detail.bu_address, detail.bu_urlstring, detail.renewalDate,detail.CustomerType,detail.bu_nickname, \n" .
                " FROM_UNIXTIME(detail.launch_date,'" . DATAFORMAT_DB . "') as DateAdd,detail.launch_date" .
                " from " . $this->table . "bu_detail detail" .
                " $sqlWhere2 order by bu_name limit $perPage";
        $arrResult['query'] = $query;
        $this->dbcon->execute_query($query);
        $arrResult2 = $this->dbcon->fetch_records(true);

        if(is_array($arrResult1) && is_array($arrResult2)){
            $arrResult1 = $this->convert2kv($arrResult1, 'bu_name');
            $arrResult2 = $this->convert2kv($arrResult2, 'bu_name');

            $arrResult = array_merge($arrResult1, $arrResult2);
        }
        elseif(is_array($arrResult1)){
            $arrResult = $arrResult1;
        }
        else{
            $arrResult = $arrResult2;
        }

        return $arrResult;
    }

    public function convert2kv($arr, $key="StoreID"){
        $result = null;
        foreach($arr as $item){
//            $result["{$item['bu_name']}"] = $item;
            $result["{$item[$key]}"] = $item;
        }
        return $result;
    }

    public function getStoreList2($pageno=1, $strParam='', $notOld = true, $field="", $orders="asc") {
        $arrResult = null;
        $pageno = $pageno > 0 ? $pageno : 1;
        $perPage = 18;

        $sqlWhere = "where detail.attribute <> 0 and detail.subAttrib<>3 and ((detail.attribute=5 and detail.renewalDate<" . time() . ") OR (detail.attribute!=5 and detail.product_feetype='year' and detail.product_renewal_date<" . time() . ")) and detail.customerType='seller' ";

        if ($strParam) {
            $arrParam = unserialize($strParam);
            if (isset($arrParam['attribute']) && $arrParam['attribute'] != '') {
                $sqlWhere .= " And detail.attribute ='$arrParam[attribute]'";
            }
            if (isset($arrParam['state']) && $arrParam['state'] != '') {
                $sqlWhere .= " And detail.bu_state ='$arrParam[state]'";
            }
            if (isset($arrParam['suburb']) && $arrParam['suburb'] != '') {
                $sqlWhere .= " AND  detail.bu_suburb ='$arrParam[suburb]'";
            }
            if (isset($arrParam['refferID']) && $arrParam['refferID'] != '') {
                $sqlWhere .= " And detail.ref_name like '%{$arrParam['refferID']}%'";
            }
            if (isset($arrParam['bu_email']) && $arrParam['bu_email'] != '') {
                $sqlWhere .= " And detail.bu_email like '%" . trim($arrParam['bu_email']) . "%'";
            }
            if (isset($arrParam['suspend']) && $arrParam['suspend'] != '') {
                $sqlWhere .= " And lg.suspend='{$arrParam['suspend']}' ";
            }
            if (isset($arrParam['bu_name']) && $arrParam['bu_name'] != '') {
                $sqlWhere .= " And detail.bu_name like '%{$arrParam['bu_name']}%' ";
            }
        }

        switch ($field) {
            case 'bu_name':
                $order = " order by detail.bu_name $orders ";
                break;
            case 'bu_nickname':
                $order = " order by detail.bu_nickname $orders ";
                break;
            case 'launch_date':
                $order = " order by detail.launch_date $orders ";
                break;
            case 'ref_name':
                $order = " order by detail.ref_name $orders ";
                break;
            default:
                $order = "";
                break;
        }

        $query = "select count(*) from " . $this->table . "login lg," . $this->table . "bu_detail detail $sqlWhere and lg.StoreID=detail.StoreID order by detail.StoreID ";
        $this->dbcon->execute_query($query);
        $totalNum = $this->dbcon->fetch_records();
        $totalNum = $totalNum[0][0];
        ($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum / $perPage) : '';
        $start = ($pageno - 1) * $perPage;

        if ($totalNum) {
            $query = "select detail.ref_name,detail.StoreID,detail.attribute,detail.bu_name,detail.renewalDate,detail.product_renewal_date,detail.CustomerType,detail.bu_nickname, \n" .
                    " FROM_UNIXTIME(detail.launch_date,'" . DATAFORMAT_DB . "') as DateAdd,detail.launch_date \n" .
                    " from " . $this->table . "login lg," . $this->table . "bu_detail detail \n" .
                    " $sqlWhere and lg.StoreID=detail.StoreID $order limit $start,$perPage";
            $arrResult['query'] = $query;
            $this->dbcon->execute_query($query);
            $arrTemp = $this->dbcon->fetch_records(true);

            if (is_array($arrTemp)) {
                $arrResult['list'] = & $arrTemp;

                //pager
                $params = array(
                    'perPage' => $perPage,
                    'totalItems' => $totalNum,
                    'currentPage' => $pageno,
                    'delta' => 15,
                    'onclick' => 'javascript:xajax_getStoreList2(\'%d\',xajax.getFormValues(\'mainForm\'),' . $notOld . ',\'' . $field . '\',\'' . $orders . '\');return false;',
                    'append' => false,
                    'urlVar' => 'pageno',
                    'path' => '#',
                    'fileName' => '%d',
                );
                $pager = & Pager::factory($params);
                $arrResult['links'] = $pager->getLinks();
                $arrResult['links']['all'] = "[ " . $pager->numItems() . "/" . $pager->numPages() . " ] " . $arrResult['links']['all'];
            }
        }
        $arrResult['query'] = $query;
        $arrResult['sort']['page'] = $pageno;
        $arrResult['sort']['field'] = $field;
        $arrResult['sort']['notold'] = $notOld;
        $arrResult['sort']['order'] = $orders;
        unset($arrTemp, $params, $sqlWhere);

        return $arrResult;
    }

    public function getUserList($pageno=1, $strParam='', $notOld = true, $field="", $orders="asc") {
        $arrResult = null;
        $pageno = $pageno > 0 ? $pageno : 1;
        $perPage = 18;

        $sqlWhere = "where 1=1 ";
        if ($strParam) {
            if (is_array($strParam)) {
                $arrParam = $strParam;
            } else {
                $arrParam = unserialize($strParam);
            }
            if (!get_magic_quotes_runtime()) {
                $arrParam = striaddslashes_deep($arrParam);
            }
            if (isset($arrParam['state']) && $arrParam['state'] != '') {
                $sqlWhere .= " And detail.bu_state ='$arrParam[state]'";
            }
            if (isset($arrParam['suburb']) && $arrParam['suburb'] != '') {
                $sqlWhere .= " AND  detail.bu_suburb ='$arrParam[suburb]'";
            }
            if (isset($arrParam['attribute']) && $arrParam['attribute'] != '') {
                $sqlWhere .= " And detail.attribute ='$arrParam[attribute]'";
            }
        }
        switch ($field) {
            case 'bu_email':
                $order = " order by detail.bu_email $orders ";
                break;
            case 'bu_nickname':
                $order = " order by detail.bu_nickname $orders ";
                break;
            case 'from_type':
                $order = " order by of.from_type $orders ";
                break;
            case 'form_date':
                $order = " order by of.form_date $orders ";
                break;
            default:
                $order = "";
                break;
        }
        $query = "select count(*) from {$this->table}order_from of ";
        $query .="LEFT JOIN {$this->table}bu_detail detail ON detail.StoreID=of.StoreID $sqlWhere";
        $this->dbcon->execute_query($query);
        $totalNum = $this->dbcon->fetch_records();
        $totalNum = $totalNum[0][0];

        ($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum / $perPage) : '';
        $start = ($pageno - 1) * $perPage;
        if ($totalNum) {
            $query = "select * from {$this->table}order_from of ";
            $query .="LEFT JOIN {$this->table}bu_detail detail ON detail.StoreID=of.StoreID $sqlWhere $order limit $start,$perPage";
            $arrResult['query'] = $query;
            $this->dbcon->execute_query($query);
            $arrTemp = $this->dbcon->fetch_records(true);

            if (is_array($arrTemp)) {
                $arrResult['list'] = & $arrTemp;

                //pager
                $params = array(
                    'perPage' => $perPage,
                    'totalItems' => $totalNum,
                    'currentPage' => $pageno,
                    'delta' => 15,
                    'onclick' => 'javascript:xajax_getUserList(\'%d\',xajax.getFormValues(\'mainForm\'),' . $notOld . ',\'' . $field . '\',\'' . $orders . '\');return false;',
                    'append' => false,
                    'urlVar' => 'pageno',
                    'path' => '#',
                    'fileName' => '%d',
                );
                $pager = & Pager::factory($params);
                $arrResult['links'] = $pager->getLinks();
                $arrResult['links']['all'] = "[ " . $pager->numItems() . "/" . $pager->numPages() . " ] " . $arrResult['links']['all'];
            }
        }
        $arrResult['query'] = $query;
        $arrResult['sort']['page'] = $pageno;
        $arrResult['sort']['field'] = $field;
        $arrResult['sort']['notold'] = $notOld;
        $arrResult['sort']['order'] = $orders;
        $arrResult['select']['state'] = $arrParam['state'];
        $arrResult['select']['suburb'] = $arrParam['suburb'];
        $arrResult['select']['attribute'] = $arrParam['attribute'];
        unset($arrTemp, $params, $sqlWhere);

		return $arrResult;
		
		
	}
	/**
	 * delete record of store
	 *
	 * @param int $StoreID
	 * @return string
	 */
	public function deleteStoreList($StoreID){
		$strResult	=	'';

		$strResult = $this->replaceLangVar($this->lang['pub_clew']['faild'],array('Store',$this->lang['operation']['delete']));

		if ($this->dbcon->execute_query("delete FROM ".$this->table."bu_detail where CustomerType='seller' AND StoreID='$StoreID'")) {
			mysql_query("delete FROM ".$this->table."login where StoreID='$StoreID'");
			mysql_query("delete FROM ".$this->table."template_details where StoreID='$StoreID'");
			mysql_query("delete FROM ".$this->table."product_job where StoreID='$StoreID'");
			mysql_query("delete FROM ".$this->table."product_realestate where StoreID='$StoreID'");
			mysql_query("delete FROM ".$this->table."product where StoreID='$StoreID'");
			mysql_query("delete FROM ".$this->table."product_job where StoreID='$StoreID'");
			mysql_query("delete FROM ".$this->table."wishlist where StoreID='$StoreID'");
			mysql_query("delete FROM ".$this->table."wishlist_image where StoreID='$StoreID'");
			mysql_query("delete FROM ".$this->table."order_from where StoreID='$StoreID'");

			$strResult = $this->replaceLangVar($this->lang['pub_clew']['successful'],array('Store',$this->lang['operation']['delete']));
		}

        return $strResult;
    }


    /**
     * get state list of all
     *
     * @return array
     */
    public function getStateList() {
        $arrResult = null;

        $sql = "select * from " . $this->table . "state order by description,stateName";
        $this->dbcon->execute_query($sql);
        $arrTemp = $this->dbcon->fetch_records(true);
        if (is_array($arrTemp)) {
            $arrResult = $arrTemp;
        }

        return $arrResult;
    }

    public function getsuburbsbysid($sid) {
        $arrResult = null;
        $sql = "select suburb_id,suburb from " . $this->table . "suburb where state_id='$sid' group by suburb order by suburb ";
        $this->dbcon->execute_query($sql);
        $arrTemp = $this->dbcon->fetch_records(true);
        if (is_array($arrTemp)) {
            $arrResult = $arrTemp;
        }
        return $arrResult;
    }

    public function getStoreInfo($StoreID) {
        $arrResult = null;
        $sql = "select bu.*,lg.password as bu_password,lg.suspend from " . $this->table . "bu_detail bu left join {$this->table}login lg on bu.StoreID=lg.StoreID where bu.StoreID='$StoreID' ";
        $this->dbcon->execute_query($sql);
        $arrTemp = $this->dbcon->fetch_records(true);
        if (is_array($arrTemp)) {
            $arrResult = $arrTemp[0];
        }
        $arrResult['bu_name'] = str_replace("\"", "&quot;", $arrResult['bu_name']);
        $arrResult['bu_address'] = str_replace("\"", "&quot;", $arrResult['bu_address']);
        $arrResult['bu_nickname'] = str_replace("\"", "&quot;", $arrResult['bu_nickname']);

        return $arrResult;
    }

    /**
     * get Suburb list by state id
     *
     * @param int $stateID
     * @return array
     */
    public function getSuburbList($stateID='') {
        $arrResult = null;

        $sqlQuery = '';
        if ($stateID != '') {
            $sqlQuery = " AND state_id ='$stateID' ";
            $QUERY = "SELECT DISTINCT suburb FROM " . $this->table . "suburb WHERE suburb <>'' $sqlQuery ORDER BY suburb";

            $this->dbcon->execute_query($QUERY);
            $arrResult = $this->dbcon->fetch_records(true);
        }
        //$arrResult['query']	=	$QUERY;

        return $arrResult;
    }

    function updaterenewdate($date, $StoreID) {
        global $dbcon;
        $Datet = 0;
        if (DATAFORMAT_DB == "%m/%d/%Y") {
            if ($date != "") {
                list($month, $day, $year) = split('/', $date);
                $Datet = mktime(0, 0, 0, $month, $day, $year);
            }
        } else {
            if ($date != "") {
                list($day, $month, $year) = split('/', $date);
                $Datet = mktime(0, 0, 0, $month, $day, $year);
            }
        }

        if ($Datet != 0) {
            $query = "update {$this->table}bu_detail set renewalDate='$Datet', product_renewal_date='$Datet' where StoreID='$StoreID'";
            return $dbcon->execute_query($query);
        }
        return 0;
    }

    function checkfrom($email, $nickname, $website, $urlstring, $attribute=0, $storeID="", $username='') {
        $ckwebsite = "";
        $strCondition = " WHERE bu_name='" . $website . "' and attribute = 0";
        if ($storeID != "") {
            $strCondition .= " AND StoreID<>'" . $storeID . "'";
        }
        if (!$this->dbcon->checkRecordExist($this->table . "bu_detail", $strCondition)) {
            $ckwebsite = 'ok';
        } else {
            $ckwebsite = "existed";
        }

        $ckurlstring = "";
        $urlstring = clean_url_name($urlstring);
        $strCondition = " WHERE store_name='" . $urlstring . "'";
        if ($storeID != "") {
            $strCondition .= " AND StoreID<>'" . $storeID . "'";
        }

        if (!$this->dbcon->checkRecordExist($this->table . "login", $strCondition)) {
            $ckurlstring = 'ok';
        } else {
            $ckurlstring = "existed";
        }

        $cknickname = "ok";
        $sql = "select bu_email from {$this->table}bu_detail where bu_nickname='$nickname' limit 1";
        $this->dbcon->execute_query($sql);
        $result = $this->dbcon->fetch_records();
        if (is_array($result)) {
            if ($result[0]['bu_email'] != $email) {
                $cknickname = "existed";
            }
        }
        $ckemail = "";
        if ($attribute == 5) {
            $strCondition = " WHERE user='" . $email . "' and attribute in('4')";
        } else {
            $strCondition = " WHERE user='" . $email . "' and attribute in('$attribute','4')";
        }

        if ($storeID != "") {
            $strCondition .= " AND StoreID<>'" . $storeID . "'";
        }
        if (!$this->dbcon->checkRecordExist($this->table . "login", $strCondition)) {
            if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
                $ckemail = 'invalid';
            } else {
                $ckemail = 'ok';
            }
        } else {
            $ckemail = "existed";
        }

        $ckusername = "ok";
        if ($attribute == 5) {
            // Add by Haydn.H By 20120927 ========= Begin =========
            //username reg
//            $namereg = '/^[a-zA-Z0-9._-\x7f-\xfe]{2,21}$/';
//
//            if (preg_match($namereg, $username) == 0 && !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $username)) {

            $namereg = '/["<>\\\'\\\]{1,}/i';
            if (preg_match($namereg, $username) > 0){
            // Add by Haydn.H By 20120927 ========= End =========

                $ckusername = 'invalid';
            } else {
                $sql = "select StoreID from {$this->table}bu_detail where bu_username='$username' limit 1";
                $this->dbcon->execute_query($sql);
                $result = $this->dbcon->fetch_records();
                if (is_array($result)) {
                    if ($result[0]['StoreID'] != $storeID) {
                        $ckusername = "existed";
                    }
                }
            }
        }

        return $ckemail . "|" . $cknickname . "|" . $ckwebsite . "|" . $ckurlstring . "|" . $ckusername;
    }

    function getstatslog() {
        global $dbcon;
        $aryResult = array();
        /*         * buyer* */
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='buyer' and lg.level=2 ";
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['buyer'] = $result[0]['num'];

        /*         * seller* */
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=0 and bu.renewalDate>=" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['seller'][0] = $result[0]['num'];
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=0 and bu.renewalDate<" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['seller'][1] = $result[0]['num'];

        /*         * estate seller* */
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=1 and bu.renewalDate>=" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['estate'][0] = $result[0]['num'];
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=1 and bu.renewalDate<" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['estate'][1] = $result[0]['num'];

        /*         * Car Seller* */
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=2 and bu.renewalDate>=" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['car'][0] = $result[0]['num'];
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=2 and bu.renewalDate<" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['car'][1] = $result[0]['num'];

        /*         * Job agent* */
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=3 and subAttrib=1 and bu.renewalDate>=" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['jobagent'][0] = $result[0]['num'];
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=3 and subAttrib=1 and bu.renewalDate<" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['jobagent'][1] = $result[0]['num'];

        /*         * Job seeker paid* */
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=3 and subAttrib=2 and bu.renewalDate>=" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['jobseeker'][0] = $result[0]['num'];
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=3 and subAttrib=2 and bu.renewalDate<" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['jobseeker'][1] = $result[0]['num'];

        /*         * Job seeker* */
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=3 and subAttrib=3";
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['jobs'] = $result[0]['num'];

        /*         * FoodWine Seller* */
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=5 and bu.renewalDate>=" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['foodwine'][0] = $result[0]['num'];
        $query = "SELECT count(*) as num FROM {$this->table}login lg,{$this->table}bu_detail bu WHERE lg.StoreID=bu.StoreID and bu.customerType='seller' and bu.attribute=5 and bu.renewalDate<" . time();
        $dbcon->execute_query($query);
        $result = $dbcon->fetch_records(true);
        $aryResult['foodwine'][1] = $result[0]['num'];

        return $aryResult;
    }

    /**
     * Import Free Listing CSV File
     */
    function importFreeListingByCSV() {
        //echo "Starting Import";
        // upload the Free Listing with csv
        $csvfile = $_REQUEST['swf_csv'];

        if (is_file($csvfile)) {
            $_FILES['csv']['size'] = filesize($csvfile);
        }
        if ($csvfile == '') {
            $msg = $GLOBALS['multi_msg'][28];
        } elseif ($_FILES['csv']['size'] > 83400320) {
            $msg = $GLOBALS['multi_msg'][11];
        } else {

            $_FILES['csv']['tmp_name'] = $csvfile;
            $_FILES['csv']['name'] = "upload.csv";

            if ($_FILES['csv']['size'] == 0) {
                $msg = $GLOBALS['multi_msg'][28];
            } elseif (strtolower(substr($_FILES['csv']['name'], -3, 3)) != 'csv') {
                $msg = $GLOBALS['multi_msg'][1];
            } else {
                $listings = $this->getFreeListingCSV($_FILES['csv']);
                if (!$listings) {
                    $msg = $GLOBALS['multi_msg'][27];
                } else {
                    list($success, $fail) = $this->importFreeListingInfo($listings, $_FILES['image']);

                    // END
                    $boolResult = true;
                    $msg = "$success records imported successfully, $fail records imported failed.";

                    if ($fail === 'all') {
                        $msg = "The titles in the csv don&#039;t match the standardized titles completely. Please check the csv.";
                        $_SESSION['multi_fl_import']['all'] = $msg;
                        $fail = 5;
                    }


                    $this->setFormInuptVar(array('fail' => $fail, 'all_msg' => $msg, 'error_more' => "&nbsp;<a href='#' onclick='javascript:window.open(\"/multi_freelisting_msg.php\",\"multerr\",\"width=600,height=400,scrollbars=yes,status=yes\");'>Click here show more!</a>"));
                }
            }
            @unlink($csvfile);
            @unlink($imgfile);
        }
        //exit;

        $_SESSION["pageParam"]["msg"] = "";
        $this->addOperateMessage($msg);
        return $boolResult;
    }

    /**
     * Get Free Listing information from CSV file
     * @param array $_FILES
     * @return array
     */
    function getFreeListingCSV($csv) {
        $file = fopen($csv['tmp_name'], 'r');
        $listings = array();
        while (($row = fgetcsv($file, 0, ',', '"')) !== false) {
            $listings[] = $row;
        }
        fclose($file);
        return $listings;
    }

    /**
     * Import Free Listing information
     * @param array $listings $images
     * @return status
     */
    function importFreeListingInfo($listings) {

        $field = $listings[0];
        $freelisting_csv = $GLOBALS['freelisting_csv'];
        $foodwine_seller_lang = &$GLOBALS['_LANG']['seller']['attribute'][5]['subattrib'];
        //print_r($foodwine_seller_lang);
        //exit();
        $success = 0;
        $fail = 0;
        $fail_detail = '';
        $attribute = 5;
        $state_id = $postcode = '';

        $field_list = array('#', 'website_name', 'url_string', 'nickname', 'attribute', 'sub_attrib', 'state', 'suburb', 'address', 'postcode', 'phone', 'mobile');
        unset($_SESSION['multi_fl_import']);
        if (count(array_unique($field)) != count($field_list)) {
            return array(0, 'all');
        }

        //check images and save products information to database
        $sql = "insert into " . $this->table . "bu_detail (";
        #, productCode, itemName, price, unit, OBO, onSale, category, deliveryCost, deliveryMethod, mainImage, image1, image2, imge3, description

        for ($i = 1; $i < count($field); $i++) {
            //echo "field:[".$field[$i]."]<br>";
            $field[$i] = trim($field[$i]);
            $field[$i] = strtolower($field[$i]);
            if ($field[$i] != "") {
                if (!in_array(strtolower($field[$i]), $field_list)) {
                    exit('Error 1:' . 'field:' . $field[$i] . ' i:' . $i . 'csv field:' . $freelisting_csv[$field[$i]]);
                    return array(0, 'all');
                }
            } else {
                exit('Error 2:' . 'field:' . $field[$i] . ' i:' . $i . 'csv field:' . $freelisting_csv[$field[$i]]);
                return array(0, 'all');
            }
            if (isset($freelisting_csv[$field[$i]]) && $freelisting_csv[$field[$i]] != "") {
                $sql.= $freelisting_csv[$field[$i]] . ',';
            } else {
                exit('Error 3:' . 'field:' . $field[$i] . ' i:' . $i . 'csv field:' . $freelisting_csv[$field[$i]]);
                return array(0, 'all');
            }
        }

        $sql.= "PayDate,renewalDate,CustomerType,is_popularize_store) values(";
        $counter_i = count($listings);

        for ($i = 1, $k = 0; $i <= $counter_i; $i++) {
            if (count($listings[$i]) < count($field)) {
                continue;
            }
            $values = '';
            $invalid = 0;
            $num = 1;
            $counter_j = count($listings[$i]);
            $website_name = '';
            for ($j = 0; $j < $counter_j; $j++) {
                $listings[$i][$j] = trim($listings[$i][$j]);
                $field[$j] = trim($field[$j]);
                if ($field[$j] == 'website_name' || $field[$j] == 'url_string') {

                    $invalid = (empty($listings[$i][$j]) && $field[$j] == 'website_name') ? 1 : $invalid;
                    if (empty($listings[$i][$j])) {
                        $_SESSION['multi_fl_import'][$i]['msg'][$j] = $GLOBALS['multi_msg'][26];
                    }
                    if ($field[$j] == 'website_name') {
                        $website_name = $listings[$i][$j];
                    } elseif ($field[$j] == 'url_string') {
                        $listings[$i][$j] = $this->getFieldValue($listings[$i][$j], $website_name);
                    }
                    /*                     * *if same item name auto renew the item url*** */
                    /*                     * *2011 08 18 Kevin.liu** */
                    $n = 0;
                    $item_url = $field[$j] == 'url_string' ? clean_url_name($listings[$i][$j]) : $listings[$i][$j];
                    do {
                        if ($n > 0) {
                            $item_url = clean_url_name($listings[$i][$j]) . (date("His", time() + $i));
                        }
                        $ckname_bl = $this->checkStoreInfo($item_url, $freelisting_csv[$field[$j]]);
                        $n++;
                    } while ($ckname_bl);
                    if ($ckname_bl) {
                        $invalid = 1;
                        switch ($field[$j]) {
                            case 'website_name':
                                $_SESSION['multi_fl_import'][$i]['msg'][$j] = $GLOBALS['multi_msg'][29];
                                break;
                            case 'url_string':
                                $_SESSION['multi_fl_import'][$i]['msg'][$j] = $GLOBALS['multi_msg'][30];
                                break;
                            case 'nickname':
                                $_SESSION['multi_fl_import'][$i]['msg'][$j] = $GLOBALS['multi_msg'][31];
                                break;
                            default:
                                break;
                        }
                    }
                    $values .= "'" . addslashes($item_url) . "',";
                } elseif ($field[$j] == 'attribute') {
                    $values.= "'$attribute',";
                } elseif ($field[$j] == 'sub_attrib') {
                    $has = false;
                    foreach ($foodwine_seller_lang as $key => $value) {
                        if (strtolower(trim($value)) == strtolower(trim($listings[$i][$j]))) {
                            $listings[$i][$j] = $key;
                            $has = true;
                            break;
                        }
                    }
                    $listings[$i][$j] = $has ? $listings[$i][$j] : 1;
                    $values.= $listings[$i][$j] . ",";
                } elseif ($field[$j] == 'email') {
                    $check_email = $this->checkStoreInfo($listings[$i][$j], $freelisting_csv[$field[$j]]);
                    if ($check_email) {
                        $invalid = 1;
                        $_SESSION['multi_fl_import'][$i]['msg'][$j] = $GLOBALS['multi_msg'][25];
                    }
                    $values.= "'" . $listings[$i][$j] . "',";
                } elseif ($field[$j] == 'state') {
                    $listings[$i][$j] = $this->getStateIdByName($listings[$i][$j]);
                    $values.="'" . $listings[$i][$j] . "',";
                    $state_id = $listings[$i][$j];
                } elseif ($field[$j] == 'postcode') {
                    $listings[$i][$j] = $listings[$i][$j] ? $listings[$i][$j] : $postcode;
                    $values.="'" . $listings[$i][$j] . "',";
                } elseif ($field[$j] != '' && $field[$j] != '#') {
                    $values.="'" . addslashes($listings[$i][$j]) . "',";
                    if ($field[$j] == 'suburb') {
                        $postcode = $this->getPostcodeBySuburb($state_id, '', $listings[$i][$j]);
                    }
                } elseif ($field[$j] == '#') {
                    $num = $listings[$i][$j];
                }
            }
            //echo "invalid:$invalid<br>";
            //exit;
            if ($invalid == 1) {
                $fail+= 1;
                $_SESSION['multi_fl_import'][$i]['ID'] = $num;
                continue;
            }
            $values.= time() . "," . (time() + 365 * 24 * 3600) . ",'seller',1)";
            if ($this->dbcon->execute_query($sql . $values)) {
                $success+= 1;
                if (!empty($imageinfo[$i])) {
                    $imageinfo[$i]['pid'] = $this->dbcon->insert_id();
                    $imageinfo[$i]['StoreID'] = $_SESSION['ShopID'];
                }
            } else {
                $_SESSION['multi_fl_import'][$i]['msg'][$j] = $GLOBALS['multi_msg'][17];
                $fail+= 1;
            }
            $invalid = 0;
        }

        //echo "succ:$success; fail:$fail;";
        //print_r($_SESSION['multi_fl_import']);
        //exit;
        return array($success, $fail);
    }

    /**
     *  Check Stroe Info
     *
     */
    function checkStoreInfo($name, $field, $StoreID=0) {
        if (empty($name) || empty($field)) {
            return false;
        }

        $name = strtolower($name);
        $name = $field == 'bu_urlstring' ? clean_url_name($name) : $name;
        $sql = "select count(*) as num from " . $this->table . "bu_detail  where lower(" . $field . ")='$name'";
        if ($StoreID != 0) {
            $sql.= " and StoreID!=$StoreID";
        }

        $this->dbcon->execute_query($sql);
        $num = $this->dbcon->fetch_records();
        //echo "num:".$num[0]['num'].";$sql\n<br>";
        return ($num[0]['num'] > 0) ? true : false;
    }

    function getFieldValue($value, $website_name) {
        if (empty($value) || strtolower($value) == 'null') {
            $website_name = strtolower(clean_url_name($website_name));
            $value = substr($website_name, 0, 100);
        }

        return $value;
    }

    /**
     *  Get State ID By Name
     *
     */
    function getStateIdByName($name) {
        if (empty($name)) {
            return 1;
        }

        if (is_int($name)) {
            return $name;
        }

        $name = strtolower($name);
        $sql = "select id from " . $this->table . "state where lower(stateName)='$name' OR lower(description)='$name'";
        $res = $this->dbcon->getOne($sql);
        return $res ? $res['id'] : 1;
    }

    /**
     *  Get Postcode By Suburb Name
     *
     */
    function getPostcodeBySuburb($state_id='', $suburb_id='', $suburb_name='') {
        if (empty($state_id) || (empty($suburb_id) && empty($suburb_name))) {
            return '';
        }

        $suburb_name = clean_url_name(strtolower($suburb_name));
        $sql = "select zip from " . $this->table . "suburb where state_id='$state_id' AND (lower(suburb)='$suburb_name' OR suburb_id='$suburb_id')";
        $res = $this->dbcon->getOne($sql);
        return $res ? $res['zip'] : '';
    }

}

/* * *******************
 * xajax function
 * ******************** */

/**
 * xajax get store list
 *
 * @param int $pageno
 * @param objForm $objForm
 * @param boolean $notOld
 * @return objResponse
 */
function getStoreList($pageno, $objForm, $notOld = true, $field="", $orders='ASC') {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];
    $req['list'] = $objAdminStore->getStoreList($pageno, $objForm['searchparam'], $notOld, $field, $orders);
    $req['nofull'] = true;
    $objAdminStore->smarty->assign('req', $req);
    $content = $objAdminStore->smarty->fetch('admin_store.tpl');
    $objResponse->assign("tabledatalist", 'innerHTML', $content);
    $objResponse->assign("pageno", 'value', $pageno);
    return $objResponse;
}

function getDuplicateList($pageno, $objForm, $notOld = true, $field="", $orders='ASC') {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];
    $req['list'] = $objAdminStore->getDuplicateList($pageno, $objForm['searchparam'], $notOld, $field, $orders);
    $req['nofull'] = true;
    $objAdminStore->smarty->assign('req', $req);
    $content = $objAdminStore->smarty->fetch('admin_duplicate_listing.tpl');
    $objResponse->assign("tabledatalist", 'innerHTML', $content);
    $objResponse->assign("pageno", 'value', $pageno);
    return $objResponse;
}

function getStoreList2($pageno, $objForm, $notOld = true, $field="", $orders='ASC') {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];
    $req['list'] = $objAdminStore->getStoreList2($pageno, $objForm['searchparam'], $notOld, $field, $orders);
    $req['nofull'] = true;
    $objAdminStore->smarty->assign('req', $req);
    $objAdminStore->smarty->assign('PBDateFormat', DATAFORMAT_DB);
    $content = $objAdminStore->smarty->fetch('admin_store_exp.tpl');
    $objResponse->assign("tabledatalist", 'innerHTML', $content);
    $objResponse->assign("pageno", 'value', $pageno);
    return $objResponse;
}

function getUserList($pageno, $objForm, $notOld = true, $field="", $orders='ASC') {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];
    if (!$objForm['searchparam']) {
        $req['list'] = $objAdminStore->getUserList($pageno, $objForm, $notOld, $field, $orders);
    } else {
        $req['list'] = $objAdminStore->getUserList($pageno, $objForm['searchparam'], $notOld, $field, $orders);
    }
    $req['nofull'] = true;
    $objAdminStore->smarty->assign('req', $req);
    $objAdminStore->smarty->assign('date_format', DATAFORMAT_DB);
    $objAdminStore->smarty->assign('siteconfig', $GLOBALS['siteconfig_sub']);
    $content = $objAdminStore->smarty->fetch('admin_user_from_report.tpl');
    $objResponse->assign("tabledatalist", 'innerHTML', $content);
    $objResponse->assign("pageno", 'value', $pageno);
    return $objResponse;
}

function renewuser($StoreID, $date, $olddate) {
    $objResponse = new xajaxResponse();
    $objadminstore = new adminStore();
    $i = 0;
    if ($date == "") {
        $date = $olddate;
    } else {
        if ($objadminstore->updaterenewdate($date, $StoreID)) {
            $i++;
        }
    }
    $objResponse->script("cancel_form($StoreID,'$date');");
    if ($i > 0) {
        $objResponse->alert("Renew user successfully.");
    }

    return $objResponse;
}

/**
 * xajax get store list of search
 *
 * @param objForm $objForm
 * @return objResponse
 */
function getStoreListSearch($objForm) {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];
    $strParam = serialize($objForm);
    $req['list'] = $objAdminStore->getStoreList(1, $strParam);
    $req['nofull'] = true;
    $objAdminStore->smarty->assign('req', $req);
    $content = $objAdminStore->smarty->fetch('admin_store.tpl');
    $objResponse->assign("tabledatalist", 'innerHTML', $content);
    $objResponse->assign("searchparam", 'value', $strParam);
    $objResponse->assign("pageno", 'value', 1);

    return $objResponse;
}

function getStoreListSearch2($objForm) {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];
    $strParam = serialize($objForm);
    $req['list'] = $objAdminStore->getStoreList2(1, $strParam);
    $req['nofull'] = true;
    $objAdminStore->smarty->assign('req', $req);
    $objAdminStore->smarty->assign('PBDateFormat', DATAFORMAT_DB);
    $content = $objAdminStore->smarty->fetch('admin_store_exp.tpl');
    $objResponse->assign("tabledatalist", 'innerHTML', $content);
    $objResponse->assign("searchparam", 'value', $strParam);
    $objResponse->assign("pageno", 'value', 1);

    return $objResponse;
}

function getDuplicateListSearch($objForm) {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];
    $strParam = serialize($objForm);
    $req['list'] = $objAdminStore->getDuplicateList(1, $strParam);
    $req['nofull'] = true;
    $objAdminStore->smarty->assign('req', $req);
    $content = $objAdminStore->smarty->fetch('admin_duplicate_listing.tpl');
    $objResponse->assign("tabledatalist", 'innerHTML', $content);
    $objResponse->assign("searchparam", 'value', $strParam);
    $objResponse->assign("pageno", 'value', 1);

    return $objResponse;
}

/**
 * xajax get suburb list
 *
 * @param int $stateID
 * @param objHTML $objHTML
 * @return objresponse
 */
function getSuburbList($stateID, $objHTML) {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];

    $req['suburb'] = $objAdminStore->getSuburbList($stateID);
    $req['display'] = 'suburb';
    $objAdminStore->smarty->assign('req', $req);
    $content = $objAdminStore->smarty->fetch('admin_store.tpl');
    $objResponse->assign("$objHTML", 'innerHTML', $content);

    return $objResponse;
}

/**
 * delete record of store
 *
 * @param int $StoreID
 * @return objRespo nse
 */
function deleteStoreList($StoreID) {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];
    $messages = $objAdminStore->deleteStoreList($StoreID);

    $objResponse->script("javascript:xajax_getStoreList(xajax.$('pageno').value, xajax.getFormValues('mainForm'));");
    $objResponse->assign('ajaxmessage', 'innerHTML', $messages);
    $objResponse->alert($messages);

    return $objResponse;
}

function deleteDuplicateList($StoreID) {
    $objResponse = new xajaxResponse();
    $objAdminStore = &$GLOBALS['objAdminStore'];
    $messages = $objAdminStore->deleteStoreList($StoreID, "List");

    $objResponse->script("javascript:xajax_getDuplicateList(xajax.$('pageno').value, xajax.getFormValues('mainForm'));");
    $objResponse->assign('ajaxmessage', 'innerHTML', $messages);
    $objResponse->alert($messages);

    return $objResponse;
}

?>