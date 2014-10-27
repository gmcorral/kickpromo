<?php

session_start();

require_once('user.php');
require_once('opttype.php');
require_once('optpricing.php');
require_once('extra.php');
require_once('extpricing.php');
require_once('giveaway.php');
require_once('prize.php');
require_once('winner.php');
require_once('option.php');
require_once('optvalue.php');
require_once('gawext.php');

require_once('configmanager.php');
require_once('langmanager.php');


/************************************************
Loads available giveaway options.

Input parameters:
  func: 'loadOPT'

Server response:
  Array of objects with available options (object 'optiontype')
  'REQUEST_ERROR' on other error
************************************************/
function loadOPT()
{
	// select all option types
	$result = [];
	$opttypes = Opttype::selectAll();
	foreach($opttypes as $opt)
	{
		// get option pricing
		$pri = Optpricing::selectByOtyId($opt->oty_id);
		$result[]->opttype = $opt;
		$result[]->optpricing = $pri;
	}
	return json_encode($result);
}


/************************************************
Loads available giveaway extras.

Input parameters:
  func: 'loadEXT'

Server response:
  Array of objects with available extras (object 'extratype')
  'REQUEST_ERROR' on other error
************************************************/
function loadEXT()
{	
	// select all extras
	$result = [];
	$extras = Extra::selectAll();
	foreach($extras as $ext)
	{
		// get extra pricing
		$pri = Extpricing::selectByExtId($ext->ext_id);
		$result[]->extra = $ext;
		$result[]->extpricing = $pri;
	}
	return json_encode($result);
}


/************************************************
Creates a new giveaway.

Input parameters:
  func: 'newGAW'
  gaw_name: new name for giveaway
  gaw_owner: new owner of the giveaway
  gaw_starttime: new start time for giveaway
  gaw_endtime: new end time for giveaway
  gaw_description: new description for giveaway (optional)
  gaw_terms: new terms for giveaway (optional)
  gaw_url: new URL for giveaway (optional)

Server response:
  Giveaway ID if creation is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function newGAW()
{
	$gaw_name = $_POST['gaw_name'];
  	$gaw_owner = $_POST['gaw_owner'];
  	if(isset($_POST['gaw_starttime']))
  		$gaw_starttime = $_POST['gaw_starttime'];
  	if(isset($_POST['gaw_endtime']))
  		$gaw_endtime = $_POST['gaw_endtime'];
  	if(isset($_POST['gaw_description']))
 	 	$gaw_description = $_POST['gaw_description'];
 	if(isset($_POST['gaw_terms']))
  		$gaw_terms = $_POST['gaw_terms'];
  	if(isset($_POST['gaw_url']))
	  	$gaw_url = $_POST['gaw_url'];
	  	
	if(isset($gaw_name) && isset($gaw_owner) && isset($gaw_starttime) && isset($gaw_endtime))
	{
		// find user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// TODO generate URL??
			
			// check values
			$creattime = date("Y-m-d");
			if(!isset($gaw_starttime))
				$gaw_starttime = date("Y-m-d", strtotime('+1 day'));
			if(!isset($gaw_endtime))
				$gaw_endtime = date("Y-m-d", strtotime('+1 month'));
			if(!isset($gaw_description))
				$gaw_description = '';
			if(!isset($gaw_terms))
				$gaw_terms = '';
			if(!isset($gaw_url))
				$gaw_url = '';
			
			// widget key hashed from current time and user ID
			$hashstr = $creattime.$usr->usr_email;
			$widgetkey = hash("md5", $hashstr);
			
			// insert giveaway into DB
			$gaw = new Giveaway($usr->usr_id, $gaw_name, $gaw_owner, $gaw_starttime, $gaw_endtime,
								$gaw_description, $gaw_terms, $widgetkey, $creattime, $gaw_url, false);
			if(!$gaw->insert())
				return json_encode('REQUEST_ERROR');
			
			ConfigManager::getInstance()->logDebug('Giveaway '.$gaw->toJSON().' created');
			
			// set state
			$gaw->gaw_state = $gaw->state();
			
			// don't send certain properties to client
			unset($gaw->gaw_usrid);
			unset($gaw->gaw_closed);
			
			return json_encode($gaw->gaw_id);
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
Loads an existing giveaway.

Input parameters:
  func: 'loadGAW'
  gaw_id: ID of giveaway to be loaded

Server response:
  Object of type 'giveawaydata' containing the requested giveaway if load is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function loadGAW()
{
	$gaw_id = $_POST['gaw_id'];
	if(isset($gaw_id))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find giveaway to be loaded
			$gaw = Giveaway::selectById($gaw_id);
			if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
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
				
				// load giveaway extras
				$result->extra = [];
				$gawextras = Gawext::selectByGawId($gaw_id);
				foreach($gawextras as $gawext)
				{
					$ext = Extra::selectById($gawext->gex_extid);
					if(isset($ext))
						$result->extra[] = $ext;
				}
				
				// calculate giveaway state
				$gaw->gaw_state = $gaw->state();
				
				ConfigManager::getInstance()->logDebug('Giveaway '.json_encode($result).' loaded');
				
				// don't send certain properties to client
				unset($gaw->gaw_usrid);
				unset($gaw->gaw_closed);
			
				return json_encode($result);
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Loads list of existing giveaways for given user.

Input parameters:
  func: 'loadGAWlist'
  
Server response:
  Array of objects from table 'giveaway' containing the requested list if load is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function loadGAWList()
{
	// get session user
	$usr = User::selectBySessionId();
	if(isset($usr))
	{
		// load all user giveaways
		$result = Giveaway::selectByUsrId($usr->usr_id);
		
		// delete and update properties
		foreach($result as $gaw)
		{
			$gaw->gaw_state = $gaw->state();
			unset($gaw->gaw_usrid);
			unset($gaw->gaw_closed);
		}
		
		ConfigManager::getInstance()->logDebug('Giveaway list for user '.$usr->usr_id.' loaded');
		return json_encode($result);
	}
	else
	{
		return json_encode('SESSION_ERROR');
	}
}


/************************************************
Saves modified giveaway data.

Input parameters:
  func: 'saveGAW'
  gaw_id: ID of giveaway which is being edited
  gaw_name: new name for giveaway (optional)
  gaw_owner: new owner of the giveaway (optional)
  gaw_starttime: new start time for giveaway (optional)
  gaw_endtime: new end time for giveaway (optional)
  gaw_description: new description for giveaway (optional)
  gaw_terms: new terms for giveaway (optional)
  gaw_url: new URL for giveaway (optional)
  gaw_closed: new closed state (optional)
  
Server response:
  Giveaway ID if update is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function saveGAW()
{
	$gaw_id = $_POST['gaw_id'];
	if(isset($_POST['gaw_name']))
		$gaw_name = $_POST['gaw_name'];
	if(isset($_POST['gaw_owner']))
	  	$gaw_owner = $_POST['gaw_owner'];
  	if(isset($_POST['gaw_starttime']))
	  	$gaw_starttime = $_POST['gaw_starttime'];
  	if(isset($_POST['gaw_endtime']))
	  	$gaw_endtime = $_POST['gaw_endtime'];
 	if(isset($_POST['gaw_description']))
	 	$gaw_description = $_POST['gaw_description'];
  	if(isset($_POST['gaw_terms']))
	  	$gaw_terms = $_POST['gaw_terms'];
  	if(isset($_POST['gaw_url']))
	  	$gaw_url = $_POST['gaw_url'];
	if(isset($_POST['gaw_closed']))
	  	$gaw_closed = $_POST['gaw_closed'];
		
	if(isset($gaw_id) && (isset($gaw_name) || isset($gaw_owner) || isset($gaw_description) || 
	   isset($gaw_starttime) || isset($gaw_endtime) || isset($gaw_terms) || isset($gaw_url)
	   || isset($gaw_closed)))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find giveaway to be saved
			$gaw = Giveaway::selectById($gaw_id);
			if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
			{
				// update giveaway data
				if(isset($gaw_name))
					$gaw->gaw_name = $gaw_name;
				if(isset($gaw_owner))
					$gaw->gaw_owner = $gaw_owner;
				if(isset($gaw_starttime))
					$gaw->gaw_starttime = $gaw_starttime;
				if(isset($gaw_endtime))
					$gaw->gaw_endtime = $gaw_endtime;
				if(isset($gaw_description))
					$gaw->gaw_description = $gaw_description;
				if(isset($gaw_terms))
					$gaw->gaw_terms = $gaw_terms;
				if(isset($gaw_url))
					$gaw->gaw_url = $gaw_url;
				if(isset($gaw_closed))
					$gaw->gaw_closed = $gaw_closed;
				
				$gaw->update();
				
				ConfigManager::getInstance()->logDebug('Giveaway '.$gaw->toJSON().' saved');
				return json_encode($gaw->gaw_id);
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Deletes an existing giveaway.

Input parameters:
  func: 'deleteGAW'
  gaw_id: giveaway ID to be deleted

Server response:
  'OK' if deletion is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function deleteGAW()
{
	$gawid = $_POST['gaw_id'];
	if(isset($gawid))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find giveaway to be deleted
			$gaw = Giveaway::selectById($gawid);
			if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
			{
				if(!$gaw->delete())
					return json_encode('REQUEST_ERROR');
				
				ConfigManager::getInstance()->logDebug('Giveaway '.$gaw->toJSON().' deleted');
				return json_encode('OK');
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Creates a new giveaway option.

Input parameters:
  func: 'newOPT'
  opt_gawid: ID of giveaway which is being added an option
  opt_otyid: option type ID
  opt_points: points for the new option
  opt_daily: option can be daily or not
  opt_mandatory: option is mandatory or not
  ovl_value: option value (optional)

Server response:
  Option ID if creation is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function newOPT()
{
	$opt_gawid = $_POST['opt_gawid'];
  	$opt_otyid = $_POST['opt_otyid'];
  	$opt_points = $_POST['opt_points'];
  	$opt_daily = $_POST['opt_daily'];
  	$opt_mandatory = $_POST['opt_mandatory'];
  	//if(isset($_POST['ovl_tag1']))
	//  	$ovl_tag1 = $_POST['ovl_tag1'];
  	if(isset($_POST['ovl_value']))
	  	$ovl_value = $_POST['ovl_value'];
  	//if(isset($_POST['ovl_tag2']))
  	//	$ovl_tag2 = $_POST['ovl_tag2'];
	//if(isset($_POST['ovl_value2']))
	// 	$ovl_value2 = $_POST['ovl_value2'];
	
	if(isset($opt_gawid) && isset($opt_otyid) && isset($opt_points) && isset($opt_daily) && isset($opt_mandatory))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find giveaway and check user id
			$gaw = Giveaway::selectById($opt_gawid);
			if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
			{
				// insert new option into DB
				$opt = new Option($opt_gawid, $opt_otyid, $opt_points, $opt_daily, $opt_mandatory);
				if(!$opt->insert())
					return json_encode('REQUEST_ERROR');

				ConfigManager::getInstance()->logDebug('Option '.$opt->toJSON().' created');
				
				// insert option values
				if(isset($ovl_value))
				{
					$ovl1 = new Optvalue($opt->opt_id, "1", $ovl_value);
					if(!$ovl1->insert())
						return json_encode('REQUEST_ERROR');
				}
				/*if(isset($ovl_tag2) && isset($ovl_value2))
				{
					$ovl2 = new Optvalue($opt->opt_id, $ovl_tag2, $ovl_value2);
					if(!$ovl2->insert())
						return json_encode('REQUEST_ERROR');
					ConfigManager::getInstance()->logDebug('Option value '.$ovl2->toJSON().' created');
				}*/
				return json_encode($opt->opt_id);
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Saves changes in an option from a giveaway.

Input parameters:
  func: 'saveOPT'
  opt_id: option ID which is being saved
  opt_points: new points for the option (optional)
  opt_daily: option can be daily or not (optional)
  opt_mandatory: option is mandatory or not (optional)
  ovl_value: new value for option (optional)

Server response:
  Option ID if save is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error

************************************************/
function saveOPT()
{
  	$opt_id = $_POST['opt_id'];
  	if(isset($_POST['opt_points']))
	  	$opt_points = $_POST['opt_points'];
  	if(isset($_POST['opt_daily']))
	  	$opt_daily = $_POST['opt_daily'];
  	if(isset($_POST['opt_mandatory']))
	  	$opt_mandatory = $_POST['opt_mandatory'];
  	//if(isset($_POST['ovl_tag1']))
	//  	$ovl_tag1 = $_POST['ovl_tag1'];
  	if(isset($_POST['ovl_value']))
	  	$ovl_value = $_POST['ovl_value'];
	  	
  	//if(isset($_POST['ovl_tag2']))
	//  	$ovl_tag2 = $_POST['ovl_tag2'];
  	//if(isset($_POST['ovl_value2']))
	//  	$ovl_value2 = $_POST['ovl_value2'];

	if(isset($opt_id))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find option and check user id
			$opt = Option::selectById($opt_id);
			if(isset($opt))
			{
				// check owner user id
				$gaw = Giveaway::selectById($opt->opt_gawid);
				if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
				{
					// update option
					if(isset($opt_points))
						$opt->opt_points = $opt_points;
					if(isset($opt_daily))
						$opt->opt_daily = $opt_daily;
					if(isset($opt_mandatory))
						$opt->opt_mandatory = $opt_mandatory;

					$opt->update();

					ConfigManager::getInstance()->logDebug('Option '.$opt->toJSON().' saved');
					
					// insert option values
					if(isset($ovl_value))
					{
						$ovl1 = Optvalue::selectByOptIdAndTag($opt->opt_id, "1");
						if(!isset($ovl1) || empty($ovl1))
						{
							$ovl1 = new Optvalue($opt->opt_id, "1", $ovl_value);
							if(!$ovl1->insert())
								return json_encode('REQUEST_ERROR');
						}
						else
						{
							$ovl1->ovl_value = $ovl_value;
							$ovl1->update();
						}
						ConfigManager::getInstance()->logDebug('Option value '.$ovl1->toJSON().' saved');
					}
					/*if(isset($ovl_tag2) && isset($ovl_value2))
					{
						$ovl2 = Optvalue::selectByOptIdAndTag($opt->opt_id, $ovl_tag2);
						$ovl2->ovl_value = $ovl_value2;
						$ovl2->update();
						ConfigManager::getInstance()->logDebug('Option value '.$ovl2->toJSON().' saved');
					}*/
				
					return json_encode($opt->opt_id);
				}
				else
				{
					return json_encode('REQUEST_ERROR');
				}
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Deletes an option from a giveaway.

Input parameters:
  func: 'deleteOPT'
  opt_id: option ID to be deleted

Server response:
  'OK' if deletion is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function deleteOPT()
{
	$opt_id = $_POST['opt_id'];
	if(isset($opt_id))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find option to be deleted
			$opt = Option::selectById($opt_id);
			if(isset($opt))
			{
				// check owner user id
				$gaw = Giveaway::selectById($opt->opt_gawid);
				if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
				{
					// delete option
					// associated option values are deleted on cascade
					if(!$opt->delete())
						return json_encode('REQUEST_ERROR');
				
					ConfigManager::getInstance()->logDebug('Option '.$opt->toJSON().' deleted');
					
					return json_encode('OK');
				}
				else
				{
					return json_encode('REQUEST_ERROR');
				}
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Adds an extra to a giveaway.

Input parameters:
  func: 'newEXT'
  ext_gawid: ID of giveaway which is being added an extra
  ext_id: ID of extra being added

Server response:
  'OK' if addition is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function newEXT()
{
	$ext_gawid = $_POST['ext_gawid'];
  	$ext_id = $_POST['ext_id'];
	if(isset($ext_gawid) && isset($ext_id))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find giveaway and check user id
			$gaw = Giveaway::selectById($ext_gawid);
			if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
			{
				// insert new extra into DB
				$gawext = new Gawextra($ext_gawid, $ext_id);
				if(!$gawext->insert())
					return json_encode('REQUEST_ERROR');
				
				ConfigManager::getInstance()->logDebug('Extra added to giveaway: '.$gawext->toJSON());
				
				return json_encode('OK');
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Deletes an extra from a giveaway.

Input parameters:
  func: 'deleteEXT'
  ext_gawid: ID of giveaway which is being deleted an extra
  ext_id: ID of extra being deleted

Server response:
  'OK' if deletion is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function deleteEXT()
{
	$ext_gawid = $_POST['ext_gawid'];
	$ext_id = $_POST['ext_id'];
	if(isset($ext_gawid) && isset($ext_id))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// check owner user id
			$gaw = Giveaway::selectById($ext_gawid);
			if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
			{
				// find extra to be deleted
				$gawext = Gawext::selectById($ext_gawid, $ext_id);
				if(isset($gawext))
				{
					// delete giveaway - extra association
					if(!$gawext->delete())
						return json_encode('REQUEST_ERROR');
				
					ConfigManager::getInstance()->logDebug('Extra deleted from giveaway: '.$gawext->toJSON());
					
					return json_encode('OK');
				}
				else
				{
					return json_encode('REQUEST_ERROR');
				}
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Adds a prize to a giveaway.

Input parameters:
  func: 'newPRI'
  pri_gawid: ID of giveaway which is being added a prize
  pri_name: name of the prize
  pri_quantity: number of prize items
  pri_order: order of prize in giveaway
  pri_image: URL of prize image (optional)

Server response:
  Prize ID if creation is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function newPRI()
{
	$pri_gawid = $_POST['pri_gawid'];
  	$pri_name = $_POST['pri_name'];
  	$pri_quantity = $_POST['pri_quantity'];
  	$pri_order = $_POST['pri_order'];
  	$pri_image = null;
  	if(isset($_POST['pri_image']))
	  	$pri_image = $_POST['pri_image'];
	if(isset($pri_gawid) && isset($pri_name) && isset($pri_quantity) && isset($pri_order))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find giveaway and check user id
			$gaw = Giveaway::selectById($pri_gawid);
			if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
			{
				// insert new prize into DB
				$pri = new Prize($pri_gawid, $pri_name, $pri_quantity, $pri_image, $pri_order);
				if(!$pri->insert())
					return json_encode('REQUEST_ERROR');
				
				ConfigManager::getInstance()->logDebug('Prize '.$pri->toJSON().' created');
				
				return json_encode($pri->pri_id);
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Saves changes in a prize from a giveaway.

Input parameters:
  func: 'savePRI'
  pri_id: ID of prize that is being edited
  pri_name: new name for the prize (optional)
  pri_quantity: new number of prize items (optional)
  pri_order: new order of prize in giveaway (optional)
  pri_image: URL of prize image (optional)

Server response:
  Prize ID if edition is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function savePRI()
{
  	$pri_id = $_POST['pri_id'];
  	if(isset($_POST['pri_name']))
	  	$pri_name = $_POST['pri_name'];
  	if(isset($_POST['pri_quantity']))
	  	$pri_quantity = $_POST['pri_quantity'];
  	if(isset($_POST['pri_order']))
	  	$pri_order = $_POST['pri_order'];
  	if(isset($_POST['pri_image']))
	  	$pri_image = $_POST['pri_image'];
	if(isset($pri_id))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find prize and check user id
			$pri = Prize::selectById($pri_id);
			if(isset($pri))
			{
				// check owner user id
				$gaw = Giveaway::selectById($pri->pri_gawid);
				if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
				{
					// update prize
					if(isset($pri_name))
						$pri->pri_name = $pri_name;
					if(isset($pri_quantity))
						$pri->pri_quantity = $pri_quantity;
					if(isset($pri_order))
						$pri->pri_order = $pri_order;
					if(isset($pri_image))
						$pri->pri_image = $pri_image;
				
					$pri->update();
				
					ConfigManager::getInstance()->logDebug('Prize '.$pri->toJSON().' saved');
				
					return json_encode($pri->pri_id);
				}
				else
				{
					return json_encode('REQUEST_ERROR');
				}
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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


/************************************************
Deletes a prize from a giveaway.

Input parameters:
  func: 'deletePRI'
  pri_id: ID of prize being deleted

Server response:
  'OK' if deletion is successful
  'SESSION_ERROR' if session has expired or is invalid
  'REQUEST_ERROR' on other error
************************************************/
function deletePRI()
{
	$pri_id = $_POST['pri_id'];
	if(isset($pri_id))
	{
		// get session user
		$usr = User::selectBySessionId();
		if(isset($usr))
		{
			// find option to be deleted
			$pri = Prize::selectById($pri_id);
			if(isset($pri))
			{
				// check owner user id
				$gaw = Giveaway::selectById($pri->pri_gawid);
				if(isset($gaw) && $gaw->gaw_usrid == $usr->usr_id)
				{
					// delete prize
					if(!$pri->delete())
						return json_encode('REQUEST_ERROR');
				
					ConfigManager::getInstance()->logDebug('Prize '.$pri->toJSON().' deleted');
					
					return json_encode('OK');
				}
				else
				{
					return json_encode('REQUEST_ERROR');
				}
			}
			else
			{
				return json_encode('REQUEST_ERROR');
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
	case 'loadOPT':
		echo loadOPT();
		break;
		
	case 'loadEXT':
		echo loadEXT();
		break;
		
	case 'newGAW':
		echo newGAW();
		break;
		
	case 'loadGAW':
		echo loadGAW();
		break;
		
	case 'loadGAWList':
		echo loadGAWList();
		break;
		
	case 'saveGAW':
		echo saveGAW();
		break;
		
	case 'deleteGAW':
		echo deleteGAW();
		break;
		
	case 'newOPT':
		echo newOPT();
		break;
		
	case 'saveOPT':
		echo saveOPT();
		break;
	
	case 'deleteOPT':
		echo deleteOPT();
		break;
		
	case 'newEXT':
		echo newEXT();
		break;
	
	case 'deleteEXT':
		echo deleteEXT();
		break;
		
	case 'newPRI':
		echo newPRI();
		break;
		
	case 'savePRI':
		echo savePRI();
		break;
	
	case 'deletePRI':
		echo deletePRI();
		break;
}

?>