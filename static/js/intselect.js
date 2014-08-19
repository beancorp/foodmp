var zQuery=function(ele,tagName,className){
    if ( window == this ) return new zQuery(ele,tagName,className); 
    if(!arr){var arr=new Array;} 
    if(ele){ 
        if(ele.constructor!=zQuery){ 
            var elem=typeof(ele)=="object"?ele:document.getElementById(ele); 
            if(!tagName){ 
                arr.push(elem); 
            }else{ 
                var tags=elem.all&&!window.opera?tagName=="*"?elem.all:elem.all.tags(tagName):elem.getElementsByTagName(tagName); 
                if(!className){ 
                    for(var i=0, l=tags.length; i<l; i++){ 
                        arr.push(tags[i]); 
                    } 
                }else{ 
                    var reClassName=RegExp("(^|\\s+)"+className+"($|\\s+)"); 
                    for(var i=0, l=tags.length; i<l; i++){ 
                        if(reClassName.test(tags[i].className)){ 
                            arr.push(tags[i]); 
                        } 
                    } 
                } 
            } 
        }else{ 
            for(var i=0, l=ele.length; i<l; i++){ 
                arr=arr.concat(Array.prototype.slice.call(zQuery(ele[i],tagName,className))); 
            } 
        } 
    } 
    return this.setArray(arr);
} 
zQuery.prototype.setArray = function( arr ) { 
    this.length = 0; 
    Array.prototype.push.apply( this, arr );
    return this; 
} 
zQuery.fn=zQuery.prototype; 

function Offset(obj){ 
    var t = obj.offsetTop; 
    var l = obj.offsetLeft; 
    var w = obj.offsetWidth; 
    var h = obj.offsetHeight-2; 
    while(obj=obj.offsetParent) 
    { 
        t+=obj.offsetTop; 
        l+=obj.offsetLeft; 
    } 
    return { 
        top : t, 
        left : l, 
        width : w, 
        height : h 
    } 
} 
function instSelect(obj){ 
    var offSet=Offset(obj); 
    obj.style.display="none"; 
    var sDiv=document.createElement("div"); 
    sDiv.id="div"+obj.name; 
    sDiv.className="divSlt"; 
    sDiv.style.width=offSet.width+"px"; 
    //sDiv.style.left=offSet.left+"px"; 
    //sDiv.style.top=offSet.top+"px"; 
    obj.parentNode.appendChild(sDiv); 
    var sSpan=document.createElement("span"); 
    var spanId=obj.options[obj.selectedIndex].value; 
    var spanText=obj.options[obj.selectedIndex].text; 
    sSpan.id=spanId; 
    //sSpan.style.lineHeight=offSet.height+"px"; 
    sTxt=document.createTextNode(spanText); 
    sSpan.appendChild(sTxt); 
    sDiv.appendChild(sSpan); 
    sSpan.onclick=function(){ 
        if(zQuery("div"+obj.name,"ul").length==0){ 
            var sUl=document.createElement("ul"); 
            sDiv.appendChild(sUl); 
            var optLen=obj.options.length; 
            var tmp=document.createDocumentFragment(); 
            for(var j=0;j<optLen;j++){ 
                var sltVal=obj.options[j].value; 
                var sltTxt=obj.options[j].text; 
                var sLi=document.createElement("li"); 
                sLi.id=sltVal;
                sLi.appendChild(document.createTextNode(sltTxt)); 
                sLi.onmouseover=function(){ 
                    this.style.background="#cccccc"; 
                    this.style.color="white"; 
                } 
                sLi.onmouseout=function(){ 
                    this.style.background="white"; 
                    this.style.color="#777777"; 
                } 
                sLi.onclick=function(){ 
                    sSpan.innerHTML=this.innerHTML;
                    obj.value=this.id; 
                    sUl.style.display="none"; 
                    if(obj.value!=""){
                    	$('#'+sSpan.parentNode.parentNode.id).css('background','url(/skin/red/images/main/select_but.jpg) left top no-repeat');
                    }
                } 
                tmp.appendChild(sLi); 
            } 
            sUl.appendChild(tmp); 
            if(optLen>9){ 
                sUl.style.overflowY="scroll"; 
                sUl.style.height="238px";
            } 
        } 
        else{ 
            if(zQuery("div"+obj.name,"ul")[0].style.display=="none") zQuery("div"+obj.name,"ul")[0].style.display="block"; 
            else zQuery("div"+obj.name,"ul")[0].style.display="none"; 
        } 
    } 
} 
function initSelect(){ 
    var slt=zQuery(document,"select"); 
    var sltLen=slt.length; 
    for(var i=0;i<sltLen;i++){ 
        instSelect(slt[i]); 
    } 
} 

window.onload=initSelect; 
document.onclick=function(){ 
    var evt=getEvent(); 
    var element=evt.srcElement || evt.target; 
    var s=zQuery(document,"select"); 
    if((element.parentNode.parentNode==null||element.parentNode.parentNode.className!="divSlt")&&element.nodeName!="SPAN"){ 
        for (var i=0; i<s.length; i++) { 
            if(!zQuery("div" + s[i].name,"ul")[0]) continue; 
            zQuery("div" + s[i].name,"ul")[0].style.display="none"; 
        } 
    } 
} 
function getEvent(){ 
     if(document.all)    return window.event; 
     func=getEvent.caller; 
     while(func!=null){ 
         var arg0=func.arguments[0]; 
         if(arg0){ 
             if((arg0.constructor==Event || arg0.constructor ==MouseEvent) 
                || (typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation)){     
                return arg0; 
              } 
         } 
          func=func.caller; 
        } 
        return null; 
} 