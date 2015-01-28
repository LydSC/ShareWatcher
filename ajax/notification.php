<?php


// Sanity checks
OCP\JSON::callCheck ( );
OCP\JSON::checkLoggedIn ( );
OCP\JSON::checkAppEnabled ( 'sharewatcher' );

use \OCA\ShareWatcher\App\ShareWatcher;

try{
	 $app = new ShareWatcher();
	 
	/**
	* check if action is to modify notification_needed
	*/
	if(isset($_POST['action']) AND $_POST['action'] == 'setNotification')
	{
		$app->setNotification($_POST);
		\OCP\JSON::success();
	}
	else
	{

	}

}
catch(Exception $e)
{
	var_dump($e);
	return;
}