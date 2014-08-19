<?php
ob_start();
include_once "include/session.php";
include_once "include/config.php";
include_once "include/maininc.php";
include_once "include/functions.php";
include_once "include/class/common.php";
include_once "include/class.soc.php";


$email = $_REQUEST['email'];
$LOGIN = trim($_SESSION['LOGIN']);
$sid = $_REQUEST['sid'];
$StoreName = getStoreByName($sid);
$uid = $_SESSION['UserID'];

if (empty($LOGIN)) {// || $LOGIN!="login" || $_SESSION['level']!=2)
    $disabled = "disabled";
    $msg = "You need to be a member of <span class=\"STYLE1\">SOC Exchange Australia</span> to use this service.
  <br>
  <span class=\"STYLE1\">It's FREE</span>. To register now <a href=\"soc.php?cp=customers_geton&ctm=1\" target=\"_blank\">Click Here</a>.";
} elseif (!emailcheck($email)) {
    $msg = "Error: Your username is not a valid email address.";
} elseif (!empty($sid) and !empty($email)) {
    $sql2 = "select email from " . $table . "emailalert where userid = '" . $uid . "' and storeid = '" . $sid . "'";
    $res = mysql_query($sql2);
    $row = mysql_num_rows($res);

    $query = "SELECT * FROM {$table}bu_detail WHERE StoreID = $sid";
    $dbcon->execute_query($query);
    $result = $dbcon->fetch_records(true);
    if ($result) {
        $result = $result[0];
    }
    if ($row > 0) {
        $msg = $result['attribute'] == 5 ? "You are already subscribed to receive Email Alerts & Hot Buy Alerts from $StoreName." : "You are already subscribed to the Newsletter from $StoreName.";
        $insert = 1;
    } else {
        $date = time();
        $sql = "insert into " . $table . "emailalert (storeid,email,userid,subscribe_date) values($sid,'$email',$uid,$date) ";
        //echo $sql;
        if ($dbcon->execute_query($sql)) {
            $msg = $result['attribute'] == 5 ? "You have successfully subscribed to receive Email Alerts & Hot Buys from $StoreName." : "You are successfully subscribed to the Newsletter $StoreName.";
            $insert = 1;

            if ($result['attribute'] == 5) {
                /**
                 * added by Kevin.Liu, 2012-02-16
                 * point new rule
                 */
                include_once(SOC_INCLUDE_PATH . '/class.point.php');
                $objPoint = new Point();
                $objPoint->addPointRecords($sid, 'subscriber', '');
                //END
            }
        } else {
            $msg = "Error:";
        }
    }
} else {
    $msg = "You have been successfully subscribed for email alerts from $StoreName.";
}
?>



<html>

    <head><title>Subscribe to the Newsletter</title>

        <script language="javascript">
            function blank(form) {
                var msg = "" ;	
                var str = form.email.value;
                var re = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
                if (!str.match(re)) {   msg += "Verify your email address format.\n"; }
                if(msg=="") { return true ; }
                else   { alert(msg) ; return false ; }
            }   
        </script>
        <link href="skin/red/css/global.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            <!--
            .STYLE1 {
                color: #FF0000;
                font-weight: bold;
            }
            -->
        </style>
    </head>

    <body bgcolor="#FFFFFF">
    <center>		
        <form name="form1" method="post" action="" onSubmit="return blank(this)">
            <table width="500" border="0" align="left">
                <tr>
                    <td width="190"><!-- Please Enter Your Email ID --> </td>
                    <td width="200"><input type="hidden" name="email" value="<?= $email1 ?>"></td>
                </tr>
                <tr>
                    <td colspan="2"><center>
                    <?php echo $msg; ?>
                </center></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <?php
                        if ($insert == 1) {
                            echo ' <a href="javascript:window.close()">Close This Window</a>';
                        }
                        ?>
                        <script>
                            function closePopup(){
                                window.opener.location.href('productDispay.php?StoreID=<?php echo $sid; ?>');
                                window.close() ;
                            }
                            function closePopupLogin(){
                                window.opener.location.href('customers_geton.php');
                                window.close() ;
                            }
                        </script>
                    </td>
                </tr>
            </table>
        </form>
    </center>   
</body>
</html>