h{if $notfull neq '1'}
    {literal}
    <style type="text/css">
     .hittable th{
         background-color:#9E99C1;
         border-right:1px solid #FFF;
         color:#FFF;
         font-weight:bold;
         text-align:center;
     }
     .hittable td{
         border-right:1px solid #9E99C1;
         border-bottom:1px solid #9E99C1;
         text-align:center;
     }
     .hittable td.firsttd{
          border-left:1px solid #9E99C1;
     }
     .hittable th.endth{
         background-color:#9E99C1;
         border-right:1px solid #9E99C1;
     }
    </style>
    {/literal}
    {$req.xajax_Javascript}
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">

    {if $req.msg1 ne ''}
    <tr>

      <td height="28" colspan="2" align="center" valign="middle" class="headsadmin">&nbsp;</td>
    </tr>
    {/if}

    <tr>

      <td colspan="2" >					  </td>      
    </tr>

    <tr>

      <td colspan="2" align="right" >    				  </td>
    </tr>

    <tr>

      <td colspan="2" >
      {literal}
        <script>
        function del(id) {
            if(confirm('Do you want to delete the message')) { 
                window.location.href = 'soc.php?cp=business_get_step_home&msgid=' + id + '&action=del' 
            }  
        }	
        </script>
        {/literal}
        </td>
    </tr>
    <tr>
      <td height="46" align="center" valign="bottom" ><span class="voucherhead"><strong>Monthly Click Report</strong></span></td>
      <td align="center" valign="bottom" ><a href="soc.php?cp=sellerhome" target="_self">&lt;&lt;back to admin home </a></td>
    </tr>

    <tr>

      <td colspan="2" align="center" ></td>
    </tr>

    <tr>

      <td height="297" colspan="2" align="center"><table width="95%" border="0" cellpadding="1" cellspacing="0">
        <tr>
          <td height="15" colspan="4" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td width="39%" height="25" align="right">This  Month:<br /></td>
          <td width="15%" align="left">&nbsp;{$req.countThisMonth}</td>
          <td width="14%" align="right">Last Month:<br /></td>
          <td width="32%" align="left">&nbsp;{$req.countLastMonth}</td>
        </tr>
        <form action="" method="post">
          <tr>
            <td height="15" align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="right">Select:&nbsp;</td>
            <td>
            <select name="selectYear" class="select">
                <option value="{$req.currentYear}" {if $req.currentYear eq $req.cys.year}selected{/if}>{$req.currentYear}</option>
                <option value="{$req.lastYear}"  {if $req.lastYear eq $req.cys.year}selected{/if}>{$req.lastYear}</option>
              </select>                            
              </td>
            <td>&nbsp;<input type="image" src="skin/red/images/buttons/or-show.gif" name="Submit" value="Show" class="greenButt"/></td>
            <td>&nbsp;</td>
          </tr>
        </form>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
            <td height="90" colspan="4" align="center">
            <table width="98%" border="0" cellpadding="0" cellspacing="0" class="hittable" >
              <tr>
                <th width="50" height="23">Year</th>
                <th width="60">Jan</th>
                <th width="60">Feb</th>
                <th width="60">Mar</th>
                <th width="60">Apr</th>
                <th width="60">May</th>
                <th width="60">Jun</th>
                <th width="60">Jul</th>
                <th width="60">Aug</th>
                <th width="60">Sep</th>
                <th width="60">Oct</th>
                <th width="60">Nov</th>
                <th width="60">Dec</th>
                <th width="70" class="endth">Total</th>
              </tr>
              <tr>
                <td height="30" class="firsttd">{$req.cys.year}</td>
                <td>{if $req.cys.1}{$req.cys.1}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.2}{$req.cys.2}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.3}{$req.cys.3}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.4}{$req.cys.4}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.5}{$req.cys.5}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.6}{$req.cys.6}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.7}{$req.cys.7}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.8}{$req.cys.8}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.9}{$req.cys.9}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.10}{$req.cys.10}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.11}{$req.cys.11}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.12}{$req.cys.12}{else}&nbsp;{/if}</td>
                <td>{if $req.cys.total}{$req.cys.total}{else}&nbsp;{/if}</td>
              </tr>
          </table></td>
        </tr>
        
        <tr>
            <td align="left" style="padding-left:8px; font-weight:bold; height:25px;">Product Item Monthly Click Report</td>
        </tr>
        <tr>
    <td colspan="4" align="center" id="peritemtable">
{/if}
        <table width="98%" border="0" cellpadding="0" cellspacing="0" class="hittable">
                  <tr>
                    <th height="23" style="width:180px;">Item</th>
                    <th width="50">Jan</th>
                    <th width="50" >Feb</th>
                    <th width="50" >Mar</th>
                    <th width="50" >Apr</th>
                    <th width="50" >May</th>
                    <th width="50" >Jun</th>
                    <th width="50" >Jul</th>
                    <th width="50" >Aug</th>
                    <th width="50" >Sep</th>
                    <th width="50" >Oct</th>
                    <th width="50" >Nov</th>
                    <th width="50" >Dec</th>
                    <th width="60" class="endth">Total</th>
                  </tr>
                  {if $req.product}
                 {foreach from=$req.product.list item=pl}
                  <tr>
                    <td height="30" class="firsttd" style="width:180px;">{if $pl.name}{$pl.name}{else}&nbsp;{/if}</td>
                    <td>{if $pl.1}{$pl.1}{else}&nbsp;{/if}</td>
                    <td>{if $pl.2}{$pl.2}{else}&nbsp;{/if}</td>
                    <td>{if $pl.3}{$pl.3}{else}&nbsp;{/if}</td>
                    <td>{if $pl.4}{$pl.4}{else}&nbsp;{/if}</td>
                    <td>{if $pl.5}{$pl.5}{else}&nbsp;{/if}</td>
                    <td>{if $pl.6}{$pl.6}{else}&nbsp;{/if}</td>
                    <td>{if $pl.7}{$pl.7}{else}&nbsp;{/if}</td>
                    <td>{if $pl.8}{$pl.8}{else}&nbsp;{/if}</td>
                    <td>{if $pl.9}{$pl.9}{else}&nbsp;{/if}</td>
                    <td>{if $pl.10}{$pl.10}{else}&nbsp;{/if}</td>
                    <td>{if $pl.11}{$pl.11}{else}&nbsp;{/if}</td>
                    <td>{if $pl.12}{$pl.12}{else}&nbsp;{/if}</td>
                    <td>{if $pl.total}{$pl.total}{else}&nbsp;{/if}</td>
                  </tr>
                  {/foreach}
                  <tr>
                    <td height="30" colspan="14" style="border-left:1px solid #9E99C1;" align="center">
                    {$req.product.links.all}
                    </td>
                  </tr>
                  {else}
                  <tr>
                    <td align="center" height="30" colspan="14" bgcolor="#FFFFFF" style="border-left:1px solid #9E99C1;">No records.</td>
                  </tr>
                  {/if}
              </table>
{if $notfull neq '1'}                    
        </td>
        </tr>
          </table></td>
        </tr>
        </table>
{/if}
                    
                    
                    
                