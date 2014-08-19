	if (document.all){
		window.attachEvent('onload',winOnload);
	}else{
		window.addEventListener('load',winOnload,false);
	}

	function winOnload(){
		Nifty("div#seller-info1","big tl bl");
		Nifty("div#seller-info2","big tl bl ");
		Nifty("div#seller-info3","big tl bl br");
		Nifty("div#paging-wide","medium bl br");
		Nifty("div#paging","medium bl br");
		Nifty("ul#loggedin","medium tr br");

		//for state search page
		Nifty("form#searchside","big br");
		Nifty("h2#location","medium");
		//for signup page
		Nifty("div#grey-inside","big bl");
		//for college search
		Nifty("form.unisearch","big br tr");
		$('#slickbox').hide();
	}

	function selectCollege(obj,params)
	{
		try{
			ajaxLoadPage('/soc.php','&act=signon&step=college&SID='+params,'GET',document.getElementById(obj),false,false,true);
		}
		catch(ex)
		{
		
		}
	}
	function selectCollegebyName(obj,params)
	{
		var objdiv = obj;
		$.get('/soc.php?statename='+params,{act:'signon',step:'getCollege'},function (data){
			$('#'+objdiv).html(data);
			//new YAHOO.Hack.FixIESelectWidth('collegeid');
		});
	}
	function rightseletCollege(obj,params){
		$.get('/soc.php?statename='+params,{act:'signon',step:'getCollege2',obj:'collegeid2'},function (data){
			$('#collegeid2').html(data);
			//new YAHOO.Hack.FixIESelectWidth('collegeid2');
		});
	}
	function showmoreImage_fade(id,bl){
		var width = 232;
		var height = 232;
		var image = $('#'+id+' img');
		var re = /px/g;
		var imgwidth = parseInt(image.css('width').replace(re,""));
		var imgheight = parseInt(image.css('height').replace(re,""));
		if (imgwidth > imgheight){
		   if(imgwidth>width){
			image.css('width',width+"px");
			image.css('height',(width/imgwidth*imgheight)+"px");
		   }
		}
		else{
		   if(imgheight>height){
			image.css('height',height);
			image.css('width',(height/imgheight*imgwidth)+"px");
		   }
		}
		if(bl){
			$('#'+id).fadeIn();
			$('#'+id+"_2").fadeIn();
		}else{
			$('#'+id).fadeOut();
			$('#'+id+"_2").fadeOut();
		}
	}

	<!-- Facebook -->
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script'));
	
	function fetchFacebookData(data) {
		if (data) {
			$('#name').val(data.name);
			$('#email').val(data.email).change();
			$('#nickname').val(data.username).change();
			$('#fb_id').val(data.id);
			if (data.location.name) {
				var location = data.location.name.split(',');
				if (location.length == 2) {
					var suburb = $.trim(location[0]);
					var state = $.trim(location[1]);
					
					$('#state_selection option').filter(function() {
						return $.trim(this.text) == state; 
					}).attr('selected', true).change();
					
					$('#suburb_list option').filter(function() {
						return $.trim(this.text) == suburb; 
					}).attr('selected', true).change();
				}
			}
		}
	}

	<!-- Google analytics -->
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-38122374-2', domain);
	ga('send', 'pageview');

	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));

	try {
		var pageTracker = _gat._getTracker("UA-7837615-1");
		pageTracker._trackPageview();
	}catch(err) {}

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-32904933-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); 
	ga.type = 'text/javascript'; 
	ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www' ) + '.google-analytics.com/ga.js'; 
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(ga, s);
	})();



	<!--add get facebook login message by haydn.h at 20120306 -->
	
$('document').ready(function() {

	if ((foodmp_ocp == 'sellerhome' || foodmp_ocp == 'buyerhome') && smarty_fb_can) {
		window.fbAsyncInit = function() {
			FB.init({
				appId: facebook_appID,
				status: false, 
				cookie: true,
				xfbml: true,
				oauth: true
			});

			FB.Event.subscribe('auth.login', function(response) {
				window.location.href='/soc.php?cp=fbbundle';
			});
			
			FB.Event.subscribe('auth.statusChange', function(response) {
				if (response.status == 'connected') {
					window.location.href='/soc.php?cp=fbbundle';
				}
			});
		};
	} else if (foodmp_ocp == 'customers_geton' || foodmp_act == 'signon') {
	
		var fb_data = null;
		var fb_get_number = 0;
		window.fbAsyncInit = function() {
			FB.init({
				appId: facebook_appID,
				status: true, 
				cookie: false,
				xfbml: false,
				oauth: true
			});

			FB.Event.subscribe('auth.login', function(response) {
				if (response.status == 'connected') {
					FB.api('/me', function(response) {
						addFacebookButton();
						fb_data = response;
						fb_get_number>1 ? inputFacebookMessage(fb_data) : fb_get_number ++;
					});
				}else{
					fb_get_number ++;
				}
			});
			FB.Event.subscribe('auth.statusChange', function(response) {
				if (response.status == 'connected') {
					FB.api('/me', function(response) {
						addFacebookButton();
						fb_data = response;
						fb_get_number>1 ? inputFacebookMessage(fb_data) : fb_get_number ++;
					});
				}else{
					fb_get_number ++;
				}
			});
		};
	} else if (foodmp_ocp == 'login') {
	
		window.fbAsyncInit = function() {
			FB.init({
				appId: facebook_appID,
				status: false, 
				cookie: true,
				xfbml: false,
				oauth: true
			});

			FB.Event.subscribe('auth.login', function(response) {
				fbLogin();
			});
			FB.Event.subscribe('auth.statusChange', function(response) {
				fbLogin();
			});
		};

	} else if (foodmp_ocp == 'signup') {
	
		var fb_data = null;
		window.fbAsyncInit = function() {
			FB.init({
				appId: facebook_appID,
				status: true, 
				cookie: false,
				xfbml: false,
				oauth: true
			});
			
			FB.Event.subscribe('auth.login', function(response) {
				if (response.status == 'connected') {
					FB.api('/me', function(response) {
						fb_data = response;
					});
				}
			});

			FB.Event.subscribe('auth.statusChange', function(response) {
				if (response.status == 'connected') {
					FB.api('/me', function(response) {
						fb_data = response;
						fetchFacebookData(fb_data);
						addFacebookButton();
					});
				}
			});
		};
	}
});


	function fbLogin(){
		window.location.href = '/login.php?use=fb_login';
	}

	function addFacebookButton(){
		$('#fb-login-button').html('<a class="fb_button fb_button_medium" href="javascript:void(0);" onclick="javascript:inputFacebookMessage(fb_data);"><span class="fb_button_text">Register with facebook</span></a>');
	}

	function inputFacebookMessage(data){
		try
		{
			$('#fb_id').val(data.id);

			if (foodmp_ocp == 'customers_geton') {
				$('#cu_username').val(data.email);
				$('#re_cu_username').val(data.email);
				$('#cu_name').val(data.username);
			} else if (foodmp_act == 'signon') {
				$('#bu_user').val(data.email);
				$('#re_bu_user').val(data.email);
				checkEmail();
				$('#bu_urlstring').val(data.username);
				checkWebsite();
				$('#bu_name').val(data.username);
				if($('input[@type=radio][@name=attribute][@checked]').val()==5){
					$('#bu_username').val(data.username);
					checkUsername();
				}
			}
			if(data.gender == 'female') $('input[@type=radio][@name=gender]').attr('checked', 1);
		} catch(e){}
	}
	<!--add get facebook login message by haydn.h at 20120306 -->

	<!-- Header -->
	var timer;
	$(document).ready(function() {
		$('#register').hover(function() {
			$('#sub_links').show();
		}, function() {
			timer = setTimeout(function() {
				if (!$('#sub_links').hasClass('hovered')) {
					$('#sub_links').hide();
				}
			}, 2000);
		});
		
		$('.sub_link').hover(function() {
			$('#sub_links').addClass('hovered');
			clearTimeout(timer);
		}, function() {
			$('#sub_links').removeClass('hovered');
			timer = setTimeout(function() {
				if (!$('#sub_links').hasClass('hovered')) {
					$('#sub_links').hide();
				}
			}, 500);
		});
	});


	<!-- Search -->


	
	function blurkeyword(obj,bl){
		if(!bl){
			if(jQuery.trim(obj.value)!=""){
				bl = true;
			}
		}
		if(bl){
			var bg = dis_bg;
			var color = dis_color;
		}else{
			var bg = nor_bg;
			var color = nor_color;
		}
	
		$('#state_name_id').attr('disabled',bl); 

		$('#state_name_id').css("color",color);
		
		$('#suburb_id').attr('disabled',bl);

		$('#suburb_id').css("color",color);
		
		$('#catst').attr('disabled',bl);

		$('#catst').css("color",color);
		
		$('#subcatest').attr('disabled',bl);

		$('#subcatest').css("color",color);
		
		$('#proximityid').attr('disabled',bl);
		
		$('#proximityid').css("color",color);
		
	}
	
	$(document).ready(function($){		
		$("#state_name_id").change(function() {
			$('input.ui-autocomplete-input').val('');
			$('#suburb_id').empty();
			if ($(this).val() != -1) {
				try{
					ajaxLoadPage('/jquery.functions.php','&cp=getCityList&type=simple&state_name='+$(this).val(),'GET',document.getElementById('suburb_id'),false,false,true);
				}
				catch(ex)
				{}
				$('#suburb_field').show();
			} else {
				$('#suburb_field').hide();
			}
		});	
		if ($('#state_name_id').val() == '-1') {
			$('#suburb_field').hide();
		} else if (typeof preselect_suburb == 'undefined') {
	    	$.when($.ajax('/jquery.functions.php?cp=getCityList&type=simple&state_name='+$("#state_name_id").val())).done(function(x) { 
    			$("#suburb_id").html(x);
	    		$("#suburb_id").combobox();
	    	});
		} else {
	    	$.when($.ajax('/jquery.functions.php?cp=getCityList&type=simple&state_name='+$("#state_name_id").val()+'&preselect='+preselect_suburb)).done(function(x) { 
    			$("#suburb_id").html(x);
	    		$("#suburb_id").combobox();
	    	});
	    }
	    $("#toggle").click(function () {
	        $("#suburb_id").toggle();
	    });
	});

    $.widget("custom.combobox", {
        _create: function () {
            this.wrapper = $("<span>")
                .addClass("custom-combobox")
                .insertAfter(this.element);
            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },
        _createAutocomplete: function () {
			var selected = this.element.children(":selected"),
                value = selected.val() ? selected.text() : "";
            this.input = $("<input>")
                .appendTo(this.wrapper)
                .val(value)
                .attr("title", "")
                .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: $.proxy(this, "_source"),
					open: function(event, ui) {
						$(this).autocomplete("widget").css({
							"width": 140
						});
					}
                });
            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                },
                autocompletechange: "_removeIfInvalid"
            });
        },
        _createShowAllButton: function () {
            var input = this.input,
                wasOpen = false;
            $("<a>")
                .attr("tabIndex", -1)
                .attr("title", "Show All Items")
                .appendTo(this.wrapper)
            if ($("<a>").button) {
				$("<a>")
	                .button({
	                    icons: {
	                        primary: "ui-icon-triangle-1-s"
	                    },
	                    text: false
	                })
	                .removeClass("ui-corner-all")
	                .addClass("custom-combobox-toggle ui-corner-right")
	                .mousedown(function () {
	                    wasOpen = input.autocomplete("widget").is(":visible");
	                })
	                .click(function () {
	                    input.focus();
	                    // Close if already visible
	                    if (wasOpen) {
	                        return;
	                    }
	                    // Pass empty string as value to search for, displaying all results
	                    input.autocomplete("search", "");
	                });
	        }
        },
        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                if (this.value && (!request.term || matcher.test(text)))
                    return {
                        label: text,
                        value: text,
                        option: this
                    };
            }));
        },
        _removeIfInvalid: function (event, ui) {
            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }
            // Search for a match (case-insensitive)
            var value = this.input.val(),
                valueLowerCase = value.toLowerCase(),
                valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });
            // Found a match, nothing to do
            if (valid) {
                return;
            }
            // Remove invalid value
            this.input
                .val("")
                .attr("title", value + " didn't match any item")
                .tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.attr("title", "");
            }, 2500);
            this.input.data("ui-autocomplete").term = "";
        },
        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });
	
	//ajax change state
	function switchState(statename, council_id) {
		$.get('/jquery.functions.php?cp=getCouncilList&type=_E_&state_name='+statename+"&t="+Math.random(),function(str){
			$("#" + council_id).html(str);
		})
	}
	
	function changesubcate(obj){
		$.get('/leftmenuajax.php',{opt:'getsubcat',cid:obj.value,search_type:'foodwine'},function (data){
			$('#subcatest').html(data);$('#subcatest').val('');
			//new YAHOO.Hack.FixIESelectWidth('subcatest');
		});
	}
	
	function changeCategory(value)
	{
		if(value == 1) {
			$("#li_cuisine").show();
		} else {
			$("#li_cuisine").hide();
		}
	}

	var bak = [];
var timerid;
var xml_http = new XMLHttpRequest();

$(document).ready(function() {
	
	(autoComplete={
		pop_len:10,
		pop_cn:'keywordDis',
		hover_cn:'cur',
		source:'',
		init:function(){
			this.setDom();
			return this;
		},
		bind:function(x){
			if(x) {
				if(x.getAttribute('type') != 'text' || x.nodeName != 'INPUT')
					return null;
				var self = this;
				x.onkeyup = function(e){
					bak = [];
					e = e || window.event;
					var keycode = e.keyCode;
					var name_target = this;
					clearTimeout(timerid);
					var lis = self.pop.getElementsByTagName('li'),lens = self.pop.getElementsByTagName('li').length,n=lens,temp;
					if(keycode == 38){
						if(self.pop.style.display != 'none'){
							for(var i=0;i<lens;i++){
								if(lis[i].className)
									temp = i;
								else
									n--;
							}
							if(n==0){                                                
								lis[lens-1].className = self.hover_cn;
								name_target.value = lis[lens-1].innerHTML;
							}else{                                                    
								if(lis[temp] == lis[0]){                        
									lis[lens-1].className = self.hover_cn;
									name_target.value = lis[lens-1].innerHTML;
									lis[temp].className = '';
								}else{                                               
									lis[temp-1].className = self.hover_cn;
									name_target.value = lis[temp-1].innerHTML;
									lis[temp].className = '';
								}
							}
						}else                                                
							self.insert(name_target);
					}else if(keycode == 40){
						if(self.pop.style.display != 'none'){
							for(var i=0;i<lens;i++){
								if(lis[i].className)
									temp = i;
								else
									n--;
							}
							if(n==0){
								lis[0].className = self.hover_cn;
								name_target.value = lis[0].innerHTML;
							}else{
								if(lis[temp] == lis[lens-1]){
									lis[0].className = self.hover_cn;
									name_target.value = lis[0].innerHTML;
									lis[temp].className = '';
								}else{
									lis[temp+1].className = self.hover_cn;
									name_target.value = lis[temp+1].innerHTML;
									lis[temp].className = '';
								}
							}
						}else
							self.insert(name_target);
					}else{
						timerid = setTimeout(function(){
							xml_http.abort();
							xml_http = $.post('/leftmenuajax.php',{'opt':'getajax','search_type':'foodwine','type':'keyword','keyword':$("#keyword_k").val(),'limit':20},function(data){
								if(data) {
									//alert(data);
									var json=eval(data); 
									$.each(json, function(k)
									 {
										bak.push(json[k]);
									 }); 
									
								}
								                                
								self.insert(name_target);   
							});
						}, 1000);
					}
				};
				x.onblur = function(){                
					setTimeout(function(){self.pop.style.display='none';},300);
					if($("#keyword_k").val() == '') {
						blurkeyword(self, false);
					}
				};
			}
			return this;
		},
		setDom:function(){
			var self = this;
			var dom = document.createElement('div'),frame=document.createElement('iframe'),ul=document.createElement('ul');
			document.body.appendChild(dom);
			with(frame){                                    
				setAttribute('frameborder','0');
				setAttribute('scrolling','no');
				style.cssText='z-index:-1;position:absolute;left:0;top:0;'
			}
			with(dom){                                        
				className = this.pop_cn;
				appendChild(frame);
				appendChild(ul);
				onmouseover  = function(e){            
					e = e || window.event;
					var target = e.srcElement || e.target;
					if(target.tagName == 'LI'){            
						for(var i=0,lis=self.pop.getElementsByTagName('li');i<lis.length;i++)
							lis[i].className = '';
						target.className=self.hover_cn;        
					}
				};
				onmouseout = function(e){
					e = e || window.event;
					var target = e.srcElement || e.target;
					if(target.tagName == 'LI')
						target.className='';
				};
			}
			this.pop = dom;
		},
		insert:function(self){
			var s,li=[],left=0,top=0,val=self.value;
			if(bak.length == 0){                                                    
				this.pop.style.display='none';
				return false;
			}
			left=self.getBoundingClientRect().left+document.documentElement.scrollLeft;
			top=self.getBoundingClientRect().top+document.documentElement.scrollTop+self.offsetHeight;
			with(this.pop){
				style.cssText = 'min-width:'+(self.offsetWidth-2)+'px;'+'position:absolute;left:'+left+'px;top:'+top+'px;display:none;z-index:1000;';
				getElementsByTagName('iframe')[0].setAttribute('width',self.offsetWidth);
				getElementsByTagName('iframe')[0].setAttribute('height',self.offsetHeight);
				onclick = function(e){
					e = e || window.event;
					var target = e.srcElement || e.target;
					if(target.tagName == 'LI')
						self.value = target.innerHTML;
					this.style.display='none';
				};
			}
			s = bak.length>this.pop_len?this.pop_len:bak.length;
			for(var i=0;i<s;i++)
				li.push( '<li>' + bak[i] +'</li>');
			this.pop.getElementsByTagName('ul')[0].innerHTML = li.join('');
			this.pop.style.display='block';
		}
	}).init().bind(document.getElementById('keyword_k'));
})/* Nifty Corners Cube - rounded corners with CSS and Javascript
Copyright 2006 Alessandro Fulciniti (a.fulciniti@html.it)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

var niftyOk=(document.getElementById && document.createElement && Array.prototype.push);
var niftyCss=false;

String.prototype.find=function(what){
	return(this.indexOf(what)>=0 ? true : false);
}

var oldonload=window.onload;
if(typeof(NiftyLoad)!='function') NiftyLoad=function(){};
if(typeof(oldonload)=='function')
window.onload=function(){oldonload();AddCss();NiftyLoad()};
else window.onload=function(){AddCss();NiftyLoad()};

function AddCss(){
	niftyCss=true;
	var l=CreateEl("link");
	l.setAttribute("type","text/css");
	l.setAttribute("rel","stylesheet");
	l.setAttribute("href","/skin/red/css/niftyCorners.css");
	l.setAttribute("media","screen");
	document.getElementsByTagName("head")[0].appendChild(l);
}

function Nifty(selector,options){
	if(niftyOk==false) return;
	if(niftyCss==false) AddCss();
	var i,v=selector.split(","),h=0;
	if(options==null) options="";
	if(options.find("fixed-height"))
	h=getElementsBySelector(v[0])[0].offsetHeight;
	for(i=0;i<v.length;i++)
	Rounded(v[i],options);
	if(options.find("height")) SameHeight(selector,h);
}

function Rounded(selector,options){
	var i,top="",bottom="",v=new Array();
	if(options!=""){
		options=options.replace("left","tl bl");
		options=options.replace("right","tr br");
		options=options.replace("top","tr tl");
		options=options.replace("bottom","br bl");
		options=options.replace("transparent","alias");
		if(options.find("tl")){
			top="both";
			if(!options.find("tr")) top="left";
		}
		else if(options.find("tr")) top="right";
		if(options.find("bl")){
			bottom="both";
			if(!options.find("br")) bottom="left";
		}
		else if(options.find("br")) bottom="right";
	}
	if(top=="" && bottom=="" && !options.find("none")){top="both";bottom="both";}
	v=getElementsBySelector(selector);
	for(i=0;i<v.length;i++){
		FixIE(v[i]);
		if(top!="") AddTop(v[i],top,options);
		if(bottom!="") AddBottom(v[i],bottom,options);
	}
}

function AddTop(el,side,options){
	try{
		var d=CreateEl("b"),lim=4,border="",p,i,btype="r",bk,color;
		d.style.marginLeft="-"+getPadding(el,"Left")+"px";
		d.style.marginRight="-"+getPadding(el,"Right")+"px";
		if(options.find("alias") || (color=getBk(el))=="transparent"){
			color="transparent";bk="transparent"; border=getParentBk(el);btype="t";
		}
		else{
			bk=getParentBk(el); border=Mix(color,bk);
		}
		d.style.background=bk;
		d.className="niftycorners";
		p=getPadding(el,"Top");
		if(options.find("small")){
			d.style.marginBottom=(p-2)+"px";
			btype+="s"; lim=2;
		}
		else if(options.find("big")){
			d.style.marginBottom=(p-10)+"px";
			btype+="b"; lim=8;
		}
		else d.style.marginBottom=(p-5)+"px";
		for(i=1;i<=lim;i++)
		d.appendChild(CreateStrip(i,side,color,border,btype));
		el.style.paddingTop="0";
		el.insertBefore(d,el.firstChild);
	}catch(ex){;}
}

function AddBottom(el,side,options){
	try{
		var d=CreateEl("b"),lim=4,border="",p,i,btype="r",bk,color;
		d.style.marginLeft="-"+getPadding(el,"Left")+"px";
		d.style.marginRight="-"+getPadding(el,"Right")+"px";
		if(options.find("alias") || (color=getBk(el))=="transparent"){
			color="transparent";bk="transparent"; border=getParentBk(el);btype="t";
		}else{
			bk=getParentBk(el); border=Mix(color,bk);
		}
		d.style.background=bk;
		d.className="niftycorners";
		p=getPadding(el,"Bottom");
		if(options.find("small")){
			d.style.marginTop=(p-2)+"px";
			btype+="s"; lim=2;
		}
		else if(options.find("big")){
			d.style.marginTop=(p-10)+"px";
			btype+="b"; lim=8;
		}
		else d.style.marginTop=(p-5)+"px";
		for(i=lim;i>0;i--)
		d.appendChild(CreateStrip(i,side,color,border,btype));
		el.style.paddingBottom=0;
		el.appendChild(d);
	}catch(ex){;}
}

function CreateStrip(index,side,color,border,btype){
	var x=CreateEl("b");
	x.className=btype+index;
	x.style.backgroundColor=color;
	x.style.borderColor=border;
	if(side=="left"){
		x.style.borderRightWidth="0";
		x.style.marginRight="0";
	}
	else if(side=="right"){
		x.style.borderLeftWidth="0";
		x.style.marginLeft="0";
	}
	return(x);
}

function CreateEl(x){
	return(document.createElement(x));
}

function FixIE(el){
	if(el.currentStyle!=null && el.currentStyle.hasLayout!=null && el.currentStyle.hasLayout==false)
	el.style.display="inline-block";
}

function SameHeight(selector,maxh){
	var i,v=selector.split(","),t,j,els=[],gap;
	for(i=0;i<v.length;i++){
		t=getElementsBySelector(v[i]);
		els=els.concat(t);
	}
	for(i=0;i<els.length;i++){
		if(els[i].offsetHeight>maxh) maxh=els[i].offsetHeight;
		els[i].style.height="auto";
	}
	for(i=0;i<els.length;i++){
		gap=maxh-els[i].offsetHeight;
		if(gap>0){
			t=CreateEl("b");t.className="niftyfill";t.style.height=gap+"px";
			nc=els[i].lastChild;
			if(nc.className=="niftycorners")
			els[i].insertBefore(t,nc);
			else els[i].appendChild(t);
		}
	}
}

function getElementsBySelector(selector){
	var i,j,selid="",selclass="",tag=selector,tag2="",v2,k,f,a,s=[],objlist=[],c;
	if(selector.find("#")){ //id selector like "tag#id"
		if(selector.find(" ")){  //descendant selector like "tag#id tag"
			s=selector.split(" ");
			var fs=s[0].split("#");
			if(fs.length==1) return(objlist);
			f=document.getElementById(fs[1]);
			if(f){
				v=f.getElementsByTagName(s[1]);
				for(i=0;i<v.length;i++) objlist.push(v[i]);
			}
			return(objlist);
		}
		else{
			s=selector.split("#");
			tag=s[0];
			selid=s[1];
			if(selid!=""){
				f=document.getElementById(selid);
				if(f) objlist.push(f);
				return(objlist);
			}
		}
	}
	if(selector.find(".")){      //class selector like "tag.class"
		s=selector.split(".");
		tag=s[0];
		selclass=s[1];
		if(selclass.find(" ")){   //descendant selector like tag1.classname tag2
			s=selclass.split(" ");
			selclass=s[0];
			tag2=s[1];
		}
	}
	var v=document.getElementsByTagName(tag);  // tag selector like "tag"
	if(selclass==""){
		for(i=0;i<v.length;i++) objlist.push(v[i]);
		return(objlist);
	}
	for(i=0;i<v.length;i++){
		c=v[i].className.split(" ");
		for(j=0;j<c.length;j++){
			if(c[j]==selclass){
				if(tag2=="") objlist.push(v[i]);
				else{
					v2=v[i].getElementsByTagName(tag2);
					for(k=0;k<v2.length;k++) objlist.push(v2[k]);
				}
			}
		}
	}
	return(objlist);
}

function getParentBk(x){
	var el=x.parentNode,c;
	while(el.tagName.toUpperCase()!="HTML" && (c=getBk(el))=="transparent")
	el=el.parentNode;
	if(c=="transparent") c="#FFFFFF";
	return(c);
}

function getBk(x){
	var c=getStyleProp(x,"backgroundColor");
	if(c==null || c=="transparent" || c.find("rgba(0, 0, 0, 0)"))
	return("transparent");
	if(c.find("rgb")) c=rgb2hex(c);
	return(c);
}

function getPadding(x,side){
	var p=getStyleProp(x,"padding"+side);
	if(p==null || !p.find("px")) return(0);
	return(parseInt(p));
}

function getStyleProp(x,prop){
	if(x.currentStyle)
	return(x.currentStyle[prop]);
	if(document.defaultView.getComputedStyle)
	return(document.defaultView.getComputedStyle(x,'')[prop]);
	return(null);
}

function rgb2hex(value){
	var hex="",v,h,i;
	var regexp=/([0-9]+)[, ]+([0-9]+)[, ]+([0-9]+)/;
	var h=regexp.exec(value);
	for(i=1;i<4;i++){
		v=parseInt(h[i]).toString(16);
		if(v.length==1) hex+="0"+v;
		else hex+=v;
	}
	return("#"+hex);
}

function Mix(c1,c2){
	var i,step1,step2,x,y,r=new Array(3);
	if(c1.length==4)step1=1;
	else step1=2;
	if(c2.length==4) step2=1;
	else step2=2;
	for(i=0;i<3;i++){
		x=parseInt(c1.substr(1+step1*i,step1),16);
		if(step1==1) x=16*x+x;
		y=parseInt(c2.substr(1+step2*i,step2),16);
		if(step2==1) y=16*y+y;
		r[i]=Math.floor((x*50+y*50)/100);
		r[i]=r[i].toString(16);
		if(r[i].length==1) r[i]="0"+r[i];
	}
	return("#"+r[0]+r[1]+r[2]);
}/* Copyright (c) 2006 Yahoo! Inc. All rights reserved. */var YAHOO=window.YAHOO||{};YAHOO.namespace=function(_1){if(!_1||!_1.length){return null;}var _2=_1.split(".");var _3=YAHOO;for(var i=(_2[0]=="YAHOO")?1:0;i<_2.length;++i){_3[_2[i]]=_3[_2[i]]||{};_3=_3[_2[i]];}return _3;};YAHOO.namespace("util");YAHOO.namespace("widget");YAHOO.namespace("example");

/* Copyright (c) 2006, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.net/yui/license.txt */ YAHOO.util.CustomEvent=function(_1,_2){this.type=_1;this.scope=_2||window;this.subscribers=[];if(YAHOO.util.Event){YAHOO.util.Event.regCE(this);}};YAHOO.util.CustomEvent.prototype={subscribe:function(fn,_4,_5){this.subscribers.push(new YAHOO.util.Subscriber(fn,_4,_5));},unsubscribe:function(fn,_6){var _7=false;for(var i=0,len=this.subscribers.length;i<len;++i){var s=this.subscribers[i];if(s&&s.contains(fn,_6)){this._delete(i);_7=true;}}return _7;},fire:function(){for(var i=0,len=this.subscribers.length;i<len;++i){var s=this.subscribers[i];if(s){var _10=(s.override)?s.obj:this.scope;s.fn.call(_10,this.type,arguments,s.obj);}}},unsubscribeAll:function(){for(var i=0,len=this.subscribers.length;i<len;++i){this._delete(i);}},_delete:function(_11){var s=this.subscribers[_11];if(s){delete s.fn;delete s.obj;}delete this.subscribers[_11];}};YAHOO.util.Subscriber=function(fn,obj,_13){this.fn=fn;this.obj=obj||null;this.override=(_13);};YAHOO.util.Subscriber.prototype.contains=function(fn,obj){return (this.fn==fn&&this.obj==obj);};if(!YAHOO.util.Event){YAHOO.util.Event=function(){var _14=false;var _15=[];var _16=[];var _17=[];var _18=[];var _19=[];var _20=[];var _21=[];var _22=0;var _23=[];var _24=0;var _25={};return {POLL_RETRYS:200,POLL_INTERVAL:50,EL:0,TYPE:1,FN:2,WFN:3,SCOPE:3,ADJ_SCOPE:4,isSafari:(/Safari|Konqueror|KHTML/gi).test(navigator.userAgent),isIE:(!this.isSafari&&!navigator.userAgent.match(/opera/gi)&&navigator.userAgent.match(/msie/gi)),addDelayedListener:function(el,_27,fn,_28,_29){_16[_16.length]=[el,_27,fn,_28,_29];if(_14){_22=this.POLL_RETRYS;this.startTimeout(0);}},startTimeout:function(_30){var _31=this;var _32=function(){_31._tryPreloadAttach();};this.timeout=setTimeout(_32,_30);},onAvailable:function(_33,_34,_35,_36){_23.push({id:_33,fn:_34,obj:_35,override:_36});_22=this.POLL_RETRYS;this.startTimeout(0);},addListener:function(el,_37,fn,_38,_39){if(!fn||!fn.call){return false;}if(this._isValidCollection(el)){var ok=true;for(var i=0,len=el.length;i<len;++i){ok=(this.on(el[i],_37,fn,_38,_39)&&ok);}return ok;}else{if(typeof el=="string"){var oEl=this.getEl(el);if(_14&&oEl){el=oEl;}else{this.addDelayedListener(el,_37,fn,_38,_39);return true;}}}if(!el){return false;}if("unload"==_37&&_38!==this){_17[_17.length]=[el,_37,fn,_38,_39];return true;}var _42=(_39)?_38:el;var _43=function(e){return fn.call(_42,YAHOO.util.Event.getEvent(e),_38);};var li=[el,_37,fn,_43,_42];var _46=_15.length;_15[_46]=li;this.mapListener(el,_37,fn,_46);if(this.useLegacyEvent(el,_37)){var _47=this.getLegacyIndex(el,_37);if(_47==-1){_47=_19.length;_21[el.id+_37]=_47;_19[_47]=[el,_37,el["on"+_37]];_20[_47]=[];el["on"+_37]=function(e){return YAHOO.util.Event.fireLegacyEvent(YAHOO.util.Event.getEvent(e),_47);};}_20[_47].push(_46);}else{if(el.addEventListener){el.addEventListener(_37,_43,false);}else{if(el.attachEvent){el.attachEvent("on"+_37,_43);}}}return true;},fireLegacyEvent:function(e,_48){var ok=true;var le=_20[_48];for(var i=0,len=le.length;i<len;++i){var _50=le[i];if(_50){var li=_15[_50];if(li&&li[this.WFN]){var _51=li[this.ADJ_SCOPE];var ret=li[this.WFN].call(_51,e);ok=(ok&&ret);}else{delete le[i];}}}return ok;},getLegacyIndex:function(el,_53){var key=this.generateId(el)+_53;if(typeof _21[key]=="undefined"){return -1;}else{return _21[key];}},useLegacyEvent:function(el,_55){return ((!el.addEventListener&&!el.attachEvent)||(_55=="click"&&this.isSafari));},removeListener:function(el,_56,fn,_57){if(!fn||!fn.call){return false;}if(typeof el=="string"){el=this.getEl(el);}else{if(this._isValidCollection(el)){var ok=true;for(var i=0,len=el.length;i<len;++i){ok=(this.removeListener(el[i],_56,fn)&&ok);}return ok;}}if("unload"==_56){for(i=0,len=_17.length;i<len;i++){var li=_17[i];if(li&&li[0]==el&&li[1]==_56&&li[2]==fn){delete _17[i];return true;}}return false;}var _58=null;if("undefined"==typeof _57){_57=this._getCacheIndex(el,_56,fn);}if(_57>=0){_58=_15[_57];}if(!el||!_58){return false;}if(el.removeEventListener){el.removeEventListener(_56,_58[this.WFN],false);}else{if(el.detachEvent){el.detachEvent("on"+_56,_58[this.WFN]);}}delete _15[_57][this.WFN];delete _15[_57][this.FN];delete _15[_57];return true;},getTarget:function(ev,_60){var t=ev.target||ev.srcElement;if(_60&&t&&"#text"==t.nodeName){return t.parentNode;}else{return t;}},getPageX:function(ev){var x=ev.pageX;if(!x&&0!==x){x=ev.clientX||0;if(this.isIE){x+=this._getScrollLeft();}}return x;},getPageY:function(ev){var y=ev.pageY;if(!y&&0!==y){y=ev.clientY||0;if(this.isIE){y+=this._getScrollTop();}}return y;},getXY:function(ev){return [this.getPageX(ev),this.getPageY(ev)];},getRelatedTarget:function(ev){var t=ev.relatedTarget;if(!t){if(ev.type=="mouseout"){t=ev.toElement;}else{if(ev.type=="mouseover"){t=ev.fromElement;}}}return t;},getTime:function(ev){if(!ev.time){var t=new Date().getTime();try{ev.time=t;}catch(e){return t;}}return ev.time;},stopEvent:function(ev){this.stopPropagation(ev);this.preventDefault(ev);},stopPropagation:function(ev){if(ev.stopPropagation){ev.stopPropagation();}else{ev.cancelBubble=true;}},preventDefault:function(ev){if(ev.preventDefault){ev.preventDefault();}else{ev.returnValue=false;}},getEvent:function(e){var ev=e||window.event;if(!ev){var c=this.getEvent.caller;while(c){ev=c.arguments[0];if(ev&&Event==ev.constructor){break;}c=c.caller;}}return ev;},getCharCode:function(ev){return ev.charCode||((ev.type=="keypress")?ev.keyCode:0);},_getCacheIndex:function(el,_65,fn){var key=el.id+_65;if(!_25[key]){return -1;}else{for(var i=0,len=_25[key].length;i<len;++i){var _66=_25[key][i];if(_66.fn==fn){return _66.index;}}}return -1;},generateId:function(el){var id=el.id;if(!id){id="yui-event-auto-id-"+(_24++);el.id=id;}return id;},mapListener:function(_68,_69,_70,_71){var key=this.generateId(_68)+_69;if(!_25[key]){_25[key]=[];}_25[key].push({fn:_70,index:_71});},_isValidCollection:function(o){return (o&&o.length&&typeof o!="string"&&!o.tagName&&!o.alert&&typeof o[0]!="undefined");},getEl:function(id){return document.getElementById(id);},clearCache:function(){},regCE:function(ce){_18.push(ce);},_load:function(e){_14=true;},_tryPreloadAttach:function(){if(this.locked){return false;}this.locked=true;var _74=!_14;if(!_74){_74=(_22>0);}var _75=[];for(var i=0,len=_16.length;i<len;++i){var d=_16[i];if(d){var el=this.getEl(d[this.EL]);if(el){this.on(el,d[this.TYPE],d[this.FN],d[this.SCOPE],d[this.ADJ_SCOPE]);delete _16[i];}else{_75.push(d);}}}_16=_75;notAvail=[];for(i=0,len=_23.length;i<len;++i){var _77=_23[i];if(_77){el=this.getEl(_77.id);if(el){var _78=(_77.override)?_77.obj:el;_77.fn.call(_78,_77.obj);delete _23[i];}else{notAvail.push(_77);}}}_22=(_75.length===0&&notAvail.length===0)?0:_22-1;if(_74){this.startTimeout(this.POLL_INTERVAL);}this.locked=false;},_unload:function(e,me,_80){for(var i=0,len=_17.length;i<len;++i){var l=_17[i];if(l){var _82=(l[this.ADJ_SCOPE])?l[this.SCOPE]:window;l[this.FN].call(_82,this.getEvent(e),l[this.SCOPE]);}}len=_15.length;if(len){for(i=0;i<len;++i){l=_15[i];if(l){this.removeListener(l[this.EL],l[this.TYPE],l[this.FN],i);}}this.clearCache();}for(i=0,len=_18.length;i<len;++i){_18[i].unsubscribeAll();delete _18[i];}for(i=0,len=_19.length;i<len;++i){delete _19[i];}},_getScrollLeft:function(){return this._getScroll()[1];},_getScrollTop:function(){return this._getScroll()[0];},_getScroll:function(){var dd=document.documentElement;db=document.body;if(dd&&dd.scrollTop){return [dd.scrollTop,dd.scrollLeft];}else{if(db){return [db.scrollTop,db.scrollLeft];}else{return [0,0];}}}};}();YAHOO.util.Event.on=YAHOO.util.Event.addListener;if(document&&document.body){YAHOO.util.Event._load();}else{YAHOO.util.Event.on(window,"load",YAHOO.util.Event._load,YAHOO.util.Event,true);}YAHOO.util.Event.on(window,"unload",YAHOO.util.Event._unload,YAHOO.util.Event,true);YAHOO.util.Event._tryPreloadAttach();}

/* Copyright (c) 2006, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.net/yui/license.txt */YAHOO.util.Dom=function(){var ua=navigator.userAgent.toLowerCase();var id_counter=0;return{get:function(el){if(typeof el!='string'&&!(el instanceof Array)){return el;}if(typeof el=='string'){return document.getElementById(el);}else{var collection=[];for(var i=0,len=el.length;i<len;++i){collection[collection.length]=this.get(el[i]);}return collection;}return null;},getStyle:function(el,property){var f=function(el,self){var value=null;var dv=document.defaultView;if(property=='opacity'&&el.filters){value=1;try{value=el.filters.item('DXImageTransform.Microsoft.Alpha').opacity/100;}catch(e){try{value=el.filters.item('alpha').opacity/100;}catch(e){}}}else if(el.style[property]){value=el.style[property];}else if(el.currentStyle&&el.currentStyle[property]){value=el.currentStyle[property];}else if(dv&&dv.getComputedStyle){var converted='';for(var i=0,len=property.length;i<len;++i){if(property.charAt(i)==property.charAt(i).toUpperCase()){converted=converted+'-'+property.charAt(i).toLowerCase();}else{converted=converted+property.charAt(i);}}if(dv.getComputedStyle(el,'')&&dv.getComputedStyle(el,'').getPropertyValue(converted)){value=dv.getComputedStyle(el,'').getPropertyValue(converted);}}return value;};return this.batch(el,f,this);},setStyle:function(el,property,val){var f=function(el,self){switch(property){case'opacity':if(el.filters){el.style.filter='alpha(opacity='+val*100+')';if(!el.currentStyle.hasLayout){el.style.zoom=1;}}else{el.style.opacity=val;el.style['-moz-opacity']=val;el.style['-khtml-opacity']=val;}break;default:el.style[property]=val;}};this.batch(el,f,this);},getXY:function(el){var f=function(el,self){if(el.parentNode===null||self.getStyle(el,'display')=='none'){return false;}var parent=null;var pos=[];var box;if(el.getBoundingClientRect){box=el.getBoundingClientRect();Math.max(document.documentElement.scrollTop,document.body.scrollTop);var scrollTop=Math.max(document.documentElement.scrollTop,document.body.scrollTop);var scrollLeft=Math.max(document.documentElement.scrollLeft,document.body.scrollLeft);return[box.left+scrollLeft,box.top+scrollTop];}else if(document.getBoxObjectFor){box=document.getBoxObjectFor(el);pos=[box.x,box.y];}else{pos=[el.offsetLeft,el.offsetTop];parent=el.offsetParent;if(parent!=el){while(parent){pos[0]+=parent.offsetLeft;pos[1]+=parent.offsetTop;parent=parent.offsetParent;}}if(ua.indexOf('opera')!=-1||(ua.indexOf('safari')!=-1&&self.getStyle(el,'position')=='absolute')){pos[0]-=document.body.offsetLeft;pos[1]-=document.body.offsetTop;}}if(el.parentNode){parent=el.parentNode;}else{parent=null;}while(parent&&parent.tagName!='BODY'&&parent.tagName!='HTML'){pos[0]-=parent.scrollLeft;pos[1]-=parent.scrollTop;if(parent.parentNode){parent=parent.parentNode;}else{parent=null;}}return pos;};return this.batch(el,f,this);},getX:function(el){return this.getXY(el)[0];},getY:function(el){return this.getXY(el)[1];},setXY:function(el,pos,noRetry){var f=function(el,self){var style_pos=self.getStyle(el,'position');if(style_pos=='static'){self.setStyle(el,'position','relative');style_pos='relative';}var pageXY=YAHOO.util.Dom.getXY(el);if(pageXY===false){return false;}var delta=[parseInt(YAHOO.util.Dom.getStyle(el,'left'),10),parseInt(YAHOO.util.Dom.getStyle(el,'top'),10)];if(isNaN(delta[0])){delta[0]=(style_pos=='relative')?0:el.offsetLeft;}if(isNaN(delta[1])){delta[1]=(style_pos=='relative')?0:el.offsetTop;}if(pos[0]!==null){el.style.left=pos[0]-pageXY[0]+delta[0]+'px';}if(pos[1]!==null){el.style.top=pos[1]-pageXY[1]+delta[1]+'px';}var newXY=self.getXY(el);if(!noRetry&&(newXY[0]!=pos[0]||newXY[1]!=pos[1])){var retry=function(){YAHOO.util.Dom.setXY(el,pos,true);};setTimeout(retry,0);}};this.batch(el,f,this);},setX:function(el,x){this.setXY(el,[x,null]);},setY:function(el,y){this.setXY(el,[null,y]);},getRegion:function(el){var f=function(el,self){return new YAHOO.util.Region.getRegion(el);};return this.batch(el,f,this);},getClientWidth:function(){return(document.documentElement.offsetWidth||document.body.offsetWidth);},getClientHeight:function(){return(self.innerHeight||document.documentElement.clientHeight||document.body.clientHeight);},getElementsByClassName:function(className,tag,root){var re=new RegExp('(?:^|\\s+)'+className+'(?:\\s+|$)');var method=function(el){return re.test(el['className']);};return this.getElementsBy(method,tag,root);},hasClass:function(el,className){var f=function(el,self){var re=new RegExp('(?:^|\\s+)'+className+'(?:\\s+|$)');return re.test(el['className']);};return this.batch(el,f,this);},addClass:function(el,className){var f=function(el,self){if(self.hasClass(el,className)){return;}el['className']=[el['className'],className].join(' ');};this.batch(el,f,this);},removeClass:function(el,className){var f=function(el,self){if(!self.hasClass(el,className)){return;}var re=new RegExp('(?:^|\\s+)'+className+'(?:\\s+|$)','g');var c=el['className'];el['className']=c.replace(re,' ');};this.batch(el,f,this);},replaceClass:function(el,oldClassName,newClassName){var f=function(el,self){self.removeClass(el,oldClassName);self.addClass(el,newClassName);};this.batch(el,f,this);},generateId:function(el,prefix){prefix=prefix||'yui-gen';var f=function(el,self){el=el||{};if(!el.id){el.id=prefix+id_counter++;}return el.id;};return this.batch(el,f,this);},isAncestor:function(haystack,needle){haystack=this.get(haystack);if(!haystack||!needle){return false;}var f=function(needle,self){if(haystack.contains&&ua.indexOf('safari')<0){return haystack.contains(needle);}else if(haystack.compareDocumentPosition){return!!(haystack.compareDocumentPosition(needle)&16);}else{var parent=needle.parentNode;while(parent){if(parent==haystack){return true;}else if(parent.tagName=='HTML'){return false;}parent=parent.parentNode;}return false;}};return this.batch(needle,f,this);},inDocument:function(el){var f=function(el,self){return self.isAncestor(document.documentElement,el);};return this.batch(el,f,this);},getElementsBy:function(method,tag,root){tag=tag||'*';root=this.get(root)||document;var nodes=[];var elements=root.getElementsByTagName(tag);for(var i=0,len=elements.length;i<len;++i){if(method(elements[i])){nodes[nodes.length]=elements[i];}}return nodes;},batch:function(el,method,o){el=this.get(el);if(!el||!el.length){return method(el,o);}var collection=[];for(var i=0,len=el.length;i<len;++i){collection[collection.length]=method(el[i],o);}return collection;}};}();YAHOO.util.Region=function(t,r,b,l){this.top=t;this.right=r;this.bottom=b;this.left=l;};YAHOO.util.Region.prototype.contains=function(region){return(region.left>=this.left&&region.right<=this.right&&region.top>=this.top&&region.bottom<=this.bottom);};YAHOO.util.Region.prototype.getArea=function(){return((this.bottom-this.top)*(this.right-this.left));};YAHOO.util.Region.prototype.intersect=function(region){var t=Math.max(this.top,region.top);var r=Math.min(this.right,region.right);var b=Math.min(this.bottom,region.bottom);var l=Math.max(this.left,region.left);if(b>=t&&r>=l){return new YAHOO.util.Region(t,r,b,l);}else{return null;}};YAHOO.util.Region.prototype.union=function(region){var t=Math.min(this.top,region.top);var r=Math.max(this.right,region.right);var b=Math.max(this.bottom,region.bottom);var l=Math.min(this.left,region.left);return new YAHOO.util.Region(t,r,b,l);};YAHOO.util.Region.prototype.toString=function(){return("Region {"+"  t: "+this.top+", r: "+this.right+", b: "+this.bottom+", l: "+this.left+"}");};YAHOO.util.Region.getRegion=function(el){var p=YAHOO.util.Dom.getXY(el);var t=p[1];var r=p[0]+el.offsetWidth;var b=p[1]+el.offsetHeight;var l=p[0];return new YAHOO.util.Region(t,r,b,l);};YAHOO.util.Point=function(x,y){this.x=x;this.y=y;this.top=y;this.right=x;this.bottom=y;this.left=x;};YAHOO.util.Point.prototype=new YAHOO.util.Region();

YAHOO.namespace( 'YAHOO.Hack' ).FixIESelectWidth = new function()
{
	var oSelf = this; 
	var YUE = YAHOO.util.Event;
	var YUD = YAHOO.util.Dom;
	var oTimer = {};
	var oAnim = {};
	var nTimerId =  0 ;
	var dLastFocalItem;
	var ie7 = !!(document.uniqueID  &&   typeof(XMLHttpRequest)!='undefined' )
	function init(el)
	{
		
		
		el = el || this;
		
		

		if( el.tagName.toLowerCase() != 'select')
		{
			throw Error('element [' + el.id + '] is not <select>');
			return;
		};	
		
		if(!YUD.hasClass( el.parentNode, 'select-box'))
		{
			throw Error('className select-box is not included for element [' + el.id + ']');
			return;
		};	
		
		var oRs = el.runtimeStyle;
		var oPRs = el.parentNode.runtimeStyle;
		
		
		oPRs.fonSize = 0;
		
		
		var sDisplay = el.parentNode.currentStyle.display.toLowerCase() ;
		if(  sDisplay=='' ||  sDisplay=='inline' ||  sDisplay=='inline-block' )
		{
			oPRs.display = 'inline-block';
			oPRs.width = el.offsetWidth + 'px';
			oPRs.height =el.offsetHeight + 'px';
			oPRs.position = 'relative';
			oRs.position = 'absolute';
			oRs.top = 0;
			oRs.left = 0;
		};
		
		
		
		el._timerId = ( nTimerId+=1 );

		el.selectedIndex = Math.max( 0 , el.selectedIndex );
		
		oTimer[ '_' + el._timerId ] = setTimeout('void(0)',0);
		oAnim [ 'A' + el._timerId ] = setTimeout('void(0)',0);
		
		YUE.on( el, 'mouseover' , onMouseOver);
		YUE.on( document, 'mousedown' ,onMouseDown , el, true);
		YUE.on(  el, 'change' ,collapseSelect , el, true);
	}


	function collapseSelect(e)
	{
		status++;
		this.runtimeStyle.width = '';			
	}

	function onMouseOver(e )
	{
	
		var el = this;	
		if(dLastFocalItem && dLastFocalItem !=el)
		{
			 onMouseDown.call( dLastFocalItem , e );
		};

		var sTimerId ='_' +  el._timerId ;
		var sAniId = 'A' + el._timerId ;
		clearTimeout( oTimer[ sTimerId ] );

		

		var onTween = function()
		{
			clearTimeout( oAnim [  sAniId  ] );
			if( Math.abs( nEndWidth - nStartWidth ) > 3 )
			{
				nStartWidth += (nEndWidth - nStartWidth ) /3;
				el.runtimeStyle.width = nStartWidth + 'px';
				oAnim [  sAniId  ] = setTimeout( onTween ,0 );
			}
			else
			{
				el.runtimeStyle.width = 'auto';
				el.selectedIndex = Math.max( 0 , el.selectedIndex );
			}
		}

		var nStartWidth =  el.offsetWidth ;
		el.runtimeStyle.width = 'auto';
		var nEndWidth  = el.offsetWidth;
		

		clearTimeout( oAnim [  sAniId  ] );
		onTween();

		el.focus();		
		dLastFocalItem = el;
	}

	function onMouseDown(e , el )
	{
		el = ( e.srcElement || e.target );
		
		
		
		if( el == this && e.type!='mouseover' )
		{
			status++;
			YUE.stopEvent(e);
			return false;
		};
		
		
		el = this;
		
		clearTimeout( oAnim [ 'A' + el._timerId ] );
	
		
		var sTimerId ='_' +  el._timerId ;
		var doItLater = function()
		{
			el.runtimeStyle.width = '';			
		};
		if( e.type=='mouseover')
		{ doItLater();}
		else{
			oTimer[ sTimerId ] = setTimeout(doItLater,100);
		}
	}

	

	function constructor(sId)
	{
		sId = [ sId , ''].join('');
		//Only fix for IE55 ~ IE7
		
		if(document.uniqueID && window.createPopup )
		{			
			YUE.onAvailable(sId ,init );
			return true;

		}else{return false};
	};

	return  constructor;
}
/**
 * SWFObject v1.4: Flash Player detection and embed - http://blog.deconcept.com/swfobject/
 *
 * SWFObject is (c) 2006 Geoff Stearns and is released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * **SWFObject is the SWF embed script formarly known as FlashObject. The name was changed for
 *   legal reasons.
 */
if(typeof deconcept=="undefined"){var deconcept=new Object();}
if(typeof deconcept.util=="undefined"){deconcept.util=new Object();}
if(typeof deconcept.SWFObjectUtil=="undefined"){deconcept.SWFObjectUtil=new Object();}
deconcept.SWFObject=function(_1,id,w,h,_5,c,_7,_8,_9,_a,_b){
if(!document.createElement||!document.getElementById){return;}
this.DETECT_KEY=_b?_b:"detectflash";
this.skipDetect=deconcept.util.getRequestParameter(this.DETECT_KEY);
this.params=new Object();
this.variables=new Object();
this.attributes=new Array();
if(_1){this.setAttribute("swf",_1);}
if(id){this.setAttribute("id",id);}
if(w){this.setAttribute("width",w);}
if(h){this.setAttribute("height",h);}
if(_5){this.setAttribute("version",new deconcept.PlayerVersion(_5.toString().split(".")));}
this.installedVer=deconcept.SWFObjectUtil.getPlayerVersion(this.getAttribute("version"),_7);
if(c){this.addParam("bgcolor",c);}
var q=_8?_8:"high";
this.addParam("quality",q);
this.setAttribute("useExpressInstall",_7);
this.setAttribute("doExpressInstall",false);
var _d=(_9)?_9:window.location;
this.setAttribute("xiRedirectUrl",_d);
this.setAttribute("redirectUrl","");
if(_a){this.setAttribute("redirectUrl",_a);}};
deconcept.SWFObject.prototype={setAttribute:function(_e,_f){
this.attributes[_e]=_f;
},getAttribute:function(_10){
return this.attributes[_10];
},addParam:function(_11,_12){
this.params[_11]=_12;
},getParams:function(){
return this.params;
},addVariable:function(_13,_14){
this.variables[_13]=_14;
},getVariable:function(_15){
return this.variables[_15];
},getVariables:function(){
return this.variables;
},getVariablePairs:function(){
var _16=new Array();
var key;
var _18=this.getVariables();
for(key in _18){
_16.push(key+"="+_18[key]);}
return _16;
},getSWFHTML:function(){
var _19="";
if(navigator.plugins&&navigator.mimeTypes&&navigator.mimeTypes.length){
if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","PlugIn");}
_19="<embed type=\"application/x-shockwave-flash\" src=\""+this.getAttribute("swf")+"\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\"";
_19+=" id=\""+this.getAttribute("id")+"\" name=\""+this.getAttribute("id")+"\" ";
var _1a=this.getParams();
for(var key in _1a){_19+=[key]+"=\""+_1a[key]+"\" ";}
var _1c=this.getVariablePairs().join("&");
if(_1c.length>0){_19+="flashvars=\""+_1c+"\"";}
_19+="/>";
}else{
if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","ActiveX");}
_19="<object id=\""+this.getAttribute("id")+"\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\">";
_19+="<param name=\"movie\" value=\""+this.getAttribute("swf")+"\" />";
var _1d=this.getParams();
for(var key in _1d){_19+="<param name=\""+key+"\" value=\""+_1d[key]+"\" />";}
var _1f=this.getVariablePairs().join("&");
if(_1f.length>0){_19+="<param name=\"flashvars\" value=\""+_1f+"\" />";}
_19+="</object>";}
return _19;
},write:function(_20){
if(this.getAttribute("useExpressInstall")){
var _21=new deconcept.PlayerVersion([6,0,65]);
if(this.installedVer.versionIsValid(_21)&&!this.installedVer.versionIsValid(this.getAttribute("version"))){
this.setAttribute("doExpressInstall",true);
this.addVariable("MMredirectURL",escape(this.getAttribute("xiRedirectUrl")));
document.title=document.title.slice(0,47)+" - Flash Player Installation";
this.addVariable("MMdoctitle",document.title);}}
if(this.skipDetect||this.getAttribute("doExpressInstall")||this.installedVer.versionIsValid(this.getAttribute("version"))){
var n=(typeof _20=="string")?document.getElementById(_20):_20;
n.innerHTML=this.getSWFHTML();
return true;
}else{
if(this.getAttribute("redirectUrl")!=""){document.location.replace(this.getAttribute("redirectUrl"));}}
return false;}};
deconcept.SWFObjectUtil.getPlayerVersion=function(_23,_24){
var _25=new deconcept.PlayerVersion([0,0,0]);
if(navigator.plugins&&navigator.mimeTypes.length){
var x=navigator.plugins["Shockwave Flash"];
if(x&&x.description){_25=new deconcept.PlayerVersion(x.description.replace(/([a-z]|[A-Z]|\s)+/,"").replace(/(\s+r|\s+b[0-9]+)/,".").split("."));}
}else{try{
var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
for(var i=3;axo!=null;i++){
axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+i);
_25=new deconcept.PlayerVersion([i,0,0]);}}
catch(e){}
if(_23&&_25.major>_23.major){return _25;}
if(!_23||((_23.minor!=0||_23.rev!=0)&&_25.major==_23.major)||_25.major!=6||_24){
try{_25=new deconcept.PlayerVersion(axo.GetVariable("$version").split(" ")[1].split(","));}
catch(e){}}}
return _25;};
deconcept.PlayerVersion=function(_29){
this.major=parseInt(_29[0])!=null?parseInt(_29[0]):0;
this.minor=parseInt(_29[1])||0;
this.rev=parseInt(_29[2])||0;};
deconcept.PlayerVersion.prototype.versionIsValid=function(fv){
if(this.major<fv.major){return false;}
if(this.major>fv.major){return true;}
if(this.minor<fv.minor){return false;}
if(this.minor>fv.minor){return true;}
if(this.rev<fv.rev){return false;}return true;};
deconcept.util={getRequestParameter:function(_2b){
var q=document.location.search||document.location.hash;
if(q){
var _2d=q.indexOf(_2b+"=");
var _2e=(q.indexOf("&",_2d)>-1)?q.indexOf("&",_2d):q.length;
if(q.length>1&&_2d>-1){
return q.substring(q.indexOf("=",_2d)+1,_2e);
}}return "";}};
if(Array.prototype.push==null){
Array.prototype.push=function(_2f){
this[this.length]=_2f;
return this.length;};}
var getQueryParamValue=deconcept.util.getRequestParameter;
var FlashObject=deconcept.SWFObject; // for backwards compatibility
var SWFObject=deconcept.SWFObject;

//=================ajax==================
/*
url 
request 
method 
container 
ispop default false
isvar default false
loading default false
*/
function ajaxLoadPage(url,request,method,container,ispop,isvar, loading){
	//alert(container);
	method=method.toUpperCase();
	var loading_msg='loading ...';
	var loader	= null;
	var popStatu = ""; //string of get pop page 
	
	
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		loader = new XMLHttpRequest();
		if (loader.overrideMimeType) {
			loader.overrideMimeType('text/xml');
		}
	} else if (window.ActiveXObject) { // IE
		var versions = [ 'MSXML6.XMLHTTP', 'Microsoft.XMLHTTP', 'MSXML5.XMLHTTP', 'MSXML4.XMLHTTP', 'MSXML3.XMLHTTP', 'MSXML2.XMLHTTP', 'MSXML.XMLHTTP'];

		for (var i = 0; i < versions.length; i ++ )
		{
			try
			{
			  loader = new ActiveXObject(versions[i]);
			  break;
			}
			catch (ex)
			{
			  continue;
			}
		}
	}
	
	if (!loader) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}
	
	if (method=='GET'){
		urls=url.split("?");
		if (urls[1]=='' || typeof urls[1]=='undefined'){
			url=urls[0]+"?"+request;
		}else{
			url=urls[0]+"?"+urls[1]+"&"+request;
		}
		request=null;//for GET method,loader should send NULL
	}
	//alert(url);

	loader.open(method,url,!isvar);
	
	if (method=="POST")	{
		loader.setRequestHeader("Content-Length",request.length);
		loader.setRequestHeader("Cache-Control","no-cache");
		loader.setRequestHeader('Content-Type',  "text/xml");
		//loader.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	}
	loader.onreadystatechange=function(){
		if (loader.readyState==1 && loading && !isvar && !ispop){
			container.innerHTML=loading_msg;
		}
		if (loader.readyState==4){
			//alert(request + "\n" +loader.responseText);
			if(ispop)
			{
				popStatu = loader.responseText;
				//Is it pop
				if(popStatu == "hasData"){
					mssagePop();
				}
			}
			else if (!isvar)
			{
				try{
				//HTML
				//handleResponse(loader.responseXML);
				container.innerHTML=loader.responseText;
				filtrate_css(loader.responseText);
				//var _d = document.createElement("style");		
				//_d.innerHTML=".aa{}";
				//var html_doc = document.getElementsByTagName( 'HEAD')[0];
				
				//container.appendChild(_d);
				//container.innerHTML="<style>.11111{}</style>";
				//alert(loader.responseText);
				//alert(html_doc);
				//alert(container.innerHTML);
				//insert_css(container,loader.responseText);
				//alert(loader.responseXML.documentElement);
				
				}catch(ex)
				{
					//alert(ex);
				}
			}
			else
			{
				//var
				container = loader.responseText;
			}
		}
	}
	loader.send(request);
	
	//
	if(isvar)
	{
		return	container;
	}
}

function formToRequestString(form_obj){
	var query_string='';
	var and='';
	//alert(form_obj.length);
	for (i=0;i<form_obj.length ;i++ ){
		e=form_obj[i];
		if (e.name != '' && e.type !="submit"){
			if (e.type=='select-one'){
				try{
					element_value=e.options[e.selectedIndex].value;
				}catch(ex){
				}
			}else if (e.type=='checkbox' || e.type=='radio'){
				if (e.checked==false){
					continue;	
				}
				//element_value=e.value;
				element_value=escape(e.value);
			}else{
				//element_value=e.value;
				element_value=escape(e.value);
			}
			query_string+=and+e.name+'='+element_value.replace(/\&/g,"%26");
			and="&";
		}	
	}
	return query_string;
}

function ajaxFormSubmit(form_obj,container){
	ajaxLoadPage(form_obj.getAttributeNode("action").value,formToRequestString(form_obj),form_obj.method,container)
}

/*
* AJAX 
* obj 		
* url 		
* param 	
* method 	
* obj2		
* value1 	
* value2 	
*/

function ajaxSubButton(obj,url,param,method,obj2,value1,value2)
{
	if(obj2.value == value1)
	{
		obj.style.display='';
		obj2.value = value2 == '' ? 'close' : value2;
		ajaxLoadPage(url,param,method,obj);
	}
	else
	{
		obj2.value = value1 == '' ? 'list' : value1;
		obj.style.display='none';
		obj.innerHTML='&nbsp;&nbsp;loading...';
	}
}

/* 
* 
* obj 			
* width 			Int				
* height 			Int				
* isShade 			Booblean		
* cleanContainer 	Booblean		
* isTop   			Booblean 		
* titles			String				
*/
function ajaxContentWindows2(obj,width,height,isShade,cleanContainer,isTop,titles){
	if (width == "" || width == null) 
		width = 300;
	if (height == "" || height == null)
		height = 300;
	if(cleanContainer == "" || cleanContainer==null) cleanContainer=false;	
	titles = typeof(titles) == "undefined" ? "" : titles ;
	printStr = "<object width='"+width+"' height='"+height+"'><div id=\"ajaxPopDiv2\" style=\"z-index:999;display:none;position: absolute;width:"+width+";height:" +height+ ";\">";
	printStr += "<table width=\"100%\" height=\"100%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	printStr += "<tr><td align=\"center\"><table width=\""+ width +"\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"trOver\">";
	printStr += "<tr><td width=\"100%\" id=\"popWinTitle\">&nbsp;<b>"+ titles +"</b>&nbsp;</td><td height=\"20\" align=\"right\" width =\"30\"><input type=\"button\" name=\"Submit\" value=\"close\" onClick=\"ajaxWindowClose('"+ obj +"',ajaxPopDiv2,"+cleanContainer+")\" class=\"bnout\" onMouseOver=\"bnover(this);\" onMouseOut=\"bnout(this);\">&nbsp;</td></tr>";
	if(isTop)
	{
		printStr += "<tr><td colspan=\"2\" height=\""+ height +"\" valign=\"top\" bgcolor=\"#E9F4FE\"><div id=\"ajaxPopContent2\">Loading...</div></td></tr>";
	}
	else
	{
		printStr += "<tr><td colspan=\"2\" height=\""+ height +"\" bgcolor=\"#E9F4FE\"><div id=\"ajaxPopContent2\">Loading...</div></td></tr>";
	}
	printStr += "</table></td></tr></table></div></object>";

	document.writeln(printStr);
}

/**
*	
*  obj       FormObject    
*   subobj	  object 		
*   popStyle  String        
*   divleft   int          
*   divtop    int           
*   isShade   Boolean       default false
*   titles    String		window title
*/

function ajaxWinPop(obj,subobj,popStyle,divleft,divtop,isShade,titles)
{
	if(typeof(titles)!= "undefined" && typeof(popWinTitle) == "object")
	{
		popWinTitle.innerHTML = "<b>" + titles + "</b>";
	}
	
	var pointo = ajaxGetXY(subobj);
	var divLimit = 10 ;
	with (ajaxPopDiv2.style)
	{
		displaySelectArea(obj,"none");
		display = '';
		if(divleft >0 && divtop >0){
			left = pointo.x + subobj.offsetWidth+1 + divleft ;
			top  = divtop;
		}else if(popStyle=="up"){
			left = pointo.x;
			top  = pointo.y - ajaxPopDiv2.offsetHeight;
		}else if(popStyle == "upright"){
			left = pointo.x + subobj.offsetWidth+1;
			top  = pointo.y + subobj.offsetHeight - ajaxPopDiv2.offsetHeight;
		}else if(popStyle == "downright"){
			left = pointo.x + subobj.offsetWidth+1;
			top  = pointo.y;
		}else if(popStyle == "downleft"){
			left = pointo.x + subobj.offsetWidth +1 - ajaxPopDiv2.offsetWidth;
			top  =  pointo.y+subobj.offsetHeight + 1;
		}else if(popStyle == "downcenter"){
			left = pointo.x + subobj.offsetWidth +1 - ajaxPopDiv2.offsetWidth/2;
			top  =  pointo.y+subobj.offsetHeight + 1;
		}else if(popStyle == "centerright"){
			tempLeft = pointo.x + subobj.offsetWidth;
			tempTop  = pointo.y - ajaxPopDiv2.offsetHeight/2;
			tempTopTotal = tempTop + ajaxPopDiv2.offsetHeight ;
			pageArr = getPageScroll();
			yOffsetH = pageArr[1] + document.body.offsetHeight;

			left = tempLeft>0 ? tempLeft : divLimit;
			top  = tempTop>0 ? (tempTopTotal >= yOffsetH ? yOffsetH - ajaxPopDiv2.offsetHeight - divLimit : (tempTop < pageArr[1] ? pageArr[1] + divLimit : tempTop)) : divLimit;
		}else if(popStyle == "centerscr"){//
			pageArr = getPageScroll();
			left = pageArr[0] + (document.body.offsetWidth - ajaxPopDiv2.offsetWidth) /2;
			top  = pageArr[1] + (document.body.offsetHeight - ajaxPopDiv2.offsetHeight)/2;
		}else{ //down
			left = pointo.x;
			top  = pointo.y+subobj.offsetHeight + 1;
		}
	}
	if (isShade){
		ajaxBackgroundDiv.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=50,finishOpacity=50);";
	}
}


//
function getPageScroll(){
  var xScroll = 0;
  var yScroll = 0;
  if (self.pageXOffset) {
    xScroll = self.pageXOffset;
  } else if (document.documentElement && document.documentElement.scrollLeft){   // Explorer 6 Strict
    xScroll = document.documentElement.scrollLeft;
  } else if (document.body) {// all other Explorers
    xScroll = document.body.scrollLeft;
  }
  
  if (self.pageYOffset) {
    yScroll = self.pageYOffset;
  } else if (document.documentElement && document.documentElement.scrollTop){   // Explorer 6 Strict
    yScroll = document.documentElement.scrollTop;
  } else if (document.body) {// all other Explorers
    yScroll = document.body.scrollTop;
  }
  
  arrayPageScroll = new Array(xScroll,yScroll) 
  return arrayPageScroll;
}

/*
*	AJAX
*/
function ajaxWindowPop(obj,divname,noShade){
	if (divname =="" || divname == null ) divname = ajaxPopDiv;
	displaySelectArea(obj,"none");
	divname.style.display='';
	if ( !noShade){
		ajaxBackgroundDiv.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=50,finishOpacity=50);";
	}
}

function ajaxWindowClose(obj,divname,cleanContainer){
	try{
		displaySelectArea(obj,"");
		if (typeof(divname)!= "object" || divname == "" || divname == null )
		{
			divname = ajaxPopDiv;
			ajaxPopContent.innerHTML="Loading...";
		}
		else if(typeof(cleanContainer) == "object")
		{
			ajaxPopContent2.innerHTML="Loading...";
		}
		divname.style.display='none';	
		ajaxBackgroundDiv.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=100,finishOpacity=100);";
	}catch(ex){
	}
	//alert(ajaxBackgroundDiv.name);
}
//display | hidden the select of HTML
function displaySelectArea(obj,display){
	try{
		obj=document.all.item(obj);	
		if(obj != null && obj !="null"){
			for (var i=0;i<obj.elements.length;i++){
				var e = obj.elements[i];
				if(e.type == "select-one" || e.type == "text/x-scriptlet"){
					e.style.display = display;
				}
			}
		}
	}catch(ex){
	}
}

function ajaxGetXY(aTag)
{
	var oTmp = aTag;
	var pt = new getPoint(0,0);
	do{
		pt.x += oTmp.offsetLeft;
		pt.y += oTmp.offsetTop;
		oTmp = oTmp.offsetParent;
	}while(oTmp.tagName!="BODY");
	return pt;
}

function getPoint(iX, iY)
{
	this.x = iX;
	this.y = iY;
}

//================End ajax=================


//====================================
/**
 * @file            control.js
 * @description     call ajaxContentWindows2()
 * @author          hhping <hh_ping@163.com>
 * @date            2007-10-06 hhping
 * @version         1.0.0
**/

var inputListObj =
{

	objectForm  : "",         		//FORM object
	objectText  : Object(),    		//text input box object
	objectValue : Object(),	 		//select value object
	objectList  : "",    			//div name of down list
	objectClew  : null,				//prompt object
	strListTitle: "",				//prompt title
	strListUrl  : "",				//search url of down list
	intListNum  : 10,				//page number of down list
	intListDisSce : 400,            //delay time of down list ,second
	objectTimeOut : null,			//counter objectr
	intPageNo   : 1 ,               //is display page number
	displaySelect : true,           //hidden other select of HTML
	intDisplayWidth: 230,			//width of down list
	
	
	/**
	  * init
	  * @objForm   object   Form obj
	  * @objText   object   text input box object
	  * @objValue  object   hidden value box object
	  * @strUrl    string   url
	  * @seturlParam  string   action 
	  * @objClew   object   clew object
	  * @strTitle  string   pop window title
	  * @disSelect boolean   display or hide other select of HTML
	  * @strContainer string get container
	  * @public
	  */
	init : function (objForm,objText,objValue,strUrl,urlParam,objClew,strTitle,disSelect,strContainer)
	{
		try
		{
			if(typeof(objForm) != "" && typeof(objText) == "object")
			{
				objectForm	= objForm;
				objectText  = objText;
				objectValue = objValue;
				objectClew  = objClew
				strListTitle= strTitle;
				strListUrl  = strUrl;
				seturlParam = urlParam;
				displaySelect = disSelect;
				
				if(objectText.style.width != "" )
				{
					this.intDisplayWidth = 	objectText.style.width;
				}
				
				//AJAX
				if (!typeof(strContainer) == "undefined")
				{
					ajaxContentWindows3(objectForm,this.intDisplayWidth,25,false,strContainer);
				}
				else if(typeof(ajaxPopDiv2)=="undefined")
				{
					ajaxContentWindows3(objectForm,this.intDisplayWidth,25,false,'ajaxPopDiv2');
				}
				
				//objectText.onblur = "onblur";
				
			}
			else
			{
				this.displayError("error of init.");
			}
		}
		catch(ex){
			this.displayError(ex);
		}
	},
	
	/**
	  * @pageno 
	  * @public
	  */
	input : function (pageno)
	{
		try{
			//alert (pageno);
			if( typeof(pageno) == "number")
			{
				intPageNo = pageno;
			}
			if(typeof(objectValue) == "object")
			{
				objectValue.value = "";
			}
			this.checkValue();
			if ( objectText.value.replace(/[\s]+/g,"") != "")
			{
				if(this.objectTimeOut != null)
					clearTimeout(this.objectTimeOut);
				
				this.objectTimeOut = window.setTimeout (this.getDataUrl,this.intListDisSce);
			}
			else
			{
				ajaxWindowClose(objectForm,ajaxPopDiv2,ajaxPopContent2);
			}
		}
		catch(ex)
		{
			this.displayError(ex + ":error "+ objectText.value,true);
		};
	},
	
	/**
	  * event
	  *
	  * @private
	  */
	getDataUrl : function()
	{
		try{
			clearTimeout(this.objectTimeOut);
			if ( objectText.value.replace(/[\s]+/g,"")!="")
			{
				ajaxLoadPage(strListUrl,"act="+seturlParam+"&sect=" + encodeURIComponent(objectText.value) + "&page=" + this.intPageNo ,"GET",ajaxPopContent2);
				ajaxWinPop(objectForm,objectText,'downleft',0,0,false,strListTitle,this.displaySelect);
			}
			//alert(this.displaySelect);
		}
		catch(ex)
		{
			this.displayError(ex + ":error of object.");
		}
	},
	
	/**
	 * select item
	 * @public
	 */
	selectItem : function(name,value)
	{
		try{
			objectText.value = name;
			if(typeof(objectValue) == "object")
			{
				objectValue.value = value;
				this.checkValue();
			}
			this.listClose();
		}
		catch(ex){}
	},
	
	onblur : function()
	{
		if(typeof(objectValue) == "object")
		{
			objectValue.value = "";
			var getvalues=ajaxLoadPage(strListUrl,"act="+seturlParam+"check&sect=" + encodeURIComponent(objectText.value) ,"GET", getvalues ,false,true);
			if (typeof(getvalues) != "undefined" &&  getvalues>0 )
			{
				if(typeof(objectValue) == "object")
				{
					objectValue.value = getvalues ;
				}
			}
			this.checkValue();
		}
	},
	
	
	/**
	 * close down list
	 * @public
	 */
	listClose : function()
	{
		ajaxWindowClose(objectForm,ajaxPopDiv2,ajaxPopContent2);
	},
	
	/**
	 * check 
	 * @public
	 */
	checkValue : function()
	{
		if(typeof(objectValue) == "object")
		{
			if (objectValue.value != "")
			{
				this.setClew(true);
			}
			else
			{
				this.setClew(false);
			}
		}
	},
	
	/**
	 * clew
	 * @private
	 */
	setClew  : function (bolVar)
	{
		if (bolVar)
		{
			objectClew.innerHTML = "<font color=green><img src='images/yes.gif' align='absmiddle'/></font>";
		}
		else
		{
			objectClew.innerHTML = "<font color=green><img src='images/no.gif' align='absmiddle'/></font>";
		}
	},
	
	//clew error information
	displayError : function(error,isNotDis)
	{
		if(!isNotDis)
		{
			alert(error);
		}
	}
	
}



function filtrate_css(str) {
	try{
		if(document.all){
			if(str.length > 15){
				var begin 	= str.indexOf('<style>') + 7 ;
				var end 	= str.indexOf('</style>') - 9;
				
				if(end>0){
					var temp = str.substr(begin,end);
					if (temp.length>0){
						
						window.style= temp;
						document.createStyleSheet("javascript:style");
						
					}
				}
			}
		}
		
	}catch(ex){
		alert(ex);
	}
	
    return false;
}

var js;
function include_js(file) {
    var html_doc = document.getElementsByTagName( 'HEAD')[0];
    js = document.createElement( 'script ');
    js.setAttribute( 'type ',  'text/javascript ');
    js.setAttribute( 'src ', file);
    html_doc.appendChild(js);
    js.onreadystatechange = function () {
        if (js.readyState ==  'complete') {
            alert( 'JS onreadystate fired ');
        }
    }
    js.onload = function () {
        alert( 'JS onload fired ');
    }
    return false;
}


function selectAll(selNmae,obj){
	var subobj = document.getElementsByName(selNmae);
	for (var i = 0; i < subobj.length; i++){
		subobj[i].checked = obj.checked;
	}
}

function checkSelect(selNmae){
	var subobj = document.getElementsByName(selNmae);
	var selected =0;
	for (var i = 0; i < subobj.length; i++){
		if(subobj[i].checked){
			selected ++;
		}
	}
	return selected;
}

function deleteList(selNmae, clew1, clew2){
	var booleanResult	=	false;
	if(clew2 != ''){
		if(checkSelect(selNmae) == 0 ){
			alert(clew1);
		}else if(confirm(clew2)){
			booleanResult	=	true;
		}
	}else{
		if(confirm(clew1)){
			booleanResult	=	true;
		}
	}
	return	booleanResult;
}

//var test = inputListObj;
//test.init();

//=================End =====================


// images function
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->

<!-- menu
function check(){
	document.form3.business_name.value="";
}

function check1(){
	document.form1.uname.value="";
}

function check2(){
	document.form1.password.value="";
}

function check3(){
	document.form4.product_name.value="";
}

function setFocus(){
	document.getElementById('password').focus();
}


//check text of input 
function defaultClew(objName, hasFocus, clew){
	if (hasFocus){
		if ($(objName).val() == clew ){
			$(objName).val('');
		}
	}else{
		if ($(objName).val() == '' ){
			$(objName).val(clew);
		}
	}
}

//window onload
function windowOnload(fn){
	if (document.all){
		window.attachEvent('onload', fn);
	}else{
		window.addEventListener('load',fn,false);
	}

}


function jquery_check_search(url, cp, formID){
	if ( typeof(url) == 'string' &&  url != ''){
		$.post(url, { 'cp': cp, 'category': $('#se_category').val(),'keyword': $('#keyword_k').val(), 'formID': formID }, function(data,status){
			if ( status == 'success' ){
				try{
					eval(data);
				}catch(ex){alert(ex);}
			}
		}
		);
	}
	return false;
}


function clewMethod(data, Obj, params){
	if(typeof(params) == 'object'){
		var ObjClewID = "#"+ Obj + "_clew";
		$(ObjClewID).css('display','');
		for (i=0; i< params.length; i++){
			if(data == params[i][0]){
				$(ObjClewID).removeClass();
				$(ObjClewID).html(params[i][1]).addClass(params[i][2]);
				$(ObjClewID).fadeTo(200,0.1,function(){$(this).fadeTo(900,1);});
				data == 'existed' ? $('#' + Obj).val('') : '';
			}
		
		}
	}
}

/*
function jquery_return_js(url, cp, sector){
	var boolResult = false;

	if ( typeof(url) == 'string' &&  url != ''){
		$.post(url, { 'cp': cp, 'sector': $('#sector').val() }, function(data,status){
		  //$.post(url, eval(jquery_getFormValue(formID, ", 'cp':'" + cp + "'")), function(data,status){
			if ( status == 'success' ){
				try{
					alert(data);
				eval(data);
				}catch(ex){alert(ex);}
			}
		}
		);
		
	}
	return boolResult;
}

function jquery_getFormValue(formID, params){
	var strResult = '';
	var tempObj = $("#" + formID).get(0);
	
	for (var i=0; i< tempObj.elements.length; i++ ){
		if(typeof(tempObj.elements[i]) == 'object'){
			if(tempObj.elements[i].name != 'cp' && typeof(tempObj.elements[i].name) != 'undefined' && typeof(tempObj.elements[i].id) != 'undefined' && tempObj.elements[i].id != ''){
				//strResult += "'"+tempObj.elements[i].name+"': \""+$('#'+tempObj.elements[i].id).val()+"\", ";
				strResult += ", '"+tempObj.elements[i].name+"': $('#"+tempObj.elements[i].id +"').val()";
			}
		}
	}
	if(strResult != ""){
		strResult = strResult.substr(1,strResult.lenght);
	}
	
	if (typeof(params) != 'undefined'){
		strResult += params;
	}
	return "{" + strResult + "}";
}
*/

//display loading when combox changed.
function loadingAddOfCombox(objHTML){
	if(typeof(objHTML) == 'string' && objHTML != ''){
		var arrObjHTML	=	objHTML.split('_');
		var subObjHTML	=	'#' + arrObjHTML[0] + "_" + (parseInt(arrObjHTML[1])+1) ;
		if (typeof( $(subObjHTML) ) == 'object') {
			$(subObjHTML).empty(); 
			$("<option value='-100'>Loading...</option>").appendTo(subObjHTML);
		}
	}

}

//Want any text of loading for comboxs .
function loadingOfCombox(objHTMLs){
	if(objHTMLs != ''){
		var arrObjHTMLs	=	objHTMLs.split(',');
		if (arrObjHTMLs.length > 0){
			for (i =0; i< arrObjHTMLs.length ; i++){
				var tempStr = arrObjHTMLs[i].toString();
				$(tempStr).change(function(){loadingAddOfCombox(this.id);});
			}
		}
	}
}

//linkage function by jquery
function jquery_linkage(objHTML, url, cp, level, addOptions, hasLoading){
	var obj	=	$('#'+objHTML);
	if ( typeof(url) == 'string' &&  url != ''){
		hasLoading ? loadingAddOfCombox(objHTML) : '';
		$.post(url, { 'cp': cp, 'values': obj.val(), 'objHTML': objHTML, 'level': level , 'addOptions': addOptions}, function(data,status){
			if ( status == 'success' ){
				//alert(data);
				try{
				eval(data);
				}catch(ex){;}
			}
		}
		); 
	}
}

// FCKEditor content change function
function autoChangeEdit(objForm,arrItems)
{
	try{
		if (arguments.length > 1) {
			  for (var i = 1; i < arguments.length; i++){
				objForm[arguments[i]].value = __get_ed_text(arguments[i]);
			  }
		}
	}catch(ex){ alert(ex);}

}
function autoLoadEdit(arrItems){
	try{
		if (arguments.length > 0) {
			  for (var i = 0; i < arguments.length; i++){
				__set_ed_text(arguments[i],xajax.$(arguments[i]));
			  }
		}
	}catch(ex){ alert(ex);}
}

function __get_ed_text(editor_name)
{
    var oEditor = FCKeditorAPI.GetInstance(editor_name) ;
    if (oEditor.GetXHTML(true)) {
        return oEditor.GetXHTML(true);
    }
    else return '';
}

function __set_ed_text(editor_name,valueObject)
{
    var oEditor = FCKeditorAPI.GetInstance(editor_name);
	FCKeditorAPI.GetInstance(editor_name).SetHTML(valueObject.value);
}

function tipRedirect(){
	alert("You need to be a member of \"FoodMarketplace\" to use this service. Register now, it's FREE.");
	location.href="{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}signup.php";
}
  
function popupNewsletter()
{
	window.open('{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}guestSubscribe.php?sid={/literal}{$headerInfo.StoreID}&email={$smarty.session.email}{literal}','mailtofriend','height=400,width=550,scrollbars=yes,status=yes') ;
}
  
function bookmark(){
	var url=window.location.href;
	var title="SOC exchange - {/literal}{$headerInfo.bu_name|regex_replace:'/\"/':'\\\"'}{literal}";
	if ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4)) 
	{
		window.external.AddFavorite(url,title);
	}else if(window.sidebar && typeof(window.sidebar.addPanel)=="function"){
		window.sidebar.addPanel(title, url, "");
	}else{
		var msg = "Don\'t forget to bookmark us!";
		if(navigator.appName == "Netscape") msg += "  (CTRL+D)";
		alert(msg);
	}
}

function popupw() {
	window.open('{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}subscribe.php?sid={/literal}{$headerInfo.StoreID}&email={$smarty.session.email}{literal}','mailtofriend','height=150,width=550,scrollbars=yes,status=yes') ;
} 

function popcontactwin(place, pid) {
	place = typeof(place) == 'undefined' ? '' : place;
	pid = typeof(pid) == 'undefined' ? '' : pid;
	window.open("{/literal}{$smarty.const.SOC_HTTP_HOST}{literal}emailstore.php?url=productDispay.php&place="+place+"&pid="+pid+"&StoreID={/literal}{$headerInfo.StoreID}{literal}", "emailstore","width=600,height=360,scrollbars=yes,status=yes");
}