var $ =cp=CanJs=function(selector)
{
    if ( window == this ) return new CanJs(selector);
	this.id=document.getElementById(selector.replace('#',''));
}
CanJs.prototype.html = function(str)
{
	if(typeof str == 'undefined'){ return this.id.innerHTML; }
	else{ this.id.innerHTML=str;}
}
CanJs.prototype.val = function(str)
{
	if(typeof str == 'undefined'){ return this.id.value;}
	else{this.id.value=str;}
}
CanJs.prototype.className = function(str)
{
	if(typeof str == 'undefined'){return this.id.className;}
	else{this.id.className=str;}
}
CanJs.prototype.show = function()
{
	this.id.style.display="";
}
CanJs.prototype.hide = function()
{
	this.id.style.display="none";
}
CanJs.cookie=function(name, value, options)
{
    if (typeof value != 'undefined') 
	{ // name and value given, set cookie
        options = options || {};
        if (value === null) 
		{
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) 
		{
            var date;
            if (typeof options.expires == 'number') 
			{
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            }
		    else 
			{
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
		
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } 	
	else 
	{ // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') 
		{
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) 
			{
                var cookie = cookies[i].toString().replace( /^\s+/, "" ).replace(/\s+$/, "" );
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) 
				{				
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
}

CanJs.ajax=function(opt)
{
	var xmlHttp = null;
	var aborted=false;
	var timeoutHandle; 
	var options={
		 type:'GET', 
		 url:'test.php',
		 data:'', 
		 async:true,
		 cache:false,
		 timeout:30000,
		 dataType:'text',
		 success:function(msg){}, 
		 error:function(msg){alert(msg);
		 }
	}

	if(opt!=null)
	{	for(var pro in opt)
    	{  
			options[pro] = opt[pro];
		 }
	} 

	this.createXMLHttpRequest=function () 
	{
		if(xmlHttp != null) return;
		if (window.ActiveXObject) {
			try {
				xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e) {
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		else if (window.XMLHttpRequest) {
			xmlHttp = new XMLHttpRequest();
		}
	}

	this.sendData=function()
	{			   
		xmlHttp.onreadystatechange = function() 
		{
			if (!aborted&&xmlHttp.readyState == 4) 
			{
				if (!aborted&&xmlHttp.status == 200) {
					clearTimeout(timeoutHandle);
					switch(options.dataType.toLowerCase())
					{
						case 'xml':data= xmlHttp.responseXML; break;
						case 'json':data=eval('(' + xmlHttp.responseText + ')'); break;
						default:data= xmlHttp.responseText; 						
					}
					options.success(data);
				}
				else {
					options.error(xmlHttp.status.toString());
				}
			}
		}
				
		if (options.type.toLowerCase() == "get")
		{
			if (options.data != "") {		
				if(options.url.indexOf("?")>=0)
				 {    
						 options.url =options.url+ "&" + options.data; 
				 }
				 else
				 {    
					 options.url =options.url+ "?" + options.data; 
				 }  
			}
			xmlHttp.open("GET", options.url, options.async);
			if (!options.cache) {xmlHttp.setRequestHeader("Cache-Control","no-cache");}
			xmlHttp.send(null);
		}
		else 
		{
			xmlHttp.open("POST", options.url, options.async);
			if (!options.cache) {xmlHttp.setRequestHeader("Cache-Control","no-cache");}   
			xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xmlHttp.send(options.data);
		}
	}
	this.checkTimeout=function()
	{
		if(xmlHttp.readyState!=null&&xmlHttp.readyState!=4)
		{ 
			aborted=true; 
			xmlHttp.abort();
			options.error('timeout');
		}
	}
	try {
		this.createXMLHttpRequest();
		timeoutHandle=setTimeout(this.checkTimeout,options.timeout); 
		this.sendData();
	}catch (e) {options.error(e.toString());}
}
CanJs.select=function(name,action)
{
	var el=document.getElementsByName(name); 
	for (var i=0;i<el.length;i++) 
	{ 
		if(el[i].type=="checkbox"&&el[i].name==name) 
		{ 
			switch(action)
			{
				case 1:el[i].checked=true; break;
				case -1:if(el[i].checked==true){ el[i].checked=false;}
						else{ el[i].checked=true;} break;
				default:el[i].checked=false; 
			}			
		} 
	} 
}