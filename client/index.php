<?php
	session_start();
	require_once('../server/session.php');
	if(isset($_GET["logout"])){
		if($_GET["logout"] == "true")
			logOut();
	}			
	else if(isLogged())
		header('Location: ca_raffles.php');
		
	include 'header.php';		
?> 

	<!-- Main Panel -->
	<div class="row">
		<div class="large-12 columns">  
			<div class="panel">
				<div class="row">
					<div class="small-4 columns">
						<img src="./img/rocket.png" alt="Generic placeholder image">
					</div>
					<div class="small-8 columns">
						<h4 class="center text-center">Organiza tus promociones gratuitamente y multiplica la visibilidad de tu producto o web rápida y fácilmente.</h4>
						<hr class="featurette-divider">
						<h4>Sorteos y Concursos virales</h4>
						<p>Aprovecha las redes sociales (Facebook, Twitter, Pinterest..) y consigue miles de visitas y clientes.</p>
						<hr class="featurette-divider">
						<h4>Bueno, bonito y GRATIS</h4>
						<p>Sin necesidad de conocimientos técnicos, instrucciones claras, rápido y sin previo registro.</p>
						<hr class="featurette-divider">
						<h4><a href="showexample.php">Un ejemplo</a> vale más que mil palabras</h4>
						o también puedes <a href="getstarted.php">crear tu promoción</a></p>
					</div>
				</div>
			</div>      
		</div>
		<hr />
	</div>

	<!-- Three-up Content Blocks -->
	<div class="row">
		<div class="large-4 columns">
			<img src="./img/people.png" />
			<h4>¿Quién suele usarlo?</h4>       
			<ol>
				<li>Bloggeros para fidelizar visitas.</li>
				<li>Nuevas empresas que quieren darse a conocer. </li>
				<li>PYMES Para reforzas su visibilidad o lanzar nuevos productos.</li>
			</ol>
		</div>
    
		<div class="large-4 columns">
			<img src="./img/advantages.png" />
			<h4>Ventajas</h4>
			<ol>
				<li>Marketing viral muy barato.</li>
				<li>En linea con las reglas de las redes sociales.</li>
				<li>Transparente, se publican los ganadores.</li>
				<li>Unas buen manual de ayuda.</li>
			</ol>
		</div>

		<div class="large-4 columns">
			<img src="./img/gears.png" />       
			<h4>¿Cómo funciona?</h4>       
			<ol>
				<li>Crea tu promoción en un paso.</li>
				<li>Publícalo en tu blog, por email, redes sociales, whatsapp..</li>
				<li>Espera unos instantes y empezarán a llegar tus primeros concursantes que reenviarán el sorteo.</li>
				<li>Elige ganador y prémiale.</li>
			</ol>
		</div>
	</div>

	<!-- Global Footer --> 
	<?php include 'footer.php'; ?>	