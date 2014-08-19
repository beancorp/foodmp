/**
* @base upon RainChen @ Fri Jul 28 17:06:37 CST 2006 version 0.1 for xajax 0.2.4 
* @author Oliver Trebes @ Sat Feb 24 11:49:53 CET 2007 
* @uses xajax file upload extend
* @access public
* @param null
* @return null
* @version 0.2 
*/
function xajax_extend()
{
    if(typeof(xajax) == 'undefined')
    {
        return false;
    }
    
    xajax.newSessionID = function()
    {
        var sessionID;
        sessionID = new String(new Date().getTime());
        var random = new String(Math.random( )).substr(2);
        sessionID = sessionID + random;
        return sessionID;
    }
    
    xajax.setStatusMessages = function(msg)
    {
        window.status = msg;
    }
    
    var loadingTimeout;

    xajax.upload = function(rpcFunc,formID,url)
    {
        var form = xajax.$(formID);
        if(!form)
        {
            return false;
        }
        var newSessionID = xajax.newSessionID();
        // init status
        if (document.body && xajax.config.waitCursor)
            document.body.style.cursor = 'wait';

        clearTimeout(loadingTimeout);
        //loadingTimeout = setTimeout("xajax.loadingFunction();",400);

        if(!url)
        {
            url = xajax.config.requestURI;
        }
        
        var separator = '?';
        if(url.indexOf('?') != -1)separator = '&';
        url += separator + 'xajax='+encodeURIComponent(rpcFunc);
        url += "&xajaxr=" + new Date().getTime();
        // get the upload file local path
        var formItem;
        var nodeName;
        for(var i=0; i<form.getElementsByTagName('input').length; i++)
        {
            formItem = form.elements[i];
            nodeName = new String(formItem.nodeName).toLowerCase();
			//alert(nodeName);
            if(formItem.name == '' || formItem.type == 'image' || nodeName == 'button')
            {
                continue;
            }
            if(formItem.type == 'file')
            {
                url += '&'+formItem.name+'='+encodeURIComponent(formItem.value);
            }
        }
        form.action = url;
        
        var iframeName = form.id + newSessionID;
        var iframe;
        if((iframe = xajax.$(iframeName)))
        {
            document.body.removeChild(iframe);
        }
        iframe = xajax.createIframe(iframeName,iframeName);
        form.target = iframeName;
        var xmlDoc;
        var responseXML;
        var responseOBJ;
        
        
        
        if(typeof iframe.onreadystatechange == 'object') // for IE
        {
            iframe.onreadystatechange = function()
            {
                if(iframe.readyState == 'complete' && !iframe.loaded)
                {
                    // IE load twice (bug or feature?)
                    iframe.loaded = true;
                    xmlDoc = document.frames(iframe.id);
                    if(xmlDoc.window.document.location != iframe.src)
                    {
                    	responseOBJ = xajax.tools.getRequestObject();
                    	
                        responseXML = xmlDoc.window.document.XMLDocument;
                        xajax.uploadResponse(responseXML,iframe, xmlDoc,responseOBJ);
                    }
                }
            }
        }
        else // for FF
        {
            iframe.onload = function()
            {
                xmlDoc = iframe.contentWindow;
                if(xmlDoc.window.document.location != iframe.src)
                {
                	responseOBJ = xajax.tools.getRequestObject();
                	
                    responseXML = xmlDoc.document;
                    
                    xajax.uploadResponse(responseXML,iframe, xmlDoc,responseOBJ);
                }
            }
        }
    }
    
    xajax.uploadResponse = function(responseXML,iframeObj, xmlDoc,responseOBJ)
    {
        // doit on the old way ...
		
		xml = responseXML.documentElement;
		if (xml == null)
		{
			return;        
		}
		
		for (var i=0; i<xml.childNodes.length; i++)
		{
			if (xml.childNodes[i].nodeName == "cmd")
			{
				var callargs = new Object();
				
				for (var j=0; j<xml.childNodes[i].attributes.length; j++)
				{
					if (xml.childNodes[i].attributes[j].name == "n")
					{
						callargs.cmd = xml.childNodes[i].attributes[j].value;
					}
					else if (xml.childNodes[i].attributes[j].name == "t")
					{
						callargs.id = xml.childNodes[i].attributes[j].value;
					}
					else if (xml.childNodes[i].attributes[j].name == "p")
					{
						callargs.property = xml.childNodes[i].attributes[j].value;
					}
					else if (xml.childNodes[i].attributes[j].name == "c")
					{
						callargs.type = xml.childNodes[i].attributes[j].value;
					}
				} 
				
				if (xml.childNodes[i].childNodes.length > 1 && xml.childNodes[i].firstChild.nodeName == "#cdata-section")
				{
					callargs.data = "";
					for (var j=0; j<xml.childNodes[i].childNodes.length; j++)
					{
						callargs.data += xml.childNodes[i].childNodes[j].data;
					}
				}
				else if (xml.childNodes[i].firstChild && xml.childNodes[i].firstChild.nodeName == 'xjxobj') {
					callargs.data = xajax._nodeToObject(xml.childNodes[i].firstChild);
					callargs.objElement = "XJX_SKIP";
				}
				else if (xml.childNodes[i].childNodes.length > 1)
				{
					for (var j=0; j<xml.childNodes[i].childNodes.length; j++)
					{
						if (xml.childNodes[i].childNodes[j].childNodes.length > 1 && xml.childNodes[i].childNodes[j].firstChild.nodeName == "#cdata-section")
						{
							var internalData = "";
							for (var k=0; k<xml.childNodes[i].childNodes[j].childNodes.length;k++)
							{
								internalData+=xml.childNodes[i].childNodes[j].childNodes[k].nodeValue;
							}
						} else {
							var internalData = xml.childNodes[i].childNodes[j].firstChild.nodeValue;
						}
					
						if (xml.childNodes[i].childNodes[j].nodeName == "s")
						{
							callargs.search = internalData;
						}
						if (xml.childNodes[i].childNodes[j].nodeName == "r")
						{
							callargs.data = internalData;
						}
					}
				}
				else if (xml.childNodes[i].firstChild)
				{
					callargs.data = xml.childNodes[i].firstChild.nodeValue;
				}
				else
				{
					callargs.data = "";				
				}
				if (callargs.objElement != "XJX_SKIP") callargs.objElement = xajax.$(callargs.id);
				
				xajax.commands[callargs.cmd](callargs);
			}
		}
        
        document.body.style.cursor = 'default';
        setTimeout("xajax.deleteIframe('"+iframeObj.name+"');",400);
        iframeObj = null;

    }

    xajax.deleteIframe = function(iframeName)
    {
        if((iframe = xajax.$(iframeName)))
        {
            document.body.removeChild(iframe);
        }
    }    
    
    xajax.createIframe = function(name,id)
    {
        var iframe;
        if(!id)
        {
            id = '';
        }
        if(document.all && navigator.appName == "Microsoft Internet Explorer") // for IE
        {
            iframe = document.createElement('<iframe id="'+id+'" name="'+name+'">');
        }
        else // for FF
        {
            iframe = document.createElement('iframe');
            iframe.id = id;
            iframe.name = name;
        }
        
        iframe.width = '0';
        iframe.height = '0';
        iframe.style.display = 'none';
        iframe.scrolling = 'no';
        iframe.src = 'about:blank';
        
        document.body.appendChild(iframe);
        return iframe;
    }
}
xajax_extend();