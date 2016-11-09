<div class="box">
    <div class="head well">
        <h3><i class="icon-edit"></i> <?php echo __('Edit Product Return'); ?></h3>
    </div>
    <?php if ($error_warning) : ?>
    <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php endif; ?>
    <?php if ($success) : ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <div class="content col-sm-12">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_general" data-toggle="tab">
                    <span><?php echo __('General') ?></span>
                </a>
            </li>
            <li>
                <a href="#tab_history" data-toggle="tab">
                    <span><?php echo __('History') ?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tab_general" class="tab-pane active">
                <fieldset>
                    <div class="col-sm-12">
                        <br/>
                        <legend>Order Information</legend>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label" for="input-order-id"><?php echo __('Order ID'); ?></label>
                        <div class="col-sm-10">
                            <?php echo CHtml::activeTextField($model, 'order_id',array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label"
                               for="input-date-ordered"><?php echo __('Order Date'); ?></label>
                        <div class="col-sm-3">
                            <div class="input-group date">
                                <?php echo CHtml::activeTextField($model, 'date_ordered',array('class' =>
                                'form-control', 'data-format' => 'YYYY-MM-DD', 'data-provide' => 'datepicker-inline'));
                                ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label" for="input-customer"><?php echo __('First Name'); ?></label>
                        <div class="col-sm-10">
                            <?php echo CHtml::activeTextField($model, 'firstname',array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label"
                               for="input-firstname"><?php echo __('Last Name'); ?></label>
                        <div class="col-sm-10">
                            <?php echo CHtml::activeTextField($model, 'lastname',array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label" for="input-email"><?php echo __('Email'); ?></label>
                        <div class="col-sm-10">
                            <?php echo CHtml::activeTextField($model, 'email',array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label"
                               for="input-telephone"><?php echo __('Telephone'); ?></label>
                        <div class="col-sm-10">
                            <?php echo CHtml::activeTextField($model, 'telephone',array('class' => 'form-control')); ?>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <div class="col-sm-12">
                        <br/>
                        <legend><?php echo __('Product Information & Reason for Return');?></legend>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label" for="input-product"><?php echo __('Product'); ?></label>
                        <div class="col-sm-10">
                            <?php echo CHtml::activeTextField($model, 'product',array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label" for="input-model"><?php echo __('Model'); ?></label>
                        <div class="col-sm-10">
                            <?php echo CHtml::activeTextField($model, 'model',array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label" for="input-quantity"><?php echo __('Quantity'); ?></label>
                        <div class="col-sm-10">
                            <?php echo CHtml::activeTextField($model, 'quantity',array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label"
                               for="input-return-reason"><?php echo __('Return Reason'); ?></label>
                        <div class="col-sm-10">
                            <select name="return_reason_id" class="form-control">
                                <?php foreach ($model_return_action as $action): ?>
                                <option value="<?php echo $action['return_action_id']?>">
                                    <?php echo $action['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label" for="input-opened"><?php echo __('Opened'); ?></label>
                        <div class="col-sm-10">
                            <select name="opened" class="form-control">
                                <option value="1" selected="selected">Opened</option>
                                <option value="0">Unopened</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label" for="input-comment"><?php echo __('Comment'); ?></label>
                        <div class="col-sm-10">
                            <?php echo CHtml::textArea($model, 'comment',array('class' => 'form-control', 'rows' =>
                            '5')); ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-2 control-label"
                               for="input-return-action"><?php echo __('Return Action'); ?></label>
                        <div class="col-sm-10">
                            <select id="action1" name="return_action_id" class="form-control">
                                <option value="0">...</option>
                                <?php foreach($model_return_action as $return_action): ?>
                                <option value="<?php echo $return_action['return_action_id']?>">
                                    <?php echo $return_action['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div id="tab_history" class="tab-pane">
                <!--<form method="post">-->
                <table class="table table-hover table-bordered" style="margin-top:15px;">
                    <thead>
                    <tr>
                        <th><?php echo __('Date Added'); ?></th>
                        <th><?php echo __('Comment'); ?></th>
                        <th><?php echo __('Status'); ?></th>
                        <th><?php echo __('Customer Notify'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($historyItems as $data):?>
                    <tr>

                            <td><?php echo $data['date_added']?></td>
                            <td><?php echo $data['comment']?></td>
                            <td><?php echo $data['status']?></td>
                            <td><?php echo $data['customer_id']?></td>

                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                <div class="col-sm-12">
                    <h3><?php echo __('Add Return History');?></h3>
                    <hr>
                    <div class="col-sm-12 form-group">
                        <label class="col-sm-2 control-label" for="input-return-status">Return Status</label>
                        <div class="col-sm-10">
                            <select id="return_status" name="return_status_id" id="input-return-status"
                                    class="form-control">
                                <?php foreach($model_return_status as $return_status):?>
                                <option value="<?php echo $return_status['return_status_id']; ?>">
                                    <?php echo $return_status['name'];?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 form-group">
                        <label class="col-sm-2 control-label" for="input-notify">Notify Customer</label>
                        <div class="col-sm-10">
                            <input type="checkbox" name="notify" value="1" id="input-notify">
                        </div>
                    </div>
                    <div class="col-sm-12 form-group">
                        <label class="col-sm-2 control-label" for="input-history-comment">Comment</label>
                        <div class="col-sm-10">
                            <textarea id="comment" name="history_comment" rows="8" id="input-history-comment"
                                      class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button id="history" type="submit" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle"></i> Add History
                        </button>
                    </div>
                </div>
                <!--</form>-->
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">

    function getParameterByName(name, url) {
        if (!url) {
            url = window.location.href;
        }
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    jQuery(function ($) {
        $("#history").click(function (e) {
            e.preventDefault();
            var return_action = $('#action1').val();
            var return_status = $('#return_status').val();
            var checkedValue = $('#input-notify:checked').val();
            var comment = $('#comment').val();
            var return_id = getParameterByName('return_id');
            $.ajax
            ({
                type: "POST",
                url: '<?php echo $returnAction ?>',
                data: {return_id,return_action,
                       return_status, comment, checkedValue},
                dataType: "JSON",
                cache: false,
                success: function (e) {
                    //var js = JSON.parse(data);
                    e.forEach(function(element) {
                        console.log(element.name);
                        console.log(element.date_added);
                        console.log(element.notify);
                        console.log(element.customer_id);
                        $('.table tr:last').after('<tr><td>'+element.date_added+'</td><td>'+element.comment+'</td><td>'+element.name+'</td><td>'+element.customer_id+'</td></tr>');
                    });

                    //$("ul#list").append('<li color="1" class="colorBlue" rel="1" id="' + inputs.length + '"><span id="' + inputs.length + 'listitem" title="Double-click to edit..." style="opacity: 1;">' + name + '</span><div class="draggertab tab"></div><div class="colortab tab" onclick="myFunction(this)"></div><div class="deletetab tab" style="width: 44px; display: block; right: -64px;"></div><div class="donetab tab"></div></li>');
                }
            });
        });
    });


</script>