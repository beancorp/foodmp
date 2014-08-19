/**
* @author RainChen @ Fri Jul 28 17:06:37 CST 2006
* @uses xajax file upload extend
* @access public
* @param null
* @return null
* @version 0.1
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
        var formObj = xajax.$(formID);
        if(!formObj)
        {
            return false;
        }
        var newSessionID = xajax.newSessionID();
        // init status
		
        //if (document.body && xajaxWaitCursor)
        //    document.body.style.cursor = 'wait';
        //if (xajaxStatusMessages == true) xajax.setStatusMessages('Sending Request...');
		try{
			if (document.body && xajax.config.waitCursor)
				document.body.style.cursor = 'wait';
			if (xajax.config.statusMessages == true) xajax.setStatusMessages('Sending Request...');
			clearTimeout(loadingTimeout);
			loadingTimeout = setTimeout("xajax.loadingFunction();",400);
			//if (xajax.config.debug) xajax.DebugMessage("Starting xajax...");
		}catch(ex){alert('aa')}
		
        if(!url)
        {
            //url = xajaxRequestUri;
			url	=	xajax.config.requestURI;
        }
        var separator = '?';
        if(url.indexOf('?') != -1)separator = '&';
        url += separator + 'xajax='+encodeURIComponent(rpcFunc);
        url += "&xajaxr=" + new Date().getTime();
        // get the upload file local path
        
        var nodeName;
        for(var i=0; i<formObj.getElementsByTagName('input').length-1 ; i++)
        {
            var formItem = formObj.elements[i];
			
            nodeName = new String(formItem.nodeName).toLowerCase();
            
			if(formItem.name == '' || formItem.type == 'image' || nodeName == 'button')
            {
                continue;
            }
            if(formItem.type == 'file')
            {
                url += '&'+formItem.name+'='+encodeURIComponent(formItem.value);
            }
        }
        formObj.action = url;
        
        var iframeName = formObj.id + newSessionID;
        var iframe;
        if((iframe = xajax.$(iframeName)))
        {
            document.body.removeChild(iframe);
        }
        iframe = xajax.createIframe(iframeName,iframeName);
        formObj.target = iframeName;
        var xmlDoc;
        var responseXML;
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
                        responseXML = xmlDoc.window.document.XMLDocument;
                        xajax.uploadResponse(responseXML,iframe, xmlDoc);
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
                    responseXML = xmlDoc.document;
                    xajax.uploadResponse(responseXML,iframe, xmlDoc);
                }
            }
        }
    }
   
    xajax.uploadResponse = function(responseXML,iframeObj, xmlDoc)
    {
        // response the xml
        var error=false;
        if(responseXML)
        {
            try
            {
                xajax.processResponse(responseXML);
            }
            catch(e)
            {
                error = true;
                //xajax.DebugMessage(e.description);
            }
        }
        else
        {
            error = true;
        }
        
        if(error)
        {
            var responseText = new String(xmlDoc.document.body.innerHTML);
            var errorString = "Error: the XML response that was returned from the server is invalid.";
            errorString += "\nReceived:\n" + responseText;
            trimmedResponseText = responseText.replace( /^\s+/g, "" );// strip leading space
            trimmedResponseText = trimmedResponseText.replace( /\s+$/g, "" );// strip trailing
            if (trimmedResponseText != responseText)
                errorString += "\nYou have whitespace in your response.";
            xajax.DebugMessage(errorString);
            document.body.style.cursor = 'default';
            if (xajaxStatusMessages == true) xajax.setStatusMessages('Invalid XML response error');
        }

        // remove the iframe after response
        document.body.removeChild(iframeObj);
        iframeObj = null;
    }
   
    xajax.createIframe = function(name,id)
    {
        var iframe;
        if(!id)
        {
            id = '';
        }
        if(document.all) // for IE
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