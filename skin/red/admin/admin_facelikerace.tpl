<link href="/css/global.css" rel="stylesheet" type="text/css">
<link href="/skin/red/css/blog.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/mdialog/mdialog.css" />
<script language="javascript">
	var protype =0;
	var soc_http_host="{$soc_http_host}";
</script>
<script type="text/javascript" src="/skin/red/js/swfupload.js"></script>
<script type="text/javascript" src="/skin/red/js/jquery-asyncUpload-0.1.js"></script>
<script type="text/javascript" src="/skin/red/js/productupload.js"></script>
<link type="text/css" href="/skin/red/css/swfupload_product.css" rel="stylesheet" media="screen" />
{literal}
<style type="text/css">
#asyncUploader_image{
display:none;
}
</style>
{/literal}
<script type="text/javascript">
var protype =0;
var soc_http_host="{$soc_http_host}";
</script>
<div align="center" style="border-bottom-color:#999999;">
	<div id="ajaxmessage" class="publc_clew" style="height:50px; text-align:center">{$req.input.title}</div>
	<form id="new_form" name="new_form" method="post" action="" onSubmit="javascript:xajax_saveFacelikeRaceInfo(xajax.getFormValues('new_form')) ; return false;">
	<div id="input-table" style="width:720px;">

	<ul>
	<li id="lable" style="width:45%;">{$lang.main.lb_facelikerace_round}</li>
	<li id="input2" style="width:53%;"><input name="round" id="round" type="text" size="30" maxlength="30" style="width:172px;" value="{$req.info.round}"></li>
	<li id="lable" style="width:45%;">{$lang.main.lb_facelikerace_startdate}</li>
	<li id="input2" style="width:53%;">
    <input type="text"  style="width:80px;+height:21px;+padding:2px 0 0 0;" id="start_date" name="start_date" maxlength="12" value="{$req.info.start_date|date_format:"$PBDateFormat"}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.new_form.start_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>&nbsp;
    	<select name="s_hour" style="width:46px;">
        	{foreach from=$hour item=h key=k}
                <option value="{$k}" {if $req.info.s_hour eq $k}selected{/if}>{$h}</option>
            {/foreach}
        </select>
    </li>
	<li id="lable" style="width:45%;">{$lang.main.lb_facelikerace_enddate}</li>
	<li id="input2" style="width:53%;">
    
    <input type="text"  style="width:80px;+height:21px;+padding:2px 0 0 0;" id="end_date" name="end_date" maxlength="12" value="{$req.info.end_date|date_format:"$PBDateFormat"}" readonly="true"/> <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.new_form.end_date);return false;" hidefocus="HIDEFOCUS"><img align="absmiddle" src="/include/cal/calbtn.gif" width="34" height="22" border="0" /></a>&nbsp;
    	<select name="e_hour" style="width:46px;">
            {foreach from=$hour item=h key=k}
                <option value="{$k}" {if $req.info.e_hour eq $k}selected{/if}>{$h}</option>
            {/foreach}
        </select>
    </li>
	<li id="lable" style="width:45%;">{$lang.main.lb_facelikerace_description}</li>
	<li id="input2" style="width:53%; height:90px;"><textarea style="width: 250px; height: 80px;" id="description" name="description">{$req.info.description}</textarea></li>
	<li id="lable" style="width:45%; height:260px;">{$lang.main.lb_facelikerace_prize}</li>
	<li id="input2" style="width:50%; height:283px;">
    <fieldset id="uploadimages" style="display:block; border:none;">
  <script src="/skin/red/js/uploadImages.js" language="javascript"></script>
  <div style="+width:220px; height:260px; border:none;">
    <table width="225">
      <tr valign="top" height="30">
        <td colspan="3"><span class="lbl"> <a id="swf_upload_1" style="float:left;" href="javascript:uploadImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" /></a>&nbsp;&nbsp;| <a href="javascript:deleteImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a> </span><span class="style11"><font face="Verdana" size="1"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;width:110px;">Click on the 'upload an image' button, in the pop-up window click 'browse' and go to the location on your computer where the image is saved, then 'upload'.</span></span></a></font></span></td>
      </tr>
      <tr>
        <td colspan="3"><table width="250" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td colspan="3" align="left"><img src="{if $req.info.image}{ $req.info.image}{else}/images/243x212.jpg{/if}" name="image" border="1" id="mainImage_dis" width="243" /></td>
            </tr>
            
          </table></td>
      </tr>
      <tr valign="top">
        <td valign="middle" colspan="3"><table width="250" height="35" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="258" height="35">
                <p>&nbsp;</p>
                <input name="mainImage_svalue" id="mainImage_svalue" type="hidden" value="{if $req.info.image}{ $req.info.image}{else}/images/243x212.jpg{/if}"/>
                <input name="image" id="mainImage_bvalue" type="hidden" value="{if $req.info.image}{ $req.info.image}{else}/images/243x212.jpg{/if}"/>
                </td>
            </tr>
          </table></td>
      </tr>
    </table>
  </div>
  </fieldset>
    </li>
    <div class="clear"></div>
	<li id="lable" style="width:45%;"></li>
	<li id="input" style="width:53%;">
		<input type="submit" class="hbutton" name="submitButton" value=" {$lang.but.submit} ">
	</li>
	</ul>
</div>
</form>
</div>

<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="/include/cal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>