<?php
/**
 * Template to display flickr photos.
 *
 * @since 4bzCore 1.0.0
 *
 * @param global variable $fourbzcore_layout_opts, passed to this template by the flickr photos shortcode.
 *
 * @package 4bzCore
 * @subpackage flickr-photos.php
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

echo '<div class="fourbzcore-flickr-photos-cont">';

// Display the title, if set.
if ( isset( $fourbzcore_layout_opts['title_text'] ) && $fourbzcore_layout_opts['title_text'] ) {
	if ( isset( $fourbzcore_layout_opts['is_widget'] ) && $fourbzcore_layout_opts['is_widget'] ) {
		echo $fourbzcore_layout_opts['title_text'];
	} else {
		echo '<h3 class="widgettitle">' . $fourbzcore_layout_opts['title_text'] . '</h3>';
	}
}

echo '<div class="flickr-photos"><script type="text/javascript" src="' . esc_url( 'http://www.flickr.com/badge_code_v2.gne?count=' . $fourbzcore_layout_opts['limit'] . '&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=' . $fourbzcore_layout_opts['user_id'] ) . '"></script></div>';
	
echo '</div>';