jQuery(function() {

	var $ = jQuery;

	function jokeswitch(data) {
	    var el = $(data["sel"]);
	    if (data["text"]) {
		var text = data["text"];
		if (typeof(text) == "object") {
		    text = text[Math.floor(Math.random() * text.length)];
		}
		el.attr("title", el.text());
		el.html(text);
	    }
	    if (data["content"]) {
		for (i in data["content"]) {
		    el.attr(i, data["content"][i]);
	    	}
	    }
	    if (data["after"]) {
		el.after(data["after"])
	    }
	}
	
	$.getJSON("/wp-admin/admin-ajax.php?action=jokedata", function(data, status, xhr) {
		if (data) {
		    $("<section>").addClass("widget").append($("<button>").click(function(e){
			sessionStorage.joke = (sessionStorage.joke=="no"?"yes":"no");
			location.reload();
		    })
	    	    .text(sessionStorage.joke=="no"?"Show Joke Issue":"Disable Joke Issue")
	    	    .css({"fontSize":"1.5em","padding":"8px"}))
	    	    .insertAfter("#sidebar-secondary section:nth-child(3)");

		    if (sessionStorage.joke != "no") {
		        for (i in data) {
			    jokeswitch(data[i]);
		        }
		    }
		}
	    });

});