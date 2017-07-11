<?php 
/* 
 * Template to display a flexslider slideshow. 
 *
 * @since 4bzCore 1.0.0
 *
 * @param array global variable $fourbzcore_layout_opts, passed to this template by the fourbzcore slideshow shortcode.
 * @param array global variable $fourbzcore_items, holds the slides. Passed to this template by the fourbzcore slideshow 
 * shortcode.
 *
 * @package 4bzCore
 * @subpackage slideshow.php
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

// Display the title, if set.
if ( isset( $fourbzcore_layout_opts['title_text'] ) && $fourbzcore_layout_opts['title_text'] ) {
	if ( isset( $fourbzcore_layout_opts['is_widget'] ) && $fourbzcore_layout_opts['is_widget'] ) {
		echo $fourbzcore_layout_opts['title_text'];
	} else {
		echo '<h3 class="widgettitle">' . $fourbzcore_layout_opts['title_text'] . '</h3>';
	}
}

echo '<div class="fourbzcore-slideshow flexslider">';
$fourbzcore_items_count = isset( $fourbzcore_items ) ? count( $fourbzcore_items ) : 0;

if ( isset( $fourbzcore_items ) && is_array( $fourbzcore_items ) && 0 < $fourbzcore_items_count ) {
	echo '<ul class="slides">';
	
	for( $i = 0; $i < $fourbzcore_items_count; ++$i ) {
		// Don't escape because slides might contain html markup
		echo "<li>" . $fourbzcore_items[$i] . "</li>";
	} 
	
	echo '</ul>';
}

echo '</div>';