<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
@session_start();


$store_id = $_REQUEST["store_id"];

$target_dir = getcwd()."/../fanpromo/";


$target_file = $target_dir . "flyer_store/flyer_{$store_id}.pdf";

if (file_exists (  $target_file )) exit;


include_once ('../include/config.php');
include_once ('../include/smartyconfig.php');
include_once ('functions.inc.php');
include_once ('maininc.php');
include_once ('class.soc.php');

$flyer_pdf = $target_dir . "flyer.pdf";


$retailer_info = getStoreInfoByStoreId($dbcon, $store_id);
$code = getCodeByStoreId($dbcon, $store_id);

//$stylesheet = file_get_contents($target_dir.'flyer_store/flyer.css');

if (LANGCODE == "en-au"){
	$url = $retailer_info["bu_urlstring"].".foodmarketplace.com.au";
	$image_name = "flyer_au.png";
}else{
	$url = $retailer_info["bu_urlstring"].".foodmarketplace.com";
	$image_name = "flyer_us.png";
}



$html = '
	
	<style>
	.content{
	background-color: #e2eff3;
	text-align: center;
	margin: 0 auto;
	position:fixed;
	font-family:sans-serif;
	
}
.content h2{
	font-weight: normal;
	font-size: 35px;
	color: #58595b;
	text-transform: capitalize;
}
.divider{
	width: 200px;
	height: 2px;
	background-color: #c2ddf6;
	margin: 100px auto 0px;
}
.content h3{
	color: #58595b;
	font-size: 28px;
	margin-bottom: 25px;
}
.btn-text{
	width: 400px;
	height: 100px;
	background-color: #ffffff;
	margin: 0px auto 0px;
	text-align: center;
	text-transform: uppercase;
	color: #58595b;
	
	font-size: 50px;
	line-height: 100px;
	border: 3px solid #d1d3d4;
	margin-bottom: 200px;
}
.content a{
	color: #5b5095;
	text-decoration: none;
	font-size: 55x;
	padding-bottom: 50px;
	display: block;
}

.link_url{
	color: #5b5095;
	text-decoration: none;
	font-size: 35px;
	padding-bottom: 200px;
	display: block;
	margin: 0 auto;

}

.image{
	padding-top: 100px;
	display: block;
}
	</style>
	
	<div style="position:fixed; left: 0; right: 0;  bottom: -100; top: 0; height:5000px; display:inline-block">
	
	
		<div class="content" >
		
		
			<img class="image" style="vertical-align: top" src="'. SOC_HTTP_HOST. '/fanpromo/flyer_store/'.$image_name.'" width="650"  />
			<br>
			<br>
			<br>
			<h2>'.$retailer_info["bu_name"].'</h2>
			<div class="divider"></div>
			<h3>Retailer Member Code</h3>
			<br>		
			<div class="btn-text">'.$code["code"].'</div>
			<br>
			<br>
			<br>
			<br>
			<br>			
			<br>
			<br>
			<div class="link_url">'.  strtolower($url) . '
			</div>
		</div>
		
	</div>
';




//create file
include("../include/mpdf/mpdf.php");

/*
$mpdf=new mPDF("c"); 
$mpdf->AddPage();
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($html);

$mpdf->SetImportUse(); 
$pagecount = $mpdf->SetSourceFile($flyer_pdf);
//$mpdf->AddPage();
$tplId = $mpdf->ImportPage(1);
$mpdf->UseTemplate($tplId, 10, 30, 100);
//$mpdf->Output();
 * 
 */

//$mpdf=new mPDF("c","A4",0, '', 0, 0, 0, 0, 0, 0);

$mpdf=new mPDF("s","",0, '', 0, 0, 0, 0, 0, 0); 


$mpdf->SetDisplayMode('fullpage');
//$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($html);



//$mpdf->SetHTMLFooter('<i>Hallo Wor123423423</i>');
$mpdf->Output($target_file,'F');

exit;





?>