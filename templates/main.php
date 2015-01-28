<?php
/**
 * ownCloud - ShareWatcher
 *
 * @author Lydéric SAINT CRIQ
 * @copyright 2015 CNRS 
 * @license This file is licensed under the Affero General Public License version 3 or later. See the COPYING file.
 */

\OCP\Util::addStyle('sharewatcher', 'sharewatcher');
\OCP\Util::addStyle('files', 'files');

\OCP\Util::addScript('sharewatcher', 'lib/angular.min');

\OCP\Util::addScript('sharewatcher', 'app/sharewatcher');

?>

<div id="app-content" ng-app="ShareWatcher" ng-controller="ShareController">
	<div id='container'>
		<h1>
		<?php
    		p($l->t("List of all your shared files"));
    	?>
    	</h1>
		<h2>
		<?php
    		p($l->t("Foreach of your shared files, you can defined to be notified if the file is downloaded."));
    	?>
    	</h2>
    	<p>
		<?php
    		p($l->t("If the file is already downloaded, you can't change the notification setup. "));
    		p($l->t("We recommend you to unshare files already downloaded."));
    	?>
    	</p>
	</div>
	<table id="filestable" class="">
		<thead>
			<tr>
				<th><?php p($l->t('Filename')); ?></th>
				<th><?php p($l->t('Share with')); ?></th>
				<th><?php p($l->t('Downloaded ?')); ?></th>
				<th><?php p($l->t('Be notified ?')); ?></th>				
				<th><?php p($l->t('Unshare')); ?></th>
			</tr>		
		</thead>
		<tbody>
			<?php foreach($_['files_shared'] as $file => $sharings): ?>	
						
					<tr >
						<td><?php echo $file; ?></td>

						<td>
							<ol>
							<?php foreach($sharings as $sharing): ?>
								<li data-id='<?php echo $sharing['id']; ?>' 
									data-item-source='<?php echo $sharing['fileid']; ?>'
									data-type='<?php echo $sharing['item_type']; ?>' 
									data-share-with='<?php echo $sharing['share_with']; ?>'
									data-share-type='<?php echo $sharing['share_type']; ?>'>
									<?php if($sharing['share_with'] != null ): ?>
										<strong><?php echo $sharing['share_with']; ?></strong>
									<?php else: ?>
										<strong><i><?php p($l->t('mail adress')); ?></i></strong>
									<?php endif; ?>	
								</li>
							<?php endforeach; ?>
							</ol>
						</td>
						<td>
							<ol>
								<?php foreach($sharings as $sharing): ?>
								<li data-id='<?php echo $sharing['id']; ?>'
									data-item-source='<?php echo $sharing['fileid']; ?>' 
									data-type='<?php echo $sharing['item_type']; ?>' 
									data-share-with='<?php echo $sharing['share_with']; ?>'
									data-share-type='<?php echo $sharing['share_type']; ?>'> 	
									<?php if($sharing['date_download'] != null AND $sharing['date_download'] != '0000-00-00 00:00:00'): ?>
										<?php p($l->t('Yes')); ?>
									<?php else: ?>
										<?php p($l->t('No')); ?>
									<?php endif; ?>
								</li>
								<?php endforeach; ?>
							</ol>
						</td>
						<td>
							<ul>
								<?php foreach($sharings as $sharing): ?>
									<li data-id='<?php echo $sharing['id']; ?>' 
										data-item-source='<?php echo $sharing['fileid']; ?>'
										data-type='<?php echo $sharing['item_type']; ?>' 
										data-share-with='<?php echo $sharing['share_with']; ?>'
										data-share-type='<?php echo $sharing['share_type']; ?>'
									>						

										<input name='cb_<?php echo $sharing['share_with']; ?>_<?php echo $sharing['id']; ?>' 
											   type="checkbox"
											   ng-click="getNotification($event)"
											   original-title='<?php p($l->t('Be notified if %s download %s', array($sharing['share_with'], $file))); ?>'
												<?php if(!is_null($sharing['date_download']) AND $sharing['date_download'] != '0000-00-00 00:00:00' ): ?>
													disabled
												<?php endif; ?>								    								 

												<?php if($sharing['notification_needed'] == '1'): ?>
													checked												
												<?php endif; ?>
										/>
										<span class="action notification"></span>
									</li>
								<?php endforeach; ?>
							</ul>
						</td>
						<td>
							<ol>
								<?php foreach($sharings as $sharing): ?>
								<li data-id='<?php p($sharing['id']); ?>' 
									data-item-source='<?php echo $sharing['fileid']; ?>'
									data-type='<?php p($sharing['item_type']); ?>' 
									data-share-with='<?php p($sharing['share_with']); ?>'
									data-share-type='<?php p($sharing['share_type']); ?>'> 	
									<a class="action delete delete-icon" 
									   original-title="<?php p($l->t('Unshare %s with %s', array($file, $sharing['share_with']))); ?>"  
									   href="#" 
									   ng-click="unshare($event)">&nbsp;</a>
								</li>
								<?php endforeach; ?>
							</ol>
						</td>	
					</tr>
				
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

