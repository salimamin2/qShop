<div class="box">
    <div class="head well">
        <h3><i class="icon-pencil"></i> <?php echo $return_add; ?></h3>
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
                    <span><?php echo $text_general?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tab_general" class="tab-pane active">
                <form method="post" action="">
                    <fieldset>
                        <div class="col-sm-12">
                            <br/>
                            <legend>Order Information</legend>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-order-id"><?php echo $text_order_id; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="order_id" placeholder="Order ID"class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-date-ordered"><?php echo $text_order_date; ?></label>
                            <div class="col-sm-3">
                                <div class="input-group date">
                                        <input type="text" name="date_ordered"data-provide="datepicker-inline" placeholder="Order Date" data-format="YYYY-MM-DD" class="form-control">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-customer"><?php echo $text_customer; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="customer" value="" placeholder="Customer" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-firstname"><?php echo $text_first_name; ?></label>
                            <div class="col-sm-10">
                            <input type="text" name="firstname" placeholder="First Name" id="input-firstname" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-lastname"><?php echo $text_last_name; ?></label>
                            <div class="col-sm-10">
                            <input type="text" name="lastname" placeholder="Last Name" id="input-lastname" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-email"><?php echo $text_email; ?></label>
                            <div class="col-sm-10">
                            <input type="text" name="email" placeholder="E-Mail" id="input-email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-telephone"><?php echo $text_telephone; ?></label>
                            <div class="col-sm-10">
                            <input type="text" name="telephone" placeholder="Telephone" id="input-telephone" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="col-sm-12">
                            <br/>
                            <legend><?php echo $text_pinfo_reason;?></legend>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-product"><?php echo $text_product; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="product" placeholder="Product" id="return_products" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-model"><?php echo $text_model; ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="return-model" placeholder="Model" class="form-control" id="return-model">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-quantity"><?php echo $text_quantity; ?></label>
                            <div class="col-sm-10">
                            <input type="text" name="quantity" placeholder="Quantity" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-return-reason"><?php echo $text_return_reason; ?></label>
                            <div class="col-sm-10">
                            <select name="return_reason_id" class="form-control">
                                <?php foreach($model_return_reason as $return_reason): ?>
                                    <option value="<?php echo $return_reason['return_reason_id']; ?>">
                                        <?php echo $return_reason['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-opened"><?php echo $text_opened; ?></label>
                            <div class="col-sm-10">
                                <select name="opened" class="form-control">
                                    <option value="1" selected="selected">Opened</option>
                                    <option value="0">Unopened</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-comment"><?php echo $text_comment; ?></label>
                            <div class="col-sm-10">
                                <textarea name="comment" rows="5" placeholder="Comment"class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-2 control-label" for="input-return-action"><?php echo $text_return_action; ?></label>
                            <div class="col-sm-10">
                            <select name="return_action_id" class="form-control">
                                <option value="0"></option>
                                <?php foreach($model_return_action as $return_action): ?>
                                <option value="<?php echo $return_action['return_action_id']?>">
                                    <?php echo $return_action['name']; ?>
                                </option>
                            <?php endforeach; ?>
                            </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-primary pull-right">Submit</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>