<?php 
	$file = 'ads';
	if ( isset($_GET['file']) && ($_GET['file'] == 'ad') ) 	$file = 'ad';


	$pathcss = '../' . MyAdsense_PATH . '/css/' . $file . get_user_option('admin_color') . '.css';
	if (!is_file($pathcss)) $pathcss = '../' . MyAdsense_PATH . '/css/' . $file . 'fresh' . '.css';

	include($file . '.php');
?>