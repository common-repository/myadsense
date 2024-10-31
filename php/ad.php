<?php
//
///////////////////////////////////////////////////////////////////
///////////////////             AD           //////////////////////
///////////////////////////////////////////////////////////////////
//

$wpvarstoreset = array('action');

for ($i=0; $i<count($wpvarstoreset); $i += 1) 
{
	$wpvar = $wpvarstoreset[$i];
	if (!isset($$wpvar)) 
	{
		if (empty($_POST["$wpvar"])) 
		{
			if (empty($_GET["$wpvar"])) 
			{
				$$wpvar = '';
			} else {
			$$wpvar = $_GET["$wpvar"];
			}
		} 
		else 
		{
			$$wpvar = $_POST["$wpvar"];
		}
	}
}

if (isset($_GET['mode'])) 	$mode 	= $_GET['mode'];		else $mode 	 = false;
if (isset($_GET['status'])) 	$status 	= $_GET['status'];	else $status = false;
if (isset($_GET['s'])) 		$s		= $_GET['s'];		else $s	 = false;
if (isset($_GET['apage'])) 	$apage	= $_GET['apage'];		else $apage	 = false;

$list_url 				= MyAdsense_edit_ads;

if ($mode) 		$list_url	= add_query_arg('mode',  $mode, $list_url);
if ($status)	$list_url	= add_query_arg('status', $status, $list_url);
if ($s) 		$list_url 	= add_query_arg('s', $s, $list_url);
if ($apage)		$list_url 	= add_query_arg('apage', $apage, $list_url);

$editing = true;
global $MyAdsense_settings;

switch($action) 
{

	case 'deletead' :

		$ad = $_GET['ad'];

		$MyAdsense_settings = get_option('MyAdsense_options');
		unset ($MyAdsense_settings ['ads'][$ad]);
		update_option('MyAdsense_options', $MyAdsense_settings);

		wp_redirect($list_url);

	break;
	case 'editedad' :

		$def = ($_POST['ad'] == $MyAdsense_settings ['defaults']['ad']) ? true : false ;

		$new = array();
		$new ['channel'] 			= $_POST['channel'];
		$new ['product'] 			= $_POST['product'];

		if ($def) 
		{
			$new ['adformat'] 		= $_POST['adformat'];
			$new ['adtype'] 			= $_POST['adtype'];
			$new ['linkformat'] 		= $_POST['linkformat'];
			$new ['linktype'] 		= $_POST['linktype'];
		}
		else
		{
			if ($new ['product'] == 'ad')
			{
				$new ['adformat'] 		= $_POST['adformat'];
				$new ['adtype'] 			= $_POST['adtype'];
				$new ['linkformat'] 		= '';
				$new ['linktype'] 		= '';
			}
			else
			{
				$new ['adformat'] 		= '';
				$new ['adtype'] 			= '';
				$new ['linkformat'] 		= $_POST['linkformat'];
				$new ['linktype'] 		= $_POST['linktype'];
			}
		}

		$new ['show_home'] 		= $_POST['show_home'];
		$new ['show_post'] 		= $_POST['show_post'];
		$new ['show_archive'] 		= $_POST['show_archive'];
		$new ['show_search'] 		= $_POST['show_search'];
		$new ['html_before'] 		= $_POST['html_before'];
		$new ['html_after'] 		= $_POST['html_after'];
		$new ['alternate_url'] 		= $_POST['alternate_url'];
		$new ['alternate_color'] 	= $_POST['alternate_color'];
		$new ['corner']		 	= $_POST['corner'];
		$new ['colors']['border'] 	= $_POST['color_border'];
		$new ['colors']['link'] 	= $_POST['color_link'];
		$new ['colors']['bg'] 		= $_POST['color_bg'];
		$new ['colors']['text'] 	= $_POST['color_text'];
		$new ['colors']['url'] 		= $_POST['color_url'];

		$new ['colors'] = clean_colors ($new ['colors']);

		$MyAdsense_settings = get_option('MyAdsense_options');

		if ($def) 	
		{
			$new ['ad'] = $_POST ['ad']; 
			$MyAdsense_settings ['defaults'] = $new;
		}
		else	
		{ 
			$MyAdsense_settings ['ads'][$_POST['ad']] = $new;
		}

		update_option('MyAdsense_options', $MyAdsense_settings);

		$key = array ('deleted','edited','new','setdef');
		$referredby = remove_query_arg( $key, $_POST['referredby']);
		wp_redirect($referredby . '&edited=1');
		
	break;
	case 'newad' :
		$new = array();
		$new ['channel'] 			= $_POST['channel'];
		$new ['product'] 			= $_POST['product'];
		$new ['adformat'] 		= $_POST['adformat'];
		$new ['adtype'] 			= $_POST['adtype'];
		$new ['linkformat'] 		= $_POST['linkformat'];
		$new ['linktype'] 		= $_POST['linktype'];
		$new ['show_home'] 		= $_POST['show_home'];
		$new ['show_post'] 		= $_POST['show_post'];
		$new ['show_archive'] 		= $_POST['show_archive'];
		$new ['show_search'] 		= $_POST['show_search'];
		$new ['html_before'] 		= $_POST['html_before'];
		$new ['html_after'] 		= $_POST['html_after'];
		$new ['alternate_url'] 		= $_POST['alternate_url'];
		$new ['alternate_color'] 	= $_POST['alternate_color'];
		$new ['corner'] 			= $_POST['corner'];
		$new ['colors']['border'] 	= $_POST['color_border'];
		$new ['colors']['link'] 	= $_POST['color_link'];
		$new ['colors']['bg'] 		= $_POST['color_bg'];
		$new ['colors']['text'] 	= $_POST['color_text'];
		$new ['colors']['url'] 		= $_POST['color_url'];
		$new ['colors'] = clean_colors ($new ['colors']);

		$MyAdsense_settings = get_option('MyAdsense_options');
		$MyAdsense_settings ['ads'][$_POST['ad_name']] = $new;
		update_option('MyAdsense_options', $MyAdsense_settings);

		$key = array ('deleted','edited','new','setdef');
		$referredby = remove_query_arg( $key, $_POST['referredby']);
		wp_redirect($referredby . '&new=1');

	break;
	case 'setdefad' :

		$ad = $_GET['ad'];

		$MyAdsense_settings = get_option('MyAdsense_options');



		if (isset($MyAdsense_settings ['ads'][$ad]))
		{
			$default = MyAdsense::get_settings ($ad,false); 
			unset ($default ['ad_name']);

			$MyAdsense_settings ['ads'][$MyAdsense_settings ['defaults']['ad']] = $MyAdsense_settings ['defaults'];
			unset ($MyAdsense_settings ['ads'][$MyAdsense_settings ['defaults']['ad']] ['ad']);

			$MyAdsense_settings ['defaults'] = $default;
			$MyAdsense_settings ['defaults']['ad'] = $ad;
			unset ($MyAdsense_settings ['ads'][$ad]);	

			update_option('MyAdsense_options', $MyAdsense_settings);		
	
			wp_redirect($list_url . '&setdef=1');
		}
		else
		{
			wp_redirect($list_url . '&setdef=ko');
		}
	break;
	case 'createad' :
		adform(false);
	break;
	case 'editad' :
		$ad  = $_GET['ad'];
		$def = false;
 		if ($ad == $MyAdsense_settings ['defaults']['ad']) $def = true;

		adform($ad,$def);
	break;
}
function clean_colors($colors)
{
	foreach ($colors as $key => $val)
	{
		$val_clean = $val;
		if (1 == strlen($val)) $val_clean = $val . $val . $val . $val . $val . $val;
		if (2 == strlen($val)) $val_clean = $val . $val . $val;
		if (3 == strlen($val)) $val_clean = substr($val,0,1) . substr($val,0,1) . substr($val,1,1)  . substr($val,1,1)  . substr($val,2,1) . substr($val,2,1);
		$colors[$key] = $val_clean;
	}
	return $colors;
}
function adform ($ad_name,$def=false)
{
	global $pathcss;
	global $MyAdsense_settings;

	if ($ad_name === false) $new = true;
	else				$new = false;

	$lang = substr(WPLANG,0,2);
	if ($lang == '') $lang = 'en';

	switch (true)
	{
		case($new) :
			$toprow_title 	= __('Create New Ad','MyAdsense');
			$form_action 	= 'newad';
			$form_extra 	= "' />\n";
			$savebutton 	= __('Create Ad','MyAdsense'); 
			$preview 		= __('View this Ad','MyAdsense');

			$ad_def = MyAdsense::get_settings($ad_name,true);

			$ad ['channel'] 			= '';
			$ad ['product'] 			= $ad_def ['product'] ;
			$ad ['adformat']	 		= '';
			$ad ['adtype'] 			= '';
			$ad ['linkformat']	 	= '';
			$ad ['linktype'] 			= '';
			$ad ['show_home']			= '';
			$ad ['show_post']			= '';
			$ad ['show_archive']		= '';
			$ad ['show_search']		= '';
			$ad ['html_before']		= '';
			$ad ['html_after'] 		= '';
			$ad ['alternate_url']		= '';
			$ad ['alternate_color']		= '';
			$ad ['corner']			= '0';
			$ad ['colors']['border']	= '';
			$ad ['colors']['link'] 		= '';
			$ad ['colors']['bg'] 		= '';
			$ad ['colors']['text'] 		= '';
			$ad ['colors']['url']		= '';

			$liad = '';
			if ( (('' == $ad_def['linktype']) || ('' == $ad_def['linkformat'])) && ('ad'   == $MyAdsense_settings['defaults']['product']) ) $liad = 'ad';
			if ( (('' == $ad_def['adtype'])   || ('' == $ad_def['adformat']  )) && ('link' == $MyAdsense_settings['defaults']['product']) ) $liad = 'link';
		break;
		default :
			$toprow_title 	= sprintf(__("Editing Ad # %s",'MyAdsense'), $ad_name);
			$form_action 	= 'editedad';
			$form_extra 	= "' />\n		<input type='hidden' name='ad' value='" . $ad_name . "' />\n";
			$savebutton 	= __('Save','MyAdsense'); 
			$preview = ($def) ? "<strong><blink style='color:red;'>" . __('Default Ad','MyAdsense') . "</blink></strong>" : __('View this Ad','MyAdsense');

			$ad_def = MyAdsense::get_settings($ad_name,true);

			$ad = MyAdsense::get_settings($ad_name,false);

			$liad = '';
			if (!$def)
			{
				if ( (('' == $ad_def['linktype']) || ('' == $ad_def['linkformat'])) && ('ad'   == $MyAdsense_settings['defaults']['product']) ) $liad = 'ad';
				if ( (('' == $ad_def['adtype'])   || ('' == $ad_def['adformat']  )) && ('link' == $MyAdsense_settings['defaults']['product']) ) $liad = 'link';

				$url_parms->ad 	= urlencode($ad_name);
				if (isset($_GET['mode'])) 	$mode 	= $_GET['mode'];		else $mode 	 = false;
				if (isset($_GET['status'])) 	$status 	= $_GET['status'];	else $status = false;
				if (isset($_GET['s'])) 		$s		= $_GET['s'];		else $s	 = false;
				if (isset($_GET['apage'])) 	$apage	= $_GET['apage'];		else $apage	 = false;

				$delete_url = MyAdsense_ad ."&action=deletead";
				$delete_url	= add_query_arg('ad',  urlencode($ad_name), $delete_url);
				if ($mode) 		$delete_url	= add_query_arg('mode',  $mode, $delete_url);
				if ($status)	$delete_url	= add_query_arg('status', $status, $delete_url);
				if ($s) 		$delete_url = add_query_arg('s', $s, $delete_url);
				if ($apage)		$delete_url = add_query_arg('apage', $apage, $delete_url);
			}
		break;
	}
	include('edit-ad-form.php');
}
?>