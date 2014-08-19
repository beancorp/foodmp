<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



//thie footer
function footer($type='seller')
{
    global $smarty;
    global $dbcon;
    global $table;
    $socObj=new socClass();
    if($type=='job') {
        
        $rs=getSectorListFromDB(0,1);
        $type="job";
    }
    elseif($type=='estate') {
        $_query = "SELECT id, `stateName` as mname,`description` as name \n".
		"FROM `". $table ."state` \n".
		"ORDER BY `description` ASC";
        $dbcon->execute_query($_query);
        $rs=$dbcon->fetch_records(true);
        $type='estate';
    }
    elseif($type=='auto') {
        $add_option = array(array('name'=>'All','id'=>'','place'=>0),array('name'=>'Other','id'=>'-2','place'=>1));
        $rs=getSectorListFromDB(504,0,$add_option);
        unset($rs[0]);
        sort($rs);
        $other=$rs[0];
        unset($rs[0]);
        
        sort($rs);
        $rs[]=$other;
        $type='auto';
    }
    elseif ($type=='foodwine') {
    	$type='foodwine';
    }
    else{
        $query="select t1.id,t1.fid,t1.name,t1.image ".
				" from ".$table."product_category as t1 ".
				" where t1.fid='0' and t1.disabled=0 order by t1.name,t1.sort";
        $dbcon->execute_query($query);
        $rs=$dbcon->fetch_records(true);
        $type='seller';
    }
    $smarty->assign('type',$type);
    $smarty->assign('categoryList', $rs);
    $smarty->assign('count',  count($rs));
    $path=str_replace('\\','/',dirname(__FILE__)).'/../skin/red/base_include/footer.tpl';
    return $smarty->fetch($path);
}
?>
