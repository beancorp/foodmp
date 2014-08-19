<?php
//create a image
include_once("include/functions.php");

Header("Content-type: image/PNG");

session_start();//�����������session��
$_SESSION['authnum']="";
$im = imagecreate(62,20); //�ƶ�ͼƬ������С

$black = ImageColorAllocate($im, 0,0,0); //�趨������ɫ
$white = ImageColorAllocate($im, 255,255,255);
$gray = ImageColorAllocate($im, 200,200,200);

imagefill($im,0,0,$gray); //����������䷨���趨��0,0��

$authnum=randStr(4);
//draw 4 chars to image
$_SESSION['authnum']=$authnum;
imagestring($im, 5, 10, 3, $authnum, $black);

for($i=0;$i<200;$i++){
$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
imagesetpixel($im, rand()%70 , rand()%30 , $randcolor);
}

ImagePNG($im);
ImageDestroy($im);
?>