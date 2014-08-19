<h3 class="content">{$req.jokeinfo.title}</h3>
<div id="helpcontent">{$req.jokeinfo.body|nl2br}</div>
<div style=" margin-top:30px;width:530px; text-align:center">
<h3 class="content">Send to Your Friends</h3>
<div style="color:#F00; margin-bottom:10px;">{$req.msg}</div>
	<form action="" method="post" onsubmit="return checkEmailForm(this);">
   
    <div style="margin:0;padding:0; width:537px;text-align:center;">
        <div style="margin:0;padding:0; width:537px;">
        <img src="/skin/red/images/step1_top.jpg" border="0"  style="float:left"/>
		<div style="clear:both;"></div>
	</div>
    	<div style="padding:0;margin:0;width:537px;background: rgb(241, 241, 241); height: 35px; text-align:center;">
         <div align="center">
         <table >
             <tr>
                <td align="right">Your Name:*</td>
                <td align="left"><input type="text" class="inputB" name="nickname" value="{$smarty.session.NickName}"/></td>
            </tr>
        </table>
        </div>
        </div>
        <img src="/skin/red/images/step1_bottom.jpg"/><br/><br/>
	</div>
    <div align="center">
    <table cellpadding="0" cellspacing="4" width="297" align="center" style="text-align:center; margin:0;">
				<tr><td  style=" width:17px;background:#9E99C1; height:23px; color:#FFFFFF;font-weight:bold;" align="center">#</td><td style=" width:280px; background:#9E99C1;height:23px;color:#FFFFFF; font-weight:bold;" align="center">Email</td></tr>
    {section name=foo start=0 loop=5 step=1}
    <tr>
    	<td>{$smarty.section.foo.index+1}.</td>
        <td align="left"><input type="text" class="inputB" id="email{$smarty.section.foo.index+1}" name="Email[]" /></td>
    </tr>
    {/section}
    <tr>
    	<td></td>
    	<td>
        <table cellspacing="0" cellpadding="0" width="100%">
        	<tr>
            <td><input type="text" class="inputB" name="vc_code" style="width:120px;" /></td>
            <td><span style="background:url(authimg.php) no-repeat center center;float:left; width:66px; height:27px; "></span></td>
            <td><input name="imageField" type="image" src="skin/red/images/buttons/or-submit.gif" border="0" style="float:left;" /></td>
            </tr>
        </table>
        </td>
    </tr>
    </table>
    </div>
    <input type="hidden" name="sendemail"  value="1"/>
    </form>
</div>
<div id="paging-wide" style=" margin-top:10px;width:530px;">
&nbsp;<a href="/soc.php?cp=jokes">&lt;&lt;Back</a>
</div>