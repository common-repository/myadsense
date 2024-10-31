<?php 
$pathcss = "../wp-content/plugins/" . MyAdsense_PLUGIN_FOLDER . "/css/admin" . get_user_option('admin_color'). ".css";
if (is_file($pathcss)) {?>
<link rel="stylesheet" href="../wp-content/plugins/<?php echo MyAdsense_PLUGIN_FOLDER; ?>/css/admin<?php echo get_user_option('admin_color'); ?>.css" type="text/css" />
<?php  } else {  ?>
<link rel="stylesheet" href="../wp-content/plugins/<?php echo MyAdsense_PLUGIN_FOLDER; ?>/css/adminfresh.css" type="text/css" />
<?php
}

global $MyAdsense_settings;
switch (true)
{
	case ($_POST['formname'] == 'settform'):
		$MyAdsense_settings ['account'] = (isset($_POST ['account'])) 	? $_POST ['account'] 	: '';
		$MyAdsense_settings ['test'] 	  = (isset($_POST ['test']))	 	? true 			: false;
		if (update_option('MyAdsense_options', $MyAdsense_settings))
		{
			MyAdsense::printMessage(__('Settings updated successfully !','MyAdsense'));
		}
		else
			MyAdsense::printMessage(__('Settings NOT updated !!','MyAdsense'),false);
	break;
	default :
	break;
}

?>

	<div class="wrap">
		<h2><?php _e('MyAdsense settings','MyAdsense'); ?></h2>
		<br/>
			<div id="fragment-1">
					<br/><?php include('setform.php'); ?><br/>
			</div>
	</div>
