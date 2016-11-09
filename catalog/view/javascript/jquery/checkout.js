var checkoutForm;
jQuery(document).on("change",'#payment_check',function( event ) {
    if (jQuery(this).is(':checked') == true) {
        jQuery('#payment_address_box').hide();
        jQuery('#payment_address_select').hide();
        jQuery('.payment_address_list').attr('checked',false);
        jQuery('#payment_new').show();
    } else {
        if(logged) {
            if(jQuery('#payment_address_select .form-list li').not('.hide').length > 0) {
                jQuery('#payment_address_select').show();
                jQuery('#payment_address_select .form-list li').not('.hide').first().find('.payment_address_list').trigger('click');
            }
            else {
                jQuery('#payment_address_box').show();
            }
        }
        else {
            jQuery('#payment_address_box').show();
        }
        jQuery('#payment_address_box input[type=text]').val('');
        jQuery('#payment_address_box select').val('');
        jQuery('#payment_address_box select option[value="' + default_country + '"]').attr('selected',true);
        jQuery('#payment_address_box select').trigger('change');
        jQuery('#payment_address_box input[type=radio]').attr('checked', false);
    }
});

jQuery( document ).on( "click", "#payment_new", function( event ) {
    jQuery('.payment_address_list').icheck('unchecked');
    jQuery('#payment_address_box').show();
    jQuery('#payment_address_box input[type=text]').val('');
    jQuery('#payment_address_box select').val('');
    jQuery('#payment_address_box select option[value="' + default_country + '"]').attr('selected',true);
    jQuery('#payment_address_box select').trigger('change');
    jQuery(this).hide();
});

jQuery( document ).on( "click", "#shipping_new", function( event ) {
    jQuery('.shipping_address_list').icheck('unchecked');
    jQuery('.payment_row').removeClass('hide');
    var form = jQuery('#shipping_form');
    jQuery('input[type=text]',form).val('');
    jQuery('select',form).val('');
    jQuery('select#shipping_country_id option[value="' + default_country + '"]',form).attr('selected',true).trigger('change');
    jQuery('#shipping_form').show();
    jQuery(this).hide();
});

//jQuery('.shipping_address_list').on('click', function() {
jQuery( document ).on( "click", ".shipping_address_list", function( event ) {
    jQuery('ul.messages').empty();
    getAddress(jQuery(this), 'shipping');
    jQuery('#shipping_new').show();
    jQuery('#shipping_form').hide();
    jQuery('.payment_row').removeClass('hide');
    jQuery('#payment_row_' + jQuery(this).val()).addClass('hide');
    if (jQuery('.payment_address_list').length == 1) {
        jQuery('#payment_address_box .right').hide();
    }
    if (jQuery('.payment_address_list:checked').val() == jQuery(this).val()) {
    }
});

jQuery('.payment_address_list').on('click', function() {
    jQuery('#payment_new').show();
    getAddress(jQuery(this), 'payment');
    jQuery('#payment_address_box').hide();
});

jQuery('#comment').blur(function() {
    if (jQuery(this).val() != "") {
        jQuery.ajax({
            type: 'post',
            url: comment_url,
            dataType: 'json',
            data: 'comment=' + jQuery(this).val(),
            success: function(html) {
            }
        });
    }
});

function getAddress(obj, type) {
    jQuery.ajax({
        type: 'get',
        url: 'checkout/confirm/getAddress',
        dataType: 'json',
        data: 'address_id=' + obj.val(),
        success: function(res) {
            if (res.error == undefined) {
                jQuery.each(res, function(i, val) {
                    if (jQuery('#' + type + '_' + i).is('input'))
                        jQuery('#' + type + '_' + i).val(val);
                    else if (jQuery('#' + type + '_' + i).is('select')) {
                        jQuery('#' + type + '_' + i + ' option[value=' + val + ']').attr('selected', true);
                        if (i == 'country_id') {
                            loadZone(type + '_zone_id', val, res.zone_id);
                        }
                    }
                });
                if(type == 'shipping'){
                    getShippingMethods({shipping:{1:res}});
                }
            } else {
                alert(res.error);
            }
        }
    });
}

jQuery( document ).on( "click", ".btn-final-checkout", function( event ) {
    if (jQuery('.validation-advice').length > 0) {
        jQuery('.validation-advice').remove();
    }
    if (!bPost) {
        return false;
    }
    if(jQuery('input[name="agree"]').is(":checked")) {
     var payment = jQuery('input.payment_method:checked').val();
        jQuery.ajax({
            type: "post",
            dataType: 'json',
            data: jQuery('#checkout_address').serialize(),
            url: 'checkout/confirm/save',
            beforeSend: function() {
                jQuery('ul.messages').empty();
                jQuery('.btn-final-checkout').hide();
                jQuery('.btn-final-checkout').before('<div class="loader"></div>');
            },
            success: function(res) {
                if (typeof res.error == "undefined") {
                    var obj = {};
                    if(payment == 'checkoutapipayment') {
                        // obj = jQuery('#payment').serialize();
                        var form = jQuery('.payment-detail');
                        CheckoutKit.configure({
                            debugMode: true,
                            publicKey: res.publicKey,
                            customerEmail: res.customerEmail,
                            ready: function(e) {
                                CheckoutKit.createCardToken(
                                        {
                                            number: jQuery('[data-checkout="card-number"]',form).val(),
                                            name: jQuery('[data-checkout="card-name"]',form).val(),
                                            expiryMonth: jQuery('[data-checkout="expiry-month"]',form).val(),
                                            expiryYear:  jQuery('[data-checkout="expiry-year"]',form).val(),
                                            cvv: jQuery('[data-checkout="cvv"]',form).val()
                                        },function(response) {
                                            obj = response;
                                            paymentConfirm(payment,obj);
                                        });
                            },
                            apiError: function(error) {
                                console.log(error);
                                var html = "<li class='error-msg'><ul>";
                                if(error.data.hasOwnProperty('errors')) {
                                    jQuery.each(error.data.errors,function(i,error) {
                                        html += "<li>" + error + "</li>";
                                    });
                                }
                                else if(error.data.hasOwnProperty('message')) {
                                    html += "<li>" + error.data.message + "</li>";
                                }
                                else {
                                    html += "<li>Invalid Credit Card details</li>";  
                                }
                                html += "</ul></li>";
                                jQuery('ul.messages').html(html);
                                jQuery('.loader').remove();
                                jQuery('.btn-final-checkout').show();
                                jQuery('html, body').animate({ scrollTop:jQuery('#Content').offset().top}, 500);
                            }
                        });
                        CheckoutKit.monitorForm('.card-form',CheckoutKit.CardFormModes.CARD_TOKENISATION);
                    }
                    else {
                        paymentConfirm(payment,obj);
                    }
                } else {
                    jQuery.each(res.error, function(k, val) {
                        if(k != "warning") {
                            jQuery.each(val, function(v, error) {
                                jQuery('#' + k + '_' + v).after('<div class="validation-advice">' + error + '</div>');
                            });

                            jQuery('html, body').animate({ scrollTop: jQuery('input').first().offset().top }, 500);
                            jQuery('ul.messages').html("<li class='error-msg'><ul><li>There is an error occured, please check below item in red.</li></ul></li>");
                            jQuery('.loader').remove();
                            jQuery('.btn-final-checkout').show();
                        }
                        else {
                            jQuery('ul.messages').html("<li class='error-msg'><ul><li>"+val+"</li></ul></li>");
                        }

                    });
                }
            },
            complete: function() {
                jQuery('html, body').animate({ scrollTop:jQuery('#Content').offset().top}, 500);
                jQuery('.loader').remove();
                jQuery('.btn-final-checkout').show();
            }
        });
    }
    else {
        jQuery('ul.messages').html("<li class='error-msg'><ul><li>You need to agree to the Terms & Conditions in order to proceed</li></ul></li>");
        jQuery('html, body').animate({ scrollTop:jQuery('#Content').offset().top}, 500);
    }
});

function paymentConfirm(payment,params) {
    jQuery.ajax({
        type: 'GET',
        dataType: 'json',
        data: params,
        url: 'payment/' + payment + '/confirm',
        success: function(data) {
            console.log(data);
            var activeTab = '#'+jQuery('.payment-panels:visible').attr('id');
            if (typeof data.error == "undefined") {
                if (typeof data.html != "undefined") {
                    jQuery('#customer-' + payment + ' .payment-detail').html(data.html);
                }
                if (typeof data.continue != "undefined") {
                    location.href = data.continue;
                } else {
                    jQuery(activeTab).find('form').submit();
                }
            } else {
                jQuery('ul.messages').html("<li class='error-msg'><ul><li>"+ data.error +"</li></ul></li>");
                jQuery('.please-wait').remove();
                jQuery('.checkout-btn').show();
            }
        }
    });
}

function loadZone(id, country_id, zone_id) {
    if (country_id) {
        bPost = false;
        jQuery('#' + id).after('<span class="ajax-loader fixed-loader"></span>');
        jQuery('#' + id).load(zone_url + '&country_id=' + country_id + '&zone_id=' + zone_id, function() {
            bPost = true;
            jQuery('.ajax-loader').remove();
        });
    }
}

loadZone('shipping_zone_id',shipping_country_id,shipping_zone_id);
loadZone('payment_zone_id',payment_country_id,payment_zone_id);

jQuery(document).on('click','input[name="shipping_method['+form_code+']"]',function(e) {
    var shipping = jQuery(this).attr('value');
    jQuery.ajax({
        url: shipping_url,
        type: 'POST',
        data: {shipping: shipping},
        dataType: 'html',
        success: function(res) {
            if(res.trim() != "") {
                var top = jQuery('#cart-summary #summary').css('top');
                jQuery('#cart-summary').html(res);
                jQuery('#cart-summary #summary').css('top',top);
            }
        }
    });
});

jQuery(document).on('blur','#shipping_address_box .required-entry',function(e) {
    var bValid = true;
    jQuery('#shipping_address_box .required-entry').each(function(){
        if(jQuery.trim(jQuery(this).val()) == '' || jQuery.trim(jQuery(this).val()) == 'FALSE'){
            bValid = false;
        }
    });
    if(bValid){
        getShippingMethods(jQuery('#checkout_address').serializeArray());

    }
});

function getShippingMethods(data){
    var bReturn = true;
    jQuery.ajax({
        url: shipping_method_url,
        type: 'get',
        data: data,
        dataType: 'json',
        async: false,
        success: function(res) {
            if(typeof res.error == 'undefined'){
                html = '';
                jQuery.each(res.methods,function(id, method){
                    html += '<div class="col-sm-12 no-padding"><p class="acctab sub-heading-acount">'+method['title']+'</p><div class="line"></div></div>';
                    html += '<div class="col-sm-12"><ul class="form-list">';
                    jQuery.each(method['quote'],function(method_id,val){
                        html += '<li class="control col-sm-12 no-padding skin-minimal">';
                        html += '<div class="col-sm-1"><input type="radio" class="shipping_method radio" name="shipping_method[' + form_code + ']" value="'+val.id+'" id="shipping_'+val.id+'" /></div>';
                        html += '<div class="col-sm-11"><label for="shipping_'+method_id+'">'+val.title+' <span class="price">'+val.text+'</span></label></div>';
                        html += '</li>';
                    });
                    html +='</ul></div>';
                });
                jQuery('#shipping-methods-block .step.a-item').html(html);
                jQuery('.shipping_method:first').trigger('click');
                jQuery('.shipping_method').icheck({
                    checkboxClass: 'icheckbox_square-grey',
                    radioClass: 'iradio_square-grey',
                    increaseArea: '80%'
                });
            }
            else {
                bReturn = false;
                jQuery('ul.messages').html('<li class="error-msg">' + res.error + '</li>');
                jQuery('html, body').animate({scrollTop:jQuery('#Content').offset().top},500);
            }
        }
    });
    return bReturn;
}
function create_account(){
    jQuery('.validation-advice').empty();
    jQuery('#register-box').fadeIn('fast');
    jQuery('#register').fadeIn('fast').delay(100);
    jQuery('#sign_in').fadeOut('fast').delay(100);
    jQuery('.forgot_message').empty();
    jQuery('#alert_messages').empty();
    jQuery('#alert_messages').addClass('hide');

    jQuery('#login_type').val('1');
}
function login_account(){
    jQuery('.validation-advice').empty();
    jQuery('#register-box').fadeOut('fast');
    jQuery('#register').fadeOut('fast').delay(100);
    jQuery('#sign_in').fadeIn('fast').delay(100);
    jQuery('.forgot_message').empty();
    jQuery('#login_type').val('0');
}

//  jQuery(document).ready(function() {
if(jQuery('#is_loged').val()=='1'){
    jQuery('.section').fadeIn('fast');
    // jQuery('.a-item').fadeIn('fast');
}
else{
    jQuery('.section').fadeOut('fast');
    //jQuery('.a-item').fadeOut('fast');

}
jQuery('#register').fadeOut('fast');

jQuery(document).on("click","#sign_in",function(e) {
    e.preventDefault();
    jQuery('.validation-advice').remove();
    jQuery('#alert_messages').empty();
    jQuery('#alert_messages').addClass('hide');
    var VError=false;
    var email=jQuery('#email').val();
    var password=jQuery('#password').val();

    if(email==''){
        jQuery('#email').after('<div class="validation-advice">This is a required field</div>');
        VError=true;
    }
    else{

        var emailReg = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if(!emailReg.test( email )){
            jQuery('#email').after('<div class="validation-advice">Please enter a valid email address</div>');
            VError=true;
        }
    }

    if(password==''){
        jQuery('#password').after('<div class="validation-advice">This is a required field</div>');
        VError=true;
    }
    if(!VError) {
        jQuery.ajax({
            url: login_url,
            type: 'post',
            data: jQuery('#login_form').serializeArray(),
            dataType: 'json',
            success: function (res) {
                if (typeof res.options != 'undefined') {
                    // location.href = url;

                    html ='<div class="col-sm-12">';
                    html +='<ul class="form-list">';

                    jQuery.each(res.addresses,function(id, method){
                        //alert(method['address_id']);
                        html +='<li class="control col-sm-12 no-padding address-pane"><div class="skin-minimal">';
                        html +='<div class="col-sm-1 no-padding"><input type="radio" name="shipping[address_id]" value="'+method['address_id']+'" id="shipping_addr_'+method['address_id']+'" class="shipping_address_list address radio left" /></div>';
                        html +='<div class="col-sm-11 no-padding">' + 
                                '<label for="shipping_addr_' + method['address_id'] + '" class="left">'+ method['address'] + '</label></div>';
                        html +='</div></li>';
                    });
                    html +='</ul>';
                    html +='</div>';
                    html += '<div class="col-sm-4">' +
                            '<button type="button" id="shipping_new" class="btn btn-cntinue btn-account btn-another"><span><span>' + text_new_address + '</span></span></button></div>';
                    jQuery('#shipping_select').html(html);
                    jQuery('.users').fadeOut();
                    //jQuery('#shipping_option').fadeIn('fast');
                    //jQuery('#next_shipping').show();
                    jQuery('#address_information').fadeIn();
                    jQuery('#login_step').fadeIn();
                    jQuery('#shipping_select .skin-minimal input').icheck({
                        checkboxClass: 'icheckbox_square-grey',
                        radioClass: 'iradio_square-grey',
                        increaseArea: '80%'
                    });
                }
                else if (typeof res.error != 'undefined') {
                        jQuery('#alert_messages').html('<ul><li class="error-msg">' + res.error + '</li></ul>').removeClass('hide');
                }
            }
        });
    }
    return false;
});

jQuery(document).on("click", "#register", function(e) {
    e.preventDefault();
    var obj = jQuery(this);
    jQuery('.login_register .validation-advice').remove();
    jQuery('.error-msg').empty();
    jQuery('#alert_messages').empty().addClass('hide');
    var bError = false;
    var fields = ['firstname','lastname','email','password'];
    jQuery.each(fields,function(i,field) {
        if(jQuery('#' + field).val() == '') {
            jQuery('#' + field).after('<div class="validation-advice">This is a required field</div>');
            bError = true;
        } else {
            var emailReg = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if(jQuery('#' + field).attr('name') == 'email' && !emailReg.test( jQuery('#' + field).val() )){
                jQuery('#' + field).after('<div class="validation-advice">Please enter a valid email address</div>');
                bError=true;
            }
        }
    });
    if(!bError) {
        jQuery.ajax({
            url: register_url,
            type: 'post',
            data: jQuery('#login_form').serializeArray(),
            dataType: 'json',
            beforeSend: function() {
                obj.hide();
                obj.before('<div class="loader"></div>');
            },
            success: function (res) {
                if (res.hasOwnProperty('error')) {
                    jQuery.each(res.error,function(i,error) {
                        jQuery('#' + i).after('<div class="validation-advice">' + error + '</div>');
                    });
                    if(res.error['warning'])
                        jQuery('#email').after('<div class="validation-advice">' + res.error['warning'] + '</div>');
                    return;
                }
                location.href = success_register;
            },
            complete: function() {
                obj.show();
                jQuery('.loader').remove();
            }
        });
    }
    return false;
});

jQuery( document ).on( "click", "#link_forgot", function( event ) {
    jQuery('#login_form').fadeOut('fast');
    jQuery('#forgotten').fadeIn('fast').delay(100);
    login_account()
});

jQuery( document ).on( "click", "#link_login_form", function( event ) {
    jQuery('#forgotten').fadeOut('fast').delay(100);
    jQuery('#login_form').fadeIn('fast');
});


jQuery( document ).on( "click", "#forget_password", function( e ) {
    e.preventDefault();
    jQuery('.validation-advice').remove();
    jQuery('#email_forgotten').removeClass('error');
    var VError = false;

    var email = jQuery('#email_forgotten').val();
    var checkout_login = jQuery('#checkout_login').val();

    if (email == '') {
        jQuery('#email_forgotten').after('<div class="validation-advice">This is a required field</div>');
        return false;
    }

    // var url = "<?php echo jQuerysuccess_action ?>";

    if(!VError) {
        jQuery.ajax({
            url: forgotten_url,
            type: 'post',
            data: 'email=' + email + '&checkout_login=' + checkout_login,
            dataType: 'json',
            success: function (res) {
                if (typeof res.options != 'undefined') {
                    jQuery('#forgotten').fadeOut();
                    jQuery('#login_form').fadeIn('fast');
                    login_account()
                    jQuery('#alert_messages').html('<ul class="messages forgot_message"><li class="success-msg"><ul><li><span>' + res.options + '</span></li></ul></li></ul>').removeClass('hide');
                }
                else {
                    if (typeof res.error != 'undefined') {
                        if (res.error['message']) {
                            jQuery('#alert_messages').html('<div class="warning validation-advice">' + res.error['message'] + '</div>').removeClass('hide');
                            jQuery('#email_forgotten').addClass('error');
                        }
                    }
                }
            }
        });
    }
    return false;
});

jQuery( document ).on( "click", "#guest_btn", function( event ) {
    jQuery('.validation-advice').empty();
    jQuery('.error-msg').empty();
    jQuery('#alert_messages').empty().addClass('hide');
    jQuery('.users').fadeOut();
    //jQuery('.a-item').fadeIn();
    jQuery('#address_information').fadeIn('fast');
    jQuery('#next_step').show();
    jQuery('#login_step').fadeIn('fast');
});

function validateForm(type,data,obj) {
    jQuery('.validation-advice').remove();
    var bError = true;
    data.push({name:'form_type',value:type});
    jQuery.ajax({
        url: validate_url,
        type: 'POST',
        dataType: 'json',
        data: data,
        async: false,
        beforeSend: function() {
            obj.hide();
            obj.before('<div class="loader"></div>');
        },
        success: function(res) {
            var mix = /\,/.test(type);
            if(!jQuery.isEmptyObject(res.errors)){
                if(mix){
                    var aType = type.split(',');
                    jQuery.each(aType,function(i,val){
                        bError = showAddressErrors(res, val, bError);
                    });
                } else {
                    bError = showAddressErrors(res, type, bError);
                }
                
            }
        },
        complete: function() {
            obj.show();
            jQuery('.loader').remove();
        }
    });
    return bError;
}

function showAddressErrors(res, type, bError){
    if(!jQuery.isEmptyObject(res.errors[type])) {
        bError = false;
        console.log(res.errors[type]);
        jQuery.each(res.errors[type],function(i,val) {
            console.log('#' + type + '_' + i);
            jQuery('#' + type + '_' + i).after('<div class="validation-advice">' + val + '</div>');
        });
    }
    return bError;
}

jQuery( document ).on( "click", "#next_step", function( event ) {
    var obj = jQuery(this);
    checkoutForm = new VarienForm("checkout_address",true,false)
    jQuery('ul.messages').empty();
    var bCheck=true;
    var test=jQuery("input:radio[name='shipping[address_id]']").is(":checked");
    if(!test){
        var form = jQuery('#shipping_form').find('input[name],select[name]').serializeArray();
        var type = 'shipping';
        if(!jQuery('#payment_check').is(':checked')){
            if(!jQuery("input:radio[name='payment[address_id]']").is(":checked")){
                form = jQuery('#shipping_form,#payment_address_box').find('input[name],select[name]').serializeArray();    
                type += ',payment';        
            }
        }
        bCheck = checkoutForm.submit();
        if(bCheck){
            bCheck = validateForm(type,form,obj);
        }
    }

    if(bCheck){
        if(getShippingMethods(jQuery('#checkout_address').serializeArray())) {
            jQuery('#address_information').fadeOut();
            if(cart_subtotal >= free_shipping_amount) {
                jQuery('#payment_and_information').fadeIn();
                jQuery('input.shipping_method[value="free.free"]').trigger('click');
                jQuery('.payment_method:first').icheck('checked').trigger('change');
            }
            else {
                jQuery('#shipping-methods-block').fadeIn();
            }  
        }
    } else {
        jQuery('.validation-advice:first').prev().focus();
    }
});

jQuery(document).on('change','.payment_method',function () {
    jQuery('.payment-panels').hide();
    if(jQuery(this).is(':checked')) {
        jQuery('#'+jQuery(this).val()).show();
    }
});

jQuery(document).on('click','.btn-shipping',function(e) {
    if(typeof jQuery('input.shipping_method:checked').val() !== 'undefined') {
        jQuery('#shipping-methods-block').fadeOut();
        jQuery('#payment_and_information').fadeIn();
    }
});

jQuery(document).on('click','.go-back',function(e) {
    e.preventDefault();
    jQuery('#payment_and_information').fadeOut();
    jQuery('#address_information').fadeIn();
    return false;
});

jQuery(document).on('keypress','form',function(event){
    if( (event.keyCode == 13)) {
      event.preventDefault();
      return false;
    }
});

jQuery(document).ready(function(){
    jQuery('#payment_and_information').fadeOut();
    jQuery('#coupon-list').fadeOut();
    jQuery('.cart-link').attr('id','');
    jQuery('#shipping-methods-block').hide();
    jQuery('#payment_check').trigger('change');
    jQuery('.payment-panels').hide();
    var p = jQuery(window).width() >= 0x3C0;
    jQuery(".gen-tabs > .tabs").tabs(".tabs-panels .panel");
    var t;
    jQuery(window).resize(function() {
        clearTimeout(t);
        t = setTimeout(function() {
            if (jQuery(window).width() < 0x3C0) {
                if (p) {
                    var a = jQuery(".tabs").data("tabs");
                    var b = a.getIndex();
                    a.destroy();
                    jQuery(".gen-tabs").addClass("accor");
                    jQuery(".tabs-panels").tabs(".tabs-panels .panel", {tabs: '.acctab', effect: 'slide', initialIndex: b})
                }
                p = false
            } else {
                if (!p) {
                    var a = jQuery(".tabs-panels").data("tabs");
                    var b = a.getIndex();
                    a.destroy();
                    jQuery(".gen-tabs").removeClass("accor");
                    jQuery(".gen-tabs > .tabs").tabs(".tabs-panels .panel", {initialIndex: b})
                }
                p = true
            }
        }, 500)
    });

    if (shipping_address_id !=''){
        if (!error_shipment_count)
            jQuery('.shipping_address_list[value=' + shipping_address_id + ']').attr('checked', true).trigger('click');
        else
            jQuery('.shipping_address_list[value=' + shipping_address_id + ']').attr('checked', true);
    }

    if (payment_address_id != '') {
        if (!error_payment_count) {
            if (!payment_address_id || (payment_address_id == shipping_address_id)) {
                jQuery('#payment_address_box').hide();
            } else {
                jQuery('.payment_address_list[value=' + payment_address_id + ']').attr('checked', true).trigger('click');
            }
        } else {
            jQuery('.payment_address_list[value=' + payment_address_id + ']').attr('checked', true);
        }
    }
    jQuery('.shipping_address_list:first').trigger('click');
});

// Coupon Script
jQuery(document).on('click','.coupon-message',function(e){
        jQuery('.coupon-form').toggle();
});

jQuery(document).on('click','.btn-coupon',function(e) {
    e.preventDefault();
    var obj = jQuery(this);
    var coupon = jQuery('input[name="coupon"]').val();
    if(coupon.length > 0) {
        jQuery.ajax({
            url: coupon_action,
            type: 'POST',
            data: {coupon: coupon},
            dataType: 'html',
            beforeSend: function() {
                obj.hide();
                obj.before('<div class="loader"></div>');
            },
            success: function(res) {
                var alert;
                if(res.indexOf("Error") == -1) {
                    jQuery('#cart-summary').html(res);
                    alert = '<div class="alert alert-success">Success: Coupon Applied</div>';
                }
                else {
                    alert = '<div class="alert alert-warning">' + res + '</div>';
                }
                obj.closest('div.col-md-12').before(alert);
            },
            complete: function() {
                jQuery('.loader').remove();
                obj.show();
            }
        });
    }
    else {
         obj.closest('div.col-md-12').before('<div class="alert alert-warning">Error: Coupon Code cannot be empty</div>');
    }
    setTimeout(function(){
        jQuery('.alert').fadeOut().hide();
    },3000);
    return false;
});