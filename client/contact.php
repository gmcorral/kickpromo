<?php
	//session_start();
	include 'header.php';	
?> 

<!-- Call to Action Panel -->
<div class="row"> 
	<div class="medium-11 small-11 large-12 small-centered columns"> 
		<div class="medium-8 columns panel">
		  <h4>Formulario de contacto</h4>
	  
		  <form data-abide>
		  
		
				<!-- Field 1 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Email*</label>
				</div>
				<div class="medium-7 small-8 columns ">
				  <input type="text" id="form_email" placeholder="Correo electrónico" required pattern="email">
				  <small class="error">This is required</small>
				</div>
				<div class="large-offset-2 small-offset-3 columns">
				</div>
			
				<!-- Field 2 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Asunto*</label>
				</div>
				<div class="medium-7 small-8 columns">
				  <input type="password" id="form_subject" placeholder="Asunto" required pattern="alpha_numeric">
				  <small class="error">This is required</small>
				</div>
				<div class="medium-offset-2 small-offset-3 columns">
				</div>
			
				<!-- Field 3 -->
				<div class="medium-3 small-3 columns">
				  <label for="right-label-inline" class="right inline">Mensaje:*</label>
				</div>
				<div class="medium-7 small-8 columns">
				  <textarea id="form_text" placeholder="Mensaje" placeholder="Mensaje" required pattern="alpha_numeric"></textarea>
				  <small class="error">This is required</small>
				</div>
				<div class="large-offset-2 small-offset-3 columns">
				</div>

				<div class="medium-12 medium-offset-3 columns">
				  <a onClick="SendMail()" class="small button">Enviar</a>
				</div>
			
		</form>
		</div>  
		<div class="medium-4 columns text-center">
			<div class="large-1 columns text-center">
			</div>
			<div class="large-11 columns right text-center">
				<h4>Tips</h4>
			
				  <hr>
				  <p >Has probado a echar un ojo en <a href="tutorial.php">tutorial</a>, nos hemos esforzado en que esté todo claro</p>
				  <hr>
				  <p>Trataremos de contestarte lo antes posible. </p>
			</div>
		</div>	
	</div>
</div>






<script>

	function SendMail()
	{
		// Recover fields
		var from = document.getElementById("form_email").value;
		var subject = document.getElementById("form_subject").value;
		var text = document.getElementById("form_text").value;
		
		//alert("sendmail" + " " + from + " - " + subject + " - " + text);

		// fill AJAX request
		new Ajax.Request('http://'+location.host+'/server/sendRequest.php', 
		{
			method:'post',
			parameters:
			{
			func: 'sendRequest', 
			form_mail: from,
			form_subject: subject,
			form_text: text
			},
			onSuccess: function(transport) 
			{
				// Check email state
				if(transport.responseText.strip() != '"EMAIL_FAILED"')				
					alert('envio OK');
				else
					alert('Error al enviar el email1');
			},
			onFailure: function()
			{
				alert('Error al enviar el email2');
			}
		});
	}	

</script>
	
<!-- Global Footer --> 
<?php include 'footer.php'; ?>