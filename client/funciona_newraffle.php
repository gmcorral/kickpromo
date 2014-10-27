<?php
	require_once('../server/session.php');
?> 


<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" lang="en"> <!--<![endif]-->

<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <link rel="stylesheet" href="./css/normalize.css">
  <link rel="stylesheet" href="./css/foundation.css">

  <script src="./js/vendor/custom.modernizr.js"></script>
  <!--<script src="./js/test.js" type="text/javascript"></script>-->
</head>

  <script>
      document.write('<script src=./js/vendor/'
        + ('__proto__' in {} ? 'zepto' : 'jquery')
        + '.js><\/script>');
    </script>

  <title>KickPromo</title>

  <link rel="stylesheet" href="./css/normalize.css">
  <script src="./js/foundation/foundation.js"></script>
  <script src="./js/foundation/foundation.section.js"></script>
  <script src="./js/js_simple_accordion.js"></script>
  <script src="./js/vendor/custom.modernizr.js"></script>
  
  <!-- Other JS plugins can be included here -->

  <script>
    $(document).foundation();
  </script>



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
		<div class="small-3 columns">
			<a href="index.php"><img src="./img/logo.png"/></a>
		</div>

		<?php
		// If user is not logged, show log in form
		if(!isLogged()){ ?>
		<!-- Log in form -->
		<div class="small-6 large-6 columns" id="usr_login">
		  <div class="row" id="login">
			<div class="small-5 large-4 columns">
			  <input type="text" id="usr_email" placeholder="Correo">  
			</div>

			<div class="small-5 large-4 columns">
			  <input type="password" id="usr_passwd" placeholder="Contraseña">
			</div>

			<div class="small-2 large-2 columns">
			  <a onClick="OnClickLogin()" class="small button">Entrar</a>
			</div>

			<div class="large-offset-2" > </div>                    
		  </div>
		</div>
		<?php
		// If user is logged, show user info
		} else { ?>	
		<!-- Logged user -->
		<div class="small-6 large-6 columns" id="usr_logged">
			<div class="row" id="login">

				<div class="small-3 large-4 columns">
					Hola, <label id="info_email"></label>
				</div>

				<div class="small-6 large-8 columns" id="lista1">
					<ul class="right button-group">
						<li id="el1"><a href="./ca_user.php" class="small button">Perfil</a></li>
						<li><a href="./ca_raffles.php" class="small button">Promociones</a></li>
						<li><a href="index.php?logout=true" class="small button">Cerrar Sesión</a></li>
					</ul>
				</div>          
			</div>
		</div> 
		<?php } ?>

		<!-- Main Menu -->
		<?php
		// If user is logged in, don´t show "comienza ya" tag
		if(isLogged()){ ?>
		<div class="small-3 large-3 columns" id="lado_dcho">
			<div class="row">
				<ul class="right button-group">
					<li><a href="tutorial.php" class="button">Tutorial</a></li>
				</ul>
			</div>
		</div>
		<?php } else{ ?>
		<div class="small-3 large-4 columns" id="lado_dcho">
			<div class="row">
				<ul class="right button-group">
					<li><a href="getstarted.php" class="button">¡Comienza Ya!</a></li>
					<li><a href="tutorial.php" class="button">Tutorial</a></li>
				</ul>
			</div>
		</div>
		<?php } ?>
	</div>
<!-- End Header and Nav -->

<!-- Initialize components -->
<script>
	window.onload = initializePage;
	
	function initializePage()
	{
	
		// fill AJAX request
		new Ajax.Request('http://'+location.host+'/server/auth.php', 
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
					// Get user info
					retVal = transport.responseText.evalJSON(true);
					
					document.getElementById("info_email").innerHTML = retVal.usr_email;
					
					//TODO if(session_error)
					
					// Load User Info
					if (document.getElementById("reg_email") != null)
						document.getElementById("reg_email").innerHTML = retVal.usr_email;
						
					if (document.getElementById("reg_name") != null)
						document.getElementById("reg_name").value = retVal.usr_fullname;

// 					if (document.getElementById("reg_birthdate") != null)
// 						document.getElementById("reg_birthdate").value = retVal.usr_birthdate;						
						
					if (document.getElementById("reg_lastvisit") != null)
						document.getElementById("reg_lastvisit").innerHTML = retVal.usr_lastvisit;

					// Hide alerts
					$("mod_fullnameOK").hide(); $("mod_fullnameERROR").hide();
					//$("mod_birthdateOK").hide(); $("mod_birthdateERROR").hide();
					$("mod_password2OK").hide(); $("mod_password2ERROR").hide();
					$("mod_password3OK").hide(); $("mod_password3ERROR").hide();
				}
				else
				  alert('Error al cargar los datos del usuario');
			},
			onFailure: function()
			{
				alert('Error al cargar los datos del usuario');
			}
		});	
	}
	
	function OnClickLogin()
	{
		// Recover fields
		var correo = document.getElementById("usr_email").value;
		var clave = document.getElementById("usr_passwd").value;

		// fill AJAX request
		new Ajax.Request('http://'+location.host+'/server/auth.php', 
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
					// Load the client area if user exists
					if(transport.responseText.strip() != '"WRONG_USR_PASSWD"')				
						window.location.href = 'http://'+location.host+'/client/ca_raffles.php';
					else //TODO panel de usuario incorrecto
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

</script>

	 <style type="text/css">
	 
			/*   STYLES.CSS   */
			 


a {
	color: #6a9e2e;
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
}

.first-p {
	margin-top: 0;
}

.last-p {
	margin-bottom: 0;
}

.code {
	background: #ebebeb;
	border: 1px solid #cccccc;
	padding: 10px;
	font-size: 10px;
	color: #333333;
}

#wrapper {
	width: 400px;
	margin: 20px auto;
}

#header {
	padding: 5px 20px;
	background: #333333;
	color: #cccccc;
	position: relative;
	margin: 0 0 20px 0;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
}

#header h1 {
	margin: 0;
	padding: 5px;
	font-size: 28px;
	font-weight: normal;
	line-height: 28px;
	text-align:center;
}

#header p {
	margin: 5px;
	font-weight: bold;
	font-size: 14px;
	text-align:center;
}

.gen_info {
    width: 200px;  height: 50px; 
    display:table-cell; 
    text-align:center;
    font-size: 17px;
    font-weight: bold;
}

.form {
    width: 400px;  height: auto; 
    display:table-cell; 
    font-size: 14px;
    font-weight: bold;
    text-align:center;
}

hr {
  border: 0 none;
  height: 1px;
  background-color: #CCC;
  color: #CCC;
}


label {
  float:left;
  width:50%;
  margin-right:0.5em;
  padding-top:0.2em;
  text-align:right;
  font-weight:bold;
  font-size: 14px;
  }

.submit input
{
margin-left: 4.5em;
} 


/*   DROPDOWN LAYER */


.table_margin{
    padding-top:10px;
}


div.add {
    display: inline-block;
    width: 120px;
    font-size: 17px;
    font-weight: bold;
    padding-right:10px;
}
div.submenu {
    position:absolute;
    display: inline-block;
    border:1px solid #CCC;
    background:#FFF;
    padding-left:10px;
    padding-right:10px;
    -moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	font-size: 17px;
}
.visible {
    opacity: 1;
    transition: opacity 0.1s linear;
}
.hidden {
    visibility: hidden;
    opacity: 0;
    transition: visibility 0s 0.1s, opacity 0.1s linear;
}
.hid { 
  display: none; 
}

/*   Para el delete onhover onmouseover   */

div.dele {
    display: inline-block;
    width:100%;
}
div.delesub {
    position:absolute;
    display: inline-block;
    margin-left:380px;
    padding-top:13px;
	font-size: 17px;
}




/*   VALLENATO.CSS   */

#accordion-container {
	font-size: 13px;
	background: #ffffff;
	padding: 5px 10px 10px 10px;
	border: 1px solid #cccccc;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	-moz-box-shadow: 0 5px 15px #cccccc;
	-webkit-box-shadow: 0 5px 15px #cccccc;
	box-shadow: 0 5px 15px #cccccc;
}

.accordion-header {
	font-size: 16px;
	background: #ebebeb;
	margin: 5px 0 0 0;
	padding: 5px 20px;
	border: 1px solid #cccccc;
	cursor: pointer;
	color: #666666;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
}

.active-header {
	-moz-border-radius: 5px 5px 0 0;
	-webkit-border-radius: 5px 5px 0 0;
	border-radius: 5px 5px 0 0;
	background: url(./img/active-header.gif) #cef98d;
	background-repeat: no-repeat;
	background-position: right 50%;
}

.active-header:hover {
	background: url(./img/active-header.gif) #c6f089;
	background-repeat: no-repeat;
	background-position: right 50%;
}

.inactive-header {
	background: url(./img/inactive-header.gif) #ebebeb;
	background-repeat: no-repeat;
	background-position: right 50%;
}

.inactive-header:hover {
	background: url(./img/inactive-header.gif) #f5f5f5;
	background-repeat: no-repeat;
	background-position: right 50%;
}

.accordion-content {
	display: none;
	padding: 20px;
	background: #ffffff;
	border: 1px solid #cccccc;
	border-top: 0;
	-moz-border-radius: 0 0 5px 5px;
	-webkit-border-radius: 0 0 5px 5px;
	border-radius: 0 0 5px 5px;
}


	  </style> 


  
<!--	<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script> -->
	
	<script type='text/javascript' src='http://code.jquery.com/jquery-1.4.3.min.js'></script>
	


	

<script type='text/javascript'>//<![CDATA[ 
$(window).ready(function(){
$(function() {
        var scntDiv = $('#p_scents');
        var i = $('#p_scents p').size() + 1;
        
        $('#fbp').live('click', function() {
                $('<div class="dele">'+
                '<div class="delesub"><a href="#" id="remScnt"><img src="./img/ico_check_error_16.png" alt="Delete">Borrar</a></div>'+
                '<h2 class="accordion-header">Facebook post</h2>'+
			    '<div class="accordion-content ">'+
			    '<p class="form">What do you want the people to post</p>'+
				'  <textarea id="text1" style="width: 335px; height: 50px;" placeholder="Example: If you want to organice good contests please visit www.kickpromo.com" ></textarea>'+
			    '<a href="#" id="remScnt">Borrar</a>'+
			    '</div>'+
			    '</div>').appendTo(scntDiv);
                i++;
                accord();
                return false;
        });
        
        $('#fbl').live('click', function() {
                $('<h2 class="accordion-header">Facebook like</h2>'+
			    '<div class="accordion-content">'+
			    '<label>Facebook page to like</label>'+
				'<input type="text" id="pro_name" placeholder="Example: www.facebook.com/kickpromo">'+
			    '</div>').appendTo(scntDiv);
                i++;
                accord();
                return false;
        });
        
        $('#twf').live('click', function() {
                $('<h2 class="accordion-header">Twitter follow</h2>'+
			    '<div class="accordion-content">'+
			    '<label>Twitter user to follow</label>'+
				'<input type="text" id="pro_name" placeholder="Example: @kickpromo">'+
			    '</div>').appendTo(scntDiv);
                i++;
                accord();
                return false;
        });
        
        $('#twt').live('click', function() {
                $('<h2 class="accordion-header">Twitter twit</h2>'+
			    '<div class="accordion-content">'+
			    '<p class="form">What do you want the people to twitt</p>'+
				'  <textarea id="text1" style="width: 335px; height: 50px;" placeholder="Example: If you want to organice good contests please visit www.kickpromo.com" ></textarea>'+
			    '</div>').appendTo(scntDiv);
                i++;
                accord();
                return false;
        });

        $('#com').live('click', function() {
                $('<h2 class="accordion-header">Comments</h2>'+
			    '<div class="accordion-content">'+
			    '<label>Leave a comment in </label>'+
				'<input type="text" id="pro_name" placeholder="Example: yourblog.com/particular_post">'+
				'</div>').appendTo(scntDiv);
                i++;
                accord();
                return false;
        });

        $('#txt').live('click', function() {
                $('<h2 class="accordion-header">Send a text</h2>'+
			    '<div class="accordion-content">'+
			    '<p class="form">Instructions for what the people can send</p>'+
				'  <textarea id="text1" style="width: 335px; height: 50px;" placeholder="Example: Please leave a feedback of what you think about kickpromo" ></textarea>'+
			    '</div>').appendTo(scntDiv);
                i++;
                accord();
                return false;
        });

        $('#fil').live('click', function() {
                $('<h2 class="accordion-header">Send a file</h2>'+
			    '<div class="accordion-content">'+
			    '<p class="form">Instructions for what the people can send</p>'+
				'  <textarea id="text1" style="width: 335px; height: 50px;" placeholder="Example: Please send a presentation/video/audio of something you like" ></textarea>'+
			    '</div>').appendTo(scntDiv);
                i++;
                accord();
                return false;
        });

        $('#fre').live('click', function() {
                $('<h2 class="accordion-header">Free action</h2>'+
			    '<div class="accordion-content">'+
			    '<label>Title of the action</label>'+
				'<input type="text" id="pro_name" placeholder="Example: Go to kickpromo presentation"><br><br>'+
			    '<p class="form">Instructions related to the action</p>'+
				'  <textarea id="text1" style="width: 335px; height: 50px;" placeholder="Example: Next XXth of XX we invite you to join the anual kickpromo celebration" ></textarea>'+
			    '</div>').appendTo(scntDiv);
                i++;
                accord();
                return false;
        });
        
        
        $('#remScnt').live('click', function() { 
                if( i > 1 ) {
                        $(this).parent().parent().remove();
                        i--;
                }
                return false;
        });
});
accord();
});//]]>  

//drag and nest div to give a different order http://www.html5rocks.com/en/tutorials/dnd/basics/ tal vez tambien se pueda con append
</script>



<script type="text/javascript"> 
// It hides/shows the user interface
$(document).ready(function () {
$('a#admin').click(function () {
$(".hid").removeClass("hid")
});

$('a').click(function(e)
{
    // Cancel on click jumping to the top
    e.preventDefault();
});
});
</script>

<script type="text/javascript"> 

$(document).ready(function () {
$('div#submenu').addClass('hidden');

    $('a#add').click(function () {
    
     if($('div#submenu').is('.visible')) {
        $('div#submenu').addClass('hidden').removeClass('visible');
        
        
        } else {
        $('div#submenu').removeClass('hidden').addClass('visible');
    }
    });

var submenu_active = false;
 
$('div#submenu').mouseenter(function() {
    submenu_active = true;
});
 
$('div#submenu').mouseleave(function() {
    submenu_active = false;
    setTimeout(function() { if (submenu_active === false) $('div#submenu').addClass('hidden').removeClass('visible');; }, 400);
});

$('div#submenu').click(function () {
$('div#submenu').addClass('hidden').removeClass('visible');
});



});


</script>

<script type="text/javascript">
/*!
 * Vallenato 1.0
 * A Simple JQuery Accordion
 *
 * Designed by Switchroyale
 * 
 * Use Vallenato for whatever you want, enjoy!
 */

//another example of accordion http://www.stemkoski.com/stupid-simple-jquery-accordion-menu/


$(document).ready(function()
{ 

 }); 
	
	function accord() { 
	
	//cuenta las veces que una function ha sido llamada
    this.num = (this.num || 0) + 1;
	
	//segun la funcion haya sido llamada una o varias veces

	if (this.num < 2) {
	  //Add Inactive Class To All Accordion Headers
	  $('.accordion-header').toggleClass('inactive-header');
	
	  //Set The Accordion Content Width
	  var contentwidth = $('.accordion-header').width();
	  $('.accordion-content').css({'width' : contentwidth });
	
	  //Open The First Accordion Section When Page Loads
	  $('.accordion-header').first().toggleClass('active-header').toggleClass('inactive-header'); //si se pone el tonggleclass una vez se añade, dos se borra
	  $('.accordion-content').first().slideDown().toggleClass('open-content');
	
	  // The Accordion Effect
	  // Si el boton esta inactivo pasa la primera funcion y despliega el contenido
	  $('.accordion-header').click(function () {
		if($(this).is('.inactive-header')) {
			$('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
			$(this).toggleClass('active-header').toggleClass('inactive-header');
			$(this).next().slideToggle().toggleClass('open-content');
		}
		// Si el boton esta activo pliega el contenido
		else {
			$(this).toggleClass('active-header').toggleClass('inactive-header');
			$(this).next().slideToggle().toggleClass('open-content');
		}
	  });
	} 
	else {
	    //aqui poner que se active
	
	  $('.accordion-header.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
      $('.accordion-header').last().toggleClass('active-header');
      $('.accordion-content').last().slideDown().toggleClass('open-content');

        //con esto conseguimos que la funccion click no se repita por cada elemento añadadido
      $('.accordion-header').unbind("click");


        //falta que funcione onclick
	  $('.accordion-header').click(function () {
		if($(this).is('.inactive-header')) {
			$('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
			$(this).toggleClass('active-header').toggleClass('inactive-header');
			$(this).next().slideToggle().toggleClass('open-content');
		}
		// Si el boton esta activo pliega el contenido
		else {
			$(this).toggleClass('active-header').toggleClass('inactive-header');
			$(this).next().slideToggle().toggleClass('open-content');
		}
	  });        
        
        
	  
	}


	return false;
    }





	</script>  

<script type="text/javascript"> 

$(function() {
    $('.accordion-header').hover(function () {
         $( ".delesub" ).show();
    }, function() { 
        setTimeout(function() { $( ".delesub" ).hide(); }, 400); 
    });
});





</script>


	  
</head>


<body>




<div class="row">   
	<h4>Administración de Promociones</h4>

	<div class="row">
		
		<!-- Client Area -->
		<div class="small-9 large-9 columns">
			<div class="section-container auto" data-section="auto">

				<!-- Nueva promoción -->
				<div class="section active">
					<p class="title" data-section-title><a href="#panel1">Nueva promo</a></p>

					<div class="content" data-section-content>					
						<!-- Create new promo -->
						        <a href="#" id="admin" class="admin">Modo user</a><br/>
        <a href="#" id="user">Modo admin</a><br/>
        
    
        
	<div id="wrapper">  <!-- aqui esta el error para que no funcione el accordion-->
		<div id="header" class="hid">
			<h1><strong>Participa para ganar un "Nombre premio"</strong></h1>
			<p>Patrocinado por "Nombre"</p>
		</div>
		
	    <!--
			Contest info
		-->
		

    <div id="accordion-container" class="hid">
	  <div  class="gen_info">Quedan 11h 20 min</div>
      <div  class="gen_info">Hay XX participantes</div>
      <div  class="gen_info">Hay XX participantes</div>

</div>



<div id="accordion-container">
<form action="#">
<p><label for="name">Cuál será el premio?</label> <input type="text" id="name" placeholder="Example: iPad Air" /></p>
<p><label for="name">Cuántos premios hay? </label> <select id="customDropdown1" ><option selected DISABLED>Cantidad</option><option>1</option><option>2</option><option>3</option></select></p>
<p><label for="name">Quien lo promociona?</label> <input type="text" id="name" placeholder="Nombre" /></p>
<p><label for="e-mail">Nombre de la promoción?</label> <input type="text" id="e-mail" placeholder="Example: Promo 1"/></p>

		<!--
			Codigos para un datepickr
			http://code.google.com/p/datepickr/downloads/detail?name=datepickr_2.1.zip&can=2&q=
			http://www.nsftools.com/tips/DatePickerTest.htm
			http://www.electricprism.com/aeron/calendar/#download
			http://code.google.com/p/datepickr/downloads/detail?name=datepickr_2.1.zip&can=2&q=
		-->

<p><label for="e-mail">Fecha de inicio</label> <input type="text" id="e-mail" placeholder="01 Oct 2013"/></p>
<p><label for="e-mail">Fecha de fin</label> <input type="text" id="e-mail" placeholder="01 No 2013"/></p>
</form> 

	</div>	
	
		<!--
			Start Accordion
		-->
		
		<div id="accordion-container">

			
			<div id="p_scents">
           </div>
		
<!--
           <h2><a href="#" id="link">+ New action</a></h2>
 
           <div class="inline" id="submenu">
             <a href="#" id="addScnt">Add Facebook Post</a><br />
             <a href="#">Careers</a>
           </div>
-->           
	       
	       
	        

	  <div class="table_margin">
           
  <div class="add"> <a href="#" id="add">+ New action</a>

    </div>
    <div class="submenu" id="submenu">
        <a href="#" id="fbp">Facebook Post</a><br/>
        <a href="#" id="fbl">Facebook like</a><br/>
        <a href="#" id="twf">Twitter follow</a><br/>
        <a href="#" id="twt">Twitter twit</a><br/>
        <a href="#" id="com">Comments</a><br/>
        <a href="#" id="txt">Send a text</a><br/>
        <a href="#" id="fil">Send a file</a><br/>
        <a href="#" id="fre">Free action</a>

    </div>
      </div>
      
  		</div>    

		
		<!--
			End Accordion
		-->
		
	</div>				
					</div>
				</div>
				
				<!-- Gestión de promos -->
				<div data-section-region class="section">
					<p class="title" data-section-title><a href="#panel1">Gestión de promos</a></p>

					<div class="content" data-section-content>	
						<!-- Admin promos -->
						<?php include 'ca_adminraffles.php'; ?>  
					</div>
				</div>		
			</div> 		
		</div>
		
		<!-- Tips -->
		<div class="small-3 large-3 columns">
			<h4 class="text-center">Tips</h4>
			<p>Has probado a echar un ojo en tutorial, nos hemos esforzado en que esté todo claro.</p>
			<p>Trataremos de contestarte lo antes posible. </p>							
		</div>
	</div>
	
	
	
	

	




</body>
</html>









