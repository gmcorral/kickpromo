<?php
	require_once('../server/session.php');
	if (session_status() == PHP_SESSION_NONE)
		session_start();
	
	if(!isLogged())
		header('Location: index.php');

	include 'header.php';
?>

<link rel="stylesheet" href="./css/widget.css">

<br/>
<div class="row">
	<div id="wrapper1">	
    
	<div id="accordion-container" class="panel radius">

		<h2 class="promo-header" id="promo-header">Cargando...</h2>
		
		<div class="row" >
		
		 <form data-abide class="medium-12 small-centered large-centered columns">
		  
		
				<!-- Field 1 -->
				<div class="medium-4 small-4 columns">
				  <label for="right-label-inline" class="right">Nombre de la promoción*</label>
				</div>
				<div class="medium-8 small-8 columns ">
				  <input type="text" id="rf_name" placeholder="Nombre de la promoción" required pattern="alpha_numeric" >
				  <small class="error">This is required</small>
				</div>
				<div class="large-offset-2 small-offset-3 columns">
				</div>
			
				<!-- Field 2 -->
				<div class="medium-4 small-4 columns">
				  <label for="right-label-inline" class="right">Nombre del promotor*</label>
				</div>
				<div class="medium-8 small-8 columns">
				  <input type="text" id="rf_owner" placeholder="Quién ofrece la promoción (nombre, blog, empresa,..)" required pattern="alpha_numeric">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>
			
				<!-- Field 3 -->
				<div class="medium-4 small-4 columns">
				  <label for="right-label-inline" class="right">Fecha de inicio*</label>
				</div>
				<div class="medium-8 small-8 columns">
				  <input type="text" id="rf_startDate" placeholder="Cuándo empieza" required pattern="alpha_numeric">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>

				<!-- Field 4 -->
				<div class="medium-4 small-4 columns">
				  <label for="right-label-inline" class="right inline">Fecha de fin*</label>
				</div>
				<div class="medium-8 small-8 columns">
				  <input type="text" id="rf_endDate" placeholder="Cuándo finaliza" required pattern="alpha_numeric">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>

				<!-- Field 5 -->
				<div class="medium-4 small-4 columns">
				  <label for="right-label-inline" class="right inline">Descripción</label>
				</div>
				<div class="medium-8 small-8 columns">
				  <input type="text" id="rf_description" placeholder="Detalles de la promoción (opcional)">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>

				<!-- Field 6 -->
				<div class="medium-4 small-4 columns">
				  <label for="right-label-inline" class="right inline">Términos*</label>
				</div>
				<div class="medium-8 small-8 columns">
				  <input type="text" id="rf_terms" placeholder="Términos y condiciones de la promoción (opcional)">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>
				
		</form>
			
		</div>
	</div>	
	
	<div id="accordion-container">
		
		<div id="p_scentsPrize"></div>

		<div class="small-8 large-12 columns" id="container"></div>   	  
	
		<div class="table_margin"></div> 			  
           
		<div class="add"> 
			<a id="addPrize">+ Nuevo premio</a>
		</div>
		
    </div>
    
	<div id="accordion-container">
		<!--
		//drag and nest div to give a different order http://www.html5rocks.com/en/tutorials/dnd/basics/ tal vez tambien se pueda con append
		-->
		<div id="p_scents"></div>

		<div class="small-8 large-12 columns" id="container"></div>   	  
	
		<div class="table_margin"></div> 			  
           
		<div class="add"> 
			<a id="add">+ Nueva acción</a>
		</div>
    
		<!-- *** Aqui hay un minibug, con algo relacionado con position absolute, parent que no deja clikear los links que tienen por encima el footer -->	
    
		<div class="submenu" id="submenu">
			<a class="gaw_opt" id="fbp">Publicación en Facebook</a></br>
			<a class="gaw_opt" id="fbl">Me gusta en Facebook</a><br/>
			<a class="gaw_opt" id="twf">Seguir en Twitter</a></br>
			<a class="gaw_opt" id="twt">Twit en Twitter</a></br>
			<a class="gaw_opt" id="mli">Subscripción a lista de correo</a></br>
			<a class="gaw_opt" id="com">Commentario en blog</a></br>
			<a class="gaw_opt" id="txt">Enviar un texto</a></br>
			<a class="gaw_opt" id="fil">Subir un archivo</a></br>
			<a class="gaw_opt" id="fre">Acción libre</a></br>
		</div>
		
    </div>
     
    <a onClick="cancelEdit()" class="button [ small ] right">Cancelar</a>
    <a onClick="saveGAW()" class="button [ small ] right">Guardar</a>
    
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>

</div>
</div>

<script>
	var gawid = <?php if(isset($_POST['edit'])) echo $_POST['edit']; else if(isset($_GET['edit'])) echo $_GET['edit']; else echo -1;?>;
</script>

<script type="text/javascript" src="./js/datepickr.js"></script>
<script src="./js/widget.js"></script>
<script src="./js/raffles.js"></script>

<!-- Global Footer --> 
<?php include 'footer.php'; ?>	
