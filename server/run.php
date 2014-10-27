<?php

session_start();

require_once('giveaway.php');
require_once('prize.php');
require_once('winner.php');
require_once('option.php');
require_once('optvalue.php');
require_once('gawext.php');

require_once('configmanager.php');
require_once('langmanager.php');


/************************************************
Loads an existing giveaway from its widget key.

Input parameters:
	func: 'loadGAW'
	gaw_widgetkey: giveaway widget key

Server response:
	Object of type 'giveawaydata' containing the requested giveaway if load is successful
	'KEY_NOT_FOUND' if giveaway key is not found
	'REQUEST_ERROR' on other error

************************************************/
function loadGAW()
{
	$gaw_widgetkey = $_POST['gaw_widgetkey'];
	if(isset($gaw_widgetkey))
	{
		// find giveaway to be loaded
		$gaw = Giveaway::selectByWidgetKey($gaw_widgetkey);
		if(isset($gaw) && !empty($gaw))
		{
			$result = new stdClass();
			$result->giveaway = $gaw;
			
			// load giveaway prizes
			$result->prize = Prize::selectByGawId($gaw_id);
			
			// load giveaway options
			$result->option = [];
			$options = Option::selectByGawId($gaw_id);
			foreach($options as $opt)
			{
				$optvalue = Optvalue::selectByOptId($opt->opt_id);
				if(isset($optvalue[0]))
					$opt->ovl_value = $optvalue[0]->ovl_value;
				else
					$opt->ovl_value = "";
				$result->option[] = $opt;
				//$result->optiondata[]->option = $opt;
				//$result->optiondata[]->optvalue = Optvalue::selectByOptId($opt->opt_id);
			}
			
			// calculate giveaway state
			$gaw->gaw_state = $gaw->state();
			
			ConfigManager::getInstance()->logDebug('Giveaway '.json_encode($result).' loaded from key');
			
			// don't send certain properties to client
			unset($gaw->gaw_id);
			unset($gaw->gaw_usrid);
			unset($gaw->gaw_closed);
		
			return json_encode($result);
		}
		else
		{
			return json_encode('KEY_NOT_FOUND');
		}
	}
	else
	{
		return json_encode('REQUEST_ERROR');
	}
}


/************************************************
Loads all winners for a given giveaway widget key.

Input parameters:
	func: 'loadWNNlist'
	gaw_widgetkey: giveaway widget key

Server response:
	An array of 'winnerdata' objects if petition is successful
	'KEY_NOT_FOUND' if giveaway key is not found
	'REQUEST_ERROR' on other error
************************************************/
function loadWNNList()
{
	$gaw_widgetkey = $_POST['gaw_widgetkey'];
	if(isset($gaw_widgetkey))
	{
		// find giveaway to be loaded
		$gaw = Giveaway::selectByWidgetKey($gaw_widgetkey);
		if(isset($gaw) && !empty($gaw))
		{
			$result = [];
			
			// load giveaway winners
			$winners = Winner::selectByGawId($gaw->gaw_id);
			foreach($winners as $wnn)
			{
				if($wnn->wnn_published == true)
				{
					$result[]->entrant = Entrant::selectById($wnn->wnn_ettid);
					$result[]->winner = $wnn;
					$result[]->prize = Prize::selectById($wnn->wnn_priid);
				}
			}
			ConfigManager::getInstance()->logDebug('Winner list for giveaway key '.$gaw->gaw_widgetkey.' loaded');
			return json_encode($result);
		}
		else
		{
			return json_encode('KEY_NOT_FOUND');
		}
	}
	else
	{
		return json_encode('REQUEST_ERROR');
	}
}


/************************************************
Creates or loads an entrant for a giveaway identified by its widget key.

Input parameters:
	func: 'newETT'
	gaw_widgetkey: giveaway widget key
	ett_email: entrant email
	ett_fullname: entrant full name

Server response:
	Entrant ID if creation or load is successful
	'KEY_NOT_FOUND' if giveaway key is not found
	'GAW_CLOSED' if giveaway admits no new entrants
	'REQUEST_ERROR' on other error
************************************************/
function newETT()
{
	$gaw_widgetkey = $_POST['gaw_widgetkey'];
  	$ett_email = $_POST['ett_email'];
  	$ett_fullname = $_POST['ett_fullname'];
	if(isset($gaw_widgetkey) && isset($ett_email) && isset($ett_fullname))
	{
		// find giveaway to be loaded
		$gaw = Giveaway::selectByWidgetKey($gaw_widgetkey);
		if(isset($gaw) && !empty($gaw))
		{
			// check if GAW is active
			if($gaw->state() != Giveaway::Ongoing)
				return 'GAW_CLOSED';
			
			// try to load existing entrant
			$entrant = Entrant::selectByGawIdAndEmail($gaw->gaw_id, $ett_email);
			if($entrant == NULL || !isset($entrant) || empty($entrant))
			{
				$entrant = new Entrant($gaw->gaw_id, $ett_email, $ett_fullname);
				$entrant->insert();
			}
			ConfigManager::getInstance()->logDebug('Entrant '.$entrant->ett_id.' for giveaway key '.$gaw->gaw_widgetkey.' loaded');
			return json_encode($entrant->ett_id);
		}
		else
		{
			return json_encode('KEY_NOT_FOUND');
		}
	}
	else
	{
		return json_encode('REQUEST_ERROR');
	}
}


/************************************************
Creates or loads an entrant for a giveaway identified by its widget key,
using the entrant Facebook ID

Input parameters:
	func: 'newETTfb'
	gaw_widgetkey: giveaway widget key
	ett_fbid: entrant Facebook ID

Server response:
	Entrant ID if creation or load is successful
	'KEY_NOT_FOUND' if giveaway key is not found
	'GAW_CLOSED' if giveaway admits no new entrants
	'REQUEST_ERROR' on other error
************************************************/
function newETTfb()
{
	$gaw_widgetkey = $_POST['gaw_widgetkey'];
  	$ett_fbid = $_POST['ett_fbid'];
	if(isset($gaw_widgetkey) && isset($ett_fbid))
	{
		// find giveaway to be loaded
		$gaw = Giveaway::selectByWidgetKey($gaw_widgetkey);
		if(isset($gaw) && !empty($gaw))
		{
			// check if GAW is active
			if($gaw->state() != Giveaway::Ongoing)
				return 'GAW_CLOSED';
			
			// try to load existing entrant
			$entrant = Entrant::selectByGawIdAndFbId($gaw->gaw_id, $ett_fbid);
			if($entrant == NULL || !isset($entrant) || empty($entrant))
			{
				$entrant = new Entrant($gaw->gaw_id, NULL, NULL, $ett_fbid);
				$entrant->insert();
			}
			ConfigManager::getInstance()->logDebug('Entrant '.$entrant->ett_id.' for giveaway key '.$gaw->gaw_widgetkey.' loaded');
			return json_encode($entrant->ett_id);
		}
		else
		{
			return json_encode('KEY_NOT_FOUND');
		}
	}
	else
	{
		return json_encode('REQUEST_ERROR');
	}
}


/************************************************
Loads all entries for an entrant and giveaway.

Input parameters:
	func: 'loadETYlist'
	gaw_widgetkey: giveaway widget key
	ett_id: enrant ID

Server response:
	An array of ‘entry’ objects if petition is successful
	'KEY_NOT_FOUND' if giveaway key is not found
	'GAW_CLOSED' if giveaway admits no new entries
	'REQUEST_ERROR' on other error
************************************************/
function loadETYList()
{
	$gaw_widgetkey = $_POST['gaw_widgetkey'];
	$ett_id = $_POST['ett_id'];
	if(isset($gaw_widgetkey) && isset($ett_id))
	{
		// find giveaway to be loaded
		$gaw = Giveaway::selectByWidgetKey($gaw_widgetkey);
		if(isset($gaw) && !empty($gaw))
		{
			// check if GAW is active
			if($gaw->state() != Giveaway::Ongoing)
				return 'GAW_CLOSED';
			
			// load entrant entries for the giveaway
			$entries = Entry::selectByEttId($ett_id);
			
			ConfigManager::getInstance()->logDebug('Entries list for entrant '.$ett_id.' and giveaway key '.$gaw->gaw_widgetkey.' loaded');
			
			return $entries;
		}
		else
		{
			return json_encode('KEY_NOT_FOUND');
		}
	}
	else
	{
		return json_encode('REQUEST_ERROR');
	}
}


/************************************************
Creates an entry for an entrant and giveaway.

Input parameters:
	func: 'loadETT'
	gaw_widgetkey: giveaway widget key
	ett_id: enrant ID
	opt_id: entry option ID
	ety_answer: entry option answer (optional)

Server response:
	Entry ID if creation is successful
	'KEY_NOT_FOUND' if giveaway key is not found
	'GAW_CLOSED' if giveaway admits no new entries
	'REQUEST_ERROR' on other error
************************************************/
function newETY()    // TODO TODO TODO
{
	$gaw_widgetkey = $_POST['gaw_widgetkey'];
  	$ett_id = $_POST['ett_id'];
  	$opt_id = $_POST['opt_id'];
  	$ety_answer = $_POST['ety_answer'];
	if(isset($gaw_widgetkey) && isset($ett_id) && isset($opt_id) && isset($ety_answer))
	{
		// find giveaway to be loaded
		$gaw = Giveaway::selectByWidgetKey($gaw_widgetkey);
		if(isset($gaw) && !empty($gaw))
		{
			// check if GAW is active
			if($gaw->state() != Giveaway::Ongoing)
				return 'GAW_CLOSED';
			
			// create new entry
			$now = date("Y-m-d H:i:s");
			$clientip = $_SERVER['REMOTE_ADDR'];
			$entry = new Entry($ett_id, $opt_id, $now, $ety_answer, $clientip);
			$entry->insert();
			
			ConfigManager::getInstance()->logDebug('Entry '.$entry->ett_id.' for giveaway key '.$gaw->gaw_widgetkey.' loaded');
			return json_encode($entry->ety_id);
		}
		else
		{
			return json_encode('KEY_NOT_FOUND');
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
	case 'loadGAW':
		echo loadGAW();
		break;
		
	case 'loadWNNList':
		echo loadWNNList();
		break;
		
	case 'newETT':
		echo newETT();
		break;
		
	case 'newETTfb':
		echo newETTfb();
		break;
	
	case 'loadETYList':
		echo loadETYList();
		break;
		
	case 'newETY':
		echo newETY();
		break;
}

?>