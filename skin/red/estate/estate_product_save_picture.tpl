<link type="text/css" href="/skin/red/css/global.css" rel="stylesheet" media="screen" />
<script src="/skin/red/js/uploadImages.js" language="javascript"></script>
<table width="225">
        <tr valign="top">
          <td colspan="3"><span class="lbl">
		  <a id="swf_upload_1" style="float:left;" href="javascript:uploadImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/bu-uploadimages-sm.gif" border="0" /></a>&nbsp;&nbsp;| <a href="javascript:deleteImage(0, 0, 0, 'mainImage' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a>
          </span><span class="style11"><font face="Verdana" size="1"><a  class="help" href="#"><img src="/skin/red/images/icon-question.gif" width="21" height="20" border="0" align="top" /><span><span style="color:#777;width:90px;">Click on the 'upload an image' button, in the pop-up window click 'browse' and go to the location on your computer where the image is saved, then 'upload'.</span></span></a></font></span></td>
        </tr>
		
		<tr><td colspan="3">
		<table width="250" cellpadding="0" cellspacing="0" border="0">
			<tr>
          		<td height="225" colspan="3" align="center"><img src="{$req.images.mainImage.0.sname.text}" name="mainImage_dis" border="1" id="mainImage_dis" width="250" height="250" /></td>
        	</tr>
			<tr><td colspan="3" height="10"><img src="images/spacer.gif" width="1" height="1" /></td>
			<tr onmousemove="//displayUploadInterface('uploadImagesSub1',true)">
				<td align="center"><img src="{$req.images.subImage.0.sname.text}" width="79" height="79" name="subImage0_dis" border="1" id="subImage0_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.1.sname.text}" width="79" height="79" name="subImage1_dis" border="1" id="subImage1_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.2.sname.text}" width="79" height="79" name="subImage2_dis" border="1" id="subImage2_dis" /></td>
			</tr>
			<tr id="uploadImagesSub1" onmouseout="//displayUploadInterface('uploadImagesSub1',false)">
				<td align="center"><a id="swf_upload_2" href="javascript:uploadImage(0, 1, 0, 'subImage0' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 0, 'subImage0' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_3" href="javascript:uploadImage(0, 1, 1, 'subImage1' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 1, 'subImage1' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_4" href="javascript:uploadImage(0, 1, 2, 'subImage2' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 2, 'subImage2' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
			</tr>
			<tr><td colspan="3" height="10"><img src="images/spacer.gif" width="1" height="1" /></td>
			</tr>
			<tr onmousemove="//displayUploadInterface('uploadImagesSub2',true);">
				<td align="center"><img src="{$req.images.subImage.3.sname.text}" width="79" height="79" name="subImage3_dis" border="1" id="subImage3_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.4.sname.text}" width="79" height="79" name="subImage4_dis" border="1" id="subImage4_dis" /></td>
				<td align="center"><img src="{$req.images.subImage.5.sname.text}" width="79" height="79" name="subImage5_dis" border="1" id="subImage5_dis" /></td>
			</tr>
			<tr id="uploadImagesSub2" onmouseout="//displayUploadInterface('uploadImagesSub2',false);">
				<td align="center"><a id="swf_upload_5" href="javascript:uploadImage(0, 1, 3, 'subImage3' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 3, 'subImage3' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_6" href="javascript:uploadImage(0, 1, 4, 'subImage4' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 4, 'subImage4' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
				<td align="center"><a id="swf_upload_7" href="javascript:uploadImage(0, 1, 5, 'subImage5' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 1, 5, 'subImage5' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
			</tr>
		</table></td>
		</tr>
        <tr valign="top">
          <td height="286" colspan="3" valign="middle">
          <table width="250" height="268" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="28" class="purpleTitle">Plan Picture </td>
              </tr>
              <tr>
                <td height="99"><table width="32%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center"><img src="{$req.images.planImage.0.sname.text}" width="79" height="79" name="planImage0_dis" border="1" id="planImage0_dis" /></td>
                  </tr>
                  <tr>
                    <td align="center"><a id="swf_upload_8" href="javascript:uploadImage(0, 2, 0, 'planImage0' );void(0);">Upload</a> | <a href="javascript:deleteImage(0, 2, 0, 'planImage0' );void(0);"><img src="/skin/red/images/icon-deletes.gif" alt="Delete" title="Delete" align="absmiddle" /></a></td>
                  </tr>
                </table>
                </td>
              </tr>
              <tr>
                <td width="258" height="158"><input name="mainImage_svalue" id="mainImage_svalue" type="hidden" value="{$req.images.mainImage.0.sname.text}"/>
			<input name="mainImage_bvalue" id="mainImage_bvalue" type="hidden" value="{$req.images.mainImage.0.bname.text}"/>
			
			<input name="subImage0_svalue" id="subImage0_svalue" type="hidden" value="{$req.images.subImage.0.sname.text}"/>
			<input name="subImage0_bvalue" id="subImage0_bvalue" type="hidden" value="{$req.images.subImage.0.bname.text}"/>
			
			<input name="subImage1_svalue" id="subImage1_svalue" type="hidden" value="{$req.images.subImage.1.sname.text}"/>
			<input name="subImage1_bvalue" id="subImage1_bvalue" type="hidden" value="{$req.images.subImage.1.bname.text}"/>
			
			<input name="subImage2_svalue" id="subImage2_svalue" type="hidden" value="{$req.images.subImage.2.sname.text}"/>
			<input name="subImage2_bvalue" id="subImage2_bvalue" type="hidden" value="{$req.images.subImage.2.bname.text}"/>
			
			<input name="subImage3_svalue" id="subImage3_svalue" type="hidden" value="{$req.images.subImage.3.sname.text}"/>
			<input name="subImage3_bvalue" id="subImage3_bvalue" type="hidden" value="{$req.images.subImage.3.bname.text}"/>
			
			<input name="subImage4_svalue" id="subImage4_svalue" type="hidden" value="{$req.images.subImage.4.sname.text}"/>
			<input name="subImage4_bvalue" id="subImage4_bvalue" type="hidden" value="{$req.images.subImage.4.bname.text}"/>
			
			<input name="subImage5_svalue" id="subImage5_svalue" type="hidden" value="{$req.images.subImage.5.sname.text}"/>
			<input name="subImage5_bvalue" id="subImage5_bvalue" type="hidden" value="{$req.images.subImage.5.bname.text}"/>
			
			<input name="planImage0_svalue" id="planImage0_svalue" type="hidden" value="{$req.images.planImage.0.sname.text}"/>
			<input name="planImage0_bvalue" id="planImage0_bvalue" type="hidden" value="{$req.images.planImage.0.bname.text}"/>
			<br /><br /> 
            <!--
            {if $req.info.product_feetype eq 'year' && $req.info.product_renewal_date >= $cur_time}
            <input name="SubmitPic" type="image" src="/skin/red/images/bu-savetowebsite-sm.gif" class="input-none-border" onclick="javascript:document.mainForm.op.value='edit';" value="Save to My Website" border="0"/>
            {else}
            	{if !empty($req.select.pid) && $req.select.pay_status eq '1'}
            	<input name="SubmitPic" type="image" src="/skin/red/images/bu-savetowebsite-sm.gif" class="input-none-border" onclick="javascript:document.mainForm.op.value='edit';" value="Save to My Website" border="0"/><br />      
                <p style="margin:0; padding-left:5px; font-family:Arial; font-size:16px; font-weight:bold; color:red;">Expired Date: {$req.select.renewal_date|date_format:"$PBDateFormat"}</p><br /> 
                <input name="SubmitPic" type="image" src="/skin/red/images/bu-renew.gif" class="input-none-border" onclick="javascript:document.mainForm.op.value='renewnow';" value="Renew" border="0"/>
                {else}
		    	<input name="SubmitPic" type="image" src="/skin/red/images/bu-paylater.gif" class="input-none-border" onclick="javascript:document.mainForm.op.value='paylater';" value="Save & Pay Later" border="0"/><br /> <br /> 
                <input name="SubmitPic" type="image" src="/skin/red/images/bu-paynow.gif" class="input-none-border" onclick="javascript:document.mainForm.op.value='paynow';" value="Pay Now" border="0"/>
                {/if}
            {/if}
            
		    <p style="margin:0; padding-left:5px; font-family:Arial; font-size:16px; font-weight:bold; color:red;">Click after you update every item</p>-->
			</td>
              </tr>
          </table></td>
        </tr>
        <tr valign="top" id="gallaryImage">
          <td colspan="3">&nbsp;</td>
        </tr>
		</table>