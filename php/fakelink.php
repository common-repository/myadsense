<?php
define('DOING_AJAX', true);

require_once('../../../../wp-config.php');
require_once('../../../../wp-admin/includes/admin.php');

load_plugin_textdomain('MyAdsense',MyAdsense_PATH . '/lang');


$color_border = $_GET['color_border'];
$color_bg = $_GET['color_bg'];
$color_url = $_GET['color_url'];

?>		
<html>
<head>

<script type="text/javascript" src="../js/color.class.js"></script>
<script type="text/javascript">
	function MyAdsense_by_google (couleur)
	{
		blanc = new RGBColor('ffffff');
		noire = new RGBColor('000000');

		okblanc = blanc.contraste(couleur);
		oknoire = noire.contraste(couleur);

		fontcolor = 'ffffff';

			if ((blanc.bright > noire.bright) && (blanc.coldif > noire.coldif)) fontcolor = 'ffffff';
			if ((blanc.bright <= noire.bright) && (blanc.coldif <= noire.coldif)) fontcolor = '000000';

		document.getElementById('googlelink').style.color='#' + fontcolor;
	}
</script>





</head>
<body>
	<div style='height:90px;width:180px;margin:0 0px;'>
		<div id='link-color-border' style='font-family:arial,sans-serif;font-size:12px;font-weight:bold;text-align:left;text-decoration:none;color:#FFFFFF;background:#<?php echo $color_border; ?>;padding:2px'>
			<span id='googlelink'><u><?php _e('Ads by Google','MyAdsense'); ?></u></span>	
		</div>
		<div id='link-color-bg' style='background:#<?php echo $color_bg; ?>'>
			<div id='link-color-url' style='line-height:1.3em;font-family:arial,sans-serif;font-size:12px;text-align:left;text-decoration:underline;color:#<?php echo $color_url; ?>;padding:2px 0px 3px 8px'>
				<?php _e('Advertiser\'s ad text here','MyAdsense'); ?>
				<br>
				<?php _e('Advertiser\'s ad text here','MyAdsense'); ?>
				<br>
				<?php _e('Advertiser\'s ad text here','MyAdsense'); ?>
				<br>
				<?php _e('Advertiser\'s ad text here','MyAdsense'); ?>
				<br>
			</div>
		</div>
	</div>
<script type="text/javascript">
	window.onload= MyAdsense_by_google ('<?php echo $color_border; ?>');
</script>
</body>
