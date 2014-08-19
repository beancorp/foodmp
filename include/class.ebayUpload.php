<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



/**
 * eBay Upload
 */


class EbayUpload
{

    private $_fieldList = array();
    private $_row = array();
    private $_images = array();

    private $_category = array();

    public function  __construct()
    {
        $this->_category = require_once(SOC_INCLUDE_PATH . '/ebay/category.php');
    }



    public function turbolister($products,$images)
    {

        $socStore = new socstoreClass();
        
        $imgup = new uploadImages();
        $images = $imgup->getzipProductImages($images,false);

        //set image
        $this->_images = $images;

        $this->_fieldList = $products[0];       //set field value

        $succ = 0;
        $fail = 0;
        for($i = 1; $i < count($products); $i++) {
            $this->_row = $products[$i];

            $insertProduct = array();       //insert into product table
            $insertAuction = array();       //if product is auction , insert auction table.
            $insertImages = array();        //insert into product image table

            $isInsert = true;       //insert is right
            $isAuction = ('Auction' == $this->_getValueByField('Format')) ? true : false ;     // product default type is buy & sell


            $_SESSION['multi_upload'][$i]['ID'] = $i;

            //item name
            $itemName = $this->_getValueByField('Title');
            if(empty($itemName)) {
                $_SESSION['multi_upload'][$i]['msg'][$this->_getFieldKey('Title')] = $GLOBALS['multi_msg'][4];
                $isInsert = false;
            }
            else {
                $insertProduct['item_name'] = $itemName;
                $item_url=clean_url_name($itemName);
                while($socStore->checkProductName($item_url,$_SESSION['ShopID'])) {
                    $item_url.=date('His').$i;
                }
                $insertProduct['url_item_name'] = $item_url;
            }

            //price
            $price = $this->_getValueByField('StartPrice');
            if($price <= 0) {
                $_SESSION['multi_upload'][$i]['msg'][$this->_getFieldKey('StartPrice')] = $GLOBALS['multi_msg'][6];
                $isInsert = false;
            }
            else {
                $insertProduct['price'] = $price;
            }

            //category
            $categoryID = $this->_getValueByField('Category');
            $categoryID = $this->_getSOCCateIDByEbayCateID($categoryID);
            $insertProduct['category'] = $categoryID;

            //description
            $description = $this->_getValueByField('Description');
            $description = ltrim($description, '@@@@%');
            $description = str_replace('%0D%0A', '\n', $description);
            if(empty($description)) {
                $_SESSION['multi_upload'][$i]['msg'][$this->_getFieldKey('Description')] = $GLOBALS['multi_msg'][5];
                $isInsert = false;
            }
            else {
                $insertProduct['description'] = str_replace('&nbsp;', ' ', formatTextarea(strip_tags($description)));
            }

            //payment method
            $payment = array();
            $p_paypal = $this->_getValueByField('PayPalAccepted');
            if(1 == $p_paypal) {
                $payment[] = 3;
            }
            if(1 == $this->_getValueByField('CashOnPickup')) {
                $payment[] = 10;
            }
            if(1 == $this->_getValueByField('COD')) {
                $payment[] = 9;
            }
            if(1 == $this->_getValueByField('MOCashiers') or 1 == $this->_getValueByField('PersonalCheck')) {
                $payment[] = 4;
            }
            if(1 == $this->_getValueByField('PaymentSeeDescription') or 1 == $this->_getValueByField('Escrow')) {
                $payment[] = 6;
            }
            if(count($payment) > 0) {
                $insertProduct['payments'] = serialize($payment);
            }
            else {
                $isInsert = false;
                $_SESSION['multi_upload'][$i]['msg'][] = $GLOBALS['multi_msg'][27];
            }
            


            //home page
            $homePage = $this->_getValueByField('HomePageFeatured');
            $insertProduct['isfeatured'] = 1 == $homePage ? '1' : '0';

            //shipping
            $shippingIn = array();
            $shippingInCost = array();

            $shippingOut = array();
            $shippingOutCost = array();

            $shippingType = strtolower($this->_getValueByField('ShippingType'));
            if('notspecified' == $shippingType) {
                $shippingIn[] = 3;
                $shippingInCost[] = 0;
            }
            else {
//                if('flat' == $shippingType) {
//                    $ship_1 = $this->_getValueByField('ShippingService-1:Option');
//                    if(preg_match('/^USPS/i', $ship_1)) {
//                        $shippingIn[] = 1;
//                        $shippingInCost[] = $this->_getValueByField('ShippingService-1:Cost');
//                    }
//                    elseif(!empty($ship_1)) {
//                        $shippingIn[] = 2;
//                        $shippingInCost[] = $this->_getValueByField('ShippingService-1:Cost');
//                    }
//
//                    $ship_2 = $this->_getValueByField('ShippingService-2:Option');
//                    if(!empty($ship_2)) {
//                        $shippingIn[] = 2;
//                        $shippingInCost[] = $this->_getValueByField('ShippingService-2:Cost');
//                    }
//                }
                $ship_1 = $this->_getValueByField('ShippingService-1:Option');
                $ship_2 = $this->_getValueByField('ShippingService-2:Option');
                $ship_3 = $this->_getValueByField('ShippingService-3:Option');

                if(preg_match('/Other/i', $ship_1)) {
                    $shippingIn[4] = 4;
                    $shippingInCost[4] = 0;
                }
                elseif(preg_match('/Courier/i', $ship_1)) {
                    $shippingIn[2] = 2;
                    $shippingInCost[2] = $this->_getValueByField('ShippingService-1:Cost');
                }
                elseif(preg_match('/Regular|Small|Registered/i', $ship_1)) {
                    $shippingIn[1] = 1;
                    $shippingInCost[1] = $this->_getValueByField('ShippingService-1:Cost');
                }
                elseif(preg_match('/Express/i' , $ship_1)) {
                    $shippingIn[6] = 6;
                    $shippingInCost[6] = $this->_getValueByField('ShippingService-1:Cost');
                }

                if(preg_match('/Other/i', $ship_2)) {
                    $shippingIn[4] = 4;
                    $shippingInCost[4] = 0;
                }
                elseif(preg_match('/Courier/i', $ship_2)) {
                    $shippingIn[2] = 2;
                    $shippingInCost[2] = $this->_getValueByField('ShippingService-2:Cost');
                }
                elseif(preg_match('/Regular|Small|Registered/i', $ship_2)) {
                    $shippingIn[1] = 1;
                    $shippingInCost[1] = $this->_getValueByField('ShippingService-2:Cost');
                }
                elseif(preg_match('/Express/i' , $ship_2)) {
                    $shippingIn[6] = 6;
                    $shippingInCost[6] = $this->_getValueByField('ShippingService-2:Cost');
                }

                if(preg_match('/Other/i', $ship_3)) {
                    $shippingIn[4] = 4;
                    $shippingInCost[4] = 0;
                }
                elseif(preg_match('/Courier/i', $ship_3)) {
                    $shippingIn[2] = 2;
                    $shippingInCost[2] = $this->_getValueByField('ShippingService-3:Cost');
                }
                elseif(preg_match('/Regular|Small|Registered/i', $ship_3)) {
                    $shippingIn[1] = 1;
                    $shippingInCost[1] = $this->_getValueByField('ShippingService-3:Cost');
                }
                elseif(preg_match('/Express/i' , $ship_3)) {
                    $shippingIn[6] = 6;
                    $shippingInCost[6] = $this->_getValueByField('ShippingService-3:Cost');
                }



                //inter
                $inShip_1 = $this->_getValueByField('IntlShippingService-1:Option');
                $inShip_2 = $this->_getValueByField('IntlShippingService-2:Option');
                $inShip_3 = $this->_getValueByField('IntlShippingService-3:Option');

                if(preg_match('/other/i', $inShip_1)) {
                    $shippingOut[4] = 4;
                    $shippingOutCost[4] = 0;
                }
                elseif(preg_match('/Courier/i', $inShip_1)) {
                    $shippingOut[2] = 2;
                    $shippingOutCost[2] = $this->_getValueByField('IntlShippingService-1:Cost');
                }
                elseif(preg_match('/Express|Expedited/i', $inShip_1)) {
                    $shippingOut[6] = 6;
                    $shippingOutCost[6] = $this->_getValueByField('IntlShippingService-1:Cost');
                }
                elseif(!empty($inShip_1)) {
                    $shippingOut[1] = 1;
                    $shippingOutCost[1] = $this->_getValueByField('IntlShippingService-1:Cost');
                }

                if(preg_match('/other/i', $inShip_2)) {
                    $shippingOut[4] = 4;
                    $shippingOutCost[4] = 0;
                }
                elseif(preg_match('/Courier/i', $inShip_2)) {
                    $shippingOut[2] = 2;
                    $shippingOutCost[2] = $this->_getValueByField('IntlShippingService-2:Cost');
                }
                elseif(preg_match('/Express|Expedited/i', $inShip_2)) {
                    $shippingOut[6] = 6;
                    $shippingOutCost[6] = $this->_getValueByField('IntlShippingService-2:Cost');
                }
                elseif(!empty($inShip_2)) {
                    $shippingOut[1] = 1;
                    $shippingOutCost[1] = $this->_getValueByField('IntlShippingService-2:Cost');
                }

                if(preg_match('/other/i', $inShip_3)) {
                    $shippingOut[4] = 4;
                    $shippingOutCost[4] = 0;
                }
                elseif(preg_match('/Courier/i', $inShip_3)) {
                    $shippingOut[2] = 2;
                    $shippingOutCost[2] = $this->_getValueByField('IntlShippingService-3:Cost');
                }
                elseif(preg_match('/Express|Expedited/i', $inShip_3)) {
                    $shippingOut[6] = 6;
                    $shippingOutCost[6] = $this->_getValueByField('IntlShippingService-3:Cost');
                }
                elseif(!empty($inShip_3)) {
                    $shippingOut[1] = 1;
                    $shippingOutCost[1] = $this->_getValueByField('IntlShippingService-3:Cost');
                }
            }

            if(empty($shippingIn)) {
                $_SESSION['multi_upload'][$i]['msg'][] = 'Shipping is invalid.';
                $isInsert = false;
            }
            else {
                $insertProduct['deliveryMethod'] = implode('|', $shippingIn);
                $insertProduct['postage'] = implode('|', $shippingInCost);
                if(!empty($shippingOut)) {
                    $insertProduct['isoversea'] = '1';
                    $insertProduct['oversea_deliveryMethod'] = implode('|', $shippingOut);
                    $insertProduct['oversea_postage'] = implode('|', $shippingOutCost);
                }
                else {
                    $insertProduct['isoversea'] = '0';
                    $insertProduct['oversea_deliveryMethod'] = '';
                    $insertProduct['oversea_postage'] = '';
                }
            }

            


            //offer
            $offer = $this->_getValueByField('BestOfferEnabled');
            $insertProduct['non'] = (1 == $offer and !$isAuction) ? '1' : '0';

            //stock
            $stock = intval($this->_getValueByField('Quantity'));
            $insertProduct['stockQuantity'] = $isAuction ? 1 : $stock;

            //on sold
            $onSale = 'yes';
            if(0 == $stock and !$isAuction) {
                $onSale = 'sold';
            }
            $insertProduct['on_sale'] = $onSale;

            //is auction
            $insertProduct['is_auction'] = $isAuction ? 'yes' : 'no';

            //images
            $_img = '';
            for($x=0; $x<=6; $x++) {
                $_img[] = $this->_getImageInfo($this->_getValueByField('img_' . $x));
            }
            foreach($_img as $k=>$v) {
                if(is_array($v) and count($k)>0) {
                    if(0 == $k) {
                        $insertProduct['image_name'] = $v['tmpname'];
                    }
                    else {
                        $key = 'moreImage' . $k;
                        $insertProduct[$key] = $v['tmpname'];
                    }
                }
            }


            //set other value
            $insertProduct['isattachment'] = '0';
            $insertProduct['unit'] = '';
            $insertProduct['StoreID'] = $_SESSION['StoreID'];
            $insertProduct['datec']=time();
            $insertProduct['datem']=time();
            
            //insert into product table
            if(true === $isInsert) {
                if($socStore->dbcon->insert_record($socStore->table.'product',$insertProduct)) {
                    $pid = $socStore->dbcon->insert_id();

                    //insert into image
                    foreach($_img as $k=>$v) {
                        if(is_array($v) and count($v)>0) {
                            $tmp = array();
                            $tmp['StoreID'] = $_SESSION['StoreID'];
                            $tmp['tpl_type'] = 0;
                            $tmp['pid'] = $pid;
                            $tmp['smallPicture'] = $v['smallpicture'];
                            $tmp['picture'] = $v['picture'];
                            $tmp['attrib'] = 0 == $k ? 0 : 1;
                            $tmp['sort'] = $k>0 ? $k-1 : $k;
                            $tmp['datec'] = time();
                            $tmp['datem'] = time();
                            $socStore->dbcon->insert_record($socStore->table.'image',$tmp);
                        }
                    }


                    //if product is auction
                    if($isAuction) {
                        $insertAuction['pid'] = $pid;
                        $insertAuction['initial_price'] = $price;
                        $insertAuction['cur_price'] = $price;
                        $insertAuction['reserve_price'] = $this->_getValueByField('ReservePrice');
                        $insertAuction['bid'] = 0;
                        $insertAuction['winner_id'] = 0;
                        $insertAuction['starttime_stamp'] = time();
                        $endTimeStamp = time() + (24*3600*intval($this->_getValueByField('Duration')));
                        $insertAuction['end_date'] = date('Y-m-d', $endTimeStamp);
                        $insertAuction['end_time'] = date('H:i', $endTimeStamp);
                        $insertAuction['end_stamp'] = $endTimeStamp;
                        $socStore->dbcon->insert_record($socStore->table.'product_auction', $insertAuction);
                    }

                    $succ++;
                }
                else {
                    $_SESSION['multi_upload'][$i]['msg'][] = $GLOBALS['multi_msg'][17];
                    $fail++;
                }
            }
            else {
                $fail++;
            }

            
            
        }


        return array($succ, $fail);

    }


    public function blackthorn($products,$images)
    {
        $socStore = new socstoreClass();

        $imgup = new uploadImages();
        $images = $imgup->getzipProductImages($images,false);

        //set images
        $this->_images = $images;
//var_dump($images);exit;
        $this->_fieldList = $products[0];       //set field value

        $succ = 0;
        $fail = 0;
        for($i = 1; $i < count($products); $i++) {
            $this->_row = $products[$i];

            $insertProduct = array();       //insert into product table
            $insertAuction = array();       //if product is auction , insert auction table.
            $insertImages = array();        //insert into product image table

            $isInsert = true;       //insert is right


            $price = $this->_getValueByField('Buy It Now Price');
            $startPrice = $this->_getValueByField('Starting Price');
            $reserPrice = $this->_getValueByField('Reserve Price');
            $buyPrice = $this->_getValueByField('Fixed Price');
            $isAuction = (1 == $this->_getValueByField('Listing Format')) ? true : false ;     // product default type is buy & sell


            $_SESSION['multi_upload'][$i]['ID'] = $i;

            //item name
            $itemName = $this->_getValueByField('Title');
            if(empty($itemName)) {
                $_SESSION['multi_upload'][$i]['msg'][$this->_getFieldKey('Title')] = $GLOBALS['multi_msg'][4];
                $isInsert = false;
            }
            else {
                $insertProduct['item_name'] = $itemName;
                $item_url=clean_url_name($itemName);
                while($socStore->checkProductName($item_url,$_SESSION['ShopID'])) {
                    $item_url.=date('His').$i;
                }
                $insertProduct['url_item_name'] = $item_url;
            }

            //price
            if($isAuction) {
                if($startPrice <= 0 or $reserPrice <= 0 ) {
                    $_SESSION['multi_upload'][$i]['msg'][] = $GLOBALS['multi_msg'][6];
                    $isInsert = false;
                }
                else {
                    $insertProduct['price'] = $startPrice;
                    $insertAuction['initial_price'] = $startPrice;
                    $insertAuction['cur_price'] = $startPrice;
                    $insertAuction['reserve_price'] = $reserPrice;
                }
            }
            else {
                if($buyPrice <= 0) {
                    $_SESSION['multi_upload'][$i]['msg'][] = $GLOBALS['multi_msg'][6];
                    $isInsert = false;
                }
                else {
                    $insertProduct['price'] = $buyPrice;
                }
            }

            //category
            $categoryID = $this->_getValueByField('Category Number 1');
            $categoryID = $this->_getSOCCateIDByEbayCateID($categoryID);
            $insertProduct['category'] = $categoryID;


            //description
            $description = $this->_getValueByField('Description');
            if(empty($description)) {
                $_SESSION['multi_upload'][$i]['msg'][$this->_getFieldKey('Description')] = $GLOBALS['multi_msg'][5];
                $isInsert = false;
            }
            else {
                $insertProduct['description'] = formatTextarea(strip_tags($description));
            }

            //home page
            $homePage = $this->_getValueByField('Home Featured');
            $insertProduct['isfeatured'] = 'TRUE' == strtoupper($homePage) ? '1' : '0';



            //offer
            $offer = $this->_getValueByField('Best Offer');
            $insertProduct['non'] = ('TRUE' == strtoupper($offer) and !$isAuction) ? '1' : '0';

            //stock
            $stock = intval($this->_getValueByField('Quantity to List'));
            $insertProduct['stockQuantity'] = $isAuction ? 1 : $stock;

            //on sold
            $onSale = 'yes';
            if(0 == $stock and !$isAuction) {
                $onSale = 'sold';
            }
            $insertProduct['on_sale'] = $onSale;

            //is auction
            $insertProduct['is_auction'] = $isAuction ? 'yes' : 'no';

            //images
            $img = array();
            for($x=1; $x<=7; $x++) {
                $_img = $this->_getImageInfo($this->_getValueByField('Picture ' . $x));
                if(count($_img) >0) {
                    if(1 == $x) {
                        $insertProduct['image_name'] = $_img['tmpname'];
                    }
                    else {
                        $_key = 'moreImage' . ($x-1);
                        $insertProduct[$_key] = $_img['tmpname'];
                    }
//                    $_img['_sort'] = $x;
                    $img[] = $_img;
                }
            }


            //payment
            $payment = array();
            $isPaypal = $this->_getValueByField('PayPal Accepted');
            if('TRUE' == strtoupper($isPaypal)) {
                $payment[] = 3;
            }
            if(count($payment) > 0) {
                $insertProduct['payments'] = serialize($payment);
            }
            else {
                $isInsert = false;
                $_SESSION['multi_upload'][$i]['msg'][] = $GLOBALS['multi_msg'][27];
            }


            //shipping
            $shipping = array();
            $shippingCost = array();
            $interShipping = array();
            $interShippingCost = array();
            $ship_1 = $this->_getValueByField('Shipping Service 1');
            $ship_2 = $this->_getValueByField('Shipping Service 2');
            $ship_3 = $this->_getValueByField('Shipping Service 3');
            if(preg_match('/ups/i', $ship_1) or preg_match('/usps/i', $ship_1)) {
                $shipping[1] = 1;
                $shippingCost[1] = $this->_getValueByField('Shipping Service Cost 1');
            }
            elseif(preg_match('/other/i', $ship_1)) {
                $shipping[1] = 4;
                $shippingCost[1] = $this->_getValueByField('Shipping Service Cost 1');
            }
            elseif(preg_match('/pickup/i', $ship_1)) {
                $shipping[1] = 3;
                $shippingCost[1] = $this->_getValueByField('Shipping Service Cost 1');
            }
            elseif(!empty($ship_1)) {
                $shipping[1] = 2;
                $shippingCost[1] = $this->_getValueByField('Shipping Service Cost 1');
            }

            if(preg_match('/ups/i', $ship_2) or preg_match('/usps/i', $ship_2)) {
                $shipping[2] = 1;
                $shippingCost[2] = $this->_getValueByField('Shipping Service Cost 2');
            }
            elseif(preg_match('/other/i', $ship_2)) {
                $shipping[2] = 4;
                $shippingCost[2] = $this->_getValueByField('Shipping Service Cost 2');
            }
            elseif(preg_match('/pickup/i', $ship_2)) {
                $shipping[2] = 3;
                $shippingCost[2] = $this->_getValueByField('Shipping Service Cost 2');
            }
            elseif(!empty($ship_2)) {
                $shipping[2] = 2;
                $shippingCost[2] = $this->_getValueByField('Shipping Service Cost 2');
            }

            if(preg_match('/ups/i', $ship_3) or preg_match('/usps/i', $ship_3)) {
                $shipping[3] = 1;
                $shippingCost[3] = $this->_getValueByField('Shipping Service Cost 3');
            }
            elseif(preg_match('/other/i', $ship_3)) {
                $shipping[3] = 4;
                $shippingCost[3] = $this->_getValueByField('Shipping Service Cost 3');
            }
            elseif(preg_match('/pickup/i', $ship_3)) {
                $shipping[3] = 3;
                $shippingCost[3] = $this->_getValueByField('Shipping Service Cost 3');
            }
            elseif(!empty($ship_3)) {
                $shipping[3] = 2;
                $shippingCost[3] = $this->_getValueByField('Shipping Service Cost 3');
            }

            //inter
            $inShip_1 = $this->_getValueByField('International Shipping Service 1');
            $inShip_2 = $this->_getValueByField('International Shipping Service 2');
            $inShip_3 = $this->_getValueByField('International Shipping Service 3');
            if(preg_match('/ups/i', $inShip_1) or preg_match('/usps/i', $inShip_1)) {
                $interShipping[1] = 1;
                $interShippingCost[1] = $this->_getValueByField('International Shipping Service Cost 1');
            }
            elseif(preg_match('/other/i', $ship_1)) {
                $interShipping[1] = 4;
                $interShippingCost[1] = $this->_getValueByField('International Shipping Service Cost 1');
            }
            elseif(!empty($ship_1)) {
                $interShipping[1] = 2;
                $interShippingCost[1] = $this->_getValueByField('International Shipping Service Cost 1');
            }

            if(preg_match('/ups/i', $inShip_2) or preg_match('/usps/i', $inShip_2)) {
                $interShipping[2] = 1;
                $interShippingCost[2] = $this->_getValueByField('International Shipping Service Cost 2');
            }
            elseif(preg_match('/other/i', $inShip_2)) {
                $interShipping[2] = 4;
                $interShippingCost[2] = $this->_getValueByField('International Shipping Service Cost 2');
            }
            elseif(!empty($inShip_2)) {
                $interShipping[2] = 2;
                $interShippingCost[2] = $this->_getValueByField('International Shipping Service Cost 2');
            }
            
            if(preg_match('/ups/i', $inShip_3) or preg_match('/usps/i', $inShip_3)) {
                $interShipping[3] = 1;
                $interShippingCost[3] = $this->_getValueByField('International Shipping Service Cost 3');
            }
            elseif(preg_match('/other/i', $inShip_3)) {
                $interShipping[3] = 4;
                $interShippingCost[3] = $this->_getValueByField('International Shipping Service Cost 3');
            }
            elseif(!empty($inShip_3)) {
                $interShipping[3] = 2;
                $interShippingCost[3] = $this->_getValueByField('International Shipping Service Cost 3');
            }

            if(count($shipping) > 0 ) {
                $insertProduct['deliveryMethod'] = implode('|', $shipping);
                $insertProduct['postage'] = implode('|', $shippingCost);
                if(count($interShipping) > 0) {
                    $insertProduct['isoversea'] = '1';
                    $insertProduct['oversea_deliveryMethod'] = implode('|', $interShipping);
                    $insertProduct['oversea_postage'] = implode('|', $interShippingCost);
                }
                else {
                    $insertProduct['isoversea'] = '0';
                    $insertProduct['oversea_deliveryMethod'] = '';
                    $insertProduct['oversea_postage'] = '';
                }
            }
            else {
                $_SESSION['multi_upload'][$i]['msg'][] = 'Shipping is invalid.';
                $isInsert = false;
            }

            //set other value
            $insertProduct['isattachment'] = '0';
            $insertProduct['unit'] = '';
            $insertProduct['StoreID'] = $_SESSION['StoreID'];
            $insertProduct['datec']=time();
            $insertProduct['datem']=time();
//echo '<pre>';var_dump($insertProduct,$img);exit;
            //insert into product table
            if(true === $isInsert) {
                if($socStore->dbcon->insert_record($socStore->table.'product', $insertProduct)) {
                    $pid = $socStore->dbcon->insert_id();

                    //insert into image
                    foreach($img as $k=>$v) {
                        if(is_array($v) and count($v)>0) {
                            $tmp = array();
                            $tmp['StoreID'] = $_SESSION['StoreID'];
                            $tmp['tpl_type'] = 0;
                            $tmp['pid'] = $pid;
                            $tmp['smallPicture'] = $v['smallpicture'];
                            $tmp['picture'] = $v['picture'];
                            $tmp['attrib'] = 0 == $k ? 0 : 1;
                            $tmp['sort'] = 0 == $k ? 0 : $k-1;
                            $tmp['datec'] = time();
                            $tmp['datem'] = time();
                            $socStore->dbcon->insert_record($socStore->table.'image',$tmp);
                        }
                    }

                    //if product is auction
                    if($isAuction) {
                        $insertAuction['pid'] = $pid;
                        $insertAuction['bid'] = 0;
                        $insertAuction['winner_id'] = 0;
                        $insertAuction['starttime_stamp'] = time();
                        $endTimeStamp = time() + (24*3600*intval($this->_getValueByField('Duration')));
                        $insertAuction['end_date'] = date('Y-m-d', $endTimeStamp);
                        $insertAuction['end_time'] = date('H:i', $endTimeStamp);
                        $insertAuction['end_stamp'] = $endTimeStamp;
                        $socStore->dbcon->insert_record($socStore->table.'product_auction', $insertAuction);
                    }

                    $succ++;
                }
                else {
                    $_SESSION['multi_upload'][$i]['msg'][] = $GLOBALS['multi_msg'][17];
                    $fail++;
                }
            }
            else {
                $fail++;
            }
        }
        
        return array($succ, $fail);

    }






    private function _getValueByField($title='')
    {
        $key = $this->_getFieldKey($title);
        return isset($this->_row[$key]) ? $this->_row[$key] : '';
    }

    private function _getFieldKey($title)
    {
        $key = array_search($title, $this->_fieldList);
        return $key;
    }


    private function _getSOCCateIDByEbayCateID($cateID=0)
    {
//        $socStore = new socstoreClass();
//        $sql = 'SELECT * FROM `' . $socStore->table . 'ebay_category` WHERE `id` = ' . intval($cateID);
//        $socStore->dbcon->execute_query($sql);
//
//        $rs = $socStore->dbcon->fetch_records(true);
//        $cateName = $rs[0]['category_name'];
//
//        $sql = 'SELECT * FROM `' . $socStore->table . 'product_category` WHERE `name` = "' . mysql_real_escape_string($cateName) . '" ';
//
//        $socStore->dbcon->execute_query($sql);
//        $rs = $socStore->dbcon->fetch_records(true);
//        $cateID = $rs[0]['id'];
//        return $cateID>0 ? $cateID : 34;
        if(isset($this->_category["$cateID"]) and $this->_category["$cateID"] > 0) {
            return $this->_category["$cateID"];
        }
        return 876;
    }


    private function _getImageInfo($img='')
    {
        $info = array();
        if(isset($this->_images["$img"])) {
            $info = $this->_images["$img"];
        }
        elseif(preg_match('/^http/i', $img)) {
            $fileName = end(preg_split('/\/|\\\/', $img));
            $info['tmpname'] = $fileName;
            $info['picture'] = $img;
            $info['smallpicture'] = $img;
        }

        return $info;
    }


}
?>
