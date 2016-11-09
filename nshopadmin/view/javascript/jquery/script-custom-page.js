$(document).ready(function() {


    /* Remove Clearfix from Shoppica - Start */

    $("#tb_cp_header_wrap").removeClass("clearfix");

    $("#tb_cp_header").next().hide();

    /* Remove Clearfix from Shoppica - End */

    function menuOpen(width, rightColumnWidth) {

	$("#left-column").animate({"width": width + "px"}, 300);

	$("#content").css("width", rightColumnWidth + "px").animate({"margin-left": width + "px"}, 300);

	if ($(window).width() <= 480) {

	    $(".right-header-content").css("width", rightColumnWidth + "px");
	    $("ul.mainnav li").css("display", "block");
	    $("ul.mainnav li#menu-control").css("padding-bottom", "45px");
	} else {
	    $("#content").css("width", rightColumnWidth + "px").animate({"margin-left": width + "px"}, 300);
	}

	$(".right-header-content").animate({"margin-left": width + "px"}, 300);

	$("ul.mainnav > li > a").css("font-size", "14px");
	$("ul.mainnav li a > span").css("display", "inline");

	$(".sidebar.copyright").css("display", "block");

	$("ul.mainnav li#menu-control .menu-control-outer").addClass("opened");
    }

    function menuClose(width) {

	$("ul.mainnav li a.parent,ul.mainnav li a.top").parent().siblings().find("ul").slideUp();

	$("#left-column").animate({"width": width + "px"}, 300, function() {
	    $(this).removeAttr('style');
	});

	if ($(window).width() <= 480) {

	    $(".right-header-content").removeAttr("style");
	    $("#content").animate({"margin-left": "0px"}, 300, function() {
		$(this).removeAttr('style');
	    });
	    $("ul.mainnav li#menu-control").removeAttr("style");

	    $("ul.mainnav li#menu-control").removeAttr("style");
	} else {
	    $("#content").animate({"margin-left": width + "px"}, 300, function() {
		$(this).removeAttr('style');
	    });
	}

	$("ul.mainnav li").removeAttr("style");

	$(".right-header-content").animate({"margin-left": width + "px"}, 300, function() {
	    $(this).removeAttr('style');
	});


	$("ul.mainnav > li > a").removeAttr("style");
	$("ul.mainnav li a > span").removeAttr("style");

	$(".sidebar.copyright").removeAttr("style");

	$("ul.mainnav li#menu-control .menu-control-outer").removeClass("opened");

    }

    $("ul.mainnav li a.parent,ul.mainnav li a.top").click(function() {

	// Get current with of right column
	var rightColumnWidth = $("#content").width();

	menuOpen("223", rightColumnWidth);
	$(this).parent().siblings().find("ul").slideUp();
    });

    $("li#menu-control").click(function() {

	var leftColumnWidth = $("#left-column").width();
	var browserWidth = $(window).width();

	if (leftColumnWidth == 73) {
	    // Get current with of right column
	    var rightColumnWidth = $("#content").width();

	    menuOpen("223", rightColumnWidth);
	} else {
	    menuClose("73");
	}

    });



    /* Main Nav - Highlight Active Menu Item */

    function getURLVar(urlVarName) {
	var urlHalves = String(document.location).toLowerCase().split('?');
	var urlVarValue = '';

	if (urlHalves[1]) {
	    var urlVars = urlHalves[1].split('&');

	    for (var i = 0; i <= (urlVars.length); i++) {
		if (urlVars[i]) {
		    var urlVarPair = urlVars[i].split('=');

		    if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
			urlVarValue = urlVarPair[1];
		    }
		}
	    }
	}

	return urlVarValue;
    }

    if (!route) {
	$('#dashboard').addClass('selected');
    } else {
	part = route.split('/');

	url = part[0];

	$('#'+url).addClass('selected');
    }


    /*  Main Nav - Change Arrow Image */

    $("ul.mainnav li:not(.selected) a").hover(function() {
	$(this).find("span img").attr("src", "view/image/dropdown-arrow-white.png");
    }, function() {
	$(this).find("span img").attr("src", "view/image/dropdown-arrow.png");
    });


    /* Top Menu Effects */

    $(".secondary-menu > ul > li").hover(function(e) {
	$(this).find("ul").slideDown('fast');
	e.stopPropagation();
    }, function(e) {
	$(this).find("ul").slideUp('fast');
	e.stopPropagation();
    });

});