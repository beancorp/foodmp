// JavaScript Document

function chk() {

var e = document.Freetrial.firstname ;
var e1 = document.Freetrial.lastname ;
var e2 = document.Freetrial.company ;
var e3 = document.Freetrial.emailaddress ;
var e4 = document.Freetrial.contactnumber ;
var e5 = document.Freetrial.weburl ;
var e6 = document.Freetrial.message ;



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

  
 var reg = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/ 
 if(!reg.test(e3.value))
   {
  msg += "-Email Is Blank Or Email Format In not Valid\n" ;
  err = true ;
   } 

if(e4.value.length == 0)
  {
  msg += "-Phone No Must Not Be blank\n" ;
  err = true ;
  }
  
if(e5.value.length == 0)
  {
  msg += "-Please Fill The Web Url\n" ;
  err = true ;
  } 

if(e6.value.length == 0)   { 
  msg += "-Please Fill The Message\n" ;
  err = true ;
  }
   
 

if(err == true) { alert(msg) ; return false ; } else { return true ; }
 
}
