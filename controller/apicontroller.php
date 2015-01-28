<?php
namespace OCA\ShareWatcher\Controller;

use \OCP\AppFramework\Controller;

class ApiController extends Controller {

	public function __construct($appName, IRequest $request, IConfig $settings, $userId){
        parent::__construct($appName, $request);
        $this->settings = $settings;
        $this->userId = $userId;
    }

}