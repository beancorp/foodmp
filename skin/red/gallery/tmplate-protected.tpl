<link type="text/css" href="/skin/red/css/wishlist.css" rel="stylesheet" media="screen" />
{literal}
<script language="javascript">
	function checkpassword(){
		var SID = $('#StoreID_Wish').val();
		var PWD = $('#password_wish').val();
		var URL = $('#StoreID_url').val();
		if(PWD==""){
			alert("Password is required.");
			return false;
		}else{
			jQuery.post("/include/jquery_svr.php",
						{ svr:'check_GalleryLogin',StoreID: SID, PWD: PWD, URL: URL },
						function(data){
							if(data=='0'){
								alert("Password is invalid.");
							}else{
								location.reload();
							}
									
						});
		}
		return false;
	}
</script>
{/literal}
<div id="wishlist_content" style="height:563px;">
<div style=" margin:0;padding-top:120px; padding-left:260px; -moz-box-sizing:border-box;" align="left">
	<form action="" method="post" onsubmit="return checkpassword();"/>
    <table cellpadding="0" cellspacing="5" >
    	<tr>
        	<td width="65">Password:</td>
            <td><input id="password_wish" type="password" name="password" value="" class="inputB" style="width:100px;"/>
            	<input type="hidden" id="StoreID_Wish" name="StoreID" value="{$gallerydetail.StoreID}"/>
            	<input type="hidden" id="StoreID_url" name="url" value="{$gallerydetail.gallery_url}"/>
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