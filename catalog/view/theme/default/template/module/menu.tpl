<?php if ($position == MENU_POSITION_MAIN): ?>
    <div id="mobnav" class="grid-full" style="display: none;">
        <a id="mobnav-trigger" href="">
    	<span class="trigger-icon"><span class="line"></span><span class="line"></span><span class="line"></span></span>
    	<span>Menu</span>
        </a>
    </div>
    <?php foreach ($aMenus as $sMenu): ?>
	<ul class="accordion vertnav vertnav-top grid-full">
	    <?php

	    echo $this->helper_menu->getFullMenu($sMenu, array(
		'li_class' => 'level-top',
		'a_class' => 'level-top',
		'child_wrapper' => '<span class="opener">&nbsp;</span><ul class="{class}">{data}</ul>',
		'name_wrapper' => '<span>{data}</span>'
	    ));

	    ?>
	</ul>
	<ul id="nav" class="grid-full wide">
	    <?php

	    echo $this->helper_menu->getFullMenu($sMenu, array(
		'li_class' => 'level-top',
        'no_static_class' => 'one-column',
		'a_class' => 'level-top',
		'a_child_class' => 'sm',
		'li_child_class' => 'item',
		'parent_caret' => '<span class="caret">&nbsp;</span>',
		'child_wrapper' => '<ul class="{class}">{data}</ul>',
        'static_block_wrapper' => '<div class="level0-wrapper dropdown-6col"><div class="level0-wrapper2">{data}</div></div>',
		'name_wrapper' => '<span>{data}</span>',
        'controller' => $this
	    ));

	    ?>
	</ul>
    <?php endforeach; ?>
    <script type="text/javascript">
        //<![CDATA[

        var activateMobileMenu = function()
        {
    	if (jQuery(window).width() < 960)
    	{
    	    jQuery('#mobnav').show();
    	    jQuery('.vertnav-top').addClass('mobile');
    	    jQuery('#nav').addClass('mobile');
    	}
    	else
    	{
    	    jQuery('#nav').removeClass('mobile');
    	    jQuery('.vertnav-top').removeClass('mobile');
    	    jQuery('#mobnav').hide();
    	}
        }
        activateMobileMenu();
        jQuery(window).resize(activateMobileMenu);


        jQuery('#mobnav-trigger').toggle(function() {
    	jQuery('.vertnav-top').addClass('show');
    	jQuery(this).addClass('active');
        }, function() {
    	jQuery('.vertnav-top').removeClass('show');
    	jQuery(this).removeClass('active');
        });

        //]]>
    </script>

    <script type="text/javascript">
        //<![CDATA[

        jQuery(function($) {
    	$("#nav > li").hover(function() {
    	    var el = $(this).find(".level0-wrapper");
    	    el.hide();
    	    el.css("left", "0");
    	    el.stop(true, true).delay(150).fadeIn(300, "easeOutCubic");
    	}, function() {
    	    $(this).find(".level0-wrapper").stop(true, true).delay(300).fadeOut(300, "easeInCubic");
    	});
        });

        var isTouchDevice = ('ontouchstart' in window) || (navigator.msMaxTouchPoints > 0);
        jQuery(window).on("load", function() {

    	if (isTouchDevice)
    	{
    	    jQuery('#nav a.level-top').click(function(e) {
    		$t = jQuery(this);
    		$parent = $t.parent();
    		if ($parent.hasClass('parent'))
    		{
    		    if (!$t.hasClass('menu-ready'))
    		    {
    			jQuery('#nav a.level-top').removeClass('menu-ready');
    			$t.addClass('menu-ready');
    			return false;
    		    }
    		    else
    		    {
    			$t.removeClass('menu-ready');
    		    }
    		}
    	    });
    	}

        }); //end: on load

        //]]>
    </script>
<?php else: ?>
    <?php

    foreach ($aMenus as $i => $sMenu):
	if (stristr($menu_wrapper[0], 'heading') !== false) {
	    echo str_replace('{heading}', $headers[$i], $menu_wrapper[0]) . "\n";
	} else {
	    echo $menu_wrapper[0] . "\n";
	}
	echo $this->helper_menu->getFullMenu($sMenu, array());
	echo $menu_wrapper[1] . "\n";
    endforeach;

    ?>
<?php endif; ?>