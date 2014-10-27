<div class="panel radius">
	<div class="row">
		<!-- Password -->
		<div class="small-3 large-3 columns">
			<label for="left-label" class="right inline">Contraseña</label>
		</div>
		
		<div class="small-3 large-7 columns">
			<input type="password" id="old_passwd" placeholder="Contraseña Actual">
		</div>  
		
		<div class="large-offset-2 columns"></div>

		<!-- New Password -->
		<div class="small-3 large-3 columns">
			<label for="left-label" class="right inline">Contraseña Nueva</label>
		</div>
		
		<div class="small-3 large-7 columns">
			<input type="password" id="new_passwd1" placeholder="Contraseña Nueva">
		</div> 
		
		<div class="small-2 large-2 columns" id="mod_password2OK">
			<img src="./img/ico_check_ok_16.png" alt="OK">
		</div>

		<div class="small-2 large-2 columns" id="mod_password2ERROR">
			<img src="./img/ico_check_error_16.png" alt="ERROR">
		</div>										
		
		<div class="large-offset-2 columns"></div>

		<!-- New Password -->
		<div class="small-3 large-3 columns">
			<label for="left-label" class="right inline">Repetir Contraseña</label>
		</div>
		
		<div class="small-3 large-7 columns">
			<input type="password" id="new_passwd2" placeholder="Contraseña Nueva">
		</div> 
		
		<div class="small-2 large-2 columns" id="mod_password3OK">
			<img src="./img/ico_check_ok_16.png" alt="OK">
		</div>

		<div class="small-2 large-2 columns" id="mod_password3ERROR">
			<img src="./img/ico_check_error_16.png" alt="ERROR">
		</div>								
		
		<div class="large-offset-2 columns"></div>							

		<div class="small-3 large-12 columns">
			<a onClick="OnClickChangeUserPass()" class="button [tiny small large radius round]">Cambiar Contraseña</a>
		</div>
	</div>
</div>