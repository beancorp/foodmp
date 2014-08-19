<?php
/**
 * Thu Sep 25 08:57:50 PDT 2008 08:57:50
 * @author  : Ping.Hu <suppoit@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * option google data feeds
 * ------------------------------------------------------------
 * \include\class.googleDataFeeds.php
 */

class googleDataFeeds extends common {
	/**
	 * @return void 
	 */
	function __construct()
	{
		$this -> dbcon  = &$GLOBALS['dbcon'];
		$this -> table	= &$GLOBALS['table'];
		$this -> smarty = &$GLOBALS['smarty'];
		$this -> lang	= &$GLOBALS['_LANG'];
	}

	/**
    * @return void 
    */
	function __destruct(){
		unset($this->dbcon,$this -> table,$this->smarty,$this -> lang);
	}

	/**
	 * @return array
	 *
	 * @package int $getDay
	 * @package int $recordNum
	 * @return array
	 */
	function getProductNewList($getDay = -2,$recordNum=100){
		$arrResult = null;
		
		$titles = "p.pid,p.item_name,p.price,p.image_name,p.description,p.StoreID,p.stockQuantity,p.datec";
		$wheres = "where p.deleted !='YES' and p.is_auction='no' and p.on_sale='yes' and p.stockQuantity > 0 ";
		// read records in one day 
		$wheres .= " and p.datec >= " . $this->dateAdd("d",$getDay, time()) .
		" and p.item_name != '' " .
		" and p.price != '' " .
		" and p.description != '' " 
		;
		$wheres .= " and bu.renewalDate>".time()." and lg.suspend=0 ";
		
		$query = " select $titles from ".$this->table."product p ".
				 " LEFT JOIN {$this->table}bu_detail bu ON bu.StoreID=p.StoreID ".
				 " LEFT JOIN {$this->table}login lg ON lg.StoreID=p.StoreID ".
				 "$wheres  order by p.datec desc, p.item_name limit 0,$recordNum";

		$this -> dbcon -> execute_query($query);
		$arrTemp = $this -> dbcon -> fetch_records(true);
		
		if (is_array($arrTemp) && count($arrTemp)>0 ) {
			$arrResult['product'] = $arrTemp;
			$i = 0 ;
			foreach ($arrResult['product'] as $temp){
				$arrResult['product'][$i]['item_name'] = $this->XmlSafeStr2($arrResult['product'][$i]['item_name']);
				$arrResult['product'][$i]['description'] = nl2br($this->XmlSafeStr2(substr($arrResult['product'][$i]['description'],0,150)));
				$i++;
			}
		}
		return $arrResult;
	}
	
}

?>