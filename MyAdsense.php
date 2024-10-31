<?php
/*
Plugin Name: MyAdsense
Plugin URI: http://www.nogent94.com/
Description: This is just a plugin, to manage Google AdSense 
Author: Andre Renaut
Version: 2.6
Author URI: http://a_renaut.club.fr/
*/

class MyAdsense
{
	function MyAdsense()
	{
		global $MyAdsense_settings;
		
		define ("MyAdsense_PLUGIN_FOLDER", 	basename(dirname(__FILE__)));

		define ("MyAdsense_PATH", 	'wp-content/plugins/' . MyAdsense_PLUGIN_FOLDER);
		define ("MyAdsense_edit_ads",	'edit.php?page=' . MyAdsense_PLUGIN_FOLDER . '/php/edit-ads.php');
		define ("MyAdsense_ad",		'edit.php?page=' . MyAdsense_PLUGIN_FOLDER . '/php/edit-ads.php&file=ad');

		add_filter('the_content', array(&$this,'filter_ads')); 

		add_action(	'wp_ajax_delete-ad', array(&$this,'wp_ajax_delete_ad'));
		add_action(	'wp_ajax_add-ad',    array(&$this,'wp_ajax_add_ad'));

		add_action( 'plugins_loaded',  array(&$this,'widget_init'));

		$MyAdsense_settings = get_option('MyAdsense_options');
		
		if (is_admin())
		{
			$MyAdsense_settings = get_option('MyAdsense_options');

			load_plugin_textdomain('MyAdsense',MyAdsense_PATH . '/lang');

			add_action('activate_' . MyAdsense_PLUGIN_FOLDER . '/MyAdsense.php'	,array(&$this,'install'));
			add_action('deactivate_' . MyAdsense_PLUGIN_FOLDER . '/MyAdsense.php'	,array(&$this,'uninstall'));

			add_action(	'admin_menu'				,array(&$this, 'admin_menu'));

			if (isset($_GET['page']) && ($_GET['page'] == MyAdsense_PLUGIN_FOLDER . '/php/edit-ads.php'))
				if (isset($_GET['file']) && ($_GET['file'] == 'ad'))
					if (isset($_GET['action']) && ($_GET['action'] == 'editad'))
					{
						global $title;
						$title = wp_specialchars( strip_tags( __('Edit ad','MyAdsense') ) ) ; 
					}
					if (isset($_GET['action']) && ($_GET['action'] == 'createad'))
					{
						global $title;
						$title = wp_specialchars( strip_tags( __('Create New Ad','MyAdsense') ) ) ; 
					}
		}

	}

////////////////			Class MyAdsense Basic Functions  ///////////////

	function install()
	{
		if (get_option('MyAdsense_options')) return;

		$new = array();
		$new ['account']= '';
		$new ['test']= '';
		$new ['defaults']['channel'] 			= '';
		$new ['defaults']['product'] 			= 'ad';
		$new ['defaults']['adformat'] 		= '250x250';
		$new ['defaults']['adtype'] 			= 'text_image';
		$new ['defaults']['linkformat'] 		= '200x90';
		$new ['defaults']['linktype'] 		= '_0ads_al';
		$new ['defaults']['show_home'] 		= 'yes';
		$new ['defaults']['show_post'] 		= 'yes';
		$new ['defaults']['show_archive'] 		= 'yes';
		$new ['defaults']['show_search'] 		= 'yes';
		$new ['defaults']['html_before'] 		= '';
		$new ['defaults']['html_after'] 		= '';
		$new ['defaults']['alternate_url'] 		= '';
		$new ['defaults']['alternate_color'] 	= '';
		$new ['defaults']['corner']		 	= '0';
		$new ['defaults']['colors']['border'] 	= '464646';
		$new ['defaults']['colors']['link'] 	= 'D83F20';
		$new ['defaults']['colors']['bg'] 		= 'E4F2FD';
		$new ['defaults']['colors']['text'] 	= '536289';
		$new ['defaults']['colors']['url'] 		= '464646';
		$new ['defaults']['ad'] 			= '&default';

		add_option('MyAdsense_options', $new, 'MyAdsense options');
	}
	function uninstall()
	{
	}
	function admin_menu()
	{
		add_options_page		(__('MyAdsense Settings','MyAdsense'), 	'MyAdsense', 	8, MyAdsense_PLUGIN_FOLDER . '/php/settings.php');
		add_management_page	(__('Ads','MyAdsense'), 	   		'MyAdsense', 	8, MyAdsense_PLUGIN_FOLDER . '/php/edit-ads.php');
	}
	function printMessage($string, $success=true, $anchor = "message")
	{
		if($success){
			echo '<div id="'.$anchor.'" class="updated fade"><p>'.$string.'</p></div>';
	 	}else{
	 		echo '<div id="'.$anchor.'" class="error fade"><p>'.$string.'</p></div>';
	 	}
	}



////////////////			Class MyAdsense Functions        ///////////////

	function get_settings ($ad_name = false,$full = true) 
	{
		global $MyAdsense_settings;

		if ( !isset($MyAdsense_settings['ads'][$ad_name]) ) 
		{
			if ($ad_name == $MyAdsense_settings['defaults']['ad'])
			{
				$settings = $MyAdsense_settings ['defaults'];
				$settings ['ad_name'] = $MyAdsense_settings['defaults']['ad'];
			}
			else
			{
				$settings = $MyAdsense_settings ['defaults'];
				$settings ['ad_name'] = $MyAdsense_settings['defaults']['ad'];
			}
		}
		else
		{
			if ($ad_name == $MyAdsense_settings['defaults']['ad'])
			{
				$settings = $MyAdsense_settings ['defaults'];
				$settings ['ad_name'] = $MyAdsense_settings['defaults']['ad'];
			}
			else
			{
				$settings = $MyAdsense_settings ['ads'] [$ad_name];
				$settings ['ad_name'] = $ad_name;
			}
		}

		if ($full)
		{
			if ( is_array($MyAdsense_settings['defaults']) )
			{
				foreach( $MyAdsense_settings['defaults'] as $key => $value ) 
				{
					if($settings[$key]=='')			$settings[$key]=$value;
				}
			}
		
			if ( is_array($MyAdsense_settings['defaults']['colors']) )
			{
				foreach( $MyAdsense_settings['defaults']['colors'] as $key => $value)
				{
					if($settings['colors'][$key]=='') 	$settings['colors'][$key]=$value; 
				}
			}
		}

		return $settings;
	}

		// This filter parses post content and replaces markup with the correct ad,
		//	<!--MyAdsense#name--> for named ad or <!--MyAdsense--> for default 

	function filter_ads($content) 
	{
		global $MyAdsense_settings;

		if (is_array($MyAdsense_settings['ads']))
		{
			$content=str_replace("<!--MyAdsense-->",MyAdsense::get_ad_inline(),$content);
			foreach ( $MyAdsense_settings['ads'] as $ad_name => $ad )
			{	
       			$content = str_replace("<!--MyAdsense#" . $ad_name .  "-->", MyAdsense::get_ad_inline( $ad_name ), $content);
   			}
		}
   		return $content;
	}

	function get_ad_inline($ad_name = false) 
	{
		$settings = MyAdsense::get_settings ($ad_name);

		$code = '';

		switch (true)
		{
			case (is_home() 	 && ('no' == $settings['show_home']))  :
			break;
			case (is_single()  && ('no' == $settings['show_post']))  :
			break;
			case (is_page() 	 && ('no' == $settings['show_post']))  :
			break;
			case (is_archive() && ('no' == $settings['show_archive']))  :
			break;
			case (is_search()	 && ('no' == $settings['show_search']))  :
			break;
			default :
				$code .= MyAdsense::get_link($settings); 
			break;
		}
 		return $code;
	}
	function doit($ad_name=false) 
	{
		$settings = MyAdsense::get_settings ($ad_name);

		echo MyAdsense::get_link($settings);
	}
	function get_link($settings) 
	{
		global $MyAdsense_settings;
		$code='';

		if ( is_array($settings) )
		{
			if ( $settings['product']=='link' )
			{
				$format = $settings['linkformat'] . $settings['linktype'];
				list($width,$height,$null) = split('[x]',$settings['linkformat']);
			} 
			else 
			{
				$format = $settings['adformat'] . '_as';
				list($width,$height,$null) = split('[x]',$settings['adformat']);
			}

			if ( $MyAdsense_settings['test'] )	($_GET['preview'] = true);
			if ( is_user_logged_in() ) 		($_GET['preview'] = true);

			$code .= $settings['html_before'];

			if ($_GET['preview'] )
			{
				$code .="<iframe width='" . $width . "' scrolling='no' height='" . $height . "' frameborder='1' allowtransparency='true' hspace='0' vspace='0' marginheight='0' marginwidth='0' src='" . get_settings('siteurl') . "/wp-content/plugins/" . MyAdsense_PLUGIN_FOLDER . "/php/html.php?ad_name=" . $settings['ad_name'] . "'></iframe>";
			} 
			else 
			{
				$code .= '<script type="text/javascript"><!--' . "\n";

				$code.= 'google_ad_client = "pub-' . $MyAdsense_settings['account'] . '"; ' ;

				if( $settings['channel']!='' )
				{ 
					$code.= 'google_ad_channel = "' . $settings['channel'] . '"; ' ;
				}

				$code.= 'google_ad_width = ' . $width . "; ";
				$code.= 'google_ad_height = ' . $height . "; ";
				$code.= 'google_ad_format = "' . $format . '"' . "; ";

				if($settings['alternate_url']!='')
				{ 
					$code.= 'google_alternate_ad_url = "' . $settings['alternate_url'] . '"; ';
				}
				else 
				{
					if($settings['alternate_color']!='')
					{ 
						$code.= 'google_alternate_color = "' . $settings['alternate_color'] . '"; ';
					}
				}
					
				//Default to Ads
				if($settings['product']!=='link')
				{ 
					$code.= 'google_ad_type = "' . $settings['adtype'] . '"; '; 
					$code.= 'google_ui_features  = "rc:' . $settings['corner'] . '"' . "; ";
				}

				$code.= 'google_color_border = "' . $settings['colors']['border'] . '"' . "; ";
				$code.= 'google_color_bg     = "' . $settings['colors']['bg'] . '"' . "; ";
				$code.= 'google_color_link   = "' . $settings['colors']['link'] . '"' . "; ";
				$code.= 'google_color_text   = "' . $settings['colors']['text'] . '"' . "; ";
				$code.= 'google_color_url    = "' . $settings['colors']['url'] . '"' . "; ";
				$code.= '					//--></script>' . "\n";

				$code.= '<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>' . "\n";
			}
			$code.=$settings['html_after'];
		}
		return $code;
   	}
	function get_fake($ad_name,$product,$lang) 
	{
		$settings = MyAdsense::get_settings ($ad_name);
		$code='';

		if ( is_array($settings) )
		{
			if ( $product =='link' )
			{
				$format = $settings['linkformat'] . $settings['linktype'];
				$width = 180;
				$height = 90;

				$fake_url = get_settings('siteurl') . "/wp-content/plugins/" . MyAdsense_PLUGIN_FOLDER . "/php/fakelink.php";
				$fake_url = add_query_arg('hl',  		$lang , 					$fake_url);

				$fake_url = add_query_arg('color_border', $settings['colors']['border'] , 	$fake_url);
				$fake_url = add_query_arg('color_bg',  	$settings['colors']['bg'] , 		$fake_url);
				$fake_url = add_query_arg('color_url',  	$settings['colors']['url'] , 		$fake_url);
			} 
			else 
			{
				$width = 160;
				$height = 70;

				$fake_url = 'https://www.google.com/pagead/ads?client=ca-google-asfe';
				$fake_url = add_query_arg('format',  	'160x70_as' ,			 	$fake_url);
				$fake_url = add_query_arg('hl',  		$lang , 					$fake_url);
				$fake_url = add_query_arg('ui', 		'rc:' . $settings['corner'] ,		$fake_url);

				$fake_url = add_query_arg('color_border', $settings['colors']['border'] , 	$fake_url);
				$fake_url = add_query_arg('color_bg',  	$settings['colors']['bg'] , 		$fake_url);
				$fake_url = add_query_arg('color_link',  	$settings['colors']['link'] , 	$fake_url);
				$fake_url = add_query_arg('color_text', 	$settings['colors']['text'] , 	$fake_url);
				$fake_url = add_query_arg('color_url',  	$settings['colors']['url'] , 		$fake_url);
	
			}
			$code .="<iframe id='" . $product . "iframe' width='" . $width . "' scrolling='no' height='" . $height . "' frameborder='0' allowtransparency='true' hspace='0' vspace='0' marginheight='0' marginwidth='0' src='" . $fake_url . "'></iframe>";
		}
		return $code;
   	}

////////////////			Class MyAdsense ad Management ///////////////

	function &get_ad(&$ad_name) 
	{
		return MyAdsense::get_settings ($ad_name,false);
	}

////////////////			Class MyAdsense ad List Management  AJAX    ///////////////


	function wp_ajax_delete_ad() 
	{
		$ad = isset($_POST['id'])?  $_POST['id'] : '';

		$MyAdsense_settings = get_option('MyAdsense_options');
		unset ($MyAdsense_settings ['ads'][$ad]);
		update_option('MyAdsense_options', $MyAdsense_settings);
		
		$r = true;

		die( $r ? '1' : '0' );
	}
	function wp_ajax_add_ad() 
	{
		$url_parms->s 		= isset($_POST['s']) 		? $_POST['s'] 					: false;
		$url_parms->mode 		= isset($_POST['mode']) 	? $_POST['mode'] 					: 'detail';
		$url_parms->status	= isset($_POST['status']) 	? $_POST['status'] 				: false;

		$start 			= isset($_POST['pageid']) 	? intval($_POST['pageid']) * 25 - 1		: 24;

		list($ads, $total) = MyAdsense::get_ad_list( $url_parms, $start, 1 );

		if ( !$ads ) die('1');

		$x = new WP_Ajax_Response();
		foreach ( (array) $ads as $ad ) {
			get_ad( $ad );
			ob_start();
				MyAdsense::ad_row( $ad_name, $url_parms, false );
				$ad_list_ad = ob_get_contents();
			ob_end_clean();
			$x->add( array(
				'what' 	=> 'ad',
				'id' 		=> $ad_name,
				'data' 	=> $ad_list_ad
			) );
		}
		$x->send();
	}

////////////////			Class MyAdsense ad List Management ///////////////


	function ad_url($url,$wpnonce=false,$url_parms)
	{
		foreach ($url_parms as $key => $value)
		{
			if ($value) $url = add_query_arg($key, $value, $url);
		}	
		if ($wpnonce) $url = clean_url( wp_nonce_url( $url, $wpnonce ) );
		return $url;
	}
	function get_ad_list( $url_parms, $start, $num ) 
	{
		global $MyAdsense_settings;
		$w = array();
		$selected = array();
	
		$start = abs( (int) $start );
		$num = (int) $num;

		if (count($MyAdsense_settings['ads']) > 0) 
		{
// status
			if ( $url_parms->status )
			{
				foreach ($MyAdsense_settings['ads'] as $key => $value) 
				{
					$settings = MyAdsense::get_settings ($key);
					if (('link' == $url_parms->status) && ('link' == $settings ['product'])) 	$w ['ads'] [$key] = $MyAdsense_settings ['ads'] [$key];
					if (('ad'   == $url_parms->status) && ('ad'   == $settings ['product'])) 	$w ['ads'] [$key] = $MyAdsense_settings ['ads'] [$key];
				}
			}
			else
			{
				$w ['ads'] = $MyAdsense_settings ['ads'];
			}
// search
			if (count($w ['ads']) > 0) 
			{
				if ( $url_parms->s ) 
				{
					$s = attribute_escape($url_parms->s);	

					foreach ($w ['ads'] as $key => $value) 
					{
						if ( strstr($key,$s) ) 
						{
							$selected [] = $key;
						}
					}
					sort($selected);
					if (count($selected) == 0) 	$tobedisplayed = array();
					else 					$tobedisplayed = array_slice($selected, $start, $num);
				} 
				else 
				{
					foreach ($w ['ads'] as $key => $value) $selected [] = $key;
					sort($selected);
					$tobedisplayed = array_slice($selected, $start, $num);
				}
				$total = count($selected);
			}
			else
			{
				$tobedisplayed = array();
				$total = 0;
			}
		}
		else
		{
			$tobedisplayed = array();
			$total = 0;
		}
			
		return array($tobedisplayed, $total);
	}

	function ad_row( $ad_name, $url_parms, $checkbox = true ) 
	{
		global $MyAdsense_settings;

		include ('php/adsense.php'); 

		$ad = MyAdsense::get_ad($ad_name);
		$ad_def = MyAdsense::get_settings($ad_name,true);

		$url_parms->ad 	= urlencode($ad_name);
		$delete_url  	= MyAdsense::ad_url( MyAdsense_ad ."&action=deletead", "delete-ad_$ad_name",	$url_parms );
		$setdef_url	 	= MyAdsense::ad_url( MyAdsense_ad ."&action=setdefad", false,	$url_parms );
		$edit_url    	= MyAdsense::ad_url( MyAdsense_ad ."&action=editad",	 false,	$url_parms );
		unset ($url_parms->ad);

		$actions = array();

		if ($ad_name != $MyAdsense_settings ['defaults']['ad'])
		{
			$def = false;
			$actions['setdef']   = "<a href='$setdef_url' 	  class='dim'>" . __('Set default','MyAdsense') . '</a> | ';
			$actions['delete']   = "<a href='$delete_url' 	  class='delete:the-ad-list:ad-$ad_name delete'>" . __('Delete','MyAdsense') . '</a>';

			if ( $checkbox ) 	$checkbox = "<td style='text-align:center;'><input type='checkbox' name='delete_ads[]' value='$ad_name' /></td>";
			else			$checkbox = "<td style='text-align:center;'>&nbsp;</td>";
		}
		else
		{
			$def = true;
			$checkbox = "<td style='text-align:center;'>&nbsp;</td>";
			$actions['setdef']   = __('default ad','MyAdsense');
		}

?>
				<tr id='ad-<?php echo $ad_name; ?>' class='<?php echo $class; ?>'>
					<?php echo $checkbox; ?>
					<td class='ad'>
						<p class='ad-title' style='float:left'>
							<strong>
								<a class='row-title' href='<?php echo $edit_url; ?>'>
									<?php echo $ad_name; ?>
									<?php echo("\n"); ?>
								</a>
							</strong>
<?php 
		if ('detail' == $url_parms->mode) 
		{ 
// format
			$categ = array ('Horizontal' => '','Vertical' => 'ad', 'Square' => '');
			$libf = $libfi = '';
			foreach ($categ as $key => $value)
			{
				if ($def)
				{
					foreach ($products as $k => $v)
					{
						$libf = 'libf_def_' . $k;
						if (isset ($formats[$k . 's'][$key][$ad[$k . 'format']])) $$libf = __($key,'MyAdsense') . ' | ' . $formats[$k . 's'][$key][$ad[$k . 'format']] ;
					//	echo '$formats[' . $k . 's' . '][' . $key . '][' . $ad[$k . 'format'] . ']' . "\n";
					}
				}
				else
				{
					if (($ad_def['product'] == $value) || ('' == $value)) 
					{
						if (isset ($formats[$ad_def['product'] . 's'][$key][$ad[$ad_def['product'] . 'format']])) $libf = __($key,'MyAdsense') . ' | ' . $formats[$ad_def['product'] . 's'][$key][$ad[$ad_def['product'] . 'format']] ;
						if ('' == $libf)
							if (isset ($formats[$ad_def['product'] . 's'][$key][$ad_def[$ad_def['product'] . 'format']])) $libfi = __($key,'MyAdsense') . ' | ' . $formats[$ad_def['product'] . 's'][$key][$ad_def[$ad_def['product'] . 'format']] ;

						if ('' != $libf) continue;
						if ('' != $libfi) continue;

					//	echo '$formats[' . $ad_def['product'] . 's' . '][' . $key . '][' . $ad[$ad_def['product'] . 'format'] . ']'. "\n";
					//	echo '$formats[' . $ad_def['product'] . 's' . '][' . $key . '][' . $ad_def[$ad_def['product'] . 'format'] . ']'. "\n";
					}
				}
			}

			$libad = '';
			$liblink = '';
			if ($def)
			{
				if (isset($libf_def_ad)) $libad = $libf_def_ad;
				if (isset($libf_def_link)) $liblink = $libf_def_link;
			}
			else
			{
				$x = 'lib_' . $ad_def['product'];
				$$x = ('' != $libfi) ? '<i>' . $libfi . '</i>' : $libf;
			}
// types

			$libt = $libti = '';
			if ($def)
			{
				foreach ($products as $k => $v)
				{
					$libt = 'libt_def_' . $k;
					if ('ad' == $ad['product']) 	if (isset ($adtypes   [$ad[$k . 'type']])) $$libt = $adtypes   [$ad[$k . 'type']];
					else					if (isset ($linktypes [$ad[$k . 'type']])) $$libt = $linktypes [$ad[$k . 'type']];
				}
			}
			else
			{
				if ('ad' == $ad_def['product'])
				{
					if (isset ($adtypes [$ad[$ad_def['product'] . 'type']])) $libt = $adtypes [$ad[$ad_def['product'] . 'type']];
					if ('' == $libt)
						if (isset ($adtypes [$ad_def[$ad_def['product'] . 'type']])) $libti = $adtypes [$ad_def[$ad_def['product'] . 'type']];

				}
				else
				{
					if (isset ($linktypes [$ad[$ad_def['product'] . 'type']])) $libt = $linktypes [$ad[$ad_def['product'] . 'type']];
					if ('' == $libt)
						if (isset ($linktypes [$ad_def[$ad_def['product'] . 'type']])) $libti = $linktypes [$ad_def[$ad_def['product'] . 'type']];

				}
			}

			if ($def)
			{
				if ('' != $libad) 	$libad .= ' | ';
				if ('' != $liblink) 	$liblink .= ' | ';
				if (isset($libt_def_ad)) 	$libad 	.= $libt_def_ad;
				if (isset($libt_def_link)) 	$liblink 	.= $libt_def_link;
			}
			else
			{
				if ('' != $$x) 	$$x .= ' | ';
				$$x .= ('' != $libti) ? '<i>' . $libti . '</i>' : $libt;
			}
// at last !
?>


<?php
			if ($def)
			{
				$libad 	.=  '<br/>';
				echo $libad . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $liblink;
			}
			else
			{
				echo $$x;
			}
 ?> 

<?php 
		}
?>
						</p>
			</div>

					</td>
					<td> <!-- channel -->
						<?php echo $ad['channel']; ?>
					</td>
					<td> <!-- product -->
						<?php echo $ad['product']; ?>
					</td>
<?php 
		if ('detail' == $url_parms->mode) 
		{ 
			if ((!$def) && ($ad_def['adtype'] != 'image'))
			{
?>
					<td colspan=5>
<?php
							$lang = substr(WPLANG,0,2);
							if ($lang == '') $lang = 'en';
							echo MyAdsense::get_fake($ad_name,$ad_def['product'],$lang); 
?>
					</td>
<?php
			}
			else
			{
?>
					<td style='background-color:#<?php echo $ad['colors']['border']; ?>;'> <!-- color1 -->
					</td>
					<td style='background-color:#<?php echo $ad['colors']['link']; ?>;'> <!-- color2 -->
					</td>
					<td style='background-color:#<?php echo $ad['colors']['bg']; ?>;'><!-- color3 -->
					</td>
					<td style='background-color:#<?php echo $ad['colors']['text']; ?>;'> <!-- color4 -->
					</td>
					<td style='background-color:#<?php echo $ad['colors']['url']; ?>;'> <!-- color5 -->
					</td>
<?php
			}
		}
		else
		{
?>
					<td style='background-color:#<?php echo $ad['colors']['border']; ?>;'> <!-- color1 -->
					</td>
					<td style='background-color:#<?php echo $ad['colors']['link']; ?>;'> <!-- color2 -->
					</td>
					<td style='background-color:#<?php echo $ad['colors']['bg']; ?>;'><!-- color3 -->
					</td>
					<td style='background-color:#<?php echo $ad['colors']['text']; ?>;'> <!-- color4 -->
					</td>
					<td style='background-color:#<?php echo $ad['colors']['url']; ?>;'> <!-- color5 -->
					</td>
<?php
		}
?>
					<td> <!-- format -->
						<?php if ($def)  $lib = $ad['adformat'] . '<br/>' . $ad['linkformat']; ?>
						<?php if (!$def) $lib = ('ad' == $ad['product']) ? $ad['adformat'] : $ad['linkformat'] ; ?>
						<?php echo $lib; ?>
					</td>

					<td>
						<?php foreach ( $actions as $action => $link ) echo "<span class='$action'>$link</span>"; ?>
						<?php echo("\n"); ?>
					</td>
				</tr>
<?php
	}

	function widget_init()
	{
		global $MyAdsense_settings;

		if (function_exists('register_sidebar_widget') )
		{   
			if(is_array($MyAdsense_settings['ads']))
			{
				foreach($MyAdsense_settings['ads'] as $name => $ad)
				{
					$widget = array ( 'MyAdsense #%s' , '' , $name );
					register_sidebar_widget($widget, array(&$this,'widget'), $name);
				}
			}
       	} 
	}

 	    // This is the function that outputs MyAdsense widget.
	function widget($args,$n=false) 
	{
		global $MyAdsense_settings;

		extract ($args);

		$n = str_replace('MyAdsense #', '', $widget_name);

		echo ("\n<!-- start widget $widget_name -->\n");
		echo $before_widget;
		MyAdsense::doit ($n); 
		echo $after_widget;
		echo ("<!-- end widget $widget_name -->\n\n");
	}
}
$MyAdsense = new MyAdsense();
?>