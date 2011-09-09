var addEvent;
if (document.addEventListener) {
	addEvent = function(element, type, handler) {
		element.addEventListener(type, handler, null);
	};
} else if (document.attachEvent) {
	addEvent = function(element, type, handler) {
		element.attachEvent('on' + type, handler);
	};
} else {
	addEvent = new Function; // not supported
}


createMailtoLink = function (e)
{
    var el;
    if (window.event && window.event.srcElement)
    {
        el = window.event.srcElement;
    }
    if (e && e.target)
    {
        el = e.target;
    }
    if (!el)
    {
        return;
    }
    location.href = 'mailto:'+el.innerHTML;
    return false;
}


secureMail = function () {
    var all_a = document.getElementsByTagName('a');
    if (all_a.length >= 0)
    for (var i = 0; i < all_a.length; i++)
    {
        if (all_a[i].innerHTML == 'dbraband|#|gmail|&amp;|com')
        {
            all_a[i].innerHTML = all_a[i].innerHTML.replace('|#|','@');
            all_a[i].innerHTML = all_a[i].innerHTML.replace('|&amp;|','.');

            addEvent(all_a[i],'click',createMailtoLink,false);
        }
    }
}


addEvent(window, 'load', secureMail);