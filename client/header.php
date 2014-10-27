<?php
	require_once('../server/session.php');
	if (session_status() == PHP_SESSION_NONE)
		session_start();
?> 

<!-- Global header -->
<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" lang="en"> <!--<![endif]-->

<head>
	<meta charset="utf-8" />

	<!-- Set the viewport width to device width for mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="./css/normalize.css">
	<link rel="stylesheet" href="./css/foundation.css">

	<script src="./js/vendor/custom.modernizr.js"></script>
	<script src="./js/vendor/jquery.js"></script>
	
	<title>KickPromo</title>
 
	<style>
		#login {
		padding-top:6px;
		}

		#welcome{
		border:1px solid;
		}

		#nom_usr{
		border:1px solid;
		}

		#salir{
		border:1px solid;
		}	
	</style>	
</head>

<body>

	<!-- Main Menu -->
	<div class="row">  
		<div class="small-5 large-5 columns">
			<a href="index.php"><img src="./img/logo.png"/></a>
		</div>

		<?php
			// If user is not logged, show log in form
			if(!isLogged())
			{
		?>
		<!-- Log in form -->
		
		
		
		<div class="small-7 large-7 columns" id="login">
			<div class="row right inline" id="login">
			  <form data-abide>
				<ul class="inline-list"> 
				<li><a href="getstarted.php" class="button tiny">Comienza Ya!</a></li>
				<li><a href="#" data-dropdown="drop1" class="tiny button dropdown">Log In</a><br></li> 
					<ul id="drop1" data-dropdown-content class="f-dropdown"> 
				<li>
					<div class="small-12 large-12 columns">
						<input type="text" id="usr_email" placeholder="Correo" required pattern="email">
				        <small class="error">This is required</small>  
					</div>
				</li>
				<li>
					<div class="small-12 large-12 columns">
						<input type="password" id="usr_passwd" placeholder="Contraseña" required pattern="alpha_numeric">
				        <small class="error">This is required</small>
					</div>
				</li>
				<li>
					<div class="small-12 large-6 columns">
						<a onClick="OnClickLogin()" class="tiny button">Entrar</a>
					</div>
				</li>
					</ul>
				</ul>
			</form>
	
			</div>
		</div>
		
			
		<?php
			// If user is logged, show user info
			}
			else
			{
		?>	
		<!-- Logged user -->
		<div class="small-8 large-8 columns" id="usr_logged">
			<div class="row right inline" id="login">
				<ul class="inline-list"> 
				<li><a href="#" data-dropdown="drop1" class="tiny button dropdown">Opciones</a><br></li> 
					<ul id="drop1" data-dropdown-content class="f-dropdown"> 
						<li><a href="ca_raffles.php">Promociones</a></li>
						<li><a href="ca_user.php" id='info_email'>Perfil - </a></li>								
						<li><a href="index.php?logout=true">Cerrar Sesión</a></li>
					</ul>
				<li><a href="tutorial.php" class="button tiny">Tutorial</a></li>
				</ul>

	
			</div>
		</div>
		<div id='userdata' type='hidden'/>
		<?php
			}
		?>

	</div>
<!-- End Header and Nav -->

<!-- Initialize components -->
<script>

	function addEvent(obj, evType, fn)
	{
		if (obj.addEventListener)
		{
	   		obj.addEventListener(evType, fn, false);
	   		return true;
	 	}
	 	else if (obj.attachEvent)
	 	{
	   		var r = obj.attachEvent("on"+evType, fn);
	   		return r;
	 	} 
		else
		{
	   		return false;
	 	}
	}
	
	function waitForElement(objName, freq, fn)
	{
		if(typeof window[objName] != "undefined" && window[objName] != null)
		{
			fn();
		}
		else
		{
			setTimeout(function() {waitForElement(objName, freq, fn);}, freq);
		}
	}
	
	<?php
		// If user is logged, load its data
		if(isLogged())
		{
	?>
	
	var userdata = null;
	addEvent(window, 'load', initializeHeader);
	
	function initializeHeader()
	{
		// fill AJAX request
		new Ajax.Request('http://'+location.host+'/kickpromo/server/auth.php', 
		{
			method:'post',
			parameters:
			{
				func: 'loadUSR'
			},
			onSuccess: function(transport)
			{
				// Client successfully registered
				if(transport && transport.responseText.length > 0)
				{
				    if(transport.responseText.strip() == '"SESSION_ERROR"')		
				    {
						alert("Error de sesión");
						window.location.href = 'http://'+location.host+'/kickpromo/client/index.php';
					}
					
					// Get user info
					userdata = transport.responseText.evalJSON(true);
					document.getElementById("info_email").innerHTML += userdata.usr_email;
				}
				else
				{
				  alert('Error al cargar los datos del usuario');
				}
			},
			onFailure: function()
			{
				alert('Error al cargar los datos del usuario');
			}
		});	
	}
	
	<?php
		// else, show login form
		}
		else
		{ 
	?>
	
	function OnClickLogin()
	{
		// Recover fields
		var correo = document.getElementById("usr_email").value;
		var clave = document.getElementById("usr_passwd").value;

		// fill AJAX request
		new Ajax.Request('http://'+location.host+'/kickpromo/server/auth.php', 
		{
			method:'post',
			parameters:
			{
				func: 'login', 
				usr_email: correo,
				usr_passwd: clave
			},
			onSuccess: function(transport) 
			{
				// Client successfully registered
				if(transport && transport.responseText.length > 0)
				{
					retVal = transport.responseText.evalJSON();
					
					// Load the client area if user exists
					if(retVal != 'WRONG_USR_PASSWD')
						window.location.href = 'http://'+location.host+'/kickpromo/client/ca_raffles.php';
					else
						alert('Usuario o contraseña incorrectos'); 
				}
				else
				  alert('Error al iniciar sesión');
			},
			onFailure: function()
			{
				alert('Error al iniciar sesión');
			}
		});
	}	

	<?php
		}
	?>
</script>