<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//require str_replace('\\','/',dirname(__FILE__)).'/include/class/download.php';
require str_replace('\\','/',dirname(__FILE__).'/include/download_helper.php');
//$download=new download();

$cp=$_GET['cp'];
$file_type = $_GET['filetype'];

switch($cp) {

    case 'csvsample' :                      // Bulk Upload Product CSV Sample
        if($file_type == 'auction') {
            download(dirname(__FILE__).'/pdf/Auction.csv');
        } elseif ($file_type == 'freelisting') {
        	download(dirname(__FILE__).'/pdf/sample_freelisting.csv');
        } else {
            download(dirname(__FILE__).'/pdf/sample_buy&sell.csv');
        }
    break;
}


?>
