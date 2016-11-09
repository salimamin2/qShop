<div class="user-profile">    
<div class="row header">
        <div class="col-md-8">
            <img src="<?php echo $image; ?>" alt="<?php echo $customer['firstname']; ?>" class="avatar img-circle" />
            <h3 class="name"><?php echo $customer['firstname']. " " .$customer['lastname']; ?></h3>
            <span class="area"><?php echo $customer['group_name']; ?></span>
        </div>
        <a class="btn-flat icon large pull-right edit" href="<?php echo $edit; ?>">Edit This Person</a>
    </div>

    <div class="row profile">
        <div class="col-md-9 bio">
            <div class="profile-box">
                <div class="col-md-12 section">
                    <h6>Customer Details</h6>
                    <p><b>Email: </b><span><?php echo $customer['email']; ?></span></p>
                    <p><b>Telephone: </b><span><?php echo $customer['telephone']; ?></span></p>
                    <p><b>Approved: </b><span><?php echo ($customer['approved'] ? "Yes" : "No"); ?></span></p>
                    <p><b>Status: </b><span><?php echo ($customer['status'] ? "Enabled" : "Disabled"); ?></span></p>
                </div>

                <?php if(!empty($orders)): ?>
                <h6>Recent Orders</h6>
                <br/>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-md-2">Order ID</th>
                            <th class="col-md-3">Status</th>
                            <th class="col-md-3">Date Added</th>
                            <th class="col-md-3">Total Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($orders as $i => $order): ?>
                            <tr <?php echo ($i == 0 ? "class='first'" : ""); ?> >
                                <td><a href="<?php echo $order['href']; ?>">#<?php echo $order['order_id']; ?></a></td>
                                <td><?php echo $order['status']; ?></td>
                                <td><?php echo $order['date_added']; ?></td>
                                <td><?php echo $order['total']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <?php if(isset($address) && !empty($address)): ?>
        <div class="col-md-3 col-xs-12 address pull-right">
            <h6>Address</h6>
            <iframe width="300" height="133" scrolling="no" src="<?php echo $address['src']; ?>"></iframe>
            <ul>
                <li><?php echo $address['address_1']; ?></li>
                <li><?php echo $address['city'] . "," . $address['country']; ?></li>
                <?php if($address['postcode'] != "") : ?>
                    <li>Zip Code, <?php echo $address['postcode']; ?></li>
                <?php endif; ?>
                <li class="ico-li">
                    <i class="ico-phone"></i> <?php echo $address['company']; ?>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>