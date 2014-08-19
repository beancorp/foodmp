function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    return false
		 }

 		 return true					
	}
function checkForm(){
	var curdate 	= new Date()
	var curmonth 	= curdate.getMonth()
	var curyear 	= curdate.getYear()
	var emailID=document.personalDetail.email;

	var	errors	=	'';
	if(document.personalDetail.firstName.value==''){
		errors += '-  First Name is required.\n';
	}
	if(document.personalDetail.lastName.value==''){
		errors += '-  Last Name is required.\n';
	}
	if(document.personalDetail.cardType.value==''){
		errors += '-  Card Type is required.\n';
	}
	if(document.personalDetail.cardNumber.value==''){
		errors += '-  Card number is required.\n';
	}
	if(isNaN(document.personalDetail.cardNumber.value)){
		errors += '-  Card number is invalid.\n';
	}
	if(document.personalDetail.month.value==''){
		errors += '-  Card expiry month is required.\n';
	}
	if((document.personalDetail.month.value<curmonth && document.personalDetail.year.value<=curyear) || (document.personalDetail.month.value>curmonth && document.personalDetail.year.value<curyear)){
		errors += '-  Card expiry date is not valid.\n';
	}
	else if(document.personalDetail.month.value<curmonth && document.personalDetail.year.value<curyear){
		errors += '-  Card expiry date is not valid.\n';
	}
	if(document.personalDetail.year.value==''){
		errors += '-  Card expiry year is required.\n';
	}
	if(document.personalDetail.address1.value==''){
		errors += '-  Address1 is required.\n';
	}
	if(document.personalDetail.city.value==''){
		errors += '-  City is required.\n';
	}
	if(document.personalDetail.state.value==''){
		errors += '-  State is required.\n';
	}
	if(document.personalDetail.postcode.value==''){
		errors += '-  Postcode is required.\n';
	}
	if (echeck(emailID.value)==false){
		errors += '- Email address is not valid.\n';
	}
	
	if(errors!=''){
		errors = '-  Sorry, The following fields are required.\n'+errors;
		alert(errors);
		return false;
	}
	return true;
	
}