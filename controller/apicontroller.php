<?php
namespace OCA\ShareWatcher\Controller;

use \OCP\AppFramework\APIController as OCAPIController;
use \OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\IConfig;
use OCP\IL10N;
use OCP\JSON;

use OCA\ShareWatcher\AppInfo\Application as ShareWatcher;

class ApiController extends OCAPIController {

	public function __construct($appName, IRequest $request, IConfig $settings, $userId){
        parent::__construct($appName, $request);
        $this->settings = $settings;
        $this->userId = $userId;
    }

    /**
     * ATTENTION!!!
     * The following comments turn off security checks
     * Please look up their meaning in the documentation:
     * http://doc.owncloud.org/server/master/developer_manual/app/appframework/controllers.html
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function notification() {
    	
    	//var_dump(debug_backtrace());
    	
    	// Sanity checks
		\OCP\JSON::callCheck ( );
		\OCP\JSON::checkLoggedIn ( );
		\OCP\JSON::checkAppEnabled ( 'sharewatcher' );

		try{
			$app = new ShareWatcher();
			 
			/**
			* check if action is to modify notification_needed
			*/
			if(isset($_POST['action']) AND $_POST['action'] == 'setNotification')
			{
				$app->setNotification($_POST);
				// @todo : Corriger bug : Ajoute null à la réponse ??!!
				//\OCP\JSON::success();
			}
			else
			{

			}

		}
		catch(Exception $e)
		{
			//var_dump($e);
			return;
		}

    }

    /**
    * @return Array Users list with the name_group in $_GET
    * @NoAdminRequired
    * @NoCSRFRequired
    */
    public function getusersingroup()
    {
    	// Sanity checks
		\OCP\JSON::callCheck ( );
		\OCP\JSON::checkLoggedIn ( );
		\OCP\JSON::checkAppEnabled ( 'sharewatcher' );

		try{
			$app = new ShareWatcher();		 

			$listUsers = $app->getUsersInGroup(htmlspecialchars($_GET['group']));

			\OCP\JSON::success(array("result" => $listUsers));
			//return new JSONResponse(array("result" => $listUsers, "status" => "success"));
		}
		catch(Exception $e)
		{
			//var_dump($e);
			\OCP\JSON::error();
		}		

		
    }

}