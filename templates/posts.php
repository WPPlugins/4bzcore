<?php 
/** 
 * Template to display posts. 
 *
 * @since 4bzCore 1.0.0
 *
 * @param global variable $fourbzcore_layout_opts, passed to this template by the fourbzcore posts shortcode.
 * @param global variable $fourbzcore_query, the posts object. Passed to this template by the various posts based shortcodes.
 *
 * @package 4bzCore
 * @subpackage posts.php
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

global $fourbzcore_plugin;

if ( isset( $fourbzcore_query ) && $fourbzcore_query ) {
	if ( $fourbzcore_query->have_posts() ) {
		echo '<div class="fourbzcore-posts-cont">';
		// Display the title, if set.
		if ( isset( $fourbzcore_layout_opts['title_text'] ) && $fourbzcore_layout_opts['title_text'] ) {
			if ( isset( $fourbzcore_layout_opts['is_widget'] ) && $fourbzcore_layout_opts['is_widget'] ) {
				echo $fourbzcore_layout_opts['title_text'];
			} else {
				echo '<h3 class="widget-title">' . $fourbzcore_layout_opts['title_text'] . '</h3>';
			}
		}
		
		// The loop
		while ( $fourbzcore_query->have_posts() ) {
			$fourbzcore_query->the_post();
			$date_format =  esc_html( get_the_date() );
	
			echo '<article class="fourbzcore-article"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' .
					get_the_title() . '</a> ';
			
			echo '<a class="post-date" href="' . esc_url( get_permalink() ) . '" title="' . 
				esc_attr( get_the_time() ) . '" rel="bookmark"><time class="post-date" datetime="' . 
				esc_attr( get_the_date( 'c' ) ) . '">' . $date_format . '</time></a>  ';
				
			comments_popup_link( '<span class="post-comments leave-reply-none">' .
				__( '0 Comments', $fourbzcore_plugin->txt_domain ) . '</span>', _x( '1 Comment', 'comments number', $fourbzcore_plugin->txt_domain ),
				_x( '% Comments', 'comments number', $fourbzcore_plugin->txt_domain ), 'comments-link' );			
	
			echo '</article><br />';
		}
		
		// Restore original Post Data 
		wp_reset_postdata();
		
		echo '</div>';
	}	
}