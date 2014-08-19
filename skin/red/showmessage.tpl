<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$pageTitle}</title>
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="/js/mdialog/mdialog.css" />
<script src="/skin/red/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="/js/mdialog/mdialog.js"></script>
{literal}
<script>
$(function() {
	{/literal}{if $req.msg}{literal}
	showEditForm();
	{/literal}{/if}{literal}
});
function showEditForm()
{
	var html = $("#dialog_html").html();
	dlgClose();
	dlgOpen('Error Message', html, 400, 150, false);
}
</script>
{/literal}
</head>
<body>
  <div id="dialog_html" style="display:none">
  <div style=" padding:5px 0 10px; color:#000; width:100%; ">
  <span style=" float:left;display:block; padding:5px;">
  <img src="/skin/red/images/logo-main.png" alt="Food Marketplace" width="60"/></span>
  <span>{$req.msg}</span>
  </div>
  <div class="clear"></div>
  <div style="text-align:right; padding:10px 0;*padding:0; width:100%; float:left">
	<input onclick="javascript:location.href='{if $req.reurl}{$req.reurl}{else}/soc.php?cp=home{/if}'" style="background-color:#FFF; cursor:pointer" type="button" value="OK" />
    </div>
  </div>
</body>
</html>