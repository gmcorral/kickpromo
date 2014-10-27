<?php
	if (session_status() == PHP_SESSION_NONE)
		session_start();
?>
<br><br>
<div class="row">
	<!-- New Promo -->
	<ul class="inline-list">
	  <li><a onClick="goGaw(-1)"><img src="./img/ico_nueva_promo_32.png"></a></li>
	  <li><a onClick="goGaw(-1)"><h6><strong>Nueva Promo!</strong></h6></a></li>
	</ul>
</div>

<div class="row">
	<div class="no_promos"></div>
</div>

<div class="waiting_winner"></div>	
<div class="active_gaw"></div>
<div class="comming_soon"></div>
<div class="old_gaw"></div>

<div class="reveal-modal" data-reveal></div>

<script src="./js/moment-with-langs.js" type="text/javascript"></script>

<script>
	addEvent(window, 'load', initializeAdminRaffles);		
	
	var arrayGaws = null;

	// Initializations
	var pending = ''; 
	var inprogress = ''; 
	var winner = '';
	var finished = '';
	
	var n_pending = 0;
	var n_inprogress = 0;
	var n_winner = 0;
	var n_finished = 0;	
	
	function initializeAdminRaffles()
	{ 
		arrayGaws = new Array();
		pending = inprogress = winner = finished = '';
		n_pending = n_inprogress = n_winner = n_finished = 0;	
	
		// fill AJAX request
		new Ajax.Request('http://'+location.host+'/kickpromo/server/edit.php', 
		{
			method:'post',
			parameters:
			{
				func: 'loadGAWList'
			},
			onSuccess: function(transport) 
			{
				// Gaw´s successfully retrieved
				if(transport && transport.responseText.length > 0)
				{
					retVal = transport.responseText.evalJSON();
					
					// Array of objects from table 'giveaway'
					if(retVal == 'REQUEST_ERROR')
					{
						alert("REQUEST_ERROR");
					}
					else if(retVal == 'SESSION_ERROR')
					{
						alert("SESSION_ERROR"); 															
					}
					else
					{
						// Get the gaw´s array
						arrayGaws = transport.responseText.evalJSON(true);

						// Print all the gaws
						printGAW(arrayGaws);
					}
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
	
	function printGAW(arrayGaws)
	{		
		jQuery(".comming_soon").empty();
		jQuery(".active_gaw").empty();
		jQuery(".waiting_winner").empty();
		jQuery(".old_gaw").empty();		
	
		// No elements
		if(arrayGaws.length <= 0)
		{
			// Clear content of div element
			//document.getElementById("no_promos").innerHTML = "";
			jQuery(".no_promos").empty();
			
			var no_promos = 
								'<div class="small-5 large-12 columns">' +
									'<a onClick="goGaw(-1)"><label><strong>No hay promociones activas... anímate y crea una!!</strong></label></a>' +
								'</div>';
						
			jQuery(no_promos).appendTo(".no_promos");
		}
		else
		{			
			for(var j = 0; j < arrayGaws.length; j++)
			{				
				// State = 0 -> pendientes
				if(arrayGaws[j].gaw_state == 0)
				{
					// add pending gaw to the table
					pending += addGaw(n_pending, arrayGaws[j], "Pendientes", arrayGaws[j].gaw_state);				
		
					// Increase counter
					n_pending++;		
				}	
				else if(arrayGaws[j].gaw_state == 1) // State = 1 -> en proceso
				{
					// add in progress gaw to the table
					inprogress += addGaw(n_inprogress, arrayGaws[j], "En proceso", arrayGaws[j].gaw_state);
		
					// Increase counter
					n_inprogress++;
				}				
				else if(arrayGaws[j].gaw_state == 2) // State = 2 -> esperando ganador
				{
					// add waiting winner gaw to the table
					winner += addGaw(n_winner, arrayGaws[j], "Esperando ganador", arrayGaws[j].gaw_state);			
		
					// Increase counter
					n_winner++;						
				}
				else if(arrayGaws[j].gaw_state == 3) // State = 3 -> finalizadas
				{
					// add ended gaw to the table
					finished += addGaw(n_finished, arrayGaws[j], "Finalizadas", arrayGaws[j].gaw_state);				
		
					// Increase counter
					n_finished++;					
				}			
			}
			
			// Close tables
			if(n_pending > 0)
			{
				pending += '</table>';				
				jQuery(pending).appendTo(".comming_soon");
			}	
			
			if(n_inprogress > 0)
			{
				inprogress += '</table>';
				jQuery(inprogress).appendTo(".active_gaw");
			}
			
			if(n_winner > 0)
			{
				winner += '</table>';
				jQuery(winner).appendTo(".waiting_winner");
			}	
			
			if(n_finished > 0)
			{
				finished += '</table>';				
				jQuery(finished).appendTo(".old_gaw");
				showEnded('FALSE');
			}			
		}
	}
		
	function addGaw(n, gaw, msg, state)	
	{	
		var str = '';
		
		// if header is not created, initialize it
		if(n == 0)
		{
			if(state == 3)
			{
				str += 	
					'<div>' +				
						'<ul class="inline-list">' +							
							'<li><img id="opt_hideEnded" alt="ocultar" src="./img/ico_triangulo_show_20.png" onClick="showEnded(\'FALSE\')"></li>' +
							'<li><img id="opt_showEnded" alt="mostrar" src="./img/ico_triangulo_hide_20.png" onClick="showEnded(\'TRUE\')"></li>' +
							'<li><h6><strong>Mostrar Finalizadas</strong></h6></li>' +
						'</ul>' +
					'</div>';
			}
			else
				str += 	'<h6><strong>'+msg+'</strong></h6>';
			
			str += 	'<table id="table_'+state+'">';
		}
		
		// add element
		str +=
				'<tr>' +
					'<td width="200">'+ gaw.gaw_name+'</td>' +
					'<td><img src="./img/ico_group_16.png" onClick="#">'+10+'</td>' +					
					'<td><img src="./img/ico_edit_16.png"  onClick="goGaw('+ gaw.gaw_id +')"></td>' +
					'<td><img src="./img/ico_check_error_16.png" onClick="deleteGaw('+ gaw.gaw_id +')"></td>' +
					'<td width="100"><img src="./img/ico_rocket_16.png" onClick="getCodeGaw(\''+gaw.gaw_widgetkey+'\')"></td>';
		
		if(state != 3)
			str += '<td>Finaliza <strong>'+ getTime(gaw.gaw_endtime) +'</strong> </td>';
		else
			str += '<td>Finalizó <strong>'+ getTime(gaw.gaw_endtime) +'</strong> </td>';
		
		str += '</tr>';
					
		return str;
	}
	
	function getTime(endDate)
	{
		moment.lang('es');
		
		var x = moment(endDate, "YYYY-MM-DD").fromNow();
		return x;
	}	
	
	function showEnded(opt)
	{
		if(opt == 'TRUE')
		{
			$("table_3").show();
			$("opt_showEnded").hide();
			$("opt_hideEnded").show();
		}
		else
		{
			$("table_3").hide();		
			$("opt_showEnded").show();
			$("opt_hideEnded").hide();
		}
	}
	
	function goGaw(gaw_id)
	{
		// Edit selected gaw
		form = document.createElement('form');
		form.setAttribute('method', 'POST');
		form.setAttribute('action', 'http://'+location.host+'/kickpromo/client/ca_newraffle.php');
		myvar = document.createElement('input');
		myvar.setAttribute('name', 'edit');
		myvar.setAttribute('type', 'hidden');
		myvar.setAttribute('value', gaw_id);
		form.appendChild(myvar);
		document.body.appendChild(form);
		form.submit();
	}
	
	/* Delete selected gaw 
		idGaw -> possition in the gaw´s array 
		gaw_id -> real gaw id on BD
	*/
	function deleteGaw(gaw_id)
	{
		// fill AJAX request
		new Ajax.Request('http://'+location.host+'/kickpromo/server/edit.php', 
		{
			method:'post',
			parameters:
			{
				func: 'deleteGAW',
				gaw_id: gaw_id
			},
			onSuccess: function(transport) 
			{
				// Gaw successfully deleted
				if(transport && transport.responseText.length > 0)
				{
					retVal = transport.responseText.evalJSON();
					if(retVal == 'REQUEST_ERROR')
					{
						alert("REQUEST_ERROR");
					}
					else if(retVal == 'SESSION_ERROR')
					{
						alert("SESSION_ERROR"); 															
					}
					else if(retVal == 'OK')
					{						
						initializeAdminRaffles();
					}
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
	
	function getCodeGaw(gaw_widgetkey)
	{
		// Clear the modal
		jQuery('.reveal-modal').html("");
				
		// Create reveal
		var revealGaw = '';
		revealGaw = 
					'<h2>Ya tienes lista tu encuesta!!</h2>' +
					'<p class="lead">Este es el código que tienes que copiar en tu site: '+ gaw_widgetkey +'</p>' +
					'<p>Ejemplo de como quedaría tu encuesta:</p>' +
					'<a class="close-reveal-modal">&#215;</a>' +
					'<iframe name="window" src="http://localhost:8000/client/widweb.php?k='+gaw_widgetkey+'"' +
						'width="1100" height="900" marginwidth="0" scrolling="yes" frameborder="0"></iframe>'
					;
		
		jQuery(revealGaw).appendTo('.reveal-modal');
		jQuery('.reveal-modal').foundation('reveal', 'open');
	}
	
	function OnClickSaveGaw(idGaw)
	{
		setButtonLogic("Show", idGaw);
	}

	function OnClickCancelSaveGaw(idGaw)
	{
		// Clear details content and show action button
		jQuery('.detailGaw_'+idGaw).html("");
		setButtonLogic("Show",idGaw);
	}
	
	function setButtonLogic(action, idGaw)
	{
		if(action == "Hide")
		{
			$("eg_"+idGaw).hide();
			$("dg_"+idGaw).hide();
			$("gc_"+idGaw).hide();
		}
		else if(action == "Show")
		{
			$("eg_"+idGaw).show();
			$("dg_"+idGaw).show();
			$("gc_"+idGaw).show();
		}
	}	
</script>