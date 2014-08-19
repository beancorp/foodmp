// JavaScript Document
function chk() {

var e = document.form1.firstname ;
var e1 = document.form1.lastname ;
var e2 = document.form1.company ;
var e3 = document.form1.emailaddress ;
var e4 = document.form1.address ;
var e5 = document.form1.contactnumber ;



var msg="" ;
var err ;
if(e.value.length == 0)
  {
  msg = "-Please Fill the First Name\n" ;
  err = true ;
  }
  
if(e1.value.length == 0)
  {
  msg += "-Please Fill the Last Name\n" ;
  err = true ;
  }

if(e2.value.length == 0)
  {
  msg += "-Please Fill the Company Name\n" ;
  err = true ;
  }


if(e4.value.length == 0)
  {
  msg += "-Address Must Not Be blank\n" ;
  err = true ;
  }
  

/*if(document.form1.address2.value.length == 0)
  {
  msg += "-Address Must Not Be blank\n" ;
  err = true ;
  }*/
  

if(document.form1.city.value.length == 0)
  {
  msg += "-City Must Not Be blank\n" ;
  err = true ;
  }
  

if(document.form1.state.value.length == 0)
  {
  msg += "-State Must Not Be blank\n" ;
  err = true ;
  }
  
if(document.form1.zipcode.value.length == 0)
  {
  msg += "-Zipcode Must Not Be blank\n" ;
  err = true ;
  } 
  
/*if(document.form1.faxno.value.length == 0)
  {
  msg += "-Fax No Must Not Be blank\n" ;
  err = true ;
  }*/  
  

var str = e5.value ;
var re = /[-]*\w*\d+\.?\d*$/;
if (!re.test(str)) 
    {
   msg += "-Please Fill The Correct Phone No\n"
   err = true ;  
    }
  
  
 var reg = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/ 
 if(!reg.test(e3.value))
   {
  msg += "-Email Is Blank Or Email Format In not Valid\n" ;
  err = true ;
   }   
 

if(err == true) { alert(msg) ; return false ; } else { return true ; }
 
}



