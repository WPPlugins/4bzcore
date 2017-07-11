=== 4bzCore ===
Contributors: 4bzthemes
Tags: recent posts, popular posts, featured posts, related posts, contact form, contact info, image text, progressbars, flexslider slideshow, flickr, facebook comments, author bio, column, shortcode builder, multiple featured images, video/audio embed, user profile fields, support, plugin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GLMKNUUTRPVT4
Requires at least: 3.8
Tested up to: 4.3
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A collection of shortcodes, widgets, a shortcode builder, multiple featured images, a related posts module, and much more.

== Description ==
A collection of shortcodes, widgets, a shortcode builder, multiple featured images, a related posts module, video and audio embed options, and extra fields for the user profile form. Includes Recent Posts, Featured Posts, Popular Posts, Related Posts, Image Text, Progressbars, Flexslider Slideshow, Contact Info, Contact Form, Flickr Photos, Facebook Comments, Author Bio, and Column shortcodes and widgets.
This plugin provides filters for theme and plugin authors to override or augment the default shortcodes and widgets' options, display, and description. 4bzthemes recommends the 4bzCore plugin for all of their themes.

You can find [docs](http://4bzthemes.com/knowledgebase/), and more detailed information on [4bzthemes.com](http://4bzthemes.com/plugin/4bzcore).

If you have any questions about 4bzCore, consult the following in this order:

1. [Docs](http://4bzthemes.com/knowledgebase/
2. [Support Forum] (http://wordpress.org/support/plugin/4bzcore) on WordPress.org.

== Translators ==

4bzCore needs translations. If you have created or updated a language pack, you can send [gettext PO and MO files](http://codex.wordpress.org/Translating_WordPress) to [4bzthemes](http://4bzthemes.com/about/) so that it can be bundled into the plugin.

== Installation ==

The plugin is available for download from the WordPress.org repository. Here are two ways to install the plugin:

= Upload a saved zip file =

Navigate to Plugins from the admin sidebar, click the "Add New" button.
Click the "Upload Plugin" button, click the "Choose File" button, navigate to the location where you saved the plugin, click Open, then click the "Install Now" button.
Activate the plugin.

= Search for the plugin =

Navigate to Plugins from the admin sidebar, click the "Add New" button.
Enter the plugin’s name in the search box and press enter on your keyboard. The search results will be displayed below, find the plugin and click the "Install Now" button.
Activate the plugin.

== Frequently Asked Questions ==

If you have any questions about 4bzCore, consult the following in this order:

1. [Docs](http://4bzthemes.com/knowledgebase/)
2. [Support Forum] (http://wordpress.org/support/plugin/4bzcore) on WordPress.org.

= How do I display multiple featured images in a theme that doesn't natively support this plugin? =

4bzCore provides a function to retrieve the post meta data, which includes the featured images, video/audio embed, and the related posts ids. Here is the suggested code to use: 
	
	global $fourbzcore_plugin;
	if ( isset( $fourbzcore_plugin ) && method_exists( $fourbzcore_plugin, 'get_post_meta' ) ) {
		$your_variable = $fourbzcore_plugin->get_post_meta( $post_id /* optional */ );
	}

Here is the structure of the array:

	array( 
		// featured images.
		'media' => array(
			array (
				'url' => string,
				'alt_text' => string,
				'width'	=> int,
				'height' => int,
			)
		),
		// video/audio iframe embed code.
		'media_embed' => array (
			'code' => string,
		),
		// related posts ids.
		'related' => string,
	)

Note that the default wordpress featured image is not included in this array. You have to retrieve this through wordpress functions, such as get_the_post_thumbnail.
		
= How do I display the extra user profile fields in a theme that doesn't natively support this plugin? =

4bzCore provides a function to retrieve the user meta data, which includes the user avatar, title,  address, phone number, social share links, and skills. Here is the suggested code to use: 
	global $fourbzcore_plugin;
	if ( isset( $fourbzcore_plugin ) && method_exists( $fourbzcore_plugin, 'get_user_meta' ) ) {
		$your_variable = $fourbzcore_plugin->get_user_meta( $user_id /* optional */ );
	}

Here is the structure of the array:

	array( 
		// Title
		'title' => string,
		// Contact info 
		'contact_address'	=>	string,
		'contact_phone'	=>	string,
		'contact_facebook'	=>	string,
		'contact_twitter'	=>	string,
		'contact_google'	=>	string,
		'contact_linkedin'	=>	string,
		'contact_instagram'	=>	string,
		'contact_tumblr'	=>	string,
		'contact_pinterest'	=>	string,
		// Skills
		'items'				=>	array,
		// Avatar
		'avatar'			=>	string,
		'avatar_width'		=>	int,
		'avatar_height'		=>	int,
		'avatar_alt'		=>	string,
	)

= The featured posts shortcode/widget displays all of my posts, why? =

This shortcode/widget displays sticky posts, and if there are no sticky posts to display, it defaults to display recent posts. So, if there is no limit set and there are no sticky posts, then this shortcode/widget will display all posts. This is not a bug, but the default behaviour.

== Report a bug ==

You can report any bugs through the [Support Forum](http://wordpress.org/support/plugin/4bzcore) on WordPress.org.

== Screenshots ==

None.

== Changelog ==
= 1.0.5 =
- Updated: updated all widgets classes to use WP_Widget::__construct().

= 1.0.3 =
- Fixed: save_postdata function.

= 1.0.2 =
- Updated: minor css changes.
- Fixed: flexslider javascript to target only 4bzcore slideshows in front-end.js file.

= 1.0.1 =
- Fixed: register the plugin's admin script to make it available to theme and plugin authors.
- Updated: changed the enqueue script code to not require jquery-ui-tabs and wp-color-picker for the plugin's admin script.

= 1.0.0 =
* Initial Release

== Credits ==

== Upgrade Notice ==

= 1.0.0 =