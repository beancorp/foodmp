<?php

ob_start();

include_once "include/session.php" ;
include_once "include/config.php" ;
include_once "include/maininc.php" ;
include_once "include/functions.php" ;
include_once "include/class/common.php";
include_once "include/class.soc.php";
include_once "include/class.guestEmailSubscriber.php";


$email	 	= 	$_REQUEST['email'];
$sid 		= 	$_REQUEST['sid'];
$StoreName	=	getStoreByName($sid);
$nickname       =       (isset($_REQUEST['nickname'])?$_REQUEST['nickname']:"");
$insertSuccess  =       false;
$msg            =       "";

$objSub = new guestEmailSubscriber();

If($_REQUEST['email'] && !empty($email)){

If (!emailcheck($email)){
	$msg = "Error: Your username is not a valid email address.";
}elseif(!empty($sid) && !empty($email) && !empty($nickname)) { 

        $count = $objSub->checkGuestSubscriberInStore($sid, $email);
        
	if( $count > 0 ){   
		$msg = "You are already subscribed to receive Email Alerts from $StoreName.";
                 $insertSuccess =false;
	}else { 
            $objSub->addGuestSubscriber($sid, $email, $nickname);
            $msg	=	"You have successfully subscribed to receive Email Alerts & Hot Buys from $StoreName.";
            $insertSuccess =true;
	}
}


} // if post
?>



<html>

<head><title>Subscribe to <?php echo $StoreName ?></title>

 <script language="javascript">
   function blank(form) {
	var msg = "" ;	
	var str = form.email.value;
        var nickstr = form.nickname.value;
    var re = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
    if (!str.match(re)) {   msg += "Verify your email address format.\n"; }
    if(nickstr=="") { msg += " Nickname is empty. \n"; }
		if(msg=="") { return true ; }
		else   { alert(msg) ; return false ; }
	}   
  </script>
<link href="skin/red/css/global.css" rel="stylesheet" type="text/css" />
<link href="skin/red/css/custom.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.STYLE1 {
	color: #FF0000;
	font-weight: bold;
}
-->
.reminder {
}

#guestSubscribeTable td input{
    border: 1px solid #CCCCCC;
	padding:5px;
	width:275px;
    
}

.guestSubmit{
    border:1px solid;
    background-color: #ffcacaa;
    cursor:pointer;
}


</style>
</head>

<body bgcolor="#FFFFFF">
<?php if(!$insertSuccess) { ?>    
    
  <center>
      <h1><strong style="font-size:16px;">Susbscribe</strong></h1>
      
      <p><span class="reminder">*</span> To Receive Specials and Hot Buys from <strong> <?php echo $StoreName?> </strong>, please enter your email address below. </p>
  </center>
 <center>
    
    <?php echo "<span style='color:red;font-weight:bold;'>".$msg."</span>"; ?> 
     
    <form name="guestSubscribeForm" method="post" action="" onSubmit="return blank(this)">
      <table id="guestSubscribeTable" width="500" border="0" align="center" cellspacing="10">
          <tr>
              <td width="156" align="right" class="text">Nickname * </td>
              <td width="200"><input type="inputBox" name="nickname" value=""></td>
        </tr>
        <tr>
            <td width="156">&nbsp;</td>
            <td width="200"><span class="reminder"> Create for yourself a nickname </span></td>
        </tr>
        
	<tr>
          <td width="156" align="right" class="text">Email Address * </td>
          <td width="200"><input type="inputBox" name="email" value=""></td>
        </tr>
        <tr>
            <td width="156">&nbsp;</td>
            <td width="200"><span class="reminder"> Enter your E-mail Address </span></td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
          <td colspan="2" width="350"><center>
              <input type="image" value="Send" src="skin/red/images/buttons/or-submit.gif" style="border:none; width:81px;">
          </center></td>
        </tr>
        
      </table>
    </form>
     </center>   
   <?php } else {  ?>  
     <center>
         <div style="width:220px;">
     <?php
		  if($insertSuccess){
                          echo "<p><span style='color:blue;font-weight:bold'>". $msg."</span><br><br>"; 
                      
			 echo ' <a href="javascript:window.close()">Close This Window</a>';
		  }
		 ?>
		 </div>     
     </center>
     <?php }  //outer IF ?>  
        
</body>
</html>