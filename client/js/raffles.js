//jQuery.noConflict();

addEvent(window, 'load', initializeNewRaffle);
addEvent(window, 'load', datepickr_load);
addWidgetHandler('#addPrize', 'click', newPrize);
addWidgetHandler('#fbp', 'click', newOption, "fbp");
addWidgetHandler('#fbl', 'click', newOption, "fbl");
addWidgetHandler('#twf', 'click', newOption, "twf");
addWidgetHandler('#twt', 'click', newOption, "twt");
addWidgetHandler('#mli', 'click', newOption, "mli");
addWidgetHandler('#com', 'click', newOption, "com");
addWidgetHandler('#txt', 'click', newOption, "txt");
addWidgetHandler('#fil', 'click', newOption, "fil");
addWidgetHandler('#fre', 'click', newOption, "fre");


var deletedPrizes = [];
var deletedOptions = [];
var requestsPending = 0;

function datepickr_load()
{
		new datepickr('rf_startDate',
		{
			dateFormat: 'y-m-d',
			weekdays: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
			months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			suffix: { 1: '' },
			defaultSuffix: ''
		});
		new datepickr('rf_endDate',
		{
			dateFormat: 'y-m-d',
			weekdays: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
			months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			suffix: { 1: '' },
			defaultSuffix: ''
		});
}

function initializeNewRaffle()
{
	var title = document.getElementById("promo-header");
	document.getElementById("rf_name").onkeyup = updateRaffleName;
	
	if(gawid && gawid >= 0)
	{
		if (title)
			title.innerHTML = "Cargando...";
		loadGAW(gawid);
	}
	else
	{
		if (title)
			title.innerHTML = "Nueva promoción";
		gawid = -1;
	}
}

function cancelEdit()
{
	window.location.href = 'http://'+location.host+'/client/ca_raffles.php';
}

function updateRaffleName()
{
	var title = document.getElementById("promo-header");
	title.innerHTML = document.getElementById("rf_name").value;
}

function saveGAW()
{	
	var promoName = document.getElementById("rf_name").value;
	var owner = document.getElementById("rf_owner").value;
	var startDate = document.getElementById("rf_startDate").value;
	var endDate = document.getElementById("rf_endDate").value;
	var description = document.getElementById("rf_description").value;
	var terms = document.getElementById("rf_terms").value;
	
	if (promoName.length == 0 || owner.length == 0 || startDate.length == 0 || endDate.length == 0)
	{
		alert ("Debes rellenar todos los datos de la promoción");
		return;
	}

	if(gawid < 0)
	{
		params = 
		{
			func: 'newGAW',
			gaw_name: promoName,
			gaw_owner: owner,
			gaw_starttime: startDate,
			gaw_endtime: endDate,
			gaw_description: description,
			gaw_terms: terms
		};
	}
	else
	{
		params =
		{
			func: 'saveGAW',
			gaw_id: gawid,
			gaw_name: promoName,
			gaw_owner: owner,
			gaw_starttime: startDate,
			gaw_endtime: endDate,
			gaw_description: description,
			gaw_terms: terms
		};
	}
	
	// fill AJAX request to save giveaway
	new Ajax.Request('http://'+location.host+'/server/edit.php', 
	{
		method:'post',
		parameters: params,  
		onSuccess: function(transport) 
		{
			// Client successfully registered
			if(transport && transport.responseText.length > 0)
			{ 
				retVal = transport.responseText.evalJSON();
				
				// Load the client area if user exists
				if(retVal == 'SESSION_ERROR')
				{
					alert("Error de sesión");
					window.location.href = 'http://'+location.host+'/client/index.php';
				}
				else if(retVal == 'REQUEST_ERROR')		
				{
					alert("Error al procesar la petición");
					window.location.href = 'http://'+location.host+'/client/index.php';
				}
				else
				{
					gawid = retVal;
					setGawID(retVal);
					
					var prizes = document.getElementsByClassName("pri_id");
					var options = document.getElementsByClassName("opt_id");
					requestsPending = prizes.length + options.length 
									+ deletedPrizes.length + deletedOptions.length;
					
					// save GAW prizes
					for (prizeNr = 0; prizeNr < prizes.length; prizeNr++)
					{
						var priId = prizes[prizeNr].value;
						var name = jQuery("#pri_name_" + priId).prop('value');
						var quantity = jQuery("#pri_quantity_" + priId).prop('value');
						var prize = { pri_id: priId, pri_gawid: gawid, pri_name: name, pri_quantity: quantity, pri_order: prizeNr }
						savePrize(prize);
					}
					
					// save GAW options
					for (optNr = 0; optNr < options.length; optNr++)
					{
						var optId = options[optNr].value;
						var type = jQuery("#opt_type_" + optId).prop('value');
						var points = jQuery("#opt_points_" + optId).prop('value');
						var daily = true;
					  	var mandatory = true;
					  	var value = jQuery("#opt_value1_" + optId).prop('value');
						var option = { opt_id: optId, opt_gawid: gawid, opt_otyid: type, opt_points: points,
									opt_daily: daily, opt_mandatory: mandatory, ovl_value: value }
						saveOption(option);
					}
					
					// delete removed prizes
					while (deletedPrizes.length > 0)
						deletePrize(deletedPrizes.pop());
					
					// delete removed options
					while (deletedOptions.length > 0)
						deleteOption(deletedOptions.pop());
					
					// go to GAW list if saves are finished
					finishSaveRequest();
				}
			}
			else
			{
				alert('Error al actualizar los datos');
			}
		},
		onFailure: function()
		{
			alert('Error al actualizar los datos');
		}
	});
}

function loadGAW(gawid)
{
	// fill AJAX request
	new Ajax.Request('http://'+location.host+'/server/edit.php', 
	{
		method:'post',
		parameters:
		{
			func: 'loadGAW',
			gaw_id: gawid
		},
		onSuccess: function(transport) 
		{
			// giveaway successfully loaded
			if(transport && transport.responseText.length > 0)
			{
				retVal = transport.responseText.evalJSON();
				if(retVal == 'SESSION_ERROR')
				{		
					alert("Error de sesión");
					window.location.href = 'http://'+location.host+'/client/index.php';
				}
				else if(retVal == 'REQUEST_ERROR')
				{		
					alert("Error al procesar la petición");
				}
				else
				{
					// fill fields
					var promoName = document.getElementById("rf_name");
					var owner = document.getElementById("rf_owner");
					var startDate = document.getElementById("rf_startDate");
					var endDate = document.getElementById("rf_endDate");
					var description = document.getElementById("rf_description");
					var terms = document.getElementById("rf_terms");
					
					// Load gaw Info
					if (promoName)
						promoName.value = retVal.giveaway.gaw_name;
					if (owner)
						owner.value = retVal.giveaway.gaw_owner;
					if (startDate)
						startDate.value = retVal.giveaway.gaw_starttime;
					if (endDate)
						endDate.value = retVal.giveaway.gaw_endtime;
					if (description)
						description.value = retVal.giveaway.gaw_description;
					if (terms)
						terms.value = retVal.giveaway.gaw_terms;
					
					updateRaffleName();
					
					// load GAW prizes
					for(prizeNr = 0; prizeNr < retVal.prize.length; prizeNr++)
						addPrizeWidget(retVal.prize[prizeNr]);
					
					// load GAW options
					for(optNr = 0; optNr < retVal.option.length; optNr++)
						addOptionWidget(retVal.option[optNr]);
				}
			}
			else
			{
			  alert('Error al cargar los datos de la promoción');
			}
		},
		onFailure: function()
		{
			alert('Error al cargar los datos de la promoción');
		}
	});	
}

function newOption(event)
{
	var options = document.getElementsByClassName("opt_id");
	var option = {opt_id: -(options.length+1), opt_gawid: gawid, opt_otyid: event.data, 
				opt_points: 1, opt_daily: true, opt_mandatory: true, ovl_value: "" }
	addOptionWidget(option);
}

function saveOption(option)
{
	if(option.opt_gawid < 0)
		return false;
	
	if(option.opt_id < 0)
	{
		params = 
		{
			func: 'newOPT',
			opt_gawid: option.opt_gawid,
			opt_otyid: option.opt_otyid,
			opt_points: option.opt_points,
			opt_daily: option.opt_daily,
			opt_mandatory: option.opt_mandatory,
			ovl_value: option.ovl_value
		};
	}
	else
	{
		params =
		{
			func: 'saveOPT',
			opt_id: option.opt_id,
			opt_otyid: option.opt_otyid,
			opt_points: option.opt_points,
			opt_daily: option.opt_daily,
			opt_mandatory: option.opt_mandatory,
			ovl_value: option.ovl_value
		};
	}
	
	// fill AJAX request to save option
	new Ajax.Request('http://'+location.host+'/server/edit.php', 
	{
		method:'post',
		parameters: params,
		onSuccess: function(transport)
		{
			// Client successfully registered
			if(transport && transport.responseText.length > 0)
			{
				retVal = transport.responseText.evalJSON();

				// Load the client area if user exists
				if(retVal == 'SESSION_ERROR')
				{
					alert("Error de sesión");
					window.location.href = 'http://'+location.host+'/client/index.php';
				}
				else if(retVal == 'REQUEST_ERROR')
				{
					alert("Error al procesar la petición");
				}
				/*else
				{
					// update prize ID on widget
					updateOptionWidget(option.opt_id, retVal);
				}*/
			}
			else
			{
				alert('Error al guardar la opción');
			}
			finishSaveRequest();
		},
		onFailure: function()
		{
			alert('Error al guardar la opción');
			finishSaveRequest();
		}
	});
}

function removeOption(event)
{
	if (event.data > 0)
	{
		deletedOptions.push(event.data);
	}
	removeOptionWidget(event.data);
}

function deleteOption(optionId)
{
	if(gawid <= 0 || optionId <= 0)
		return false;
	
	// fill AJAX request to delete option
	new Ajax.Request('http://'+location.host+'/server/edit.php', 
	{
		method:'post',
		parameters:
		{
			func: 'deleteOPT',
			opt_id: optionId
		},
		onSuccess: function(transport)
		{
			// Client successfully registered
			if(transport && transport.responseText.length > 0)
			{
				retVal = transport.responseText.evalJSON();

				// Load the client area if user exists
				if(retVal == 'SESSION_ERROR')
				{
					alert("Error de sesión");
					window.location.href = 'http://'+location.host+'/client/index.php';
				}
				else if(retVal == 'REQUEST_ERROR')
				{
					alert("Error al eliminar la opción");
				}
			}
			else
			{
				alert('Error al borrar la opción');
			}
			finishSaveRequest();
		},
		onFailure: function()
		{
			alert('Error al borrar la opción');
			finishSaveRequest();
		},
	});
}

function newPrize()
{
	var prizes = document.getElementsByClassName("pri_id");
	var prize = {pri_id: -(prizes.length+1), pri_gawid: gawid, pri_name: "", pri_quantity: 1 }
	addPrizeWidget(prize);
}

function savePrize(prize)
{
	if(prize.pri_gawid < 0)
		return false;
			
	if(prize.pri_id < 0)
	{
		params = 
		{
			func: 'newPRI',
			pri_gawid: prize.pri_gawid,
			pri_name: prize.pri_name,
			pri_quantity: prize.pri_quantity,
			pri_order: prize.pri_order
		};
	}
	else
	{
		params =
		{
			func: 'savePRI',
			pri_id: prize.pri_id,
			pri_name: prize.pri_name,
			pri_quantity: prize.pri_quantity,
			pri_order: prize.pri_order
		};
	}
	
	// fill AJAX request to save prize
	new Ajax.Request('http://'+location.host+'/server/edit.php', 
	{
		method:'post',
		parameters: params,
		onSuccess: function(transport)
		{
			// Client successfully registered
			if(transport && transport.responseText.length > 0)
			{
				retVal = transport.responseText.evalJSON();

				// Load the client area if user exists
				if(retVal == 'SESSION_ERROR')
				{
					alert("Error de sesión");
					window.location.href = 'http://'+location.host+'/client/index.php';
				}
				else if(retVal == 'REQUEST_ERROR')
				{
					alert("Error al procesar la petición");
				}
				/*else
				{
					// update prize ID on widget
					updatePrizeWidget(prize.pri_id, retVal);
				}*/
			}
			else
			{
				alert('Error al guardar el premio');
			}
			finishSaveRequest();
		},
		onFailure: function()
		{
			alert('Error al guardar el premio');
			finishSaveRequest();
		}
	});
}

function removePrize(event)
{
	if (event.data > 0)
	{
		deletedPrizes.push(event.data);
	}
	removePrizeWidget(event.data);
}

function deletePrize(prizeId)
{
	if(gawid <= 0 || prizeId <= 0)
		return false;
	
	// fill AJAX request to delete prize
	new Ajax.Request('http://'+location.host+'/server/edit.php', 
	{
		method:'post',
		parameters:
		{
			func: 'deletePRI',
			pri_id: prizeId
		},
		onSuccess: function(transport)
		{
			// Client successfully registered
			if(transport && transport.responseText.length > 0)
			{
				retVal = transport.responseText.evalJSON();

				// Load the client area if user exists
				if(retVal == 'SESSION_ERROR')
				{
					alert("Error de sesión");
					window.location.href = 'http://'+location.host+'/client/index.php';
				}
				else if(retVal == 'REQUEST_ERROR')
				{
					alert("Error al eliminar el premio");
				}
			}
			else
			{
				alert('Error al borrar el premio');
			}
			finishSaveRequest();
		},
		onFailure: function()
		{
			alert('Error al borrar el premio');
			finishSaveRequest();
		},
	});
}

function finishSaveRequest()
{
	requestsPending--;
	if(requestsPending < 0)
		window.location.href = 'http://'+location.host+'/client/ca_raffles.php';	
}
