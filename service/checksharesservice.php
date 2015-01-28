<?php

/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\ShareWatcher\Service;

use \OCP\DB;

/**
* On test pour chaque partage de chaqu User, si il existe un oc_share_watcher correspondant et si oui, on envoie un mail avec la demande de désactivé le partage
*/
class CheckSharesService
{
	
}
//OC_MailNotify_Mailing::sendEmail($text,$l->t('New message from '.$fromUid),$toUid);