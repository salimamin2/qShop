(function(a){a.fn.extend({accordion:function(b){var e={accordion:"true",speed:300,closedSign:"[+]",openedSign:"[-]"};var c=a.extend(e,b);var d=a(this);d.find("li").each(function(){if(a(this).find("ul").size()!=0){a(this).find("a:first").append("<span>"+c.closedSign+"</span>");if(a(this).find("a:first").attr("href")=="#"){a(this).find("a:first").click(function(){return false})}}});d.find("li.active").each(function(){a(this).parents("ul").slideDown(c.speed);a(this).parents("ul").parent("li").find("span:first").html(c.openedSign)});d.find("li a").click(function(){if(a(this).parent().find("ul").size()!=0){if(c.accordion){if(!a(this).parent().find("ul").is(":visible")){parents=a(this).parent().parents("ul");visible=d.find("ul:visible");visible.each(function(f){var g=true;parents.each(function(h){if(parents[h]==visible[f]){g=false;return false}});if(g){if(a(this).parent().find("ul")!=visible[f]){a(visible[f]).slideUp(c.speed,function(){a(this).parent("li").find("span:first").html(c.closedSign)})}}})}}if(a(this).parent().find("ul:first").is(":visible")){a(this).parent().find("ul:first").slideUp(c.speed,function(){a(this).parent("li").find("span:first").delay(c.speed).html(c.closedSign)})}else{a(this).parent().find("ul:first").slideDown(c.speed,function(){a(this).parent("li").find("span:first").delay(c.speed).html(c.openedSign)})}}})}})})(jQuery);