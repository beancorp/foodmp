<HTML>
<HEAD>
<TITLE>{$pageTitle}</TITLE>
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="{$smarty.const.STATIC_URL}/css/skin/red/ui-lightness/jquery-ui.min.css" rel="stylesheet">

<script language="javascript" src="{$smarty.const.STATIC_URL}js/skin/red/jquery-1.10.2.min.js"></script>
<script language="javascript" src="{$smarty.const.STATIC_URL}js/skin/red/jquery.js"></script>



<script language="javascript" src="{$smarty.const.STATIC_URL}js/skin/red/jquery-ui-1.10.3.custom.min.js"></script>
<script language="javascript" src="{$smarty.const.STATIC_URL}js/control.js"></script>


{$req.xajax_Javascript}
<link href="/skin/red/admin/css/global.css" rel="stylesheet" type="text/css" media="screen">
</HEAD>

<BODY>
<DIV id="body-top"><IMG src="/skin/red/admin/images/header_text.jpg" alt="Logo"></DIV>
<div id="body-menu">{include file="left_menu.tpl"}</div>
<div id="body-content">
<div id="body-control-title" align="center">{$req.header}</div>
{$content}
</div>
</BODY>
</HTML>