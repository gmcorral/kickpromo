<?php

session_start();

require_once('user.php');
require_once('giveaway.php');

/************************************************
Creates a new user with the given data (signup).
If giveaway data is included, also a new giveaway 
is created for user

Input parameters:
  func: 'newUSR'
  usr_email: user email
  usr_passwd: user password
  usr_timezone: user browser timezone
  usr_fullname: user full name (optional)
  usr_birthdate: user birth date (optional)
  usr_fbid: user facebook ID (optional)
  gaw_name: new name for giveaway (optional)
  gaw_owner: new owner for giveaway (optional)

Server response:
  'OK' if signup is successful and no giveaway is created
  Giveaway ID if giveaway is created
  'USER_EXISTS' if user already exists
  'REQUEST_ERROR' on other error
************************************************/
function newUSR()
{
	$email = $_POST['usr_email'];
	$passwd = $_POST['usr_passwd'];
	$timezone = $_POST['usr_timezone'];
	if(isset($_POST['usr_fullname']))
		$name = $_POST['usr_fullname'];
	else
		$name = NULL;
	if(isset($_POST['usr_birthdate']))
		$birthdate = $_POST['usr_birthdate'];
	else
		$birthdate = NULL;
	if(isset($_POST['usr_fbid']))
		$fbid = $_POST['usr_fbid'];
	else
		$fbid = NULL;
	if(isset($_POST['gaw_name']))
		$gaw_name = $_POST['gaw_name'];
	if(isset($_POST['gaw_owner']))
		$gaw_owner = $_POST['gaw_owner'];
	if(isset($email) && isset($passwd) && isset($timezone))
	{
		// check if user exists
		$usr = User::selectByEmail($email);
		if(!isset($usr))
		{
			// get signup time and create user
			$regtime = date("Y-m-d H:i:s");
			$usr = new User($email, $passwd, $name, $timezone, $regtime, 
							$_SERVER['REMOTE_ADDR'], $birthdate, $fbid, session_id());
			$usr->insert();
		
			ConfigManager::getInstance()->logDebug('User '.$usr->toJSON().' signed up, sessID='.session_id());
		
			// create initial giveaway
			if(isset($gaw_name))
			{
				$creattime = date("Y-m-d H:i:s");
				$starttime = date("Y-m-d H:i:s", strtotime('+1 day'));
				$endtime = date("Y-m-d H:i:s", strtotime('+1 month'));
				
				// widget key hashed from current time and user ID
				$hashstr = $creattime.$email;
				$widgetkey = hash("md5", $hashstr);
				
				// insert giveaway into DB
				$gaw = new Giveaway($usr->usr_id, $gaw_name, $gaw_owner, $starttime, 
							$endtime, '', '', $widgetkey, $creattime, '', false);
				if(!$gaw->insert())
					ConfigManager::getInstance()->logDebug('Error creating giveaway '.$gaw->toJSON());
				else
					ConfigManager::getInstance()->logDebug('Giveaway '.$gaw->toJSON().' created');

				return json_encode($gaw->gaw_id);
			}
			
			return json_encode('OK');
		}
		else
		{
			return json_encode('USER_EXISTS');
		}
	}
	else
	{
		return json_encode('REQUEST_ERROR');
	}
}


/************************************************
Logs a user into the system.

Input parameters:
  func: 'login'
  usr_email: user email
  usr_passwd: user password

Server response:
  'OK' if login is successful
  'WRONG_USR_PASSWD' if user doesn't exist or password is wrong
  'REQUEST_ERROR' on other error
************************************************/
function login()
{
	$email = $_POST['usr_email'];
	$passwd = $_POST['usr_passwd'];
	
	if(isset($email) && isset($passwd))
	{
		// find user
		$usr = User::selectByEmail($email);
		if(isset($usr))
		{
			// check password
			if($passwd == $usr->usr_password)
			{
				// set user session ID
				$usr->usr_sessionid = session_id();
				
				// set last visit time
				$usr->usr_lastvisit = date("Y-m-d H:i:s");
				
				// update in DB
				$usr->update();
				ConfigManager::getInstance()->logDebug('User '.$usr->toJSON().' logged in with session '.session_id());
				
				return json_encode('OK');
			}
			else
			{
				return json_encode('WRONG_USR_PASSWD');
			}
			
		}
		else
		{
			return json_encode('WRONG_USR_PASSWD');
		}
	}
	else
	{
		return json_encode('REQUEST_ERROR');
	}
}


/************************************************
Loads an existing user data.

Input parameters:
  func: 'loadUSR'

Server response:
  Object with user data if load is OK
  'SESSION_ERROR' if session has expired or is invalid
************************************************/
function loadUSR()
{
	// find user
	$usr = User::selectBySessionId();
	if(isset($usr))
	{
		// don't send id, password and session to client
		unset($usr->usr_id);
		unset($usr->usr_password);
		unset($usr->usr_sessionid);
		unset($usr->usr_regip);
		unset($usr->usr_fbid);
		
		ConfigManager::getInstance()->logDebug('User '.$usr->toJSON().' loaded');
		return $usr->toJSON();
	}
	else
	{
		return json_encode('SESSION_ERROR');
	}
}


/************************************************
Saves modified user data.

Input parameters:
  func: 'saveUSR'
  usr_fullname: new fullname (optional)
  usr_birthdate: new birthdate (optional)

Server response:
  'OK' if update is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function saveUSR()
{
	if(isset($_POST['usr_birthdate']))
		$birthdate = $_POST['usr_birthdate'];
	if(isset($_POST['usr_fullname']))
		$fullname = $_POST['usr_fullname'];
	if((isset($birthdate) || isset($fullname)))
	{
		// find user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// set last visit time
			if(isset($birthdate))
				$usr->usr_birthdate = $birthdate;
			if(isset($fullname))
				$usr->usr_fullname = $fullname;
			$usr->update();
								
			ConfigManager::getInstance()->logDebug('User '.$usr->toJSON().' updated');
				
			return json_encode('OK');
		}
		else
		{
			return json_encode('SESSION_ERROR');
		}
	}
	else
	{
		return json_encode('REQUEST_ERROR');
	}
}


/************************************************
Saves an existing user password.

Input parameters:
  func: 'saveUSRpwd'
  usr_oldpassword: old user password
  usr_password: new password for user
Server response:
  'OK' if update is successful
  'WRONG_PASSWD' if old password is wrong
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function saveUSRpwd()
{
	$oldPassword = $_POST['usr_oldpassword'];
	$newPassword = $_POST['usr_password'];
	if(isset($oldPassword) && isset($newPassword))
	{
		// find user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// check old password and update
			if($oldPassword == $usr->usr_password)
			{
				$usr->usr_password = $newPassword;
				$usr->update();
				
				ConfigManager::getInstance()->logDebug('User '.$usr->toJSON().' password updated');
				
				return json_encode('OK');
			}
			else
			{
				return json_encode('WRONG_PASSWD');
			}
		}
		else
		{
			return json_encode('SESSION_ERROR');
		}
	}
	else
	{
		return json_encode('REQUEST_ERROR');
	}
}


ConfigManager::getInstance()->logDebug($_POST['func'].' requested from '.$_SERVER['REMOTE_ADDR']);

header('Content-type: application/x-json');

switch($_POST['func'])
{
	case 'newUSR':
		echo newUSR();
		break;
	
	case 'login':
		echo login();
		break;
	
	case 'loadUSR':
		echo loadUSR();
		break;
		
	case 'saveUSR':
		echo saveUSR();
		break;
		
	case 'saveUSRpwd':
		echo saveUSRpwd();
		break;
}

?>