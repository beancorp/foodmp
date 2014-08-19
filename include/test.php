<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once ('include/config.php');
@session_start();
//include_once ('include/smartyconfig.php');
include_once ('include/maininc.php');
//include_once ('include/class.soc.php');
$count=50;
$s=0;
$arr1=array();
for($i=1;$i<=$count;$i++) {
    $start=microtime();
    $sql="Select t1.*,t2.name as catName, t3.name as catNameF, t3.id as fid, t4.initial_price,  t4.end_date, t4.end_time, t4.end_stamp,t4.end_stamp as end_stamp2, t4.cur_price, t4.bid, t4.winner_id, t4.status,t4.reserve_price,t4.cur_price, t6.description as state, t6.stateName,t5.bu_suburb,  t5.bu_name,t5.bu_urlstring, t7.smallPicture, t7.picture, t4.reserve_price  from aus_soc_product as t1  left join aus_soc_product_category as t2 on t1.category=t2.id  left join aus_soc_product_category as t3 on t2.fid=t3.id  left join aus_soc_product_auction as t4 on t1.pid=t4.pid  left join aus_soc_bu_detail as t5 on t1.StoreID=t5.StoreID  left join aus_soc_state as t6 on t5.bu_state=t6.id  left join aus_soc_image as t7 on t1.StoreID=t7.StoreID and t1.pid=t7.pid and t7.attrib=0 and t7.sort=0  where t1.StoreID='855026' and t1.deleted=''  AND t1.is_auction in('yes','no')
    AND IF(t1.is_auction='yes',t4.end_stamp-1278731084>0,1=1) order by  t3.name ASC,  t1.datem desc,t1.item_name  LIMIT 250,50;";
    $dbcon->execute_query($sql);
    $end=microtime();
    $time=$end-$start;
    if($time<=0) {
        $i--;
    }
    else {
        //file_put_contents('db_old.txt', "$time\n\r",FILE_APPEND);
        $arr1[]=$time;
        $s+=$time;
    }
    
}
$avg1=$s/$i;
//file_put_contents('db_old.txt', "AVG:{$avg}",FILE_APPEND);
//----------------------------------------------------------------------------------------------------
$s=0;
$arr2=array();
for($i=1;$i<=$count;$i++) {
    $start=microtime();
    $sql="Select t1.*,t2.name as catName, t3.name as catNameF, t3.id as fid, t4.initial_price,  t4.end_date, t4.end_time, t4.end_stamp,t4.end_stamp as end_stamp2, t4.cur_price, t4.bid, t4.winner_id, t4.status,t4.reserve_price,t4.cur_price, t6.description as state, t6.stateName,t5.bu_suburb,  t5.bu_name,t5.bu_urlstring, t7.smallPicture, t7.picture, t4.reserve_price  from aus_soc_product as t1  left join aus_soc_product_category as t2 on t1.category=t2.id  left join aus_soc_product_category as t3 on t2.fid=t3.id  left join aus_soc_product_auction as t4 on t1.pid=t4.pid  left join aus_soc_bu_detail as t5 on t1.StoreID=t5.StoreID  left join aus_soc_state as t6 on t5.bu_state=t6.id  left join aus_soc_image as t7 on t1.StoreID=t7.StoreID and t1.pid=t7.pid and t7.attrib=0 and t7.sort=0  where t1.StoreID='855026' and t1.deleted=''  AND t1.is_auction in('yes','no')
    AND ((t1.is_auction='yes' AND t4.end_stamp-1278731084>0) OR t1.is_auction <> 'yes') order by  t3.name ASC,  t1.datem desc,t1.item_name  LIMIT 250,50;";
    $dbcon->execute_query($sql);
    $end=microtime();
    $time=$end-$start;
    if($time<=0) {
        $i--;
    }
    else {
        //file_put_contents('db_new.txt', "$time\n\r",FILE_APPEND);
        $arr2[]=$time;
        $s+=$time;
    }

}
$avg2=$s/$i;
//file_put_contents('db_new.txt', "AVG:{$avg}",FILE_APPEND);

foreach($arr1 as $k=>$v) {
    file_put_contents('db_sql.txt',$v.'--'.$arr2[$k]."\n\r",FILE_APPEND);
}
file_put_contents('db_sql.txt',$avg1.'--'.$avg2."\n\r",FILE_APPEND);
file_put_contents('avg.txt', $avg1.'--'.$avg2."\n\r",FILE_APPEND);
echo 'OK';
?>