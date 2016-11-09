<?php if(count($modules)>0): ?>
<div id="ColumnBottom">
    <?php foreach ($modules as $module) { ?>
	<?php echo $this->load('module/' . $module['code'], $module['param']); ?>
    <?php } ?>
</div>
<?php endif; ?>
