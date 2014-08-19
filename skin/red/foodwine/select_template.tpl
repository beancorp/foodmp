{if $req.info.foodwine_type eq 'food'}
    <table width="720" cellpadding="0" cellspacing="0" id="choosetemplate">
        <tr>
            <td width="33%" align="center" valign="top">
                <h3 style="font-size:20px; color:#666; font-weight:bold;">A</h3>
                <a href="/skin/red/foodwine/images/foodwine-a.jpg" rel=lightbox  ><img src="/skin/red/foodwine/images/foodwine-a-s.jpg" border="0" /></a>
            </td>
            <td width="33%" align="center" valign="top">
                <h3 style="font-size:20px; color:#666; font-weight:bold;">B</h3>
                <a href="/skin/red/foodwine/images/foodwine-b.jpg" rel=lightbox  ><img src="/skin/red/foodwine/images/foodwine-b-s.jpg" alt="" border="0" /></a>
            </td>
            <td width="33%" align="center" valign="top">
                <h3 style="font-size:20px; color:#666; font-weight:bold;">C</h3>
                <a href="/skin/red/foodwine/images/foodwine-c.jpg" rel=lightbox  ><img src="/skin/red/foodwine/images/foodwine-c-s.jpg" border="0" /></a>
            </td>
            <td width="33%" align="center" valign="top">
                <h3 style="font-size:20px; color:#666; font-weight:bold;">D</h3>
                <a href="/skin/red/foodwine/images/foodwine-h.jpg" rel=lightbox  ><img src="/skin/red/foodwine/images/foodwine-h-s.jpg" border="0" /></a>
            </td>
        </tr>
        <tr>
        <td align="center">
            <p style="text-align:center;"><strong>Template A</strong></p>
        </td>
        <td align="center">
            <p style="text-align:center;"><strong>Template B</strong></p>
        </td>
        <td align="center">
            <p style="text-align:center;"><strong>Template C</strong></p>
        </td>
        <td align="center">
            <p style="text-align:center;"><strong>Template D</strong></p>
        </td>
        </tr>
        <tr>
              <td height="25" align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-a" {if $req.TemplateName eq '' or $req.TemplateName eq 'foodwine-a'}checked{/if} /></td>
              <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-b" {if $req.TemplateName eq 'foodwine-b'}checked{/if} /></td>
              <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-c" {if $req.TemplateName eq 'foodwine-c'}checked{/if} /></td>
              <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-h" {if $req.TemplateName eq 'foodwine-h'}checked{/if} /></td>
      </tr>
    </table>
{else}
<table width="720" cellpadding="0" cellspacing="0" id="choosetemplate">
        <tr>
            <td width="33%" align="center" valign="top">
                <h3 style="font-size:20px; color:#666; font-weight:bold;">A</h3>
                <a href="/skin/red/foodwine/images/foodwine-d.jpg" rel=lightbox  ><img src="/skin/red/foodwine/images/foodwine-d-s.jpg" border="0" /></a>
            </td>
            <td width="33%" align="center" valign="top">
                <h3 style="font-size:20px; color:#666; font-weight:bold;">B</h3>
                <a href="/skin/red/foodwine/images/foodwine-e.jpg" rel=lightbox  ><img src="/skin/red/foodwine/images/foodwine-e-s.jpg" border="0" /></a>
            </td>
            <td width="33%" align="center" valign="top">
                <h3 style="font-size:20px; color:#666; font-weight:bold;">C</h3>
                <a href="/skin/red/foodwine/images/foodwine-f.jpg" rel=lightbox  ><img src="/skin/red/foodwine/images/foodwine-f-s.jpg" alt="" border="0" /></a>
            </td>
            <td width="33%" align="center" valign="top">
                <h3 style="font-size:20px; color:#666; font-weight:bold;">D</h3>
                <a href="/skin/red/foodwine/images/foodwine-g.jpg" rel=lightbox  ><img src="/skin/red/foodwine/images/foodwine-g-s.jpg" border="0" /></a>
            </td>
        </tr>
        <tr>
        <td align="center">
            <p style="text-align:center;"><strong>Template A</strong></p>
        </td>
        <td align="center">
            <p style="text-align:center;"><strong>Template B</strong></p>
        </td>
        <td align="center">
            <p style="text-align:center;"><strong>Template C</strong></p>
        </td>
        <td align="center">
            <p style="text-align:center;"><strong>Template D</strong></p>
        </td>
        </tr>
        <tr>
              <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-d" {if $req.TemplateName eq 'foodwine-d'}checked{/if} /></td>
              <td height="25" align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-e" {if $req.TemplateName eq '' or $req.TemplateName eq 'foodwine-e'}checked{/if} /></td>
              <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-f" {if $req.TemplateName eq 'foodwine-f'}checked{/if} /></td>
              <td align="center"><input type="radio" name="TemplateName" id="TemplateName" value="foodwine-g" {if $req.TemplateName eq 'foodwine-g'}checked{/if} /></td>
      </tr>
    </table>
{/if}
