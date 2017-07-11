<?php
/**
 * Progressbars Widget
 *
 * @since 4bzCore 1.0.0
 *
 * @package 4bzCore 
 * @subpackage class-4bzcore-widget-progressbars.php
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if ( ! class_exists( 'FourBzCore_Widget_Progressbars' ) ) {  
	class FourBzCore_Widget_Progressbars extends WP_Widget {
		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			global $fourbzcore_plugin;
			
			WP_Widget::__construct( 'fourbzcore_widget_progressbars', '4bzCore Progressbars Widget',
				array( 
					'description'	=>	apply_filters( '4bzcore_widget_descr_progressbars', __( 'Display a set of progressbars', $fourbzcore_plugin->txt_domain ) ),
				),
				array(
					'width' 	=>	700,
					'height'	=> 	350,
				) 
			);
		}
		
		/**
		 * widget method.
		 *
		 * Uses the progressbars shortcode function to display the widget.
		 * 
		 * @since 1.0.0
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			/** 
			 * Check if widget is in cache first, if so retrieve and output it,  if not then construct it 
			 * and cache it.
			 */
			global $blogcentral_opts;

			if( isset( $blogcentral_opts['caching'] ) && $blogcentral_opts['caching'] ) {
				$cache = get_transient( 'fourbzcore_widget_progressbars' );
				
				if ( ! is_array( $cache ) ) {
					$cache = array();
				}
				
				if ( isset( $cache[$args['widget_id']] ) ) {
					echo $cache[$args['widget_id']];
					return;
				}
			}
			
			extract( $args );
			
			$title = '';
		
			$html_frag = '';
			
			$html_frag .= $before_widget;

			if ( isset( $instance['title_text']  ) ) {
				$title = apply_filters( '4bzcore_widget_title', $before_title . $instance['title_text'] . $after_title, $instance['title_text'], $before_title, $after_title );
			}
			
			$instance['title_text'] = $title;
			
			if ( ! isset( $instance['title_class'] ) ) {
				$instance['title_class'] = '';
			}
			
			$instance['title_class'] .= ' widget-title';
			$instance['is_widget'] = true;
			
			global $fourbzcore_plugin;
			
			$html_frag .= $fourbzcore_plugin->fourbzcore_shortcodes->progressbars( $instance );
			$html_frag .= $after_widget;	
			
			if ( isset( $blogcentral_opts['caching'] ) && $blogcentral_opts['caching'] ) {
				if ( isset( $args['widget_id'] ) ) {
					$cache[$args['widget_id']] = $html_frag;
				}
				
				set_transient( 'fourbzcore_widget_progressbars', $cache, 'widget' );
			}
			
			echo $html_frag;
		}

		/**
		 * update method.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$items = array();
			
			if ( is_array( $new_instance['items'] ) && 0 < count( $new_instance['items'] ) ) {
				foreach ( $new_instance['items'] as $item ) {
					$key = $item['name'];
					$value = $item['value'];
					$items[$key] = $value;
				}
			}
				
			$new_instance['items'] = $items;
				
			return $new_instance;
		}

		/**
		 * form method.
		 *
		 * Uses the progressbars display options function
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance
		 */
		public function form( $instance ) {
			global $fourbzcore_plugin;
			
			$fourbzcore_plugin->display_options_progressbars( $instance, $this );
		}
	} // End of class.
}