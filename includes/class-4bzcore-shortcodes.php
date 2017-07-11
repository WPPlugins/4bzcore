<?php
/**
 * FourBzCore Shortcodes Class.
 *
 * This class creates all of the shortcodes included in the plugin. It invokes various functions to create html fragments for the attributes of various html tags. 
 *
 * These functions are also used by the custom widgets provided with this plugin to output its content.
 *
 * Filters are provided to override or augment the shortcodes' display.
 *
 * @since 4bzCore 1.0.0
 *
 * @package 4bzCore
 * @subpackage class-4bzcore-shortcodes.php
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if ( ! class_exists( 'FourBzCore_Shortcodes' ) ) {
	class FourBzCore_Shortcodes {
		// Class Variables
		/**
		 * FourBzCore Database class object.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object
		 */
		private $db;
		
		/**
		 * Default values for options.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var array
		 */
		private $defaults = array(
			'cols'			=>	'1',
			'title_text'	=>	null,
			'limit'			=>	'5',
			'is_widget'		=>	false,
			'column_width'	=> 	null,
			'column_padding'	=>	null,
			'column_margin'	=>	null,
			'content'		=>	null,
			'image_url'	=>	null,
			'image_url_width'	=>	null,
			'image_url_height'	=>	null,
			'image_url_alt'	=>	null,
		);
					
		/**
		 * Constructor. Create the shortcodes.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Set private variable to hold a FourBzCore_Database class object.
			$this->db = new FourBzCore_Database();
			
			// Add the shortcodes.
			add_shortcode( '4bzcore_recent_posts', array( $this, 'recent_posts' ) );
			add_shortcode( '4bzcore_related_posts', array( $this, 'related_posts' ) );
			add_shortcode( '4bzcore_featured_posts', array( $this, 'featured_posts' ) );
			add_shortcode( '4bzcore_popular_posts', array( $this, 'popular_posts' ) );
			add_shortcode( '4bzcore_contact_form', array( $this, 'contact_form' ) );
			add_shortcode( '4bzcore_contact_info', array( $this, 'contact_info' ) );
			add_shortcode( '4bzcore_slideshow', array( $this, 'slideshow' ) );
			add_shortcode( '4bzcore_progressbars', array( $this, 'progressbars' ) );
			add_shortcode( '4bzcore_facebook_comments', array( $this, 'facebook_comments' ) );
			add_shortcode( '4bzcore_flickr_photos', array( $this, 'flickr_photos' ) );
			add_shortcode( '4bzcore_image_text', array( $this, 'image_text' ) );
			add_shortcode( '4bzcore_author_bio', array( $this, 'author_bio' ) );
			add_shortcode( '4bzcore_column', array( $this, 'column' ) );
		}
		
		/**
		 * Posts shortcode method.
		 *
		 * General post display shortcode. Apply the fourbzcore_posts filter on the shortcode output.
		 * 
		 * @since 1.0.0
		 *
		 * @param array $atts Required. Options for the shortcode, including the query string variable that holds any query variables to be passed to the WP Query object via the database object.
		 */
		public function posts( $atts ) {
			if ( empty( $atts ) ) {
				return;
			}
			
			// Get global variables.
			global $fourbzcore_plugin;
			
			$results = $this->db->get_posts( unserialize( $atts['query'] ) );
			
			// Merge defaults with shortcode atts.
			$posts_options = shortcode_atts( $this->defaults, $atts, 'posts_options' );
			
			// Pass to the function called by the component wrapper function options saved on theme's options page, and the actual posts to display.
			$posts_atts = array(
				'fourbzcore_layout_opts'		=>	$posts_options,
				'fourbzcore_query'			=>	$results,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_posts->display( $posts_atts );
			
			return apply_filters( '4bzcore_posts', ob_get_clean(), $atts, $results );
		}
		
		/**
		 * Recent Posts shortcode method.
		 *
		 * Apply the fourbzcore_recent_posts filter on the shortcode output.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts Optional. Options for the shortcode.
		 */
		public function recent_posts( $atts ) {		
			// Get global variables
			global $fourbzcore_plugin;
			
			/*
			 * Options for the WP Query object.
			 *
			 * Need to retrieve the recent posts, ignore sticky posts if they are not a part of the recent posts set, and remove pagination.
			 */
			$query = array(
				'sticky_only'		=>	isset( $atts['sticky_only'] ) ? $atts['sticky_only'] : '',
				'ignore_sticky'		=>	1,
				'limit'				=>	isset( $atts['limit'] ) ? $atts['limit'] : '',
				'query_var'			=>	'fourbzcore_recent_posts',
				'posts_per_page'	=>	-1,
			);
			
			// Retrieve the posts using the FourBzCore Database class.
			$results = $this->db->get_posts( $query );
			
			// Merge recent posts specific defaults with shortcode atts.
			$recent_posts_options = shortcode_atts( $this->defaults, $atts, 'recent_posts_options' );
			
			$recent_posts_options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_recent_posts->get_id();
			
			// Passed to the posts shortcode display function: options, and the actual posts to display.
			$recent_posts_atts = array(
				'fourbzcore_layout_opts'	=>	$recent_posts_options,
				'fourbzcore_query'		=>	$results,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_posts->display( $recent_posts_atts );
			
			return apply_filters( '4bzcore_recent_posts', ob_get_clean(), $atts, $results );
		}
		
		/**
		 * Related Posts shortcode method.
		 *
		 * Requires the post id either in $atts or the global $post variable. Apply the fourbzcore_related_posts filter on the shortcode output.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts Optional. Options for the shortcode.
		 */
		public function related_posts( $atts ) {
			// Get global variables.
			global $fourbzcore_plugin;
			
			// Need the post id of the post to get its related posts, if not provided then return.
			if ( ! isset( $atts['post_id'] ) ) {
				global $post;
				$post_id = $post->ID;
				
				if ( ! isset( $post_id ) ) {
					return;
				}
			} else {
				$post_id = $atts['post_id'];
			}
			
			// Retrieve list of related post ids.
			$fourbzcore = get_post_meta( $post_id, $fourbzcore_plugin->db_options_name, true );
			
			$related = isset( $fourbzcore['related'] ) ? trim( $fourbzcore['related'] ) : '';
			
			// If no related posts, then return.
			if ( ! $related ) {
				return __( 'No related posts', $fourbzcore_plugin->txt_domain );
			}
			
			// Post should not be related to self
			$new_string = preg_replace( '/\b' . $post_id . '/', '', $related );
			$related = trim( $new_string );
			
			$related = explode( ' ', $related );
			$related =  "'" . implode( "' , '" , $related ) . "'";
			
			/*
			 * Options for the WP Query object.
			 *
			 * Pass the list of related posts ids, any limit, the query variable, and remove pagination.
			 * Ignore sticky posts that are not a part of the related posts list.
			 */
			$query = array(
				'limit'				=>	isset( $atts['limit'] ) ? $atts['limit'] : -1,
				'query_var'			=>	'fourbzcore_related_posts',
				'data'				=>	$related,
				'ignore_sticky'		=>	1,
				'posts_per_page'	=>	-1,
			);
		
			$results = $this->db->get_posts( $query );
			
			// If no posts, then return.
			if ( ! $results->posts ) {
				return;
			}
			
			// Merge related posts specific defaults with general component layout defaults.
			$related_posts_options = shortcode_atts( $this->defaults, $atts, 'related_posts_options' );
			
			$related_posts_options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_related_posts->get_id();
			
			// Passed to the posts shortcode display function: options, and the actual posts to display.
			$related_posts_atts = array(
				'fourbzcore_layout_opts'	=>	$related_posts_options,
				'fourbzcore_query'		=>	$results,
			);
				
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_posts->display( $related_posts_atts );
			
			return apply_filters( '4bzcore_related_posts', ob_get_clean(), $atts, $results );
		}
		
		/**
		 * Featured Posts shortcode method.
		 *
		 * Display only sticky posts. Apply the fourbzcore_featured_posts filter on the shortcode output.
		 * 
		 * @since 1.0.0
		 *
		 * @param array $atts Optional. Options for the shortcode.
		 */
		public function featured_posts( $atts ) {
			// Get global variables.
			global $fourbzcore_plugin;
			
			// Options for the WP Query object.
			$query = array(
				'sticky_only'		=>	true,
				'limit'				=>	isset( $atts['limit'] ) ? $atts['limit'] : -1,
				'query_var'			=>	'fourbzcore_posts',
				'posts_per_page'	=>	-1,
			);
			
			$results = $this->db->get_posts( $query );
			
			// Merge featured posts specific defaults with general component layout defaults.
			$posts_options = shortcode_atts( $this->defaults, $atts, 'posts_options' );
		
			$posts_options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_featured_posts->get_id();
			
			// Passed to the posts shortcode display function: options, and the actual posts to display.
			$posts_atts = array(
				'fourbzcore_layout_opts'	=>	$posts_options,
				'fourbzcore_query'		=>	$results,
			);
				
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_posts->display( $posts_atts );
			
			return apply_filters( '4bzcore_featured_posts', ob_get_clean(), $atts, $results );
		}
		
		/**
		 * Popular Posts shortcode method.
		 *
		 * Display popular posts based on number of comments. Apply the fourbzcore_popular_posts filter on the shortcode output.
		 * 
		 * @since 1.0.0
		 *
		 * @param array $atts Optional. Options for the shortcode.
		 */
		public function popular_posts( $atts ) {
			// Get global variables.
			global $fourbzcore_plugin;
		
			/*
			 * Options for the WP Query object.
			 *
			 * Need to ignore sticky posts because they might take the place of a more popular post.
			 */
			$query = array(
				'orderby'			=>	'comment_count',
				'ignore_sticky'		=>	1,
				'limit'				=>	isset( $atts['limit'] ) ? $atts['limit'] : -1,
				'query_var'			=>	'fourbzcore_popular_posts',
				'posts_per_page'	=>	-1,
			);
			
			$results = $this->db->get_posts( $query );
			
			// Merge popular posts specific defaults with shortcode atts.
			$posts_options = shortcode_atts( $this->defaults, $atts, 'posts_options' );
		
			$posts_options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_popular_posts->get_id();
	
			// Pass to the function called by the component wrapper function options saved on theme's options page, and the actual posts to display.
			$posts_atts = array(
				'fourbzcore_layout_opts'	=>	$posts_options,
				'fourbzcore_query'		=>	$results,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_posts->display( $posts_atts );

			return apply_filters( '4bzcore_popular_posts', ob_get_clean(), $atts, $results );
		}
		
		/**
		 * Display items, usually a skill, as progressbars
		 * 
		 * Apply the fourbzcore_progressbars filter on the shortcode output.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts Required. Options for the shortcode, including the list of items to display as
		 * progressbars.
		 */
		public function progressbars( $atts ) {
			if ( empty( $atts ) ) {
				return;
			}
			
			// Get global variable.
			global $fourbzcore_plugin;
			
			// Default settings specific to progressbars.
			$defaults = array(
				'items'	=>	array(),
			);
			
			// Merge progressbars specific defaults with general component layout defaults.
			$options = shortcode_atts( array_merge( $this->defaults, $defaults ), $atts, 'options' );
			
			$options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_progressbars->get_id();
			
			/*
			 * If skills variable is not input as an array, then is a string constructed by the shortcode builder, 
			 * and need to do some string manipulation and json_decode the string.
			 */
			if ( ! is_array( $options['items'] ) ) {
				$items = preg_replace( '/\(/','[', $options['items'] );
				$items = preg_replace( '/\)/',']', $items );
				$items =  (array) json_decode( $items );
			} else {
				$items = $options['items'];
			}
			
			$progressbar_atts = array(
				'fourbzcore_layout_opts'	=>	$options,
				'fourbzcore_items'		=>	$items,
			);
		
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_progressbars->display( $progressbar_atts );
			
			return apply_filters( '4bzcore_progressbars', ob_get_clean(), $atts, $items );
		}
		
		/**
		 * Contact Info shortcode method.
		 *
		 * Method to display the contact information for an organization or a user. Apply the fourbzcore_contact_info filter on the shortcode output.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts Optional. Options for the shortcode.
		 */
		public function contact_info( $atts ) {
			// Get global variables.
			global $fourbzcore_plugin;
			
			// Retrieve the contact information for a user if a user id is provided, or an organization.
			$user_id = '';
			
			if ( ( isset( $atts['user_id'] ) && null !== $atts['user_id'] ) ) {
				$user_id = $atts['user_id'];
			}
			
			if ( $user_id ) {
				$results = $this->db->get_user_contact_info( $user_id );
				
				$results = isset( $results[0] ) ? $results[0] : array();
				$fourbzcore_fields = isset( $results['fourbzcore_options'] ) ?
					unserialize( $results['fourbzcore_options'] ) :
					array();
				
				$contact_info = array_merge( $results, $fourbzcore_fields );
				$contact_info['address_icon'] = isset( $atts['address_icon'] ) ? $atts['address_icon'] : '';
				$contact_info['email_icon'] = isset( $atts['email_icon'] ) ? $atts['email_icon'] : '';
				$contact_info['phone_icon'] = isset( $atts['phone_icon'] ) ? $atts['phone_icon'] : '';
				$contact_info['url_icon'] = isset( $atts['url_icon'] ) ? $atts['url_icon'] : '';
				$contact_info['facebook_icon'] = isset( $atts['facebook_icon'] ) ? $atts['facebook_icon'] : '';
				$contact_info['twitter_icon'] = isset( $atts['twitter_icon'] ) ? $atts['twitter_icon'] : '';
				$contact_info['google_icon'] = isset( $atts['google_icon'] ) ? $atts['google_icon'] : '';
				$contact_info['linkedin_icon'] = isset( $atts['linkedin_icon'] ) ? $atts['linkedin_icon'] : '';
				$contact_info['tumblr_icon'] = isset( $atts['tumblr_icon'] ) ? $atts['tumblr_icon'] : '';
				$contact_info['pinterest_icon'] = isset( $atts['pinterest_icon'] ) ? $atts['pinterest_icon'] : '';
				$contact_info['instagram_icon'] = isset( $atts['instagram_icon'] ) ? $atts['instagram_icon'] : '';
			
				unset( $contact_info['fourbzcore_options'] );
				
			} else {
				$contact_info = $atts;
			}
			
			$contact_options = shortcode_atts( $this->defaults, $atts, 'contact_options' );
			
			$contact_options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_contact_info->get_id();
			
			// Passed to the contact info shortcode display function: options, and the actual posts to display.
			$contact_atts = array(
				'fourbzcore_layout_opts'	=>	$contact_options,
				'fourbzcore_contact_info'	=>	$contact_info,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_contact_info->display( $contact_atts );
			
			return apply_filters( '4bzcore_contact_info', ob_get_clean(), $atts, $contact_info );
		}
		
		/**
		 * Contact Form shortcode method.
		 *
		 * Method to display a contact form powered by any plugin that uses a shortcode for its display, along with an optional display of 
		 * contact information.
		 *
		 * Apply the fourbzcore_contact_form filter on the shortcode output.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts Optional. Options for the shortcode.
		 */
		public function contact_form( $atts ) {
			// Get global variables.
			global $fourbzcore_plugin;
			
			$contact_form = isset( $atts['contact_form'] ) && $atts['contact_form'] ? $atts['contact_form'] : '';
			
			// Defaults specific to the contact form and contact info shortcodes.
			$defaults = array(
				'share_title'			=>	'',
				'contact_address'		=>	null,
				'contact_phone'			=>	null,
				'contact_email'			=>	null,
				'contact_url'			=>	null,
				'contact_facebook'		=>	null,
				'contact_twitter'		=>	null,
				'contact_google'		=>	null,
				'contact_linkedin'		=>	null,
				'contact_tumblr'		=>	null,
				'contact_instagram'		=>	null,
				'contact_pinterest'		=>	null,
				'address_icon'			=>	null,
				'phone_icon'			=>	null,
				'email_icon'			=>	null,
				'url_icon'				=>	null,
				'facebook_icon'			=>	null,
				'twitter_icon'			=>	null,
				'google_icon'			=>	null,
				'linkedin_icon'			=>	null,
				'tumblr_icon'			=>	null,
				'instagram_icon'		=>	null,
				'pinterest_icon'		=>	null,
				'show_contact'			=>	null,
				'show_map'				=>	null,
				'user_id'				=>	null,
			);
			
			// Merge contact form specific defaults with general component layout defaults.
			$form_options = shortcode_atts( array_merge( $this->defaults, $defaults ), $atts, 'form_options' );
			
			// If atts are from the shortcode builder, then need to do some string manipulation of the contact form.
			if ( $contact_form ) {
				$contact_form = str_replace( "(", "[", $contact_form );
				$contact_form = str_replace( ")", "]", $contact_form );
			}
			
			$form_options['contact_form'] = $contact_form;
			
			if ( isset( $atts['show_map'] ) && $atts['show_map'] ) {
				$key = get_option( $fourbzcore_plugin->db_options_name );
				$form_options['google_app_id'] = ! empty( $key['google_app_id'] ) ? $key['google_app_id'] : '';
			}
			
			$form_options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_contact_form->get_id();
			
			$form_atts = array(
				'fourbzcore_layout_opts'	=>	$form_options,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_contact_form->display( $form_atts );
			
			return apply_filters( '4bzcore_contact_form', ob_get_clean(), $atts, $form_options );
		}
		
		/**
		 * Slideshow shortcode method.
		 *
		 * Display a flexslider slider. Apply the fourbzcore_slideshow filter on the shortcode output.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts Required. Options for the shortcode, including an array of html fragments to be used as the slides.
		 */
		public function slideshow( $atts ) {
			if ( empty( $atts['slides'] ) ) {
				return;
			}
			
			// Get global variables.
			global $fourbzcore_plugin;

			// Defaults specific to the slideshow shortcode.
			$defaults = array(
				'slideshow'			=>	true,
				'slides'			=>	array(),
			);
			
			// Merge slideshow specific defaults with general component layout defaults.
			$slideshow_options = shortcode_atts( array_merge( $this->defaults, $defaults ), $atts, 'slideshow_options' );
			
			$slideshow_options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_slideshow->get_id();
			/*
			 * If slides variable is not input as an array, then is a string constructed by the shortcode builder, 
			 * and need to do some string manipulation.
			 */
			if ( ! is_array( $slideshow_options['slides'] ) ) {
				$items = html_entity_decode( $slideshow_options['slides'] );
				$items = explode( '=>', $items );
			} else {
				$items = $slideshow_options['slides'];
			}
			
			$slideshow_atts = array(
				'fourbzcore_layout_opts'	=>	$slideshow_options,
				'fourbzcore_items'		=>	$items,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_slideshow->display( $slideshow_atts );
			
			return apply_filters( '4bzcore_slideshow', ob_get_clean(), $atts, $items );
		}
		
		/**
		 * Facebook Comments shortcode method.
		 *
		 * Apply the fourbzcore_facebook_comments filter on the shortcode output.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts Optional. Options for the shortcode.
		 */
		public function facebook_comments( $atts ) {
			// Get global variables.
			global $fourbzcore_plugin;
			// Merge facebook comments specific defaults with general component layout defaults.
			$options = shortcode_atts( $this->defaults, $atts, 'posts_options' );
				
			$options['limit'] = isset( $atts['limit'] ) ? $atts['limit'] : 5;
			
			$options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_facebook_comments->get_id();
			
			$facebook_atts = array(
				'fourbzcore_layout_opts'	=>	$options,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_facebook_comments->display( $facebook_atts );
			
			return apply_filters( '4bzcore_facebook_comments', ob_get_clean(), $atts, $options );
		}
		
		/**
		 * Display photos from flickr.
		 *
		 * Apply the fourbzcore_flickr_photos filter on the shortcode output.
		 *
		 * @since 1.0.0
		 *
		 * @param array $atts Required. Options for the shortcode.
		 */
		public function flickr_photos( $atts ) {
			if ( ! isset( $atts['user_id'] ) ) {
				return;
			}
			
			// Get global variables.
			global $fourbzcore_plugin;
			
			// Merge flickr photos specific defaults with general component layout defaults.
			$options = shortcode_atts( $this->defaults, $atts, 'posts_options' );
				
			$options['limit'] = isset( $atts['limit'] ) ? $atts['limit'] : '6';
			$options['user_id'] = $atts['user_id'];
			
			$options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_flickr_photos->get_id();
			
			$flickr_atts = array(
				'fourbzcore_layout_opts'	=>	$options,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_flickr_photos->display( $flickr_atts );
			
			return apply_filters( '4bzcore_flickr_photos', ob_get_clean(), $atts, $options );
		}
		
		/**
		 * Display an image text
		 *
		 * Apply the fourbzcore_image_text filter on the shortcode output.
		 *
		 * @since 1.0.1
		 *
		 * @param array $atts Required. Options for the shortcode.
		 */
		public function image_text( $atts ) {
			
			// Get global variables.
			global $fourbzcore_plugin;
			
			// Merge image_text specific defaults with general component layout defaults.
			$options = shortcode_atts( $this->defaults, $atts, 'options' );
			
			$options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_image_text->get_id();
			
			$image_text_atts = array(
				'fourbzcore_layout_opts'	=>	$options,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_image_text->display( $image_text_atts );
			
			return apply_filters( '4bzcore_image_text', ob_get_clean(), $atts, $options );
		}
		
		/**
		 * Display an author bio
		 *
		 * Apply the fourbzcore_author_bio filter on the shortcode output.
		 *
		 * @since 1.0.1
		 *
		 * @param array $atts Required. Options for the shortcode.
		 */
		public function author_bio( $atts ) {
			
			// Get global variables.
			global $fourbzcore_plugin;
			
			// Merge author_bio specific defaults with general component layout defaults.
			$options = shortcode_atts( $this->defaults, $atts, 'options' );
			
			$options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_author_bio->get_id();
			
			$author_atts = array(
				'fourbzcore_layout_opts'	=>	$options,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_author_bio->display( $author_atts );
			
			return apply_filters( '4bzcore_author_bio', ob_get_clean(), $atts, $options );
		}
		
		/**
		 * Display a column
		 *
		 * Apply the fourbzcore_column filter on the shortcode output.
		 *
		 * @since 1.0.1
		 *
		 * @param array $atts Required. Options for the shortcode.
		 * @param array $content Required. The content to display in a column.
		 */
		public function column( $atts, $content ) {
			
			if ( ! isset( $atts ) || ! isset ( $content ) ) {
				return;
			}
			
			// Get global variables.
			global $fourbzcore_plugin;
			
			// Merge column specific defaults with general component layout defaults.
			$options = shortcode_atts( $this->defaults, $atts, 'posts_options' );
			
			$options['id'] = $fourbzcore_plugin->fourbzcore_shortcode_column->get_id();
			$options['content'] = $content;
			
			$column_atts = array(
				'fourbzcore_layout_opts'	=>	$options,
			);
			
			ob_start();
			
			$fourbzcore_plugin->fourbzcore_shortcode_column->display( $column_atts );
			
			return apply_filters( '4bzcore_column', ob_get_clean(), $atts, $content );
		}
	}// End of class
}