<?php

global $MyAdsense_settings;
//print_r($MyAdsense_settings);

//
// MANAGING CHECKBOX REQUESTS
//

if ( !empty( $_REQUEST['delete_ads'] ) ) 
{
	$ads_deleted = 0;
	foreach ($_REQUEST['delete_ads'] as $ad) : 	
		$ad_name =  $ad;
		
		switch (true)
		{
			case ( isset( $_REQUEST['deleteit'] )):
				$MyAdsense_settings = get_option('MyAdsense_options');
				unset ($MyAdsense_settings ['ads'] [$ad_name]);
				update_option('MyAdsense_options', $MyAdsense_settings);
				$ads_deleted++;
			break;
		}
	endforeach;

	$redirect_to = MyAdsense_edit_ads . '&deleted=' . $ads_deleted;

//
// MANAGING URL PARMS
//

	if ( !empty($_REQUEST['mode']) ) 			$redirect_to = add_query_arg('mode', 	$_REQUEST['mode'], 	$redirect_to);
	if ( !empty($_REQUEST['status']) )			$redirect_to = add_query_arg('status', 	$_REQUEST['status'], 	$redirect_to);
	if ( !empty($_REQUEST['s']) )				$redirect_to = add_query_arg('s', 		$_REQUEST['s'], 		$redirect_to);
	if ( !empty($_REQUEST['apage']) )			$redirect_to = add_query_arg('apage', 	$_REQUEST['apage'], 	$redirect_to);
	wp_redirect( $redirect_to );
} 
elseif (!empty($_GET['_wp_http_referer']) ) 
{
	 wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), stripslashes($_SERVER['REQUEST_URI'])));
	 exit;
}

$url_parms->mode 		= empty($_GET['mode'])  	? 'detail' 					: attribute_escape($_GET['mode']);
$url_parms->status 	= isset($_GET['status']) 	? attribute_escape($_GET['status']) : '';
$url_parms->s 		= isset($_GET['s']) 		? $_GET['s'] 				: '';
$url_parms->apage		= isset($_GET['apage'])		? $_GET['apage'] 				: 1;
if ($url_parms->apage == 1) unset($url_parms->apage);

$search = attribute_escape( $url_parms->s );

//
// MANAGING RESULTS FROM CHECKBOX OR EDITING
//

	$deleted 	= isset( $_GET['deleted'] ) 	? (int) $_GET['deleted'] 	: 0;
	$edited 	= isset( $_GET['edited'] ) 	? (int) $_GET['edited'] 	: 0;
	$new	 	= isset( $_GET['new'] ) 	? (int) $_GET['new'] 		: 0;
	$setdef 	= isset( $_GET['setdef'] ) 	? 	  $_GET['setdef'] 	: 'ok';

	if ('ko' == $setdef) 	
	{
		$faderr  =  __('Sorry ! Ad could not be set as default', 'MyAdsense');
	}
	else
	{
		if ($deleted > 0)	$fade .= sprintf( __ngettext( __('%s Ad deleted', 'MyAdsense') . '<br />', __('%s Ads deleted', 'MyAdsense'), $deleted ) . '<br />', $deleted );
		if ($edited > 0 )	$fade .=  __('Ad saved', 'MyAdsense')  . '<br />';
		if ($new > 0 )	$fade .=  __('Ad inserted', 'MyAdsense') . '<br />';
		if ($setdef > 0 )	$fade .= __('Ad set as default', 'MyAdsense') . '<br />';
	}

//
// MANAGING SUBSUBSUB URL
//

$status_links 	= array();

$stati 		= array('link' => __('Product \'Link\' only','MyAdsense'), 'ad' => __('Product \'Ad\' only','MyAdsense'));
$class		= ( '' === $url_parms->status ) ? ' class="current"' : '';
$status_links[] 	= "	<li><a href=\"" . MyAdsense_edit_ads . "&mode=$url_parms->mode\"$class>".__('Show All Ads','MyAdsense')."</a>";
foreach ( $stati as $status => $label ) {
	$class = '';

	if ( $status == $url_parms->status ) $class = ' class="current"';

	$status_links[] = "	<li><a href=\"" . MyAdsense_edit_ads . "&status=$status&mode=$url_parms->mode\"$class>" . $label . '</a>';
}
$subsubsub_urls = implode(' | </li>', $status_links) . '</li>';
unset($status_links);

//
// MANAGING DETAIL/LIST URL
//

$wmode = $url_parms->mode;
$url_parms->mode = 'detail';
$detail_url = MyAdsense::ad_url( MyAdsense_edit_ads, false ,$url_parms );
$url_parms->mode = 'list';
$list_url  	= MyAdsense::ad_url( MyAdsense_edit_ads, false ,$url_parms );
$url_parms->mode = $wmode;
unset($wmode);

//
// MANAGING PAGINATION
//

	$url_parms->apage		= isset($_GET['apage'])		? $_GET['apage'] : 1;

	do
	{
		$start = ( $url_parms->apage - 1 ) * 20;
		list($_ads, $total) = MyAdsense::get_ad_list( $url_parms, $start, 25 ); // Grab a few extra
		$url_parms->apage--;		

	} while ( $total <= $start );
	$url_parms->apage++;

	$ads 		= array_slice($_ads, 0, 20);
	$extra_ads 	= array_slice($_ads, 20);

	$page_links = paginate_links	(array(
							'base' => add_query_arg( 'apage', '%#%' ),
							'format' => '',
							'total' => ceil($total / 20),
							'current' => $url_parms->apage
							)
						);
	if ($url_parms->apage == 1) unset($url_parms->apage);

	$new = MyAdsense_ad . '&action=createad';

?>
<!--   MyAdsense ads_head début -->
<?php include ('ads_head.php'); ?>
<!--   MyAdsense ads_head fin -->
<?php
	switch (true)
	{
		case (isset($faderr)) :
			MyAdsense::printMessage($faderr, false);
		break;
		case (isset($fade)) :
			MyAdsense::printMessage($fade);
		break;
	}
?>

<div class="wrap">
	<form id="posts-filter" action="" method="get">
		<h2>
			<?php printf(__('Manage Ads (<a href="%s">add new</a>)','MyAdsense'), $new); echo("\n"); ?>
		</h2>
		<ul class="subsubsub">

<?php echo $subsubsub_urls; ?>

		</ul>
		<input type="hidden" name="page" value="<?php echo MyAdsense_PLUGIN_FOLDER; ?>/php/edit-ads.php" />
		<p id="post-search">
			<input type="text" id="ad-search-input" name="s" value="<?php echo $search; ?>" />
			<input type="submit" value="<?php _e( 'Search ads','MyAdsense' ); ?>" class="button" />
		</p>
		<input type="hidden" name="mode" value="<?php echo $url_parms->mode; ?>" />
		<input type="hidden" name="status" value="<?php echo $url_parms->status; ?>" />
	</form>
	<ul class="view-switch">
		<li <?php if ( 'detail' == $url_parms->mode ) echo "class='current'" ?>><a href="<?php echo $detail_url; ?>"><?php _e('Detail View','MyAdsense') ?></a></li>
		<li <?php if ( 'list'   == $url_parms->mode ) echo "class='current'" ?>><a href="<?php echo $list_url  ; ?>"><?php _e('List View','MyAdsense')   ?></a></li>
	</ul>
	<form id="ads-form" action="" method="post">
		<div class="tablenav">
<?php if ( $page_links ) echo "			<div class='tablenav-pages'>$page_links</div>"; ?>

			<div class="alignleft">
				<input type="submit" value="<?php _e('Delete','MyAdsense'); 	?>" name="deleteit" 	class="button-secondary" />
			</div>
			<br class="clear" />
		</div>
		<br class="clear" />
		<table class="widefat">
			<thead>
				<tr>
				    <th scope="col" style="text-align: center"><input type="checkbox" onclick="checkAll(document.getElementById('ads-form'));" /></th>
				    <th scope="col"><?php _e('Ad','MyAdsense') 			?></th>
				    <th scope="col"><?php _e('Channel','MyAdsense') 		?></th>
				    <th scope="col"><?php _e('Product','MyAdsense') 		?></th>
				    <th scope="col" style='text-align:center;' colspan=5 ><?php _e('Colors','MyAdsense')?></th>
				    <th scope="col"><?php _e('Format','MyAdsense')?></th>
				    <th scope="col"><?php _e('Actions','MyAdsense')?></th>
				</tr>
			</thead>
			<tbody id="the-ad-list" class="list:ad">
<?php
		MyAdsense::ad_row( $MyAdsense_settings['defaults']['ad'], $url_parms );

if ($ads) 
{
	foreach ($ads as $ad)
		MyAdsense::ad_row( $ad, $url_parms );
?>
			</tbody>
			<tbody id="the-extra-ad-list" class="list:ad" style="display: none;">
<?php
	foreach ($extra_ads as $ad)
		MyAdsense::ad_row( $ad, $url_parms );
?>
			</tbody>
		</table>
	</form>

	<form id="get-extra-ads" method="post" action="" class="add:the-extra-ad-list:" style="display: none;">
		<input type="hidden" name="s" 	value="<?php echo $search; 			?>" />
		<input type="hidden" name="mode" 	value="<?php echo $url_parms->mode; 	?>" />
		<input type="hidden" name="status"  value="<?php echo $url_parms->status; 	?>" />
		<input type="hidden" name="pageid" 	value="<?php echo isset($url_parms->apage) ? absint( $url_parms->apage ) : 1; ?>" />
		<?php wp_nonce_field( 'add-ad', '_ajax_nonce', false ); ?>
	</form>

	<div id="ajax-response"></div>
<?php
} else {
?>
			</tbody>
		</table>
	</form>
	<p>
		<?php _e('No results found.','MyAdsense') ?>
	</p>
<?php
}
?>
	<div class="tablenav">
<?php if ( $page_links ) echo "		<div class='tablenav-pages'>$page_links</div>"; ?>
		<br class="clear" />
	</div>
</div>


