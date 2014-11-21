(function () {
	function getCookie(name) {			
		var cookieValue = null;
		if (document.cookie && document.cookie != '') {
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++) {
				var cookie = cookies[i].toString().replace( /^\s+/, "" ).replace(/\s+$/, "" );
				if (cookie.substring(0, name.length + 1) == (name + '=')) {				
					cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
					break;
				}
			}
		}
		return cookieValue;
	}
	if(window.Navbar) {
		window.Navbar.getUsername = function(){
			return getCookie('username');
		};
		return
	}
	window.UDBSDKProxy || document.write('<script src="http://res.udb.duowan.com/js/oauth/udbsdk/proxy/udbsdkproxy.min.js"><\/script>');
	window.Navbar = {
			login: function (callbackURL) {
				try{
					UDBSDKProxy.openByFixProxy(callbackURL||"")
				}catch(e) {}
			},
			logout: function (callbackURL) {
				try{
					UDBSDKProxy.deleteCookieByFixProxyWithCallBack(callbackURL||"")
				}catch(e) {}
			},
			getUsername: function () {
				return getCookie('username');
			}
	}
})();