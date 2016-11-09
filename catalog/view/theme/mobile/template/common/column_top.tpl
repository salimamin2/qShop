<?php foreach ($modules as $module) { ?>
    <?php echo $this->load('module/' . $module['code'], $module['param']); ?>
<?php } ?>