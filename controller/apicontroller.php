<?php
namespace OCA\ShareWatcher\Controller;

use \OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\IConfig;
use OCP\IL10N;
use OCP\JSON;

use OCA\ShareWatcher\AppInfo\Application as ShareWatcher;

class ApiController extends Controller {

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

}