// JavaScript Document

function displayUploadInterface(obj, isDisplay){
	$('#'+obj).css('display',isDisplay ? '' : 'none'); 
}

function uploadImage(tpltype, attrib, index, objImage){
	window.open('/uploadImageSingle.php?tpltype='+tpltype+'&attrib='+attrib+'&index='+index+'&objImage='+objImage,'addimage',	'width=700,height=150,statusbars=yes,status=yes');
	
}

function deleteImage(tpltype, attrib, index, objImage){
	var tempSImage = $('#'+objImage+'_svalue').val();
	if (tempSImage == '' || tempSImage == '/images/79x79.jpg' || tempSImage == '/images/243x212.jpg' || tempSImage == '/images/243x100.jpg' || tempSImage == '/images/72x100.jpg' || tempSImage == '/images/250x34.jpg' || tempSImage == '/images/250x17.jpg' || tempSImage == '/images/250x57.jpg' || tempSImage == '/images/50x50.jpg' || tempSImage == '/images/243x212.jpg' || tempSImage == '/images/172x127.jpg' || tempSImage == '/images/282x195.jpg'){
		
		alert ("Sorry, you haven't uploaded yet.");
		
	} else if (confirm("Are you sure you want to delete?")){
		//alert($('#'+objImage+'_svalue').val());
		$.post("/uploadImageSingle.php" ,
		{ cp: "delete", simage:$('#'+objImage+'_svalue').val(), bimage:$('#'+objImage+'_bvalue').val(), vtpltype: tpltype, vattrib: attrib, vindex : index } ,
		function(value, status)
		{
			
			if (value != ''){
				
				var arrTemp	= value.split('|');
				if(typeof (arrTemp)	==	'object'){
					var arrTemp1	=	arrTemp[0].split(',');
					var arrTemp2	=	arrTemp[1].split(',');
					
					$('#'+objImage+'_svalue').val(arrTemp1[0]);
					$('#'+objImage+'_bvalue').val(arrTemp2[0]);
					
					$('#'+objImage+'_dis').attr ( { 'src':arrTemp1[0] ,width : arrTemp1[1], height : arrTemp1[2]});
				}
			}
		}
		);
	}
}

function checkImageForm(obj){
	RegExp.multiline=true;
	try{
		var errors	=	'';
		if(obj.upfiles.value==''){
			errors += 'Image is required.';
		}
		
		if(errors!=''){
			//errors = 'Sorry, the fields are required.'+errors;
			$('#message').html(errors);
			return false;
		}else{
			return true;
		}
		
	}catch(ex){alert(ex);}
}



function displayUploadSellerForms(obj, value){
	for (i=0 ; i< obj.length; i++ ){
		var subObj = obj.get(i).value;
		
		if (subObj == value){
			$('#dis'+ subObj).css('display','');
		}else{
			$('#dis'+ subObj).css('display','none');
		}
	}
}


function displayUploadSellerFormsBind(objHTML){
	
	$("input[name="+objHTML+"]").click( function(){
		displayUploadSellerForms( $("input[name="+objHTML+"]"), $("input[name="+objHTML+"]:checked").val() ); 
	});
	
	if (document.all){
		window.attachEvent('onload',function(){displayUploadSellerForms( $("input[name="+objHTML+"]"), $("input[name="+objHTML+"]:checked").val() );});
	}
	else{
		window.addEventListener('load',function(){displayUploadSellerForms( $("input[name="+objHTML+"]"), $("input[name="+objHTML+"]:checked").val() );},false);
	}
}
