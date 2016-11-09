<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box">
    <div class="head well">
        <h3><i class="icon-user"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
                <button class="btn-flat btn-success btn-sm" type="submit"><?php echo $button_save; ?></button>

                <a href="<?php echo $cancel; ?>" class="btn-flat btn-sm btn-default"><?php echo $button_cancel; ?></a>
			</div>
	    </h3>
    </div>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="content">

            <table class="form" >

                <tr>

                    <td><?php echo CHtml::activeLabelEx($model, 'username'); ?></td>

                    <td>

			<?php echo CHtml::activeTextField($model, 'username',array('class' => 'form-control')); ?>

			<?php echo $model->getValidator()->showError('username'); ?>

                    </td>

                </tr>

                <tr>

                    <td><?php echo CHtml::activeLabelEx($model, 'firstname'); ?></td>

                    <td>

			<?php echo CHtml::activeTextField($model, 'firstname',array('class' => 'form-control')) ?>

			<?php echo $model->getValidator()->showError('firstname'); ?>

                    </td>

                </tr>

                <tr>

                    <td><?php echo CHtml::activeLabelEx($model, 'lastname'); ?></td>

                    <td>

			<?php echo CHtml::activeTextField($model, 'lastname',array('class' => 'form-control')) ?>

			<?php echo $model->getValidator()->showError('lastname'); ?>

                    </td>

                </tr>

                <tr>

                    <td><?php echo CHtml::activeLabelEx($model, 'email'); ?></td>

                    <td>

                        <?php echo CHtml::activeTextField($model, 'email',array('class' => 'form-control')) ?>

			            <?php echo $model->getValidator()->showError('email'); ?>

                    </td>

                </tr>

                <tr>

                    <td><?php echo CHtml::label(__('User Group'), 'user_group_id'); ?></td>

                    <td><div class="ui-select">

			<?php echo CHtml::activeDropDownList($model,'user_group_id',$user_groups) ?>

			<?php echo $model->getValidator()->showError('user_group_id'); ?>

                   </div> </td>

                </tr>

		<?php if ($model->getScenario() == 'update'): ?>

    		<tr id='changePass'>

    		    <td><?php echo CHtml::activeLabelEx($model, 'password'); ?></td>

    		    <td><a href="#" id="show_pass">Change Password</a> </td>

    		</tr>

    		<tr class="dvPass hide" >

    		    <td><?php echo CHtml::activeLabelEx($model, 'password'); ?></td>

    		    <td>

                    <?php echo CHtml::activePasswordField($model,'password',array('value' => '','class' => 'form-control')); ?>

			        <?php echo $model->getValidator()->showError('password'); ?>

                </td>

    		</tr>

    		<tr class="dvPass hide">

    		    <td><?php echo CHtml::label(__('Confirm Password ')."<span class='required'>*</span>", 'password_confirm'); ?></td>

    		    <td>

                    <?php echo CHtml::passwordField('password_confirm','',array('class' => 'form-control')); ?>

			    <?php echo $model->getValidator()->showError('password_confirm'); ?>

                </td>

    		</tr>

		<?php else: ?>

    		<tr >

    		    <td><?php echo CHtml::activeLabelEx($model, 'password'); ?></td>

    		    <td>

                    <?php echo CHtml::activePasswordField($model,'password',array('class' => 'form-control')); ?>

			        <?php echo $model->getValidator()->showError('password'); ?>

                </td>

    		</tr>

    		<tr >

    		    <td><?php echo CHtml::label(__('Confirm Password ')."<span class='required'>*</span>", 'password_confirm'); ?></td>

    		    <td>

                    <?php echo CHtml::passwordField('password_confirm','',array('class' => 'form-control')); ?>

			        <?php echo $model->getValidator()->showError('password_confirm'); ?>

                </td>

    		</tr>

		<?php endif; ?>

                <tr>

                    <td><?php echo CHtml::activeLabelEx($model, 'status');; ?></td>

                    <td>
<div class="ui-select">
                        <?php echo CHtml::activeDropDownList($model,'status',$status) ?>

                        <?php echo $model->getValidator()->showError('status'); ?>
</div>
                    </td>

                </tr>

            </table>

    </div>

</div>

</form>

<!-- <script type="text/javascript">

    $('input[type=text]').alphanumeric({

	allow: ".@_"

    });

</script> -->



<script>

    $(document).on('click','#show_pass',function(e) {

        $('.dvPass').removeClass('hide');

        $('#password').removeAttr('disabled');

        $('#changePass').hide();

        return false;

    });

</script>