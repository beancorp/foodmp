// JavaScript Document


function MM_jumpMenu(targ,selObj,restore){ 
  eval("document.location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}




function check()
{
	
var var1 ="document.jayP.a";
var var2=".checked";
var3=".value";
var val="";
var valC = document.jayP.delCount.value 
var flagT = false;
var count =0;
var myval='';


  for (j=1 ; j <valC ; j=j+1) 
    {     
       if  ( eval(var1+j+var2 ) ) 
       {
		
		 flagT = true;
		 count+=1;
		 if(j == 1)
		 {
			myval= eval(var1+j+var3) ;    
		 }
		 else
		 {		 
		 	if (myval=='')
		 	{
			myval= eval(var1+j+var3) ;    
			}
			else
			{
			myval= "," + eval(var1+j+var3) ;    
			}						
		 }
     
      val =val +  myval;
     
       }     
   }   

if(!flagT)
{
	alert("Check the Delete Box");
	return false;
}

document.jayP.counter.value = count;
document.jayP.codes.value = val;

//  alert(val);


return confirm("Delete " + count + " Records ?\n" );

}

function selectAll(obj)
{	
len = document.jayP.elements.length;

var i=0;
if (obj.checked) 
{ 
for( i=0; i<len; i++)
{
if (document.jayP.elements[i].name.indexOf("a") == 0)
{
document.jayP.elements[i].checked=true;	
}
}
}
else {
for( i=0; i<len; i++) {
if (document.jayP.elements[i].name.indexOf("a") == 0)	
{	
document.jayP.elements[i].checked=false;	
} 
}
}
}
