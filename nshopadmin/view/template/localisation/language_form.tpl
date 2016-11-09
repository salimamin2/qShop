<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box">
	<div class="head well">
	   	<h3><i class="icon-flag"></i><?php echo $heading_title; ?>
			<div class="pull-right">
				<button class="btn-flat btn-success btn-sm"><span><?php echo __('button_save'); ?></span></button>

				<a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo __('button_cancel'); ?></span></a>
			</div>
		</h3>	
	</div>
		<?php if ($error_warning) { ?>
		    <div class="alert alert-danger"><?php echo $error_warning; ?></div>
		<?php } ?>
	<div class="content">

	    <table class="form">

		<tr>

		    <td><?php echo CHtml::activeLabelEx($model, 'name') ?></td>

		    <td><?php echo CHtml::activeTextField($model, 'name',array('class' => 'form-control')) ?></td>

		</tr>

		<tr>

		    <td><?php echo CHtml::activeLabelEx($model, 'code') ?></td>

		    <td><?php echo CHtml::activeTextField($model, 'code',array('class' => 'form-control')) ?></td>

		</tr>

		<tr>

		    <td><?php echo CHtml::activeLabelEx($model, 'locale') ?></td>

		    <td><?php echo CHtml::activeTextField($model, 'locale',array('class' => 'form-control')) ?></td>

		</tr>

		<tr>

		    <td><?php echo CHtml::activeLabelEx($model, 'image') ?></td>

		    <td><?php echo CHtml::activeTextField($model, 'image',array('class' => 'form-control')) ?></td>

		</tr>

		<tr>

		    <td><?php echo CHtml::activeLabelEx($model, 'directory') ?></td>

		    <td><?php echo CHtml::activeTextField($model, 'directory',array('class' => 'form-control')) ?></td>

		</tr>

		<tr>

		    <td><?php echo CHtml::activeLabelEx($model, 'filename') ?></td>

		    <td><?php echo CHtml::activeTextField($model, 'filename',array('class' => 'form-control')) ?></td>

		</tr>

		<tr>

		    <td><?php echo CHtml::activeLabelEx($model, 'status') ?></td>

		    <td><div class="ui-select"><?php echo CHtml::activeDropDownList($model, 'status', $aStatus) ?></div></td>

		</tr>

		<tr>

		    <td><?php echo CHtml::activeLabelEx($model, 'sort_order') ?></td>

		    <td><?php echo CHtml::activeTextField($model, 'sort_order',array('size' => '3')) ?></td>

		</tr>

	    </table>

	</div>

    </div>

</form>