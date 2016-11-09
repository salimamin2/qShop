<div class="filtes-main-contanier">
    <div class="top-headeing">
        <div class="col-md-12">
            <p>You can select multiple options simultaneously</p>
            <button type="button" class="close-filter">&times;</button>
        </div>
    </div>
    <div class="clearfix"></div>
    <form id="filter_form" action="<?php echo $filter_action ?>" method="get">
        <div class="block-content">
            <div class="row1">
                <div class="col-sm-2 col-md-2"></div>
                <?php if($aCategories): ?>
                    <div class="col-sm-2 col-md-2 border-filter">
                        <?php $first = (!stristr($filter_action,"?") ? true : false); ?>
                        <div class="categories_filter filter-main" id="cssmenu">
                            <dt class="odd">Categories</dt>
                            <ul id="" class="">
                                <?php foreach($aCategories as $id => $Cat): ?>
                                    <li <?php if($Cat['selected']): ?>class="active"<?php endif; ?>>
                                        <a href="<?php echo  $Cat['href']; ?>"><span>
                                            <?php echo $Cat['name']; ?></span>
                                            <?php if($Cat['child']): ?>
                                                <label class="right"><?php if($Cat['selected'] || $Cat['child_selected']): ?>-<?php else: ?>+<?php endif; ?></label>
                                            <?php endif; ?>
                                        </a>
                                        <?php if($Cat['child']): ?>
                                            <ul class="" <?php if($Cat['selected'] || $Cat['child_selected']): ?>style="display:block;"<?php endif; ?>>
                                                <?php foreach($Cat['child'] as $cId => $cCat): ?>
                                                    <li <?php if($cCat['selected']): ?>class="active"<?php endif; ?>>
                                                        <a href="<?php echo $cCat['href']; ?>"><span>
                                                            <?php echo $cCat['name']; ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($filters) && $products): $count = count($filters); ?>
                    <?php foreach ($filters as $i => $filter): ?>
                        <?php if (!empty($filter['values'])): ?>
                            <div class="col-sm-2 col-md-2">
                                <div class="filter-main">
                                    <dt class="odd"><?php echo $filter['name']; ?></dt>
                                    <dd class="odd">
                                        <ol>
                                            <?php foreach ($filter['values'] as $k => $v): ?>
                                                <li>
                                                <?php //d(array($filter,$i,$k,$v,$org_post_filters)); ?>
                                                    <?php if(isset($v['childs'])): ?>
                                                        <h5 rel="<?php echo $k; ?>"><?php echo $v['name']; ?></h5>
                                                        <div id="<?php echo $k; ?>">
                                                            <ul>
                                                                
                                                                <?php foreach($v['childs'] as $id => $child): ?>
                                                                    <?php $bChecked = (isset($org_post_filters[$filter['field']]) && in_array(strtolower($id),$org_post_filters[$filter['field']])) ? true : false; ?>
                                                                       <li class="skin-minimal">
                                                                        <button type="button" value="filter[<?php echo $filter['field'] ?>][]=<?php echo $id; ?>" class="filter-check <?php echo ($bChecked ? 'selected' : ''); ?>"><span>
                                                                            <?php echo $child['name']; ?></span>
                                                                        </button>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    <?php else: ?>
                                                        <?php $bChecked = isset($org_post_filters[$filter['field']]) && in_array(strtolower($k),$org_post_filters[$filter['field']]) ? true : false; ?>
                                                          <div class="skin-minimal">
                                                            <button type="button" value="filter[<?php echo $filter['field'] ?>][]=<?php echo $k; ?>" class="filter-check <?php echo ($bChecked ? 'selected' : ''); ?>"><span>
                                                                <?php echo $v['name'] ?></span>
                                                            </button>
                                                            
                                                        </div>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ol>
                                    </dd>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="clearfix"></div>
            <div class="text-align: right;">
                <div class="button_filters">
                    <button type="button" class="btn clear">Clear All</button>
                    <button type="button" class="btn apply-filter">Apply</button>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </form>
    <div class="clearfix"></div>
</div>

<script type="text/javascript">
    var url = "<?php echo $filter_action ?>";
    var post_filters = JSON.parse('<?php echo json_encode($post_filters); ?>');
    jQuery(document).on('click','.clear',function() {
        // jQuery('#filter_form input[type="checkbox"]').attr('checked',false);
        jQuery('#filter_form button.filter-check.selected').removeClass('selected');
        clearFilters();
    });

    function clearFilters() {
        jQuery.each(post_filters,function(i,arr) {
            jQuery.each(arr,function(j,val) {
                var sUrl = '&filter[' + i + '][' + j +']=' + val;
                url = url.replace(sUrl,'');
            });
        });
    }

    jQuery(document).on('click','button.filter-check',function(e) {
        // var parent = jQuery(this).parents('ol');
        // jQuery('button.selected',parent).removeClass('selected');
        jQuery(this).toggleClass('selected');
    });

    jQuery(document).on('click','.apply-filter',function(e) {
        // jQuery('#filter_form input[type="checkbox"]:checked').each(function(i,obj) {
        clearFilters();
        if(url.indexOf('?') == -1) {
            url += '?';
        }
        jQuery('#filter_form button.filter-check.selected').each(function(i,obj) {
            obj = jQuery(obj);
            if(url.indexOf(obj.val()) == -1) {
                url += (url.slice(-1) != '?' ? '&' : '') + obj.val();
            }
        });
        location.href = decodeURIComponent(url);
    });

    jQuery('.filter-remove').on('click', function() {
        url = url.replace(/\s/g,"%20");
        var url_parse = url.split('?');
        url_parse.splice(1,1);
        url = url_parse[0];
        var query = window.location.search;
        var array = query.split("&");
        var array2 = array.slice(0);
        var rel = jQuery(this).attr('rel').replace(/\s/g,"%20");
        rel = rel.replace('[]','');
        aRel = rel.split('=');
        jQuery(array2).each(function(i,val) {
            val = val.replace('?','');
            if(val.indexOf("page=") != -1) {
               array.splice(i,1);
            }
            aVal = val.split('=');
            if(aVal[0].indexOf(aRel[0]) != -1 && aVal[1] == aRel[1]){
               array.splice(i,1);
            }
        });
        query = array.join('&');
        /*
	    var toRemove = '&' + rel;
	    var toRemove1 = '?' + rel;
        var toRemove2 = toRemove.replace('[]', '[0]');
        var toRemove3 = toRemove1.replace('[]', '[0]');
        query = query.replace(toRemove,'').replace(toRemove1,'').replace(toRemove2,'').replace(toRemove3,'');*/
        if(query != "") {
            url = url + (query.charAt(0) == '&' ? query.replace('&','?') : (query.charAt(0) == '?' ? query : '?'+query));
        }
	   location.href = url;
    });

    jQuery(document).on('click','.close-filter',function() {
        jQuery('.filters-div').fadeOut();
    }); 

jQuery(function() {
    var Accordion = function(el, multiple) {
        this.el = el || {};
        this.multiple = multiple || false;

        // Variables privadas
        var links = this.el.find('.link');
        // Evento
        links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
    }

    Accordion.prototype.dropdown = function(e) {
        var jQueryel = e.data.el;
            jQuerythis = jQuery(this),
            jQuerynext = jQuerythis.next();

        jQuerynext.slideToggle();
        jQuerythis.parent().toggleClass('open');

        if (!e.data.multiple) {
            jQueryel.find('.submenu').not(jQuerynext).slideUp().parent().removeClass('open');
        };
    }   

    var accordion = new Accordion(jQuery('#accordion'), false);

    jQuery('#cssmenu ul li:has(ul)').addClass("has-sub");
    jQuery('#cssmenu ul li.has-sub > a').click(function(e) {
        e.preventDefault();
        var checkElement = jQuery(this).next();
        if(checkElement.is(':visible')){
            location.href = jQuery(this).attr('href');
            return false;
        }
        jQuery('#cssmenu ul li.has-sub > a label').text('+');
        if(!checkElement.is(':visible')){
            jQuery('label',this).text('-');
        }
        //jQuery('#cssmenu li').removeClass('active');
        //jQuery(this).parents('li').addClass('active');
        if((checkElement.is('ul')) && (checkElement.is(':visible'))) {            
            jQuery(this).parents('li').removeClass('active');
            checkElement.slideUp('normal');
        }
        if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
            jQuery('#cssmenu ul ul:visible').slideUp('normal');
            checkElement.slideDown('normal');
        }
        if (checkElement.is('ul')) {
            return false;
        } else {
            return true;
        }
    });
});
</script>