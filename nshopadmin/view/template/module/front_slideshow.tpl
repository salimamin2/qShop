<form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data" id="fileupload">

<div class="box">
	<div class="head well">
		<h3>
			<i class="icon-picture"></i> <?php echo $heading_title; ?>
	    	<div class="pull-right">
		    	<button class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></button>
		    	 <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
			</div>
		</h3>
  </div>
	<?php if ($error_warning) { ?>
  		<div class="alert alert-danger"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
    	<div class="alert alert-success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="alert alert-success hide"></div>	
	<div class="content">
	    <table class="form">
		<tr>
		    <td><?php echo $entry_status; ?></td>
		    <td><div class="ui-select"><select name="frontss_status">

			    <?php if ($frontss_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		    <td><?php echo $entry_position; ?></td>

		    <td><div class="ui-select"><select name="frontss_position">

			    <?php foreach ($positions as $position) { ?>

				<?php if ($frontss_position == $position['position']) { ?>

				    <option value="<?php echo $position['position']; ?>" selected="selected"><?php echo $position['title']; ?></option>

				<?php } else { ?>

				    <option value="<?php echo $position['position']; ?>"><?php echo $position['title']; ?></option>

				<?php } ?>

			    <?php } ?>

			</select></div></td>

		</tr>

		<tr>

		    <td colspan="4">

			<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->

			<div class="row fileupload-buttonbar">

			    <div class="col-lg-7">

				<!-- The fileinput-button span is used to style the file input field as button -->

				<span class="btn btn-success fileinput-button">

				    <i class="icon-plus"></i>

				    <span>Add files...</span>

				    <input type="file" name="files[]" multiple>

				</span>

				<button type="reset" class="btn btn-warning cancel">

				    <i class="icon-remove-circle"></i>

				    <span>Cancel upload</span>

				</button>

				<button type="button" class="btn btn-danger delete">

				    <i class="icon-trash"></i>

				    <span>Delete</span>

				</button>

				<input type="checkbox" class="toggle">

				<!-- The global file processing state -->

				<span class="fileupload-process"></span>

			    </div>

			    <!-- The global progress state -->

			    <div class="col-lg-5 fileupload-progress fade">

				<!-- The global progress bar -->

				<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">

				    <div class="progress-bar progress-bar-success" style="width:0%;"></div>

				</div>

				<!-- The extended global progress state -->

				<div class="progress-extended">&nbsp;</div>

			    </div>

			</div>

			<!-- The table listing the files available for upload/download -->

			<table role="presentation" class="table table-bordered table-striped image-table">

			    <thead>

				<tr>

				    <td width="15%"><?php echo __('Image'); ?></td>

				    <td width="45%"><?php echo __('Title'); ?> <span class="required">*</span></td>

				    <td width="20%"><?php echo __('Link'); ?> <span class="required">*</span></td>

				    <td width="20%"><?php echo __('Action'); ?></td>

				</tr>

			    </thead>

			    <tbody class="files"></tbody>

			</table>

		    </td>

		</tr>

	    </table>

	</div>

    </div>

</form>

<!-- The template to display files available for upload -->

<script id="template-upload" type="text/x-tmpl">

    {% row = (parseInt($('.image-table tbody tr').length) + 1); %}

    {% for (var i=0, file; file=o.files[i]; i++) { %}

    <tr id="row-{%=row%}" class="template-upload fade">

    <td>

    <input type="hidden" class="hdn" name="row" value="" />

    <span class="preview"></span>

    <p class="name">{%=file.name%}</p>

    <strong class="error text-danger"></strong>

    </td>

    <td>

    <p class="name"><textarea style="width:450px;height:100px;" name="frontss_data[{%=row%}][title]" id="frontss_title_{%=row%}" ></textarea></p>

    </td>

    <td>

    <p class="name"><input type="text" name="frontss_data[{%=row%}][link]" /></p>

    </td>

    <td>

    <p class="size">Processing...</p>

    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>

    {% if (!i && !o.options.autoUpload) { %}

    <button class="btn btn-primary btn-sm start" data-rel="{%=row%}" disabled>

    <i class="icon-upload"></i>

    <span>Start</span>

    </button>

    {% } %}

    {% if (!i) { %}

    <button class="btn btn-warning btn-sm cancel">

    <i class="icon-remove-circle"></i>

    <span>Cancel</span>

    </button>

    {% } %}

    </td>

    </tr>

    {% } %}

    {%  %}

</script>

<!-- The template to display files available for download -->

<script id="template-download" type="text/x-tmpl">

    {% if(o.files.length > 0){ %}

    {% for (var i=0, file; file=o.files[i]; i++) { %}

    <tr class="template-download fade">

    <td>

    <span class="preview">

    {% if (file.thumbnailUrl) { %}

    <img src="{%=file.thumbnailUrl%}"  width="100" height="100" />

    {% } %}

    </span>

    {% if (file.error) { %}

    <div><span class="label label-danger">Error</span> {%=file.error%}</div>

    {% } %}

    </td>

    <td>

    <p class="name"><textarea style="width:450px;height:100px;" id="frontss_title_{%=file.row%}" data-rel="wyswyg">

        {% if (file.title) { %}

        {%=file.title%}

        {% } %}

    </textarea>

    </p>    

    </td>

    <td>

    <p class="name"><input type="text" value="{%=file.link%}" id="frontss_link_{%=file.row%}"  /></p>

    </td>

    <td>

    {% if (file.deleteUrl) { %}

    <button type="button" class="btn btn-primary btn-sm edit_frontss" rel="{%=file.row%}"><i class="icon-edit"></i> <span>Edit</span></button>

    <button class="btn btn-danger btn-sm delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>

    <i class="icon-trash"></i>

    <span>Delete</span>

    </button>

    <input type="checkbox" name="delete" value="1" class="toggle">



    {% } else { %}

    <button class="btn btn-warning btn-sm cancel">

    <i class="icon-remove-circle"></i>

    <span>Cancel</span>

    </button>

    {% } %}

    </td>

    </tr>



    {%

     } %}



    {% } %}



</script>

<script type="text/javascript"><!--

    var flag = false;

    $(function() {

	'use strict';





	// Initialize the jQuery File Upload widget:

	$('#fileupload').fileupload({

	    // Uncomment the following to send cross-domain cookies:

	    //xhrFields: {withCredentials: true},

	    url: '<?php echo makeUrl('module/front_slideshow/insert'); ?>',

	    added: function(e, data) {

		$(".template-upload .start").on("click", function() {

		    var row = $(this).attr('data-rel');

		    $("#row-" + row + " .hdn").val(row);

		});

		initWysiwyg("#frontss_title_"+row);

	    },

	    completed: function(e,data) {

            if(flag) {

                initWysiwyg(".table tbody tr.template-download:last textarea");

            }

            flag = true;

	    }

	});



	// Enable iframe cross-domain access via redirect option:

	$('#fileupload').fileupload(

		'option',

		'redirect',

		window.location.href.replace(

			/\/[^\/]*$/,

			'/cors/result.html?%s'

			)

		);



	    // Load existing files:

	    $('#fileupload').addClass('fileupload-processing');

	    $.ajax({

		// Uncomment the following to send cross-domain cookies:

		//xhrFields: {withCredentials: true},

		url: $('#fileupload').fileupload('option', 'url'),

		dataType: 'json',

		context: $('#fileupload')[0]

	    }).always(function() {

		$(this).removeClass('fileupload-processing');

	    }).done(function(result, e) {

		$(this)

			.removeClass('fileupload-processing')

			.fileupload('option', 'done')

			.call(this, $.Event(this), {result: result});

//		$(this).fileupload('option', 'done')

//		.call(this, null, {result: result});

        initWysiwyg();

	    });



    });



//--></script>

