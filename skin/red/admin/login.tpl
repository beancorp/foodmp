<HTML>
<HEAD>
<TITLE>{$req.pageTitle}</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{$req.xajax_Javascript}
<link href="/skin/red/admin/css/global.css" rel="stylesheet" type="text/css" media="screen">
<link href="/skin/red/admin/css/login.css" rel="stylesheet" type="text/css" media="screen">
</HEAD>

<BODY>
<div id="loadingMessage" style="width:560px;margin:0 auto;text-align:left;float:right; display: none;clear:both;">
<img src="/skin/red/images/xajax-loading.gif" alt="" title="" /> loading...
</div> 
<div id="body-top"><IMG src="/skin/red/admin/images/header_text.jpg" alt="Logo" width="306" height="38"></div>

<div id="ctr" align=center>
	<div class="login">
	  
	<div class="login-form"><IMG src="/skin/red/admin/images/login.gif" alt=Login width="74" height="33"> 
	<form id="mainForm" name="mainForm" action="" onSubmit="javascript:xajax_userLogin(xajax.getFormValues('mainForm'));return false;"  method="post">
		<div class="form-block">
			<div class="inputlabel">{$lang.login.lb_username}</div>
			<div><INPUT name="user" style="width:185px;" maxlength="50" value="{$req.username}"></div>
			<div class="inputlabel">{$lang.login.lb_password}</div>
			<div><INPUT name="pass" type="password" style="width:185px;" maxlength="60" value="{$req.password}"></div>
			<div class="inputlabel">Validation Code</div>
			<div><INPUT name="vdcode" type="text" value="" style="float:left;width:60px; margin-top:2px; +margin-top:1px;" maxlength="4" >&nbsp;<img src="/authimg.php" border="0" /></div>
			<div align="left" style="margin-top:3px;">	<INPUT class="button" type="submit" value=" {$lang.login.lb_login} " name="submitButton" id="submitButton">
			</div>
		</div>
	</form>
	</div>
	
	<div class=login-text>
		<div class=ctr style="height:33px;"></div>
		<P>Welcome..</P>
		<P>Use a valid username and password to gain access to the administration console.</P>
	</div>
	
	<div class=clr></div>
	
	<div id="ajaxmessage"></div>
	</div>
</div>

<div>
<NOSCRIPT>!Warning! Javascript must be enabled for proper operation of the Administrator </NOSCRIPT>
</div>

</BODY>
</HTML>