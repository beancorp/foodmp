<link type="text/css" href="/skin/red/css/foodwine.css" rel="stylesheet"/>
<script type="text/javascript">
var cp = "{$req.cp}";
var page = "{$req.page}";
{literal}
	$(document).ready(function() {
		setTimeout(function(){$("#msg_show").hide();},5000);
	})
{/literal}
</script>
{include file="../seller_home_rightmenu.tpl"}
<form id="orderform" name="orderform" action="" method="post">
<h1 class="soc-announcement">Your Announcements</h1>
<div class="clear"></div>
<div style="border-bottom:1px solid #CCCCCC; margin:0 0 10px;"></div>
<div class="announcement">
<p class="txt" id="msg_show" style="{if $req.msg eq ''}display: none;{/if}"><font style="color:red;">{$req.msg}</font></p>
    <table cellspacing="4" cellpadding="0">
		<tbody>
        <tr>
        	<td align="left">Title</td>
			<td><input type="text" class="inputB" id="title" name="title" style="width:400px;+width:400px;" value="{$req.info.title}" /></td>
			<td></td>
		</tr>		
		<tr>
        	<td align="left" style="width:30px;+width:30px;" valign="top">Text</td>
			<td>
      <div class="seller-setting-fieldset" style="width:412px;">
        <div class="seller-setting-wysiwyg" style="width:412px;+width:412px;*padding-bottom:0;">
        {$req.input.content}
        </div>
        <div class="seller-setting-wysiwyg-description">Allowed HTML tags: &lt;table&gt;, &lt;thead&gt;, &lt;tfoot&gt;, &lt;caption&gt;, &lt;tbody&gt;, &lt;tr&gt;, &lt;td&gt;, &lt;th&gt;, &lt;div&gt;, &lt;dl&gt;, &lt;dd&gt;, &lt;dt&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;, 
          &lt;pre&gt;, &lt;p&gt;, &lt;h[1-6]&gt;, &lt;hr&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;address&gt;, &lt;br&gt;.<br />All tags not allowed to use the attributes.</div>
        </div>
			</td>
			<td></td>
		</tr>
		
		<tr><td align="left" style="width:30px;+width:30px;">&nbsp;</td>
			<td align="right">
				<input type="image" style="padding:1px;" src="/skin/red/images/foodwine/post_announcement.jpg">
			</td>
			<td></td>
		</tr>
		
	</tbody></table>
</div>
<div class="clear"></div>
<input type="hidden" name="cp" value="save" />
<input type="hidden" name="aid" value="{$req.info.id}" />
<input type="hidden" name="StoreID" value="{$req.StoreID}" />
</form>