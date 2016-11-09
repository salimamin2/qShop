<?php echo $header ?>
<div class="box">
    <div class="head well">
    <h3>
            <i class="icon-th-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
                <a onclick="formValidation()" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-sm btn-default"><span><?php echo $button_cancel; ?></span></a>
			</div>
        </h3>
    </div>
        <?php if ($error_warning) : ?>
            <div class="warning"><?php echo $error_warning; ?></div>
        <?php endif; ?>

    <div class="content">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

            <table class="form">

                <tr>

                    <td> <?php echo $entry_name; ?><span class="required">*</span></td>

                    <td><input type="text" class="form-control" name="name" value="<?php echo $name; ?>" />

                        <?php if ($error_name) : ?>

                        <span class="error"><?php echo join(' , ', $error_name); ?></span>

                        <?php  endif; ?></td>

                </tr>



                <tr>

                    <td colspan='2' style="height:600px">

                        <table align="left" width="100%">

                            <tr>

                                <td><?php echo $entry_access; ?></td>

                                <td><?php echo $entry_modify; ?></td>

                            </tr>

                            <tr>

                                <td>

                                    <div class="scrollbox" style="height:600px">

                                        <?php $class = 'odd'; ?>

                                        <?php foreach ($permissions as $permission) : ?>

                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>

                                        <div class="<?php echo $class; ?>">

                                            <input type="checkbox" name="permission[access][]" class="check_permission" value="<?php echo $permission; ?>" <?php if (in_array($permission, $access)) : ?> checked="checked" <?php endif;?> />

                                                   <?php echo $permission; ?>

                                        </div>

                                        <?php endforeach; ?>

                                    </div>

                                </td>

                                <td>

                                    <div class="scrollbox" style="height:600px">

                                        <?php $class = 'odd'; ?>

                                        <?php foreach ($permissions as $permission) : ?>

                                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>

                                        <div class="<?php echo $class; ?>">

                                            <input type="checkbox" name="permission[modify][]" class="check_modify" value="<?php echo $permission; ?>" <?php if (in_array($permission, $modify)) : ?>checked="checked" <?php endif;?> />

                                                   <?php echo $permission; ?>

                                        </div>

                                        <?php endforeach; ?>

                                    </div>

                                </td>

                            </tr>

                        </table>

                    </td>

                </tr>

            </table>

        </form>

    </div>

</div>

<script type="text/javascript">

    function formValidation(){

        var modify = false;

        var permission = false;

        $('.check_modify').each(function(){

            if($(this).is(':checked')){

                modify=true;

                return false;

            }

        });

        $('.check_permission').each(function(){

            if($(this).is(':checked')){

                permission=true;

                return false;

           }

        });

        if(modify && permission)

            $('#form').submit();

        else

            alert('select any one from access and modify permission');

    }

</script>

<?php echo $footer ?>