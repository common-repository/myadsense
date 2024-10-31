=== MyAdsense ===
Contributors: andre renaut
Donate link: if you want one, tell me !
Tags: Google, Adsense, Ads, Wordpress, Plugin
Requires at least: 2.5
Stable tag: 2.6

Allows you to manage your ads formats from Google Adsense and to include them in your theme or in your posts ...

== Description ==

Look and Feel totally compliant with Wordpress 2.5 

Detail/List view, Filters and Search on lists, Mass delete by checkbox, delete list item 'ajaxified'

Supported languages : English, French	(.pot provided)

== Installation ==
 **&nbsp;**
###Installing The Plugin###

1. Extract all files from the ZIP file, making sure to keep the file structure intact, and then upload it to `/wp-content/plugins/`.

This should result in the following file structure:

`- wp-content
    - plugins
     	  - MyAdsense
            | MyAdsense.php
            | readme.txt
            | screenshot-1.jpg
            | screenshot-2.jpg
            | screenshot-3.jpg
            | screenshot-4.jpg
            | screenshot-5.jpg
            
            - css
                 | adclassic.css
                 | adfresh.css
                 | adsclassic.css
                 | adsfresh.css
            
            - js
                 | ad.js
                 | color.class.js
                 | edit-ads.js
            
            - lang
                 | MyAdsense.pot
                 | MyAdsense-fr_FR.po
                 | MyAdsense-fr_FR.mo

            - php
                 | ad.php
                 | ads.php
                 | ads_head.php
                 | adsense.php
                 | edit-ad-form.php
                 | edit-ads.php
                 | fakelink.php
                 | html.php
                 | setform.php
                 | settings.php`

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)


1. Be sure the plugin folder name is MyAdsense and not myadsense.

1. Then just visit your admin area and activate the plugin.

1. Fill your settings under the admin option : Settings > MyAdsense.

1. Create/Delete/Update your ads or the default under the admin option : Manage > MyAdsense

1. To add your ads on your blog : two ways :

**Insert** into a post or a page the following
<em>
to display default ad :
	'<!--MyAdsense-->' 
to display 'name_of_the_ad' ad :
	'<!--MyAdsense#name_of_the_ad-->`
</em>
**Insert** in your code : 
<em>
to display default ad : 
'<?php
	if ( class_exists( 'MyAdsense' ) )  
			MyAdsense::doit(); 
?>'
to display 'name_of_the_ad' ad :
'<?php
	if ( class_exists( 'MyAdsense' ) )  
			MyAdsense::doit( 'name_of_the_ad' ); 
?>`
</em>
== Frequently Asked Questions ==
 **&nbsp;**
= How do i have my ads on my blog ? =

**Insert** into a post or a page the following
<em>
to display default ad :
	'<!--MyAdsense-->' 
to display 'name_of_the_ad' ad :
	'<!--MyAdsense#name_of_the_ad-->`
</em>
**Insert** in your code : 
<em>
to display default ad : 
'<?php
	if ( class_exists( 'MyAdsense' ) )  
			MyAdsense::doit(); 
?>'
to display 'name_of_the_ad' ad :
'<?php
	if ( class_exists( 'MyAdsense' ) )  
			MyAdsense::doit( 'name_of_the_ad' ); 
?>`
</em>



== Screenshots ==

1. MyAdsense settings
2. MyAdsense manage : detail view
3. MyAdsense manage : list view
4. MyAdsense manage : edit an ad
5. Write > Post : add a ad in a post ...


== Log ==
 2.6				for WordPress 2.6
* special thanks to G.D. for the Italian translation (http://gidibao.net)

 2.5.0.2     		displaying bug in seetings on IE

 2.5.0.1     		independant from the plugin folder name

 2.5		     		2008/04/07 first release


== Next features ==

Any new idea or code improvement can be posted at : contact@nogent94.com
