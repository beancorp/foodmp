<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classdbcountry
 *
 * @author Yangball
 */
include_once('class.db.dbbase.php');
class country extends dbbase{
    //put your code here

    /*
     *  @author YangBall
     *  get array country by country name
     */
    function getCountryByName($countryname){
        $strsql='SELECT * FROM `'.$this->table.'country` WHERE country_name="'.$countryname.'"';
        $this->dbcon->execute_query($strsql);
        $rs=$this->dbcon->fetch_records();
        return $rs[0];
    }

    /*
     *  @author YangBall
     */
    function getCountryById($id){
        $strsql='SELECT * FROM `'.$this->table.'country` WHERE country_id="'.$id.'"';
        $this->dbcon->execute_query($strsql);
        $rs=$this->dbcon->fetch_records();
        return $rs[0];
    }
}
?>
