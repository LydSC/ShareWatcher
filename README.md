# ShareWatcher

!! NOT FUNCTIONNAL !!

Owncloud pre 8 app where the user can see all his shared files and decided to be notified when the file is downloaded


Cron task is used to check last downloaded files and to notify users who ask for it

## Limits 

Hooks abour sharing are not functionnal in Owncloud v7. This app needs OC8 hooks to be finished

First try :

 - app/files/ajax/download.php 
```
/***
* USE ShareWatcher App
*/
use OCA\ShareWatcher\AppInfo\Application as ShareWatcher;

// Check if we are a user
OCP\User::checkLoggedIn();
\OC::$server->getSession()->close();

$files = isset($_GET['files']) ? (string)$_GET['files'] : '';
$dir = isset($_GET['dir']) ? (string)$_GET['dir'] : '';

$files_list = json_decode($files);
// in case we get only a single file
if (!is_array($files_list)) {
	$files_list = array($files);
}

/***
* START ShareWatcher App
*/
// Check if its the downloading of a shared item
try{
	// Foreach files downloaded
	foreach ($files_list as $key => $filename) {
		// it is !
		if($shared_item = ShareWatcher::checkIfSharedItem($_SESSION['user_id'], $dir, $filename))
		{
			// Save in db for the ShareWatcher App			
			ShareWatcher::setItemIsDownloaded($shared_item['id']);
		}
	}

}
catch (Exception $e)
{
	// Do nothing
	// @todo log !
}
/***
* END ShareWatcher App
*/

OC_Files::get($dir, $files_list, $_SERVER['REQUEST_METHOD'] == 'HEAD');
```

## Shell
To list all shared files by users :

```
cd [owncloud]/
./occ sharewatcher:download -l
```

To simulate the missing share hook, you can download a file by using : 
```
cd [owncloud]/
./occ sharewatcher:download [id_share]
```
where [id_share] is the id of the sharing in DB. Use -l to see all sharings.

## Contributing

This app is developed for an internal deployement of ownCloud at CNRS (French National Center for Scientific Research).

If you want to be informed about this ownCloud project at CNRS, please contact david.rousse@dsi.cnrs.fr, gilian.gambini@dsi.cnrs.fr or marc.dexet@dsi.cnrs.fr

## License and Author

|                      |                                          |
|:---------------------|:-----------------------------------------|
| **Author:**          | Lydéric SAINT CRIQ (lyderic.saint-criq@cnrs-dir.fr)
| **Copyright:**       | Copyright (c) 2015 CNRS
| **License:**         | AGPL v3, see the COPYING file.

