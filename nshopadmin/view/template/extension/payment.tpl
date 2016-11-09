<div class="box table-wrapper products-table section">
  <div class="head well">
    <h3><i class="icon-columns"></i><?php echo $heading_title; ?></h3>
  </div>
      <?php if ($success) { ?>
          <div class="success"><?php echo $success; ?></div>
      <?php } ?>
      <?php if ($error) { ?> 
          <div class="warning"><?php echo $error; ?></div>
      <?php } ?>

  <div class="content">

    <div class="table-responsive">

	    <table class="table table-hover" data-rel="data-grid">

      <thead>

        <tr>

          <th class="left"><span class="line"></span><?php echo $column_name; ?></th>

          <th class="left"></th>

          <th class="left"><span class="line"></span><?php echo $column_status; ?></th>

          <th class="left"><span class="line"></span><?php echo $column_sort_order; ?></th>

          <th class="left"><span class="line"></span><?php echo $column_action; ?></th>	

        </tr>

      </thead>

      <tbody>

        <?php if ($extensions) { ?>

        <?php foreach ($extensions as $extension) { ?>

        <tr>

          <td class="left"><?php echo $extension['name']; ?></td>

          <td class="center"><?php echo $extension['link'] ?></td>

          <td class="left"><?php echo $extension['status'] ?></td>

          <td class="right"><?php echo $extension['sort_order']; ?></td>

          <td class="right"><?php foreach ($extension['action'] as $action) { ?>

            <a class="<?php echo $action['class']; ?>" href="<?php echo $action['href']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>

            <?php } ?></td>

        </tr>

        <?php } ?>

        <?php } else { ?>

        <tr>

          <td class="center" colspan="6"><?php echo $text_no_results; ?></td>

        </tr>

        <?php } ?>

      </tbody>

    </table>

  </div>

</div>
</div>
