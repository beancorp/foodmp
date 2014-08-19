<SCRIPT LANGUAGE="JavaScript">

function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=410,height=300');");
}

function popUpImage(URL, width, height) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open('/showimg.php?img=' + URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width="+width+",height="+height+"');");
}

function popSliding(StoreID, Pid, imageURL){
	height	=	608;
	// if($.browser.msie && $.browser.version == '6.0'){
		// height	=	616;
	// }else if($.browser.mozilla){
		// height = 618;
	// }
	if(imageURL != '/images/79x79.jpg' && imageURL != '/images/243x212.jpg'){
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open('/soc.php?cp=slidingshow&StoreID=' + StoreID + '&pid=' + Pid +'&url=' + imageURL , '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=720,height='+height);");
	}else{
		void(0);
	}
}

function popwishSliding(StoreID, Pid, imageURL){
	height	=	608;
	// if($.browser.msie && $.browser.version == '6.0'){
		// height	=	616;
	// }else if($.browser.mozilla){
		// height = 618;
	// }
	if(imageURL != '/images/79x79.jpg' && imageURL != '/images/243x212.jpg'){
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open('/soc.php?cp=sliding2show&StoreID=' + StoreID + '&pid=' + Pid +'&url=' + imageURL , '" + id + "','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=720,height='+height);");
	}else{
		void(0);
	}
}


function deletes(url,target){
	if (confirm("Are you sure you want to delete?")){
		window.location.target	=	"_self";
		window.location.href	=	url;
	}
}

function continueNextStep(){
	var clewString	=	"IMPORTANT: \nAny unsaved changes will be lost. You must click on 'Save to my Website' to save your changes. Continue?";
	if (confirm(clewString)){
		return true;
	}else{
		return false;
	}
}

function other(url,target){
	window.location.target	=	"_self";
	window.location.href	=	url;
}

function popPrompt(msg){
	if( msg != "")
	{
		alert (msg);
	}
}

function popNewOffer(StoreID, pid){
	window.open('/soc.php?act=offer&StoreID='+StoreID+'&pid='+pid, '_blank', 'top=100,left=100,height=545,width=580,scrollbars=no,status=yes');
	//void('0');
}

</script>