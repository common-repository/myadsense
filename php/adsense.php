<?php

	$formats['default']			= array ('' => __('Use Default','MyAdsense'));

	$formats['ads']['Horizontal']		= array ('728x90'  => '728 x 90  ' . __('Leaderboard','MyAdsense'),     '468x60'  => '468 x 60  ' . __('Banner','MyAdsense'),           '234x60'  => '234 x 60  ' . __('Half Banner','MyAdsense'));
	$formats['ads']['Vertical']		= array ('120x600' => '120 x 600 ' . __('Skyscraper','MyAdsense'),      '160x600' => '160 x 600 ' . __('Wide Skyscraper','MyAdsense'),  '120x240' => '120 x 240 ' . __('Vertical Banner','MyAdsense'));
	$formats['ads']['Square']		= array ('336x280' => '336 x 280 ' . __('Large Rectangle','MyAdsense'), '300x250' => '300 x 250 ' . __('Medium Rectangle','MyAdsense'), '250x250' => '250 x 250 ' . __('Square','MyAdsense'), '200x200' => '200 x 200 ' . __('Small Square','MyAdsense'), '180x150' => '180 x 150 ' . __('Small Rectangle','MyAdsense'), '125x125' => '125 x 125 ' . __('Button','MyAdsense'));

	$formats['links']['Horizontal'] 	= array ('728x15'  => '728 x 15',  '468x15' => '468 x 15');
	$formats['links']['Square']     	= array ('200x90'  => '200 x 90',  '180x90' => '180 x 90',  '160x90' => '160 x 90',  '120x90' => '120 x 90');

	$adtypes 					= array ('text_image' => __('Text &amp; Image','MyAdsense'), 'image' => __('Image Only','MyAdsense'), 'text' => __('Text Only','MyAdsense'));

	$linktypes 					= array ('_0ads_al' => __('4 Ads Per Unit','MyAdsense'), '_0ads_al_s' => __('5 Ads Per Unit','MyAdsense'));

	$yesno 					= array ('yes' => __('Yes','MyAdsense'), 'no' => __('No','MyAdsense'));
	$default 					= array ('' => __('Use Default','MyAdsense'));
	$products 					= array ('ad' => __('Ad Unit','MyAdsense'),'link' => __('Link Unit','MyAdsense'));	
	$corners 					= array ('0' => __('Square corners','MyAdsense'),'6' => __('Slightly rounded corners','MyAdsense'),'10' => __('Very rounded corners','MyAdsense'));	

?>
