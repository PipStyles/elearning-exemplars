//requires jquery

var js_includes = ["js/json2.js","js/jquery.zend.jsonrpc.js"];

var hum_exemplar_rpc;

function getScripts(names, callback) {
    var received = 0;
    var realCallback = function() { 
        received++;
        if (received === names.length)
            $(callback);
    };

    for (var i = 0; i < names.length; i++)
        $.getScript(names[i], realCallback);
}

$(document).ready(function() 
{
	getScripts(js_includes, function()
	{
		hum_exemplar_rpc = jQuery.Zend.jsonrpc({url: "v1/index.php"});
		//define hum_exemplar_page() in the calling page to use the hum_exemplar_rpc object
		hum_exemplar_page();
	});
	
});
	