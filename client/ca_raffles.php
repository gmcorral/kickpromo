<?php
	require_once('../server/session.php');
	if (session_status() == PHP_SESSION_NONE)
		session_start();
	
	if(!isLogged())
		header('Location: index.php');

	include 'header.php';
?>

<!-- Main panel -->
<div class="row"> 
	<div class="medium-11 small-11 large-12 small-centered columns"> 
		<div class="medium-8 columns panel">
		  <h4>Administración de Promociones</h4>
	  
		  <div class="medium-12 row small-centered large-centered columns">
					<div class="content active" id="panel_ca_admin">
						<!-- Admin promos -->
						<?php include 'ca_adminraffles.php'; ?> 
					</div>
		  </div> 

		</div>  
		<div class="medium-4 columns text-center">
			<div class="large-1 columns text-center">
			</div>
			<div class="large-11 columns right text-center">
			<h4>Tips</h4>
			  <hr>
			  <p>¿Has probado a echarle un ojo al tutorial? Nos hemos esforzado en que esté todo claro.</p>
			  <hr>
			  <p>Trataremos de contestarte lo antes posible. </p>	
			</div>
		</div>
	</div>
</div>

<!-- Global Footer --> 
<?php include 'footer.php'; ?>	

