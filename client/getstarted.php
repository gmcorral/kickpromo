<?php
	//session_start();
	include 'header.php';	
?> 

<!-- Call to Action Panel -->
<div class="row"> 
	<div class="medium-11 small-11 large-12 small-centered columns"> 
		<div class="medium-8 columns panel">
		  <h4>Tu promoción en sólo dos pasos</h4>
	  
		  <form data-abide>
		  
			  <div class="medium-12 columns">
				<img src="./img/uno.png" alt="Primer paso">Créate una cuenta para poder gestionar tus promociones
			  </div>
		
				<!-- Field 1 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Correo*</label>
				</div>
				<div class="medium-7 small-8 columns ">
				  <input type="text" id="new_email" placeholder="Correo electrónico" required pattern="email">
				  <small class="error">This is required</small>
				</div>
				<div class="large-offset-2 small-offset-3 columns">
				</div>
			
				<!-- Field 2 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Contraseña*</label>
				</div>
				<div class="medium-7 small-8 columns">
				  <input type="password" id="new_passwd1" placeholder="Contraseña" required pattern="alpha_numeric">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>
			
				<!-- Field 3 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline"></label>
				</div>
				<div class="medium-7 small-8 columns">
				  <input type="password" id="new_passwd2" placeholder="Repite la contraseña" required pattern="alpha_numeric" data-equalto="new_passwd1">
				  <small class="error">Password doesn't match, please check</small>
				</div>
				<div class="large-offset-2 small-offset-3 columns">
				</div>
			
			
			  <div class="medium-12 columns">
				<img src="./img/dos.png" alt="Segundo paso">Configura tu primera promoción
			  </div>
		
				<!-- Field 4 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Promoción</label>
				</div>
				<div class="medium-7 small-8 columns ">
				  <input type="text" id="pro_name" placeholder="Nombre de la promoción" pattern="alpha_numeric">
				  <small class="error">This is required</small>
				</div>
				<div class="large-offset-2 small-offset-3 columns">
				</div>
			
				<!-- Field 5 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Promotor</label>
				</div>
				<div class="medium-7 small-8 columns">
				  <input type="password" id="pro_owner" placeholder="Quién ofrece la promoción (nombre, blog, empresa,..)" pattern="alpha_numeric">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>
				<div class="medium-12 medium-offset-3 columns">
				  <button type="submit" onclick="OnClickSignup()">Acceder</button>
				</div>
			
		</form>
		</div>  
		<div class="medium-4 columns text-center">
			<div class="large-1 columns text-center">
			</div>
			<div class="large-11 columns right text-center">
				<h4>Tips</h4>
				  <hr>
				  <p >Es importante que elijas un buen nombre para tu promoción.</p>
				  <hr>
				  <p>Elige cuantos premios/ganadores quieres tener.</p>
				  <hr>
				  <p>Párate un momento a mirar las condiciones de tu promoción. <hr>
			</div>
		</div>	
		</div>
	</div>

<!-- Global Footer --> 
<?php include 'footer.php'; ?>

<script>

  function OnClickSignup()
  {

    // cuenta
    var nombre = ""; //document.getElementById("usr_fullname").value;
    var correo = document.getElementById("new_email").value;
    var clave1 = document.getElementById("new_passwd1").value;
    var clave2 = document.getElementById("new_passwd2").value;
    var nombrePromo = document.getElementById("pro_name").value;
    var promotor = document.getElementById("pro_owner").value;
    
	if(!correo || !clave1 || !clave2)
	{
		alert('Debes rellenar los datos de la cuenta');
		return;
	}
	if(clave1 != clave2)
	{
		alert('Las contraseñas no coinciden');
		return;
	}
	
	// fill AJAX request
	new Ajax.Request('http://'+location.host+'/server/auth.php', 
	{
	  method:'post',
	  parameters:
	  {
		func: 'newUSR', 
		usr_email: correo,
		usr_passwd: clave1,
		usr_fullname: nombre,
		usr_timezone: '+2',
		gaw_name: nombrePromo,
		gaw_owner: promotor
	  },
	  onSuccess: function(transport) 
	  {
		if(transport && transport.responseText.length > 0)
		{
		  retVal = transport.responseText.evalJSON();
		  if(retVal == 'USER_EXISTS')
		  {
		  	alert('Oooops, el nombre de usuario que has elegido ya existe, ¡prueba con otro!');
		  }
		  else
		  {
		  	alert('¡Enhorabuena, te has registrado en KickPromo! ');
		  	
		  	if(retVal == 'OK')
		  	{
			  	window.location.href = 'http://'+location.host+'/client/ca_raffles.php';
		  	}
		  	else
		  	{
		  	    // if new GAW is created, edit it
		  		form = document.createElement('form');
        		form.setAttribute('method', 'POST');
        		form.setAttribute('action', 'http://'+location.host+'/client/ca_newraffle.php');
        		myvar = document.createElement('input');
        		myvar.setAttribute('name', 'edit');
        		myvar.setAttribute('type', 'hidden');
        		myvar.setAttribute('value', retVal);
        		form.appendChild(myvar);
        		document.body.appendChild(form);
        		form.submit();
        	}
		  }
		}
		else
		  alert('Error al dar de alta el usuario');
		},
	  onFailure: function()
	  {
		alert('Error al dar de alta el usuario');
	  }
	});
  }

</script>

