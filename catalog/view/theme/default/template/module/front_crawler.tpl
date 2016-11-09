<?php if($frontcrlr_status): ?>
<script type="text/javascript" src="<?php echo HTTP_SERVER ?>catalog/view/javascript/jquery/crawler.js">
/* Text and/or Image Crawler Script v1.5 (c)2009-2011 John Davenport Scheuer
as first seen in http://www.dynamicdrive.com/forums/
username: jscheuer1 - This Notice Must Remain for Legal Use
updated: 4/2011 for random order option, more (see below)
*/

</script>

<script type="text/javascript">
marqueeInit({
uniqueid: 'mycrawler2',
style: {
'padding': '2px',
'width': '1000px',
'height': '90px'
},
inc: 2, //speed - pixel increment for each iteration of this marquee's movement
mouse: 'pause', //mouseover behavior ('pause' 'cursor driven' or false)
moveatleast: 2,
neutral: 150,
savedirection: true,
random: false
});
</script>
    <div class="marquee" id="mycrawler2">
        <?php foreach ($frontcrlr as $front) : ?>
            <?php if ($front['link_value']) : ?><a href="<?php echo $front['link_value'] ?>"><?php endif; ?>
                <img src="<?php echo $front['image_value']; ?>" alt="" />
            <?php if ($front['link_value']) : ?></a><?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>