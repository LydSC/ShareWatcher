<?php

/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\ShareWatcher\Cron;

use \OCA\ShareWatcher\App\ShareWatcher;

class CheckSharesTask {

    public static function run() {
        $app = new ShareWatcher();
        
        $container = $app->getContainer();
        $r = $container->query('CheckSharesTaskService')->run();
       
    }

}

