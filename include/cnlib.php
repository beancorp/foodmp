<?php

function generate_pdf($filename,$dir,$order_id,$gift_id,$from,$to,$store_name,$amount,$street,$city,$state,$date){
	include_once($dir."/include/fpdf.php");
	define('FPDF_FONTPATH',$dir.'/include/font/');
	$pdf = new FPDF('L','mm',array(106,210));
	$pdf->AddPage('L');
	$pdf->SetMargins(0,0);
	$pdf->SetTopMargin(0);
	$pdf->SetRightMargin(0);
	$pdf->SetFillColor(154,95,3);
	$pdf->Rect(0,0,210,105,'F');
	$pdf->Image($dir.'/include/gift.jpg', 0, 0, 210, 101);
	$pdf->SetFont('arial','B',13);
	$pdf->Text(22,79,$to);
	$pdf->Text(22,89,$from);
	$pdf->SetFontSize(14);
	$sn_width = $pdf->GetStringWidth($store_name);
	$pdf->Text((310+$sn_width)/2-$sn_width,50,$store_name);
	$pdf->SetFontSize(44);
	$pdf->SetTextColor(152,95,5);
	$pdf->Text(130,90,'$'.number_format($amount, 2, '.', ''));
	$pdf->SetFont('arial');
	$pdf->SetFontSize(12);
	//$addr_width = $pdf->GetStringWidth("$street  $city  $state");
	//$pdf->Text((310+$addr_width)/2-$addr_width,60,"$street  $city  $state");
	$addr_width = $pdf->GetStringWidth("$street");
	$pdf->Text((310+$addr_width)/2-$addr_width,60,"$street");
	$pdf->SetFontSize(8);
	$pdf->SetTextColor(255,255,2550);
	$pdf->Text(3,103,"Order ID: ".$order_id." Certificate No: ".$gift_id);
	$pdf->SetTextColor(255,255,255);
	$pdf->Text(183,103,'Expires:'.$date);

	$pdf->Output($dir.'/upload/pdf/'.$filename, 'F');
}

?>