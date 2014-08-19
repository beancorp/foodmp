<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'class.page.php';

class FoodWine extends common {

    var $dbcon = null;
    var $table = '';
    var $smarty = null;
    var $lang = null;

    function __construct() {
        $this->dbcon = &$GLOBALS['dbcon'];
        $this->time_zone_offset = &$GLOBALS['time_zone_offset'];
        $this->table = &$GLOBALS['table'];
        $this->smarty = &$GLOBALS['smarty'];
        $this->lang = &$GLOBALS['_LANG'];
        $this->blog_length = &$GLOBALS['blog_length'];
    }

    public function getCategoryList($type='wine', $StoreID=0) {
//    	if ($type == 'wine') {
//	        $sql = 'SELECT * FROM `' . $this->table . 'product_category_foodwine` WHERE `fid`=2 AND deleted=0 AND StoreID=' . $StoreID;
//    	} else {    		
//	        $sql = 'SELECT * FROM `' . $this->table . 'product_category_foodwine` WHERE `fid`=1 AND deleted=0 ';
//    	}

        $sql = 'SELECT * FROM `' . $this->table . 'product_category_foodwine` WHERE deleted=0 AND StoreID=' . $StoreID . ' ORDER BY `order` ASC';

        $this->dbcon->execute_query($sql);
        $res = $this->dbcon->fetch_records(true);

        return $res ? $res : array();
    }

    public function getLastEmailAlert($StoreID=0) {
        $StoreID = $StoreID ? $StoreID : $_SESSION['StoreID'];
        $sql = "SELECT * FROM  {$this->table}emailalerts_detail_foodwine WHERE StoreID = '" . $StoreID . "' ORDER BY id DESC limit 1";
        $this->dbcon->execute_query($sql);
        $data = $this->dbcon->fetch_records(true);
        return (count($data) > 0) ? $data[0] : false;
    }

    public function saveCategory($cid_ary=array(), $category_name_ary=array(), $StoreID=0) {
		if (empty($cid_ary) && !is_array($cid_ary) || empty($category_name_ary) || !is_array($category_name_ary)) {
			return false;
        }
        $fid = getFoodWineType() == 'food' ? 1 : 2;
        foreach ($cid_ary as $key => $cid) {
            if ($cid) {
                $sql = "UPDATE {$this->table}product_category_foodwine SET `category_name`='$category_name_ary[$key]',`order`='$key' WHERE id='$cid' AND StoreID='$StoreID'";
            } else {
                $sql = "INSERT INTO {$this->table}product_category_foodwine(`fid`,`StoreID`,`category_name`,`order`) VALUES('$fid','$StoreID','$category_name_ary[$key]','$key')";
            }
            $this->dbcon->execute_query($sql);
            $cid_ary[$key] = $cid ? $cid : $this->dbcon->insert_id();
        }
        //Update had delete category
        $cid_str = implode(',', $cid_ary);
        if ($cid_str) {
            $sql = "UPDATE {$this->table}product_category_foodwine SET `deleted`='1' WHERE id NOT IN ($cid_str) AND StoreID='$StoreID'";
            $this->dbcon->execute_query($sql);
        }
        return true;
    }

    public function saveCategoryOrder($cid, $order) {
        $sql = "UPDATE {$this->table}product_category_foodwine SET `order`='$order' WHERE id='$cid'";
        return $this->dbcon->execute_query($sql);
    }

    public function checkProductData($post, $isUpdate=false) {
        $msg = array();

        //check data
        if ('' == $post['item_name']) {
            $msg[] = 'Item Name is required.';
        }
        /* if(!($post['price'] >= 0)) {
          $msg[] = 'Price is required.';
          } */
        if ('' == $post['category']) {
            $msg[] = 'Category is required.';
        }
        /* if('' == $post['description']) {
          $msg[] = 'Description is required.';
          } */

        if (count($msg) > 0) {
            return array('msg' => $msg, 'status' => false);
        }

        //get data
        $data = array();
        $data['item_name'] = $post['item_name'];

        $item_url = clean_url_name($data['item_name']);
        do {
            if ($i > 0) {
                $item_url = clean_url_name($data['item_name']) . date("His");
            }
            $ckname_bl = $this->__checkProductURL($item_url, $isUpdate ? $post['pid'] : 0);
            $i++;
        } while (!$ckname_bl);
        $data['is_special'] = $post['is_special'] ? $post['is_special'] : 0;
        $data['url_item_name'] = $item_url;
        $data['price'] = $post['price'];
        $data['unit'] = $post['unit'];
        $data['description'] = $post['description'];
        $data['p_code'] = $post['p_code'];
        $data['StoreID'] = $_SESSION['StoreID'];
        $data['priceorder'] = $post['priceorder'];
        $data['deleted'] = '0';
        $data['enabled'] = '1';
        $data['enabled'] = '1';
        $data['category'] = $post['category'];
        $data['youtubevideo'] = $post['youtubevideo'];
        if (false == $isUpdate) {
            $data['datec'] = time();
        }
        $data['datem'] = time();
        $data['stock_quantity'] = $post['stockQuantity'];
        $data['isfeatured'] = '1' == $post['isfeatured'] ? '1' : '0';
        $data['type'] = $post['type'];
        $data['sale_state'] = $post['on_sale'];
        $data['tags'] = $post['str_tags'];
        //images

        $images = array();

        if ($post['mainImage_bvalue'] !== '') {
            $mainImage_bvalue = $post['mainImage_bvalue'];
            $mainImage_svalue = $post['mainImage_svalue'];

            /* if ($post['iscopy']) {
              $src_mainImage_bvalue = $mainImage_bvalue;
              $src_mainImage_svalue = $mainImage_svalue;
              $mainImageb_imginfo = pathinfo($mainImage_bvalue);
              $mainImages_imginfo = pathinfo($mainImage_svalue);

              $mainimg_name = 'fwmain'.$_SESSION['StoreID'].time().rand(1000,9999);
              $mainImage_bvalue = '/upload/temp/'.$mainimg_name.'.'.$mainImageb_imginfo['extension'];
              $mainImage_svalue = '/upload/temp/'.$mainimg_name.'_s.'.$mainImages_imginfo['extension'];
              @copy(ROOT_PATH.$src_mainImage_bvalue, ROOT_PATH.$mainImage_bvalue);
              @copy(ROOT_PATH.$src_mainImage_svalue, ROOT_PATH.$mainImage_svalue);
              } */

            $images[] = array(
                'big_url' => $mainImage_bvalue,
                'small_url' => $mainImage_svalue,
                'attrib' => '0',
                'sort' => '0'
            );
        }


        for ($i = 0; $i <= 5; $i++) {
            $fieldName = 'subImage' . $i . '_bvalue';
            if ('' != $post[$fieldName]) {
                $bvalue = $post['subImage' . $i . '_bvalue'];
                $svalue = $post['subImage' . $i . '_svalue'];

                /* if ($post['iscopy']) {
                  $src_bvalue = $bvalue;
                  $src_svalue = $svalue;
                  $b_imginfo = pathinfo($bvalue);
                  $s_imginfo = pathinfo($svalue);

                  $img_name = 'fwsub'.$_SESSION['StoreID'].time().rand(1000,9999);
                  $bvalue = '/upload/temp/'.$img_name.'.'.$b_imginfo['extension'];
                  $svalue = '/upload/temp/'.$img_name.'_s.'.$s_imginfo['extension'];
                  @copy(ROOT_PATH.$src_bvalue, ROOT_PATH.$bvalue);
                  @copy(ROOT_PATH.$src_svalue, ROOT_PATH.$svalue);
                  } */

                $arr['big_url'] = $post['subImage' . $i . '_bvalue'];
                $arr['small_url'] = $post['subImage' . $i . '_svalue'];
                $arr['attrib'] = '1';
                $arr['sort'] = $i;
                $images[$i + 1] = $arr;
            }
        }

        return array('status' => true, 'data' => $data, 'images' => $images);
    }

    public function checkRecipeData($post, $isUpdate=false) {
        $StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID'] : '');
        $msg = array();

        //check data
        if (empty($StoreID)) {
            $msg[] = 'StoreID is required.';
        }
        if (empty($post['title'])) {
            $msg[] = 'Title is required.';
        }
        if (empty($post['content'])) {
            $msg[] = 'Ingredients is required.';
        }

        if (count($msg) > 0) {
            return array('msg' => $msg, 'status' => false);
        }

        //get data
        $data = array();
        $data['title'] = $post['title'];
        $data['content'] = $post['content'];
        $data['method'] = $post['method'];
        $data['StoreID'] = $StoreID;

        if (false == $isUpdate) {
            $data['datec'] = time();
        }
        $data['datem'] = time();
        //images
        $data['picture'] = $post['mainImage_bvalue'];
        $data['thumb'] = $post['mainImage_svalue'];

        return array('status' => true, 'data' => $data);
    }

    public function checkEmailAlertsData($post, $isUpdate=false) {
        $StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID'] : '');
        $msg = array();

        //check data
        if (empty($StoreID)) {
            $msg[] = 'StoreID is required.';
        }
        if (empty($post['type'])) {
            $msg[] = 'Type is required.';
        }
//        if(empty($post['title'])) {
//            $msg[] = 'Description is required.';
//        }
        if (empty($post['pid'])) {
            $msg[] = 'Items is required.';
        }
        if (empty($post['start_date'])) {
            $msg[] = 'Valid From is required.';
        }
        if (empty($post['end_date'])) {
            $msg[] = 'Until is required.';
        }

        if (count($msg) > 0) {
            return array('msg' => $msg, 'status' => false);
        }

        //get data
        $data = array();
		
		$data['hotbuy_message'] = (int)(isset($_POST['hotbuy_message']) ? $_POST['hotbuy_message']: 0);
        $data['type'] = $post['type'];
        $data['title'] = $post['title'];
        $data['start_date'] = $post['start_date'] ? strtotime(str_replace('/', '-', $post['start_date'])) : 0;
        $data['end_date'] = $post['end_date'] ? strtotime(str_replace('/', '-', $post['end_date'])) : 0;
        $data['products'] = is_array($post['pid']) ? implode(',', $post['pid']) : '';
        $data['StoreID'] = $StoreID;

        if (false == $isUpdate) {
            $data['datec'] = time();
        }
        $data['datem'] = time();

        return array('status' => true, 'data' => $data);
    }

    public function saveProduct($data=array(), $images=array(), $pid=0) {
        if ($pid > 0) {
            $this->dbcon->update_record($this->table . 'product_foodwine', $data, ' WHERE `pid` = ' . $pid);
        } else {
            $this->dbcon->insert_query($this->table . 'product_foodwine', $data);
            $pid = $this->dbcon->lastInsertId();
            /**
             * added by YangBall, 2011-07-05
             * referrer new rule
             */
            require_once(SOC_INCLUDE_PATH . '/class.referrer.php');
            $referrer = new Referrer();
            $referrer->addReferrerRecord('product', $_SESSION['StoreID']);
            //END-YangBall
        }

        foreach ($images as $k => $img) {
            $rs = $this->__checkImage($pid, $img['attrib'], $img['sort']);
            $tmp['StoreID'] = $_SESSION['StoreID'];
            $tmp['tpl_type'] = 0;
            $tmp['pid'] = $pid;
            $tmp['smallPicture'] = $img['small_url'];
            $tmp['picture'] = $img['big_url'];
            $tmp['attrib'] = $img['attrib'];
            $tmp['sort'] = $img['sort'];
            $tmp['datem'] = time();
            if (false == $rs) {
                $tmp['datec'] = time();
                $this->dbcon->insert_query($this->table . 'image', $tmp);
            } else {
                $this->dbcon->update_record($this->table . 'image', $tmp, ' WHERE id = ' . $rs);
            }
        }

        /**
         * added by Kevin.Liu, 2012-02-16
         * point new rule
         */
        if ($data['type'] == 'special' || $data['is_special'] || 1) {
            include_once(SOC_INCLUDE_PATH . '/class.point.php');
            $objPoint = new Point();
            $objPoint->addPointRecords($_SESSION['StoreID'], 'year', $pid);
        }
        //END

        return $pid;
    }

    public function saveRecipe($data=array(), $rid=0) {
        if ($rid > 0) {
            $this->dbcon->update_record($this->table . 'recipe', $data, ' WHERE `StoreID`=' . $_SESSION['StoreID'] . ' AND `id`=' . $rid);
        } else {
            $this->dbcon->insert_query($this->table . 'recipe', $data);
            $rid = $this->dbcon->lastInsertId();
        }

        return $rid;
    }

    public function saveEmailAlerts($data=array(), $eid=0) {
        if ($eid > 0) {
            $this->dbcon->update_record($this->table . 'emailalerts_detail_foodwine', $data, ' WHERE `id` = ' . $eid);
        } else {
            $this->dbcon->insert_query($this->table . 'emailalerts_detail_foodwine', $data);
            $eid = $this->dbcon->lastInsertId();
        }

        return $eid;
    }

    private function __checkProductURL($url='', $pid=0) {
        $sql = 'SELECT * FROM `' . $this->table . 'product_foodwine` WHERE `url_item_name` = "' . $url . '" AND deleted=0 AND `StoreID` = ' . $_SESSION['StoreID'];
        if ($pid > 0) {
            $sql .= ' AND pid <> ' . $pid;
        }
        $this->dbcon->execute_query($sql);
        $rs = $this->dbcon->fetch_records(true);
        return count($rs) > 0 ? true : false;
    }

    private function __checkImage($pid, $attrib, $sort=0) {
        $sql = 'SELECT * FROM ' . $this->table . 'image WHERE pid = ' . $pid . ' AND `attrib` = ' . $attrib . ' AND `sort` = ' . $sort;
        $this->dbcon->execute_query($sql);
        $rs = $this->dbcon->fetch_records(true);
        if (is_array($rs) and count($rs) > 0) {
            return $rs[0]['id'];
        }
        return false;
    }

    public function getProductsList($StoreID, $type='wine', $sub_type='', $category='', $isfeatured=false, $tmpl='', $pid_ary=array(), $is_special=0, $order_type=0) {
        
		if (empty($type)) {
            $type = getFoodWineType();
        }

        if ('food' == $type) {
            if ($sub_type && in_array($sub_type, array('stock', 'special'))) {
                $in = '("' . $sub_type . '")';
            } else {
                $in = '("stock", "special")';
            }
        } else {
            $in = '("wine")';
            $where = $is_special ? " AND a.is_special='$is_special'" : '';
        }
        if ($category) {
            $where .= " AND a.category='$category'";
        }
        if ($isfeatured) {
            //$isfeatured = false;
            //$where .= " AND isfeatured=".intval($isfeatured);
            if ($tmpl == 'foodwine-a') {
                $pagesize = 3;
            } elseif ($tmpl == 'foodwine-b') {
                $pagesize = 6;
            } elseif ($tmpl == 'foodwine-c') {
                $pagesize = 4;
            } elseif ($tmpl == 'foodwine-d') {
                $pagesize = 3;
            } elseif ($tmpl == 'foodwine-e') {
                $pagesize = 2;
            } elseif ($tmpl == 'foodwine-f') {
                $pagesize = 4;
            } elseif ($tmpl == 'foodwine-g') {
                $pagesize = 6;
            } elseif ($tmpl == 'foodwine-h') {
                $pagesize = 2;
            }
            $limit = $pagesize ? " Limit 0,$pagesize" : '';
        }
        switch ($order_type) {
            case 1:
                $order = 'b.order ASC,a.isfeatured DESC,a.item_name ASC, a.pid DESC';
                break;

            default:
                $order = 'a.item_name ASC,a.isfeatured DESC,b.order ASC, a.pid DESC';
                break;
        }

        if ($pid_ary && is_array($pid_ary)) {
            $pid_str = implode(',', $pid_ary);
            $where .= " AND a.pid IN ($pid_str)";
        }
		$sql = 'SELECT COUNT(a.pid) AS total FROM ' . $this->table . 'product_foodwine a WHERE a.StoreID = ' . $StoreID . ' AND a.deleted=0 AND a.`type` IN ' . $in . $where . $limit;
        //$sql = 'SELECT COUNT(a.pid) AS total FROM ' . $this->table . 'product_foodwine a WHERE a.StoreID = ' . $StoreID . ' AND a.deleted=0 ' . $where . $limit;
        $res = $this->dbcon->getOne($sql);
        $total = $res['total'] ? $res['total'] : 0;
		
		//echo $sql;
		$wheresql = ' FROM ' . $this->table . 'product_foodwine a,' . $this->table . 'product_category_foodwine b WHERE a.StoreID = ' . $StoreID . ' AND a.category=b.id AND a.deleted=0 AND a.`type` IN ' . $in . $where . ' ORDER BY ' . $order;
        
       // $wheresql = ' FROM ' . $this->table . 'product_foodwine a,' . $this->table . 'product_category_foodwine b WHERE a.StoreID = ' . $StoreID . ' AND a.category=b.id AND a.deleted=0 '. $where . ' ORDER BY ' . $order;
        if ($_GET['cp'] == 'list' && 'food' == $type) {
            $cquery = "SELECT COUNT(*) \n" . $wheresql;
            $this->dbcon->execute_query($cquery);
            $total = $this->dbcon->fetch_records(true);
            $total = $total[0]['COUNT(*)'];
            $pageSize = 25;
            $clsPage = new Page($total, $pageSize);
            $wheresql .= $clsPage->get_limit();
            $limit = "";
            $linkStr = $clsPage->get_link('index.php?act=product&cp=list&type=' . $_REQUEST['type'] . '&StoreID=' . $_REQUEST['StoreID'] . '&category=' . $_REQUEST['category'], $pageSize);
        }

        $sql = 'SELECT a.* ' . $wheresql . $limit;
		
        $this->dbcon->execute_query($sql);
        $items = $this->dbcon->fetch_records(true);
        $objImage = new uploadImages();
        if ($items) {
            foreach ($items as $k => $dt) {
                $sql = 'SELECT * FROM ' . $this->table . 'image WHERE pid = ' . $dt['pid'] . ' AND picture!="/images/700x525.jpg" AND picture!="" AND StoreID=' . $StoreID . ' ORDER BY attrib ASC, sort ASC';
                $this->dbcon->execute_query($sql);
                $tmp = $this->dbcon->fetch_records(true);
                $items[$k]['big_image'] = $tmp[0]['picture'];
                $items[$k]['small_image'] = $tmp[0]['smallPicture'];
                $items[$k]['limage'] = $objImage->getDefaultImage($items[$k]['small_image'], false, 0, 0, 16);
                $items[$k]['bimage'] = $objImage->getDefaultImage($items[$k]['small_image'], false, 0, 0, 9);
            }
            $data = array('items' => $items, 'total' => $total, 'linkStr' => $linkStr);
        } else {
            $data = array('items' => array(), 'total' => 0);
        }

        return $data;
    }

    function getProductByCategory($StoreID, $type='wine', $sub_type='', $isfeatured=false, $tmpl='') {

        if ('food' == $type) {
            if ($sub_type && in_array($sub_type, array('stock', 'special'))) {
                $in = '("' . $sub_type . '")';
            } else {
                $in = '("stock", "special")';
            }
        } else {
            $in = '("wine")';
        }
        if ($isfeatured) {
            //$where .= " AND isfeatured=".intval($isfeatured);
            $limit = ' Limit 4';
        }

        $sql = 'SELECT DISTINCT(p.category) AS cid, c.category_name FROM ' . $this->table . 'product_foodwine p, ' . $this->table . 'product_category_foodwine c  WHERE p.category=c.id AND p.StoreID = ' . $StoreID . ' AND p.deleted=0 AND p.`type` IN ' . $in . $where . ' ORDER BY p.isfeatured DESC, c.order ASC, p.pid DESC ' . $limit;

        $this->dbcon->execute_query($sql);
        $category = $this->dbcon->fetch_records(true);
        if ($category) {
            foreach ($category as $k => $v) {
                $v['products'] = $this->getProductsList($StoreID, $type, $sub_type, $v['cid'], $isfeatured, $tmpl);
                $v['flag'] = $k % 2;
                $category[$k] = $v;
            }
        } else {
            $category = array();
        }

        return $category;
    }

    public function getProductInfo($StoreID, $pid) {
        $sql = 'SELECT * FROM ' . $this->table . 'product_foodwine WHERE deleted=0 AND StoreID=' . $StoreID . ' AND pid=' . $pid;
        $this->dbcon->execute_query($sql);
        $rs = $this->dbcon->fetch_records(true);
        $rs = $rs[0];
        //image
        $sql = 'SELECT * FROM ' . $this->table . 'image WHERE pid = ' . $pid . ' AND StoreID=' . $StoreID;
        $this->dbcon->execute_query($sql);
        $tmp = $this->dbcon->fetch_records(true);
        $images = array();
        if ($tmp) {
            foreach ($tmp as $k => $v) {
                if (0 == $v['attrib']) {
                    $images['big'] = $v;
                } else {
                    $images['small'][$v['sort']] = $v;
                }
            }
        }
        $rs['images'] = $images;
        return $rs;
    }

    public function getRecipeInfo($rid, $StoreID=0, $get_last_one=false, $get_pre_one=false, $get_next_one=false, $nl2br=false) {
        if (empty($rid) && !$get_last_one) {
            return;
        }
        if (empty($StoreID)) {
            $StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID'] : '');
        }

        $sql = 'SELECT * FROM ' . $this->table . 'recipe WHERE StoreID=' . $StoreID . ' AND id=' . $rid;
        if ($get_last_one) {
            $sql = "SELECT * FROM {$this->table}recipe WHERE StoreID='$StoreID' AND deleted=0 ORDER BY id DESC LIMIT 1";
        } elseif ($get_pre_one) {
            $sql = "SELECT * FROM {$this->table}recipe WHERE StoreID='$StoreID' AND deleted=0 AND id>'$rid' ORDER BY id ASC LIMIT 1";
        } elseif ($get_next_one) {
            $sql = "SELECT * FROM {$this->table}recipe WHERE StoreID='$StoreID' AND deleted=0 AND id<'$rid' ORDER BY id DESC LIMIT 1";
        }
        $res = $this->dbcon->getOne($sql);
        if ($res && $nl2br) {
            $res['content'] = nl2br($res['content']);
            $res['method'] = nl2br($res['method']);
        }

        return $res;
    }

    public function getRecipeList($StoreID=0) {
        if (empty($StoreID)) {
            $StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID'] : '');
        }

        $sql = 'SELECT * FROM ' . $this->table . 'recipe WHERE StoreID=' . $StoreID . ' AND deleted=0';
        $this->dbcon->execute_query($sql);
        $res = $this->dbcon->fetch_records(true);

        return $res;
    }

    public function getCategoryInfo($cid, $fid = 2, $StoreID = 0) {
        $sql = "SELECT category_name FROM {$this->table}product_category_foodwine WHERE id='$cid' AND fid='$fid' AND StoreID='$StoreID'";
        $res = $this->dbcon->getOne($sql);

        return $res;
    }

    public function checkCategoryName($name, $fid = 2, $StoreID = 0, $cid = 0) {
        $name = trim($name);
        $where = "WHERE deleted=0 AND category_name='$name' AND fid='$fid' AND StoreID='$StoreID'";
        if ($cid) {
            $where .= " AND id<>'$cid'";
        }
        return $this->dbcon->checkRecordExist($this->table . 'product_category_foodwine', $where);
    }

    public function delRecipe($rid, $StoreID) {
        $res = $this->dbcon->update_record($this->table . 'recipe', array('deleted' => '1'), " WHERE `id`='$rid' AND StoreID='$StoreID'");

        return $res;
    }

    public function addCategory($name, $fid = 2, $StoreID = 0) {
        $name = trim($name);
        $data['category_name'] = $name;
        $data['fid'] = $fid;
        $data['StoreID'] = $StoreID;
        return $this->dbcon->insert_query($this->table . 'product_category_foodwine', $data);
    }

    public function editCategory($cid, $data = array()) {
        $res = false;
        if ($data && is_array($data)) {
            $res = $this->dbcon->update_query($this->table . 'product_category_foodwine', $data, "WHERE id='$cid'");
        }

        return $res;
    }

    public function deleteProductByPID(array $pid) {
        $this->dbcon->update_record($this->table . 'product_foodwine', array('deleted' => '1'), ' WHERE `pid` IN ( ' . implode(',', $pid) . ') AND StoreID = ' . $_SESSION['StoreID']);


        /**
         * added by Kevin.Liu, 2012-02-16
         * reduce point new rule
         */
        include_once(SOC_INCLUDE_PATH . '/class.point.php');
        $objPoint = new Point();
        foreach ($pid as $id) {
            $objPoint->addPointRecords($_SESSION['StoreID'], 'product', $id, true);
        }

        //END
    }

    public function deleteCategoryByCID(array $cid) {
        $this->dbcon->update_record($this->table . 'product_category_foodwine', array('deleted' => '1'), ' WHERE `id` IN ( ' . implode(',', $cid) . ') AND StoreID = ' . $_SESSION['StoreID']);
    }

    /**
     * @title	: searchFoodWine
     * Wed Aug 10 10:45:59 GMT 2011
     * @input	: array searchForm, 
     * @output	: array foodwine list 
     * @description	: 
     * @author	: Keivn <kevin.liu@infinitytesting.com.au>
     * @version	: V1.0
     * 
     */
    function searchFoodWineList($searchForm, $page) {
        # get states list
        $states = getStateArray($searchForm['state_name']);

        $whereSQL = array();
        #  suburb search condition and suburb array
        $suburbArray = explode('.', $searchForm['suburb']);
        $cities = getSuburbArray($searchForm['state_name'] == -1 ? 'NSW' : $searchForm['state_name'], $searchForm['suburb']);
        if ($searchForm['suburb_id']) {
            $cities = getSuburbIdArystr($searchForm['state_name'], $searchForm['suburb_id']);
            $cities && $whereSQL[] = "detail.bu_suburb in(" . getSuburbIdArystr($searchForm['state_name'], $searchForm['suburb_id']) . ") ";
        }

        # distance
        $distance = array(3, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300);

        # generate the radius search sql string
        $postcode = $searchForm['postcode'];
        if ($searchForm['state_name'] != '-1' && $searchForm['distance'] != "") {
            $whereSQL[] = "detail.bu_postcode " . getRadiusSqlString($searchForm['postcode'], $searchForm['distance'], LANGCODE=='en-au'?'AUS':'USA');
        }

        # get state id and add state condition
        $stateInfo = getStateInfoByName($searchForm['state_name']);
        if ($searchForm['state_name'] != '-1') {
            $whereSQL[] = "detail.bu_state = '" . $stateInfo['id'] . "'";
        }

        # category search condition
        if ($searchForm['category'] > 0) {
            $whereSQL[] = "category='" . $searchForm['category'] . "'";
        }
        # property search condition
        if (!empty($searchForm['property'])) {
            $whereSQL[] = "property='" . $searchForm['property'] . "'";
        }

        # price condition and negotiable search
        if ($searchForm['category'] < 4) {
            $price_string = '';
            if ($searchForm['min_price'] > 0 and $searchForm['max_price'] > 0) {
                if ($searchForm['min_price'] <= $searchForm['max_price']) {
                    $price_string = "(price >= '" . $searchForm['min_price'] . "' and price <= '" . $searchForm['max_price'] . "')";
                }
            } elseif ($searchForm['min_price'] > 0) {
                $price_string = "price >= '" . $searchForm['min_price'] . "'";
            } elseif ($searchForm['max_price'] > 0) {
                $price_string = "price <= '" . $searchForm['max_price'] . "'";
            }
            if ($price_string != '') {
                if ($searchForm['negotiable'] == 1) {
                    $whereSQL[] = "(" . $price_string . " or negotiable = 1)";
                } else {
                    $whereSQL[] = $price_string;
                }
            }
        }

        # bedroom condition
        if ($searchForm['bedroom'] > 0) {
            if ($searchForm['bedroom'] == 6) {
                $whereSQL[] = "bedroom>='" . $searchForm['bedroom'] . "'";
            } else {
                $whereSQL[] = "bedroom='" . $searchForm['bedroom'] . "'";
            }
        }

        # bathroom condition
        if ($searchForm['bathroom'] > 0) {
            if ($searchForm['bathroom'] == 6) {
                $whereSQL[] = "bathroom>='" . $searchForm['bathroom'] . "'";
            } else {
                $whereSQL[] = "bathroom='" . $searchForm['bathroom'] . "'";
            }
        }

        # car spaces condition
        if (is_numeric($searchForm['carspace'])) {
            if ($searchForm['carspace'] == 6) {
                $whereSQL[] = "carspaces>='" . $searchForm['carspace'] . "'";
            } else {
                $whereSQL[] = "carspaces='" . $searchForm['carspace'] . "'";
            }
        }

        # keyword and agent name condition
        if (strlen($searchForm['keyword']) > 0) {
            $searchForm['keyword'] = mysql_escape_string($searchForm['keyword']);
            $whereSQL[] = "( item_name like '%" . $searchForm['keyword'] . "%' or detail.bu_name like '%" . $searchForm['keyword'] . "%' or detail.bu_urlstring like '%" . $searchForm['keyword'] . "%')";
        }
        if ($searchForm['bcategory'] > 0) {
            $whereSQL[] = "detail.subAttrib='{$searchForm['bcategory']}'";
        }
        if (strlen($searchForm['agent_name']) > 0) {
            $searchForm['agent_name'] = mysql_escape_string($searchForm['agent_name']);
            $whereSQL[] = "(detail.bu_name like '%" . $searchForm['agent_name'] . "%' or detail.bu_nickname like '%" . $searchForm['agent_name'] . "%')";
        }

        # conditions
        $queryCondition = "FROM " . $this->table . "product_foodwine AS product \n" .
                "LEFT JOIN " . $this->table . "bu_detail AS detail ON detail.StoreID = product.StoreID \n" .
                "LEFT JOIN " . $this->table . "login AS lg ON lg.StoreID = detail.StoreID \n" .
                "LEFT JOIN " . $this->table . "state AS state ON state.id = detail.bu_state \n" .
                "LEFT JOIN " . $this->table . "suburb AS suburb ON suburb.suburb = detail.bu_suburb \n" .
                "LEFT JOIN " . $this->table . "image AS img ON (img.StoreID=product.StoreID and img.pid = product.pid and img.attrib=0 and img.sort=0) \n" .
                "WHERE product.deleted = '' and product.enabled=1 \n" .
                "AND detail.CustomerType = 'seller' AND detail.attribute='5' \n" .
                "AND NOT(detail.bu_name IS NULL) \n" .
                "AND detail.renewalDate >= '" . time() . "' \n" .
                "AND lg.suspend=0 AND detail.status = 1 \n";
        if ($whereSQL) {
            $queryCondition.= "AND " . join(' and ', $whereSQL);
        }

        # get total number
        $query = "SELECT COUNT(*) \n" . $queryCondition;

        $this->dbcon->execute_query($query);
        $total = $this->dbcon->fetch_records(true);
        $total = $total[0]['COUNT(*)'];

        # divide pages
        $clsPage = new pagerClass();
        $pageLink = $clsPage->getLink($page, $total, PAGESIZE, 'page');
        unset($clsPage);

        # search products
        $start = ($page - 1) * PAGESIZE;
        $query = "SELECT product.*, state.description as stateName, suburb.suburb as suburbName, \n" .
                " '' AS flag, '' AS website_name, \n" .
                "detail.bu_name,detail.bu_urlstring, img.smallPicture,img.picture  \n" .
                "$queryCondition \n" .
                "ORDER BY product.datec DESC limit $start," . PAGESIZE;
        //echo "<pre>$query</pre>";
        $this->dbcon->execute_query($query);
        $searchResult = $this->dbcon->fetch_records(true);

        $tmpName = '';
        $objImage = new uploadImages();
        for ($i = 0; $i < PAGESIZE && $i < $total - $start; $i++) {
            if ($i > 0) {
                if ($searchResult[$i]['name'] == $tmpName) {
                    $searchResult[$i]['flag'] = 0;
                } else {
                    $searchResult[$i]['flag'] = 1;
                    $tmpName = $searchResult[$i]['name'];
                }
            } else {
                $searchResult[$i]['flag'] = 1;
                $tmpName = $searchResult[$i]['name'];
            }
            $searchResult[$i]['simage'] = $objImage->getDefaultImage($searchResult[$i]['smallPicture'], true, 0, 0, 4);
            $searchResult[$i]['bimage'] = $objImage->getDefaultImage($searchResult[$i]['picture'], false, 0, 0, 9);
            $searchResult[$i]['limage'] = $objImage->getDefaultImage($searchResult[$i]['picture'], false, 0, 0, 15);
            $searchResult[$i]['website_name'] = clean_url_name($searchResult[$i]['bu_urlstring']);
            //echo "name: ".$searchResult[$i]['bu_name']."; url:".$searchResult[$i]['website_name']."\n<br>";
            //$searchResult[$i]['description'] = strip_tags($searchResult[$i]['content']);
        }

        //echo $query;
        //var_dump($searchResult);
        return array
            (
            'states' => $states,
            'cities' => $cities,
            'distance' => $distance,
            'counter' => $total,
            'products' => $searchResult,
            'linkStr' => $pageLink['linksAllFront'],
            'page' => 'state'
        );
    }

    /**
     * @title	: searchFoodWine Store By Category
     * Wed Aug 12 10:45:59 GMT 2011
     * @input	: array searchForm, 
     * @output	: array foodwine store list 
     * @description	: 
     * @author	: Keivn <kevin.liu@infinitytesting.com.au>
     * @version	: V1.0
     * 
     */
    function searchStoreList($searchForm, $page) {
        include_once(SOC_INCLUDE_PATH . '/class.uploadImages.php');

        # get states list
        $states = getStateArray($searchForm['state_name']);

        $whereSQL = array();
        #  suburb search condition and suburb array
        $suburbArray = explode('.', $searchForm['suburb']);
        $cities = getSuburbArray($searchForm['state_name'] == -1 ? 'NSW' : $searchForm['state_name'], $searchForm['suburb']);
        if ($searchForm['suburb_id'] && $searchForm['distance'] == "") {
            $whereSQL[] = "detail.bu_suburb ='" . $searchForm['suburb'] . "'";
        }

        # distance
        $distance = array(3, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300);

        # generate the radius search sql string
//		$postcode = $searchForm['postcode'];
        $postcode = getPostcodeSubburb($searchForm['suburb'], $searchForm['state_name']);
        if ($searchForm['state_name'] != '-1' && $searchForm['distance'] != "") {
            $radius_sql_str = getRadiusSqlString($postcode, $searchForm['distance'], LANGCODE=='en-au'?'AUS':'USA', 0);

            $whereSQL[] = empty($radius_sql_str) ? "0" : "detail.bu_postcode " . $radius_sql_str;
        }

        # get state id and add state condition
        $stateInfo = getStateInfoByName($searchForm['state_name']);
        if ($searchForm['state_name'] != '-1') {
            $whereSQL[] = "detail.bu_state = '" . $stateInfo['id'] . "'";
        }

        # postcode search condition
        if (!empty($searchForm['bu_postcode'])) {
            $whereSQL[] = "detail.bu_postcode='" . $searchForm['bu_postcode'] . "'";
        }

        # category search condition
        if ($searchForm['category'] > 0) {
            $whereSQL[] = "category='" . $searchForm['category'] . "'";
        }
        # property search condition
        if (!empty($searchForm['property'])) {
            $whereSQL[] = "property='" . $searchForm['property'] . "'";
        }

        # price condition and negotiable search
        if ($searchForm['category'] < 4) {
            $price_string = '';
            if ($searchForm['min_price'] > 0 and $searchForm['max_price'] > 0) {
                if ($searchForm['min_price'] <= $searchForm['max_price']) {
                    $price_string = "(price >= '" . $searchForm['min_price'] . "' and price <= '" . $searchForm['max_price'] . "')";
                }
            } elseif ($searchForm['min_price'] > 0) {
                $price_string = "price >= '" . $searchForm['min_price'] . "'";
            } elseif ($searchForm['max_price'] > 0) {
                $price_string = "price <= '" . $searchForm['max_price'] . "'";
            }
            if ($price_string != '') {
                if ($searchForm['negotiable'] == 1) {
                    $whereSQL[] = "(" . $price_string . " or negotiable = 1)";
                } else {
                    $whereSQL[] = $price_string;
                }
            }
        }

        # keyword and agent name condition
        if (strlen($searchForm['keyword']) > 0) {
            $searchForm['keyword'] = mysql_escape_string($searchForm['keyword']);
            $whereSQL[] = "( product.item_name like '%" . $searchForm['keyword'] . "%' or detail.bu_name like '%" . $searchForm['keyword'] . "%' or detail.bu_urlstring like '%" . $searchForm['keyword'] . "%')";
        }
        if ($searchForm['bcategory'] > 0) {
            $whereSQL[] = "detail.subAttrib='{$searchForm['bcategory']}'";
            if ($searchForm['bcategory'] == 1 && $searchForm['cuisine']) {
                $whereSQL[] = "detail.bu_cuisine='{$searchForm['cuisine']}'";
            }
        }
        if (strlen($searchForm['agent_name']) > 0) {
            $searchForm['agent_name'] = mysql_escape_string($searchForm['agent_name']);
            $whereSQL[] = "(detail.bu_name like '%" . $searchForm['agent_name'] . "%' or detail.bu_nickname like '%" . $searchForm['agent_name'] . "%')";
        }

        //Order By
        $order = 'detail.bu_name ASC';
        switch ($searchForm['sort']) {
            case 1:
                $order = 'detail.PayDate DESC';
            case 2:
                $order = 'product.price ASC';
                break;
            case 3:
                $order = 'product.price DESC';
                break;
            case 4:
                $order = 'product.item_name ASC';
                break;
                break;
            case 5:
                $order = 'detail.bu_name ASC';
                break;
            default:
                break;
        }

        # conditions
        $queryCondition = "FROM " . $this->table . "bu_detail AS detail \n" .
                "LEFT JOIN " . $this->table . "product_foodwine AS product ON detail.StoreID = product.StoreID \n" .
                "LEFT JOIN " . $this->table . "login as lg on lg.StoreID = detail.StoreID " .
                "LEFT JOIN " . $this->table . "state AS state ON state.id = detail.bu_state \n" .
                "WHERE (detail.CustomerType = 'seller' OR detail.CustomerType = 'listing') AND detail.attribute='5' \n" .
                "AND NOT(detail.bu_name IS NULL) \n" .
                "AND IF(detail.is_popularize_store=0, lg.suspend=0, 1) \n" .
                "AND (detail.renewalDate >= '" . time() . "' OR `is_popularize_store` = 1) \n" .
                "AND detail.status = 1 \n";
        if ($whereSQL) {
            $queryCondition.= "AND " . join(' and ', $whereSQL);
        }
        
        

        # get total number
        $sql = "SELECT COUNT(DISTINCT(detail.StoreID)) AS num \n" . $queryCondition;
        $xtotal = $this->dbcon->getOne($sql);
        $total = $xtotal['num'];
		

        # divide pages
        $clsPage = new pagerClass();
        $pageLink = $clsPage->getLink($page, $total, PAGESIZE, 'page');
        unset($clsPage);

        # search products
        $start = ($page - 1) * PAGESIZE;
        $query = "SELECT DISTINCT(detail.bu_name) AS bu_name,state.stateName, detail.bu_suburb as suburbName, \n" .
                " '' AS flag, '' AS website_name, \n" .
                "detail.StoreID,detail.bu_urlstring,detail.subAttrib,detail.is_popularize_store,detail.bu_website \n" .
                "$queryCondition \n" .
                "ORDER BY detail.is_popularize_store ASC, $order, detail.StoreID DESC limit $start," . PAGESIZE;
//echo "<pre>$query</pre>";

        
        $this->dbcon->execute_query($query);
        $searchResult = $this->dbcon->fetch_records(true);

        $objUI = new uploadImages();
        $tmp = $res = $res_not_popularize = $res_popularize = array();
        $not_popularize_counter = $popularize_counter = 0;
		
		$default_store_images = array(
			1 => 'restaurants.jpg', // Restaurents
			2 => 'liquorstores.jpg', // Liquor 
			3 => 'bakerygrocery.jpg', // Bakery 
			4 => 'seafood.jpg', // Seafood
			5 => 'meatdeli.jpg', // Meat
			6 => 'fruitsvegetables.jpg', // Fruitveg
			7 => 'pubsbars.jpg', // Bar, pubs
			8 => 'fastfood.jpg', // Fast food
			9 => 'cafes.jpg', // Cafe
			10 => 'juicebars.jpg' // Juice
		);
		
        //group the popularize store by subAttrib
        if ($searchResult) {
            foreach ($searchResult as $val) {
                $val['images'] = $objUI->getDisplayImage('', $val['StoreID'], 0, -1, -1, 6);
                $image2 = $val['images']['mainImage'][2]['bname']['text'];
                $val['images']['mainImage'][2]['bname']['text'] = $image2 == '/images/243x100.jpg' ? '/images/79x79.jpg' : $image2;
                $val['store_logo'] = $objUI->getDefaultImage($val['images']['mainImage'][2]['bname']['text'], true, 6, 4, 15);
                $val['store_logo_big'] = $objUI->getDefaultImage($val['images']['mainImage'][2]['bname']['text'], true, 6, 4, 9);
                $val['store_search_result_logo'] = $objUI->getDefaultImage($val['images']['mainImage'][5]['bname']['text'], true, 6, 4, 15);
                $val['store_search_result_logo_big'] = $objUI->getDefaultImage($val['images']['mainImage'][5]['bname']['text'], true, 6, 4, 9);
                $val['website_name'] = clean_url_name($val['bu_urlstring']);
				$val['website_url'] = $val['bu_website'];
				$val['default_store_image'] = IMAGES_URL.'/skin/red/foodwine/category_icon/default/'.$default_store_images[$val['subAttrib']];
                if ($val['is_popularize_store']) {
                    $tmp[$val['subAttrib']][] = $val;
                    $popularize_counter++;
                } else {
                    $not_popularize_counter++;
                    $res_not_popularize[] = $val;
                }
            }
        }

        //get the popularize store
        $lang = &$GLOBALS['_LANG']['seller']['attribute'][5]['subattrib'];
        foreach ($tmp as $key => $val) {
            $category['category_name'] = $lang[$key];
            $category['cid'] = $key;
            $category['items'] = $val;
            $res_popularize[] = $category;
        }
        usort($res_popularize, array(&$this, 'cmp_catid'));

        $res['popularize'] = $res_popularize;
        $res['not_popularize'] = $res_not_popularize;

        return array
            (
            'states' => $states,
            'cities' => $cities,
            'distance' => $distance,
            'counter' => $total,
            'popularize_counter' => $popularize_counter,
            'not_popularize_counter' => $not_popularize_counter,
            'popularize_category_counter' => count($res_popularize),
            'stores' => $res,
            'linkStr' => $pageLink['linksAllFront'],
            'page' => 'foodwine'
        );
    }

    /**
     * @author  Kevin.Liu, 2011-08-19
     * @Edited  Albert Clyde Osmena, 2012-09-09
     * @param
     *
     */
    public function sendEmailAlert($StoreID, $send_type='specials', $pid_ary=array(), $eid) {
        if (empty($StoreID)) {
            return 'StoreID is required.';
        }
        $send_type = strtolower($send_type);
        if (!in_array($send_type, array('specials', 'hotbuy'))) {
            return 'Error Type.';
        }

        //query for email alert user
        $sql = "SELECT e.*,l.user FROM  " . $this->table . "emailalert e," . $this->table . "login l WHERE e.userid=l.id AND e.StoreID = '$StoreID'";
        $this->dbcon->execute_query($sql);
        $subscribers = $this->dbcon->fetch_records();
        $sub_num = count($subscribers);

        //query for guest email alerts
        include_once(SOC_INCLUDE_PATH . '/class.guestEmailSubscriber.php');
        $guestSub = new guestEmailSubscriber();
        $subscribersGuests = $guestSub->getGuestSubscriberListByStore($StoreID);
        $sub_num = $sub_num + count($subscribersGuests);

        $emailalerts_data = $this->getEmailAlertsInfo($eid, $StoreID);
        include_once(SOC_INCLUDE_PATH . '/class.emailClass.php');

        $socObj = new socClass();
        $params = $socObj->displayStoreWebside(false, true);
        //$params['To'] = 'kevin.liu@infinitytesting.com.au';
        $params['Subject'] = $send_type == 'specials' ? "Specials from {$params['info']['bu_name']}" : "Hot Buy from {$params['info']['bu_name']}";
        $params['_no_tpl_'] = true;
        $params['header_img'] = $this->getEmailAlertsHeaderImg($StoreID, $send_type);
        $params['attachment'] = '';
        $params['bgcolor'] = $send_type == 'specials' ? '#d8d9db' : '#f5f5f5';
        $params['products'] = $this->getProductsList($StoreID, '', '', '', false, '', $pid_ary);
		
		
		$params['hotbuy_message'] = $emailalerts_data['hotbuy_message'];
        $params['info']['send_type'] = $send_type;
        $params['info']['start_date'] = date('d F Y', $emailalerts_data['start_date']);
        $params['info']['end_date'] = date('d F Y', $emailalerts_data['end_date']);

        if ($subscribers) {

            foreach ($subscribers as $subscriber) {
                $params['To'] = $subscriber['user'];
                $emailObj = new emailClass();
                $res = $emailObj->send($params, "email_alert_content.tpl", true, false);
                $emailObj->__destruct();
                unset($emailObj);
            }
            //$msg = $emailObj->msg && !$res? $emailObj->msg : "You have sent your items to " . $sub_num ." subscribers.";
        }

        if ($subscribersGuests) {
            foreach ($subscribersGuests as $subscribersGuests) {
                $params['To'] = $subscribersGuests['email'];
                $emailObj = new emailClass();
                $res = $emailObj->send($params, "email_alert_content.tpl", true, false);
                $emailObj->__destruct();
                unset($emailObj);
            }
            //$msg = $emailObj->msg && !$res? $emailObj->msg : "You have sent your items to " . $sub_num ." subscribers.";
        }

        $msg = $emailObj->msg && !$res ? $emailObj->msg : "You have sent your items to " . $sub_num . " subscribers.";

        if (!$subscribers && !$subscribersGuests) {
            $msg = 'Your have not subscribers.';
        }

        return $msg;

        /* $this->smarty->assign('req', $params);
          $this->smarty->assign('products', $params);
          $html = $this->smarty->fetch("email_alert_{$send_type}.tpl");
          echo $html;
          exit();

          return $res; */
    }

    function getEmailAlertsList($StoreID) {
        $StoreID = $StoreID ? $StoreID : $_SESSION['StoreID'];
        //query for email alert user
        $sql = "SELECT * FROM  {$this->table}emailalerts_detail_foodwine WHERE StoreID = '" . $StoreID . "' ORDER BY id DESC";
        $this->dbcon->execute_query($sql);

        return $this->dbcon->fetch_records(true);
    }

    function getEmailAlertsHeaderImg($StoreID, $send_type) {
        $send_type = strtolower($send_type);
        if (!in_array($send_type, array('specials', 'hotbuy'))) {
            return 'Error Type.';
        }
        $tpl_ary = array(
            'specials' => array(
                'lady_with_the_shopping_bag' => array('2', '3', '4', '5', '6'),
                'people_with_the_chef_waitress' => array('1', '7', '8', '9', '10')
            ),
            'hotbuy' => array(
                'people_yelling_out' => array('1', '7', '8', '9', '10'),
                'guy_holding_the_fish' => array('4'),
                'two_kids_with_the_burgers' => array('5'),
                'lady_with_the_shopping_bag' => array('2', '3', '6')
            )
        );
        $socObj = new socClass();
        $store_info = $socObj->getStoreInfo($StoreID);
        $subAttrib = $store_info['subAttrib'];
        $sent_type_tpl_ary = $tpl_ary[$send_type];
        $header_img = $send_type;
        foreach ($sent_type_tpl_ary as $key => $val) {
            if (in_array($subAttrib, $val)) {
                $header_img .= '_' . $key;
                break;
            }
        }

        return $header_img;
    }

    function getOrderNum($StoreID, $reviewed=0, $status=1) {
        $condition = '';
        if ($status) {
            $condition .= " AND status='$status'";
        }
        if ($reviewed != '') {
            $condition .= " AND seller_reviewed='$reviewed'";
        }
        $sql = 'SELECT COUNT(OrderID) AS total FROM ' . $this->table . 'order_foodwine WHERE StoreID = ' . $StoreID . $condition;
        $res = $this->dbcon->getOne($sql);

        return $res['total'] ? $res['total'] : 0;
    }

    function getBookNum($StoreID, $status='') {
        $condition = '';
        if ($status != '') {
            $condition .= " AND status='$status'";
        }
        $sql = 'SELECT COUNT(id) AS total FROM ' . $this->table . 'book WHERE deleted=0 AND StoreID = ' . $StoreID . $condition;
        $res = $this->dbcon->getOne($sql);

        return $res['total'] ? $res['total'] : 0;
    }

    function getSubscribersNum($StoreID) {
        $sql = 'SELECT COUNT(id) AS total FROM ' . $this->table . 'emailalert WHERE StoreID = ' . $StoreID;
        $res = $this->dbcon->getOne($sql);

        return $res['total'] ? $res['total'] : 0;
    }

    /**
     * @author  Kevin.Liu, 2011-08-29
     * @param
     *
     */
    public function getEmailAlertsHtml($StoreID, $send_type='special', $pid_ary=array(), $eid=0) {
        if (empty($StoreID)) {
            return 'StoreID is required.';
        }
        if (empty($pid_ary) || !is_array($pid_ary)) {
            return 'Items is required.';
        }

        $socObj = new socClass();
        $req = $socObj->displayStoreWebside(false, true);
        $req['products'] = $this->getProductsList($StoreID, '', '', '', false, '', $pid_ary);
        $req['info']['id'] = $eid;
        $this->smarty->assign('req', $req);
        //$html = $this->smarty->fetch("email_alert_{$send_type}.tpl");
        $html = $this->smarty->fetch("email_alert_preview.tpl");

        return $html;
    }

    function cmp_catid($a, $b) {
        if ($a['cid'] == $b['cid']) {
            return 0;
        }
        return $a['cid'] > $b['cid'] ? 1 : -1;
    }

    function getStoreDeliveryMethod($StoreID) {
        $sql = "SELECT * FROM " . $this->table . "bu_detail WHERE StoreID='$StoreID' order by StoreID asc";
        $arrResult['select'] = $this->dbcon->getOne($sql);

        $arrResult['select']['deliveryMethod'] = explode('|', $arrResult['select']['deliveryMethod']);
        $arrResult['select']['postage'] = explode('|', $arrResult['select']['postage']);
        $arrResult['select']['oversea_deliveryMethod'] = explode('|', $arrResult['select']['oversea_deliveryMethod']);
        $arrResult['select']['oversea_postage'] = explode('|', $arrResult['select']['oversea_postage']);

        if (is_array($this->lang['Delivery'])) {
            $deliveryMethod .= "<table style=\"background:#F1F1F1\" cellpadding=4>";
            $oversea_deliveryMethod .= "<table style=\"background:#F1F1F1\" cellpadding=4>";
            foreach ($this->lang['Delivery'] as $key => $value) {
                $checked = in_array($key, $arrResult['select']['deliveryMethod']) ? " checked='checked'" : "";
                $pos_key = array_search($key, $arrResult['select']['deliveryMethod']);
                $pos_value = $checked != "" && isset($arrResult['select']['postage'][$pos_key]) ? $arrResult['select']['postage'][$pos_key] : "";

                $deliveryMethod .= "<tr><td>" .
                        "<input type='checkbox' class='ck_deliveryMethod' name='deliveryMethod[$key]' " .
                        "$checked value='{$key}' title='{$value['text']}' " .
                        "onclick='enableCost(\"postage[$key]\",this);' />&nbsp;" . ($key == '5' ? '<strong>' : '') . "{$value['text']} " . ($key == '5' ? '</strong>' : '') . ($key == '5' ? $codHelp_one : '') . "</td>" .
                        "<td valign='middle'>&nbsp;&nbsp;";
                if ($key == '1' or $key == '2' or $key == '5' or 6 == $key) {
                    $deliveryMethod.= "$</td><td><input type='text' class='inputB input_postage' name='postage[$key]' " .
                            ($checked == "" ? "disabled" : "") . " value='$pos_value' style='width:40px;margin-right:5px;'>";
                } else {
                    $deliveryMethod.= "</td><td style='height:22px;'><input type='hidden' class='inputB input_postage' name='postage[$key]' " .
                            ($checked == "" ? "disabled" : "") . " value='0' style='width:40px;margin-right:5px;'>";
                }
                $deliveryMethod.= "</td></tr>";

                $checked_over = in_array($key, $arrResult['select']['oversea_deliveryMethod']) ? " checked='checked'" : "";
                $pos_over_key = array_search($key, $arrResult['select']['oversea_deliveryMethod']);
                $pos_over_value = $checked_over != "" && isset($arrResult['select']['oversea_postage'][$pos_over_key]) ? $arrResult['select']['oversea_postage'][$pos_over_key] : "";

                $oversea_deliveryMethod .= "<tr><td>" .
                        "<input type='checkbox' class='ck_oversea_deliveryMethod' " .
                        "name='oversea_deliveryMethod[$key]' $checked_over value='{$key}' " .
                        "title='{$value['text']}' onclick='enableCost(\"oversea_postage[$key]\",this);' " .
                        "/>&nbsp;" . ($key == '5' ? '<strong>' : '') . "{$value['text']} " . ($key == '5' ? '</strong>' : '') . ($key == '5' ? $codHelp_two : '') . "</td><td valign='middle'>&nbsp;&nbsp;";
                if ($key == '1' or $key == '2' or $key == '5' or 6 == $key) {
                    $oversea_deliveryMethod.= "$</td><td><input type='text' class='inputB "
                            . "input_oversea_postage' name='oversea_postage[$key]' "
                            . ($checked_over == "" ? "disabled" : "") . " value='$pos_over_value' "
                            . "style='width:40px;margin-right:5px;'></td></tr>";
                } else {
                    $oversea_deliveryMethod.= "</td><td style='height:22px;'><input type='hidden' class='inputB "
                            . "input_oversea_postage' name='oversea_postage[$key]' "
                            . ($checked_over == "" ? "disabled" : "") . " value='0' "
                            . "style='width:40px;margin-right:5px;'></td></tr>";
                }
            }
            $oversea_deliveryMethod .= "</table>";
            $deliveryMethod.= "</table>";
        }
        $arrResult['select']['deliveryMethod'] = $deliveryMethod;
        $arrResult['select']['oversea_deliveryMethod'] = $oversea_deliveryMethod;

        return $arrResult['select'];
    }

    /**
     * generate the array for payment method selection
     * @param 
     * @return array of variable
     */
    function selectPayment() {
        $arrResult = array();

        $arrResult['StoreID'] = $_REQUEST['StoreID'];
        // check seller's paypal account
        $sql = "select * from " . $this->table . "bu_detail d where StoreID='$arrResult[StoreID]'";
        $info = $this->dbcon->getOne($sql);
        if (is_array($info)) {
            $arrResult = $info;
            $arrResult['ref_id'] = $_REQUEST['refid'];
            $arrResult['isattachment'] = 0;
            /* $arrResult['image_name']=$arrResult['picture'];
              if (!file_exists('./'.$arrResult['image_name'])){
              $arrResult['image_name'] = 'images/243x212.jpg';
              } */
            $arrResult['item_name'] = $info['item_name'];
            $arrResult['deliveryMethod'] = $info['deliveryMethod'];
            $arrResult['stockQuantity'] = $info['stockQuantity'];
            $postage = explode('|', $info['postage']);
            $arrResult['total'] = $info['price'] + $postage[0];
            $sql = "select bu_paypal, google_merchantid, google_merchantkey from " . $this->table . "bu_detail where StoreID='" . $arrResult['StoreID'] . "'";
            $this->dbcon->execute_query($sql);
            $paymentInfo = $this->dbcon->fetch_records();
            $bu_paypal = $paymentInfo[0]['bu_paypal'];
            $google_merchantid = $paymentInfo[0]['google_merchantid'];
            $google_merchantkey = $paymentInfo[0]['google_merchantkey'];

            $payments = unserialize($info['payments']);

            $arrResult['credit_card'] = 'disabled';
            $arrResult['check_payment'] = 'disabled';
            $arrResult['paypal_enable'] = 'disabled';
            $arrResult['cash_payment'] = 'disabled';
            $arrResult['bank_transfer'] = 'disabled';
            $arrResult['other_payment'] = 'disabled';
            $arrResult['googlecheckout'] = 'disabled';
            $arrResult['cod'] = 'disabled';
            $arrResult['cash_on_pickup'] = 'disabled';
            $arrResult['eftpos'] = 'disabled';

            if (is_array($payments)) {
                foreach ($payments as $val) {
                    if ($val == '1') {
                        $arrResult['cash_payment'] = '';
                    } elseif ($val == '2') {
                        $arrResult['credit_card'] = '';
                    } elseif ($val == '4') {
                        $arrResult['check_payment'] = '';
                    } elseif ($val == '3' && !empty($bu_paypal)) {
                        $arrResult['paypal_enable'] = '';
                    } elseif ($val == '5') {
                        $arrResult['bank_transfer'] = '';
                        $arrResult['isbtinfo'] = 1;
                        $arrResult['btinfo'] = array(
                            'bt_account_name' => $arrResult['bt_account_name'],
                            'bt_BSB' => $arrResult['bt_BSB'],
                            'bt_account_num' => $arrResult['bt_account_num'],
                            'bt_instruction' => $arrResult['bt_instruction'],
                        );

                        $seller_bt_name = $seller[0]['bt_account_name'];
                        $seller_bsb = $seller[0]['bt_BSB'];
                        $seller_act_num = $seller[0]['bt_account_num'];
                        $seller_btinstruct = $seller[0]['bt_instruction'];
                        $arrParams['bt_name'] = $seller_bt_name;
                        $arrParams['bsb'] = $seller_bsb;
                        $arrParams['act_num'] = $seller_act_num;
                        $arrParams['btinstruct'] = $seller_btinstruct;
                    } elseif ($val == '6') {
                        $arrResult['other_payment'] = '';
                    } elseif ($val == '7' && !empty($google_merchantid) && !empty($google_merchantkey)) {
                        $arrResult['googlecheckout'] = '';
                    } elseif ($val == '9') {
                        $arrResult['cod'] = '';
                    } elseif ($val == '10') {
                        $arrResult['cash_on_pickup'] = '';
                    } elseif ($val == '11') {
                        $arrResult['eftpos'] = '';
                    }
                }
            }
            $arrResult['paypal'] = $bu_paypal;
            //echo "refid:".$arrResult['ref_id'];
            if ($arrResult['ref_id']) {
                $postage = explode('|', $info['postage']);
                $sql = "select price from " . $this->table . "order_reviewref where ref_id=" . $arrResult['ref_id'];
                $this->dbcon->execute_query($sql);
                $info = $this->dbcon->fetch_records();
                $arrResult['price'] = $info['price'];
                $arrResult['total'] = $info['price'] + $postage[0];
                $arrResult['isbid'] = "1";
            }
        } else {
            $arrResult['display'] = 'error';
            $arrResult['msg'] = $this->replaceLangVar($this->lang['pub_clew']['notexist'], array('Product'));
        }
        return $arrResult;
    }

    function saveAnnouncement($StoreID, $title, $content = "", $aid=0) {
        if (empty($StoreID)) {
            return false;
        }
        $content = $content ? $this->wysiwyg_filter_html($content) : "";
        $now = time();
        if ($aid) {
            $aid = intval($aid);
            $sql = "UPDATE {$this->table}announcement SET `title`='$title', `content`='$content', `datem`='$now' WHERE id='$aid'";
        } else {
            $sql = "INSERT INTO {$this->table}announcement(`StoreID`, `title`, `content`, `datec`, `datem`) VALUES ('$StoreID', '$title', '$content', '$now', '$now')";
        }

        return $this->dbcon->execute_query($sql);
    }

    function getAnnouncementInfo($StoreID=0, $aid=0) {
        if (empty($StoreID)) {
            return '';
        }

        $sql = "SELECT * FROM {$this->table}announcement WHERE id='$aid' OR StoreID='$StoreID'";

        return $this->dbcon->getOne($sql);
    }

    public function getEmailAlertsInfo($eid, $StoreID=0) {
        if (empty($eid)) {
            return;
        }

        if (empty($StoreID)) {
            $StoreID = $_REQUEST['StoreID'] ? $_REQUEST['StoreID'] : ($_SESSION['StoreID'] ? $_SESSION['StoreID'] : '');
        }
        $sql = 'SELECT * FROM ' . $this->table . 'emailalerts_detail_foodwine WHERE StoreID=' . $StoreID . ' AND id=' . $eid;
        $res = $this->dbcon->getOne($sql);
        $res['pid_ary'] = $res['products'] ? explode(',', $res['products']) : '';

        return $res;
    }

    function getBookOnlineList($StoreID, $isnew=true, $page, $all=false) {
        include_once(SOC_INCLUDE_PATH . '/class/pagerclass.php');

        $where = "deleted=0";
        if ($StoreID) {
            $where .= " AND StoreID='$StoreID'";
        }
        if ($isnew || !$all) {
            $status = intval(!$isnew);
            $where .= " AND status='$status'";
        }

        # get total number
        $sql = "SELECT COUNT(id) AS num FROM {$this->table}book WHERE " . $where;
        $total = $this->dbcon->getOne($sql);
        $total = $total['num'];

        # divide pages
        $clsPage = new pagerClass();
        $pageLink = $clsPage->getLink($page, $total, PAGESIZE, 'page');
        unset($clsPage);

        # search orders
        $start = ($page - 1) * PAGESIZE;
        $sql = "SELECT * FROM {$this->table}book WHERE $where ORDER BY id DESC LIMIT $start," . PAGESIZE;
        $this->dbcon->execute_query($sql);
        $res = $this->dbcon->fetch_records(true);
        if ($res) {
            foreach ($res as $key => $val) {
                $val['sn'] = str_pad($val['id'], 7, '0', STR_PAD_LEFT);
                $day = $val['reservation_day'] . ' 00:00:00';
                $val['week'] = date('l', strtotime($day));
                $val['month'] = date('M', strtotime($day));
                $val['reservation_date_format'] = date('l / d M Y / ', strtotime($day)) . $val['reservation_hour'] . $val['reservation_am'];
                $res[$key] = $val;
            }
        } else {
            $res = array();
        }

        return array('items' => $res, 'linkStr' => $pageLink['linksAllFront']);
    }

    function deleteBookRequest($bid, $StoreID) {
        return $this->dbcon->update_record($this->table . 'book', array('deleted' => '1'), " WHERE `id`='$bid' AND StoreID = '$StoreID'");
    }

    function getSubscribers($StoreID = 0) {
        $StoreID = $StoreID ? $StoreID : $_SESSION['StoreID'];
        //query for email alert user
        $before_date = strtotime('2011-10-21 00:00:00');
        $sql = "SELECT c.*, a.subscribe_date, " . $before_date . " AS before_date FROM  {$this->table}emailalert a,{$this->table}login b,{$this->table}bu_detail c WHERE a.userid=b.id AND b.StoreID=c.StoreID AND a.StoreID = '" . $StoreID . "' ORDER BY a.id DESC";
        $this->dbcon->execute_query($sql);

        return $this->dbcon->fetch_records(true);
    }

    function getSeasonProduct($search=array()) {
        $pagesize = 18;
        $page = $search['page'] ? $search['page'] : 1;
        $where = ' WHERE 1';
        if ($search['season']) {
            $where .= " AND season_ids LIKE '%{$search['season']}%'";
        }
        if ($search['typeid']) {
            $where .= " AND typeids LIKE '%{$search['typeid']}%'";
        }

        $queryCondition = " FROM  {$this->table}season_product $where";
        if (!$search['all']) {
            # get total number
            $sql = "SELECT COUNT(DISTINCT(pid)) AS num \n" . $queryCondition;
            $total = $this->dbcon->getOne($sql);
            $total = $total['num'];

            # divide pages
            $clsPage = new pagerClass();
            $clsPage->preImg = 'Previous';
            $clsPage->nextImg = 'Next';
            $pageLink = $clsPage->getLink($page, $total, $pagesize, 'page');
            unset($clsPage);

            $start = ($page - 1) * $pagesize;
            $limit = " LIMIT $start, $pagesize";
        }


        $sql = "SELECT * " . $queryCondition . " ORDER BY title ASC $limit";
        $this->dbcon->execute_query($sql);
        $res['list'] = $this->dbcon->fetch_records(true);

        if (!$search['all']) {
            $res['linkStr'] = '<span class="page_left">Displaying ' . count($res['list']) . ' of ' . $total . '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/foodwine/index.php?cp=season&season=' . $search['season'] . '&typeid=' . $search['typeid'] . '&all=1">View All</a></span><span class="page_right">' . $pageLink['linksAllFront'] . '</span>';
        }


        return $res;
    }
  /**
   * secure wysiwyg editor
   * 
   * allow seller editor table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|p|h[1-6]|hr tag
   */
  function wysiwyg_filter_html($text) {
    $allowed_tags = array('em', 'strong', 'ul', 'ol', 'li', 'dl', 'dt', 'dd', 'b', 'u', 'i', 'table', 'thead', 'tbody', 'tfoot', "caption", 'tr', 'td', 'th', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'div', 'p', 'address', 'br');
    $text = $this->_filter_xss($text, $allowed_tags);
    return trim($text);
  }

  function _filter_xss($string, $allowed_tags = array('a', 'em', 'strong', 'cite', 'blockquote', 'code', 'ul', 'ol', 'li', 'dl', 'dt', 'dd')) {
    // Store the text format.
    _filter_xss_split($allowed_tags, TRUE);
    // Remove NULL characters (ignored by some browsers).
    $string = str_replace(chr(0), '', $string);
    // Remove Netscape 4 JS entities.
    $string = preg_replace('%&\s*\{[^}]*(\}\s*;?|$)%', '', $string);

    // Defuse all HTML entities.
    $string = str_replace('&', '&amp;', $string);
    // Change back only well-formed entities in our whitelist:
    // Decimal numeric entities.
    $string = preg_replace('/&amp;#([0-9]+;)/', '&#\1', $string);
    // Hexadecimal numeric entities.
    $string = preg_replace('/&amp;#[Xx]0*((?:[0-9A-Fa-f]{2})+;)/', '&#x\1', $string);
    // Named entities.
    $string = preg_replace('/&amp;([A-Za-z][A-Za-z0-9]*;)/', '&\1', $string);

    return preg_replace_callback('%
    (
    <(?=[^a-zA-Z!/])  # a lone <
    |                 # or
    <!--.*?-->        # a comment
    |                 # or
    <[^>]*(>|$)       # a string that starts with a <, up until the > or the end of the string
    |                 # or
    >                 # just a >
    )%x', '_filter_xss_split', $string);
  }

}


function _filter_xss_split($m, $store = FALSE) {
  static $allowed_html;
  if ($store) {
    $allowed_html = array_flip($m);
    return;
  }
  $string = $m[1];
  if (substr($string, 0, 1) != '<') {
    // We matched a lone ">" character.
    return '&gt;';
  } elseif (strlen($string) == 1) {
    // We matched a lone "<" character.
    return '&lt;';
  }
  if (!preg_match('%^<\s*(/\s*)?([a-zA-Z0-9]+)([^>]*)>?|(<!--.*?-->)$%', $string, $matches)) {
    // Seriously malformed.
    return '';
  }
  $slash = trim($matches[1]);
  $elem = &$matches[2];
  $attrlist = &$matches[3];
  $comment = &$matches[4];
  if ($comment) {
    $elem = '!--';
  }
  if (!isset($allowed_html[strtolower($elem)])) {
    // Disallowed HTML element.
    return '';
  }
  if ($comment) {
    return $comment;
  }
  if ($slash != '') {
    return "</$elem>";
  }
  // Is there a closing XHTML slash at the end of the attributes?
  $attrlist = preg_replace('%(\s?)/\s*$%', '\1', $attrlist, -1, $count);
  $xhtml_slash = $count ? ' /' : '';
  // Clean up attributes.
  $attr2 = implode(' ', _filter_xss_attributes($attrlist));
  $attr2 = preg_replace('/[<>]/', '', $attr2);
  $attr2 = strlen($attr2) ? ' ' . $attr2 : '';
  return "<$elem$attr2$xhtml_slash>";
}

function _filter_xss_attributes($attr) {
  $attrarr = array();
  $mode = 0;
  $attrname = '';
  while (strlen($attr) != 0) {
    // Was the last operation successful?
    $working = 0;
    switch ($mode) {
      case 0:
        // Attribute name, href for instance.
        if (preg_match('/^([-a-zA-Z]+)/', $attr, $match)) {
          $attrname = strtolower($match[1]);
          $skip = ($attrname == 'style' || substr($attrname, 0, 2) == 'on');
          $working = $mode = 1;
          $attr = preg_replace('/^[-a-zA-Z]+/', '', $attr);
        }
        break;
      case 1:
        // Equals sign or valueless ("selected").
        if (preg_match('/^\s*=\s*/', $attr)) {
          $working = 1;
          $mode = 2;
          $attr = preg_replace('/^\s*=\s*/', '', $attr);
          break;
        }
        if (preg_match('/^\s+/', $attr)) {
          $working = 1;
          $mode = 0;
          if (!$skip) {
            $attrarr[] = $attrname;
          }
          $attr = preg_replace('/^\s+/', '', $attr);
        }
        break;
      case 2:
        // Attribute value, a URL after href= for instance.
        if (preg_match('/^"([^"]*)"(\s+|$)/', $attr, $match)) {
          $thisval = _filter_xss_bad_protocol($match[1]);

          if (!$skip) {
            $attrarr[] = "$attrname=\"$thisval\"";
          }
          $working = 1;
          $mode = 0;
          $attr = preg_replace('/^"[^"]*"(\s+|$)/', '', $attr);
          break;
        }
        if (preg_match("/^'([^']*)'(\s+|$)/", $attr, $match)) {
          $thisval = _filter_xss_bad_protocol($match[1]);

          if (!$skip) {
            $attrarr[] = "$attrname='$thisval'";
          }
          $working = 1;
          $mode = 0;
          $attr = preg_replace("/^'[^']*'(\s+|$)/", '', $attr);
          break;
        }
        if (preg_match("%^([^\s\"']+)(\s+|$)%", $attr, $match)) {
          $thisval = _filter_xss_bad_protocol($match[1]);
          if (!$skip) {
            $attrarr[] = "$attrname=\"$thisval\"";
          }
          $working = 1;
          $mode = 0;
          $attr = preg_replace("%^[^\s\"']+(\s+|$)%", '', $attr);
        }
        break;
    }
    if ($working == 0) {
      // Not well formed; remove and try again.
      $attr = preg_replace('/
        ^
        (
        "[^"]*("|$)     # - a string that starts with a double quote, up until the next double quote or the end of the string
        |               # or
        \'[^\']*(\'|$)| # - a string that starts with a quote, up until the next quote or the end of the string
        |               # or
        \S              # - a non-whitespace character
        )*              # any number of the above three
        \s*             # any number of whitespaces
        /x', '', $attr);
      $mode = 0;
    }
  }

  // The attribute list ends with a valueless attribute like "selected".
  if ($mode == 1 && !$skip) {
    $attrarr[] = $attrname;
  }
  return $attrarr;
}

function _filter_xss_bad_protocol($string) {
  return _check_plain(_filter_strip_dangerous_protocols($string));
}

function _filter_strip_dangerous_protocols($uri) {
  static $allowed_protocols;

  if (!isset($allowed_protocols)) {
    $allowed_protocols = array_flip(variable_get('filter_allowed_protocols', array('ftp', 'http', 'https', 'irc', 'mailto', 'news', 'nntp', 'rtsp', 'sftp', 'ssh', 'tel', 'telnet', 'webcal')));
  }

  // Iteratively remove any invalid protocol found.
  do {
    $before = $uri;
    $colonpos = strpos($uri, ':');
    if ($colonpos > 0) {
      // We found a colon, possibly a protocol. Verify.
      $protocol = substr($uri, 0, $colonpos);
      // If a colon is preceded by a slash, question mark or hash, it cannot
      // possibly be part of the URL scheme. This must be a relative URL, which
      // inherits the (safe) protocol of the base document.
      if (preg_match('![/?#]!', $protocol)) {
        break;
      }
      // Check if this is a disallowed protocol. Per RFC2616, section 3.2.3
      // (URI Comparison) scheme comparison must be case-insensitive.
      if (!isset($allowed_protocols[strtolower($protocol)])) {
        $uri = substr($uri, $colonpos + 1);
      }
    }
  } while ($before != $uri);

  return $uri;
}

function _check_plain($text) {
  return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}