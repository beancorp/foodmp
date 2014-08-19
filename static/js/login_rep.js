// JavaScript Document

function chk() {
var e = document.loginForm.user ;
var e1 = document.loginForm.pass ;
var msg="" ;
var err ;
if(e.value.length == 0)
  {
  msg = "-Please fill the user name\n" ;
  err = true ;
  }
  
if(e1.value.length == 0)
  {
  msg += "-Please fill the password\n" ;
  err = true ;
  }


if(err == true) { alert(msg) ; return false ; } else { return true ; }
}

	
	function setFocus() {
		document.loginForm.user.select();
		document.loginForm.user.focus();
	}
