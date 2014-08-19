

$.extend({
	mdialog : function() {

		var DLOGID = VICO_DLOGID = "VICO_DIALOG";
		var SRCEENID = 'VICO_SCREENOVER';
		var MSGID = "VICO_MESSAGE";
		var SRARTMOVE = 0;
		var MOVEX = 0;
		var MOVEY = 0;
		var MSGTIME = 0;
		

		this.axis = function (width, height) {
			var dde = document.documentElement;
			if (window.innerWidth) {
				var ww = window.innerWidth;
				var wh = window.innerHeight;
				var bgX = window.pageXOffset;
				var bgY = window.pageYOffset;
			} else {
				var ww = dde.offsetWidth;
				var wh = dde.offsetHeight;
				var bgX = dde.scrollLeft;
				var bgY = dde.scrollTop;
			}
			x = bgX + ((ww - width) / 2);
			y = bgY + ((wh - height) / 2);
			return {x:x, y:y};
		}

		this.drag = function (my, move_callback, end_callback) {

			my.each(function() {

				this.posRange = {
					minX:0, minY:0,
					maxX:(document.compatMode == "CSS1Compat" ? document.documentElement.clientWidth : document.body.clientWidth),
					maxY:(document.compatMode == "CSS1Compat" ? document.documentElement.clientHeight : document.body.clientHeight)
				};
				this.onmousedown = function(e) {
					//this.style.zIndex = $.zIndex++;
					//this.style.position = "absolute";
				   
					//alert(e);
				   

				   this.drag(e, move_callback, end_callback);
				}
				this.drag = function(e, move_callback, end_callback) {
					var element = this,ev = e || window.event;
					ev.rScreenX = ev.screenX;
					ev.rScreenY = ev.screenY;
					var pos = $(this).offset();
					element.dragConfig = {
						defaultX : parseInt(pos.left,10),
						defaultY : parseInt(pos.top,10),
						defaultW : parseInt($(this).width(),10),
						defaultH : parseInt($(this).height(),10)
					};
					document.onmouseup = function() {
						element.drop();
						end_callback && end_callback();
					};
					document.onmousemove = function(e) {
						var ev2 = e || window.event;
						if($.browser.msie && ev2.button != 1) {
							return (element.drop(), callback && callback());
						}
						var mx = element.dragConfig.defaultX + (ev2.screenX - ev.rScreenX),
						my = element.dragConfig.defaultY + (ev2.screenY - ev.rScreenY),
						pr = element.posRange, 
						mw = element.dragConfig.defaultW, 
						mh = element.dragConfig.defaultH;
						with(element.style) {
							left = (mx<pr.minX?pr.minX:mx) + "px";
							top = (my<pr.minY?pr.minY:my) + "px";
							//left = (mx<pr.minX?pr.minX:((mx+mw)>pr.maxX?(pr.maxX-mw):mx)) + "px";
							//top = (my<pr.minY?pr.minY:((my+mh)>pr.maxY?(pr.maxY-mh):my)) + "px";
						}
						move_callback && move_callback();
						return false;
					};
					document.onselectstart = function() { return false; }
				}
				this.drop = function() {
					document.onmousemove = document.onselectstart = document.onmouseup = null;
				}
			});
		}

		this.close = function () {
			var closedogid = DLOGID;
			if(CURENTVICODLOG.length>0){
				closedogid  = CURENTVICODLOG;
			}
			if($("#"+closedogid)[0]!=null) {
				$("#"+closedogid).remove();
				//$('#'+closedogid+'BoxBG').remove();
			}
			//alert($('div[id^="'+VICO_DLOGID+'"]'))
			if($('div[id^="'+VICO_DLOGID+'"]').length){
				CURENTVICODLOG = $('div[id^="'+VICO_DLOGID+'"]').attr('id');
			}else{
				CURENTVICODLOG = '';
			}
			
			/*if($.browser.msie && $.browser.version.substr(0,1)=='6' ) {
				$("select").each(function(i) { 
					this.style.visibility = "";
				});
			}*/
		}

		this.open = function (title, body, width, height, showclose) {
			//alert('a'+CURENTVICODLOG);
			var nDLOGID = DLOGID;
			if(CURENTVICODLOG.length>0){				
				nDLOGID = VICO_DLOGID + $('div[id^="'+VICO_DLOGID+'"]').length;
			}
			CURENTVICODLOG = nDLOGID;
			//alert('b'+nDLOGID);
			if($("#"+nDLOGID)[0] != null) {
				return;
			}
			
			closestyle = showclose ? "" : " display:none";
			//alert('c'+CURENTVICODLOG);
			var dlg = $("<div></div>").attr("id",nDLOGID);
			var p = this.axis(width, height);
			dlg.css({
				top:p.y, left:p.x, display:"block", margin:"0px", padding:"0px",
				width:width, height:height, position:"absolute", zIndex:"1003",
				filter:"progid:DXImageTransform.Microsoft.shadow(direction=135,color=#CCCCCC,strength=3)"
			});
			dlg.addClass("mdialog");
			dlg.append($("<div></div>").html("<em style=\"" + closestyle + "\"></em><span></span>"));
			dlg.click(this.setontop);
			var dragfoo = dlg.find("div");
			dragfoo.addClass("mheader");			
			dragfoo.append('<div style="clear:both; margin:0px; padding:0px; height:1px; display:none;"></div>');
			dlg.find("div > span").text(title);
			dlg.find("div > em").click(this.close);
			dlg.append($("<div></div>").addClass("mbody").append(body));
			/*if($.browser.msie && $.browser.version.substr(0,1)=='6' ) {
				$("select").each(function(i) { 
					this.style.visibility="hidden";
				});
			}*/
			//alert(document.body.scrollHeight);
			//$(document.body).append('<div id="'+nDLOGID+'BoxBG" style="z-index: 1002; position: absolute; filter: alpha(opacity=40); background-color: #eeeeee; width: 100%; height: '+document.body.scrollHeight+'px; top: 0px; left: 0px; opacity: 0.4"></div>')
			$(document.body).append(dlg);
			this.drag(dlg);
		}
		
		this.setontop = function(){
			//alert(CURENTVICODLOG);
			if(CURENTVICODLOG.length>0 && $("#"+CURENTVICODLOG)[0] != null){
				var id = $(this).attr('id');
				//alert('id'+id);
			
				$('div[id^="'+VICO_DLOGID+'"]').css({zIndex:"1003"});
				$('#'+id).css({zIndex:"1006"});
				CURENTVICODLOG = id;
			}
		}

		this.showmsg = function() {

			if(arguments.length == 0) return;

			var body = arguments[0];
			var width = typeof(arguments[1]) == 'undefined' ? 250 : arguments[1];
			var height = typeof(arguments[2]) == 'undefined' ? 50 : arguments[2];
			var hideauto = typeof(arguments[2]) == 'undefined' ? true : arguments[3];

			if($("#"+MSGID)[0] != null) {
				return;
			}
			var msg = $("<div></div>").attr("id",MSGID);
			var p = this.axis(width, height);
			msg.css({ 
				top:p.y, left:p.x, display:"block", margin:"0px", padding:"0px",
				width:width+'px', height:height+'px', position:"absolute", zIndex:"1005",
				filter:"progid:DXImageTransform.Microsoft.shadow(direction=135,color=#CCCCCC,strength=3)",
				"line-height":height+'px'
			});
			msg.addClass("mmessage");
			msg.append($("<div></div>").addClass("mbody").append(body)).click(this.hidemsg);

			if($.browser.msie && $.browser.version.substr(0,1) == '6' ) {
				$("select").each(function(i) {
					this.style.visibility = "hidden";
				});
			}

			$(document.body).append(msg);
			//this.drag(msg);
			if(hideauto){
			MSGTIME = window.setTimeout(this.hidemsg, 3000);
			}
		}

		this.hidemsg = function() {
			if($("#"+MSGID)[0]!=null) {
				$("#"+MSGID).remove();
			}
			if($.browser.msie && $.browser.version.substr(0, 1)=='6' ) {
				$("select").each(function(i) { 
					this.style.visibility = "";
				});
			}
			if(MSGTIME){
			window.clearTimeout(MSGTIME);
			}
		}
		return this;
	}
});

var CURENTVICODLOG = '';
dlgOpen = function(title, body, width, height, showclose) {
	$.mdialog().open(title, body, width, height, showclose);
}

dlgClose = function() {
	$.mdialog().close();
}
msgClose = function(){
	$.mdialog().hidemsg();
}
msgOpen = function() {
	if(arguments.length == 0) return;
	var body = arguments[0];
	var hideauto = typeof(arguments[1]) == 'undefined' ? true : arguments[1];
	var width = typeof(arguments[2]) == 'undefined' ? 250 : arguments[2];
	var height = typeof(arguments[3]) == 'undefined' ? 50 : arguments[3];
	
	$.mdialog().showmsg(body,width,height,hideauto);
}
dlgCloseReload = function() {
	$.mdialog().close();
	window.location.reload();
}
