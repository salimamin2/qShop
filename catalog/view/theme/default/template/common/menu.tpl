<?php if($menu_status): ?>
<ul>
<?php foreach($menus as $i => $menu) { ?>
    <li class="<?php echo ($i == 0) ? 'first' : ''; echo (count($menus)-1 == $i) ? ' last' : ''; ?>"><a href="<?php echo $menu['link']; ?>" <?php echo $menu['attribs']; ?>><span><?php echo $menu['title']; ?></span></a></li>
<?php } ?>
</ul>
<?php endif; ?>