<?php
	function select_option($list,$selected)
	{
		foreach($list as $key=>$value)
		{
			echo("\t\t\t\t\t\t\t\t\t\t\t\t"); 
?>
<option <?php selected( $key, $selected ); ?> value='<?php echo $key; ?>'><?php echo $value; ?></option>
<?php
		}
	}
?>

<?php include ('adsense.php'); ?>
<script type="text/javascript">
var  MyAdsense_PATH ='<?php echo get_settings('siteurl') . '/' . MyAdsense_PATH; ?>';
</script>
<script type='text/javascript' src='../<?php echo MyAdsense_PATH ; ?>/js/ad.js?'></script>
<script type="text/javascript" src="../<?php echo MyAdsense_PATH ; ?>/js/color.class.js"></script>
<script type="text/javascript">
	function focusit() 
	{ // focus on first input field
		document.adform.ad_name.focus();
	}
	addLoadEvent(focusit);
<?php if (!$def) { ?>
	addLoadEvent(MyAdsense_product_<?php echo $ad_def['product']; ?>);
<?php } ?>
</script>
<link rel='stylesheet' href='<?php echo $pathcss; ?>' type='text/css' />
<form name='adform' action='' method='post' id='adform' autocomplete='off' >

	<div class='wrap'>

		<h2><?php echo $toprow_title; ?></h2>
		<input type='hidden' name='user_ID' value='<?php echo $user_ID; ?>' />
		<input type='hidden' name='action' value='<?php echo $form_action . $form_extra; ?>

		<div id='poststuff'>



			<div id='submitcomment' class='submitbox' style='margin-top:13px;'>

				<div id="previewview">
					<a>
						<?php echo $preview; ?>
					</a>
				</div>
				<div class="inside">
					<br class="clear" />
					<div id='adad'>
						<?php echo MyAdsense::get_fake($ad_name,'ad',$lang); ?>
<?php if ($def) { ?>
						<br class="clear" />
						<br class="clear"/>
<?php } ?>
					</div>
					<div id='adlink'>
						<?php echo MyAdsense::get_fake($ad_name,'link',$lang); ?>
					</div>

				</div>
				<p class="submit">
					<input value='<?php echo $savebutton; ?>' type="submit" style="font-weight: bold;" tabindex="4"/>
<?php if ((!$def) && (!$new)){ ?>
					<a class="submitdelete" href='<?php echo $delete_url; ?>' onclick="if ( confirm('<?php _e('You are about to delete this ad','MyAdsense'); ?>') ) { return true;}return false;">
						<?php _e('Delete&nbsp;ad','MyAdsense'); ?>
					</a>
<?php } ?>
					<br class="clear" />
				</p>
				<div class="side-info">
					<h5><?php _e('Related','MyAdsense'); ?></h5>
					<ul>
						<li><a href="admin.php?page=MyAdsense/php/edit-ads.php"><?php _e('Manage All ads','MyAdsense'); ?></a></li>
					</ul>
				</div>

				
			</div>

			<div id='post-body'>
				<table class='form-table'>
					<tbody>
						<tr valign='top'>
							<th scope='row'>
								<?php _e('Name','MyAdsense'); ?>
							</th>
							<td colspan=2>
								<input id='ad_name' name='ad_name' size='30' value='<?php echo $ad_name; ?>' <?php if (!$new) echo "disabled='disabled'";?>/>
							</td>
						</tr>
						<tr valign='top'>
							<th scope='row'>
								<?php _e('Channel','MyAdsense'); ?>
							</th>
							<td colspan=2>
								<input id='channel' name='channel' size='20' title='<?php _e('Enter multiple Channels seperated by + signs','MyAdsense'); ?>' value='<?php echo $ad['channel']; ?>'/>
							</td>
						</tr>
						<tr valign='top'>
							<th scope='row'>
								<?php _e('Product','MyAdsense'); ?>
							</th>
							<td colspan=2>
								<select name='product'<?php if (!$def) { ?> onchange='MyAdsense_product_options(this.value);'<?php } ?>>
<?php select_option($products,$ad['product']);?>
								</select>
							</td>
						</tr>
						<tr valign='top'>
							<th scope='row'>
								<?php _e('Layout','MyAdsense'); ?>
<!-- ad   -->
								<div id='div-adlib' style='text-align:right;font-size:12px;font-weight:normal;'>
									<?php _e('Ad','MyAdsense'); ?>
									<br/><br/>
								</div>
<!-- link -->
								<div id='div-linklib' style='text-align:right;font-size:12px;font-weight:normal;'>
									<?php _e('Link','MyAdsense'); ?>
								</div>
							</th>
							<td>
								<?php _e('Format','MyAdsense'); ?>
<!-- ad   -->						<br/>
								<div id='div-adformat'>
									<select name='adformat'>
<!-- Options taken from Google AdSense setup pages -->
<?php if (!$def) { if ('link' != $liad) { ?>
										<optgroup label='<?php _e('Default','MyAdsense'); ?>'>
<?php select_option($formats['default'],			$ad['adformat']);?>
										</optgroup>
<?php } } ?>
										<optgroup label='<?php _e('Horizontal','MyAdsense'); ?>'>
<?php select_option($formats['ads']['Horizontal'],	$ad['adformat']);?>
										</optgroup>
										<optgroup label='<?php _e('Vertical','MyAdsense'); ?>'>
<?php select_option($formats['ads']['Vertical'],	$ad['adformat']);?>
										</optgroup>
										<optgroup label='<?php _e('Square','MyAdsense'); ?>'>
<?php select_option($formats['ads']['Square'],		$ad['adformat']);?>
										</optgroup> 
									</select>
								</div>
<!-- link -->				
								<div id='div-linkformat'>
									<select name='linkformat'>
<!-- Options taken from Google AdSense setup pages -->
<?php if (!$def) { if ('ad' != $liad) { ?>
										<optgroup label='<?php _e('Default','MyAdsense'); ?>'>
<?php select_option($formats['default'],			$ad['linkformat']);?>
										</optgroup>
<?php } } ?>
										<optgroup label='<?php _e('Horizontal','MyAdsense'); ?>'>
<?php select_option($formats['links']['Horizontal'],	$ad['linkformat']);?>
										</optgroup>
										<optgroup label='<?php _e('Square','MyAdsense'); ?>'>
<?php select_option($formats['links']['Square'],	$ad['linkformat']);?>
										</optgroup> 
									</select>
								</div>
							</td>
							<td>
								<?php _e('Type','MyAdsense'); ?>
<!-- ad   -->						<br/>
								<div id='div-adtype'>
									<select name='adtype'>
<!-- Options taken from Google AdSense setup pages -->
<?php if (!$def) { if ('link' != $liad) { ?>
<?php select_option($default, $ad['adtype']);?>
<?php } } ?>
<?php select_option($adtypes, $ad['adtype']);?>
									</select>
								</div>
<!-- link -->				
								<div id='div-linktype'>
									<select name='linktype'>
<!-- Options taken from Google AdSense setup pages -->
<?php if (!$def) { if ('ad' != $liad) { ?>
<?php select_option($default,		$ad['linktype']);?>
<?php } } ?>
<?php select_option($linktypes,	$ad['linktype']);?>
									</select>
								</div>
							</td>
						</tr>
						<tr valign='top'>
							<th scope='row'>
									<?php _e('Ad Colours','MyAdsense'); ?>
							</th>
							<td colspan=2>
								<table width=500px>
									<tr>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('Border','MyAdsense'); ?>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('Title','MyAdsense'); ?>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('Background','MyAdsense'); ?>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('Text','MyAdsense'); ?>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('URL','MyAdsense'); ?>
										</td>
									</tr>		
									<tr>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											#
											<input name='color_border' id='color_border' onchange="MyAdsense_set_url('<?php echo $lang;?>');" size='6' value='<?php echo $ad['colors']['border']; ?>'/>
											<input name='color_border_def'    id='color_border_def' value='<?php echo $ad_def['colors']['border']; ?>' type='hidden' />
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											#
											<input name='color_link' id='color_link' onchange="MyAdsense_set_url('<?php echo $lang;?>');" size='6' value='<?php echo $ad['colors']['link']; ?>'/>
											<input name='color_link_def'     id='color_link_def' 	value='<?php echo $ad_def['colors']['link']; ?>' type='hidden' />

										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											#
											<input name='color_bg' id='color_bg' onchange="MyAdsense_set_url('<?php echo $lang;?>');" size='6' value='<?php echo $ad['colors']['bg']; ?>'/>
											<input name='color_bg_def' 	id='color_bg_def' value='<?php echo $ad_def['colors']['bg']; ?>' type='hidden' />

										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											#
											<input name='color_text' id='color_text' onchange="MyAdsense_set_url('<?php echo $lang;?>');" size='6' value='<?php echo $ad['colors']['text']; ?>'/>
											<input name='color_text_def' 	  id='color_text_def' 	value='<?php echo $ad_def['colors']['text']; ?>' type='hidden' />

										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											#
											<input name='color_url' id='color_url' onchange="MyAdsense_set_url('<?php echo $lang;?>');" size='6' value='<?php echo $ad['colors']['url']; ?>'/>
											<input name='color_url_def' 	 id='color_url_def' value='<?php echo $ad_def['colors']['url']; ?>' type='hidden' />
										</td>
									</tr>		
								</table>					
							</td>
						</tr>	
						<tr valign='top' id='adcorner'>
							<th scope='row'>
									<?php _e('Corner Styles','MyAdsense'); ?>
							</th>
							<td colspan=2>
								<select name='corner' id='corner'  onchange="MyAdsense_set_url('<?php echo $lang;?>');" >
<?php if (!$def) { ?>
<?php select_option($default,	$ad['corner']);?>	
<?php } ?>
<?php select_option($corners,	$ad['corner']);?>
								</select>
							</td>
						</tr>	
						<tr valign='top'>
							<th scope='row'>
								<?php _e('Show Inline Ads','MyAdsense'); ?>	
							</th>
							<td colspan=2>
								<table width=500px>
									<tr>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('On Homepage','MyAdsense'); ?>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('On Archives','MyAdsense'); ?>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('On Posts/Pages','MyAdsense'); ?>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('On Search','MyAdsense'); ?>
										</td>
									</tr>		
									<tr>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<select name='show_home'>
<?php if (!$def) { ?>
<?php select_option($default,	$ad['show_home']);?>	
<?php } ?>
<?php select_option($yesno,	$ad['show_home']);?>
											</select>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<select name='show_post'>
<?php if (!$def) { ?>
<?php select_option($default,	$ad['show_post']);?>	
<?php } ?>
<?php select_option($yesno,	$ad['show_post']);?>
											</select>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<select name='show_archive'>
<?php if (!$def) { ?>
<?php select_option($default,	$ad['show_archive']);?>	
<?php } ?>
<?php select_option($yesno,	$ad['show_archive']);?>
										</select>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<select name='show_search'>
<?php if (!$def) { ?>
<?php select_option($default,	$ad['show_search']);?>	
<?php } ?>
<?php select_option($yesno,	$ad['show_search']);?>
											</select>
										</td>
									</tr>		
								</table>					
							</td>
						</tr>
						<tr valign='top'>
							<th scope='row'>
								<?php _e('HTML Markup (Optional)','MyAdsense'); ?>
							</th>
							<td colspan=2>
								<table width=500px>
									<tr>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('Before','MyAdsense'); ?>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<?php _e('After','MyAdsense'); ?>
										</td>
									</tr>		
									<tr>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<input name='html_before' size='40' title='<?php _e('Enter HTML to be included before Ad unit','MyAdsense'); ?>' value='<?php echo $ad['html_before']; ?>'/>
										</td>
										<td style='text-align:center;border:none;margin:0;padding:0;'>
											<input name='html_after' size='40' title='<?php _e('Enter HTML to be included after Ad unit','MyAdsense'); ?>' value='<?php echo $ad['html_after']; ?>'/>
										</td>
									</tr>
								</table>					
							</td>
						</tr>
						<tr valign='top'>
							<th scope='row'>
								<?php _e('Alternate Ads (Optional)','MyAdsense'); ?>
							</th>
							<td colspan=2>
								<?php _e('URL','MyAdsense'); ?>&nbsp;&nbsp;&nbsp;<input name='alternate_url' size='50' title='<?php _e('Enter URL to alternate Ad for display when Google Ad unavailable','MyAdsense'); ?>' value='<?php echo $ad['alternate_url']; ?>'/>
								<br/>
								<?php _e('Color','MyAdsense'); ?> #&nbsp;&nbsp;<input name='alternate_color' size='6' title='<?php _e('Enter #COLOUR to display when Google Ad unavailable','MyAdsense'); ?>' value='<?php echo $ad['alternate_color']; ?>'/>
							</td>
						</tr>
					</tbody>
				</table>
				<input name="referredby" type="hidden" id="referredby" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
			</div>
		</div>
	</div>
</form>
