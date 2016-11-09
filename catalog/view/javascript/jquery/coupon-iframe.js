var parent_url = decodeURIComponent( document.location.hash.replace( /^#/, '' ) ),link;

$(document).ready(function() {
    $("#field_share_email").focus(function() {
        $(this).addClass("active");
        if($(this).attr("value") == text) $(this).attr("value", "");
    });

    $("#field_share_email").blur(function() {
        $(this).removeClass("active");
        if($(this).attr("value") == "") $(this).attr("value", text);
    });
	
    $("#share_button_email").click(function(){
        checkemail();
    });
});

function parent_callback(t,email){
    var pass_data = {
        'shared':t,
        'email':email
    };
	
    $.postMessage(
        JSON.stringify(pass_data),
        parent_url,
        parent
        );
}

function getpromocode(t,email,cp_ID){
    $.ajax({
        url: coupon_url,
        data: {
            type: t, 
            email: email, 
            id: ac_guid, 
            cp: cp_ID, 
            url: parent_url
        },
        dataType: 'jsonp',
        jsonp: 'callback',
        cache: false,
        jsonpCallback: 'jsonpCallback',
        success: function(){
            if (typeof(ju_callback) !== 'undefined' && typeof(ju_callback) === 'function') {
                ju_callback(t,email);
            };
        }
    });
			
    parent_callback(t,email);
}
function jsonpCallback(data){
    var cpidsp = data.cpids;
    var codesp = data.codes;
    var titlesp = data.titles;
    
    var cpids_array = cpidsp.split("|,|");
    var code_array = codesp.split("|,|");
    var title_array = titlesp.split("|,|");
    if ($('.couponcode').css('background-color')!='rgb(255, 255, 255)'){
        var tabcolor = $('.couponcode').css('background-color');
    } else {
        var tabcolor = $('.couponcode').css('color');
    }
    for (i in code_array) { 
        var cp_id = cpids_array[i];
        var cp_code = code_array[i];
        var cp_title = title_array[i];
        $('.couponcode[cp="'+cp_id+'"]').html('<div class="code">'+cp_code+'</div><div class="share_button" name="'+cp_title+'" link="http://'+fb_share_url+'/" image="" caption="'+fb_share_url+'" description="'+cp_title+'">Share on Facebook</a>');
			
        if(cp_code.indexOf("://") > -1 || cp_code.indexOf("</a>") > -1){
        //$('.copypaste').css('background-image','url('+$('.copypaste').css('background-image').replace('copy-paste-','click-link-')+')');
        }
        //$('.copypaste').fadeIn();
        $('.couponcode').addClass("showcoupon");
        $('.peel').remove();
        $('.couponcode').css('color',tabcolor);
        $('.couponcode').css('border',"5px solid "+tabcolor);
    };
}

function disableEnterKey(e)
{
    var key;     
    if(window.event)
        key = window.event.keyCode; //IE
    else
        key = e.which; //firefox     
    if (key == 13){
        return checkemail();
    }
}

function checkemail(){
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var emailaddressVal = $("#field_share_email").val();
    if(emailaddressVal!=''){
        if(!emailReg.test(emailaddressVal)) {
            alert(text);
            return false;
        } else {
            getpromocode('4',emailaddressVal,currentcp);
            wibiyaaction(104);
            return false;
        }
    } else {
        return false;
    }
}

/*
 * jQuery postMessage - v0.5 - 9/11/2009
 * http://benalman.com/projects/jquery-postmessage-plugin/
 * 
 * Copyright (c) 2009 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($){
    var g,d,j=1,a,b=this,f=!1,h="postMessage",e="addEventListener",c,i=b[h]&&!$.browser.opera;
    $[h]=function(k,l,m){
        if(!l){
            return
        }
        k=typeof k==="string"?k:$.param(k);
        m=m||parent;
        if(i){
            m[h](k,l.replace(/([^:]+:\/\/[^\/]+).*/,"$1"))
        }else{
            if(l){
                m.location=l.replace(/#.*$/,"")+"#"+(+new Date)+(j++)+"&"+k
            }
        }
    };

    $.receiveMessage=c=function(l,m,k){
        if(i){
            if(l){
                a&&c();
                a=function(n){
                    if((typeof m==="string"&&n.origin!==m)||($.isFunction(m)&&m(n.origin)===f)){
                        return f
                    }
                    l(n)
                }
            }
            if(b[e]){
                b[l?e:"removeEventListener"]("message",a,f)
            }else{
                b[l?"attachEvent":"detachEvent"]("onmessage",a)
            }
        }else{
            g&&clearInterval(g);
            g=null;
            if(l){
                k=typeof m==="number"?m:typeof k==="number"?k:100;
                g=setInterval(function(){
                    var o=document.location.hash,n=/^#?\d+&/;
                    if(o!==d&&n.test(o)){
                        d=o;
                        l({
                            data:o.replace(n,"")
                        })
                    }
                },k)
            }
        }
    }
})(jQuery);