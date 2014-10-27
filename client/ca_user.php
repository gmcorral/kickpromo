<?php
	//session_start();
	include 'header.php';	
	if(!isLogged())
		header('Location: index.php');
?> 




<!-- Main panel -->
<div class="row"> 
	<div class="medium-11 small-11 large-12 small-centered columns"> 
		<div class="medium-8 columns panel">
		   
		   <h4>Datos de usuario</h4>
	  
		   <form data-abide>
		
				<!-- Field 1 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Email*</label>
				</div>
				<div class="medium-7 small-8 columns ">
				  <label for="left-label" class="left inline" id="reg_email"></label>
				  <small class="error">This is required</small>
				</div>
				<div class="large-offset-2 small-offset-3 columns">
				</div>
				
				<!-- Field 2 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Nombre</label>
				</div>
				<div class="medium-7 small-8 columns ">
				  <input type="text" id="reg_name">
				  <small class="error">This is required</small>
				
				</div>
				<div class="large-offset-2 small-offset-3 columns">
				</div>
				<div class="small-12 large-12 columns">
			       <a onClick="OnClickChangeUserData()" class="button tiny">Modificar Datos de Usuario</a>
		        </div>
		   </form>

<hr/>

		    <h4>Cambiar contraseña</h4>
	  
		    <form data-abide class="medium-12 row small-centered large-centered columns">
				<!-- Field 3 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Contraseña*</label>
				</div>
				<div class="medium-7 small-8 columns">
				  <input type="password" id="old_passwd" placeholder="Contraseña" required pattern="alpha_numeric">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>
				
				<!-- Field 4 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right">Contraseña nueva*</label>
				</div>
				<div class="medium-7 small-8 columns">
				  <input type="password" id="new_passwd1" placeholder="Contraseña" required pattern="alpha_numeric">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>
			
				<!-- Field 5 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline"></label>
				</div>
				<div class="medium-7 small-8 columns">
				  <input type="password" id="new_passwd2" placeholder="Repite la contraseña" required pattern="alpha_numeric" data-equalto="new_passwd1">
				  <small class="error">Password doesn't match, please check</small>
				</div>
				<div class="large-offset-2 small-offset-3 columns">
				</div>
			    <div class="small-12 large-12 columns">
			      <a onClick="OnClickChangeUserPass()" class="button tiny">Cambiar Contraseña</a>
		        </div>
			
		</form>

		</div>  
		<div class="medium-4 columns text-center">
			<div class="large-1 columns text-center">
			</div>
			<div class="large-11 columns right text-center">
				<h4>Tips</h4>
				  <hr>
				  <p>Has probado a echar un ojo en tutorial, nos hemos esforzado en que esté todo claro.</p>
				  <hr>
				  <p>Trataremos de contestarte lo antes posible. </p>	
				  <hr>
			</div>
		</div>	
	</div>
</div>

	<script>
	
	addEvent(window, 'load', initializeUserData);
	
	function initializeUserData()
	{
		// Load User Info
		if (document.getElementById("reg_email") != null)
			document.getElementById("reg_email").innerHTML = "Cargando...";
		
		if (document.getElementById("reg_name") != null)
			document.getElementById("reg_name").value = "Cargando...";

// 		if (document.getElementById("reg_birthdate") != null)
// 			document.getElementById("reg_birthdate").value = "Cargando...";						
			
		if (document.getElementById("reg_lastvisit") != null)
			document.getElementById("reg_lastvisit").innerHTML = "Cargando...";

		// Hide alerts
		//$("mod_fullnameOK").hide(); $("mod_fullnameERROR").hide();
		//$("mod_birthdateOK").hide(); $("mod_birthdateERROR").hide();
		//$("mod_password2OK").hide(); $("mod_password2ERROR").hide();
		//$("mod_password3OK").hide(); $("mod_password3ERROR").hide();
		
		waitForElement('userdata', 500, loadUserData);
	}
	
	function loadUserData()
	{
		// Load User Info
		userinfo = window['userdata'];
		
		if (document.getElementById("reg_email") != null)
			document.getElementById("reg_email").innerHTML = userinfo.usr_email;
		
		if (document.getElementById("reg_name") != null)
			document.getElementById("reg_name").value = userinfo.usr_fullname;

// 		if (document.getElementById("reg_birthdate") != null)
// 			document.getElementById("reg_birthdate").value = userinfo.usr_birthdate;						
			
		if (document.getElementById("reg_lastvisit") != null)
			document.getElementById("reg_lastvisit").innerHTML = userinfo.usr_lastvisit;
	}
	
	function OnClickChangeUserData()
	{
		// Recover fields
		var name = document.getElementById("reg_name").value;
		
// 		var day = document.getElementById("dropDownDay").value;
// 		var month = document.getElementById("dropDownMonth").value;
// 		var year = document.getElementById("dropDownYear").value;
// 		var birthdate = year + "-" + month + "-" + day;

		// var birthdate = new Date();
// 		birthdate.setFullYear(year,month,day);
// 		alert(birthdate);

		if(!name) // || !day || !month || !year)
		{
			alert('Debe rellenar todos los campos');
			$("mod_fullnameOK").hide(); $("mod_fullnameERROR").show();
			return;
		}
		
		// fill AJAX request
		new Ajax.Request('http://'+location.host+'/server/auth.php', 
		{
			method:'post',
			parameters:
			{
				func: 'saveUSR', 
				usr_fullname: name
				//usr_birthdate: birthdate
			},
			onSuccess: function(transport) 
			{
				// Client successfully registered
				if(transport && transport.responseText.length > 0)
				{ 
					retVal = transport.responseText.evalJSON();
					
					// Load the client area if user exists
					if(retVal == 'OK')
					{
						// Hide or Show alerts and ok images
						$("mod_fullnameOK").show(); $("mod_fullnameERROR").hide();
						//$("mod_birthdateOK").show(); $("mod_birthdateERROR").hide();
					}
					else
					{
						//TODO panel de usuario incorrecto validaciones personalizadas
						alert('Error al actualizar los datos');
						$("mod_fullnameOK").hide(); $("mod_fullnameERROR").show();
						//$("mod_birthdateOK").hide(); $("mod_birthdateERROR").show();
					}
				}
				else
				  alert('Error al actualizar los datos');
			},
			onFailure: function()
			{
				alert('Error al actualizar los datos');
			}
		});
	}
	
	function OnClickChangeUserPass()
	{
	
		// Recover fields
		var old_password = document.getElementById("old_passwd").value;
		var new_password1 = document.getElementById("new_passwd1").value;
		var new_password2 = document.getElementById("new_passwd2").value;
		
		if(!old_password || !new_password1 || !new_password2)
		{
			alert('Debe rellenar todos los campos');
			if(!new_password1)
				$("mod_password2OK").hide(); $("mod_password2ERROR").show();
			if(!new_password2)
				$("mod_password3OK").hide(); $("mod_password3ERROR").show();
			return;
		}
		if(new_password1 != new_password2)
		{
			alert('La nueva contraseña no coincide');
			$("mod_password2OK").hide(); $("mod_password2ERROR").show();
			$("mod_password3OK").hide(); $("mod_password3ERROR").show();
			return;
		}
		
		// fill AJAX request
		new Ajax.Request('http://'+location.host+'/server/auth.php', 
		{
			method:'post',
			parameters:
			{
				func: 'saveUSRpwd', 
				usr_oldpassword: old_password,
				usr_password: new_password1
			},
			onSuccess: function(transport) 
			{
				// Client successfully registered
				if(transport && transport.responseText.length > 0)
				{
					retVal = transport.responseText.evalJSON();
					
					// Load the client area if user exists
					if(retVal == 'OK')
					{				
						// Hide or Show alerts and ok images
						$("mod_password2OK").show(); $("mod_password2ERROR").hide();
						$("mod_password3OK").show(); $("mod_password3ERROR").hide();
					}
					else if(retVal == 'WRONG_PASSWD')
					{
						//TODO panel de usuario incorrecto validaciones personalizadas
						alert("Problemas al actualizar la información");
						$("mod_password2OK").hide(); $("mod_password2ERROR").show();
						$("mod_password3OK").hide(); $("mod_password3ERROR").show();
					}
				}
				else
				{
					alert('Error al actualizar la contraseña');
				}
			},
			onFailure: function()
			{
				alert('Error al actualizar la contraseña');
			}
		});		
	}
	</script>

	<!-- Global Footer --> 
	<?php include 'footer.php'; ?>	