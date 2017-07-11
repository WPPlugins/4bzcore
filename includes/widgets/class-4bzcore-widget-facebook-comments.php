<?php
/**
 * Facebook Comments Widget Class
 *
 * @since 4bzCore 1.0.0
 *
 * @package 4bzCore 
 * @subpackage class-4bzcore-widget-facebook-comments.php
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'FourBzCore_Widget_Facebook_Comments' ) ) {  
	class FourBzCore_Widget_Facebook_Comments extends WP_Widget {
		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			global $fourbzcore_plugin;
			
			WP_Widget::__construct( 'fourbzcore_widget_facebook_comments', '4bzCore Facebook Comments Widget',
				array(
					'description'	=>	apply_filters( '4bzcore_widget_descr_facebook_comments', __( 'Display a list of your recent comments from facebook.', $fourbzcore_plugin->txt_domain ) ),
				),
				array(
					'width' 	=>	700,
					'height'	=>	350, 
				)
			);
		}
		
		/**
		 * widget function.
		 *
		 * Displays list of featured posts. Uses the corresponding shortcode function.
		 *
		 * @see WP_Widget
		 * @access public
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			// Get the plugin object
			global $fourbzcore_plugin;
			
			$options = get_option( $fourbzcore_plugin->db_options_name );
			
			if ( isset( $options['caching'] ) && $options['caching'] ) {
				$cache = get_transient( 'fourbzcore_widget_facebook_comments' );
			
				if ( ! is_array( $cache ) ) {
					$cache = array();
				}
				
				if ( isset( $cache[$args['widget_id']] ) ) {
					echo $cache[$args['widget_id']];
					return;
				}
			}
			
			extract( $args );
			
			$html_frag = '';
			$html_frag .= $before_widget;

			$title = '';
			
			if ( isset( $instance['title_text']  ) ) {
				$title = apply_filters( '4bzcore_widget_title', $before_title . $instance['title_text'] . $after_title, $instance['title_text'], $before_title, $after_title );
			}
			
			if ( ! isset( $instance['title_class'] ) ) {
				$instance['title_class'] = '';
			}
			
			$instance['title_text'] = $title;
			$instance['title_class'] .= ' widget-title';
			$instance['is_widget'] = true;
			
			$html_frag .= $fourbzcore_plugin->fourbzcore_shortcodes->facebook_comments( $instance );	
			$html_frag .= $after_widget;	
			
			if ( isset( $options['caching'] ) && $options['caching'] ) {
				if ( isset( $args['widget_id'] ) ) {
					$cache[$args['widget_id']] = $html_frag;
				}
			
				set_transient('fourbzcore_widget_facebook_comments', $cache, 'widget');
			}
			
			echo $html_frag;
			
		}


		/**
		 * update function.
		 *
		 * @see WP_Widget->update
		 * @access public
		 * @param array $new_instance
		 * @param array $old_instance
		 * @return array
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
		 * form function.
		 *
		 * @see WP_Widget->form
		 * @access public
		 * @param array $instance
		 * 
		 */
		public function form( $instance ) {
			global $fourbzcore_plugin;
		
			$fourbzcore_plugin->display_options_facebook_comments( $instance, $this );
		}
	}
}