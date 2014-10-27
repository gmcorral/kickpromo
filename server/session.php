<?php

require_once('user.php');

/************************************************
Checks if a user is logged in, using its session ID.

Input parameters:
  none
  
Server response:
  true if user is logged in, false otherwise
************************************************/
function isLogged()
{
	// find user by session ID
	$usr = User::selectBySessionId();
	ConfigManager::getInstance()->logDebug('is logged with sessID='.session_id());
		
	return isset($usr);
}


/************************************************
Checks if a user is logged in, using its session ID.

Input parameters:
  none
  
Server response:
  'OK' if login is successful
  'LOGOUT_ERROR' if session ID was not logged in
************************************************/
function logout()
{
	// find user by session ID
	$usr = User::selectBySessionId();
	session_destroy();
	if(isset($usr))
	{
		$usr->usr_sessionid = NULL;
		$usr->update();
		return json_encode('OK');
	}
	else
	{
		return json_encode('LOGOUT_ERROR');
	}
}

?>