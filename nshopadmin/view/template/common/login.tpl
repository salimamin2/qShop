<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">

    <head>

	<meta charset="utf-8" />

        <title>Login</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	    <meta name="apple-mobile-web-app-capable" content="yes" /> 

	    <base href="<?php echo $base; ?>" />

	    <link type="text/css" href="view/stylesheet/bootstrap.min.css" rel="stylesheet" />

	    <link type="text/css" href="view/stylesheet/style.css" rel="stylesheet" />

	    <link type="text/css" href="view/stylesheet/bootstrap-responsive.css" rel="stylesheet" />

	    <link type="text/css" href="view/stylesheet/style-responsive.css" rel="stylesheet" />

	    <link href="view/stylesheet/font-awesome.css" rel="stylesheet" />

	    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet" />

	    <!--<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />-->

	    <script type="text/javascript" src="view/javascript/jquery/jquery.js"></script>

    <!-- bootstrap -->

    <!-- <link href="view/stylesheet/bootstrap.css" rel="stylesheet" /> -->

    <link href="view/stylesheet/bootstrap-overrides.css" type="text/css" rel="stylesheet" />



    <!-- global styles -->

    <link rel="stylesheet" type="text/css" href="view/stylesheet/layout.css" />

    <link rel="stylesheet" type="text/css" href="view/stylesheet/elements.css" />

    <link rel="stylesheet" type="text/css" href="view/stylesheet/icons.css" />



    <!-- libraries -->

    <link rel="stylesheet" type="text/css" href="view/stylesheet/font-awesome.css" />

    

    <!-- this page specific styles -->

    <link rel="stylesheet" href="view/stylesheet/signin.css" type="text/css" media="screen" />



    <!-- open sans font -->

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css' />

    <!--[if lt IE 9]>

      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>

    <![endif]-->

	</head>

    <body class="bck">



    

		<div class="login-wrapper">

        <a href="index.html">

          <img class="logo" src="view/image/logo.png" alt="logo" />

        </a>



          <div class="box">

             <div class="content-wrap">

			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

                <h6>Member Login</h6>

				 <?php if ($success) { ?>

    		    <div class="alert alert-success"><?php echo $success; ?></div>

		    <?php } ?>

		    <?php if ($error_warning) { ?>

    		    <div class="alert alert-danger alert-dismissible" role="alert">

    			<button type="button" class="close" data-dismiss="alert">×</button>

			    <?php echo $error_warning; ?>

    		    </div>

		    <?php } ?>

		    <div class="login-fields">



			<p>Please provide your details</p>

                <input class="form-control" type="text" placeholder="User name" id="username" name="username" value="">

				

                <input class="form-control" type="password" placeholder="Your password" id="password" name="password" value="">

         <?php if ($redirect) { ?>

    			<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />

		 <?php } ?> 

               

                <button class="btn-glow primary login"><?php echo $button_login; ?>

				</button>

             </div>

          </div>

        </div>

</form>

       <!-- <div class="box">

            <div class="content-wrap">

                <h6>Member Login</h6>

                <input class="form-control" type="text" placeholder="E-mail address">

                <input class="form-control" type="password" placeholder="Your password">

              

               

                <a class="btn-glow primary login" href="index.html">Log in</a>

            </div>

        </div>-->



       

    

		







		

			



    </body>

</html>