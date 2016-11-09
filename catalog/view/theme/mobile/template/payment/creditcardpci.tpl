<form id="payment" class="card-form grid12-12">
   <!--  <fieldset>
        <legend><?php echo $text_card_details; ?></legend>
    </fieldset> -->
    <div class="row" style="margin-bottom: 30px; margin-top: 30px;">
       
       <div class="col-md-6">
            <!-- <label class="required" for="input-cc-number"><?php //echo $entry_cc_number; ?>Credit card number </label> -->
            <div class="input-box">
                <input type="text" data-checkout="card-number" value="" placeholder="<?php //echo $entry_cc_number; ?>Credit card number" id="input-cc-number" class="input-text required-entry" />
            </div>
        </div>

        <div class="col-sm-3 col-md-3">
           <!--  <label class="required" for="input-cc-expire-month"><?php echo $entry_cc_expire_month; ?></label> -->
            <div class="input-box">
                <select data-checkout="expiry-month" id="input-cc-expire-month" class="required-entry">
                    <?php foreach ($months as $month) { ?>
                        <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-sm-3 col-md-3">
           <!--  <label class="required" for="input-cc-expire-year">
                <?php echo $entry_cc_expire_year; ?>
            </label> -->
            <div class="input-box">
                <select data-checkout="expiry-year" id="input-cc-expire-year" class="required-entry">
                    <?php foreach ($year_expire as $year) { ?>
                        <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <!-- <label class="required" for="input-cc-owner">
                <?php echo $entry_cc_owner; ?>
            </label> -->
            <div class="input-box">
                <input data-checkout="card-name" type="text" value="" placeholder="<?php //echo $entry_cc_owner; ?>Credit card owner" id="input-cc-owner" class="input-text required-entry" />
            </div>
        </div>
           
        <div class="col-md-6">
            <!-- <label class="required" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?></label> -->
            <div class="input-box">
                <input type="text" data-checkout="cvv" value="" placeholder="<?php //echo $entry_cc_cvv2; ?>Security code" id="input-cc-cvv2" class="input-text required-entry" />
            </div>
        </div>
    </div>
</form>