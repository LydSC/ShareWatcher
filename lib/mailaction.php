<?php

/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\ShareWatcher\Lib;

use \OCP\IL10N;
use \OCP\IConfig;

/**
 * Send mail on hook trigger
 */
Class MailAction
{
    protected $appName;
    protected $l;
    
    /**
    * @param appName string
    * @param l IL10N (localisation)
    */
    public function __construct($appName, IL10N $l)
    {
        $this->appName = $appName;
        $this->l = $l;       
    }

    /**
    * @param Array sharings
    * @return mail Array(html, altText, subject)
    * Create template html and altText, subject with the localisation  
    */
    public function mailNotificationcreation($sharings)
    {    	
    	$mail['subject'] = $this->l->t("MyCore - Shared files downloaded");

    	foreach ($sharings as $key => $sharing)
    	{    		
    		if(is_null($sharing['share_with']))
    			$sharings[$key]['share_with'] = $this->l->t("unknown mail");   		
    	}

    	$html = new \OCP\Template($this->appName, "notification_files_downloaded_html", "");
        $html->assign('sharings', $sharings);
        $htmlMail = $html->fetchPage();

        $alttext = new \OCP\Template($this->appName, "notification_files_downloaded_text", "");
        $alttext->assign('sharings', $sharings);        
        $altMail = $alttext->fetchPage();    	

    	$mail['altText'] = $altMail;
    	$mail['html'] = $htmlMail;

    	return $mail;
    }

    /**
    * @param string toMail
    * @param string toDisplayName
    * @param string subject
    * @param string htmlMail
    * @param string altMail
    */
    public function send($toMail, $toDisplayName, $subject, $htmlMail, $altMail)
    {
    	$fromAddress = $fromName = \OCP\Util::getDefaultEmailAddress('owncloud');

    	\OCP\Util::sendMail($toMail, $toDisplayName, $subject, $htmlMail, $fromAddress, $fromName, 1, $altMail);
    }
}