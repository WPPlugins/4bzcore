<?php 
/** 
 * Template to output progressbars. 
 *
 * @since 4bzCore 1.0.0
 *
 * @param array global variable $fourbzcore_layout_opts, passed to this template by the fourbzcore progressbars shortcode.
 * @param array global variable $fourbzcore_items, holds the labels and percentages for the progressbars, passed to this
 * template by the fourbzcore progressbars shortcode.
 *
 * @package 4bzCore
 * @subpackage progressbars.php
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

echo '<div class="fourbzcore-progressbars-cont">';
// Display title, if set.
if ( isset( $fourbzcore_layout_opts['title_text'] ) && $fourbzcore_layout_opts['title_text'] ) {
	if ( isset( $fourbzcore_layout_opts['is_widget'] ) && $fourbzcore_layout_opts['is_widget'] ) {
		echo $fourbzcore_layout_opts['title_text'];
	} else {
		echo '<h3 class="widgettitle">' . $fourbzcore_layout_opts['title_text'] . '</h3>';
	}
}

$items_html = '';
$fourbzcore_items_count = isset( $fourbzcore_items ) ? count( $fourbzcore_items ) : 0;

if ( isset( $fourbzcore_items ) && is_array( $fourbzcore_items ) && 0 < $fourbzcore_items_count ) {
	echo '<ul class="skills-set">';
	
	foreach ( $fourbzcore_items as $key => $val ) {
		$name = esc_html( $key );
		$value = esc_html( $val );
		$items_html .= "<li class='component'><div class='component-gutter'>
		<div class='skill-lbl-cont'><span class='skill-lbl'>$name</span><span class='skill-percent'>$value</span></div><div class='progressbar-cont'><div data-percentage='$value' class='progressbar'></div>
		</div></div></li>";
	}
	
	echo $items_html;
	echo '</ul>';
}
echo '</div>';