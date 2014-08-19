<link href="css/global.css" rel="stylesheet" type="text/css">
{if $saved}
	The food report has been saved <br />
{/if}
<div id="list_input">
<form action="index.php?act=main&cp=freshreport" id="mainForm" name="mainForm" method="post">
<ul id="input-table">
<li><span class="publc_clew" style="font-size:12px; font-weight:normal;">{$lang.EditerClew}</span><br />
</li>
<li>{$req.input.content}</li>
</ul>
<div align="center"><input name="title" type="hidden" id="title" value="{$req.input.title}">
  <input name="id" type="hidden" id="id" value="{$req.input.id}">
  <input style="float:left; margin-left:300px;" type="submit" class="hbutton" id="submitButton" name="submitButton" value=" {$lang.but.save} ">
  <div style="float: left; padding-left: 10px;" id="reminder"><input type="checkbox" value="1" name="sendreminder">Send a reminder</div>
</div>
</form>
</div>
