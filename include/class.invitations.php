<?php
	class invitations extends common {
		var $imagelc;
		var $mp3lc;
		var $dbcon  = null;
		var $table  = '';
		var $smarty = null;
		var $lang   = null;
		var $EmailID = "0016e6496c3e0aad8d0475778edd";
		var $EmailBlockID = "0016e6496c3e0aad890475778edc";
		
		public function __construct(){
			/**
			 * The wishlist image file location:
			 * The wishlist mp3 file location: 
			**/
			$this->imagelc = "/upload/wishlist/image";
			$this->mp3lc = "/upload/wishlist/mp3";
			$this->dbcon  = &$GLOBALS['dbcon'];
			$this->table  = &$GLOBALS['table'];
			$this->smarty = &$GLOBALS['smarty'];
			$this->lang   = &$GLOBALS['_LANG'];
		}
		public function __destruct(){
			unset( $this->dbcon, $this->table, $this->smarty, $this -> lang , $this->imagelc, $this->mp3lc);
		}
		
		public function save_invitations($data){
			$this->dbcon->insert_record($this->table."wishlist_invation", $data);
			return $this->dbcon->insert_id();
		}
	
		public function get_SellerInfo($StoreID){
			$query = "SELECT * FROM {$this->table}bu_detail where StoreID='$StoreID'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$Store_Info = $result[0];
			$query = "SELECT * FROM {$this->table}wishlist_detail where StoreID='$StoreID'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$wishlist_Info = $result[0];
			$sellerInfo = array();
			$sellerInfo['wishlist_email'] = $Store_Info['bu_email'];
			$sellerInfo['wishlist_password'] = $wishlist_Info['password'];	 
			$sellerInfo['wishlist_url'] = "http://{$_SERVER['HTTP_HOST']}/".$Store_Info['bu_urlstring']."/wishlist";
			$sellerInfo['wishlist_StoreName'] = $Store_Info['bu_name']; 
			return $sellerInfo;
		}
				
		public function getTemplate($id){
			$query = "SELECT * FROM {$this->table}wishlist_invation_template WHERE id='$id'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$TemplateInfo = array();
			$aryKey = array('body_top','body_left','body_right','body_bottom','content_1','content_2','content_3','content_4','botton_left','botton_right');
			if($result){
				$result = $result[0];
				$templateID = $result['id'];
				foreach ($result as $key=>$val):
					if(in_array($key,$aryKey)){
						if($key=='body_top'&&@$_REQUEST['template_type']=='user'&&@isset($_REQUEST['usertpl_img'])){
							$TemplateInfo['Images'][$key]['key'] = $val;
							$TemplateInfo['Images'][$key]['url'] = ROOT_PATH.$_REQUEST['usertpl_img'];
							$TemplateInfo['Images'][$key]['htmlurl'] = "/".$_REQUEST['usertpl_img'];
							switch (strtolower(substr($_REQUEST['usertpl_img'],-3))){
								case 'jpg':
									$imgtype = "image/jpeg";
									break;
								case 'gif':
									$imgtype = "image/png";
									break;
								case 'png':
									$imgtype = "image/png";
									break;
							}
							$TemplateInfo['Images'][$key]['type'] = $imgtype; 
							$TemplateInfo['Images'][$key]['name'] = "{$templateID}_{$key}.".substr($_REQUEST['usertpl_img'],-3); 
							continue;
						}
						if(file_exists(ROOT_PATH."skin/red/invitations/template/{$templateID}_{$key}.jpg")):
							$TemplateInfo['Images'][$key]['key'] = $val; 
							$TemplateInfo['Images'][$key]['url'] = ROOT_PATH."skin/red/invitations/template/{$templateID}_{$key}.jpg";
							$TemplateInfo['Images'][$key]['htmlurl'] = "/skin/red/invitations/template/{$templateID}_{$key}.jpg";
							$TemplateInfo['Images'][$key]['type'] = 'image/jpeg'; 
							$TemplateInfo['Images'][$key]['name'] = "{$templateID}_{$key}.jpg"; 
						elseif (file_exists(ROOT_PATH."skin/red/invitations/template/{$templateID}_{$key}.gif")):
							$TemplateInfo['Images'][$key]['key'] = $val; 
							$TemplateInfo['Images'][$key]['url'] = ROOT_PATH."skin/red/invitations/template/{$templateID}_{$key}.gif"; 
							$TemplateInfo['Images'][$key]['htmlurl'] = "/skin/red/invitations/template/{$templateID}_{$key}.gif";
							$TemplateInfo['Images'][$key]['type'] = 'image/gif'; 
							$TemplateInfo['Images'][$key]['name'] = "{$templateID}_{$key}.gif"; 
						elseif (file_exists(ROOT_PATH."skin/red/invitations/template/{$templateID}_{$key}.png")):
							$TemplateInfo['Images'][$key]['key'] = $val; 
							$TemplateInfo['Images'][$key]['url'] = ROOT_PATH."skin/red/invitations/template/{$templateID}_{$key}.png"; 
							$TemplateInfo['Images'][$key]['htmlurl'] = "/skin/red/invitations/template/{$templateID}_{$key}.png";
							$TemplateInfo['Images'][$key]['type'] = 'image/png';
							$TemplateInfo['Images'][$key]['name'] = "{$templateID}_{$key}.png";  
						endif;
					}else{
						$TemplateInfo['Info'][$key] = $val;
					}
				endforeach;
			}
			return $TemplateInfo;
		}		
			
		public function bulidEmailheader($subject,$FromName,$to){
			$header = "MIME-Version: 1.0\n";
			$header.= "Received: by ".$_SERVER['REMOTE_ADDR']." with HTTP; ".date("D, j M  Y G:i:s O (T)")."\n";
			$header.= "Date: ".date("D, j M  Y G:i:s O")."\n";
			//$header.= "Subject: $subject\n";
			$header.= "From: $FromName<noreply@thesocexchange.com>\n";
			$header.= "To: $to\n";
			$header.= "Content-Type: multipart/related; boundary={$this->EmailID}\n";
			$header.= "\n";
			$header.= "--{$this->EmailID}\n";
			$header.= "Content-Type: multipart/alternative; boundary={$this->EmailBlockID}\n\n";
			return $header;
		}
		
		public function bulidHTML($id,$textInfo){
			$attachment = $this->getTemplate($id);
			$str  ="--{$this->EmailBlockID}\n";
			$str .="Content-Type: text/plain; charset=UTF-8\n";
			$str .="\n";
			$str .=$this->textbody($textInfo);			//text body
			$str .="--{$this->EmailBlockID}\n";
			$str .="Content-Type: text/html; charset=UTF-8\n";
			$str .="Content-Transfer-Encoding: UTF-8\n";
			$str .="\n";
			$str .=$this->htmlbody($textInfo,$attachment['Images'],$attachment['Info']);//html body
			$str .="\n";
			$str .="--{$this->EmailBlockID}--\n";
			foreach ($attachment['Images'] as $pass):
			$str .=$this->_bulidEmailattment($pass['url'],$this->EmailID,$pass['key'],$pass['name'],$pass['type']);
			endforeach;			
			$str .="--{$this->EmailID}--";
			return $str;
		}
		
	    private	function _bulidEmailattment($file,$block,$fileid,$filename,$filetype){
			$str  = "";
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
			
		private function htmlbody($textInfo,$ImgInfo,$templateInfo){
			preg_match('/body\s?\{(\s+.*\s+.*\s+.*\s+.*\s+.*[^\}])\}/i', $templateInfo['style'], $matches);
			$cssbody = "style=\"$matches[1]\"";
			$this->strCHRS($cssbody);
			preg_match('/a:link\s?\{(\s+.*\s+.*\s+.*\s+.*\s+.*[^\}])\}/i', $templateInfo['style'], $matches);
			$cssalink = "style=\"$matches[1]\"";
			$this->strCHRS($cssalink);
			preg_match('/font-large\s?\{(\s+.*\s+.*\s+.*\s+.*\s+.*[^\}])\}/i', $templateInfo['style'], $matches);
			$cssfont_large = "style=\"$matches[1]\"";
			$this->strCHRS($cssfont_large);
			preg_match('/font-med\s?\{(\s+.*\s+.*\s+.*\s+.*\s+.*[^\}])\}/i', $templateInfo['style'], $matches);
			$cssfont_med = "style=\"$matches[1]\"";
			$this->strCHRS($cssfont_med);
			preg_match('/font-small\s?\{(\s+.*\s+.*\s+.*\s+.*\s+.*[^\}])\}/i', $templateInfo['style'], $matches);
			$cssfont_small = "style=\"$matches[1]\"";
			$this->strCHRS($cssfont_small);
			
			$str .= "<html xmlns=\"http://www.w3.org/1999/xhtml\"><head>\n";
			$str .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/>\n";
			$str .= "<title>SOCExchange Invitation</title>\n";
			$str .= "<style type=\"text/css\">\n";
			$str .= $templateInfo['style'];
			$str .= "</style></head><body $cssbody>\n";
			$str .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\" width=\"749\">\n";
			$str .= "<tbody><tr><td colspan=\"3\">\n";
			$str .= "<div><img height=\"216\" width=\"749\" src=\"cid:{$ImgInfo['body_top']['key']}\"/></div>\n";
			$str .= "</td></tr>\n";
			$str .= "<tr><td width=\"60\" valign=\"top\" bgcolor=\"{$templateInfo['bgcolor']}\">\n";
			$str .= "<div><img height=\"487\" width=\"60\" src=\"cid:{$ImgInfo['body_left']['key']}\"/></div>\n";
			$str .= "</td><td bgcolor=\"{$templateInfo['bgcolor']}\" align=\"center\" width=\"629\" valign=\"top\">\n";
			$str .= "<p class=\"font-small\" $cssfont_small>\n";
			if(isset($ImgInfo['content_1'])):
			$str .= "<img alt=\"To\" src=\"cid:{$ImgInfo['content_1']['key']}\"/><br/>\n";
			endif;
			$str .= "{$textInfo['event_to']}<br/>\n";
			if(isset($ImgInfo['content_2'])):
				if($templateInfo['type']!="wedding"):
				$str .= "<br/>";
				endif;
				$str .= "<img src=\"cid:{$ImgInfo['content_2']['key']}\"/><br/>\n";
				if($templateInfo['template_type']=='wedding_1'):
				//wedding style 1
				$str .= "to the wedding of\n";
				endif;
			endif;
			$str .= "</p>\n";
			
			if($templateInfo['template_type']=='wedding_1'):
				//wedding style 1
				$str .= "<p class=\"font-large\" $cssfont_large>{$textInfo['event_1']}</p>\n";
			elseif ($templateInfo['template_type']=='wedding_2'):
				//wedding style 2
				$str .= "<p class=\"font-large\" $cssfont_large>{$textInfo['event_1']}</p>\n";
				$str .= "<p class=\"font-small\" $cssfont_small>{$textInfo['event_2']}</p>\n";
				$str .= "<p class=\"font-med\" $cssfont_med>invite you to celebrate their wedding</p>";
			elseif ($templateInfo['template_type']=='normal_2'):
				//normal style 2
				$str .= "<p class=\"font-large\" $cssfont_large><br/>{$textInfo['event_1']}</p>";
				$str .= "<p class=\"font-med\" $cssfont_med>{$textInfo['event_2']}<br/><br/></p>";
			elseif ($templateInfo['template_type']=='normal_1'):
				//normal style 1
				$str .= "<p class=\"font-large\" $cssfont_large>{$textInfo['event_1']}</p>";
			endif;
			
			$str .= "<p class=\"font-med\" $cssfont_med>\n";
			if(in_array($templateInfo['type'],array('party','xmas'))):
			$str .= "<br/>";
			endif;
			if(isset($ImgInfo['content_3'])):
			$str .= "<img src=\"cid:{$ImgInfo['content_3']['key']}\"/>\n";
			endif;
			if(in_array($templateInfo['type'],array('party','xmas'))):
			$str .= "<br/>";
			endif;
			$str .= "<br/>{$textInfo['event_time']}<br/>\n";
			$str .= "<br/>\n";
			if(isset($ImgInfo['content_4'])):
			$str .= "<img src=\"cid:{$ImgInfo['content_4']['key']}\"/>\n";
			endif;
			if(in_array($templateInfo['type'],array('party','xmas'))):
			$str .= "<br/>";
			endif;
			$str .= "<br/>{$textInfo['event_addr']}<br/>\n";
			if($templateInfo['type']=="wedding"):
				$str .= "{$textInfo['event_addr2']}<br/>";
			endif;
			$str .= "<br/></p>\n";
			$str .= "<table cellpadding=\"2\" border=\"0\" width=\"200\">\n";
			$str .= "<tbody><tr>\n";
			$str .= "<td align=\"center\" valign=\"bottom\" colspan=\"2\">\n";
			$str .= "<span class=\"font-small\" $cssfont_small>RSVP by {$textInfo['event_RSVP']}:</span>\n";
			$str .= "</td></tr>\n";
			$str .= "<tr><td width=\"176\">";
			$str .= "<a href=\"mailto:{$textInfo['wishlist_email']}?subject=".rawurlencode("Attending - {$textInfo['subject']}")."&body=".rawurlencode("Number of attendants = ?")."\">\n";
			$str .= "<img height=\"31\" border=\"0\" width=\"176\" src=\"cid:{$ImgInfo['botton_left']['key']}\"/>\n";
			$str .= "</a></td>\n";
			$str .= "<td width=\"10\">\n";
			$str .= "<a href=\"mailto:{$textInfo['wishlist_email']}?subject=".rawurlencode("Not attending - {$textInfo['subject']}")."\">\n";
			$str .= "<img height=\"31\" border=\"0\" width=\"269\" src=\"cid:{$ImgInfo['botton_right']['key']}\"/>\n";
			$str .= "</a></td></tr>\n";
			$str .= "<tr><td height=\"21\" align=\"center\" valign=\"top\" colspan=\"2\">";
			if($textInfo['ispass']):
			$str .= "<span class=\"font-small\" $cssfont_small>";
			$str .= "<a $cssalink href=\"{$textInfo['wishlist_url']}\">View wish list</a>\n";
			$str .= "&nbsp;&nbsp;|&nbsp;&nbsp;Password:&nbsp;&nbsp;\n";
			$str .= "<strong>{$textInfo['wishlist_password']}</strong></span>\n";
			endif;
			$str .= "</td></tr></tbody></table></td>\n";
			$str .= "<td width=\"60\" valign=\"top\" bgcolor=\"{$templateInfo['bgcolor']}\">\n";
			$str .= "<div><img height=\"487\" width=\"60\" src=\"cid:{$ImgInfo['body_right']['key']}\"/></div>\n";
			$str .= "</td></tr><tr>\n";
			$str .= "<td colspan=\"3\" bgcolor=\"{$templateInfo['bgcolor']}\">\n";
			$str .= "<div><img height=\"35\" width=\"749\" src=\"cid:{$ImgInfo['body_bottom']['key']}\"/></div>\n";
			$str .= "</td></tr></tbody></table></body></html>\n";
			return getEmailTemplate($str);
		}
		
		private function strCHRS(&$str){
			$tmpstr = "";
			for ($i =0; $i<strlen($str);$i++):
				if(!in_array(ord(substr($str,$i,1)),array(9,10,13))){
					$tmpstr.= substr($str,$i,1);
				}
			endfor;
			$str = $tmpstr;
		}
/*
		public function previewHTML($textInfo,$ImgInfo,$templateInfo){
			$str .= "<html xmlns=\"http://www.w3.org/1999/xhtml\"><head>\n";
			$str .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/>\n";
			$str .= "<title>SOCExchange Invitation</title>\n";
			$str .= "<style type=\"text/css\">\n";
			$str .= $templateInfo['style'];
			$str .= ".noteDIV{position:absolute;z-index:100;background-color:#777;width:739px;*width:749px;color:#FFF;padding:5px;filter:alpha(Opacity=80);-moz-opacity:0.8;opacity: 0.8;}";
			$str .= "</style></head><body>\n";
			$str .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\" width=\"749\">\n";
			$str .= "<tbody><tr><td colspan=\"3\">\n";
			$str .= "<div>\n";
			$str .= "<div class=\"noteDIV\">\n";
			if($_REQUEST['cp']=='preview'){
			$str .= "<span style=\"float:left;font-size:13px;*font-size:14px;\"><strong>Note: When sending more than one invitation, the To field will change for each email.</strong></span>\n";
			}
			$str .= "<span style=\"float:right;\";><a href=\"javascript:window.close();\" style=\"color:#fff;font-size:13px;*font-size:14px;\">Close</a></span></div>";
			$str .= "<img height=\"216\" width=\"749\" src=\"{$ImgInfo['body_top']['htmlurl']}\"/></div>\n";
			$str .= "</td></tr>\n";
			$str .= "<tr><td width=\"60\" valign=\"top\" bgcolor=\"{$templateInfo['bgcolor']}\">\n";
			$str .= "<div><img height=\"487\" width=\"60\" src=\"{$ImgInfo['body_left']['htmlurl']}\"/></div>\n";
			$str .= "</td><td bgcolor=\"{$templateInfo['bgcolor']}\" align=\"center\" width=\"629\" valign=\"top\">\n";
			$str .= "<p class=\"font-small\">\n";
			if(isset($ImgInfo['content_1'])):
			$str .= "<img alt=\"To\" src=\"{$ImgInfo['content_1']['htmlurl']}\"/><br/>\n";
			endif;
			$str .= "{$textInfo['event_to']}<br/>\n";
			if(isset($ImgInfo['content_2'])):
				if($templateInfo['type']!="wedding"):
				$str .= "<br/>";
				endif;
				$str .= "<img src=\"{$ImgInfo['content_2']['htmlurl']}\"/><br/>\n";
				if($templateInfo['template_type']=='wedding_1'):
				//wedding style 1
				$str .= "to the wedding of\n";
				endif;
			endif;
			$str .= "</p>\n";
			
			if($templateInfo['template_type']=='wedding_1'):
				//wedding style 1
				$str .= "<p class=\"font-large\">{$textInfo['event_1']}</p>\n";
			elseif ($templateInfo['template_type']=='wedding_2'):
				//wedding style 2
				$str .= "<p class=\"font-large\">{$textInfo['event_1']}</p>\n";
				$str .= "<p class=\"font-small\">{$textInfo['event_2']}</p>\n";
				$str .= "<p class=\"font-med\">invite you to celebrate their wedding</p>";
			elseif ($templateInfo['template_type']=='normal_2'):
				//normal style 2
				$str .= "<p class=\"font-large\"><br/>{$textInfo['event_1']}</p>";
				$str .= "<p class=\"font-med\">{$textInfo['event_2']}<br/><br/></p>";
			elseif ($templateInfo['template_type']=='normal_1'):
				//normal style 1
				$str .= "<p class=\"font-large\">{$textInfo['event_1']}</p>";
			endif;
			
			$str .= "<p class=\"font-med\">\n";
			if(in_array($templateInfo['type'],array('party','xmas'))):
			$str .= "<br/>";
			endif;
			if(isset($ImgInfo['content_3'])):
			$str .= "<img src=\"{$ImgInfo['content_3']['htmlurl']}\"/>\n";
			endif;
			if(in_array($templateInfo['type'],array('party','xmas'))):
			$str .= "<br/>";
			endif;
			$str .= "<br/>{$textInfo['event_time']}<br/>\n";
			$str .= "<br/>\n";
			if(isset($ImgInfo['content_4'])):
			$str .= "<img src=\"{$ImgInfo['content_4']['htmlurl']}\"/>\n";
			endif;
			if(in_array($templateInfo['type'],array('party','xmas'))):
			$str .= "<br/>";
			endif;
			$str .= "<br/>{$textInfo['event_addr']}<br/>\n";
			if($templateInfo['type']=="wedding"):
				$str .= "{$textInfo['event_addr2']}<br/>";
			endif;
			$str .= "<br/></p>\n";
			
			$str .= "<table cellpadding=\"2\" border=\"0\" width=\"200\">\n";
			$str .= "<tbody><tr>\n";
			$str .= "<td align=\"center\" valign=\"bottom\" colspan=\"2\">\n";
			$str .= "<span class=\"font-small\">RSVP by {$textInfo['event_RSVP']}:</span>\n";
			$str .= "</td></tr>\n";
			$str .= "<tr><td width=\"176\">";
			$str .= "<a href=\"mailto:{$textInfo['wishlist_email']}?subject=".rawurlencode("Attending - {$textInfo['subject']}")."&body=".rawurlencode("Number of attendants = ?")."\">\n";
			$str .= "<img height=\"31\" border=\"0\" width=\"176\" src=\"{$ImgInfo['botton_left']['htmlurl']}\"/>\n";
			$str .= "</a></td>\n";
			$str .= "<td width=\"10\">\n";
			$str .= "<a href=\"mailto:{$textInfo['wishlist_email']}?subject=".rawurlencode("Not attending - {$textInfo['subject']}")."\">\n";
			$str .= "<img height=\"31\" border=\"0\" width=\"269\" src=\"{$ImgInfo['botton_right']['htmlurl']}\"/>\n";
			$str .= "</a></td></tr>\n";
			$str .= "<tr><td height=\"21\" align=\"center\" valign=\"top\" colspan=\"2\">";
			if($textInfo['ispass']):
			$str .= "<span class=\"font-small\">";
			$str .= "<a href=\"{$textInfo['wishlist_url']}\">View wish list</a>\n";
			$str .= "&nbsp;&nbsp;|&nbsp;&nbsp;Password:&nbsp;&nbsp;\n";
			$str .= "<strong>{$textInfo['wishlist_password']}</strong></span>\n";
			endif;
			$str .= "</td></tr></tbody></table></td>\n";
			$str .= "<td width=\"60\" valign=\"top\" bgcolor=\"{$templateInfo['bgcolor']}\">\n";
			$str .= "<div><img height=\"487\" width=\"60\" src=\"{$ImgInfo['body_right']['htmlurl']}\"/></div>\n";
			$str .= "</td></tr><tr>\n";
			$str .= "<td colspan=\"3\" bgcolor=\"{$templateInfo['bgcolor']}\">\n";
			$str .= "<div><img height=\"35\" width=\"749\" src=\"{$ImgInfo['body_bottom']['htmlurl']}\"/><div>\n";
			$str .= "</td></tr>\n";
			$str .= "</tbody></table></body></html>\n";
			return $str;
		}
*/
                /*
                 *  @INFINITY_TEMP[YANG BALL 2010-07-29]
                 *
                 */

                public function previewHTML($textInfo,$ImgInfo,$templateInfo){
			$str .= "<html xmlns=\"http://www.w3.org/1999/xhtml\"><head>\n";
			$str .= "<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"/>\n";
			$str .= "<title>SOCExchange Invitation</title>\n";
			$str .= "<style type=\"text/css\">\n";
			$str .= $templateInfo['style'];
			$str .= ".noteDIV{height:15px;z-index:100;background-color:#777;width:739px;*width:749px;color:#FFF;padding:5px;filter:alpha(Opacity=80);-moz-opacity:0.8;opacity: 0.8;}";
			$str .= "</style></head><body>\n";

			$str .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\" width=\"749\">\n";
			$str .= "<tbody><tr><td colspan=\"3\">\n";
                        $str .= "<div class=\"noteDIV\">\n";
			if($_REQUEST['cp']=='preview'){
			$str .= "<span style=\"float:left;font-size:13px;*font-size:14px;\"><strong>Note: When sending more than one invitation, the To field will change for each email.</strong></span>\n";
			}
			$str .= "<span style=\"float:right;\";><a href=\"javascript:window.close();\" style=\"color:#fff;font-size:13px;*font-size:14px;\">Close</a></span></div>";
                        $str.="</td></tr>\n";
                        $str .= "<tr><td colspan=\"3\">\n";
			$str .= "<div>\n";

			$str .= "<img height=\"216\" width=\"749\" src=\"{$ImgInfo['body_top']['htmlurl']}\"/></div>\n";
			$str .= "</td></tr>\n";
			$str .= "<tr><td width=\"60\" valign=\"top\" bgcolor=\"{$templateInfo['bgcolor']}\">\n";
			$str .= "<div><img height=\"487\" width=\"60\" src=\"{$ImgInfo['body_left']['htmlurl']}\"/></div>\n";
			$str .= "</td><td bgcolor=\"{$templateInfo['bgcolor']}\" align=\"center\" width=\"629\" valign=\"top\">\n";
			$str .= "<p class=\"font-small\">\n";
			if(isset($ImgInfo['content_1'])):
			$str .= "<img alt=\"To\" src=\"{$ImgInfo['content_1']['htmlurl']}\"/><br/>\n";
			endif;
			$str .= "{$textInfo['event_to']}<br/>\n";
			if(isset($ImgInfo['content_2'])):
				if($templateInfo['type']!="wedding"):
				$str .= "<br/>";
				endif;
				$str .= "<img src=\"{$ImgInfo['content_2']['htmlurl']}\"/><br/>\n";
				if($templateInfo['template_type']=='wedding_1'):
				//wedding style 1
				$str .= "to the wedding of\n";
				endif;
			endif;
			$str .= "</p>\n";

			if($templateInfo['template_type']=='wedding_1'):
				//wedding style 1
				$str .= "<p class=\"font-large\">{$textInfo['event_1']}</p>\n";
			elseif ($templateInfo['template_type']=='wedding_2'):
				//wedding style 2
				$str .= "<p class=\"font-large\">{$textInfo['event_1']}</p>\n";
				$str .= "<p class=\"font-small\">{$textInfo['event_2']}</p>\n";
				$str .= "<p class=\"font-med\">invite you to celebrate their wedding</p>";
			elseif ($templateInfo['template_type']=='normal_2'):
				//normal style 2
				$str .= "<p class=\"font-large\"><br/>{$textInfo['event_1']}</p>";
				$str .= "<p class=\"font-med\">{$textInfo['event_2']}<br/><br/></p>";
			elseif ($templateInfo['template_type']=='normal_1'):
				//normal style 1
				$str .= "<p class=\"font-large\">{$textInfo['event_1']}</p>";
			endif;

			$str .= "<p class=\"font-med\">\n";
			if(in_array($templateInfo['type'],array('party','xmas'))):
			$str .= "<br/>";
			endif;
			if(isset($ImgInfo['content_3'])):
			$str .= "<img src=\"{$ImgInfo['content_3']['htmlurl']}\"/>\n";
			endif;
			if(in_array($templateInfo['type'],array('party','xmas'))):
			$str .= "<br/>";
			endif;
			$str .= "<br/>{$textInfo['event_time']}<br/>\n";
			$str .= "<br/>\n";
			if(isset($ImgInfo['content_4'])):
			$str .= "<img src=\"{$ImgInfo['content_4']['htmlurl']}\"/>\n";
			endif;
			if(in_array($templateInfo['type'],array('party','xmas'))):
			$str .= "<br/>";
			endif;
			$str .= "<br/>{$textInfo['event_addr']}<br/>\n";
			if($templateInfo['type']=="wedding"):
				$str .= "{$textInfo['event_addr2']}<br/>";
			endif;
			$str .= "<br/></p>\n";

			$str .= "<table cellpadding=\"2\" border=\"0\" width=\"200\">\n";
			$str .= "<tbody><tr>\n";
			$str .= "<td align=\"center\" valign=\"bottom\" colspan=\"2\">\n";
			$str .= "<span class=\"font-small\">RSVP by {$textInfo['event_RSVP']}:</span>\n";
			$str .= "</td></tr>\n";
			$str .= "<tr><td width=\"176\">";
			$str .= "<a href=\"mailto:{$textInfo['wishlist_email']}?subject=".rawurlencode("Attending - {$textInfo['subject']}")."&body=".rawurlencode("Number of attendants = ?")."\">\n";
			$str .= "<img height=\"31\" border=\"0\" width=\"176\" src=\"{$ImgInfo['botton_left']['htmlurl']}\"/>\n";
			$str .= "</a></td>\n";
			$str .= "<td width=\"10\">\n";
			$str .= "<a href=\"mailto:{$textInfo['wishlist_email']}?subject=".rawurlencode("Not attending - {$textInfo['subject']}")."\">\n";
			$str .= "<img height=\"31\" border=\"0\" width=\"269\" src=\"{$ImgInfo['botton_right']['htmlurl']}\"/>\n";
			$str .= "</a></td></tr>\n";
			$str .= "<tr><td height=\"21\" align=\"center\" valign=\"top\" colspan=\"2\">";
			if($textInfo['ispass']):
			$str .= "<span class=\"font-small\">";
			$str .= "<a href=\"{$textInfo['wishlist_url']}\">View wish list</a>\n";
			$str .= "&nbsp;&nbsp;|&nbsp;&nbsp;Password:&nbsp;&nbsp;\n";
			$str .= "<strong>{$textInfo['wishlist_password']}</strong></span>\n";
			endif;
			$str .= "</td></tr></tbody></table></td>\n";
			$str .= "<td width=\"60\" valign=\"top\" bgcolor=\"{$templateInfo['bgcolor']}\">\n";
			$str .= "<div><img height=\"487\" width=\"60\" src=\"{$ImgInfo['body_right']['htmlurl']}\"/></div>\n";
			$str .= "</td></tr><tr>\n";
			$str .= "<td colspan=\"3\" bgcolor=\"{$templateInfo['bgcolor']}\">\n";
			$str .= "<div><img height=\"35\" width=\"749\" src=\"{$ImgInfo['body_bottom']['htmlurl']}\"/><div>\n";
			$str .= "</td></tr>\n";
			$str .= "</tbody></table></body></html>\n";
			return $str;
		}

		private function textbody($textInfo){
			$str = "";
			foreach ($textInfo as $val):
			$str .= "$val\n";
			endforeach;
			return $str;
		}
		
		public function getTempList(){
			$query = "SELECT * FROM {$this->table}wishlist_invation_template order by id asc";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$template = array();
			if($result):
				foreach ($result as $pass):
					$template[$pass['type']][] = $pass;
				endforeach;
			endif;
			return $template;
		}
	
		public function saveTemplateBanner($data){
			$query = "SELECT * FROM {$this->table}wishlist_invation_user where StoreID='{$data['StoreID']}'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			if($result){
				$this->dbcon->update_record($this->table."wishlist_invation_user",$data," where id={$result[0]['id']} ");
			}else{
				$this->dbcon->insert_record($this->table."wishlist_invation_user",$data);
			}
		}
	
		public function getUserTemplate($StoreID){
			$query = "SELECT template FROM {$this->table}wishlist_invation_user where StoreID='$StoreID'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			return $result[0]['template'];
		}
		
		public function getinvationsByStore($StoreID,$curpage =1){
			global $dbcon;
			$pageno		=	$curpage >0 ? $curpage : 1;
			$perPage	=	18;
			$arrResult = array();
			$sql = "SELECT count(*) as num from {$this->table}wishlist_invation where StoreID='{$StoreID}'";
			$dbcon->execute_query($sql);
			$totalNum	=	$this->dbcon->fetch_records();
			$totalNum	= 	$totalNum[0]['num'];
			($pageno * $perPage > $totalNum) ? $pageno = ceil($totalNum/$perPage) : '';
			$start	= ($pageno-1) * $perPage;
			$sql = "SELECT a.*,b.type from {$this->table}wishlist_invation a left join {$this->table}wishlist_invation_template b on a.email_template=b.id where StoreID='{$StoreID}' order by add_time DESC limit $start,$perPage";
			$dbcon->execute_query($sql);
			$arrResult['invitation_list']=$dbcon->fetch_records('true');
			$params = array(
					'perPage'    => $perPage,
					'totalItems' => $totalNum,
					'currentPage'=> $pageno,
					'delta'      => 15,
					'onclick'	 => 'javascript:xajax_invationlist(\'%d\',\''.$StoreID.'\');return false;',
					'append'     => false,
					'urlVar'     => 'pageno',
					'path'		 => '#',
					'fileName'   => '%d',
					);
			$pager = Pager::factory($params);
			$arrResult['links'] 		= $pager->getLinks();
			$arrResult['links']['all']	= "[ ".$pager ->numItems() . "/" .$pager ->numPages()." ] ". $arrResult['links']['all'];
			return $arrResult;
		}
		public function getInvationById($StoreID,$id){
			global $dbcon;
			$query = "SELECT * FROM {$this->table}wishlist_invation where StoreID='{$StoreID}' and id='$id'";
			$dbcon->execute_query($query);
			$result=$dbcon->fetch_records('true');
			return $result;
		}
		public function getInvationUserTPL($StoreID){
			$query = "SELECT * FROM {$this->table}wishlist_invation_user where StoreID='{$StoreID}'";
			$this->dbcon->execute_query($query);
			$result=$this->dbcon->fetch_records('true');
			if($result){
				return $result[0];
			}
			return "";
		}
	
		public function getTemplateByID($id){
			$query = "SELECT * FROM {$this->table}wishlist_invation_template where id='$id'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			return @$result[0];
		}
	}
?>