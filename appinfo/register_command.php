<?php

/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS 
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 *
 */

$application->add(new OCA\ShareWatcher\Command\Populate);
$application->add(new OCA\ShareWatcher\Command\Download);