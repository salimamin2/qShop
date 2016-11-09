<script type="text/javascript">
    jQuery(function ($) {
        $('.date').datepicker({ dateFormat: 'yy-mm-dd' });
    });
    jQuery(function ($) {
        $("#btn1").click(function() {
            //e.preventDefault();
            if ( $('.form-control').filter(function() {
                        return $(this).val() != '';
                    }).length == 0
            ){
                $(".form-control").parent().after("<div class='validation' style='color:#990000; margin-bottom: 15px;'>* Field is Required</div>");
                return false;
            }else {
                $(".form-control").parent().next(".validation").remove();
            }
        });

    });


</script>

<hr>

<ul class="messages">
    <?php if($errors): ?>
    <li class="error-msg">
        <ul>
            <?php foreach($errors as $error): ?>
            <li><span><?php echo $error; ?></span></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <?php endif; ?>
</ul>

<form method="post" action="account/return" id="form">
    <div id="Content" class="my-account container">
        <div class="col-md-12">
            <div class="page-description">
                <div class="post-content">
                    <h2 class="order-date text-center"><h1>Product Details</h1></h2>
                    <p>Please complete the form below to request.</p>

                            <p class="sub-heading-acount"><?php echo __('Order Information'); ?></p>
                            <div class="line"></div>


                    <div class="modal-body">

                        <div class="form-group required">
                            <label class="str" for="first_name">First Name</label>
                            <input type="text" name="firstname" id="<?php echo $firstname; ?>" placeholder="Firstname" class="form-control required-entry"/>

                        </div>

                        <div class="form-group required">
                            <label class="str" for="last_name">Last Name</label>
                            <input type="text" name="lastname" id="<?php echo $lastname; ?>" placeholder="Lastname" class="form-control required-entry"/>
                        </div>

                        <div class="form-group required">
                            <label class="str" for="email">Email Address</label>
                            <input type="text" name="email" id="<?php echo $email; ?>" placeholder="Email Address" class="form-control required-entry"/>
                        </div>

                        <div class="form-group required">
                            <label class="str" for="telephone">Telephone</label>
                            <input type="text" name="telephone" id="<?php echo $telephone; ?>" placeholder="Telephone" class="form-control required-entry"/>
                        </div>

                        <div class="form-group required">
                            <label class="str" for="orderid">Order ID</label>
                            <input type="text" name="order_id" id="<?php echo $order_id; ?>" placeholder="Order ID" class="form-control required-entry"/>
                        </div>

                        <div class="form-group">
                            <label for="date">Date</label>
                            <input class="date" name="date_ordered" id="date_ordered" type="text" placeholder="Date"/>
                        </div>

                    </div>
                    <div class="col2-set order-info-box row">
                        <div class="col-sm-9">
                            <p class="sub-heading-acount"><?php echo __('Product Information & Reason for Return'); ?></p>
                            <div class="line"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-body">

                        <div class="form-group required">
                            <label class="str" for="product_name">Product Name</label>
                            <input type="text" name="product" id="<?php echo $product; ?>" placeholder="Product Name" class="form-control required-entry"/>
                        </div>

                        <div class="form-group required">
                            <label class="str" for="product_model">Product Code</label>
                            <input type="text" name="model" id="<?php echo $model; ?>" placeholder="Product Model" class="form-control required-entry"/>
                        </div>

                        <div class="form-group required">
                            <label class="str" for="quantity">Quantity</label>
                            <input type="text" name="quantity" id="<?php echo $quantity; ?>" placeholder="Quantity" class="form-control required-entry"/>
                        </div>


                        <div class="form-group required ">
                            <label class="str" for="return_reason">Reason for Return</label>
                            <select name="return_reason_id" id="return_reason_id">
                                <option id="1" value="1">Dead On Arrival</option>
                                <option id="2" value="2">Faulty, please supply details</option>
                                <option id="3" value="3">Order error</option>
                                <option id="4" value="4">Other, please supply details</option>
                                <option id="5" value="5">Received Wrong Item</option>
                            </select>
                        </div>

                        <div class="form-group required radio-custom ">
                            <label class="str" for="product_opened">Product is Opened</label>

                            <input name="return_status_id" type="radio" id="radio01" value="1"/>
                            <label for="radio01"><span></span>Awaiting Products</label>

                            <input name="return_status_id" type="radio" id="radio02" value="2"/>
                            <label for="radio02"><span></span>Complete</label>

                            <input name="return_status_id" type="radio" id="radio03" value="3"/>
                            <label for="radio03"><span></span>Pending</label>
                        </div>

                        <div class="form-group required">
                            <textarea name="comment" placeholder="Other Details" rows="4" cols="50"></textarea>
                        </div>

                    </div>

                </div>

            </div>
        </div>
        <div class="clearfix"></div>

        <div class="buttons-set">

            <div class="col-sm-4">
                <div class="panel-footer row"><!-- panel-footer -->
                    <div class="col-sm-6 text-left">
                        <div class="previous">
                            <button type="button" onclick="goBack()" style="margin-left: 110px;" class="btn btn-cntinue btn-account">
                                <span class="glyphicon glyphicon-chevron-left">
                                    <?php echo _('Back'); ?></span>
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="next">
                            <button id="btn1" type="submit" style="margin-left: 6em;" class="btn btn-cntinue btn-account">
                                <span class="glyphicon glyphicon-chevron-right"><?php echo _('Save'); ?></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- <button type="submit" style="margin-left: 89px;" class="btn btn-cntinue btn-account"><span><span><?php echo __('Save'); ?></span></span></button>
                <button type="submit" style="margin-left: 590px;" class="btn btn-cntinue btn-account"><span><span><?php echo __('Save'); ?></span></span></button> -->

            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</form>
<script>
    function goBack() {
        window.history.back();
    }


</script>