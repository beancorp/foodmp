<?php

/**
 * Order
 *
 */
class Order extends common {

    var $dbcon;
    var $socObj;
    var $table = '';
    var $smarty = null;
    var $lang = null;

    function Order() {
        $this->dbcon = &$GLOBALS['dbcon'];
        
        if(isset($GLOBALS['socObj'])){
            $this->socObj = &$GLOBALS['socObj'];
        }
        else{
            $this->socObj = new socClass();
        }
        $this->table = &$GLOBALS['table'];
        $this->smarty = &$GLOBALS['smarty'];
        $this->lang = &$GLOBALS['_LANG'];
    }

    function getOrderList($StoreID, $isnew=true, $cur_orderid, $page, $all=false, $status=1) {
        include_once(SOC_INCLUDE_PATH . '/class/pagerclass.php');

        $where = "1";
        if ($StoreID) {
            $where .= " AND orderinfo.StoreID='$StoreID'";
        }
        if ($status) {
            $where .= " AND orderinfo.status='$status'";
        }
        if ($isnew || !$all) {
            $reviewed = intval(!$isnew);
            $where .= " AND orderinfo.seller_reviewed='$reviewed'";
        }

        # get total number
        $sql = "SELECT COUNT(orderinfo.OrderID) AS num FROM {$this->table}order_foodwine orderinfo WHERE " . $where;
        $total = $this->dbcon->getOne($sql);
        $total = $total['num'];
		
		//echo $sql;
		//echo $total;

        # divide pages
        $clsPage = new pagerClass();
        $pageLink = $clsPage->getLink($page, $total, PAGESIZE, 'page');
        unset($clsPage);

        # search orders
        $start = ($page - 1) * PAGESIZE;
        $sql = "SELECT orderinfo.*, detail.bu_name, detail.bu_suburb, detail.bu_state, detail.bu_address, detail.mobile, detail.bu_phone FROM {$this->table}order_foodwine orderinfo, {$this->table}bu_detail detail WHERE $where AND orderinfo.buyer_id=detail.StoreID ORDER BY order_date DESC,OrderID DESC LIMIT $start," . PAGESIZE;
        $this->dbcon->execute_query($sql);
        $res = $this->dbcon->fetch_records(true);
		//echo $sql;
        if ($res) {
            foreach ($res as $key => $order) {
                if ($cur_orderid) {
                    if ($order['OrderID'] == $cur_orderid) {
                        $sql = "SELECT o.*, p.item_name, p.priceorder FROM {$this->table}order_detail_foodwine o, {$this->table}product_foodwine p WHERE o.pid=p.pid AND OrderID='$cur_orderid'";
                        $this->dbcon->execute_query($sql);
                        $product_lists = $this->dbcon->fetch_records(true);
                        foreach ($product_lists as $k => $dt) {
                            $sql = 'SELECT * FROM ' . $this->table . 'image WHERE pid = ' . $dt['pid'] . ' AND attrib=0 AND StoreID=' . $StoreID;
                            $this->dbcon->execute_query($sql);
                            $tmp = $this->dbcon->fetch_records(true);
                            $product_lists[$k]['big_image'] = $tmp[0]['picture'];
                            $product_lists[$k]['small_image'] = $tmp[0]['smallPicture'];
                        }
                        $order['product_lists'] = $product_lists;
                    }
                }
                $order['ordersn'] = str_pad($order['OrderID'], 7, '0', STR_PAD_LEFT);
                $order['bu_state'] = $this->socObj->_getStateName($order['bu_state'], 1);
                $res[$key] = $order;
            }
        } else {
            $res = array();
        }
		
		//echo var_export($res);

        return array('items' => $res, 'linkStr' => $pageLink['linksAllFront']);
    }

    function getOrderInfo($orderid) {
        $sql = "SELECT orderinfo.*, detail.bu_name, detail.bu_suburb, detail.bu_state, detail.bu_address, detail.mobile, detail.bu_phone, 
                detail.bu_postcode,detail.bu_email FROM {$this->table}order_foodwine orderinfo, {$this->table}bu_detail detail 
                WHERE OrderID='$orderid' AND orderinfo.buyer_id=detail.StoreID";
        $res = $this->dbcon->getOne($sql);

        $sql = "SELECT o.*,p.item_name,p.priceorder FROM {$this->table}order_detail_foodwine o, {$this->table}product_foodwine p 
                WHERE o.pid=p.pid AND o.OrderID='$orderid'";
        $this->dbcon->execute_query($sql);
        $product_lists = $this->dbcon->fetch_records(true);
        foreach ($product_lists as $k => $dt) {
            $sql = 'SELECT * FROM ' . $this->table . 'image WHERE pid = ' . $dt['pid'] . ' AND attrib=0 AND StoreID=' . $StoreID;
            $this->dbcon->execute_query($sql);
            $tmp = $this->dbcon->fetch_records(true);
            $product_lists[$k]['big_image'] = $tmp[0]['picture'];
            $product_lists[$k]['small_image'] = $tmp[0]['smallPicture'];
        }
        
        $res['product_lists'] = $product_lists;
        $res['ordersn'] = str_pad($orderid, 7, '0', STR_PAD_LEFT);
       
        $res['bu_state'] = $this->socObj->_getStateName($res['bu_state'], 1);

        if ($res['paid_date']) {
            $res['paid_date_format'] = date('d/m/Y h:i a', $res['paid_date']);
        }

        return $res;
    }
	
    function orderOfflineSendMail($OrderID = 0) {

        if (!$OrderID) {
            return;
        }

        include_once('class.soc.php');
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= 'From: noreply@'.EMAIL_DOMAIN . "\r\n";

        $socObj = new socClass();
        //get seller's, buyer's info
        $req = $this->getOrderInfo($OrderID);
        

        $store_info = $socObj->displayStoreWebside(true, '', $req['StoreID']);
        //get buyer's info
        $sql = "SELECT t1.bu_nickname, t2.user FROM " . $this->table . "bu_detail t1, " . $this->table . "login t2 WHERE t1.StoreID=" . $req['buyer_id'] . " AND t1.StoreID=t2.StoreID";
        $buyer = $this->dbcon->getOne($sql);
        $store_info['buyer_nickname'] = $buyer['bu_nickname'];
        $store_info['buyer_email'] = $buyer['user'];
        
        $req = array_merge($req, $store_info);
        $this->smarty->assign('req', $req);

        $subject = 'FoodMarketplace Purchase Order - Offline Payment';
        $seller_email = $req['info']['bu_email'];
        $buyer_email = $req['bu_email'];
        $buyerid = $req['buyer_id'];

        $message = $this->smarty->fetch('foodwine/email_order.tpl');

        //send mail to seller
        @mail($seller_email, $subject, $message, fixEOL($headers));
        $query = "INSERT INTO `" . $this->table . "message` (`subject`, `message`, `StoreID`, `date`,`emailaddress`,`fromtoname`)VALUES('$subject','" . addslashes($message) . "','{$req['info']['StoreID']}','" . time() . "','SYSTEM','SYSTEM') ";
        $this->dbcon->execute_query($query);

        //send mail to buyer
        $subject = 'The Item(s) You Purchased';
        @mail($buyer_email, $subject, $message, fixEOL($headers));
        $query = "SELECT * FROM {$this->table}login where StoreID='{$buyerid}'";
        $this->dbcon->execute_query($query);
        $buyerinfo = $this->dbcon->fetch_records(true);
        if ($buyerinfo[0]['attribute'] != '4') {
            $query = "INSERT INTO `" . $this->table . "message` (`subject`, `message`, `StoreID`, `date`,`emailaddress`,`fromtoname`)VALUES('$subject','" . addslashes($message) . "','$buyerid','" . time() . "','SYSTEM','SYSTEM') ";
            $this->dbcon->execute_query($query);
        }
    }

    function orderSendMail($OrderID = 0, $is_creditcard = false) {

        if (!$OrderID) {
            return;
        }

        include_once('class.soc.php');
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= 'From: noreply@'.EMAIL_DOMAIN . "\r\n";

        $socObj = new socClass();
        //get seller's, buyer's info
        $req = $this->getOrderInfo($OrderID);
        

        $store_info = $socObj->displayStoreWebside(true, '', $req['StoreID']);
        //get buyer's info
        $sql = "SELECT t1.bu_nickname, t2.user FROM " . $this->table . "bu_detail t1, " . $this->table . "login t2 WHERE t1.StoreID=" . $req['buyer_id'] . " AND t1.StoreID=t2.StoreID";
        $buyer = $this->dbcon->getOne($sql);
        $store_info['buyer_nickname'] = $buyer['bu_nickname'];
        $store_info['buyer_email'] = $buyer['user'];
        
        $req = array_merge($req, $store_info);
        $this->smarty->assign('req', $req);

        $subject = 'FoodMarketplace Purchase Order';
        $seller_email = $req['info']['bu_email'];
        $buyer_email = $req['bu_email'];
        $buyerid = $req['buyer_id'];

        if ($is_creditcard) {
            $message = $this->smarty->fetch('foodwine/email_credit.tpl');
        } else {
            $message = $this->smarty->fetch('foodwine/email_order.tpl');
        }

        //send mail to seller
        @mail($seller_email, $subject, $message, fixEOL($headers));
        $query = "INSERT INTO `" . $this->table . "message` (`subject`, `message`, `StoreID`, `date`,`emailaddress`,`fromtoname`)VALUES('$subject','" . addslashes($message) . "','{$req['info']['StoreID']}','" . time() . "','SYSTEM','SYSTEM') ";
        $this->dbcon->execute_query($query);

        //send mail to buyer
        $subject = 'The Item(s) You Purchased';
        @mail($buyer_email, $subject, $message, fixEOL($headers));
        $query = "SELECT * FROM {$this->table}login where StoreID='{$buyerid}'";
        $this->dbcon->execute_query($query);
        $buyerinfo = $this->dbcon->fetch_records(true);
        if ($buyerinfo[0]['attribute'] != '4') {
            $query = "INSERT INTO `" . $this->table . "message` (`subject`, `message`, `StoreID`, `date`,`emailaddress`,`fromtoname`)VALUES('$subject','" . addslashes($message) . "','$buyerid','" . time() . "','SYSTEM','SYSTEM') ";
            $this->dbcon->execute_query($query);
        }
    }

    function updateReviewed($orderid, $type='seller') {
        if (empty($type)) {
            return false;
        }
        $data[$type . '_reviewed'] = 1;

        $res = $this->dbcon->update_record($this->table . 'order_foodwine', $data, "WHERE OrderID='$orderid'");

        return $res;
    }

    /**
     * save order and payment info then send confirm email
     * @param 
     * @return message of operation
     */
    function creditPayment() {
        $boolResult = false;

        $_var = $this->setFormInuptVar();
        extract($_var);

        if (empty($firstName)) {
            $msg = "First Name is required. ";
        } else { //regeidt
            $arrSetting = array(
                'firstName' => "$firstName",
                'lastName' => "$lastName",
                'cardType' => "$cardType",
                'cardNumber' => "$cardNumber",
                'expMonth' => "$month",
                'expYear' => "$year",
                'address1' => "$address1",
                'address2' => "$address2",
                'city' => "$city",
                'state' => "$state",
                'emailAddr' => "$email",
                'phone' => "$phone",
                'postcode' => "$postcode",
                'description' => "Credit Card: " . $this->lang['CreditCardType'][$cardType][text],
                'status' => 1
            );

            if ($this->dbcon->update_record($this->table . "order_foodwine", $arrSetting, " WHERE OrderID='$OrderID'")) {
                $this->orderSendMail($OrderID, true);
                $msg = "THANK YOU. Your order has been sent. Please check your email for order details. Thank you for shopping at FoodMarketplace.";
            } else {
                $msg = "Operation failed.";
            }
        }
        return $msg;
    }

    function creditSendMail($orderId) {
        global $email_regards;
        //get order info
        $_query = "SELECT t1.*, t2.reviewkey, t2.StoreID, t3.item_name, t3.postage, t3.price FROM " . $this->table . "order_detail t1, ";
        $_query .= $this->table . "order_reviewref t2, ";
        $_query .= $this->table . "product t3 WHERE t1.OrderID=" . $orderId . " AND t1.OrderID=t2.OrderID AND t1.pid=t3.pid";
        $this->dbcon->execute_query($_query);
        $result = $this->dbcon->fetch_records(true);
        $orderInfo = $result[0];

        $StoreID = $orderInfo['StoreID'];
        $buyerId = $orderInfo['buyer_id'];
        $pid = $orderInfo['pid'];
        $reviewKey = $orderInfo['reviewkey'];
        $itemName = $orderInfo['item_name'];
        $amount = $orderInfo['amount'];
        $quantity = $orderInfo['quantity'];
        $shippingCost = $orderInfo['postage'];
        $price = $orderInfo['price'];

        $productLink = 'http://' . $_SERVER['HTTP_HOST'] . '/soc.php?cp=dispro&StoreID=' . $StoreID . '&proid=' . $pid;

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= 'From: info@'.EMAIL_DOMAIN . "\r\n";

        //send mail to seller
        //get seller's info
        $_query = "SELECT t1.bu_nickname, t2.user FROM " . $this->table . "bu_detail t1, " . $this->table . "login t2 WHERE t1.StoreID=" . $StoreID . " AND t1.StoreID=t2.StoreID";
        $this->dbcon->execute_query($_query);
        $seller = $this->dbcon->fetch_records(true);
        $seller_nickname = $seller[0]['bu_nickname'];
        $seller_email = $seller[0]['user'];
        //get buyer's info
        $_query = "SELECT t1.bu_nickname, t2.user FROM " . $this->table . "bu_detail t1, " . $this->table . "login t2 WHERE t1.StoreID=" . $buyerId . " AND t1.StoreID=t2.StoreID";
        $this->dbcon->execute_query($_query);
        $buyer = $this->dbcon->fetch_records(true);
        $buyer_nickname = $buyer[0]['bu_nickname'];
        $buyer_email = $buyer[0]['user'];

        $subject = 'FoodMarketplace Purchase Order';
        $cardType = array(
            'c1' => 'VISA',
            'c2' => 'MasterCard',
            'c3' => 'AMEX',
            'c4' => 'Discover'
        );
        $arrParams = array(
            'subject' => $subject,
            'seller_nickname' => $seller_nickname,
            'buyer_nickname' => $buyer_nickname,
            'productLink' => $productLink,
            'itemName' => $itemName,
            'price' => $price,
            'quantity' => $quantity,
            'shippingCost' => $shippingCost,
            'totalCost' => $quantity * $price + $shippingCost,
            'buyer_email' => $buyer_email,
            'firstName' => "$orderInfo[firstName]",
            'lastName' => "$orderInfo[lastName]",
            'cardType' => $cardType['c' . $orderInfo['cardType']],
            'cardNumber' => "$orderInfo[cardNumber]",
            'expMonth' => "$orderInfo[expMonth]",
            'expYear' => "$orderInfo[expYear]",
            'address1' => "$orderInfo[address1]",
            'address2' => "$orderInfo[address2]",
            'city' => "$orderInfo[city]",
            'state' => "$orderInfo[state]",
            'postcode' => "$orderInfo[postcode]",
            'emailAddr' => "$orderInfo[emailAddr]",
            'phone' => "$orderInfo[phone]",
            'productLink' => "$productLink",
            'email_regards' => "$email_regards"
        );

        $this->smarty->assign('req', $arrParams);
        $seller_message = $this->smarty->fetch('email_credit.tpl');

        @mail($seller_email, $subject, $seller_message, $headers);
    }

}

?>