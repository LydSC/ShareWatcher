<?php

/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS 
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

namespace OCA\ShareWatcher\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use OC\DB\Connection;

use OCA\ShareWatcher\AppInfo\Application as ShareWatcher;

class Download extends Command {

	protected function configure() {
        $prefix = \OCP\Config::getSystemValue('dbtableprefix', 'oc_');

        $this
            ->setName('sharewatcher:download')
            ->setDescription('Simulate a download of a file by a user')
            
            ->addArgument('id_share', InputArgument::OPTIONAL, "id share")
            ->addArgument('notification_needed', InputArgument::OPTIONAL, "notification_needed")

            ->addOption('list', 'l', InputOption::VALUE_NONE, 'List all sharings by uid');
        ;
    }


	// @todo
    protected function execute(InputInterface $input, OutputInterface $output) {    	

    	$id_share = $input->getArgument('id_share');    	
    	$notification_needed = $input->getArgument('notification_needed');

    	if ($input->getOption('list')) 
    	{
            $this->listAllSharesByUsers($output);
        }

        if(is_numeric($id_share))
        {
        	$this->download($output, $id_share);
        }
    }


    protected function download($output, $id_share)
    {
        $app = new ShareWatcher();
    	$app->setItemIsDownloaded($id_share);

        $sql = "SELECT * FROM *PREFIX*share             		
        		WHERE id = ?";

    	$query = \OCP\DB::prepare($sql);      	        	

    	$query->bindParam(1, $id_share, \PDO::PARAM_STR);

    	$result = $query->execute();

    	while($row = $result->fetchRow()) {
            $files[] = $row;	            
        }
    

        $output->writeln($files[0]['file_target']. " downloaded by ".$files[0]['share_with']);

        
    }

    protected function listAllSharesByUsers($output)
    {
    	$sql = "SELECT * FROM *PREFIX*users";

        $query = \OCP\DB::prepare($sql);

        $result = $query->execute();

        while($row = $result->fetchRow()) {
            $users[] = $row;
        }  

        $output->writeln(' - '.count($users)." users");

        foreach ($users as $key => $user)
        {
			$output->writeln(' # uid_owner => '.$user['uid']);        	
        	$sql = "SELECT * FROM *PREFIX*share   
        			LEFT JOIN *PREFIX*share_watcher ON id = id_share
        			INNER JOIN *PREFIX*filecache ON fileid = item_source
        			WHERE uid_owner = ?";
        	
        	$query = \OCP\DB::prepare($sql);      	        	

        	$query->bindParam(1, $user['uid'], \PDO::PARAM_STR);

        	$result = $query->execute();

        	while($row = $result->fetchRow()) {
	            $files[] = $row;
	            //var_dump($row);
	            $downloaded = ""; 
	        	if(!is_null($row['date_download']) AND $row['date_download'] != "0000-00-00 00:00:00")	        	
		        	$downloaded = "already downloaded";

		        $notified = "";
		      	if($row['notification_sended'] == "1")
		      		$notified = " and notified";

	            $output->writeln('  - id_share = '.$row['id']." -> uid = ".$row['share_with']." (".$row['file_target']." ".$downloaded.$notified.")");
	        } 
        }
    }
}

