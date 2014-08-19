
function chk() {


var product = document.form1.product ;


var msg="" ;
var err ;
 

  
if(product.value.length == 0)
  {
  msg += "-Product name must not be blank\n" ;
  err = true ;
  }





if(err == true) { alert(msg) ; return false ; } else { return true ; }
 
}
