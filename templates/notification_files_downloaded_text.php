<?php
/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS 
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

print_unescaped($l->t("Following files were downloaded :\r\n"));

foreach ($_['sharings'] as $key => $sharing)
{	
	print_unescaped($l->t(" - %s by %s\r\n", array($sharing['name'], $sharing['share_with']))); 	
}
?>
--
<?php p($theme->getName() . ' - ' . $theme->getSlogan()); ?>
<?php print_unescaped("\n".$theme->getBaseUrl());