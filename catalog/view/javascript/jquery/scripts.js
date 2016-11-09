jQuery("ul.login_menu li a.search-icon").click(function(){
	var search_panel = jQuery(".search-panel");
	if( search_panel.hasClass('open') ) {
		jQuery(".search-panel").slideUp().removeClass('open');
	} else {
		jQuery(".search-panel").slideDown().addClass('open');
	}
});

jQuery(window).load(function(){

	jQuery('#slideshow').show();

	jQuery('.product-overlay').css('opacity', '0.9');

	jQuery('.product-main-image').show();
	

});
function ajaxLogin(){
	var form = jQuery('#login-form');
	jQuery('.validation-advice').remove();
    var VError=false;
    var email = jQuery('input[name="email"]',form);
    var password = jQuery('input[name="password"]',form);

    if(email.val() ==''){
        email.after('<div class="validation-advice">This is a required field</div>');
        VError=true;
    }
    else{

        var emailReg = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if(!emailReg.test( email.val() )){
            email.after('<div class="validation-advice">Please enter a valid email address</div>');
            VError=true;
        }
    }

    if(password.val()==''){
        password.after('<div class="validation-advice">This is a required field</div>');
        VError=true;
    }
    if(!VError) {
        jQuery.ajax({
            url: modal_login_url,
            type: 'post',
            data: form.serializeArray(),
            dataType: 'json',
            beforeSend: function(){
            	jQuery('.btn-login',form).addClass('hide');
            	jQuery('.btn-login',form).after('<div class="loader_login pull-right"></div>');
            },
            complete: function(){
            	
            },
            success: function (res) {
                if (typeof res.options != 'undefined') {
                	if(/logout/.test(location.href)){
                    	location.href= res.url;
                	} else {
                    	location.reload();                		
                	}
                }
                else if (typeof res.error != 'undefined') {
                	jQuery('.btn-login',form).removeClass('hide');
            		jQuery('.loader_login',form).remove();
                    jQuery('.alert-warning').html( res.error).removeClass('hide');
                }
            }
        });
    }
}

jQuery(document).ready(function($) {

	jQuery('.product-overlay').css('opacity', '0');

	setGridItemsEqualHeight(jQuery);
	jQuery('.skin-minimal input').icheck({
		checkboxClass: 'icheckbox_square-grey',
		radioClass: 'iradio_square-grey',
		increaseArea: '80%'
	});

	var maxHeight = -1;
	jQuery('.menu-visiblity').show();
	jQuery('.menu-visiblity').css('visibility','hidden');
	jQuery('.women-visiblity').show();
	jQuery('.women-visiblity').css('visibility','hidden');
	jQuery('.border-menu').each(function() {
		maxHeight = maxHeight > jQuery(this).height() ? maxHeight : jQuery(this).height();
	});
	jQuery('.border-menu').each(function() {
		jQuery(this).height(maxHeight);
	});
	jQuery('.menu-visiblity').css('visibility','visible');
	jQuery('.menu-visiblity').hide();
	jQuery('.women-visiblity').css('visibility','visible');
	jQuery('.women-visiblity').hide();
	jQuery('.product-like').attr('title','Love')
	if(aLikeProducts.length > 0){
		jQuery('.product-like').each(function(){
			var id = $(this).val();
			var src = $('img',this).attr('src');
			var that = this;
			$.each(aLikeProducts,function(i, val){
				if(id == val){
					$('img',that).attr('src',src.replace('heart-icon.png','heart-icon-red.png'));
				}
			});
		})
	}

	jQuery(document).on('click','.btn-login',function(e) {
		e.preventDefault();
		ajaxLogin();
	    return false;
	});
	jQuery(document).on('keypress','#customerLogin #login-form',function(e) {
		var code = e.which || e.keycode;
		if(code == 13){
			e.preventDefault();
			ajaxLogin();
	    	return false;
		}
	});

	if(jQuery('.dashboard .messages').length > 0 && jQuery('.dashboard .messages > li').length > 0){
		jQuery('.dashboard .messages').delay(5000).fadeOut(2000, function(){
			$('li',this).remove();
		});
	}

	jQuery('.border-filter').each(function() {
		maxHeight = maxHeight > jQuery(this).height() ? maxHeight : jQuery(this).height();
	});
	jQuery('.border-filter').each(function() {
		jQuery(this).height(maxHeight);
	});

	var special_sell = jQuery('.special-sell');
	var sold = parseInt(jQuery('.sold',special_sell).html());
	var total = parseInt(jQuery('.total',special_sell).html());
	jQuery('.bar',special_sell).css('width',((sold / total) * 100) + '%');
	var special_price = jQuery('.price',special_sell);
	if(special_price.length > 0) {
		var text = special_price.text();
		var code = text.substring(0,3);
		var price = text.substring(3);
		special_price.html('<span class="special-code">' + code + '</span><span class="special-price">' + price + '</span>');
	}

	if(cart_updated == 1) {
		jQuery('.cart-link').trigger('click');
		setTimeout(function() {
			jQuery('.alert-success').remove();
		},2000);
	}

	// Men Menu add class to menu
	jQuery(".menu-shop ul li > a.men, div.menu-visiblity").hover(function () {
		clearTimeout(jQuery("div.menu-visiblity").attr('data-timeoutId'));
		jQuery("div.menu-visiblity").fadeIn(300);
	},function () {
		timeoutId = setTimeout(function(){
            jQuery("div.menu-visiblity").fadeOut(300);
        }, 300);
		jQuery("div.menu-visiblity").attr('data-timeoutId', timeoutId);
	});

	jQuery("div.menu-visiblity").hover(function () {
		jQuery(".menu ul li > a.men").addClass('hover_menu');
	},function () {
		jQuery(".menu ul li > a.men").removeClass('hover_menu');
	});

	// Women Menu add class to menu
	jQuery(".menu-shop ul li > a.women, div.women-visiblity").hover(function () {
		clearTimeout(jQuery("div.women-visiblity").attr('data-timeoutId'));
		jQuery("div.women-visiblity").fadeIn(300);
	},function () {
		timeoutId = setTimeout(function(){
            jQuery("div.women-visiblity").fadeOut(300);
        }, 300);
		jQuery("div.women-visiblity").attr('data-timeoutId', timeoutId);
	});

	jQuery("div.women-visiblity").hover(function () {
		jQuery(".menu ul li > a.women").addClass('hover_menu');
	},function () {
		jQuery(".menu ul li > a.women").removeClass('hover_menu');
	});

	//Subscribe panel
	jQuery(document).on('click','.acount-nav ul.login_menu > #open_subs',function(e) {
		jQuery('div.quickCourse').fadeOut();
		if ( jQuery("div.subscribe").is(':visible') ) {
			jQuery("div.subscribe").fadeOut();
			jQuery('#overlay-login').fadeOut('fast');
		} else {
			jQuery("div.subscribe").fadeIn();
			jQuery('#overlay-login').fadeIn('fast');
		}
		e.stopPropagation();
	});

	jQuery(document).on('click', '.subscribe', function(e){
		e.stopPropagation();
	});

	jQuery(document).on('click', '.quickCourse', function(e){
		e.stopPropagation();
	});

	jQuery(document).on('click','div.app',function () {
		jQuery("div.subscribe").fadeOut();
		jQuery("div.quickCourse").fadeOut();
		jQuery('#overlay-login').fadeOut('fast');
	});

	jQuery(document).on('click','div#overlay-login',function () {
		jQuery("div.subscribe").fadeOut();
		jQuery("div.quickCourse").fadeOut();
		jQuery('#overlay-login').fadeOut('fast');
	});

	jQuery(document).on('click',".acount-nav ul.login_menu > #open_login",function(e) {
		jQuery('div.subscribe').fadeOut();
		if(jQuery('div.quickCourse').is(':visible')) {
			jQuery("div.quickCourse").fadeOut();
			jQuery('#overlay-login').fadeOut('fast');
		}
		else {
			jQuery("div.quickCourse").fadeIn();
			jQuery('#overlay-login').fadeIn('fast');
		}
		e.stopPropagation();
	});

	var gridItemsEqualHeightApplied = false;
	function setGridItemsEqualHeight($) {
		var $list = $('.category-products-grid');
		var $listItems = $list.children();
		var centered = $list.hasClass('centered');
		var gridItemMaxHeight = 0;
		$listItems.each(function() {
			$(this).css("height", "auto");
			var $object = $(this).find('.actions');
			if (centered) {
				var objectWidth = $object.width();
				var availableWidth = $(this).width();
				var space = availableWidth - objectWidth;
				var leftOffset = space / 2;
				$object.css("padding-left", leftOffset + "px");
			}

			var bottomOffset = parseInt($(this).css("padding-top"));
			if (centered)
				bottomOffset += 10;
				$object.css("bottom", bottomOffset + "px");

			if ($object.is(":visible")) {
				var objectHeight = $object.height();
				$(this).css("padding-bottom", (objectHeight + bottomOffset) + "px");
			}
			gridItemMaxHeight = Math.max(gridItemMaxHeight, $(this).height());
		});
		//Apply max height
		$listItems.css("height", gridItemMaxHeight + "px");
		gridItemsEqualHeightApplied = true;
	}

	$('.collapsible').each(function(index) {
		$(this).prepend('<span class="opener">&nbsp;</span>');
		if ($(this).hasClass('active')) {
			$(this).children('.block-content').css('display', 'block');
		} else {
			$(this).children('.block-content').css('display', 'none');
		}
	});

	$('.collapsible .opener').click(function() {
		var parent = $(this).parent();
		if (parent.hasClass('active')) {
			$(this).siblings('.block-content').stop(true).slideUp(300, "easeOutCubic");
			parent.removeClass('active');
		} else {
			$(this).siblings('.block-content').stop(true).slideDown(300, "easeOutCubic");
			parent.addClass('active');
		}
	});

	var ddOpenTimeout;
	var dMenuPosTimeout;
	var DD_DELAY_IN = 200;
	var DD_DELAY_OUT = 0;
	var DD_ANIMATION_IN = 0;
	var DD_ANIMATION_OUT = 0;
	$(".clickable-dropdown > .dropdown-toggle").click(function() {
		$(this).parent().addClass('open');
		$(this).parent().trigger('mouseenter');
	});

	$(".dropdown").hover(function() {
		var ddToggle = $(this).children('.dropdown-toggle');
		var ddMenu = $(this).children('.dropdown-menu');
		var ddWrapper = ddMenu.parent();
		ddMenu.css("left", "");
		ddMenu.css("right", "");
		if ($(this).hasClass('clickable-dropdown')) {
			if ($(this).hasClass('open')) {
				$(this).children('.dropdown-menu').stop(true, true).delay(DD_DELAY_IN).fadeIn(DD_ANIMATION_IN, "easeOutCubic");
			}
		} else {
			clearTimeout(ddOpenTimeout);
			ddOpenTimeout = setTimeout(function() {
				ddWrapper.addClass('open');
			}, DD_DELAY_IN);

			//$(this).addClass('open');
			$(this).children('.dropdown-menu').stop(true, true).delay(DD_DELAY_IN).fadeIn(DD_ANIMATION_IN, "easeOutCubic");
		}

		clearTimeout(dMenuPosTimeout);
		dMenuPosTimeout = setTimeout(function() {
			if (ddMenu.offset().left < 0) {
				var space = ddWrapper.offset().left;
				ddMenu.css("left", (-1) * space);
				ddMenu.css("right", "auto");
			}
		}, DD_DELAY_IN);

	}, function() {
		var ddMenu = $(this).children('.dropdown-menu');
		clearTimeout(ddOpenTimeout);
		ddMenu.stop(true, true).delay(DD_DELAY_OUT).fadeOut(DD_ANIMATION_OUT, "easeInCubic");
		if (ddMenu.is(":hidden")) {
			ddMenu.hide();
		}
		$(this).removeClass('open');
	});

	var windowScroll_t;
	$(window).scroll(function() {
		clearTimeout(windowScroll_t);
		windowScroll_t = setTimeout(function() {
			if ($(this).scrollTop() > 100) {
				$('#scroll-to-top').fadeIn();
			} else {
				$('#scroll-to-top').fadeOut();
			}
		}, 500);
	});

	$('#scroll-to-top').click(function() {
		$("html, body").animate({scrollTop: 0}, 600, "easeOutCubic");
		return false;
	});

	var startHeight;
	var bpad;
	$('.category-products-grid').on('mouseenter', '.item', function() {
		if ($(window).width() >= 320) {
			if (gridItemsEqualHeightApplied === false) {
				return false;
			}
			startHeight = $(this).height();
			$(this).css("height", "auto"); //Release height
			$(this).find(".display-onhover").fadeIn(400, "easeOutCubic"); //Show elements visible on hover
			var h2 = $(this).height();
			////////////////////////////////////////////////////////////////
			var addtocartHeight = 0;
			var addtolinksHeight = 0;
			var addtolinksEl = $(this).find('.add-to-links');
			if (addtolinksEl.hasClass("addto-onimage") == false)
				addtolinksHeight = addtolinksEl.innerHeight(); //.height();

			var h3 = h2 + addtocartHeight + addtolinksHeight;
			var diff = 0;
			if (h3 < startHeight) {
				$(this).height(startHeight);
			} else {
				$(this).height(h3);
				diff = h3 - startHeight;
			}
			////////////////////////////////////////////////////////////////
			$(this).css("margin-bottom", "-" + diff + "px");
		}
	}).on('mouseleave', '.item', function() {
		if ($(window).width() >= 320) {
			//Clean up
			$(this).find(".display-onhover").stop(true).hide();
			$(this).css("margin-bottom", "");
			$(this).height(startHeight);
		}
	});

	$('.products-grid, .products-list').on('mouseenter', '.item', function() {
		$(this).find(".alt-img").fadeIn(400, "easeOutCubic");
	}).on('mouseleave', '.item', function() {
		$(this).find(".alt-img").stop(true).fadeOut(400, "easeOutCubic");
	});

	$('.fade-on-hover').on('mouseenter', function() {
		$(this).animate({opacity: 0.75}, 300, 'easeInOutCubic');
	}).on('mouseleave', function() {
		$(this).stop(true).animate({opacity: 1}, 300, 'easeInOutCubic');
	});

	var winWidth = $(window).width();
	var winHeight = $(window).height();
	$(window).resize(
		$.debounce(50, onEventResize)
	); //end: resize

	function onEventResize() {
		var winNewWidth = $(window).width();
		var winNewHeight = $(window).height();
		if (winWidth != winNewWidth || winHeight != winNewHeight)
		{
		afterResize();
		}
		//Update window size variables
		winWidth = winNewWidth;
		winHeight = winNewHeight;

	} //end: onEventResize

	function afterResize() {
		$(document).trigger("themeResize");
		setGridItemsEqualHeight($);
		$('.itemslider').each(function(index) {
			var flex = $(this).data('flexslider');
			if (flex != null) {
				flex.flexAnimate(0);
				flex.resize();
			}
		});
		var slideshow = $('.the-slideshow').data('flexslider');
		if (slideshow != null) {
			slideshow.resize();
		}
	} //end: afterResize

	$( ".tabs" ).tabs();

	jQuery('#slideshow').owlCarousel({
		items:1,
		singleItem : true,
		lazyLoad : true,
		navigation: true,
		pagination : false
	});

	var owl = jQuery('#itemslider-zoom').owlCarousel({
		items : 2,
		itemsCustom : false,
		itemsDesktop : [1200, 2],
		itemsDesktopSmall : [1180, 2],
		itemsMobile : [768,1],
		afterMove: function(e) {
			var obj = owl.data('owlCarousel');
			jQuery('.thumbs').removeClass('active');
			jQuery('.thumb-' + obj.currentItem).addClass('active');
			return false;
		}
		// startDragging: function(e) {
		// 	console.log(e);
		// 	return false;
		// }
	});

	jQuery('#best_seller').owlCarousel({
		items: 4,
		itemsMobile : [479, 2],
		slideSpeed: 200,
		paginationSpeed: 500,
		autoPlay: 8000,
		stopOnHover: true,
		rewindNav: true,
		rewindSpeed: 600,
		transitionStyle: 'fadeUp',
		navigation: false ,
		responsiveBaseWidth: window
	}); //end: owl

	/*jQuery('#similar-seller').owlCarousel({
		items: 4,
		slideSpeed: 200,
		paginationSpeed: 500,
		autoPlay: 8000,
		stopOnHover: true,
		rewindNav: true,
		rewindSpeed: 600,
		transitionStyle: 'fadeUp',
		navigation: false ,
		responsiveBaseWidth: window
	}); //end: owl*/

	/*jQuery('#shape-the-look').owlCarousel({
		items: 4,
		slideSpeed: 200,
		paginationSpeed: 500,
		autoPlay: 8000,
		stopOnHover: true,
		rewindNav: true,
		rewindSpeed: 600,
		transitionStyle: 'fadeUp',
		navigation: false ,
		responsiveBaseWidth: window
	}); //end: owl*/

	jQuery('.input_class_checkbox').each(function(){
		jQuery(this).hide().after('<div class="class_checkbox" />');
	});

	jQuery('.class_checkbox').on('click',function(){
		jQuery(this).toggleClass('checked').prev().prop('checked',jQuery(this).is('.checked'))
	});

	jQuery.widget( "custom.catcomplete", jQuery.ui.autocomplete, {
		_create: function() {
			this._super();
			this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
		},
		_renderMenu: function( ul, items ) {
			var that = this,
			currentCategory = "";
			console.log(items);
			jQuery.each( items, function( index, item ) {
				var li;
				if ( item.category != currentCategory ) {
					ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
					currentCategory = item.category;
				}
				li = that._renderItemData( ul, item );
				if ( item.category ) {
					li.attr( "aria-label", item.category + " : " + item.label );
				}
			});
		}
	});

	function log( message ) {
		jQuery("<div>").text(message).prependTo("#log");
		location=message;
	}
	
	jQuery( "#tags1" ).catcomplete({
		minLength: 2,
		source: 'product/search',
		select: function( event, ui ) {
			location=ui.item.href;
		}
	});
	
	//Mobile Footer Accordion
	jQuery('#accordion h2').each(function() {
		var tis = $(this), state = false, answer = tis.next('div.answer').hide().css('height','auto').slideUp();
		tis.click(function(e) {
			e.preventDefault();
			var bool = tis.hasClass('active');
			$('#accordion div.answer').slideUp();
			$('#accordion a label.mark span').text('+');
			$('#accordion h2').removeClass('active');
			if(!bool) {
				state = !state;
				answer.slideToggle(state);
				// tis.toggleClass('active',state);
				var href = tis.children('a').attr('href');
				tis.addClass('active');
				// $('#accordion h2 a[href='+href+']').children('span').text('-');
				$('a label.mark span',tis).text('-');
			}
			
			//alert(rel);
			/*setTimeout(function(){
				var pos = $('a[href='+href+']').offset();
				//alert(pos.top);
				$('html, body').animate({ scrollTop: pos.top }, 1000);
			}, 500);*/
		});
		var pageheight = $(this).height();
		jQuery('label.mark span',this).css('height',pageheight);
	});
	jQuery(window).resize(function($) {
		jQuery('#accordion h2 label.mark span').css('height','');
		jQuery('#accordion h2').each(function() {
			var pageheight = jQuery(this).height();
			jQuery('label.mark span',this).css('height',pageheight);
		});
	});

	/*
	jQuery(document).on('hover','li.item', function(){
		jQuery('.btn-cart',this).tooltip({
			tooltipClass: "tooltip"
		});
	});*/
});

jQuery('.product-like').click(function() {
	var obj = jQuery(this);
	var src = jQuery('img',obj).attr('src');
	if(src.indexOf('heart-icon-red') != -1) {
		return;
	}
	jQuery.ajax({
		url: 'product/product/like',
		type: 'GET',
		data: {product_id: obj.val()},
		dataType: 'json',
		success: function(res) {
			if(res.hasOwnProperty('error')) {
				if(res.hasOwnProperty('not_logged')) {
					jQuery('#open_login').click();
					jQuery('#customerLogin .alert-warning').html(res.error).removeClass('hide');
					setTimeout(function() {
						jQuery('#customerLogin .alert-warning').addClass('hide');
					},2000);
				} else {
					alert(res.error);
				}
				return;
			}
			jQuery('img',obj).attr('src',src.replace('heart-icon','heart-icon-red'));
		}
	});
});

jQuery(document).on('click','#activator',function(e){
	if(jQuery('#overlay').is(':visible')) {
		hideCart();
	} else {
		showCart();
	}
});

jQuery(document).on('click','#overlay,#boxclose',function(e){
	hideCart();
});

function showCart() {
	jQuery('#overlay').fadeIn('fast',function(){
		jQuery('#cartopen').animate({'top':'52px' ,'right':'0px'},500);
	});
}

function hideCart() {
	jQuery('#cartopen').animate({'right':'-3000px'},500,function(){
		jQuery('#overlay').fadeOut('fast');
	});
}