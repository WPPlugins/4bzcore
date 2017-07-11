<?php
/**
 * Image Text Widget Class
 *
 * Used to display an image with a text description.
 *
 * @since 4bzCore 1.0.0
 *
 * @package 4bzCore 
 * @subpackage class-4bzcore-widget-image-text.php
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if ( ! class_exists( 'FourBzCore_Widget_Image_Text' ) ) {  
	class FourBzCore_Widget_Image_Text extends WP_Widget {
		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			global $fourbzcore_plugin;
			
			$extra_descr = '';
			
			WP_Widget::__construct( 'fourbzcore_widget_image_text', '4bzCore Image Text Widget',
				array( 
					'description'	=>	apply_filters( '4bzcore_widget_descr_image_text', __( "Show an image with a description.", $fourbzcore_plugin->txt_domain ) ),
				),
				array(
					'width' 	=> 	700,
					'height'	=>	350,
				)
			);
		}
		
		/**
		 * widget method.
		 *
		 * Outputs the widget
		 *
		 * @since 4bzCore 1.0.0
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			// Get the plugin object
			global $fourbzcore_plugin;
			
			$options = get_option( $fourbzcore_plugin->db_options_name );
			
			/*
			 * Check if widget is in cache first, if so retrieve and output it,  if not then construct it and cache it.
			 */
			if ( isset( $options['caching'] ) && $options['caching'] ) {
				$cache = get_transient( 'fourbzcore_widget_image_text' );

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
			$instance['is_widget'] = true;
			
			if ( ! isset( $instance['title_class'] ) ) {
				$instance['title_class'] = '';
			}
			$instance['title_class'] .= ' widget-title';
			
			$html_frag .= $fourbzcore_plugin->fourbzcore_shortcodes->image_text( $instance );
			
			$html_frag .= $after_widget;	
			
			if ( isset( $options['caching'] ) && $options['caching'] ) {
				if ( isset( $args['widget_id'] ) ) {
					$cache[$args['widget_id']] = $html_frag;
				}
				
				set_transient( 'fourbzcore_widget_image_text', $cache, 'widget' );
			}
			
			echo $html_frag;
		}


		/**
		 * update method.
		 *
		 * @since 4bzCore 1.0.0
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 * @return array $new_instance
		 */
		public function update( $new_instance, $old_instance ) {		
			$options = array();
			
			if ( is_array( $new_instance ) && 0 < count( $new_instance ) ) {
				foreach ( $new_instance as $opt => $val ) {
					if ( is_array( $val ) ) {
						$flag = false;
						$temp = array();
						
						foreach (  $val as $key => $val ) {
							if ( '' != $val ) {
								$flag = true;
								$temp[$key] = $val;
							}
						}
						
						if ( $flag ) {
							$options[$opt] = array();
							$options[$opt] = $temp;
						}
					} else {
						if ( '' !== $val ) {						
							$options[$opt] = $val;
						}
					}
				}
			}
			
			return $options;
		}

		/**
		 * form method.
		 *
		 * Display options for the widget.
		 * @since 4bzCore 1.0.0
		 *
		 * @param array $instance
		 */
		public function form( $instance ) {
			global $fourbzcore_plugin;
			
			$fourbzcore_plugin->display_options_image_text( $instance, $this );
		}
	} // End of class
}