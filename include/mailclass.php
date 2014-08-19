<?  
/**
 * mail class
 * @package mail
 */
class mailclass {
 //smtp
 var $smtp = ""; 
 //
 var $check = 0;
 //
 var $username = "service";
 //
 var $password = "";
 //
 var $s_from = "info@thesocexchange.com";
 
 //error info
 var $mailerr;
 
 function smail ( $from, $password, $smtp="", $check = 1 ) {
  if( preg_match("/^[^\d\-_][\w\-]*[^\-_]@[^\-][a-zA-Z\d\-]+[^\-](\.[^\-][a-zA-Z\d\-]*[^\-])*\.[a-zA-Z]{2,3}/", $from ) ) {
   $this->username = substr( $from, 0, strpos( $from , "@" ));
   $this->password = $password ? $password : $this->password;
   $this->smtp = $smtp ? $smtp : $this->smtp;
   $this->check = ($check!=1) ? $check : $this->check;
   $this->s_from = $from;
  }
 }
 

 function send ( $to, $from, $subject, $message ) { 
 
  //connect
  $fp = fsockopen ( $this->smtp,25, $errno, $errstr, 100); 
  if (!$fp ){
  	$this->mailerr[] = "connect error " . __LINE__ ;
	return false;
  }
  set_socket_blocking($fp, true ); 
  $lastmessage=fgets($fp,512);
  if ( substr($lastmessage,0,3) != 220 ){
  	$this->mailerr[] = "Error: ".$lastmessage.__LINE__;
	return false;
  }
  
  //HELO
  $yourname = "service";
  if($this->check == 1) $lastact="EHLO ".$yourname."\r\n";
  else $lastact="HELO ".$yourname."\r\n";
  
  fputs($fp, $lastact);
  $lastmessage == fgets($fp,512);
  if (substr($lastmessage,0,3) != 220 ){
	$this->mailerr[] = "Error: $lastmessage".__LINE__; 
	return false;
  }
  while (true) {
   $lastmessage = fgets($fp,512);
   if ( (substr($lastmessage,3,1) != "-")  or  (empty($lastmessage)) )
    break;
  } 
 
    
  //duty auth
  if ($this->check==1) {
   //auth begin
   $lastact="AUTH LOGIN"."\r\n";
   fputs( $fp, $lastact);
   $lastmessage = fgets ($fp,512);
   if (substr($lastmessage,0,3) != 334){
   		$this->mailerr[] = "Error: $lastmessage".__LINE__; 
		return false;
   }
   //user name
   $lastact=base64_encode($this->username)."\r\n";
   fputs( $fp, $lastact);
   $lastmessage = fgets ($fp,512);
   if (substr($lastmessage,0,3) != 334) {
   		$this->mailerr[] = "Error: $lastmessage".__LINE__;
		return false;
   }
   //user passowrd
   $lastact=base64_encode($this->password)."\r\n";
   fputs( $fp, $lastact);
   $lastmessage = fgets ($fp,512);
   if (substr($lastmessage,0,3) != "235"){
   		$this->mailerr[] = "Error: $lastmessage".__LINE__;
		return false;
   }
  }
  
  //FROM:
  $lastact="MAIL FROM: ". $this->s_from . "\r\n"; 
  fputs( $fp, $lastact);
  $lastmessage = fgets ($fp,512);
  if (substr($lastmessage,0,3) != 250){
  		"Error: $lastmessage".__LINE__;
		return false;
  }
    
  //TO:
  $lastact="RCPT TO: $to" . "\r\n"; 
  fputs( $fp, $lastact);
  $lastmessage = fgets ($fp,512);
  if (substr($lastmessage,0,3) != 250){
  		$this->mailerr[] = "Error: $lastmessage".__LINE__;
		return false;
  }
   
  //DATA
  $lastact="DATA\r\n";
  fputs($fp, $lastact);
  $lastmessage = fgets ($fp,512);
  if (substr($lastmessage,0,3) != 354){
  		$this->mailerr[] = "Error: $lastmessage".__LINE__;
		return false;
  }
  
   
  //Subject
  $head="Subject: $subject\r\n"; 
  $message = $head."\r\n".$message; 
   
  
  //From
  $head="From: $from\r\n"; 
  $message = $head.$message; 
   
  //To 
  $head="To: $to\r\n";
  $message = $head.$message;
   
  //HTML format
  $head="MIME-Version: 1.0 \r\nContent-Type: text/html;charset=\"iso-8859-1\"\r\n";
  $message = $head.$message;

  //version
  $head="X-Mailer: <!-- buyblitz Version 2.0 Copyright (c) 2002-2008 -->\r\n";
  $message = $head.$message;
  
  //version content
  $head="X-Originating-IP: [" . $this->smtp . "]\r\n";
  $message = $head.$message;
  
  //add end string 
  $message .= "\r\n.\r\n";
  
  //send info 
  fputs($fp, $message); 
  $lastact="QUIT\r\n"; 
  
  fputs($fp,$lastace); 
  fclose($fp); 
  return true;
 } 
}
/* send mail */
function sendmail($to, $subject, $message, $from="", $password = "", $smtp="", $check = 1){
	$sm = new mailclass( $from , $password , $smtp , $check);
	$end = $sm->send( $to, $from, $subject, $message );
	if(!$end){
		echo $sm->mailerr[0];
	}
	unset($sm);  //chedk error info in before.
	return $end;
}
?> 
