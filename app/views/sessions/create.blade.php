<!DOCTYPE html>

<html lang='en'>
<head>
    <meta charset="UTF-8" /> 
    <title>
        Samir Tiles ERP System
    </title>
    
    
	{{ HTML::style('http://fonts.googleapis.com/css?family=Exo') }}
    {{ HTML::style('login_assets/login.css') }}

</head>
<body>
<center><h1 id="titleheader">Samir Tiles ERP System</h1></center>
<div id="wrapper">
	


	<!-- <form name="login-form" class="login-form" action="" method="post">	
	</form> -->


	{{ Form::open(['name'=>'login-form', 'class'=>'login-form', 'route'=>'sessions.store']) }}
			
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
		<input type="submit" name="submit" value="Forgot password?" class="register" />
		</div>
		
	{{ Form::close()}}

</div>
<div class="gradient"></div>


</body>
</html>
