<?php

//include_once 'class.facebook.php';

require_once ('facebook/facebook.php');

$facebook = new Facebook(array(
  'appId'  => FB_APP_ID,
  'secret' => FB_APP_SECRET,
));

try{
    if(isset($smarty)){
        $smarty->assign('facebook',array('appID'=>FB_APP_ID, 'appSecret'=>FB_APP_SECRET));
    }
}  catch (Exception $e){
    
}