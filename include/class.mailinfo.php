<?php
/**
 * This class is send mail in public
 * Tue Feb 19 08:50:27 CST 2008 08:50:27
 * @author  : Ping.Hu <enquiries@infinitytechnologies.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * class.mail.php
 */
class mailInfoClass{

	/**
	 * send mail for after succeed register.
	 *
	 * @param array $arrVar
	 * @param string $mailto
	 * @param string $mailfrom
	 * @return boolean
	 */
	function sendRegisterMail($arrVar, $mailfrom='info@thesocexchange.com')
	{
		$boolResult	= false;
		
		extract($arrVar);
		
		$mailto	 = $bu_user;
		
		$subject = "Welcome to SOCExchange.com.au";
		
		$message	=	"<html>
		<head>
		<title>Website Registration</title>
		</head>
	
		<body>
		<p>Dear $bu_name, </p>
		<p>Congratulations, you are now a member of <a href=\"www.socexchange.com.au\">SOC Exchange</a> </p>
		<p><strong>The SOC Exchange is like having your own state-of-the-art website, with all the e-commerce facilities you will ever need, for only $1.00 per month!</strong><strong></strong></p>
		<p><strong>You can make changes to your website at anytime by logging in at www.socexchange.com.au </strong></p>
		<p>Username: $bu_user<br>
		Password: $bu_password</p>
		<p><strong>Let Us Make The Internet Work For You !!!!!! </strong><strong></strong></p>
		<p>Kind regards, </p>
		<p><a href=\"http://www.socexchange.com.au/\">www.SOCExchange.com.au </a></p>
		</body></html>";	

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=".PB_CHARSET."\r\n";
		/* additional headers */
		$headers .= "To: $mailto\r\n";
		$headers .= "From: $mailfrom\r\n";
		
		$boolResult	= mail($to, $subject, $message, $headers);

		return $boolResult;
	}
}
?>
