<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of bu_detail
 *
 * @author Yangball
 */
include_once('class.db.dbbase.php');
class bu_detail extends dbbase {
    //put your code here

    /*
     *  @author Yangball
     *  get a record 
     */
    function getRowByEmail($email){
        $strsql='SELECT * FROM `'.$this->table.'bu_detail` WHERE bu_email="'.$email.'"';
        $this->dbcon->execute_query($strsql);
        $rs=$this->dbcon->fetch_records();
        return $rs[0];
    }


    /*
     *  @author Yangball
     *  get a record
     */
    function getRowByStoreId($storeId){
        $strsql='SELECT * FROM `'.$this->table.'bu_detail` WHERE StoreID='.$storeId;
        $this->dbcon->execute_query($strsql);
        $rs=$this->dbcon->fetch_records();
        return $rs[0];
    }
}
?>
