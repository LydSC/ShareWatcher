<?php
/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

\OCP\App::addNavigationEntry(array(

    // the string under which your app will be referenced in owncloud
    'id' => 'sharewatcher',

    // sorting weight for the navigation. The higher the number, the higher
    // will it be listed in the navigation
    'order' => 11,

    // the route that will be shown on startup
    'href' => \OCP\Util::linkToRoute('sharewatcher.page.index'),

    // the icon that will be shown in the navigation
    // this file needs to exist in img/example.png
    'icon' => \OCP\Util::imagePath('sharewatcher', 'sharewatcher.svg'),

    // the title of your application. This will be used in the
    // navigation or on the settings page of your app
    'name' => \OC_L10N::get('sharewatcher')->t('ShareWatcher')
));

OC::$CLASSPATH['OC_ShareWatcher_Hooks'] = 'sharewatcher/lib/hooks.php';


/**
 * cron task
 */
\OCP\Backgroundjob::addRegularTask('\OCA\ShareWatcher\Cron\checkSharesTask', 'run');
