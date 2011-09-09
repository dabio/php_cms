var now = new Date();
var path = '/pub/inc/stats.php?';
var req;

// flash fehlt noch, query string und plugins
path += 'time=' + now.getTime();
path += '&browser=' + navigator.userAgent.toLowerCase();
path += '&document=' + escape(document.URL);
path += '&document_title=' + escape(document.title);
path += '&resolution=' + screen.width + 'x' + screen.height;
path += '&java=' + navigator.javaEnabled();
path += '&cookie=' + navigator.cookieEnabled;
path += '&language=' + (navigator.userLanguage ? navigator.userLanguage.toLowerCase() : navigator.language.toLowerCase());
path += '&ip=<?php print($_SERVER["REMOTE_ADDR"]) ?>';

// geht in IE nur, wenn man ueber http:// kommt
path += '&referrer=' + escape(document.referrer);

/*@cc_on @*/
/*@if (@_jscript_version >= 5)
try {
	req = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
	try {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
		req = false;
	}
}
@end @*/

if (!req && typeof XMLHttpRequest != 'undefined') {
	req = new XMLHttpRequest();
}

if (req) { req.open("GET", path, true); req.send(null); }
