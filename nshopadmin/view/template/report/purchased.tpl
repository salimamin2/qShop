<div class="box">
  <div class="head well">
    <h3><i class="icon-folder-open"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="content">
      <div style="background: #E7EFEF; border: 1px solid #C6D7D7; padding: 3px; margin-bottom: 15px;">

          <table>

              <tr>
                  <td width="10%">
                      <?php echo __('entry_country'); ?><br />
                      <div class="ui-select">

                          <select name="filter_country_id" style="margin-top: 1px;">

                              <option value="0"><?php echo __('text_countries'); ?></option>

                              <?php foreach ($countries as $country) { ?>

                              <option value="<?php echo $country['country_id']; ?>" <?php echo ($country['country_id'] == $filter_country_id ? "selected='selected'" : ""); ?>><?php echo $country['name']; ?></option>

                              <?php } ?>

                          </select>
                      </div>
                  </td>
                  <td align="right"><button style="margin-top:20px"  onclick="filter();" class="btn-flat btn-success btn-sm"><span><?php echo __('button_filter'); ?></span></button></td>
              </tr>
              </table>
          </div>
    <table class="table table-hover">
      <thead>
        <tr>
          <th class="left"><?php echo $column_name; ?></th>
          <th class="left"><span class="line"></span><?php echo $column_model; ?></th>
          <th class="right"><span class="line"></span><?php echo $column_quantity; ?></th>
          <th class="right"><span class="line"></span><?php echo $column_total; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if ($products) { ?>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="left"><?php echo $product['name']; ?></td>
          <td class="left"><?php echo $product['model']; ?></td>
          <td class="right"><?php echo $product['quantity']; ?></td>
          <td class="right"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="row"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--

    function filter() {

        url = 'report/purchased';


        var filter_country_id = $('select[name=\'filter_country_id\']').attr('value');



        if (filter_country_id) {

            url += '&filter_country_id=' + encodeURIComponent(filter_country_id);

        }


        location = url;

    }

    //--></script>
