<?php
/**
 * Basket
 *
 */

include_once ('functions.php');
class Cart extends common {
	
	var $db;
	var $tablepre = '';
	var $by_session = false;

	function Cart()
	{
        $this -> db 	  			= 	&$GLOBALS['dbcon'];
        $this -> tablepre			= 	&$GLOBALS['table'];
	}
	
	/**
	 * 添加进购物车,成功返0, 出错返回非0,只能添加同一店铺的产品
	 *
	 * @param int $pid			商品ID
	 * @param int $amount		商品数量
	 * @param int $storeid		店铺ID
	 * @param int $type			商品所属店铺类型	
	 * @return int				()
	 */
	function add($pid, $quantity=1, $storeid=0, $type=1)
	{
		$data = array(); 
		
		if ($_SESSION['UserID'] == '' || empty($_SESSION['LOGIN'])) {
			return 4;
		}
		
		$sql = "SELECT * FROM {$this->tablepre}product_foodwine WHERE pid='{$pid}'";
		$goods_info = $this->db->getOne($sql);		
		if(!$goods_info){								//产品信息不存在
			return 1;		
		}		
//		if($goods_info['stock_quantity'] < 1){			//缺货
//			return 2;
//		}
//		if($goods_info['stock_quantity'] < $quantity){	//库存不足
//			return 3;
//		}
		if($goods_info['sale_state '] == 'soon'){		//soon
			return 6;
		}
		
		
		$data['pid'] 		= 	$pid;
		$data['StoreID'] 	= 	$goods_info['StoreID'];
		$data['price'] 		= 	$goods_info['price'];
		$data['unit'] 		= 	$goods_info['unit'];
		$data['quantity'] 	= 	$quantity;
		$data['amount'] 	= 	$quantity * $goods_info['price'];
		$data['cartid'] 	= 	$this->getCurrentCartId(true);
		
		$sql = "SELECT * FROM {$this->tablepre}cartgoods WHERE cartid='{$data['cartid']}' AND pid='{$pid}' ";
		$res = $this->db->getOne($sql);
		if($res){//购物车已存在同一商品
			$sql = "UPDATE {$this->tablepre}cartgoods SET quantity={$quantity},amount=price*quantity WHERE id='{$res['id']}'";
			$this->db->execute_query($sql);		
		} else {
			
			//判断是不是同一店铺的产品
			$sql = "SELECT DISTINCT(StoreID) AS StoreID FROM {$this->tablepre}cartgoods WHERE cartid='{$data['cartid']}' LIMIT 1";
			$tmp = $this->db->getOne($sql);
			if ($tmp && $tmp['StoreID'] != $goods_info['StoreID']) {
				return 5;
			}
			
			//插入新的产品到购物车
			$sql = "INSERT INTO {$this->tablepre}cartgoods(`".implode('`,`',array_keys($data))."`)VALUES('".implode("','",$data)."') ";
			$this->db->execute_query($sql);
		}
		
		//更新商品总价
		$sql = "UPDATE {$this->tablepre}cart SET total_money=(SELECT SUM(amount) FROM {$this->tablepre}cartgoods WHERE cartid='{$data['cartid']}') WHERE id='{$data['cartid']}'";
		$this->db->execute_query($sql);
				
		return 0;
	}
	
	function deleteGoodsById($pid, $cartid)
	{
		$cartid = $cartid ? $cartid : $this->getCurrentCartId();
		
		$sql = "DELETE FROM {$this->tablepre}cartgoods WHERE pid='$pid' AND cartid='$cartid'";
		$status = $this->db->execute_query($sql);
		
		$this->fixCart();
		
		return $status;
	}
	function sessID($new=false)
	{
		if (!$this->by_session) {
			return '';
		}
		$sess_id = $_COOKIE['sess_id'];
		if(!$sess_id || $new) {
			$sess_id = md5(rand());
			setcookie('sess_id', $sess_id);
			$_COOKIE['sess_id'] = $sess_id;
		}
		
		return $sess_id;
	}
	
	//取当前购物车id, 参数$auto_create表明不存在时会自动添加
	function getCurrentCartId($auto_create=false)
	{
		$session_id = $this->sessID();
		$uid  = $_SESSION['UserID'] ? $_SESSION['UserID'] : 0;
		$sql = "SELECT id,session_id,uid FROM {$this->tablepre}cart WHERE uid='{$uid}'  ";
		$res = $this->db->getOne($sql);
		
		if ($res) {
			$cartid = $res['id'];
		} else {
			$timestamp = time();
			//生成新的购物车
			$sql = "INSERT INTO {$this->tablepre}cart(`session_id`,`uid`,`addtime`)VALUES('$session_id','$uid','$timestamp')";
			$this->db->execute_query($sql);
			$cartid = $this->db->lastInsertId();
		}
		
		return $cartid;
	}
	
	//删除
	function delCart($cartid=0)
	{		
		$cartid = $cartid ? $cartid : $this->getCurrentCartId(false);
		$sql = "DELETE FROM {$this->tablepre}cartgoods WHERE cartid='$cartid' ";
		$this->db->execute_query($sql);
		$sql ="DELETE FROM {$this->tablepre}cart WHERE id='$cartid'";
		return $this->db->execute_query($sql);
	}
	function goodsAmount($cartid)
	{
		global $db,$tablepre,$timestamp;
		$sql = "SELECT sum(amount) as a FROM {$tablepre}cartgoods WHERE cartid='$cartid' ";
		$r = $db->getOne($sql);
		if($r) return $r['a'];
		
		return 0;
	}
	//取得购物车的商品
	function getGoods($cartid=0)
	{
		$cartid = $cartid ? $cartid : $this->getCurrentCartId(true);
		
		$sql = "SELECT * FROM {$this->tablepre}cart WHERE id='$cartid'";
		$cart_info = $this->db->getOne($sql);
		if(!$cart_info) return array();
		$uid = $cart_info['uid'];
		
		$foodwine = new FoodWine();
		$sql = "SELECT * FROM {$this->tablepre}cartgoods WHERE cartid='$cartid'";
		$this->db->execute_query($sql);
		$res = $this->db->fetch_records(true);
		
		if(is_array($res)) {
			foreach ($res as $k => $val) {
				$sql = "SELECT * FROM {$this->tablepre}bu_detail WHERE StoreID='{$val['StoreID']}'";
				$arr_temp = $this->db->getOne($sql);
				$tmp = $foodwine->getProductInfo($val['StoreID'], $val['pid']);
				$tmp['store_info'] = $this->formatStoreInfo($arr_temp);
				$val = array_merge($val, $tmp);
				$res[$k] = $val;
			}
		}		
		$cart_info['StoreID'] = $val['StoreID'];		
		$data = array(
					'info'			=> 	$cart_info,
					'can_buy_list'	=>	$res ? $res : array(),
					'total_money'	=>	$cart_info['total_money']
				);
		
		return $data;
			

	}
	
	function changeGoodsAmount($pid, $quantity)
	{
		$quantity = intval($quantity);
		if (empty($pid) || empty($quantity) || $quantity < 1) {
			return false;
		}
		
		//if($this->checkNoStock($pid, $quantity)) return false;
		
		$sql = "UPDATE {$this->tablepre}cartgoods SET quantity={$quantity},amount=quantity*price WHERE pid='$pid'";
		$this->db->execute_query($sql);
		$this->fixCart();
		
		return true;
	}
	
	function formatStoreInfo($info=array()) 
	{
		$data['StoreID']		=	$info['StoreID'];
		$data['bu_name']		=	$info['bu_name'];
		$data['url_bu_name']	=	clean_url_name($info['bu_urlstring']);
		$data['bu_nickname']	=	$info['bu_nickname'];
		$data['bu_address']		=	$info['address_hide'] ? "" : $info['bu_address'];
		$data['bu_suburb']		=	$info['bu_suburb'];
		$data['bu_phone']		=	$info['phone_hide'] || empty($info['bu_phone']) ? "" : $info['bu_area'] ." ". $info['bu_phone'];
		$data['bu_fax']			=	empty($info['bu_fax']) ? "" : $info['bu_area'] ." ". $info['bu_fax'];
		$data['bu_procode']		=	empty($info['bu_procode']) ? "" :$info['bu_procode'];
		$data['bu_urlstring']	=	empty($info['bu_urlstring']) ? "" :$info['bu_urlstring'];
		$data['bu_website_all']	=	$info['bu_website'];
		$data['bu_email']		=	$info['bu_email'];
		$data['bu_paypal']		=	$info['bu_paypal'];
		$data['address_hide']	=	!$info['address_hide'] || empty($info['bu_address']) ? 0 : $info['address_hide'];
		$data['college_hide']	=	!$info['college_hide'] || empty($info['bu_college'])?0:$info['college_hide'];
		$data['phone_hide']		=	$info['phone_hide'];
		$data['description']	=	$info['bu_desc'];

		return $data;
	}

	//通过商品id,购物车id从购物车中取单个商品信息
	function getGoodsById($goodsid,$cartid)
	{
		
		global $db,$tablepre;
		
		//加载价格
		//require_once(DIR_CLASS.'price.inc.php');
		$oPrice = new Price();
		
		//取VIP价格
		$sql = "SELECT u.vip
					FROM {$tablepre}cart AS c ,{$tablepre}user AS u
					WHERE c.id='$cartid' AND c.uid>0 AND u.uid=c.uid ";
		$info = $db->getOne($sql);
		if(!$info) return ;//未登录的不处理
		
		$vip = $info['vip'];
			
		$sql = "SELECT a.id,a.amount,a.goodsid,a.specid,b.name,b.stock,(salestock+IF(stock>jxcstock,jxcstock,stock)) AS onsalestock
				FROM {$tablepre}cartgoods AS a,{$tablepre}goods AS b
				WHERE a.cartid='$cartid'
				AND a.goodsid=b.goodsid
				AND a.goodsid='$goodsid'
				ORDER BY a.id DESC";
		$res = $db->getOne($sql);
		if(!$res) return;
		//取得该规格信息
		if($res['specid']) $specinfo = $db->getOne("SELECT * FROM {$tablepre}goodsspec WHERE goodsid='{$res[goodsid]}' AND id='{$res['specid']}'");

		//存在规格
		if ($specinfo) {
			$specinfo['onsalestock'] = min($specinfo['stock'],$specinfo['jxcstock']) + $specinfo['salestock'];
			$res['tipmsg'] = $specinfo['onsalestock']<$res['amount']?"最多可购".$specinfo['onsalestock']."件":"";
			$res['amount'] = $specinfo['onsalestock']<$res['amount']?$specinfo['onsalestock']:$res['amount'];

			//颜色名称
			if($specinfo['color']) {
				$colorinfo = $db->getOne("SELECT avtext FROM {$tablepre}goodsattribute WHERE goodsid='{$res['goodsid']}' AND avid='{$specinfo['color']}'");
				$res['colortext'] = $colorinfo['avtext'];
			}

			//尺寸
			if ($specinfo['size']) {
				$sizeinfo = $db->getOne("SELECT value as avtext FROM {$tablepre}attributevalue WHERE  avid='{$specinfo['size']}'");
				$res['sizetext'] = $sizeinfo['avtext'];
			}
		} else {

			$res['tipmsg'] = $res['onsalestock']<$res['amount']?"最多可购".$res['onsalestock']."件":"";
			$res['amount'] = $res['onsalestock']<$res['amount']?$res['onsalestock']:$res['amount'];
		}

		$priceinfo = $oPrice->GetGoodsPrice($res['goodsid'],0,$vip);

		$res['markprice'] = $priceinfo['markprice'];
		$res['mallprice'] = $priceinfo['mallprice'];
		$res['points'] = $priceinfo['points']*$res['amount'];
		$res['discount'] = $priceinfo['discount'];
		$res['price'] = $priceinfo['price'];
		$res['money'] = $res['price']*$res['amount'];
		
		return $res;
		
	}
		
	//插入购物车基本信息
	function insertCartInfo($cartid,$ary)
	{
		require_once(DIR_CLASS.'mallbase.inc.php');
		$obj = new MallBase('cart','id');
		
		$obj->edit($obj->filterData($ary),$cartid);
	}
	
	//取购物车基本信息
	function getCartInfo($cartid)
	{
		global $db,$tablepre;
		
		$rs = $db->getOne("SELECT * FROM {$tablepre}cart WHERE id='$cartid'");
		if($rs['orderid']){
			$sql = "SELECT id,type FROM {$tablepre}order WHERE id='{$rs['orderid']}' ";
			$oi =$db->getOne($sql);
			if(!$oi || $oi['type']!=4){
				$rs['orderid']=0;
				$sql = "UPDATE {$tablepre}cart SET orderid=0 WHERE id='$cartid'";
				$db->query($sql);
			}
		}
		return $rs;
	}
	function cleanNotSubmitGoods($cartid)
	{
		global $db,$tablepre;
		
		$rs = $db->getOne("SELECT * FROM {$tablepre}cart WHERE id='$cartid'");
		if($rs['orderid']){
			$sql = "SELECT id,type FROm {$tablepre}order WHERE id='{$rs['orderid']}' ";
			$oi =$db->getOne($sql);
			if($oi && '4'==$oi['type']){
				$sql = "DELETE FROM {$tablepre}ordergoods  WHERE orderid='{$rs['orderid']}'";
				$db->query($sql);
			}
		}
		return $rs;
	}

	
	//检查购物车中商品是否都可以购买
	function checkGoodsCanBy()
	{
		global $db,$tablepre;
		
		require_once SITE_TOP_PATH.'dos/cart.php';
		$doscart = new dosCart();
		
		//取购物车商品列表和总金额                                       
		$cartinfo = $doscart->run(array());
		$good_lists = $cartinfo['list'];
		
		//判断订单商品是否上架和是否有库存
		foreach($good_lists as $ordergoods){
			if ($ordergoods['amount'] < 1 || empty($ordergoods['goodsid'])) {
				return false;
			}
			if($ordergoods['specid']>0){
				$sql = "SELECT id,salestock,stock,jxcstock FROM {$tablepre}goodsspec WHERE `id`='{$ordergoods[specid]}'  AND `isshelf`=1 AND `show`=1";
			}else{
				$sql = "SELECT goodsid,jxcstock,stock,salestock FROM {$tablepre}goods WHERE goodsid='{$ordergoods[goodsid]}' AND isshelf=1 ";
			}

			$tmp =$db->getOne($sql); 
			if(!$tmp || min($tmp['stock'],$tmp['jxcstock'])+$tmp['salestock'] < $ordergoods['amount'] ) {
				return false;
			}
		}
		return true;
		//return $hasstock;
	}
	
	function delNoBuy($cartid) 
	{
		global $db,$tablepre;
		
		$detail = $this->goods($cartid);
		$nobuy_list = $detail['nobuy_list'];
		
		foreach ($nobuy_list as $goods) {
			
			$sql = "DELETE FROM {$tablepre}cartgoods WHERE cartid='$cartid' AND goodsid='$goods[goodsid]'";
			$db->query($sql);
		}
	}
	
	//更新购物车
	function fixCart($cartid=0)
	{
		$uid = $_SESSION['UserID'] ? $_SESSION['UserID'] : 0;
		$cartid = $cartid ? $cartid : $this->getCurrentCartId();
		
		//更新商品总价
		$sql = "UPDATE {$this->tablepre}cart SET uid='$uid', total_money=(SELECT IF(SUM(amount),SUM(amount),0) FROM {$this->tablepre}cartgoods WHERE cartid='{$cartid}') WHERE id='{$cartid}'";
		$this->db->execute_query($sql);
	}
	
	//检查库存
	function checkNoStock($pid, $quantity) {
		$sql = "SELECT * FROM {$this->tablepre}product_foodwine WHERE pid='{$pid}'";
		$goods_info = $this->db->getOne($sql);		
		if(!$goods_info){								//产品信息不存在
			return 1;		
		}		
		if($goods_info['stock_quantity'] < 1){			//缺货
			return 2;
		}
		if($goods_info['stock_quantity'] < $quantity){	//库存不足
			return 3;
		}
		
		return 0;
	}
}

?>