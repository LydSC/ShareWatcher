<?php

namespace OCA\ShareWatcher\AppInfo;

$application = new Application();
$application->registerRoutes($this, array('routes' => array(
    // page
    array('name' => 'page#index', 'url' => '/', 'verb' => 'GET'),
    array('name' => 'api#notification', 'url' => '/notification', 'verb' => 'POST'),
)));