<?php if ($error) { ?>

<div class="alert alert-warning warning"><?php echo $error; ?></div>

<?php } ?>

<?php if ($success) { ?>

<div class="alert alert-success success"><?php echo $success; ?></div>

<?php } ?>

<div class="table-responsive">

<table class="table table-hover" data-rel="data-grid" id="history_table">

  <thead>

    <tr>

      <th class="left"><?php echo $column_date_added; ?></th>

      <th class="left">
	  <span class="line"></span><?php echo $column_comment; ?></th>

      <th class="left">
	  <span class="line"></span><?php echo $column_status; ?></th>

      <th class="left">
    <span class="line"></span><?php echo $column_notify; ?></b></th>
      <th class="left">
	  <span class="line"></span><?php echo $column_notify_manufacturer; ?></b></th>

    </tr>

  </thead>

  <tbody>

    <?php if ($histories) { ?>

    <?php foreach ($histories as $history) { ?>

    <tr>

      <td class="left"><?php echo $history['date_added']; ?></td>

      <td class="left"><?php echo $history['comment']; ?></td>

      <td class="left"><?php echo $history['status']; ?></td>

      <td class="left"><?php echo $history['notify']; ?></td>
      <td class="left"><?php echo $history['notify_manufacturer']; ?></td>

    </tr>

    <?php } ?>

    <?php } ?>

  </tbody>

</table>

</div>

