<?php
class ecardClass{
	function bulidheader($subject,$From,$to){
		$header = "MIME-Version: 1.0\n";
		$header.= "Received: by ".$_SERVER['REMOTE_ADDR']." with HTTP; ".date("D, j M  Y G:i:s O (T)")."\n";
		$header.= "Date: ".date("D, j M  Y G:i:s O")."\n";
		$header.= "Subject: $subject\n";
		$header.= "From: $From\n";
		//$header.= "To: $to\n";
		$header.= "Content-Type: multipart/related; boundary=0016e6496c3e0aad8d0475778edd\n";
		$header.= "\n";
		$header.= "--0016e6496c3e0aad8d0475778edd\n";
		$header.= "Content-Type: multipart/alternative; boundary=0016e6496c3e0aad890475778edc\n\n";
		return $header;
	}
	function bulidattment($file,$block,$fileid,$filename,$filetype){
		$str  = "";
		$file = ROOT_PATH.$file;
		if(file_exists($file)){
			$str = "--$block\n";
			$str .="Content-Type: $filetype; name=\"$filename\"\n";
			$str .="Content-Transfer-Encoding: base64\n";
			$str .="Content-ID: <$fileid>\n";
			$str .="X-Attachment-Id: $fileid\n";
			$str .="\n";
			$str .= chunk_split(base64_encode(file_get_contents($file)))."\n";
		}
		return $str;
	}
	
	function bulidmessagecontent($bid,$aryInfo,$tpl,$payment='paypal'){
		$blockid = "0016e6496c3e0aad890475778edc";
		$str ="--$blockid\n";
		$str.="Content-Type: text/plain; charset=UTF-8\n";
		$str.="\n";
		$str.="Dear {$aryInfo['nickname']},\n";
		$str.="\n";
		$str.="{$aryInfo['message']}\n";
		$str.="{$aryInfo['proName']}\n";
		$str.="\n";
		$str.="{$aryInfo['proDesc']}\n";
		$str.="$".$aryInfo['proPrice']."\n";
		$str.=$aryInfo['payInculde']."\n";
		$str.="\n";
		$str.="Love {$aryInfo['buyerName']} \n";
		$str.="<http://www.socexchange.com.au/>\n";
		$str.="\n";
		$str.="--$blockid\n";
		$str.="Content-Type: text/html; charset=UTF-8\n";
		$str.="Content-Transfer-Encoding: UTF-8\n";
		$str.="\n";
		$strHTML ="<table cellspacing=\"0\" cellpadding=\"0\" width=\"576\" align=\"center\" bgcolor=\"#ffffff\" border=\"0\">\n";
		$strHTML.="<tbody>\n";
		$strHTML.="<tr>\n";
		$strHTML.="<td colspan=\"5\"><img src=\"cid:ii_124372c23e3f5a31\"></td></tr>\n";
		$strHTML.="<tr>\n";
		$strHTML.="<td width=\"31\" height=\"256\"><img title=\"border-left.jpg\" alt=\"border-left.jpg\" src=\"cid:ii_124372b23501391f\"></td>\n";
		$strHTML.="<td valign=\"top\" width=\"331\">\n";
		$strHTML.="<table cellspacing=\"0\" cellpadding=\"0\" width=\"331\" align=\"center\" bgcolor=\"#ffffff\" border=\"0\">\n";
		$strHTML.="<tbody>\n";
		$strHTML.="<tr>\n";
		$strHTML.="<td width=\"35\" height=\"42\"><img title=\"spacer.gif\" alt=\"spacer.gif\" src=\"cid:ii_124372acb88f8d6d\"></td>\n";
		$strHTML.="<td width=\"264\"><img title=\"spacer.gif\" alt=\"spacer.gif\" src=\"cid:ii_124372acb88f8d6d\"></td>\n";
		$strHTML.="<td width=\"25\"><img title=\"spacer.gif\" alt=\"spacer.gif\" src=\"cid:ii_124372acb88f8d6d\"></td></tr>\n";
		$strHTML.="<tr>\n";
		$strHTML.="<td height=\"214\"></td>\n";
		$strHTML.="<td style=\"PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FONT-SIZE: 16px; PADDING-BOTTOM: 0px; MARGIN: 0px; COLOR: #494949; LINE-HEIGHT: 20px; PADDING-TOP: 0px; FONT-FAMILY: Arial\" valign=\"top\">".nl2br($aryInfo['message'])."</td>\n";
		$strHTML.="<td></td></tr></tbody></table></td>\n";
		$strHTML.="<td width=\"9\"><img title=\"border-mid.gif\" alt=\"border-mid.gif\" src=\"cid:ii_124372b0daeda2eb\"></td>\n";
		$strHTML.="<td valign=\"top\" width=\"177\">\n";
		$strHTML.="<table cellspacing=\"0\" cellpadding=\"0\" width=\"177\" align=\"center\" bgcolor=\"#ffffff\" border=\"0\">\n";
		$strHTML.="<tbody>\n";
		$strHTML.="<tr>\n";
		$strHTML.="<td width=\"17\"><img title=\"spacer.gif\" alt=\"spacer.gif\" src=\"cid:ii_124372acb88f8d6d\"></td>\n";
		$strHTML.="<td width=\"143\"><div style=\"height:20px\">&nbsp;</div><img src=\"cid:ii_124372c766fcd852\" width=\"142\" height=\"165\"></td>\n";
		$strHTML.="<td width=\"17\"><img title=\"spacer.gif\" alt=\"spacer.gif\" src=\"cid:ii_124372acb88f8d6d\"></td></tr>\n";
		$strHTML.="<tr>\n";
		$strHTML.="<td></td>\n";
		$strHTML.="<td style=\"PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FONT-SIZE: 12px; PADDING-BOTTOM: 0px; MARGIN: 0px; COLOR: #999999; PADDING-TOP: 0px; FONT-FAMILY: Arial\"><span style=\"FONT-SIZE: 14px; COLOR: #494949\">{$aryInfo['proName']}</span><br>\n";
		$strHTML.="<span style=\"FONT-WEIGHT: bold; FONT-SIZE: 15px\">$".$aryInfo['proPrice']."</span><br><span style=\"FONT-SIZE: 12px\">{$aryInfo['payInculde']}</span></td>\n";
		$strHTML.="<td></td></tr></tbody></table></td>\n";
		$strHTML.="<td width=\"28\"><img title=\"border-right.jpg\" alt=\"border-right.jpg\" src=\"cid:ii_124372b870796475\"></td></tr><tr>\n";
		$strHTML.="<td colspan=\"5\"><img title=\"footer.jpg\" alt=\"footer.jpg\" src=\"cid:ii_124372c493c46e82\"><a href=\"http://www.socexchange.com.au/\" target=\"_blank\"></a></td></tr>\n";
		/*if($payment=='googlecheckout'){
			$str .= "<td><td colspan=\"5\">Please be aware when using Google Checkout that your eCard will be sent to the wishlist recipient, irrespective of the funds being successfully deposited into the designated Google Checkout account.</td></tr>\n";
		}*/	
		$strHTML.="</tbody>\n";
		$strHTML.="</table>\n";

                $str .= getEmailTemplate($strHTML);
		$str.="\n";
		$str.="--$blockid--\n";
		
		$fileary = array(
				array('file'=>'/skin/red/images/ecard/spacer.gif','fileid'=>'ii_124372acb88f8d6d','filename'=>'spacer.gif','filetype'=>'image/gif'),
				array('file'=>'/skin/red/images/ecard/border-right.jpg','fileid'=>'ii_124372b870796475','filename'=>'border-right.jpg','filetype'=>'image/jpeg'),
				array('file'=>'/skin/red/images/ecard/border-mid.gif','fileid'=>'ii_124372b0daeda2eb','filename'=>'border-mid.gif','filetype'=>'image/gif'),
				array('file'=>'/skin/red/images/ecard/footer.jpg','fileid'=>'ii_124372c493c46e82','filename'=>'footer.jpg','filetype'=>'image/jpeg'),
				array('file'=>'/skin/red/images/ecard/border-left.jpg','fileid'=>'ii_124372b23501391f','filename'=>'border-left.jpg','filetype'=>'image/jpeg')		);
		$templates = array('a'=>'/skin/red/images/ecard/birthday-boys-blue.jpg','b'=>'/skin/red/images/ecard/birthday-boys-light-blue.jpg','c'=>'/skin/red/images/ecard/birthday-girls-pink.jpg','d'=>'/skin/red/images/ecard/birthday-girls-purple.jpg','e'=>'/skin/red/images/ecard/general-blue.jpg','f'=>'/skin/red/images/ecard/general-green.jpg','g'=>'/skin/red/images/ecard/general-light-blue.jpg','h'=>'/skin/red/images/ecard/general-pink.jpg','i'=>'/skin/red/images/ecard/general-purple.jpg','j'=>'/skin/red/images/ecard/xmas-blue.jpg','k'=>'/skin/red/images/ecard/xmas-green.jpg','l'=>'/skin/red/images/ecard/xmas-grey.jpg','m'=>'/skin/red/images/ecard/xmas-red.jpg','r'=>'/skin/red/images/ecard/wedding-purple.jpg','n'=>'/skin/red/images/ecard/wedding-blue.jpg','o'=>'/skin/red/images/ecard/wedding-green.jpg','p'=>'/skin/red/images/ecard/wedding-lightblue.jpg','q'=>'/skin/red/images/ecard/wedding-pink.jpg');
					
		$fileary[] = array('file'=>$templates[$tpl],'fileid'=>'ii_124372c23e3f5a31','filename'=>'banner.jpg','filetype'=>'image/jpeg');
		$fileary[] = array('file'=>$aryInfo['profile'],'fileid'=>'ii_124372c766fcd852','filename'=>'product.jpg','filetype'=>'image/jpeg');
		foreach ($fileary as $pass){
			$str.=$this->bulidattment($pass['file'],$bid,$pass['fileid'],$pass['filename'],$pass['filetype']);
		}
		$str.="--$bid--";
		return fixEOL($str);
	}
}
	/*$products = array('nickname'=>'Thomas','message'=>'Trrem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.Trrem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.','buyerName'=>'Shelley','proPrice'=>'80.00','proName'=>'Red suitcase','proDesc'=>substr('Trem ipsom terheryir form ad sdm terhe for ryir form',0,60),'profile'=>'/skin/red/images/ecard/suitcase.jpg');
	$headers = bulidheader('Send ecard','roy luo <roy.luo@infinitytesting.com.au>','roy luo <roy.luo@infinitytesting.com.au>');
	$headers.= bulidmessagecontent('0016e6496c3e0aad8d0475778edd',$products,'c');
	mail('roy.luo@infinitytesting.com.au','Send ecard','',$headers);*/
?>