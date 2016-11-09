jQuery(window).load(function(){

    setTimeout(function() {
        var header_height = jQuery('.fixed-top').outerHeight();
        var height = jQuery('div.bg-light-grey').outerHeight();
        var product_height = jQuery('div.product-div').outerHeight();
        var thumb_panel = jQuery("body > .app .slides-images").height();
        var image_panel = jQuery(".product-img").height();
        var thumb_height = (image_panel - thumb_panel) / 2;
        jQuery("body > .app .slides-images").css("top", thumb_height);
        jQuery('.main-content').scroll(function() {
            var top = jQuery(this).scrollTop();
            var offset = top + product_height;
            if(offset < height) {
                jQuery('div.product-div').animate({top:top},0);
            }
        });
    },1000);

});

jQuery(document).on('click','.btn-cart-main',function(e){
    jQuery('.alert-option').remove();
    if(jQuery('.product-options select,.product-options input').length > 0){
        
        var bValid = true;
        jQuery('.product-options select,.product-options input').removeClass('error');
        jQuery('.product-options select,.product-options input').each(function(){
            if(jQuery.trim(jQuery(this).val()) == ''){
                bValid = false;
                jQuery(this).addClass('error');
            }
        });
        if(!bValid){
            e.preventDefault();
            jQuery('.product-options').prepend('<div class="warning alert-option">Please specify the product\'s required option(s)</div>')
        }
        return bValid;
    }
});

jQuery(document).ready(function() {

    getDependentValue(jQuery('.color-swatch:first'));
    // selSize(jQuery('.dependent-value:not(.no-select):first span').attr('rel'));
    selSize(jQuery('.dependent-value:not(.no-select):first'));
});

jQuery(document).on('click',".tabs-menu-product a",function(event) {
    event.preventDefault();
    jQuery(this).parent().addClass("current");
    jQuery(this).parent().siblings().removeClass("current");
    var tab = jQuery(this).attr("href");
    jQuery(".tab-content-product").not(tab).css("display", "none");
    jQuery(tab).fadeIn();
}); 

jQuery(document).on('click','.product-overlay',function(e) {
    e.preventDefault();
    jQuery('#itemslider-zoom').trigger('owl.next');
});

jQuery(document).on('click','.thumbs',function(e) {
    e.preventDefault();
    var obj = jQuery(this);
    jQuery('.thumbs').removeClass('active');
    obj.addClass('active');
    var item = parseInt(obj.attr('data-rel'));
    jQuery('#itemslider-zoom').trigger('owl.goTo',item);
});

jQuery(document).on('submit','#product-form',function(e){
    var iLen = jQuery('.required').length;
    var bApprove = true;
    if(iLen > 0){
        jQuery('input.required,select.required').each(function(){
        if(!jQuery(this).val()){
            bApprove = false;
        }
        });
    }
    if(jQuery('.dependent-value').length > 0 && jQuery('.dependent-value.selected').length < 1){
        bApprove = false;
    }
    if(!bApprove){
        alert('Error: Please fill required field(s)');
        e.preventDefault();
        return false;
    }
});

// execute above function
initPhotoSwipeFromDOM('.my-gallery');

// jQuery(document).on('click','.my-gallery a',function(e) {
//     e.preventDefault();
//     return false;
// });

jQuery(document).on('click','.btn-out-of-stock',function(e) {
    var obj = jQuery(this);
    jQuery.ajax({
        url: '<?php echo $out_of_stock; ?>',
        type: 'GET',
        data: 
        {
            product_id: '<?php echo $product_id; ?>',
            email:jQuery('.email-out-of-stock').val(),
            name: jQuery('.main-product-name h1').text()
        },
        dataType: 'json',
        beforeSend: function() {
            obj.hide();
        },
        success: function(res) {
            var alert;
            if(res.hasOwnProperty('error')) {
                alert = '<div class="alert alert-warning">' + res.error + '</div>';
            }
            else {
                alert = '<div class="alert alert-success">' + res.success + '</div>';
            }
            jQuery('.email-out-of-stock').before(alert);
            setTimeout(function() {
                jQuery('.alert').remove();
            },2000);
        },
        complete: function() {
            obj.show();
        }
    });
});

function changePic(img){
    if(img){
        jQuery('.zoom-large').attr('src',img);
    }
}

// Product Review
jQuery('#review').load('product/product/review&product_id=' + product_id);

jQuery(document).on('submit','#review-form',function(e){
    review();
    e.preventDefault();
    return false;
});

function review() {
    jQuery.ajax({
        type: 'POST',
        url: 'product/product/write&product_id=' + product_id,
        dataType: 'json',
        data: 'name=' + encodeURIComponent(jQuery('input[name="name"]').val()) + '&text=' + encodeURIComponent(jQuery('textarea[name="text"]').val()) + '&rating=' + encodeURIComponent(jQuery('input[name="rating"]:checked').val() ? $('input[name="rating"]:checked').val() : '') + '&captcha=' + encodeURIComponent(jQuery('input[name="captcha"]').val()),
        beforeSend: function() {
            jQuery('.success, .warning').remove();
            jQuery('#review_button').attr('disabled', 'disabled');
            jQuery('#review_title').after('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" />Loading...</div>');
        },
        complete: function() {
            jQuery('#review_button').attr('disabled', '');
            jQuery('.wait').remove();
        },
        success: function(data) {
            if (data.error) {
                jQuery('#review_title').after('<div class="warning">' + data.error + '</div>');
            }

            if (data.success) {
                jQuery('#review_title').after('<div class="success">' + data.success + '</div>');

                jQuery('input[name="name"]').val('');
                jQuery('textarea[name="text"]').val('');
                jQuery('input[name="rating"]:checked').attr('checked', '');
                jQuery('input[name="captcha"]').val('');
            }
        }
    });
}

jQuery(document).on('click','.btn-option',function(e) {
    selSize(jQuery(this));
});

jQuery(document).on('click','.color-swatch',function(e) {
    getDependentValue(jQuery(this));
});

// Product Options
function selSize(obj){
    // var obj = jQuery('.swatch span[rel='+val+']').parent();
    // var obj = jQuery(obj).parent();
    var val = jQuery('span',obj).attr('rel');
    if(obj.hasClass('no-select') == false){
        jQuery('.hdn-dependent').remove();
        jQuery('.dependent-value').removeClass('selected');
        if(val){
            if(obj.hasClass('dependent-value')) {
                var color = jQuery('.color-swatch.selected').attr('alt');
                var option_id = jQuery('.color-swatch.selected').attr('data-option-id');
                var size = obj.attr('alt');
                var option_value_id = aDependents[color][size]['id'];
            }
            else {
                var option_id = obj.attr('data-option-id');
                var option_value_id = obj.attr('data-value');
            }
            if(option_value_id){
                jQuery('#product-options-wrapper').append('<input type="hidden" class="option-size hdn-dependent required" name="option['+option_id+']" value="'+option_value_id+'">');
            }
            obj.addClass('selected');
        }
    }
}

function showOptionDesc(id,obj){
    if(jQuery('#'+id).length > 0){
        if(jQuery(obj).val() != ''){
            jQuery('#'+id).show();
        } else {
            jQuery('#'+id).hide();
        }
    }
}

function getDependentValue(obj){
    var name = jQuery(obj).attr('alt');
    var option_id = jQuery(obj).attr('data-option-id');
    jQuery('.dependent-value').addClass('no-select');
    jQuery('.color-swatch').removeClass('selected');
    jQuery('.hdn-dependent').remove();
    var sc_name = jQuery('.dependent-value.selected').attr('alt');
    var option_value_id = 0;
    if(aDependents_length > 0) {
        jQuery.each(aDependents[name],function(i,val){
            if(val.quantity > 0) {
                jQuery('.dependent-value[alt='+i+']').removeClass('no-select');
                if(i == sc_name){
                    option_value_id = val['id'];
                }
            }
        });
    }
    if(option_value_id){
        jQuery('#product-options-wrapper').append('<input type="hidden" class="option-size hdn-dependent required" name="option['+option_id+']" value="'+option_value_id+'">');
    } else {
        jQuery('.dependent-value').removeClass('selected');
    }
    jQuery(obj).addClass('selected');
}

// Product Delivery
function delivery() {
    var _second = 1000;
    var _minute = _second * 60;
    var _hour = _minute * 60;
    var _day = _hour * 24;
    var today = new Date(dServer);
    var shipDate = new Date(dServer);
    var term_html = '<p><a class="muted" href="# target="_blank">Terms & Conditions apply</a></p>';
    if (delivery_days > 0) {
        shipDate.setDate(today.getDate() + delivery_days);
    }

    if (tCuttOff) {
        var aCutTime = tCuttOff.split(':');
        var cutDate = new Date(shipDate.getFullYear(), shipDate.getMonth(), shipDate.getDate(), aCutTime[0], aCutTime[1]);
        var dateCutOff = cutDate;
        if (shipDate.getTime() <= dateCutOff.getTime()) {
            if (shipDate.getTime() == today.getTime()) {
                var shipTime = dateCutOff.getTime() - shipDate.getTime();
                var days = Math.floor(shipTime / _day);
                var hours = Math.floor((shipTime % _day) / _hour);
                var minutes = Math.floor((shipTime % _hour) / _minute);
                var seconds = Math.floor((shipTime % _minute) / _second);
                var html = '<p>Order this product in the next</p><h2>' + (hours >= 1 ? hours + ' hour' + (hours > 1 ? 's' : '') : '') + ' ' + minutes + ' min' + (minutes > 1 ? 's' : '') + '</h2>';
                html += '<p>...and we will deliver it on the same day in UAE</p>';
                html += term_html;
                jQuery('#time_shipping').html(html).show();
            }
            else {
                var html = '<p>Available for delivery on</p><h2>' + shipDate.format('ddd dS mmmm') + '</h2>';
                html += term_html;
                jQuery('#time_shipping').html(html).show();
            }
        }
        else {
            var deliverDate = shipDate;
            deliverDate.setDate(shipDate.getDate() + 1);
            var html = '<p>Available for delivery on</p><h2>' + deliverDate.format('ddd dS mmmm') + '</h2>';
            html += term_html;
            $('#time_shipping').html(html).show();
        }
    }
}

jQuery(document).on('click','input[name="option_group"]',function(e) {
    jQuery('.group-child').addClass('hide');
    jQuery('div#' + jQuery(this).val() + '.group-child').removeClass('hide');
});