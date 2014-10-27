
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

  <script src="./js/foundation/foundation.js"></script>
  <script src="./js/foundation/foundation.section.js"></script>
  <!-- Other JS plugins can be included here -->

  <script>
    $(document).foundation();
  </script>


  
<script type='text/javascript'>
$(window).ready(function(){
$(function() {
        var scntDiv = $('#p_scents');
        var i = $('#p_scents p').size() + 1;
        var singleValues = $( "#addScnt" ).val();

        $('#addScnt').live('click', function() {
                    if($(this).val() == 'fbp'){
                			
                			//$(
                			//'<p data-section-title>'+
                			//'<a href="#">Facebook post</a>'+
                			//'<label for="p_scnts">Url Post'+
                			//'<input type="text" id="p_scnt" size="20" name="p_scnt_' + i +'" value="" placeholder="Input Value" />'+
                			//'</label> '+
                			//'<a href="#" id="remScnt">Borrar</a>'+
                			//'</p>').appendTo(scntDiv);
                			
                			
                			$('<div data-section="accordion">'+
                			    '<section class="active">'+
                			      '<ul class="inline-list" data-section-title>'+
  							        '<li><a href="#">Post on Facebook</a></li>'+
  							        '<li><a href="#" id="remScnt">Borrar</a></li>'+
						          '</ul>'+
        					      '<div class="vcard" data-section-content>'+   
        					         '<div class="small-3 large-3 columns">'+
                                       '<label for="right-label" class="right inline">What do you want the people to post</label>'+
                                     '</div>'+
                                     '<div class="small-3 large-7 columns">'+
                                       '<input type="text" id="pro_name" placeholder="http://">'+
                                     '</div></br></br></br></br>'+  
        					      '</div>'+
        					    '</section>'+
        					  '</div>').appendTo(scntDiv);
        
            }
					else if($(this).val() == 'twf'){
                			$('<div data-section="accordion">'+
                			    '<section class="active">'+
                			      '<ul class="inline-list" data-section-title>'+
  							        '<li><a href="#">Follow twitter user</a></li>'+
  							        '<li><a href="#" id="remScnt">Borrar</a></li>'+
						          '</ul>'+
        					      '<div class="vcard" data-section-content>'+   
        					         '<div class="small-3 large-3 columns">'+
                                       '<label for="right-label" class="right inline">Which user they have to follow?</label>'+
                                     '</div>'+
                                     '<div class="small-3 large-7 columns">'+
                                       '<input type="text" id="pro_name" placeholder="Example: @max_max">'+
                                     '</div></br></br></br></br>'+  
        					      '</div>'+
        					    '</section>'+
        					  '</div>').appendTo(scntDiv);

            }
            
					else if($(this).val() == 'twt'){
                			$('<div data-section="accordion">'+
                			    '<section class="active">'+
                			      '<ul class="inline-list" data-section-title>'+
  							        '<li><a href="#">Twit on Twitter</a></li>'+
  							        '<li><a href="#" id="remScnt">Borrar</a></li>'+
						          '</ul>'+
        					      '<div class="vcard" data-section-content>'+   
        					         '<div class="small-3 large-3 columns">'+
                                       '<label for="right-label" class="right inline">What do you want the people to tweet?</label>'+
                                     '</div>'+
                                     '<div class="small-3 large-7 columns">'+
                                       '<textarea id="text1" rows="10" placeholder="Example: If you want to organice good contests please visit www.kickpromo.com" ></textarea>'+
                                     '</div></br></br></br></br>'+  
        					      '</div>'+
        					    '</section>'+
        					  '</div>').appendTo(scntDiv);

            }
                i++;
                return false;
        });
        
        $('#remScnt').live('click', function() { 
                if( i > 1 ) {
                        $(this).parent().parent().parent().parent().remove();
                        i--;
                }
                return false;
        });
});

}); 

</script>


          
          
</head>
<body>







<p>
<!-- Call to Action Panel -->
<div class="row"> 
  <div class="large-6 small-8 columns vcard">
    <h4 class="text-center" >Entra para ganar un "Premio"</h4> 
    <h5 class="text-center">Patrocinado por "Empresa"</h5> 
    <hr>
    <div class="large-4 small-4 columns text-center">
      <p>Quedan 11h y 20min</p>
    </div>
    <div class="large-4 small-4 columns text-center">
      <p>Hay XX participantes</p>
    </div>
    <div class="large-4 small-4 columns text-center">
      <p>Tienes XX papeletas</p>
    </div>
    <form class="custom">      
      <div class="large-12 small-12 columns panel">
        <!-- User info -->
        
        <div class="small-3 large-3 columns">
          <label for="left-label" class="right inline">Nombre</label>
        </div>

        <div class="small-3 large-6 columns">
          <input type="text" id="usr_email" placeholder="Nombre">
        </div>
        
        <div class="large-offset-3 columns"></div>
        
        <div class="small-3 large-3 columns">
          <label for="left-label" class="right inline">Correo</label>
        </div>

        <div class="small-3 large-6 columns">
          <input type="text" id="usr_email" placeholder="Correo">
        </div>

        <div class="large-offset-3 columns"></div>
        </div>

<div class="section-container vertical-nav" data-section="vertical-nav" data-options="one_up: false;">
      <section>
        <p class="" data-section-title><a href="#">Add new action</a></p>
        <div class="content " data-section-content>
          <ul class="side-nav">
            <li><a href="#" id="fbp">Link 1</a></li>
            <li><a href="#">Link 2</a></li>
            <li><a href="#">Link 3</a></li>
            <li class="divider"></li>
            <li><a href="#">Link 1</a></li>
          </ul>
        </div>
      </section>

</div>

        <div class="vcard" data-section="accordion">
          <select id="addScnt" name="addScnt" style="width: 212px;">
            <option value="fbl">Facebook likes</option>
            <option value="fbp">Facebook post</option>
            <option value="twf">Twitter followers</option>
            <option value="twt">Twitter twit</option>
            <option value="com">Comments</option>
            <option value="stx">Sending a text</option>
            <option value="sfl">Sending a file</option>
            <option value="fra">Free action</option>
          </select>
              
  
        <div id="p_scents">
	    </div>
	  
	  </div>
    </form>
    
  </div>
</div>
  