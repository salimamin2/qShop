<?php if(count($modules)>0): ?>
<div id="ColumnLeft">
    <?php foreach ($modules as $module) { ?>
	<?php echo $this->load('module/' . $module['code'], $module['param']); ?>
    <?php } ?>
</div>
<?php endif; ?>