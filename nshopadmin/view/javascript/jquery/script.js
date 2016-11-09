$(document).ready(function() {
	if(jQuery('#filter_category').length > 0){
		$('.loader').remove();
		var aCategories = [];
		jQuery('#filter_category').typeahead({
			name: 'categories',
	        source: function(d, p){
	        	var that = this;
	        	if(aCategories.length > 0){
	        		p(aCategories);
	        		return aCategories;
	        	} 
	        	jQuery.ajax({
	        		url: sCategoryAction,
	        		data: 'keyword='+d,
	        		dataType: "json",
	        		beforeSend: function(){
	        			that.$element.after("<div class='loader'></div>");
	        		},
	        		complete: function(){
	        			$('.loader').remove();
	        		},
	        		success: function(res){
	        			if(res.length > 0){
	        				aCategories = res;
	        				/*var aResult = [];
	        				$.each(res,function(i,val){
	        					aResult.push(val);
	        				})*/
		        			p(res);
	        			}
	        		}
	        	})
	        }
		});
		jQuery('#filter_category').on('change',function(){
			var id = $(this).attr('data-id');
			jQuery('#filter_hdn_category').val(id);
		});
	}
	var aProducts = [];
	jQuery('#return_products').typeahead({
		name: 'categories',
        source: function(d, p){
        	var that = this;
        	if(aProducts.length > 0){
        		p(aProducts);
        		return aProducts;
        	} 
        	jQuery.ajax({
        		url: sProductAction,
        		data: 'keyword='+d,
        		dataType: "json",
        		beforeSend: function(){
        			that.$element.after("<div class='loader'></div>");
        		},
        		complete: function(){
        			$('.loader').remove();
        		},
        		success: function(res){
        			if(res.length > 0){
        				aProducts = res;
	        			p(res);
        			}
        		}
        	})
        }
	});
	jQuery('#return_products').on('change',function(){
		var model = $(this).attr('data-model');
		jQuery('#return-model').val(model);
	});

		
    /* Set Canvas Width & Height */
    function setCanvasWidth() {
	// Statistics - Line Graph
	var salesCustomerChartWidth = $(".statistic .dashboard-content #report").width();
	$("#sales-customer-graph").css("width", salesCustomerChartWidth + "px");
	// Overview - Pie Chart
	var salesValueChartWidth = $(".overview .sales-value-graph").width();
	var salesValueChartHeight = $(".overview .dashboard-overview-top").height();
	if (salesValueChartWidth > 0) {
	    if (salesValueChartWidth >= salesValueChartHeight) {
		// set canvas height and width of canvas to salesValueChartHeight
		$("#sales-value-graph").css("height", salesValueChartHeight - 10 + "px").css("width", salesValueChartHeight - 10 + "px");
	    } else {
		// set canvas height and width of canvas to salesValueChartWidth
		$("#sales-value-graph").css("height", salesValueChartWidth - 10 + "px").css("width", salesValueChartWidth - 10 + "px");
	    }
	}
    }
    var waitForFinalEvent = (function() {
	var timers = {};
	return function(callback, ms, uniqueId) {
	    if (!uniqueId) {
		uniqueId = "Don't call this twice without a uniqueId";
	    }
	    if (timers[uniqueId]) {
		clearTimeout(timers[uniqueId]);
	    }
	    timers[uniqueId] = setTimeout(callback, ms);
	};
    })();
    function generateUniqueString() {
	var text = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	for (var i = 0; i < 5; i++)
	    text += possible.charAt(Math.floor(Math.random() * possible.length));
	return text;
    }
    /* Set Canvas Width & Height - Continued */
    var graphExists = $("#sales-customer-graph").height();
    if (graphExists > 0) {
	setCanvasWidth();
    }
    /* Plot Sales/Customer Graph */
    // Get Graph Data
    (function(fn) {
	if (!fn.map)
	    fn.map = function(f) {
		var r = [];
		for (var i = 0; i < this.length; i++)
		    r.push(f(this[i]));
		return r
	    }
	if (!fn.filter)
	    fn.filter = function(f) {
		var r = [];
		for (var i = 0; i < this.length; i++)
		    if (f(this[i]))
			r.push(this[i]);
		return r
	    }
    })(Array.prototype);
    function getSalesChart(range) {
	$.ajax({
	    type: 'GET',
	    url: 'common/home/chart&range=' + range,
	    dataType: 'json',
	    async: false,
	    success: function(json) {
		var option = {
		    series: {
			lines: {
			    show: true,
			    fill: true,
			    lineWidth: 1
			},
			points: {
			    show: true
			}
		    },
		    shadowSize: 0,
		    grid: {
			backgroundColor: '#FFFFFF',
			borderColor: '#F2F2F2',
			hoverable: true
		    },
		    legend: {
			show: false
		    },
		    xaxis: {
			ticks: json.xaxis
		    },
		    tooltip: true,
		    tooltipOpts: {
			content: "'%s': <b>%y</b>",
			shifts: {
			    x: -60,
			    y: 25
			}
		    }
		}
		option.colors = ['#939BCB', '#82D14D'];
		$.plot($('#sales-customer-graph'), [json.order, json.customer], option);
	    }
	});
    }
    if (graphExists > 0) {
	getSalesChart($('#range').val());
    }
    // Get New Data Range
    $("#range").change(function() {
	var graphRange = $('#range').val();
	getSalesChart(graphRange);
    });
    /* Plot Sales/Amount/Value Graph (Pie Chart) */
    function getSalesValueChart() {
	var salesTotal = parseFloat($("#total_sale_raw").val());
	var salesThisYear = parseFloat($("#total_sale_year_raw").val());
	var salesThisYearText = $("#total_sale_year_raw").data("text_label");
	var salesThisYearCurrency = $("#total_sale_year_raw").data("currency_value");
	var salesPreviousYears = parseFloat($("#total_sales_previous_years_raw").val());
	var salesPreviousYearsText = $("#total_sales_previous_years_raw").data("text_label");
	var salesPreviousYearsCurrency = $("#total_sales_previous_years_raw").data("currency_value");
	$("#title").text("Default pie chart");
	$("#description").text("The default pie chart with no options set.");
	var placeholder = $("#sales-value-graph");
	var data = [
	    {label: salesThisYearText + " <b>" + salesThisYearCurrency + "</b>", data: salesThisYear, color: '#69D2E7'},
	    {label: salesPreviousYearsText + " <b>" + salesPreviousYearsCurrency + "</b>", data: salesPreviousYears, color: '#F38630'}
	];
	$.plot(placeholder, data, {
	    series: {
		pie: {
		    show: true
		}
	    },
	    legend: {
		show: true,
		container: '#hiddenLegend'
	    },
	    grid: {
		hoverable: true,
		clickable: true
	    },
	    tooltip: true,
	    tooltipOpts: {
		content: "%s",
		shifts: {
		    x: -60,
		    y: 25
		}
	    }
	});
    }
    if (graphExists > 0) {
	getSalesValueChart();
    }
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
    if (!routeAct) {
	$('#dashboard').addClass('selected');
    } else {
	part = routeAct.split('/');
	url = part[0];
	if (part[1]) {
	    url += '/' + part[1];
	}
	$('a[href*=\'' + url + '\']').parents('li[id]').addClass('active');
    }
    $('.keyword').slugify('.name');
    if($('.fileinput-button').length > 0){
        $('.fileinput-button').each(function(){
            if($(this).parents('.buttons,.fileupload-buttonbar,td.thumb,td.image-opt').length > 0){
                return false;
            }
            var html = '<div class="filelink-button">'+
                '<button type="button" class="btn btn-info btn-sm link-btn-show" data-toggle="tooltip" title="Add Image by Link"><i class="icon-picture"></i></button>'+
                '<div class="hide input-group"><input type="text" class="form-control link-btn-input input-sm" />'+
                '<a data-toggle="tooltip" title="Enter full path of image url that are already uploaded on server. To get link goto File Manager." class="feedback-icon glyphicon icon-question form-control-feedback" aria-hidden="true"></a>'+
                '<span class="input-group-btn"><button type="button" class="link-btn-add btn btn-info btn-sm">Add</button><button type="button" class="btn btn-sm btn-danger link-btn-remove"><i class="icon-remove"></i></button></span>'+
                '</div>'+
                '</div>';

            $(this).after(html);
        })
    }
});
$(document).on('click','.link-btn-show',function(){
    var parent = $(this).parents('.filelink-button');
    $('.input-group',parent).removeClass('hide');
    $(this).hide();
});
$(document).on('click','.link-btn-remove',function(){
    var parent = $(this).parents('.filelink-button');
    $('.input-group',parent).addClass('hide');
    $('.link-btn-show',parent).show();
});

$(document).on('click','.link-btn-add',function(){
    var parent = $(this).parents('.filelink-button');
    var column = parent.parent();
    var link = $.trim($('.form-control',parent).val());
    if(link == ''){
        alert('Link field is required');
        return false;
    }
    if(link.indexOf(imageBaseUrl) === false){
        alert('Invalid image link');
        return false;
    }
    var image = link.replace(imageBaseUrl,'');
    $('img',column).attr('src',link);
    $('#image,#thumb',column).val(image);
    $('.input-group',parent).addClass('hide');
    $('.link-btn-show',parent).show();
});
$("#filter-list").on("click", function() {
    if ($(".dataTable").attr("data-rel") == "ajax-grid") {
		oGridData = oAjaxDataGrid;
    } else {
		oGridData = oDataGrid;
    }
    
    $(".content .filter input,.content .filter select").each(function() {
		var idx = $(this).attr('data-rel');
    	if($(this).parents('.dataTable').length > 0){
			idx = $(this).parent().index();
		}
		if(this.value != '' && typeof idx != 'undefined'){
			oGridData.api()
				.column(idx)
				.search(this.value);
		}
	});
	oGridData.api().draw(false);
    return false;
});
$("#reset-filter").on("click", function() {
    if ($(".dataTable").attr("data-rel")  == "ajax-grid") {
		oGridData = oAjaxDataGrid;
    } else {
		oGridData = oDataGrid;
    }
    $(".content .filter select").each(function() {
		$(this).find(":first").attr("selected", true);
		var idx = $(this).attr('data-rel');
		if($(this).parents('.dataTable').length > 0){
			idx = $(this).parent().index();
		}
		oGridData.api()
			.column(idx)
			.search("");
    });
    $(".content .filter input").each(function() {
		$(this).val("");
		var idx = $(this).attr('data-rel');
		if($(this).parents('.dataTable').length > 0){
			idx = $(this).parent().index();
		}
		oGridData.api()
			.column(idx)
			.search("");
    });
    oGridData.api().draw();
    return false;
});