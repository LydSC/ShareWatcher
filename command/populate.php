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

// OCP\Share::unshare

class Populate extends Command {

	protected function configure() {
        $prefix = \OCP\Config::getSystemValue('dbtableprefix', 'oc_');

        $this
            ->setName('sharewatcher:populate')
            ->setDescription('Create random shares in ' . $prefix . 'share with existing files and users ')
            //->addArgument('nb', InputArgument::OPTIONAL, 'Number of days you want stats for.', DEFAULT_NB)
            //->addOption('truncate', 't', InputOption::VALUE_NONE, 'Delete all history datas before generating new ones.');
        ;
    }


	// @todo
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('Beginning');
        $sql = "SELECT * FROM *PREFIX*users";

        $query = \OCP\DB::prepare($sql);

        $result = $query->execute();

        while($row = $result->fetchRow()) {
            $users[] = $row;
        }  

        $output->writeln(' - '.count($users)." users");

        /**
        * Define how many users will get random shares
        */
        $nbUsersSharing = count($users)%5 + 1;

        $output->writeln(' - '.$nbUsersSharing." sharing users ");
        //var_dump($users);

        /**
        * Define witch users will get random shares
        */
        for($i=0;$i<$nbUsersSharing;$i++)
        {
        	$rand = rand(1, count($users));
        	$output->writeln(' - RAND : '.$rand);
        	$user = $users[$rand]['uid'];
        	/**
        	* Get existing files of ths users
        	*/ 
        	$sql = "SELECT * FROM *PREFIX*filecache 
        			INNER JOIN *PREFIX*storages 
        				ON numeric_id = storage
        				AND id = 'home::".$user."'
					WHERE path LIKE 'files\/%'";        	
			
        	$query = \OCP\DB::prepare($sql);      	        	

        	$query->bindParam(1, $user, \PDO::PARAM_STR);

        	$result = $query->execute();

        	/**
	        * Get all the files by the user
	        */
	        while($row = $result->fetchRow()) {
	            $files[] = $row;
	            //$output->writeln('  - '.$row['path']);
	        }

	        $success = false;
	        while ($success != true) 
	        {
	        	
	        }

        	//$output->writeln(' - '.$user." -> ".count($files). " files");

        }
    }
}
