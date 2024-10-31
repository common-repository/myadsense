						<div style='margin: 0 10px 0 10px'>
							<form id='settform' name='settform' action='' method='post'>
								<input type='hidden' id='formname' name='formname' value='settform' />
								<table class='form-table'>
									<tbody>
										<tr valign='top'>
											<th scope='row' style='width:250px;'>
												<b><?php _e('Google AdSense Account ID','MyAdsense'); ?></b>
											</th>
											<td> 
												<input type='text' size='50'  name='account' id='account' value='<?php echo $MyAdsense_settings['account']; ?>' style='<?php if ($MyAdsense_settings['account'] != ''){echo 'font-weight:bold; color:green;';} else { echo 'border:1px dotted red;'; }?>'/>
											</td>
										</tr>
										<tr valign='top'>
											<th scope='row'>
												<b><?php _e('Are we on a test site ?','MyAdsense'); ?></b>
											</th>
											<td> 
												<input name='test' type='checkbox' <?php echo( ($MyAdsense_settings['test']==true) ? 'checked ' : ' '); ?> />
											</td>
										</tr>
									</tbody>
								</table>
								<p class='submit'>
									<input type='submit' name='Submit' value='<?php  _e('Update','MyAdsense'); ?>' />
								</p>
							</form>
						</div>
