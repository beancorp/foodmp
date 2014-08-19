<div id="ajaxmessage" class="publc_clew" style="text-align:center;">{$req.title}</div>

{$req.js.xajaxInt}	

{literal}
<script type="text/javascript">
<!--//
function selectAll()
{
	var obj = document.getElementsByName('messageId[]');
	
	for (var i = 0; i < obj.length; i++)
	{
		obj[i].checked = !obj[i].checked;
	}
}
//-->
</script>
{/literal}

<div id="admin_msg"></div>
