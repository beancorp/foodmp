// JavaScript Document



function chk() {

var e3 = document.form1.payment ;

var e4 = document.form1.date ;

var e5 = document.form1.paymode ;

var msg="" ;

var err ;





if(e3.value.length == 0)

  {

  msg += "-Please Fill Payment Amount\n" ;

  err = true ;

  }

  

if(e4.value.length == 0)

  {

  msg += "-Please select the date\n" ;

  err = true ;

  } 





if(!document.form1.paymode[0].checked)

   {



if(document.form1.paymode[1].checked)

   {

   if(document.form1.number.value.length == 0)

      {

	    msg += "-Please Fill DD Number\n" ;

        err = true ;

	  }

   }



if(document.form1.paymode[1].checked)

   {

   if(document.form1.bank.value.length == 0)

      {

	    msg += "-Please Fill Bank Name\n" ;

        err = true ;

	  }

   }







if(document.form1.paymode[2].checked)

   {

   if(document.form1.number.value.length == 0)

      {

	    msg += "-Please Fill Check Number\n" ;

        err = true ;

	  }

   }

   

   



if(document.form1.paymode[2].checked)

   {

   if(document.form1.bank.value.length == 0)

      {

	    msg += "-Please Fill Bank Name\n" ;

        err = true ;

	  }

   }



}

if(document.form1.receiptno.value.length == 0)

      {

	    msg += "-Please Fill Recipt Number\n" ;

        err = true ;

	  }







if(err == true) { alert(msg) ; return false ; } else { return true ; }

 

}



function lbl(){





if(document.form1.paymode[1].checked)

   {

   document.form1.number.disabled = false ;

   document.form1.bank.disabled = false ; 

   document.getElementById('lblmode').innerHTML= " DD Number" ;



   }





if(document.form1.paymode[2].checked)

   {

   document.form1.number.disabled = false ;

   document.form1.bank.disabled = false ; 

   document.getElementById('lblmode').innerHTML= " Check Number" ;

   }



}



function dis() {



if(document.form1.paymode[0].checked)

   {

   

   document.form1.number.disabled = true ;

   document.form1.bank.disabled = true ;   

   } 

}







function MM_jumpMenu(targ,selObj,restore){ //v3.0

  eval("document.location='"+selObj.options[selObj.selectedIndex].value+"'");

  if (restore) selObj.selectedIndex=0;

}



