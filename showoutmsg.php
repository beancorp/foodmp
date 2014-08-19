<?

include_once "include/session.php" ;

include_once "include/config.php" ;

include_once "include/maininc.php" ;

include_once "include/functions.php" ;



$msgid = $_REQUEST['msgid'] ;

$sqlr = "select subject,message,StoreID,Status,emailaddress,fromtoname from ".$GLOBALS['table']."message_out where messageID = $msgid" ;
$dbcon->execute_query($sqlr) ;

$grid = $dbcon->fetch_records() ;

?>

<title>Message</title>

<body>

<div style="background-color:#FCF5F8">

<b>To</b>&nbsp;&nbsp;&nbsp;<?=nl2br($grid[0]['fromtoname'])?>

<br>

<!--<b>Email Address</b>&nbsp;&nbsp;&nbsp;<?=nl2br($grid[0]['emailaddress'])?>

<br>-->

<b>Subject</b>&nbsp;&nbsp;&nbsp;<?=nl2br($grid[0]['subject'])?>

<br><BR>

<b>Message</b>

<br>

<?=nl2br($grid[0]['message'])?>

</div>

<!--<BR><BR>

<b>Reply to this message:</b><BR>

<form method="post" action="replytomsg.php"> 

	<input type="hidden" name="emailaddress" value="<?=nl2br($grid[0]['emailaddress'])?>" >

	<input type="hidden" name="subject" value="<?=nl2br($grid[0]['subject'])?>" >

	<input type="hidden" name="fromtoname" value="<?=nl2br($grid[0]['fromtoname'])?>" >

	<input type="hidden" name="StoreID" value="<?=nl2br($grid[0]['StoreID'])?>" >

	<textarea name="body" rows="5" cols="70" id="body"></textarea><BR>

	<input type="submit" value="Reply"/>

	

	

</form>-->



</body>



