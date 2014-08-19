"use strict"
var lastBase64 = "";
var validatingCode = false;
var onErrorShowing = false;
var imageData = {};
/**
 *
 * HTML5 Image uploader with Jcrop
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2012, Script Tutorials
 * http://www.script-tutorials.com/
 * 
 */

// convert bytes into friendly format
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

window.code_valid = true;
function codeMessage(message) {
	$('#entry_code').validationEngine('showPrompt', message, 'red', null, true);
}

function retailerMessage(message) {
	$('#entry_code').validationEngine('showPrompt', message, 'red', null, true);
	
}

// check for selected crop region
$(document).ready(function(){
    $('#entry_code').on("blur", function(){
        check_code_valid();
    })
});

function check_code_valid(){
    setTimeout(function(){
        window.code_valid = false;
        $('#processing_btn_upload').show();
        var entry_code = $('#entry_code').val();
        var retailer_id = $('#retailer_id').val();
        var photo_id = $('#photo_id').val();
        validatingCode = true;
        $.ajax({
            async: false,
            type: "POST",
            url: "/fanpromo/codes.php",
            dataType: "json",
            data: { 'code': entry_code, 'retailer_id': retailer_id, 'photo_id': photo_id, 'action': "checkCode"}
        }).done(function( d ) {
            validatingCode = false;
            if (d.rs) {
                window.code_valid = true;
                window.code_error_message = "";
                $('#entry_code').validationEngine('hide');
            } else {
                //error message or whatever
                if (d.error_code == "code")
                    codeMessage(d.message);
                if (d.error_code == "retailer"){
                    retailerMessage(d.message);
                }
                window.code_valid = false;
                window.code_error_message = "";
                return false;
            }
        });
    }, 200)
}

// update info by cropping (onChange and onSelect events handler)
function updateInfo(e) {
    $('#x1').val(e.x/imageData.ns);
    $('#y1').val(e.y/imageData.ns);
    $('#x2').val(e.x2/imageData.ns);
    $('#y2').val(e.y2/imageData.ns);
    $('#w').val(618);
    $('#h').val(441);
};

// clear info by cropping (onRelease event handler)
function clearInfo() {
    $('.info #w').val('');
    $('.info #h').val('');
};

// Create variables (in this scope) to hold the Jcrop API and image size
var jcrop_api, boundx, boundy;

function fillData(){
    $('#button-upload').validationEngine('hide');
    onErrorShowing = false;
    if ($('#file').val()) {
        var $filenames = $('#file').val().split('\\');
        var $filename = $filenames[$filenames.length-1];
        $('#text_box_1').val($filename);
    } else {
        $('#text_box_1').val("");
        $("#preview").attr("src", "");
        $("#preview").attr("width", "");
        $("#preview").attr("height", "");
    }
}

function loadImageInfo(url){
    var xmlhttp;
    // compatible with IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200){            
            data = $.parseJSON(xmlhttp.responseText);
            // showKey(data);
            if (data.error) {
                window.imageInfo = data;
                var json = "/fanpromo/ie_upload/"+window.imageInfo.new_name+".json";
                $("#ie_upload_info").val(json);
            }
        }
    }
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function showKey(obj) {
    for(var key in obj) {
        console.log (key + ':' + obj[key]);
    }
}

function showImage() {
    // display step 2
    $('.preview_upload_fanfrenzy').fadeIn(500);
    setTimeout(function(){
        $("#upload_flag").val("1");
        if ($("#submit_flag").val() == "1") {
            validateBeforeSubmit();
        }
    }, 100);

    // display some basic image info
    var sResultFileSize = bytesToSize(imageData.size);
    $('#filesize').val(sResultFileSize);
    $('#filetype').val(imageData.type);
    $('#filedim').val(imageData.w + ' x ' + imageData.h);
    $('#w').val(imageData.w);
    $('#h').val(imageData.h);
    $('#x1').val("");
    $('#y1').val("");
    $('#x2').val("");
    $('#y2').val("");
    // destroy Jcrop if it is existed
    if (jcrop_api) {
        if (typeof jcrop_api.destroy === 'function') {
            jcrop_api.destroy();
            jcrop_api = null;
            var imgW = imageData.w*imageData.ns;
            var imgH = imageData.h*imageData.ns;
            $('#preview').width(imgW);
            $('#preview').height(imgH);
        }
    }

    var cropWidth;
    if (imageData.w < 618){
        cropWidth = imageData.w;
    }else{
        cropWidth = 618;
    }
    
    var cropHeight;
    if (imageData.h < 441){
        cropHeight = imageData.h; 
    }else{
        cropHeight = 441;
    }

    cropWidth *= imageData.ns;
    cropHeight *= imageData.ns;

    jCrop(cropWidth, cropHeight);
    var i = 0;
    var jcropItv = setInterval(function(){
        if (window.imageInfo.width != cropWidth && window.imageInfo.height != cropHeight) {
            jCrop(cropWidth, cropHeight);
            clearInterval(jcropItv);
        }
        i++;
        if (i == 10) {
            clearInterval(jcropItv);
        }
    }, 1000)
}

function jCrop(cropWidth, cropHeight) {
    $('#preview').Jcrop({
        minSize: [cropWidth, cropHeight], // min crop size
        maxSize: [cropWidth, cropHeight],
        //aspectRatio : 1, // keep aspect ratio 1:1
        bgFade: true, // use fade effect
        bgOpacity: .3, // fade opacity
        onChange: updateInfo,
        onSelect: updateInfo,
        onRelease: clearInfo
    }, function(){

        // use the Jcrop API to get the real image size
        var bounds = this.getBounds();
        boundx = bounds[0];
        boundy = bounds[1];

        // Store the Jcrop API in the jcrop_api variable
        jcrop_api = this;
    });
}

function fileSelectHandler(error) {
    if (!error && window.ie9) {
        $(".loading").show();
    }
    $("#upload_flag").val("0");
    $('.preview_upload_fanfrenzy').hide();
    $("#preview").attr("src", "");
    $("#w").val("");
    $("#h").val("");
    $("#filedim").val("");
    $("#filetype").val("");
    $("#filesize").val("");
    if ($("#text_box_1").val()) {
    	//check for IE9
    	if(!window.ie9){
            // get selected file
            var oFile = $('#file')[0].files[0];
            
            if (typeof oFile  == "undefined"){
            	$('#button-upload').validationEngine('showPrompt', '* Please select a valid image file (gif, jpeg, jpg, pjpeg, x-png and png are allowed)', 'red', null, true);
            	return false;
            }

            // hide all errors
            $('.error').hide();

            // check for image type (gif, jpeg, jpg, pjpeg, x-png and png are allowed)
            var rFilter = /^(image\/jpeg|image\/gif|image\/jpg|image\/x-png|image\/png|image\/pjpeg|)$/i;
            if (! rFilter.test(oFile.type)) {
                $('#button-upload').validationEngine('showPrompt', '* Please select a valid image file (gif, jpeg, jpg, pjpeg, x-png and png are allowed)', 'red', null, true);
                return;
            }

            // check for file size
            
            if (oFile.size > 1048576) {
            	$('#button-upload').validationEngine('showPrompt', '* Your file must be less than 1MB, please select smaller one', 'red', null, true);
        //    $('.error').html('Your file must be less than 1MB, please select smaller one').show();
                return;
            }
        }
        else
        {
            if (!window.ie9Uploaded){
                setTimeout( function(){
                    fileSelectHandler(true);
                }, 1000);
            } else {
                // showKey(window.imageInfo)
                // hide all errors
                $('.error').hide();

                // check for image type (gif, jpeg, jpg, pjpeg, x-png and png are allowed)
                var rFilter = /^(image\/jpeg|image\/gif|image\/jpg|image\/x-png|image\/png|image\/pjpeg|)$/i;
                if (window.imageInfo.type) {
                    if (! rFilter.test(window.imageInfo.type)) {
                        $('#button-upload').validationEngine('showPrompt', '* Please select a valid image file (gif, jpeg, jpg, pjpeg, x-png and png are allowed)', 'red', null, true);
                        return;
                    }

                    // check for file size
                    
                    if (window.imageInfo.size > 1048576) {
                        $('#button-upload').validationEngine('showPrompt', '* Your file must be less than 1MB, please select smaller one', 'red', null, true);
                //    $('.error').html('Your file must be less than 1MB, please select smaller one').show();
                        return;
                    }
                }
            }
        }
        
        if(!window.ie9){
            // preview element
            var oImage = document.getElementById('preview');
            // prepare HTML5 FileReader
            var oReader = new FileReader();
            oReader.onload = function(e) {
                // e.target.result contains the DataURL which we can use as a source of the image
                oImage.src = e.target.result;
                oImage.onload = function () { // onload event handler
                    var nWidth = oImage.naturalWidth;
                    var nHeight = oImage.naturalHeight;
                    if (oImage.naturalWidth > 860) {
                        nWidth = 860;
                        nHeight = 860*oImage.naturalHeight/oImage.naturalWidth;
                    }
                    imageData = {
                        w: oImage.naturalWidth,
                        h: oImage.naturalHeight,
                        type: oFile.type,
                        size: oFile.size,
                        ns: nWidth/oImage.naturalWidth
                    }
                    oImage.width = nWidth;
                    oImage.height = nHeight;
                    if (imageData.w < 618 || imageData.h < 441){
                        $('#button-upload').validationEngine('showPrompt', '*Your image must be 618 x 441', 'red', null, true);
                    } else {
                        showImage();
                    }
                };
            };

            // read selected file as DataURL
            oReader.readAsDataURL(oFile);
        } else {
            if (parseInt(window.imageInfo.error) === 1) {
                var itv = setInterval(function(){
                    if (window.imageInfo.base64 !== $("#preview").attr("src")) {
                        lastBase64 = window.imageInfo.base64;
                        var nWidth = window.imageInfo.width;
                        var nHeight = window.imageInfo.height;
                        if (window.imageInfo.width > 860) {
                            nWidth = 860;
                            nHeight = 860*window.imageInfo.height/window.imageInfo.width;
                        }
                        imageData = {
                            w: window.imageInfo.width,
                            h: window.imageInfo.height,
                            type: window.imageInfo.type,
                            size: window.imageInfo.size,
                            ns: nWidth/window.imageInfo.width
                        }
                        if (imageData.w < 618 || imageData.h < 441){
                            $('#button-upload').validationEngine('showPrompt', '*Your image must be 618 x 441', 'red', null, true);
                            $(".loading").hide();
                            clearInterval(itv);
                        } else {
                            if (imageData.w) {
                                $("#preview").attr("src", window.imageInfo.base64);
                                $("#preview").attr("width", nWidth);
                                $("#preview").attr("height", nHeight);
                                $(".loading").hide();
                                showImage();
                                clearInterval(itv);
                                $('#button-upload').validationEngine('hide');
                                onErrorShowing = false;
                            }
                        }
                    }
                }, 1000);
            } else if (parseInt(window.imageInfo.error) === 2){
                if (!onErrorShowing) {
                    $('#button-upload').validationEngine('showPrompt', '* Your file must be less than 1MB, please select smaller one', 'red', null, true);
                    onErrorShowing = true;
                }
                $(".loading").hide();
            } else if (parseInt(window.imageInfo.error) === 3){
                if (!onErrorShowing) {
                    $('#button-upload').validationEngine('showPrompt', '* Please select a valid image file (gif, jpeg, jpg, pjpeg, x-png and png are allowed)', 'red', null, true);
                    onErrorShowing = true;
                }
                
                $(".loading").hide();
            }
        }
    } else {
        $('#button-upload').validationEngine('showPrompt', '* Please select a valid image file (gif, jpeg, jpg, pjpeg, x-png and png are allowed)', 'red', null, true);
    }
}

function validateBeforeSubmit(){
    var $return = true;
    var submitItv = setInterval(function(){
        if (!validatingCode) {
            $("#submit_flag").val("1");  
            var photo_id = $('#photo_id').val();
            if (photo_id == "0"){
                if (!$("#text_box_1").val()) {
                    $('#text_box_1').validationEngine('showPrompt', '* This field is required', 'red', null, true);
                    $return = false;
                } else if ($("#upload_flag").val() !== "1" && $("#text_box_1").val()) {     
                    $('#text_box_1').validationEngine('hide');
                    $('#button-upload').validationEngine('showPrompt', '* Please press the Upload button', 'red', null, true);
                    $return = false;
                } else {
                    $('#text_box_1').validationEngine('hide');
                    var imgWidth = $('#w').val();
                    var imgHeight = $('#h').val();
                    if (imgWidth < 618 || imgHeight < 441){
                        $('#button-upload').validationEngine('showPrompt', '* Your image must be 618 x 441', 'red', null, true);
                        $return = false;
                    } else if (imgWidth > 618 || imgHeight > 441){
                        $('#button-upload').validationEngine('showPrompt', '* Please select a crop region', 'red', null, true);
                        $return = false;
                    } else {
                        $('#button-upload').validationEngine('hide');
                        onErrorShowing = false;
                    }
                }
            }
            if (!$("#retailer_name").val()) {
                $('#retailer_name').validationEngine('showPrompt', '* This field is required', 'red', null, true);
                $return = false;
            }
            if (!$("#category_id").val()) {
                $('#category_id').validationEngine('showPrompt', '* This field is required', 'red', null, true);
                $return = false;
            }
            if (!$("#state_id").val()) {
                $('#state_id').validationEngine('showPrompt', '* This field is required', 'red', null, true);
                $return = false;
            }
            if (!$("#entry_description").val()) {
                $('#entry_description').validationEngine('showPrompt', '* This field is required', 'red', null, true);
                $return = false;
            }
            if (!$("#tc_checkbox").prop("checked")) {
                $('#tc_checkbox').validationEngine('showPrompt', '* This field is required', 'red', null, true);
                $return = false;
            }
            if (!$("#photo_checkbox").prop("checked")) {
                $('#photo_checkbox').validationEngine('showPrompt', '* This field is required', 'red', null, true);
                $return = false;
            }

            clearInterval(submitItv);
            if ($return && window.code_valid) $("#entry_form").submit();
        }
    }, 100)
}