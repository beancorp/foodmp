{literal}
<style type="text/css">
	ul.mainlist {list-style:none; margin:0; width:750px; background:#CCC; float:left; }
	ul.mainlist li{padding:0;margin:0; float:left; width:100%; }
	
	ul.listhead { list-style:none; margin:0; padding:0; width:100%; float:left; height:34px;}
	ul.listhead li{ padding:0; background:#CCC;border-left:1px solid #CCC;  color:#FFFFFF; font-weight:bold; text-align:center;height:33px;line-height:33px;}
	ul.listhead li{ margin:0 0 1px 0px ; float:left;width:120px;}
	ul.listhead li.detail{width:264px;}
	
	ul.list { height:31px;
			  list-style:none; margin:0; padding:0; width:100%; float:left;}
	
	ul.list li{float:left;width:120px;height:30px;line-height:30px;margin:0 0 0px 0px;
		border-left:1px solid #CCC; border-bottom:1px solid #CCC; text-align:center;}
	ul.list li{ padding:0; background:#ffffff;}
	ul.list li.detail{width:264px;}

	ul.mainlist li.pagelist{ border-left:1px solid #FFFFFF;
			border-bottom:1px solid #ffffff;
			border-right:1px solid #ffffff;
			background:#FFFFFF;
			width:750px;
			height:22px;
			line-height:22px;
			text-align:center;
    }
</style>
{/literal}
{$req.xajax_Javascript}
<div align="center">
<div id="refcontent" style="width:750px;">
{include file="invitations/invitations_list.tpl"}
</div>
</div>
<div style="height:40px;"></div>