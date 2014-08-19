<?php
	set_time_limit(0);

	include_once "include/session.php" ;

	include_once "include/config.php" ;

	include_once "include/maininc.php" ;

	include_once "include/functions.php" ;

	$from	=	"info@thesocexchange.com";



	$date	=	mktime("0","0","0",date("m")+1,date("d"),date("Y"));

	

	$query	=	"select * from usa_buyblitz_bu_detail where renewalDate ='$date' AND bu_sent <> 1";
	$result		=	$dbcon->execute_query($query) ;
	$grid 		=	$dbcon->fetch_records() ;
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	/* additional headers */
	$headers .= "To: $to\r\n";
	$headers .= "From: $from\r\n";

	$subject	=	"Your business account expires";

	for($i=0;$i<sizeof($grid);$i++){

		$to	=	$grid[$i]['bu_email'];

		if($to=='')

		$to	=	$grid[$i]['bu_name'];

		$name	=	$grid[$i]['bu_name'];

		$message=<<<EOF

		<html>

		<head>

		<title>Expiry Alert</title>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

		<style type="text/css">

		<!--

		.style1 {color: #000066}

		-->

		</style>

		</head>

		

		<body>

		

		Dear $name,

		<p>Your website is about to expire in 30 days. To renew your service login at <a href=\"http://www.socexchange.com.au/\">www.socexchange.com.au</a> and select &lsquo;payment report&rsquo; in the left navigation. Click on &lsquo;renew my account&rsquo; to continue your service. </p>

		<p>Kind regards,</p>

		<p>The SOC Exchange Australia</p>

		</body>

		</html>

EOF;



	mail($to,$subject,$message,$headers);

	$query	=	"update usa_buyblitz_bu_detail set bu_sent=1  where StoreID=".$grid[$i]['StoreID'];

	$dbcon->execute_query($query);

	}



?>



