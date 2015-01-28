<?php

/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS 
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\ShareWatcher\Service;

use OC\Mail;

use OCA\ShareWatcher\Lib\MailAction as MailAction;
use \OCA\ShareWatcher\App\ShareWatcher;

class CheckSharesTaskService {

	protected $checkSharesService;
	protected $mailAction;

	/**
	* @param CheckSharesService
	* @param MailAction
	**/
	public function __construct(\OCA\ShareWatcher\Service\CheckSharesService $checkSharesService, MailAction $mailAction)
	{
		$this->checkSharesService = $checkSharesService;		
		$this->mailAction = $mailAction;
		
	}

	/**
	* Verify if a share has been downloaded and the notification mail hasn't been sended. 
	* If need, send the notification and put notification_sended to 1.
	*/
	public function run() 
	{
		$app = new ShareWatcher();
		
        $options = array('downloaded' => true, "notification_sended" => false, "notification_needed" => true);
        $allSharingDownloaded = $app->getAllSharingsByUsers($options);
        
        $mailAction = new MailAction();
        foreach ($allSharingDownloaded as $uid_user => $sharings) 
        {
        	$mail = $this->mailAction->mailNotificationcreation($sharings);
        	foreach ($sharings as $key => $sharing) 
	        {
	        	$result = $app->setNotfied($sharing);
	        	// @todo log if result = false
	        }
	        
	        $user = $app->getUserFromUID($uid_user);
	        

	        // sanitize user
	        if($user['displayname'] == "")
	        	$user['displayname'] = $user['uid'];
	        

	        // @todo manage exception (e.g. email empty)	        
	        $this->mailAction->send($user['email'], $user['displayname'], $mail['subject'], $mail['html'], $mail['altText']);	        
        }
        
		return 'OK';
    }
    
}

