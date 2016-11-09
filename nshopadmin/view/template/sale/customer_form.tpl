<div class="box">
  <div class="head well">
        <h3><i class="icon-edit"></i> <?php echo $heading_title; ?>
			<div class="pull-right">

            <a onclick="$('#form').submit();" class="btn btn-success btn-sm"><span><?php echo $button_save; ?></span></a>

            <a href="<?php echo $cancel; ?>" class="btn btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
				</div>
            </h3>		
        </div>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="content">

    <div style="display: inline-block; width: 100%;">

                <ul class="nav nav-tabs">

                    <li><a href="#tab_general" data-toggle="tab"><?php echo $tab_general; ?></a></li>

                    <?php if ($customer_id) { ?>

                        <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>

                    <?php } ?>

                    <?php $address_row = 1; ?>

                    <?php foreach ($addresses as $address) { ?>

                        <li><a id="address_<?php echo $address_row; ?>" data-toggle="tab" href="#tab_address_<?php echo $address_row; ?>"><?php echo $tab_address . ' ' . $address_row; ?> &nbsp;<button onclick="$('#vtabs a:first').trigger('click'); $('#address_<?php echo $address_row; ?>').remove(); $('#tab_address_<?php echo $address_row; ?>').remove();" class="btn btn-xs btn-default"><i class="icon-remove"></i></button></a></li>

                    <?php $address_row++; ?>

                    <?php } ?>

                    <li id="add_link"><a id="address_add" onclick="addAddress();" class="add" style="float: right; margin-right: 14px; font-size: 13px; font-weight: bold;">Add Address</a></li>

                </ul>



            <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">

                <div class="tab-content">

                <div id="tab_general" class="vtabs_page tab-pane active">

                    <table class="form">

                        <tr>

                            <td> <span class="entry"><?php echo $entry_firstname; ?></span><span class="required">*</span></td>

                            <td><input class="form-control" type="text" name="firstname" value="<?php echo $firstname; ?>" />

                                <?php if ($error_firstname) { ?>

                                <span class="error"><?php echo $error_firstname; ?></span>

                                <?php } ?></td>

                        </tr>

                        <tr>

                            <td> <span class="entry"><?php echo $entry_lastname; ?></span><span class="required">*</span></td>

                            <td><input class="form-control" type="text" name="lastname" value="<?php echo $lastname; ?>" />

                                <?php if ($error_lastname) { ?>

                                <span class="error"><?php echo $error_lastname; ?></span>

                                <?php } ?></td>

                        </tr>

                        <tr>

                            <td> <?php echo $entry_email; ?><span class="required">*</span></td>

                            <td><input class="form-control" type="text" name="email" value="<?php echo $email; ?>" />

                                <?php if ($error_email) { ?>

                                <span class="error"><?php echo $error_email; ?></span>

                                <?php  } ?></td>

                        </tr>

                        <tr>

                            <td> <?php echo $entry_telephone; ?><span class="required">*</span></td>

                            <td><input class="form-control" type="text" name="telephone" value="<?php echo $telephone; ?>" />

                                <?php if ($error_telephone) { ?>

                                <span class="error"><?php echo $error_telephone; ?></span>

                                <?php  } ?></td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_fax; ?></td>

                            <td><input class="form-control" type="text" name="fax" value="<?php echo $fax; ?>" /></td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_password; ?></td>

                            <td><input class="form-control" type="password" name="password" value="<?php echo $password; ?>"  />

                                <br />

                                <?php if ($error_password) { ?>

                                <span class="error"><?php echo $error_password; ?></span>

                                <?php  } ?></td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_confirm; ?></td>

                            <td><input class="form-control" type="password" name="confirm" value="<?php echo $confirm; ?>" />

                                <?php if ($error_confirm) { ?>

                                <span class="error"><?php echo $error_confirm; ?></span>

                                <?php  } ?></td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_agent; ?></td>

                            <td>
							<div class="ui-select">
							<select name="user_id">

                                    <option value=""></option>

                                    <?php foreach($users as $user){ ?>

                                    <?php if($agent == $user['user_id']) { ?>

                                    <option value="<?php echo $user['user_id'] ?>" selected="selected"><?php echo $user['username'] ?></option>

                                    <?php } else { ?>

                                    <option value="<?php echo $user['user_id'] ?>"><?php echo $user['username'] ?></option>

                                    <?php } ?>

                                    <?php }?>

                                </select>
                                </div>  
                            </td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_newsletter; ?></td>

                            <td>
							<div class="ui-select">
							<select name="newsletter">

                                    <?php if ($newsletter) { ?>

                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

                                    <option value="0"><?php echo $text_disabled; ?></option>

                                    <?php } else { ?>

                                    <option value="1"><?php echo $text_enabled; ?></option>

                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

                                    <?php } ?>

                                </select>
								</div></td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_lcn; ?></td>

                            <td><input class="form-control" type="text" name="lcn" value="<?php echo $lcn; ?>"  /></td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_customer_group; ?></td>

                            <td><div class="ui-select"><select name="customer_group_id">

                                    <?php foreach ($customer_groups as $customer_group) { ?>

                                    <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>

                                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>

                                    <?php } else { ?>

                                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>

                                    <?php } ?>

                                    <?php } ?>

                                </select></div></td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_status; ?></td>

                            <td><div class="ui-select"><select name="status">

                                    <?php if ($status) { ?>

                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

                                    <option value="0"><?php echo $text_disabled; ?></option>

                                    <?php } else { ?>

                                    <option value="1"><?php echo $text_enabled; ?></option>

                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

                                    <?php } ?>

                                </select></div></td>

                        </tr>

                    </table>

                </div>

                <?php $address_row = 1; ?>

                <?php foreach ($addresses as $i => $address) {

                    $errors = (isset($error_addresses[$address_row]) ? $error_addresses[$address_row] : array());

                ?>

                <div id="tab_address_<?php echo $address_row; ?>" class="vtabs_page tab-pane tab-address">

                    <table class="form">

                        <tr>

                            <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>

                            <td>

                                <input type="text" name="addresses[<?php echo $address_row; ?>][firstname]" value="<?php echo $address['firstname']; ?>" />

                                <?php if ($errors['firstname']) { ?>

                                <span class="error"><?php echo $errors['firstname']; ?></span>

                                <?php  } ?>

                            </td>

                        </tr>

                        <tr>

                            <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>

                            <td>

                                <input type="text" name="addresses[<?php echo $address_row; ?>][lastname]" value="<?php echo $address['lastname']; ?>" />

                                <?php if ($errors['lastname']) { ?>

                                <span class="error"><?php echo $errors['lastname']; ?></span>

                                <?php  } ?>

                            </td>

                        </tr>

                        <tr>

                            <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>

                            <td>

                                <input type="text" name="addresses[<?php echo $address_row; ?>][company]" value="<?php echo $address['company']; ?>" />

                                <?php if ($errors['telephone']) { ?>

                                <span class="error"><?php echo $errors['telephone']; ?></span>

                                <?php  } ?>

                            </td>

                        </tr>

                        <tr>

                            <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>

                            <td>

                                <input type="text" name="addresses[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" />

                                <?php if ($errors['address_1']) { ?>

                                <span class="error"><?php echo $errors['address_1']; ?></span>

                                <?php  } ?>

                            </td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_address_2; ?></td>

                            <td><input type="text" name="addresses[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" /></td>

                        </tr>

                        <tr>

                            <td><span class="required">*</span> <?php echo $entry_city; ?></td>

                            <td>

                                <input type="text" name="addresses[<?php echo $address_row; ?>][city]" value="<?php echo $address['city']; ?>" />

                                <?php if ($errors['city']) { ?>

                                    <span class="error"><?php echo $errors['city']; ?></span>

                                <?php  } ?>

                            </td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_postcode; ?></td>

                            <td>

                                <input type="text" name="addresses[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" />

                                <?php if ($errors['postcode']) { ?>

                                    <span class="error"><?php echo $errors['postcode']; ?></span>

                                <?php  } ?>

                            </td>

                        </tr>

                        <tr>

                            <td><span class="required">*</span> <?php echo $entry_country; ?></td>

                            <td><select name="addresses[<?php echo $address_row; ?>][country_id]" id="addresses[<?php echo $address_row; ?>][country_id]" onchange="$('select[name=\'addresses[<?php echo $address_row; ?>][zone_id]\']').load('sale/customer/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $address['zone_id']; ?>');">

                                    <option value="FALSE"><?php echo $text_select; ?></option>

                                    <?php foreach ($countries as $country) { ?>

                                    <?php if ($country['country_id'] == $address['country_id']) { ?>

                                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>

                                    <?php } else { ?>

                                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>

                                    <?php } ?>

                                    <?php } ?>

                                </select>

                                <?php if ($errors['country']) { ?>

                                    <span class="error"><?php echo $errors['country']; ?></span>

                                <?php } ?></td>

                        </tr>

                        <tr>

                            <td><span class="required">*</span> <?php echo $entry_zone; ?></td>

                            <td><select name="addresses[<?php echo $address_row; ?>][zone_id]">

                                </select>

                                <?php if ($errors['zone']) { ?>

                                    <span class="error"><?php echo $errors['zone']; ?></span>

                                <?php } ?></td>

                        </tr>

                    </table>

                    <script type="text/javascript"><!--

              $('select[name=\'addresses[<?php echo $address_row; ?>][zone_id]\']').load('sale/customer/zone&token=<?php echo $token; ?>&country_id=<?php echo $address['country_id']; ?>&zone_id=<?php echo $address['zone_id']; ?>');

              //--></script> 

                </div>

                <?php $address_row++; ?>

                <?php } ?>

                <?php if ($customer_id) { ?>

                <div id="tab-reward" class="vtabs_page tab-pane">

                    <table class="form">

                        <tr>

                            <td><?php echo $entry_description; ?></td>

                            <td><input type="text" name="description" value="" /></td>

                        </tr>

                        <tr>

                            <td><?php echo $entry_points; ?></td>

                            <td><input type="text" name="points" value="" /></td>

                        </tr>

                        <tr>

                            <td colspan="2" style="text-align: right;"><a id="button-reward" class="button" onclick="return addRewardPoints();"><span><?php echo $button_add_reward; ?></span></a></td>

                        </tr>

                    </table>

                    <div id="reward"></div>

                </div>

                <?php } ?>

                </div>

            </form>

        </div>

    </div>

</div>

<script type="text/javascript"><!--

    var address_row = $('.tab-address').length + 1;



    function addAddress() {

        address_row = $('.tab-address').length + 1;

    html  = '<div id="tab_address_' + address_row + '" class="vtabs_page tab-pane tab-address">';

    html += '<table class="form">'; 

    html += '<tr>';

    html += '<td><?php echo $entry_firstname; ?></td>';

    html += '<td><input type="text" name="addresses[' + address_row + '][firstname]" value="" /></td>';

    html += '</tr>';

    html += '<tr>';

    html += '<td><?php echo $entry_lastname; ?></td>';

    html += '<td><input type="text" name="addresses[' + address_row + '][lastname]" value="" /></td>';

    html += '</tr>';

    html += '<tr>';

    html += '<td><?php echo $entry_company; ?></td>';

    html += '<td><input type="text" name="addresses[' + address_row + '][company]" value="" /></td>';

    html += '</tr>';

    html += '<tr>';

    html += '<td><?php echo $entry_address_1; ?></td>';

    html += '<td><input type="text" name="addresses[' + address_row + '][address_1]" value="" /></td>';

    html += '</tr>';

    html += '<tr>';

    html += '<td><?php echo $entry_address_2; ?></td>';

    html += '<td><input type="text" name="addresses[' + address_row + '][address_2]" value="" /></td>';

    html += '</tr>';

    html += '<tr>';

    html += '<td><?php echo $entry_city; ?></td>';

    html += '<td><input type="text" name="addresses[' + address_row + '][city]" value="" /></td>';

    html += '</tr>';

    html += '<tr>';

    html += '<td><?php echo $entry_postcode; ?></td>';

    html += '<td><input type="text" name="addresses[' + address_row + '][postcode]" value="" /></td>';

    html += '</tr>';

    html += '<td><?php echo $entry_country; ?></td>';

    html += '<td>';

    html += '<select name="addresses[' + address_row + '][country_id]" onchange="$(\'select[name=\\\'addresses[' + address_row + '][zone_id]\\\']\').load(\'sale/customer/zone&token=<?php echo $token; ?>&country_id=\' + this.value + \'&zone_id=0\');">';

    html += '<option value="FALSE"><?php echo $text_select; ?></option>';

        <?php foreach ($countries as $country) { ?>

        html += '<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';

        <?php } ?>

    html += '</select>';

    <?php if ($error_country) { ?>

    html += '<span class="error"><?php echo $error_country; ?></span>';

    <?php } ?>

html += '</td>';

html += '</tr>';

html += '<tr>';

html += '<td><?php echo $entry_zone; ?></td>';

html += '<td>';

html += '<select name="addresses[' + address_row + '][zone_id]"><option value="FALSE"><?php echo $this->language->get('text_none'); ?></option></select>';

<?php if ($error_zone) { ?>

html += '<span class="error"><?php echo $error_zone; ?></span>';

<?php } ?>  

html += '</td>';

html += '</tr>';

html += '</table>';

html += '</div>';

	

$('#form .tab-content').append(html);

	

$('ul.nav-tabs li#add_link').before('<li><a id="address_' + address_row + '" href="#tab_address_' + address_row + '" data-toggle="tab"><?php echo $tab_address; ?> ' + address_row + ' &nbsp;<button onclick="$(\'#vtabs a:first\').trigger(\'click\'); $(\'#address_' + address_row + '\').remove(); $(\'#tab_address_' + address_row + '\').remove();" class="btn btn-default btn-xs"><i class="icon-remove"></i></button></a><li>');

		

$.tabs('.vtabs a', address_row);

	

$('#address_' + address_row).trigger('click');



address_row++;

}

$('#reward .pagination a').live('click', function() {

$('#reward').load(this.href);



return false;

});



$('#reward').load('sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');



function addRewardPoints() {

var r = $('#tab-reward input[name=\'points\']').val();	

var x=window.confirm("Are you sure you want to award "+r+" ?  You will not be able to remove these points.");

if (!x)

return false;

else



        

        

$.ajax({

url: 'sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',

type: 'post',

dataType: 'html',

data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),

beforeSend: function() {

$('.success, .warning').remove();

$('#button-reward').attr('disabled', true);

$('#reward').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');

},

complete: function() {

$('#button-reward').attr('disabled', false);

$('.attention').remove();

},

success: function(html) {

$('#reward').html(html);



$('#tab-reward input[name=\'points\']').val('');

$('#tab-reward input[name=\'description\']').val('');

}

});



return true;

}

//--></script>