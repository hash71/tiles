<!DOCTYPE html>

<html lang='en'>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Samir Tiles ERP</title>
    <meta name="description" content="ERP system for samir tiles and sanitary">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Favicons -->

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/icons/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/icons/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/icons/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/images/icons/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="assets/images/icons/favicon2.png">
    
    
	<?php echo HTML::style('http://fonts.googleapis.com/css?family=Exo'); ?>

    <?php echo HTML::style('login_assets/login.css'); ?>


</head>
<body>
<center><h1 id="titleheader">Samir Tiles ERP System</h1></center>
<div id="wrapper">
	


	<!-- <form name="login-form" class="login-form" action="" method="post">	
	</form> -->


	<?php echo Form::open(['name'=>'login-form', 'class'=>'login-form', 'route'=>'sessions.store']); ?>

			
		<div class="header">
		<h1>Login Form</h1>
		</div>
	
		<div class="content">
		<input name="user_name" type="text" class="input username" placeholder="Username" required/>
		<div class="user-icon"></div>
		<input name="password" type="password" class="input password" placeholder="Password" required/>
		<div class="pass-icon"></div>		
		</div>

		<div class="footer">
		<input type="submit" name="submit" value="Login" class="button" />
		</div>
		
	<?php echo Form::close(); ?>


</div>
<div class="gradient"></div>


</body>
</html>
