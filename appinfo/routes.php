<?php

namespace OCA\ShareWatcher;

use \OCA\ShareWatcher\App\ShareWatcher;

$application = new ShareWatcher();
$application->registerRoutes($this, array('routes' => array(
    // page
    array('name' => 'page#index', 'url' => '/', 'verb' => 'GET'),
)));