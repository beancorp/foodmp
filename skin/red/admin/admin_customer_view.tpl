<ul id="table" style="width:720px;">
<li id="lable" style="text-align:right">{$lang.main.lb_cus_name}</li>
<li id="input" style="width:600px;text-align:left">{$req.list.bu_name}</li>

<li id="lable" style="text-align:right">{$lang.main.lb_cus_nickname}</li>
<li id="input" style="width:600px;text-align:left">{$req.list.bu_nickname}</li>

<li id="lable" style="text-align:right">{$lang.main.lb_cus_email}</li>
<li id="input" style="width:600px;text-align:left">{$req.list.bu_email}</li>

<li id="lable" style="text-align:right">{$lang.main.lb_cus_phone}</li>
<li id="input" style="width:600px;text-align:left">{$req.list.bu_phone}</li>

<li id="lable" style="text-align:right">{$lang.main.lb_cus_state}</li>
<li id="input" style="width:600px;text-align:left">{$req.list.bu_state}</li>

<li id="lable" style="text-align:right">{$lang.main.lb_cus_suburb}</li>
<li id="input" style="width:600px;text-align:left">{$req.list.bu_suburb}</li>

<li id="lable" style="text-align:right">{$lang.main.lb_cus_zipcode}</li>
<li id="input" style="width:600px;text-align:left">{$req.list.bu_postcode}</li>
<li id="lable" style="text-align:right"> </li>
<li id="input" style="width:600px;text-align:left">
  <input name="back" type="button" class="hbutton" id="back" value="{$lang.but.back}" onClick="javascript:xajax_customerGetList($('#pageno').val(),'tabledatalist',xajax.getFormValues('mainForm'))">
</li>
</ul>
