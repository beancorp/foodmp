// JavaScript Document
// $(document).ready(function(){
// 	$('#block-gallery-bt').click(function(){
//       $('#tab-nav > li').removeClass('active');
//       $(this).addClass('active');
//       $('.tab-gallery').removeClass('active');
//       $('.block-gallery').addClass('active');
//     });

//     $('#my-gallery-bt').click(function(){
//       $('#tab-nav > li').removeClass('active');
//       $(this).addClass('active');
//       $('.tab-gallery').removeClass('active');
//       $('.my-gallery').addClass('active');
//     });	
// });
	
function delconfirm(obj){
	if(confirm('Are you sure to delete this '+obj +"?")){
		return true;
	}else{
		return false;
	}
}
function checkImageForm(){
	var errors = "";
    var uploadType = $("input:checked[name='uploadtype']")[0];
    if(uploadType.value == 'bluk'){
        if($('#bulkimg').val() == ''){
            errors += "-	The images is required.\n";
        }
    }else{
        if($('#gallery_thumbs').val()==""){
            errors += "-	The image is required.\n";
        }
    }

    if(errors!=""){
        errors = 'Sorry, the following fields are required.\n'+errors;
        alert(errors);
        return false;
    }else{
        return true;
    }
}
function checkGalleryForm(){
	var errors = "";
	if($('#gallery_category').val()==""){
		errors += "-	The Gallery Name is required.\n";
	}
	if($('#galpwd').val()==""){
		errors += "-	The Gallery Password is required.\n";
	}else{
		if($('#galpwd').val()!=$('#galpwd2').val()){
			errors += "-	The Gallery Password you have entered did not match.\n";
		}
	}
	if(errors!=""){
		errors = 'Sorry, the following fields are required.\n'+errors;
		alert(errors);
		return false;
	}else{
		return true;
	}
}

function addgalleryuser(){
	var newval = $('#recivers').val();
	if(newval==""){
		alert('The Recipient is required.');
	}else{
		if(!ifEmail(newval)){
			alert('The Recipient email is invalid.');
			return false;
		}
		if(!checkexitsuser(newval)){
			alert("The Recipient is duplicated.");
			return false;
		}
		$('#userlist').append("<option value=\""+newval+"\">"+newval+"</option>");
	}
}

function checkexitsuser(newval){
	try{
		var usrlist = $('#userlist option');
		for(var i=0;i<usrlist.length;i++){
			if(newval==$(usrlist[i]).val()){
				return false;
			}
		}
	}catch(ex){}
	return true;
}
function loaduserlist(storeid){
	var usrlist = $('#invationlist').val();
/*	if(!invationlist){
		alert("Please select a event.");
		return false;
	}
	var usrlist = "";
	for(var i=0; i<invationlist.length; i++){
		if(usrlist==""){
			usrlist += invationlist[i];
		}else{
			usrlist += ","+invationlist[i];
		}
	}*/

	$.getJSON('/include/jquery_svr.php',{svr:'loadinvationUser',invations:usrlist,StoreID:storeid},function(data){
		$.each(data, function(i,item){
			if(checkexitsuser(item['invitation_email'])){
				$('#userlist').append("<option value=\""+item['invitation_email']+"\">"+item['invitation_email']+"</option>");
			}
		});
	});
}

function loadCSVUser(storeid){
	$.getJSON('/include/jquery_svr.php',{svr:'loadCSVUser',StoreID:storeid},function(data){
		$.each(data, function(i,item){
			if(checkexitsuser(item['emailAddress'])){
				$('#userlist').append("<option value=\""+item['emailAddress']+"\">"+item['emailAddress']+"</option>");
			}
		});
	});
}

function removeuser(){
	$('#userlist option[@selected]').remove();
}

function checkeventSend(){
	$('#userlist option').attr("selected","true");
	var errors = "";
	if($('#subject').val()==""){
		errors += "-	The Email Subject is required.\n";
	}
	
	if(!$('#userlist').val()){
		errors += "-	The Recipient is required.\n";
	}
	
	if($('#emailcontent').val()==""){
		errors += "-	The Email Content is required.\n";
	}

	if(errors!=""){
		errors = 'Sorry, the following fields are required.\n'+errors;
		alert(errors);
		return false;
	}else{
		return true;
	}
}
function ifEmail(str,allowNull){
	if(str.length==0) return allowNull;
	i=str.indexOf("@");
	j=str.lastIndexOf(".");
	if (i == -1 || j == -1 || i > j) return false;
	return true;
}

function changetab(tab,obj){
	$('.tabtmp').children().removeClass("active_tab");
	obj.className = "active_tab";
	$('#xmas_tab').css('display','none');
	$('#birthday_tab').css('display','none');
	$('#college_tab').css('display','none');
	$('#general_tab').css('display','none');
	$('#custom_tab').css('display','none');
	$('#wedding_tab').css('display','none');
	$('#'+tab).css('display','');
	switch(tab){
		case 'xmas_tab':
			if(xmas_s==0){
				$('#xmas_1').attr('checked',true);
			}else{
				$('#xmas_'+xmas_s).attr('checked',true);
			}
			break;
		case 'birthday_tab':
			if(birthday_s==0){
				$('#birthday_13').attr('checked',true);
			}else{
				$('#birthday_'+birthday_s).attr('checked',true);
			}
			break;
		case 'college_tab':
			if(college_s==0){
				$('#college_16').attr('checked',true);
			}else{
				$('#college_'+college_s).attr('checked',true);
			}
			break;
		case 'general_tab':
			if(general_s==0){
				$('#general_27').attr('checked',true);
			}else{
				$('#general_'+general_s).attr('checked',true);
			}
			break;
		case 'wedding_tab':
			if(wedding_s==0){
				$('#wedding_85').attr('checked',true);
			}else{
				$('#wedding_'+wedding_s).attr('checked',true);
			}
			break;
		case 'custom_tab':
			$('#custom_1').attr('checked',true);
			if($('#custom_banner_s').val()){
				$('#'+$('#custom_banner_s').val()).attr('checked',true);
			}else{
				 $('#cus_bid_1').attr('checked',true);
			}
			break;
			
	}
}
function deletefile(file,StoreID){
	if(confirm('Are you sure to delete this file?')){
		$('#filelist').html('');
		$('#file_music').val('');
		$('#music_name').val('');
	}
	return void(0);
}
function textcontrol(obj){
	if($(obj).val().length>180){
		$(obj).val($(obj).val().substr(0,180));
	}
}

function checkGalleryName(storeid,obj,id){
	var name = $(obj).val();
	if($.trim(name)!=""){
		$.post('/include/jquery_svr.php',{svr:'checkgalleryname',StoreID:storeid,name:name,id:id},function(data){
			if(data!=""){
				$('#msg').html(data);
				$('#msg').css('color','red');
				$(obj).val("");
			}else{
				$('#msg').html("Gallery Name is availabled.");
				$('#msg').css('color','green');
			}
		});
	}else{
		$('#msg').html("Gallery Name is empty.");
		$('#msg').css('color','red');
	}
}