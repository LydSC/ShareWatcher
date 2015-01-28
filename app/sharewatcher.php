<?php
/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */
namespace OCA\ShareWatcher\App;

use \OCP\AppFramework\App;
use \OCA\ShareWatcher\Controller\PageController;
use \OCA\ShareWatcher\Controller\ApiController;
use \OCA\ShareWatcher\Service\CheckSharesTaskService;
use \OCA\ShareWatcher\Service\CheckSharesService;

use \OCA\ShareWatcher\Lib\MailAction;

use \OCP\DB;

class ShareWatcher extends App {


    /**
     * Define your dependencies in here
     */
    public function __construct(array $urlParams=array()){
        parent::__construct('sharewatcher', $urlParams);

        $container = $this->getContainer();

        /**
         * Controllers
         */
        $container->registerService('PageController', function($c){
            return new PageController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('CoreConfig'),
                $c->query('UserId'),
                $c->query('L10N')
            );
        });

        $container->registerService('ApiController', function($c){
            return new ApiController(
                $c->query('AppName'),
                $c->query('Request'),
                $c->query('CoreConfig'),
                $c->query('UserId')
            );
        });

        /**
         * Core
         */
        $container->registerService('UserId', function($c) {
            return \OCP\User::getUser();
        });

        $container->registerService('L10N', function($c) {
            return \OC_L10N::get($c['AppName']);
        });

        $container->registerService('CoreConfig', function($c) {
            return $c->query('ServerContainer')->getConfig();
        });

        $container->registerService('CheckSharesService', function($c) {
            return new CheckSharesService(
                    
                );
        });

        $container->registerService('CheckSharesTaskService', function($c) {
            return new CheckSharesTaskService(
                    $c->query('CheckSharesService'),
                    $c->query('MailAction')
                );
        });

        $container->registerService('MailAction', function($c) {
            return new MailAction(
                $c->query('AppName'),
                $c->query('L10N')
            );
        });

        $container->registerService('L10N', function($c) {
            return $c->query('ServerContainer')->getL10N($c->query('AppName'));
        });
    }

    /**
    * @param User $user
    * @return array        
    */
    public function getAllSharing(User $user)
    {
        $sql = 'SELECT *PREFIX*filecache.*, *PREFIX*share.*, *PREFIX*share_watcher.*
                FROM *PREFIX*share
                INNER JOIN oc_filecache ON item_source = oc_filecache.fileid
                LEFT OUTER JOIN *PREFIX*share_watcher
                    ON `id_share` = *PREFIX*share.id                    
                WHERE `uid_owner` = ?
                ORDER BY `stime` DESC'
                ;
        
       
        $query = DB::prepare($sql);
        
        $query->bindParam(1, $user, \PDO::PARAM_STR);

        $result = $query->execute();

        /**
        * Get all the shared files by the user
        */
        while($row = $result->fetchRow()) {
            $shared_files[$row['name']][] = $row;
        }
        //var_dump($shared_files);
        return $shared_files;
    }

    public function getUsersSharedFile($file)
    {        
        $sql = 'SELECT `share_with` from `oc_share` WHERE `item_source` = ?';

        $query = DB::prepare($sql);
        
        $query->bindParam(1, $file['item_source'], \PDO::PARAM_STR);
        
        $result = $query->execute();
        //var_dump($result);
        while($row = $result->fetchRow()) {            
            $users[] = $row;
        }

        return $users;
    }

    public function getUsersDownloadFile($file)
    {        
        $sql = 'SELECT `share_with` from `oc_share` WHERE `item_source` = ?';

        $query = DB::prepare($sql);
        
        $query->bindParam(1, $file['item_source'], \PDO::PARAM_STR);
        
        $result = $query->execute();
        //var_dump($result);
        while($row = $result->fetchRow()) {            
            $users[] = $row;
        }

        return $users;
    }


    /**
    * @param $data array (id_share, shareWith)
    */
    public function setNotification($data)
    {

        
        
        // If exists one share_watch ont the item source, an UPDATE is needed
        try
        {
            $sql = "SELECT * FROM *PREFIX*share_watcher WHERE id_share = ?";
            
            $query = DB::prepare($sql);
            $query->bindParam(1, $data['id_share'], \PDO::PARAM_STR);
            $result = $query->execute();

            while($row = $result->fetchRow()) {            
                $shares[] = $row;
            }
        }
        catch(Exception $e)
        {

        }

        if(count($shares) == 1)
        {

            $sql = "UPDATE *PREFIX*share_watcher SET notification_needed = ? WHERE id_share = ?";
            $query = DB::prepare($sql);

            $query->bindParam(1, $data['notification_needed'], \PDO::PARAM_STR);
            $query->bindParam(2, $data['id_share'], \PDO::PARAM_STR);
            
            $result = $query->execute();
        }
        // else, an INSERT is NEEDED
        elseif (count($shares) == 0)
        {
            $sql = "INSERT INTO *PREFIX*share_watcher (id_share, date_download, notification_needed, notification_sended) VALUES (?, ?, ?, ?)";
            $query = DB::prepare($sql);

            $query->bindValue(1, $data['id_share'], \PDO::PARAM_STR);
            $query->bindValue(2, '', \PDO::PARAM_STR);
            $query->bindValue(3,  $data['notification_needed'], \PDO::PARAM_STR);
            $query->bindValue(4, "0", \PDO::PARAM_STR);
            
            $result = $query->execute();
            
        }
        else
        {

        }
    }

    /**
    * @param $options (downloaded=true|false, notification_sended=true|false, notification_needed=true|false)
    * @return array sharings_files sort by users owner
    */
    public function getAllSharingsByUsers($options = array())
    {
        $sql = 'SELECT *PREFIX*filecache.*, *PREFIX*share.*, *PREFIX*share_watcher.*
                FROM *PREFIX*share
                INNER JOIN oc_filecache ON item_source = oc_filecache.fileid
                LEFT OUTER JOIN *PREFIX*share_watcher
                    ON `id_share` = *PREFIX*share.id';

        $sql .= "\r\nWHERE ";
        foreach ($options as $option => $value)
        {
            switch ($option) {
                case 'downloaded':
                    if($value)
                        $sql .= "date_download != '0000-00-00 00:00:00' AND ";
                    else
                        $sql .= "date_download = '0000-00-00 00:00:00' AND ";
                    break;
                
                case 'notification_sended':
                    if($value)
                        $sql .= "notification_sended = '1' AND ";
                    else
                        $sql .= "notification_sended = '0' AND ";
                    break;

                case 'notification_needed':
                    if($value)
                        $sql .= "notification_needed = '1' AND ";
                    else
                        $sql .= "notification_needed = '0' AND ";
                    break;

                default:
                    # code...
                    break;
            }

        }

        $sql .= "1";
       

        $sql .= '        
                ORDER BY `stime` DESC'
                ;
        
        //var_dump(str_replace("*PREFIX*", "oc_", $sql));

        $query = DB::prepare($sql);

        $result = $query->execute();

        while($row = $result->fetchRow()) {
            $sharings_files[$row['uid_owner']][] = $row;            
        }        

        return $sharings_files;
    }

    /**
    * @param $sharing
    */
    public function setNotfied($sharing)
    {
        $sql = "UPDATE *PREFIX*share_watcher SET notification_sended = 1 WHERE id_share = ?";

        $query = DB::prepare($sql);

        $query->bindValue(1, $sharing['id_share'], \PDO::PARAM_STR);
        try
        {
            $result = $query->execute();
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
    *   @param string uid
    *   @return array 
    */
    public function getUserFromUID($uid)
    {
        $sql = "SELECT displayname 
                FROM *PREFIX*users                
                WHERE uid = ? ";

        $query = DB::prepare($sql);

        $query->bindValue(1, $uid, \PDO::PARAM_STR);
        $result = $query->execute();

        $row = $result->fetchRow();

        $user['displayname'] = $row['displayname'];

        $sql = "SELECT * 
                FROM *PREFIX*preferences               
                WHERE userid = ? ";

        $query = DB::prepare($sql);

        $query->bindValue(1, $uid, \PDO::PARAM_STR);
        $result = $query->execute();

        while($row = $result->fetchRow())
        {
            $user[$row['configkey']] = $row['configvalue'];            
        }

        return $user;

    }

}