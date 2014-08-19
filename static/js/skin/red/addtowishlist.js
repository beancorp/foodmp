function checkaddtoWishlist(pid,StoreID,itemname){
	$('#tmpid').val(pid);
	$('#tmpitem').html(itemname);
	$.post('/include/jquery_svr.php',{svr:'checkwishItem',pid:pid,StoreID:StoreID},function(data){
		if(data=="0"){
			$('#confirmForm').floatdiv('middle');
			$('#confirmForm').css('display','');
		}else{
			$('#ckmsg').html('');
			$('#wishlist_renew').val('');
			$('#renameForm').floatdiv('middle');
			$('#renameForm').css('display','');
			
		}
	});
	
}
function submitnewname(pid,StoreID){
	var newname = $('#wishlist_renew').val();
	if(newname==""){
		$('#ckmsg').html("Item Name is required.");
	}else{
		$.post('/include/jquery_svr.php',{svr:'checkwishItem',pid:pid,StoreID:StoreID,name:newname},function(data){
			if(data=="0"){
				$('#vhidname').val(newname);
				$('#confirmForm').css('display','');
				$('#confirmForm').floatdiv('middle');
				$('#renameForm').css('display','none');
			}else{
				$('#ckmsg').html("Item Name is existed.");
			}
		});
	}
}
function hideform(hidf){
	$('#'+hidf).css('display','none');
}
function submittolink(pid,StoreID,Seller){
	var newname = $('#vhidname').val();
	$.post('/include/jquery_svr.php',{svr:'copytowish',pid:pid,StoreID:StoreID,name:newname},function(data){
		if(data=="1"){
			$('#returncomment').html("Item added to my wishlist from "+Seller);
		}else{
			$('#returncomment').html('Faild to add to your wishlist, please refresh the page.');						
		}
		$('#confirmForm').css('display','none');
	});
}