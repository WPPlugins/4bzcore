<?php
/** 
 * Template to output a column
 *
 * @since 4bzCore 1.0.0
 *
 * @param global variable $fourbzcore_layout_opts, passed to this template by the fourbzcore column bio shortcode.
 *
 * @package 4bzCore
 * @subpackage column.php
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

global $fourbzcore_plugin;

$style = '';

// Construct the style for the columns.
if ( isset( $fourbzcore_layout_opts['column_width'] ) ) {
	$style = ' width:' . esc_attr( $fourbzcore_layout_opts['column_width'] ) . ';';
}

if ( isset( $fourbzcore_layout_opts['column_padding'] ) ) {
	$style .= ' padding:' . esc_attr( $fourbzcore_layout_opts['column_padding'] ) . ';';
}

if ( isset( $fourbzcore_layout_opts['column_margin'] ) ) {
	$style .= ' margin:' . esc_attr( $fourbzcore_layout_opts['column_margin'] ) . ';';
}

if ( $style ) {
	$style = ' style="' . $style . ' display: inline-block; vertical-align:top;"';
}

// Display the title, if it's set.
if ( isset( $fourbzcore_layout_opts['title_text'] ) && $fourbzcore_layout_opts['title_text'] ) {
	if ( isset( $fourbzcore_layout_opts['is_widget'] ) && $fourbzcore_layout_opts['is_widget'] ) {
		echo $fourbzcore_layout_opts['title_text'];
	} else {
		echo '<h3 class="widget-title">' . $fourbzcore_layout_opts['title_text'] . '</h3>';
	}
}


echo '<div class="fourbzcore-column-cont' . '"' . $style . '>' .  do_shortcode( $fourbzcore_layout_opts['content'] );

echo '</div>';
?>