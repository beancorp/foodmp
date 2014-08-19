<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classdbstate
 *
 * @author Yangball
 */
include_once('class.db.dbbase.php');
class state extends dbbase{
    //put your code here

    /*
     *  @author YangBall
     *  get state array by id
     */
    public function getStateById($id){
        $strsql='SELECT * FROM `'.$this->table.'state` WHERE id='.$id;
        $this->dbcon->execute_query($strsql);
        $rs=$this->dbcon->fetch_records();
        return $rs[0];
    }
}
?>
