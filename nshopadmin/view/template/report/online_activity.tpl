<html>
    <head>
        <base href="<?php echo $base; ?>" />
        <link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
    </head>
    <body>
        <?php echo $activity ?>
    </body>
    <script type="text/javascript" src="view/javascript/jquery/jquery.js"></script>
    <script type="text/javascript" src = 'view/javascript/jquery/jquery.colorize-2.0.0.js'></script>
    <script type="text/javascript" src = 'view/javascript/jquery/jquery.ezpz_tooltip.min.js'></script>
    <script type="text/javascript">
        $('table').colorize();
        $(".tooltip-target").ezpz_tooltip();
    </script>
</html>