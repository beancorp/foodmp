<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once(dirname(__FILE__) . '/class.db.dbbase.php');
class Referrer
{

    public function  __construct()
    {
        $this -> dbcon  = &$GLOBALS['dbcon'];
        $this -> table	= &$GLOBALS['table'];
        $this -> smarty = &$GLOBALS['smarty'];
        $this -> lang	= &$GLOBALS['_LANG'];
    }

    
//    public function getReferrerByStoreIDPage($storeID=0, $page=1)
//    {
//        $sql = 'SELECT a.* ,b.StoreID as my_store_id,b.bu_nickname AS nickname FROM `'
//             . $this->table. 'bu_detail` as a left join `'
//             . $this->table. 'bu_detail` as b on (b.ref_name = a.referrer) '
//             . 'WHERE a.referrer IS NOT NULL AND a.referrer<>"" AND b.StoreID = ' . $storeID;
//        $this->dbcon->execute_query($sql);
//        $tmp = $this->dbcon->fetch_records(true);
//
//        //user list
//        $userList = array();
//        foreach($tmp as $v) {
//            $userList[] = array(
//                    'StoreID'=>$v['StoreID'],
//                    'attribute'=>$v['attribute'],
//                    'email'     =>  $v['bu_email'],
//                    'nickname'  =>  $v['bu_nickname'],
//                );
//        }
//
//        //products counter
//        $productsCounter = array();
//        foreach($userList as $value) {
//            $productsCounter[$value['StoreID']] = $this->_getUserProductCounter($value['StoreID'], $value['attribute']);
//        }
//
//        $result = array();
//        $rank = 1;
//        foreach($userList as $user) {
//            //reg 1 user
//            $tmp = array();
//            $tmp['rank'] = $rank; $rank++;
//            $tmp['type'] = 'user';
//            $tmp['store_id'] = $user['StoreID'];
//            $tmp['email']   = $user['email'];
//            $tmp['nickname'] = $user['nickname'];
//            $result[] = $tmp;
//            if(isset($productsCounter[$user['StoreID']])) {
//                //1 product
//                if($productsCounter[$user['StoreID']] >0 and $productsCounter[$user['StoreID']] < 5) {
//                    $tmp = array();
//                    $tmp['rank'] = $rank; $rank++;
//                    $tmp['type'] = 'product_1';
//                    $tmp['store_id'] = $user['StoreID'];
//                    $tmp['email']   = $user['email'];
//                    $tmp['nickname'] = $user['nickname'];
//                    $result[] = $tmp;
//                }
//                // 5 product
//                if($productsCounter[$user['StoreID']] >= 5) {
//                    $tmp = array();
//                    $tmp['rank'] = $rank; $rank++;
//                    $tmp['type'] = 'product_5';
//                    $tmp['store_id'] = $user['StoreID'];
//                    $tmp['email']   = $user['email'];
//                    $tmp['nickname'] = $user['nickname'];
//                    $result[] = $tmp;
//                }
//            }
//        }
//
//        //page
//
//        $page = intval($page)>0 ? intval($page) : 1;
//        $counter = count($result);
//        $perNum = 3;
//        $rs = array_slice($result, ($page-1)*$perNum, $perNum);
//
//        $pages = new pagerClass();
//        $pages = $pages->getLink($page, $counter, $perNum, 'page');
////        echo '<pre>';var_dump($rs, $pages['linksAll']);exit;
//
//    }
//
//    private function _getUserProductCounter($storeID=0, $attribute=0)
//    {
//        switch($attribute) {
//            case 0:
//                $sql = 'SELECT COUNT(*) AS counter FROM ' . $this->table . 'product WHERE deleted<>"YES" AND StoreID = ' . $storeID;
//                break;
//            case 1:
//                $sql = 'SELECT COUNT(*) AS counter FROM ' . $this->table . 'product_realestate WHERE deleted = 0  AND StoreID = ' . $storeID;
//                break;
//            case 2:
//                $sql = 'SELECT COUNT(*) AS counter FROM ' . $this->table . 'product_automotive WHERE deleted = 0  AND StoreID = ' . $storeID;
//                break;
//            case 3:
//                $sql = 'SELECT COUNT(*) AS counter FROM ' . $this->table . 'product_job WHERE deleted = 0  AND StoreID = ' . $storeID;
//                break;
//            default:
//                return 0;
//                break;
//        }
//
//        $this->dbcon->execute_query($sql);
//        $tmp = $this->dbcon->fetch_records(true);
//        return $tmp[0]['counter'];
//    }



//====================================================================      new rule        ========================================================

        /**
         * table
         * id
         * store_id my storeid
         * ref_store_id my referrer storeid
         * datestamp add datestamp
         * type reg/product
         * product_counter  product numbers
         */

    /**
     * new rule
     * in table referrer_records
     */
    public function getReferByStoreIDPage($refStoreID=0, $page=1, $perNums=10)
    {
        $sql = 'SELECT COUNT(*) AS counter FROM ' . $this->table . 'referrer_records WHERE `product_counter` IN (0,1,5) AND ref_store_id = \'' . intval($refStoreID) .'\'';
        $this->dbcon->execute_query($sql);
        $tmp = $this->dbcon->fetch_records(true);
        $counter = $tmp[0]['counter'];


        $page = intval($page) <= 1 ? 1 : intval($page);
        $offset = ($page-1) * $perNums;
        $sql = 'SELECT r.*, u.bu_nickname AS nickname, u.bu_email AS email FROM ' . $this->table . 'referrer_records AS r LEFT JOIN ' . $this->table . 'bu_detail AS u ON (u.StoreID = r.store_id) WHERE `product_counter` IN (0,1,5) AND ref_store_id = \'';
        $sql .= intval($refStoreID) . '\' ORDER BY `datestamp` DESC LIMIT ' . $offset . ', ' . $perNums;
        $this->dbcon->execute_query($sql);
        $tmp = $this->dbcon->fetch_records(true);

        $pages = new pagerClass();
        $pages = $pages->getLink($page, $counter, $perNums, 'page');

        return array('counter'=>$counter, 'list'=>$tmp, 'links'=>$pages['linksAll']);
//        echo '<pre>';var_dump($tmp,$pages['linksAll']);exit;
    }



    /**
     * @author YangBall, 2011-07-05
     * @param <string> $type referrer type
     * @param <int> $myStoreID my store id
     */
     public function addReferrerRecord($type='reg', $myStoreID=0)
     {
         $type = strtolower($type);
         if(!in_array($type, array('reg', 'product'))) {
             $type = 'reg';
         }

         $sql = 'SELECT a.* ,b.StoreID as my_store_id,b.bu_nickname AS nickname FROM ' . $this->table . 'bu_detail  AS a LEFT JOIN ' . $this->table . 'bu_detail AS b ON (b.ref_name = a.referrer) WHERE a.StoreID = ' . intval($myStoreID);
         $this->dbcon->execute_query($sql);
         $rs = $this->dbcon->fetch_records(true);
         $refStoreID = $rs[0]['my_store_id'];
         if(intval($refStoreID) <= 0) {
             return false;
         }
//         echo '<pre>';var_dump($rs);exit;

         switch($type) {
             case 'reg':
                 return $this->_addRegReferrer($refStoreID, $myStoreID);
                 break;

             case 'product':
                 return $this->_addProductReferrer($refStoreID, $myStoreID);
                 break;

             default :
                 return false;
                 break;
         }
     }

     public function getStoreIDByRefer($ref_name='')
     {
        $sql = 'SELECT * FROM ' . $this->table . 'bu_detail WHERE ref_name = "' . $ref_name . '"';
        $this->dbcon->execute_query($sql);
        $rs = $this->dbcon->fetch_records(true);
        return (int)$rs[0]['StoreID'];
     }


     private function _addRegReferrer($refStoreID=0, $myStoreID=0, $point=1)
     {
         $array = array(
             'ref_store_id' =>  $refStoreID,
             'store_id'     =>  $myStoreID,
             'datestamp'    =>  time(),
             'type'         =>  'reg',
             'product_counter'  =>  0
         );
		 
         if ($this->dbcon->insert_query($this->table . 'referrer_records', $array)) {
			return true;		 
		 }
		 
		 return false;
     }

     private function _addProductReferrer($refStoreID, $myStoreID)
     {
         //get products counter
         $sql = 'SELECT COUNT(*) AS counter FROM ' . $this->table . 'referrer_records WHERE `type` = "product" AND store_id = ' . intval($myStoreID);
         $this->dbcon->execute_query($sql);
         $rs = $this->dbcon->fetch_records(true);
         $rs = $rs[0]['counter'];

         if($rs >= 5) {
             return true;
         }
         
         $array = array(
             'ref_store_id' =>  $refStoreID,
             'store_id'     =>  $myStoreID,
             'datestamp'    =>  time(),
             'type'         =>  'product',
             'product_counter'  =>  $rs+1
         );
         return $this->dbcon->insert_query($this->table . 'referrer_records', $array);

     }
}