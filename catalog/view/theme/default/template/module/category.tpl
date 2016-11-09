<?php if($category): ?>

<div id="side-category-menu" class="block block-vertnav hide-below-768">

    <div class="block-title">

        <strong><span>Categories: <?php echo $parent_title; ?></span></strong>

    </div>

        <?php echo $category; ?>

    <!--div class="block-content">

    <ul id="accordion" class="accordion accordion-style1 vertnav vertnav-side clearer">

    <?php /*

    $i = 1;

    $length = count($categories);

    foreach ($categories as $cat):

            $class = "level0 nav-".$i;

            $class .= ($i == 1 ? ' first' : '');

            $class .= ($i == $length ? ' last' : '');

            $class .= (!empty($cat['child']) ? ' parent' : '');

        ?>

        <li class="<?php echo $class; ?>"><a href="<?php echo $cat['href']; ?>" title="<?php echo $title; ?>" alt="<?php echo $title; ?>"><span><?php echo $cat['name']; ?></span></a>

            <?php if($cat['child']): ?>

            <span class="opener">&nbsp;</span>

                <ul class="level0">

                    <?php

                    $j = 1;

                    $num_child = count($cat['child']);

                    foreach ($cat['child'] as $child):

                        $child_class = "level1 nav-".$i."-".$j;

                        $child_class .= ($j == 1 ? ' first' : '');

                        $child_class .= ($j == $num_child ? ' last' : '');

                    ?>

                        <li class="<?php echo $child_class; ?>"><a href="<?php echo $child['href']; ?>" title="<?php echo $child['title']; ?>" alt="<?php echo $child['title']; ?>"><?php echo $child['name']; ?></a></li>

                    <?php $j++; ?>

                    <?php endforeach; ?>

                </ul>

            <?php endif; ?>

        </li>

    <?php $i++; ?>

    <?php endforeach; */ ?>

    </ul>

    </div-->

</div>

<?php endif; ?>