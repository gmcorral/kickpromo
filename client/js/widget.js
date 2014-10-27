jQuery.noConflict();

var i = 0;
var j = 0;
var gawID = -1;

jQuery(window).ready(function()
{
	i = jQuery('#p_scents p').size() + 1;
	//accord();
	j = jQuery('#p_scentsPrize p').size() + 1;
	accord();
});
					
// It hides/shows the user interface
jQuery(document).ready(
	function() 
	{
		jQuery('a#admin').click(
			function()
			{
				jQuery(".hid").removeClass("hid")
			}
		);
	}
);


jQuery(document).ready(
	function() 
	{
		jQuery('div#submenu').addClass('hidden');
		
		jQuery('a#add').click(
			function() 
			{
				if(jQuery('div#submenu').is('.visible')) 
				{
					jQuery('div#submenu').addClass('hidden').removeClass('visible');
				} 
				else 
				{
					jQuery('div#submenu').removeClass('hidden').addClass('visible');
				}
			}
		);

		var submenu_active = false;
 
		jQuery('div#submenu').mouseenter(
			function() 
			{
				submenu_active = true;
			}
		);
		 
		jQuery('div#submenu').mouseleave(
			function() 
			{
				submenu_active = false;
				setTimeout(
					function()
					{ 
						if (submenu_active === false) 
							jQuery('div#submenu').addClass('hidden').removeClass('visible');; 
					}, 400
				);
			}
		);

		jQuery('div#submenu').click(
			function() 
			{
				jQuery('div#submenu').addClass('hidden').removeClass('visible');
			}
		);
	}
);

	
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

function setGawID(editGawId)
{
	gawID = editGawId;
}

function addWidgetHandler(element, event, handler, value)
{
	jQuery(document).on(event, element, value, handler);
}

function addOptionWidget(option) 
{
	switch(option.opt_otyid)
	{
		case "fbp": buildOptionWidget(option, 'Publicación en Facebook', 'Texto de la publicación',
								'text', 'Ej: Organiza grandes concursos en www.kickpromo.com');
					break;
					
		case "fbl": buildOptionWidget(option, 'Me gusta en Facebook', 'Página de Facebook',
								'text', 'Ej: www.facebook.com/kickpromo');
					break;
		
		case "twf": buildOptionWidget(option, 'Seguir en Twitter', 'Usuario de Twitter a seguir',
								'text', 'Ej: @kickpromo');		
					break;
					
		case "twt": buildOptionWidget(option, 'Twit en Twitter', 'Texto a twitear',
								'text', 'Ej: Organiza grandes concursos en www.kickpromo.com');		
					break;
		
		case "mli": buildOptionWidget(option, 'Subscribirse a una lista de correo', 'Dirección de la lista de correo',
								'text', 'Ej: news@kickpromo.com');		
					break;
					
		case "com": buildOptionWidget(option, 'Comentar una entrada de un blog', 'Dirección de la entrada',
								'text', 'Ej: tublog.com/tu_post');		
					break;
		
		case "txt": buildOptionWidget(option, 'Enviar un texto libre', 'Temática del texto',
								'text', 'Ej: Por favor dinos qué te parece Kickpromo');		
					break;
		
		case "fil": buildOptionWidget(option, 'Subir un fichero', 'Contenido del fichero',
								'text', 'Ej: Envía una foto de tu mascota');		
					break;
					
		case "fre": buildOptionWidget(option, 'Acción libre', 'Descripción de la acción',
								'text', 'Ej: Firma el libro de visitas de Kickpromo');		
					break;
		
		default: return false;
	}
	
	addWidgetHandler('#rem_opt_'+option.opt_id, 'click', removeOption, option.opt_id);
	
	i++;
	accord();
}

function buildOptionWidget(option, title, label, inputtype, placeholder) 
{
	var scntDiv = jQuery('#p_scents');
	jQuery('<div class="field" id="opt_'+option.opt_id+'">'+
			'<h2 class="accordion-header">'+title+'</h2>'+
			'<div class="accordion-content ">'+
			'<input type="button" class="accordion-delete" id="rem_opt_'+option.opt_id+'"/>'+
			'<input type="hidden" class="opt_id" id="opt_id_'+option.opt_id+'" value="'+option.opt_id+'" />'+
			'<input type="hidden" class="opt_type" id="opt_type_'+option.opt_id+'" value="'+option.opt_otyid+'" />'+
			'<p class="form">'+label+'</p>'+
			'  <input type="'+inputtype+'" id="opt_value1_'+option.opt_id+'" style="width: 335px; height: 50px;" placeholder="'+placeholder+'" value="'+option.ovl_value+'"/>'+
			'<div class="small-3 large-5 columns">'+
			'	<label for="left-label" class="right inline">Puntos</label>'+
			'</div>'+
			'<div class="small-2 large-4 columns">'+
			'   <input type="number" id="opt_points_'+option.opt_id+'" style="width: 50px; height: 30px;" min="1" max="5" step="1" value="'+option.opt_points+'">'+
			'</div><br> '+
			'</div>'+
			'</div>').appendTo(scntDiv);
}

/*function updateOptionWidget(oldId, newId)
{
	jQuery('div#opt_'+oldId).attr('id', 'opt_'+newId);
	jQuery('input#rem_opt_'+oldId).attr('id', 'rem_opt_'+newId);
	jQuery('input#opt_id_'+oldId).attr('value', newId);
	jQuery('input#opt_id_'+oldId).attr('id', 'opt_id_'+newId);
	jQuery('input#opt_type_'+oldId).attr('id', 'opt_type_'+newId);
	jQuery('input#opt_name_'+oldId).attr('id', 'opt_name_'+newId);
	jQuery('input#opt_points_'+oldId).attr('id', 'opt_points_'+newId);
}*/

function removeOptionWidget(optId)
{
	jQuery('div#opt_'+optId).remove();
}

function addPrizeWidget(prize)
{
	var scntDivPr = jQuery('#p_scentsPrize');
	var name = prize.pri_name;
	if ( name == '')
		name = "Nuevo premio";
	
	jQuery('<div class="field" id="prize_'+prize.pri_id+'">'+
		'<h2 class="accordion-header" id="title_pri_'+prize.pri_id+'">'+name+'</h2>'+
		'<div class="accordion-content ">'+
		'<input type="button" class="accordion-delete" id="rem_pri_'+prize.pri_id+'"/>'+
		'<input type="hidden" class="pri_id" id="pri_id_'+prize.pri_id+'" value="'+prize.pri_id+'" />'+
		'<p class="form">Descripción del premio</p>'+
		'  <input id="pri_name_'+prize.pri_id+'" style="width: 335px; height: 50px;" placeholder="Ejemplo: Un fantástico reproductor MP3" type="text" value="'+prize.pri_name+'"/>'+
		'<div class="small-3 large-5 columns">'+
		'	<label for="left-label" class="left inline">Cantidad</label>'+
		'</div>'+
		'<div class="small-2 large-4 columns">'+
		'   <input type="number" id="pri_quantity_'+prize.pri_id+'" min="1" max="100" step="1" value="'+prize.pri_quantity+'">'+
		'</div><br> '+
		'</div>'+
		'</div>').appendTo(scntDivPr);
	
	addWidgetHandler('#rem_pri_'+prize.pri_id, 'click', removePrize, prize.pri_id);
	addWidgetHandler('#pri_name_'+prize.pri_id, 'keyup', updatePrizeTitle, prize.pri_id);
	
	j++;
	accord();
}

/*function updatePrizeWidget(oldId, newId)
{
	jQuery('div#prize_'+oldId).attr('id', 'prize_'+newId);
	jQuery('h2#title_pri_'+oldId).attr('id', 'title_pri_'+newId);
	jQuery('input#rem_pri_'+oldId).attr('id', 'rem_pri_'+newId);
	jQuery('input#pri_id_'+oldId).attr('value', newId);
	jQuery('input#pri_id_'+oldId).attr('id', 'pri_id_'+newId);
	jQuery('input#pri_name_'+oldId).attr('id', 'pri_name_'+newId);
	jQuery('input#pri_quantity_'+oldId).attr('id', 'pri_quantity_'+newId);
}*/

function removePrizeWidget(priId)
{
	jQuery('div#prize_'+priId).remove();
}

function updatePrizeTitle(event)
{
	var value = jQuery('input#pri_name_'+event.data).prop('value');
	if (value == '')
		value = "Nuevo premio";
	jQuery('h2#title_pri_'+event.data).html(value);
}
