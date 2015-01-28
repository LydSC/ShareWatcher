<?php
/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS 
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\ShareWatcher\Controller;

use \OCP\AppFramework\Controller;
use OCA\ShareWatcher\App;
use \OCP\User;

class PageController extends Controller {

    protected $settings;
    protected $userId;
    protected $l;

    public function __construct($appName, IRequest $request, IConfig $settings, $userId, IL10N $l){
        parent::__construct($appName, $request);
        $this->settings = $settings;
        $this->userId = $userId;
        $this->l = $l;
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
    public function index() {

        // check if the user has the right permissions to access the sharewatcher app
        \OCP\User::checkLoggedIn();
        $r = \OCP\App::checkAppEnabled('sharewatcher');

        $files_shared = App\ShareWatcher::getAllSharing(User::getUser());
        
        return $this->render('main', array(
            'uid'           => $this->userId,
            'appVersion'    => $this->settings->getAppValue($this->appName, 'installed_version'),
            'userLastLogin' => date('d/m/Y H:i:s', $this->settings->getUserValue($this->userId, 'login', 'lastLogin')),
            "files_shared" => $files_shared
        ));
    }
}