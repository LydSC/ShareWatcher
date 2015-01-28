<?php

\OCP\Util::addStyle('sharewatcher', 'sharewatcher');
\OCP\Util::addStyle('files', 'files');

\OCP\Util::addScript('sharewatcher', 'lib/angular.min');

\OCP\Util::addScript('sharewatcher', 'app/sharewatcher');

?>

<div id="app-content" ng-app="ShareWatcher" ng-controller="ShareController">
	<div id='container'>
		<h1>Liste des fichiers partagés</h1>
		<h2>Vous trouvez ici les informations concernant vos fichiers partagés et s'ils ont été téléchargés</h2>
	</div>
	<table id="filestable" class="">
		<thead>
			<tr>
				<th>Nom du fichier</th>
				<th>Partagé avec</th>				
				<th>Supprimer le partage ?</th>
			</tr>		
		</thead>
		<tbody>
			<?php foreach($_['files_shared'] as $file => $shares): ?>	
				<?php foreach($shares as $share): ?>		
					<tr data-id='<?php echo $share['item_source']; ?>' 
						data-type='<?php echo $share['item_type']; ?>' 
						data-share-with='<?php echo $share['share_with']; ?>'
						data-share-type='<?php echo $share['share_type']; ?>'
					>
						<td><?php echo $file; ?></td>
						<td> 
							<?php if($share['share_with'] != null ): ?>
								<strong><?php echo $share['share_with']; ?></strong>
									<?php if($share['date_download'] != null ): ?>
										a déjà téléchargé le fichier
									<?php else: ?>
										n'a pas encore téléchargé le fichier
									<?php endif; ?>
							<?php else: ?>
								<strong><i>Une personne non identifiée</i></strong>
								<?php if($share['date_download'] != null ): ?>
										a déjà téléchargé le fichier
									<?php else: ?>
										n'a pas encore téléchargé le fichier
									<?php endif; ?>
							<?php endif; ?>								
						</td>					
						<td class="delete">						
							<div class="action delete delete-icon" original-title="Unshare" href="#" ng-click="showClicked($event)">&nbsp;</div>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</tbody>
	</table>

</div>
<div id="footer">
    <p>You're user id #<?= $_['uid']; ?> (last log : <?= $_['userLastLogin']; ?>) - ShareWatcher version #<?= $_['appVersion']; ?></p>
</div>
