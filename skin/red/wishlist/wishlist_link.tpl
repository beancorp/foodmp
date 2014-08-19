<script src="/skin/red/js/jquery.floatDiv.js"></script>
<script src="/skin/red/js/addtowishlist.js"></script>
    <div>
	<div style="color:#F00;{if $isline eq 'yes'}display:none;{/if}" id="returncomment"></div>
    <input type="hidden" value="0" id="tmpid"/>
	<div id="renameForm" style="border:1px solid #ccc; position:absolute; z-index:10002;  height:250px; background:#FFF; width:500px; text-align:center; display:none;">
        <div style="margin-top:40px; color:#F00;width:100%;">The Item Name(<b id="tmpitem"></b>) is already in your wishlist, please rename it.</div>
        <div style="padding-top:40px;width:100%;">New Name: <input id="wishlist_renew" type="text" name="newname" class="inputB" value=""/></div>
        <div id="ckmsg" style="color:#F00; margin-top:10px;width:100%;"></div>
        <div style="margin-top:20px;padding-left:180px;"><img src="/skin/red/images/no.gif" onclick="hideform('renameForm');" style="cursor:pointer;float:left;margin-right:10px;"/> <img src="/skin/red/images/yes.gif" onclick="submitnewname($('#tmpid').val(),'{$smarty.session.ShopID}');" style="cursor:pointer;float:left;"/><div style="clear:both"></div>
        </div>
    </div>
        
    <div id="confirmForm" style="border:1px solid #ccc; position:absolute; z-index:10002; height:250px; background:#FFF; width:500px; text-align:center; display:none;">
        <div style="margin-top:80px;">Would you like to add this item to your wishlist?</div>
        <div id="ckmsg" style="color:#F00; padding-top:10px;"></div>
        <div style="margin-top:20px;padding-left:180px;"><img src="/skin/red/images/no.gif"  onclick="hideform('confirmForm');" style="cursor:pointer;float:left;margin-right:10px;"/> <img src="/skin/red/images/yes.gif" onclick="submittolink($('#tmpid').val(),'{$smarty.session.ShopID}','{$req.info.bu_name|escape:"quotes"|escape:"and"}');" style="cursor:pointer;float:left;"/><div style="clear:both"></div></div>
        <input type="hidden" id="vhidname" value=""/>
        </div>
    </div>