<?php
/** 
 * Template to output an author bio
 *
 * @since 4bzCore 1.0.0
 *
 * @param global variable $fourbzcore_layout_opts, passed to this template by the fourbzcore author bio shortcode.
 *
 * @package 4bzCore
 * @subpackage author-bio.php
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

global $fourbzcore_plugin;

$extra_class = '';

echo '<div class="fourbzcore-author-bio-cont' . $extra_class . '">';
  
// Display the title, if it's set.
if ( isset( $fourbzcore_layout_opts['title_text'] ) && $fourbzcore_layout_opts['title_text'] ) {
	if ( isset( $fourbzcore_layout_opts['is_widget'] ) && $fourbzcore_layout_opts['is_widget'] ) {
		echo $fourbzcore_layout_opts['title_text'];
	} else {
		echo '<h3 class="widget-title">' . $fourbzcore_layout_opts['title_text'] . '</h3>';
	}
}

// Display author bio and contact information, if provided.
if ( get_the_author_meta( 'description' ) ) {
	the_author_meta( 'description' ); //get_template_part( 'author-bio' ); 
}

echo '</div>';
?>