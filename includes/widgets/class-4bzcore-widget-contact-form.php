<?php
/**
 * Contact Form Widget Class
 *
 * @since 4bzCore 1.0.0
 *
 * @package 4bzCore 
 * @subpackage class-4bzcore-widget-contact-form.php
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if ( ! class_exists( 'FourBzCore_Widget_Contact_Form' ) ) {  
	class FourBzCore_Widget_Contact_Form extends WP_Widget {
		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			global $fourbzcore_plugin;
		
			WP_Widget::__construct( 'fourbzcore_widget_contact_form', '4bzCore Contact Form Widget',
				array(
					'description'	=>	apply_filters( '4bzcore_widget_descr_contact_form', __( 'Display a contact form.', $fourbzcore_plugin->txt_domain ) ),
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
		 * Uses the contact form shortcode function to display the widget.
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
			 * Check if widget is in cache first, if so retrieve and output it,  if not then construct it 
			 * and cache it.
			 */
			if ( isset( $options['caching'] ) && $options['caching'] ) {
				$cache = get_transient( 'fourbzcore_widget_contact_form' );
				
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
			
			$html_frag .= $fourbzcore_plugin->fourbzcore_shortcodes->contact_form( $instance );		
			$html_frag .= $after_widget;	

			if ( ! empty( $options['caching'] ) ) {
				if ( isset( $args['widget_id'] ) ) {
					$cache[$args['widget_id']] = $html_frag;
				}
				
				set_transient( 'fourbzcore_widget_contact_form', $cache, 'widget' );
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
		 * form method.
		 *
		 * Uses the contact form display options function.
		 *
		 * @since 4bzCore 1.0.0
		 *
		 * @param array $instance
		 */
		public function form( $instance ) {
			global $fourbzcore_plugin;
			
			$fourbzcore_plugin->display_options_contact_form( $instance, $this );
		}
	}
}