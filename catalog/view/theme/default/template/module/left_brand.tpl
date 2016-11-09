<?php if ($categories) {?>
<div class="module module-left green">
    <h2><span><?php echo $heading_title; ?></span></h2>
    <div class="grd">
    <ul class="navmenu">
        <?php foreach ($categories as $i => $category) { ?>
            <li class="<?php echo (count($categories) == $i)?'last ':''; echo ($i % 2 == 1)?'even ':'odd '; ?>"><a href="<?php echo str_replace('&', '&amp;', $category['href']); ?>" ><?php echo $category['name']; ?></a></li>
        <?php } ?>
    </ul>
    </div>
    <div class="bottom"><span></span></div>
</div>
<?php }?>