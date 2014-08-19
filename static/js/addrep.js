
function chk() {


var e2 = document.form1.company ;
var e3 = document.form1.email ;
var e4 = document.form1.address ;
var e5 = document.form1.ph ;
var fax = document.form1.faxno ;
var website = document.form1.website ;
var market = document.form1.market ;

var msg="" ;
var err ;

msg += "Following error(s) are found\n" ;

if(document.form1.state.value == "")
  {
  msg += "-Please select the representative Name\n" ;
  err = true ;
  }


if(e2.value.length == 0)
  {
  msg += "-Please fill the company name\n" ;
  err = true ;
  }

if(e4.value.length == 0)
  {
  msg += "-Address must not be blank\n" ;
  err = true ;
  }
  
 

var str = e5.value ;
var re = /[-]*\w*\d+\.?\d*$/;
if (!re.test(str)) 
    {
   msg += "-Please fill the correct phone no\n"
   err = true ;  
    }
   
 var reg = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/ 
 if(!reg.test(e3.value))
   {
  msg += "-Email is blank or email format is not valid\n" ;
  err = true ;
   }   

if(fax.value.length == 0)
  {
  msg += "-Fax no must not be blank\n" ;
  err = true ;
  }
  
if(website.value.length == 0)
  {
  msg += "-Website must not be blank\n" ;
  err = true ;
  }

if(market.value.length == 0)
  {
  msg += "-Market must not be blank\n" ;
  err = true ;
  }



if(err == true) { alert(msg) ; return false ; } else { return true ; }
 
}


function chk2() {


var e2 = document.form1.company ;
var e3 = document.form1.ssheet ;
var e4 = document.form1.psheet ;
var e5 = document.form1.username ;
var e6 = document.form1.password ;
var e7 = document.form1.sheet_title1 ;
var e8 = document.form1.sheet_title2 ;

var msg="" ;
var err ;

msg += "Following error(s) are found\n" ;

if(document.form1.state.value == "")
  {
  msg += "-Please select the state name\n" ;
  err = true ;
  }


if(e2.value.length == 0)
  {
  msg += "-Please select the company name\n" ;
  err = true ;
  }

if(e4.value.length == 0)
  {
  msg += "-Please attach the price sheet\n" ;
  err = true ;
  }
  
if(e7.value.length == 0)
  {
  msg += "-Please assign the title for the sheet\n" ;
  err = true ;
  }


if(e5.value.length == 0)
  {
  msg += "-Please assign the username\n" ;
  err = true ;
  }
  
if(e6.value.length == 0)
  {
  msg += "-Please assign the password\n" ;
  err = true ;
  }




if(err == true) { alert(msg) ; return false ; } else { return true ; }
 
}

