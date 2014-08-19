<?php

class ProductCertified extends common {

    private $_storeId = '';
    private $_userId = '';
    private $_table = '';
    private $_db = NULL;
    private $_lang = NULL;

    public function __construct() {
        $this->_db  = &$GLOBALS['dbcon'];
        $this->_table  = &$GLOBALS['table'];
        $this->_smarty = &$GLOBALS['smarty'];
        $this->_lang   = &$GLOBALS['_LANG'];
        $this->_storeId = isset($_SESSION['StoreID'])? $_SESSION['StoreID']: 0;
        $this->_userId = isset($_SESSION['UserID'])? $_SESSION['UserID']: 0;
    }

    public function __destruct() {
        unset($this->_db, $this->_table, $this->_smarty, $this->_lang);
    }

    /**
     * Get All Certified Products
     * @return <array>
     * @author roanld
     */
    public function getProducts() {
        $sql = "SELECT DISTINCT p.pid, p.item_name FROM {$this->_table}product p
        INNER JOIN {$this->_table}product_certified c ON(p.pid=c.product_id)
        WHERE p.is_auction='yes' AND is_certified=1 AND c.product_store_id=".$_SESSION['StoreID'] ;
        $this->_db->execute_query($sql);
        return $this->_db->fetch_records(true);
    }

    /**
     * Get Certified Count by State
     * @param <int> $storeId
     * @param <int> $state 0:pending, 1:Authorise, 2:Decline
     * @return <int>
     * @author ronald
     */
    public function getCountCertifiedByState($storeId, $state = -1){
        $_query = "SELECT COUNT(*)"
                . "\nFROM {$this->_table}product_certified `t1`"
                . "\nINNER JOIN {$this->_table}product as `t2` ON `t1`.product_id=`t2`.pid"
                . "\nINNER JOIN {$this->_table}product_auction as `t3` ON `t1`.product_id=`t3`.pid"
                . "\nWHERE `t1`.product_store_id='$storeId'"
                . "\nAND `t3`.finish !=1";

        if($state === 0){
            $_query .= "\nAND `t1`.is_authorised = 0";
        }elseif($state === 1){
            $_query .= "\nAND `t1`.is_authorised = 1";
        }elseif($state === 2){
            $_query .= "\nAND `t1`.is_authorised = 2";
        }

		$this->_db->execute_query($_query);
		$arrTemp = $this->_db->fetch_records();
        return $arrTemp[0][0];
    }

    /**
     * Get Certifieds by ProductId
     * @param <int> $pid
     * @param <int> $pageIndex
     * @param <int> $paegSize
     * @return <array>
     * @author ronald
     */
    public function getCertifieds($pid = NULL, $pageno = 1, $perPage = 15) {
        $arrResult = array();

        $pageno		= $pageno ? $pageno : 1;

        $dateformat = str_replace('-','/',DATAFORMAT_DB);
        $where = "\nFROM {$this->_table}product_certified as `t1`"
                . "\nINNER JOIN {$this->_table}product as `t2` ON `t1`.product_id=`t2`.pid"
                . "\nINNER JOIN {$this->_table}product_auction as `t3` ON `t1`.product_id=`t3`.pid"
                . "\nWHERE product_store_id={$this->_storeId}";
        if(!empty($pid)) {
            $where .= "\nAND product_id=$pid";
        }
        $countSql = "SELECT COUNT(*)".$where;

        $this->_db->execute_query($countSql);
        $count = $this->_db->fetch_records();
        $count = $count[0][0];

        ($pageno * $perPage > $count) ? $pageno = ceil($count/$perPage) : '';
        if(empty($pageno)){
            $pageno = 1;
        }
        $start	= ($pageno-1) * $perPage;

        $sql = "SELECT url_item_name, product_certified_id as `id`, product_id, store_id, full_name, is_authorised, DATE_FORMAT(FROM_UNIXTIME(created_time),'$dateformat') as created_time, DATE_FORMAT(FROM_UNIXTIME(authorise_time),'$dateformat') as authorise_time, item_name, finish"
                . $where;

        $sql .= "\n ORDER BY `t1`.`product_certified_id` DESC LIMIT $start, $perPage";
        $this->_db->execute_query($sql);
        $arrResult['list'] = $this->_db->fetch_records(FALSE);

        $params = array(
                'perPage'    => "$perPage",
                'totalItems' => "$count",
                'currentPage'=> "$pageno",
                'delta'      => 15,
                'append'     => true
        );
        $pager = & Pager::factory($params);


        $arrResult['links'] 		= $pager->getLinks();
        $arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
        $arrResult['pageno']	=  $pageno;
        $arrResult['perpage']	=  $perPage;
        unset($pager,$params);

        return $arrResult;
    }


    public function addCertified($arr_filed=array(),$arr_val=array()) {
        $field=implode(',',$arr_filed);
        foreach($arr_val as $key=>$v) {
            $val.='"'.trim($v).'",';
        }
        $val=rtrim($val,',');
        $strsql='INSERT INTO `'.$this->_table.'product_certified` ('.$field.') VALUES('.$val.')';
        $this->_db->execute_query($strsql);
        return;
    }

    /**
     * Audit Certified
     * @param <int> $certifiedId
     * @param <boolean> $isPass
     * @return <boolean> TRUE for Success, other fail.
     * @author ronald
     */
    public function audit($certifiedId, $isPass) {
        $arrResult = array();
        $where = "WHERE product_certified_id='$certifiedId' AND is_authorised='0'";
        $arrSetting = array(
            'is_authorised' => $isPass ? '1': '2',
            'authorise_time'=> time()
        );
        if ($this ->_db -> update_record($this->_table."product_certified", $arrSetting, $where)) {
            $arrResult['success'] = TRUE;
            $dateformat = str_replace('-','/',DATAFORMAT_DB);
            $sql = "SELECT product_id, contact_email, full_name, DATE_FORMAT(FROM_UNIXTIME(authorise_time),'$dateformat') as authorise_time, store_id FROM {$this->_table}product_certified WHERE product_certified_id='$certifiedId'";
            $this->_db->execute_query($sql);
            $r = $this->_db->fetch_records();
            $arrResult['time'] = $r[0]['authorise_time'];

            $this->sendAuthoriseOrDeclineEmail($r[0], $isPass);
        }else{
            $arrResult['time'] = '';
            $arrResult['success'] = FALSE;
        }

        return $arrResult;
    }

    /**
     * Send Certified Audit Email
     * @param <array> $buyer
     * @param <boolean> $isPass
     * @author ronald
     */
    private function sendAuthoriseOrDeclineEmail($buyer, $isPass){
        $sql = "SELECT * FROM {$this->_table}product WHERE pid='{$buyer['product_id']}'";
        $this->_db->execute_query($sql);
        $product = $this->_db->fetch_records(true);
        $product = $product[0];

        $soc = new socClass();
        $storeInfo = $soc->getStoreInfo($product['StoreID']);
        
        $sellerName = getStoreByName($product['StoreID']);
        $sellerUrl = 'http://'.$_SERVER['HTTP_HOST'].'/'.getStoreByURL($product['StoreID']);
        $productUrl = $sellerUrl.'/'.$product['url_item_name'];
        $to = $buyer['contact_email'];
        //$from = $storeInfo[0]['bu_email'];

        /*
         *  By YangBall 2010-06-23
         *  Change Email Subejct
         */
        //$subject = 'SOC Certified Bidder';
        $content = '<html><head><title>'.$subject.'</title></head><body>';
        $content .= "Dear {$buyer['full_name']},<br/><br/>";
        if($isPass){
            $subject = 'SOC: Bidder certification request approved';
            $content .= $_SESSION['NickName']." has approved your request to be a bidder on the following auction - <a href=\"$productUrl\">{$product['item_name']}</a>.<br/>";
            $content .= "Please click here to participate in the auction now!<br/><a href=\"$productUrl\">($productUrl)</a><br/>";
        }else{
            $subject = 'SOC: Bidder certification request declined';
            $content .= "Your Bidder Certification request for auction - <a href=\"$productUrl\">{$product['item_name']}</a> - has not been approved.<br/>";
            $content .= "Please check the information that you have submitted and try again or contact the <a href=\"http://".$_SERVER['HTTP_HOST']."/emailstore.php?url=productDispay.php&place=&pid=&StoreID={$product['StoreID']}\">seller<a/> for more details.<br/>";
        }
        $content .= "<br/>Sincerely,<br/>";
        $content .= $sellerName;
        $content .= "</body></html>";

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "From: info@thesocexchange.com\r\n";

        if (!@mail('', $subject, getEmailTemplate(stripslashes($content)), fixEOL($headers))){
            $msg = "Email sent failed.";
        }

        $query = "SELECT level FROM {$this->_table}login WHERE StoreID='{$buyer['store_id']}'";
        $this->_db->execute_query($query);
        $r = $this->_db->fetch_records();
        if($r[0][0] == 1){
            sendInboxMessage($buyer['store_id'], $subject, addslashes($content));
        }
    }

    /**
     * Check the User is Certified Bidder.
     * @param <int> $pid
     * @return <boolean> TRUE for Success, other fail.
     * @author ronald
     */
    public function checkCertified($pid){
        if(empty($this->_storeId)){
            return FALSE;
        }

        $sql = "SELECT COUNT(*)"
             . "\nFROM {$this->_table}product_certified as t1"
             . "\nWHERE product_id=$pid AND store_id='{$this->_storeId}' AND is_authorised=1";
        $this->_db->execute_query($sql);
        $count = $this->_db->fetch_records();
        return $count[0][0];
    }

    public function getProduceById($pid){
        $strsql='SELECT * FROM `'.$this->_table.'product` WHERE pid='.$pid;
        $this->_db->execute_query($strsql);
        $rs=$this->_db->fetch_records();
        return $rs[0];
    }


    public function is_pass($pid,$email){
        $strsql='SELECT * FROM `'.$this->_table.'product_certified` WHERE product_id='.$pid.' AND contact_email="'.$email.'" AND is_authorised=1';
        $this->_db->execute_query($strsql);
        $rs=$this->_db->fetch_records();
        return $rs[0];
    }


    function getCretifiedById($id){
        $strsql='SELECT * FROM `'.$this->_table.'product_certified`'.'   WHERE product_certified_id='.$id;
        $this->_db->execute_query($strsql);
        $rs=$this->_db->fetch_records();
        return $rs[0];
    }


    /*
     *  @author YangBall 2010-06-24
     *  check product having new apply
     *  @Return : new true
     *           none false
     *
     */
    function checkNewCertifiedByPid($pid){
        $strsql='SELECT DISTINCT product_id FROM `'.$this->_table.'product_certified` WHERE product_id in ('.$pid.')';
        $this->_db->execute_query($strsql);
        $rs=$this->_db->fetch_records();
        return $rs;
    }


    /*
     *  @author YangBall 2010-06-24
     *  get Certified state
     */
    function getApplyState($pid){
        if(!isset($_SESSION['StoreID'])){
            return NULL;
        }
        $strsql='SELECT * FROM `'.$this->_table.'product_certified` WHERE store_id='.$_SESSION['StoreID'].' AND product_id='.$pid.' ORDER BY product_certified_id DESC';
        $this->_db->execute_query($strsql);
        $rs=$this->_db->fetch_records();
        return $rs[0]['is_authorised'];
    }
}