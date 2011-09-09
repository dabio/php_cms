addContent = function (parent)
{
	var url = '/4600da0df39030a225fbc4098d48f004/content/add/' + parent + '/';

	var options = {
		onComplete: function(r) {Element.toggle('f_preview', 'spinner');},
		onLoading: function(r) {Element.toggle('spinner');}
	};

    var myAjax = new Ajax.Updater('item', url, options);
}

changeMarkup = function ()
{
    if ($('f_preview').style.display == 'none') return false;
    $('f_preview').innerHTML = ''

    var url = '/4600da0df39030a225fbc4098d48f004/content/preview/';
    var myForm = Form.serialize('page_content');

	var options = {
	    parameters: myForm,
		onComplete: function(r) {Element.toggle('spinner');},
		onLoading: function(r) {Element.toggle('spinner');}
	};

    var myAjax = new Ajax.Updater('f_preview', url, options);

}

editContent = function (id)
{
	var url = '/4600da0df39030a225fbc4098d48f004/content/edit/' + id + '/';

	var options = {
		onComplete: function(r) {Element.toggle('f_preview', 'spinner');},
		onLoading: function(r) {Element.toggle('spinner');}
	};

    var myAjax = new Ajax.Updater('item', url, options);
}

hidePreview = function ()
{
    if ($('f_preview').style.display == 'none') return false;

    Element.toggle('f_preview', 'f_content');

    $('f_preview').innerHTML = '';
}

showPreview = function (id)
{
    if ($('f_preview').style.display != 'none') return false;

    var url = '/4600da0df39030a225fbc4098d48f004/content/preview/';
    var myForm = Form.serialize('page_content');

	var options = {
	    parameters: myForm,
		onComplete: function(r) {Element.toggle('spinner');},
		onLoading: function(r) {
		    Element.toggle('spinner', 'f_preview', 'f_content');
		}
	};

    var myAjax = new Ajax.Updater('f_preview', url, options);
}

urlUpdate = function ()
{
	$('legend').innerHTML = $('f_title').value;
}

init = function ()
{
    Element.hide('spinner');
}


Event.observe(window, 'load', init, false);