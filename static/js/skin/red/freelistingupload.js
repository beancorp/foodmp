// JavaScript Document
$(function() {	
		$("#swf_upload_1").makeAsyncUploader({
				upload_url: soc_http_host+"uploadproduct_img.php?objImage=mainImage&tpltype="+protype+"&attrib=0&index=0",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/buttons/upload_image.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.jpg;*.gif;*.png',
				file_size_limit:'10MB',
				file_types_description:'All images',
				button_text:"",
				button_window_mode:"transparent",
				height:"25",
				debug:false
		 });
		$("#swf_upload_2").makeAsyncUploader({
				upload_url: soc_http_host+"uploadproduct_img.php?objImage=subImage0&tpltype="+protype+"&attrib=1&index=0",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/buttons/upload_samll.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.jpg;*.gif;*.png',
				file_size_limit:'10MB',
				file_types_description:'All images',
				button_text:"",
				button_window_mode:"transparent",
				height:"15",
				width:"39",
				debug:false
		 });
		$("#swf_upload_3").makeAsyncUploader({
				upload_url: soc_http_host+"uploadproduct_img.php?objImage=subImage1&tpltype="+protype+"&attrib=1&index=1",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/buttons/upload_samll.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.jpg;*.gif;*.png',
				file_size_limit:'10MB',
				file_types_description:'All images',
				button_text:"",
				button_window_mode:"transparent",
				height:"15",
				width:"39",
				debug:false
		 });
		$("#swf_upload_4").makeAsyncUploader({
				upload_url: soc_http_host+"uploadproduct_img.php?objImage=subImage2&tpltype="+protype+"&attrib=1&index=2",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/buttons/upload_samll.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.jpg;*.gif;*.png',
				file_size_limit:'10MB',
				file_types_description:'All images',
				button_text:"",
				button_window_mode:"transparent",
				height:"15",
				width:"39",
				debug:false
		 });
		$("#swf_upload_5").makeAsyncUploader({
				upload_url: soc_http_host+"uploadproduct_img.php?objImage=subImage3&tpltype="+protype+"&attrib=1&index=3",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/buttons/upload_samll.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.jpg;*.gif;*.png',
				file_size_limit:'10MB',
				file_types_description:'All images',
				button_text:"",
				button_window_mode:"transparent",
				height:"15",
				width:"39",
				debug:false
		 });
		$("#swf_upload_6").makeAsyncUploader({
				upload_url: soc_http_host+"uploadproduct_img.php?objImage=subImage4&tpltype="+protype+"&attrib=1&index=4",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/buttons/upload_samll.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.jpg;*.gif;*.png',
				file_size_limit:'10MB',
				file_types_description:'All images',
				button_text:"",
				button_window_mode:"transparent",
				height:"15",
				width:"39",
				debug:false
		 });
		$("#swf_upload_7").makeAsyncUploader({
				upload_url: soc_http_host+"uploadproduct_img.php?objImage=subImage5&tpltype="+protype+"&attrib=1&index=5",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/buttons/upload_samll.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.jpg;*.gif;*.png',
				file_size_limit:'10MB',
				file_types_description:'All images',
				button_text:"",
				button_window_mode:"transparent",
				height:"15",
				width:"39",
				debug:false
		 });
		$("#swf_upload_8").makeAsyncUploader({
				upload_url: soc_http_host+"uploadproduct_img.php?objImage=planImage0&tpltype="+protype+"&attrib=2&index=0",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/buttons/upload_samll.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.jpg;*.gif;*.png',
				file_size_limit:'10MB',
				file_types_description:'All images',
				button_text:"",
				button_window_mode:"transparent",
				height:"15",
				width:"39",
				debug:false
		 });
		
		$("#csv").makeAsyncUploader({
				upload_url: soc_http_host+"upload_listing.php?type=csvmsg",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/blankButton.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.csv',
				file_size_limit:'10MB',
				file_types_description:'Only CSV',
				button_text:"",
				button_window_mode:"transparent",
				height:"29",
				debug:false
		 });
		$("#image").makeAsyncUploader({
				upload_url: soc_http_host+"upload_listing.php?type=imgmsg",
				flash_url: soc_http_host + 'skin/red/js/swfupload.swf',
				button_image_url: '/skin/red/images/blankButton.png',
				disableDuringUpload: 'INPUT[type="submit"]',
				file_types:'*.zip',
				file_size_limit:'70MB',
				file_types_description:'Only ZIP',
				button_text:"",
				button_window_mode:"transparent",
				height:"29",
				debug:false
		 });
});
function uploadresponse(response){
	var aryResult = response.split('|');
	if(aryResult.length>3){
		var objRes = aryResult[0];
		var imgobj = $("#"+objRes+"_dis");
		$(imgobj).attr('src',"/"+aryResult[1]);
		$(imgobj).css('width',aryResult[2]);
		$(imgobj).css('height',aryResult[3]);
		$("#"+objRes+"_svalue").val("/"+aryResult[4]);
		$("#"+objRes+"_bvalue").val("/"+$.trim(aryResult[5]));
	}else if(aryResult.length==3){
		$("#"+aryResult[0]).html(aryResult[2]);
		$("#swf_"+aryResult[0]).val(aryResult[1]);		
	}
}
function uploadprocess(bl){
	/*if(!bl){
		$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save_grey.gif)');
	}else{
		$('INPUT[type="submit"]').css('background','url(/skin/red/images/buttons/bu_save.gif)');
	}*/
}