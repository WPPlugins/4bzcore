<?php
/** 
 * Template to output an image and text
 *
 * @since 4bzCore 1.0.0
 *
 * @param global variable $fourbzcore_layout_opts, passed to this template by the fourbzcore image text shortcode.
 *
 * @package 4bzCore
 * @subpackage image-text.php
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

global $fourbzcore_plugin;

echo '<div class="fourbzcore-image-text-cont">';
  
// Display the title, if it's set.
if ( isset( $fourbzcore_layout_opts['title_text'] ) && $fourbzcore_layout_opts['title_text'] ) {
	if ( isset( $fourbzcore_layout_opts['is_widget'] ) && $fourbzcore_layout_opts['is_widget'] ) {
		echo $fourbzcore_layout_opts['title_text'];
	} else {
		echo '<h3 class="widget-title">' . $fourbzcore_layout_opts['title_text'] . '</h3>';
	}
}

$img = '';
$width = isset( $fourbzcore_layout_opts['image_url_width'] ) ?
	' width="' . esc_attr( $fourbzcore_layout_opts['image_url_width'] ) . '"' :
	'';
	
$height = isset( $fourbzcore_layout_opts['image_url_height'] ) ?
	' height="' . esc_attr( $fourbzcore_layout_opts['image_url_height'] ) . '"' :
	'';
		
$alt = ( isset( $fourbzcore_layout_opts['image_url_alt'] ) && $fourbzcore_layout_opts['image_url_alt'] ) ?
	esc_attr( $fourbzcore_layout_opts['image_url_alt'] ) :
	'';
	
$url = isset( $fourbzcore_layout_opts['image_url'] ) ? esc_url( $fourbzcore_layout_opts['image_url'] ) : '';
		
$img .=  '<img src="' . $url . '"';

if ( $alt ) {
	$img .= ' alt="' . $alt  . '"';
}

if ( $width ) {
	$img .= ' ' . $width;
}

if ( $height ) {
	$img .= ' ' . $height;
}

$img .= ' />';

echo '<figure>' . $img . '<p class="content post-content text-format">' . $fourbzcore_layout_opts['content'] . '</p></figure>';

echo '</div>';
?>