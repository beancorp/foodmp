<link type="text/css" href="/skin/red/css/wishlist.css" rel="stylesheet" media="screen" />
<script language="javascript">var lcurl = "/{$headerInfo.url_bu_name}/wishlist"</script>
{literal}
<script language="javascript">
	function checkpassword(){
		var SID = $('#StoreID_Wish').val();
		var PWD = $('#password_wish').val();
		if(PWD==""){
			alert("Password is required.");
			return false;
		}else{
			jQuery.post("/include/jquery_svr.php",
						{ svr:'check_WishLogin',StoreID: SID, PWD: PWD },
						function(data){
							if(data=='0'){
								alert("Password is invalid.");
							}else{
								location.href= lcurl;
							}
									
						});
		}
		return false;
	}
</script>
{/literal}
<div id="wishlist_content" style="height:563px;">
<div style=" margin:0;padding-top:120px;  -moz-box-sizing:border-box;" align="center">
	<form action="" method="post" onsubmit="return checkpassword();"/>
    <table cellpadding="0" cellspacing="5" >
    	<tr><td colspan="2" align="center" style="font-size:13px;">Please verify your Wishlist password</td></tr>
    </table>
    <table cellpadding="0" cellspacing="5">
    	<tr>
        	<td width="65">Password:</td>
            <td><input id="password_wish" type="password" name="password" value="" class="inputB" style="width:100px;"/>
            	<input type="hidden" id="StoreID_Wish" name="StoreID" value="{$wishinfodetail.StoreID}"/>
            </td>
        </tr>

        <tr>
        	<td width="65"></td>
            <td align="right"><input type="image" src="/skin/red/images/buttons/bu-submit.png" value="Submit"/></td>
        </tr>
    </table>
    </form>
</div>
</div>