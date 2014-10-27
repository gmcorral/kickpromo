<script src="./js/moment-with-langs.js" type="text/javascript"></script>

<link rel="stylesheet" href="./css/normalize.css">
<link rel="stylesheet" href="./css/foundation.css">
<link rel="stylesheet" href="./css/widget.css">

<script src="./js/vendor/custom.modernizr.js"></script>
<script src="./js/vendor/jquery.js"></script>
<script src="./js/widget.js"></script>

<div id="fb-root"></div>

<div id="wrapper1">
	
	<!-- Info promo -->
	<div class="panel callout radius" style="text-align:center;">
		<!-- gaw name -->
		<h3><strong>Promoción "<span id="lb_gaw_name"></span>"</strong></h3>		
		
		<!-- gaw prizes -->
		<h5><strong>Participa para poder ganar</strong></h5>
		
		<div class="gaw_prizes row"></div>
		
		<h6>Patrocinado por <span id="lb_gaw_owner"></span></h6>		
	</div>	
	
	<!-- Participants and time -->
	<div id="accordion-container" class="panel callout radius">
		<ul class="small-block-grid-2">
			<li>Finaliza <span id="lb_timeleft"></span></li>
			<li>Hay <span id="lb_participants">XX</span> participantes</li>
		</ul>
	</div>

	<!-- User registration -->
	<div id="accordion-container" class="panel radius">		
		<div class="row" >
			
			
		
			
					
			<!-- Award -->
			<div class="small-5 large-5 columns">
				<label for="left-label" class="right inline">Tu nombre</label>
			</div>
				
			<div class="small-3 large-7 columns">
				<input type="text" id="rf_award" placeholder="Nombre y apellido">
			</div>  
			
			<div class="large-offset-2 columns"></div>

			<!-- Owner -->
			<div class="small-3 large-5 columns">
				<label for="left-label" class="right inline">Tu email</label>
			</div>
			
			<div class="small-3 large-7 columns">
				<input type="text" id="rf_owner" placeholder="ejem@plo.es">
			</div> 								
			
			<div class="large-offset-2 columns"></div>	
			
			<a href="#" class="button [ tiny ] right">Save!</a><br>			

		</div>		
	</div>	
	
	<!-- Actions -->	
	<div id="accordion-container" class="gaw_options">
	    <div id="p_scents"></div>		
	</div>  
</div>

<script>

	window.onload = getUrlVars;
	
	var gawInfo = new Object();
	
	// Leer los datos GET de nuestra pagina y devolver un array asociativo (Nombre de la variable GET => Valor de la variable). 
	function getUrlVars() 
	{ 		
		var str = window.location.href.slice(window.location.href.indexOf('?') + 1); 
		var param = str.split('=');
		var value = param[1];	

		getGawCode(param[1]);
	}

	function getGawCode(gaw_id)
	{
//		// fill AJAX request
//		new Ajax.Request('http://'+location.host+'/server/run.php', 
//		{
//			method:'post',
//			parameters:
//			{
//				func: 'loadGAW',
//				gat_widgetkey: gaw_id
//			},
//			onSuccess: function(transport) 
//			{
//				// Gaw successfully loaded
//				if(transport && transport.responseText.length > 0)
//				{
//					if(transport.responseText.strip() == '"KEY_NOT_FOUND"')				
//					{
//						alert("KEY_NOT_FOUND");
//					}
//					else if(transport.responseText.strip() == '"SESSION_ERROR"')	
//					{
//						alert("SESSION_ERROR"); 															
//					}
//					else if(transport.responseText.strip() == '"OK"')
//					{						
//						// Get the gaw´s array
//						gawInfo = transport.responseText.evalJSON(true);

						// info personal de la promo
						var giveway = new Object();
						giveway.gaw_name = "nombre gaw";
						giveway.gaw_owner = "jander´s club";
						giveway.gaw_endtime = "2014-03-31";
						giveway.gaw_terms = "asdfasfasf";
						giveway.gaw_description = "asdfasdfasdfasfd";
						
						// info de los premios
						var prize = new Array();
						
							// premio1
							var prize1 = new Object();
							prize1.pri_name = "premio 1";
							prize1.pri_quantity = 3;
							
							var prize2 = new Object();
							prize2.pri_name = "premio 2";
							prize2.pri_quantity = 34;
							
						prize[0] = prize1;
						prize[1] = prize2;

						// info de las opciones
						var option = new Array();
						
							// opcion 1 (fb post)
							var option1 = new Object();
							option1.opt_otyid = "fbl";
							option1.opt_points = 23;
							option1.ovl_value = "http://www.google.es";
							
							// opcion 2 (fb like)
							var option2 = new Object();
							option2.opt_otyid = "twf";
							option2.opt_points = 12;
							option2.ovl_value = "janderfm";

							// opcion 2 (fb like)
							var option3 = new Object();
							option3.opt_otyid = "mli";
							option3.opt_points = 4;
							option3.ovl_value = "www.google.es";

							// opcion 2 (fb like)
							var option4 = new Object();
							option4.opt_otyid = "com";
							option4.opt_points = 2;
							option4.ovl_value = "www.google.es";
							
							// opcion 2 (fb like)
							var option5 = new Object();
							option5.opt_otyid = "txt";
							option5.opt_points = 12;
							option5.ovl_value = "Danos tu opinión sobre KickPromo, mola o qué?";
							
							var option6 = new Object();
							option6.opt_otyid = "fbp";
							option6.opt_points = 9;
							option6.ovl_value = "www.kickpromo.es";
							
							var option7 = new Object();
							option7.opt_otyid = "twt";
							option7.opt_points = 9;
							option7.ovl_value = "www.kickpromo.es";							

							var option8 = new Object();
							option8.opt_otyid = "fre";
							option8.opt_points = 9;
							option8.ovl_value = "blablabla blabla bla lorem ipsum etc y eso";	
							
						option[0] = option1;
						option[1] = option2;
						option[2] = option3;
						option[3] = option4;
						option[4] = option5;
						option[5] = option6;
						option[6] = option7;
						option[7] = option8;
				
						gawInfo.giveway = giveway;
						gawInfo.prize = prize;
						gawInfo.option = option;

						loadGawInfo(gawInfo);
//					}
//				}
//				else
//				  alert('Error al iniciar sesión');
//			},
//			onFailure: function()
//			{
//				alert('Error al iniciar sesión');
//			}
//		});			
	}

jQuery.noConflict();

var i = 0;
var j = 0;
var gawID = -1;

jQuery(window).ready(function()
{
	i = jQuery('#p_scents p').size() + 1;
	accord();
});

	
	function loadGawInfo(gawInfo)
	{
		// Load gaw info
		if(gawInfo != null)
		{
			if(gawInfo.giveway != null)
			{		
				document.getElementById("lb_gaw_name").innerHTML = gawInfo.giveway.gaw_name;
				document.getElementById("lb_gaw_owner").innerHTML = gawInfo.giveway.gaw_owner;
				document.getElementById("lb_timeleft").innerHTML = getTime(gawInfo.giveway.gaw_endtime);
			}
			
			// Load gaw´s prizes
			if(gawInfo.prize != null)
			{
				var prizes = '<table style="margin:0 auto;">';

				for(var i = 0; i < gawInfo.prize.length; i++)
				{
					// add element
					prizes += 	'<tr>' +
									'<td width="200">'+ gawInfo.prize[i].pri_name +'</td>' +
									'<td>'+ gawInfo.prize[i].pri_quantity +'</td>' +
								'</tr>';
				}
				
				prizes += '</table>';

				jQuery(prizes).appendTo(".gaw_prizes");				
			}
			
			// Load gaw´s options
			if(gawInfo.option != null)
			{
				for(var i = 0; i < gawInfo.option.length; i++)
				{
					addOptionWidget(gawInfo.option[i]);
				}
			}
		}
	}
	
	function addOptionWidget(option)
	{		
		switch(option.opt_otyid)
		{		
			case "fbp": var label = '<label style="padding-top:5px;">Compártelo en tu muro!</label>'+
									'<div class="panel radius" style="margin-right:15px;">'+
										'<label>'+option.ovl_value+'</label>'+
									'</div>'+	
									'<div class="fb-share-button" data-href="'+option.ovl_value+'" data-type="button_count"></div>';
			
						buildOptionWidget(option, 'Publicación en Facebook', label);
						
						break;
						
			case "fbl": var label = '<label style="padding-top:5px;">Dale "Me gusta" a</label>'+
									'<div class="panel radius" style="margin-right:15px;">'+
										'<label>'+option.ovl_value+'</label>'+
									'</div>'+
									'<div class="fb-like" data-href="'+option.ovl_value+'" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>';

						buildOptionWidget(option, 'Me gusta en Facebook', label);
						
						break;
			
			case "twf": var label = '<label style="padding-top:5px;">Sigue en Twitter a</label>'+
									'<div class="panel radius" style="margin-right:15px;">'+
										'<label>'+option.ovl_value+'</label>'+
									'</div>'+
									'<iframe allowtransparency="true" frameborder="0" scrolling="no" '+
										'src="//platform.twitter.com/widgets/follow_button.html?screen_name='+option.ovl_value+'" style="width:300px;'+
										'height:20px;"></iframe>';
										
						buildOptionWidget(option, 'Seguir en Twitter', label);
						
						break;
				
			case "twt": var label = '<label style="padding-top:5px;">Publica el siguiente tweet</label>'+
									'<div class="panel radius" style="margin-right:15px;">'+
										'<label>'+option.ovl_value+'</label>'+
									'</div>'+
									'<iframe allowtransparency="true" frameborder="0" scrolling="no"'+
										'src="https://platform.twitter.com/widgets/tweet_button.html?text='+option.ovl_value+'"'+
										'style="width:130px; height:20px;">'+
									'</iframe>';	
													
						buildOptionWidget(option, 'Twit en Twitter', label);		
						
						break;
			
			case "mli": var label = '<label style="padding-top:5px;">Navega a la siguiente dirección y suscríbete </label>'+
									'<div class="panel radius" style="margin-right:15px;">'+
										'<label><a href="'+option.ovl_value+'">'+option.ovl_value+'</a></label>'+
									'</div>';
									
						buildOptionWidget(option, 'Subscribirse a una lista de correo', label);		
						
						break;
						
			case "com": var label = '<label style="padding-top:5px;">Deja un comentario <a href="'+option.ovl_value+'">aquí</a></label>';
			
						buildOptionWidget(option, 'Comentar una entrada de un blog', label);
						
						break;
						
			
			case "txt": var label = '<label style="padding-top:5px;">'+ option.ovl_value +'</label>'+ 
									'<textarea id="txt1" style="width: 335px; height: 50px;" placeholder="Ejemplo: escribe aquí tu comentario" ></textarea>';
			
						buildOptionWidget(option, 'Enviar un texto libre', label);		
						
						break;
			/*
			case "fil": buildOptionWidget(option, 'Subir un fichero', 'Contenido del fichero',
									'text', 'Ej: Envía una foto de tu mascota');		
						break;
			*/
					
			case "fre": var label = '<label style="padding-top:5px;">'+option.ovl_value+'</label>';
				
						buildOptionWidget(option, 'Acción libre', label);		
						
						break;
				
			default: return false;
		}
		
		i++;
		accord();
	}	   
	
	function buildOptionWidget(option, title, label)
	{	
		var scntDiv = jQuery('#p_scents');
		
		jQuery('<div class="field" id="opt_'+option.opt_id+'">'+
					'<div class="accordion-header">'+
						'<span>'+title+'</span><span style="float:right; margin-right:4px;"><strong> '+option.opt_points+
						'<img src="./img/ico_medal_16.png"></strong></span>'+
					'</div>'+
					'<div class="accordion-content ">'+
						label+'<br>'+
						'<a href="#" class="button [ tiny radius ] right" style="margin-right:15px;">OK, DONE!</a><br>' +
					'</div>'+
				'</div>').appendTo(scntDiv);
	}
	
	function addWidgetHandler(element, event, handler, value)
	{
		jQuery(document).on(event, element, value, handler);
	}
	
	function accord() 
	{ 
		//cuenta las veces que una function ha sido llamada
		this.num = (this.num || 0) + 1;
		
		//segun la funcion haya sido llamada una o varias veces

		if (this.num < 2) 
		{
			//Add Inactive Class To All Accordion Headers
			jQuery('.accordion-header').toggleClass('inactive-header');

			// The Accordion Effect
			// Si el boton esta inactivo pasa la primera funcion y despliega el contenido
			jQuery('.accordion-header').click(
				function() 
				{
					if(jQuery(this).is('.inactive-header')) 
					{
						jQuery('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
						jQuery(this).toggleClass('active-header').toggleClass('inactive-header');
						jQuery(this).next().slideToggle().toggleClass('open-content');
					}
					// Si el boton esta activo pliega el contenido
					else 
					{
						jQuery(this).toggleClass('active-header').toggleClass('inactive-header');
						jQuery(this).next().slideToggle().toggleClass('open-content');
					}
				}
			);
		} 
		else 
		{
			//aqui poner que se active
			jQuery('.accordion-header.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
			jQuery('.accordion-header').last().toggleClass('active-header');
			jQuery('.accordion-content').last().slideDown().toggleClass('open-content');

			//con esto conseguimos que la funccion click no se repita por cada elemento añadadido
			jQuery('.accordion-header').unbind("click");

			//falta que funcione onclick
			jQuery('.accordion-header').click(
				function() 
				{
					if(jQuery(this).is('.inactive-header')) 
					{
						jQuery('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
						jQuery(this).toggleClass('active-header').toggleClass('inactive-header');
						jQuery(this).next().slideToggle().toggleClass('open-content');
					}
					// Si el boton esta activo pliega el contenido
					else 
					{
						jQuery(this).toggleClass('active-header').toggleClass('inactive-header');
						jQuery(this).next().slideToggle().toggleClass('open-content');
					}
				}
			);        
		}

		return false;
	}	
	    
	function getTime(endDate)
	{
		moment.lang('es');
		
		var x = moment(endDate, "YYYY-MM-DD").fromNow();
		return x;
	}	
	
	// FACEBOOK
    window.fbAsyncInit = function() 
	{
		FB.init({
			appId      : '{your-app-id}',
			status     : true,
			xfbml      : true
        });
    };

    (function(d, s, id)
	{
		var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/es_ES/all.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
	
	/*
	(function(d, s, id) 
	{
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	*/
	
	// TWITTER  
	!function(d,s,id)
	{
		var js,fjs=d.getElementsByTagName(s)[0];
		if(!d.getElementById(id))
		{
			js=d.createElement(s);
			js.id=id;
			js.src="https://platform.twitter.com/widgets.js";
			fjs.parentNode.insertBefore(js,fjs);
		}
	}(document,"script","twitter-wjs");

</script>