<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author Albert O
 */
require_once(dirname(__FILE__) . '/class.db.dbbase.php');
class guestEmailSubscriber   {
	
    function __construct()
    {
        $this -> dbcon  = &$GLOBALS['dbcon'];
        $this -> table	= &$GLOBALS['table'];
        $this -> smarty = &$GLOBALS['smarty'];
        $this -> lang	= &$GLOBALS['_LANG'];
        	
    }
    
    public function getGuestSubscriber($storeID,$id)
    {
    	$sql = "SELECT * FROM {$this->table}emailsubscriber WHERE store_id =".$storeID."  and id=".$id;
        $this -> dbcon -> execute_query($sql);
    	$res = $this -> dbcon -> fetch_records(true);
         return $res ? $res : array();
    }
    
    public function getGuestSubscriberNum($storeID)
    {
        $sql = "SELECT count(*) as total FROM {$this->table}emailsubscriber WHERE store_id =".$storeID;
       $res = $this->dbcon->getOne($sql);
       $count = $res ? $res['total'] : 0;    	
       return $count;
        
    }
    
    public function getGuestSubscriberListByStore($storeID)
    {
        $sql = "SELECT *, DATE_FORMAT(subscribe_date, '%d.%m.%Y %H:%M') as fsubscribe_date FROM {$this->table}emailsubscriber WHERE store_id =".$storeID;
    	$this -> dbcon -> execute_query($sql);
        $res = $this -> dbcon -> fetch_records(true);
        
        return $res ? $res : array();
        
    }
    
    public function checkGuestSubscriberInStore($storeID,$email)
    {
       $sql = "SELECT count(*) as xcount FROM {$this->table}emailsubscriber WHERE store_id =".$storeID."  and email='".$email."'";
       $res = $this->dbcon->getOne($sql);
       $count = $res ? $res['xcount'] : 0;    	
       return $count;
    }
    
    public function addGuestSubscriber($storeID,$email, $nickname)
    {
        
        return $this->_addGuestSubscriber($storeID, $email, $nickname);
    }
 
    private function _addGuestSubscriber($storeID, $email, $nickname)
    {
        $data = array(
             'email' =>  $email,
             'store_id'     =>  $storeID,
             'nickname'    =>  $nickname
         );
         return $this->dbcon->insert_query($this->table . 'emailsubscriber', $data);
    }
    
}
?>
