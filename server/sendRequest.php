<?php

/************************************************
Creates a new user with the given data (signup).

Input parameters:
  func: 'sendRequest'
  form_email: user email
  form_subject: request subject
  form_text: request text
  
Server response:
  'EMAIL_SENT' if email was sent
  'EMAIL_FAILED' on error occurred
************************************************/
function sendRequest()
{ echo 'entro envio mail';
	$from = $_POST['form_email'];
	$subject = $_POST['form_subject'];
	$text = $_POST['form_text'];
	
	if(isset($from) && isset($subject) && isset($text))
	{		
		$email_to = "janderfm@gmail.com";
		$email_subject = "New request from KickPromo";

		$email_message = "Detalles del formulario de contacto:\n\n";
		$email_message .= "E-mail: " . $from . "\n";
		$email_message .= "Asunto: " . $subject . "\n";
		$email_message .= "Comentarios: " . $text . "\n\n";

		// Enviar el e-mail usando la función mail() de PHP
		$headers = 'From: '.$from."\r\n".
		'Reply-To: '.$from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		@mail($email_to, $email_subject, $email_message, $headers);
		
		return json_encode('EMAIL_SENT');
	}
	else
	{
		return json_encode('EMAIL_FAILED');
	}
}

?>