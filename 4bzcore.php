<?php
/**
 * Plugin Name: 4bzCore
 * Plugin URI: http://wordpress.org/plugins/4bzcore
 * Author: 4bzthemes
 * Author URI: http://4bzthemes.com
 * Description: A collection of shortcodes, widgets, a shortcode builder, multiple featured images, a related posts module, video and audio embed options, and extra fields for the user profile form. Includes Recent Posts, Featured Posts, Popular Posts, Related Posts, Image Text, Progressbars, Flexslider Slideshow, Contact Info, Contact Form, Flickr Photos, Facebook Comments, Author Bio, and Column shortcodes and widgets. This plugin provides filters for theme and plugin authors to override or augment the default shortcodes and widgets' options, display, and description. 4bzthemes recommends the 4bzCore plugin for all of their themes.
 * Version: 1.0.5
 * Requires at least: 3.8
 * Tested up to: 4.3
 * Text Domain: 4bzcore
 * Domain Path: /languages/
 * LOCALIZATION
 * Place your language file in the domain path directory within the plugin directory and name it
 * "4bzcore-{language}.mo" replace {language} with your language value from wp-config.php.	
 * CHANGELOG
 * See readme.txt
 * License: GPLv2 or later
 ------------------------------------------------------------------------
	Table of Contents
	
	1.0 Define Class Variables
	2.0 Define Class Methods
		2.1 Constructor + Initialization
		2.2 Setup + Utitity Methods
			- Autoload Classes
			- Load Text Domain
			- Instantiate Shortcodes
			- Register Widgets
		2.3 Admin Side Methods
			2.3.1 Enqueue Scripts + Style
			2.3.2 Controller + Clear Cache + Save Options
			2.3.3 Plugin Options Page
				2.3.3.1 Main
				2.3.3.2 General Component Options
				2.3.3.3 Posts Options
				2.3.3.4 Shortcodes + Widgets Options: Not Post Based
			2.3.4 Tinymce + Shortcode Builder
			2.3.5 Post Edit Page
			2.3.6 User Profile Page
			2.3.7 Admin Ajax
		2.4 Front End Methods
-------------------------------------------------------------------------*/
/*  Copyright 2015 4bzthemes (email : 4bzthemes@gmail.com)
-------------------------------------------------------------------------*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if ( ! class_exists( 'FourBzCore' ) ) {  
	class FourBzCore {
		/**
		 * 1.0 Define Class Variables
		 *-----------------------------------------------------------------------*/
		
		/**
		 * Plugin prefix used to construct various other variables.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var string $plugin_prefix
		 */
		private $plugin_prefix;
		
		/**
		 * Name of the plugin.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var string $plugin_name
		 */
		private $plugin_name;
		
		/**
		 * The url of the plugin.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var string $plugin_url
		 */
		private $plugin_url;
		
		/**
		 * Plugin directory.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var string $plugin_dir
		 */
		private $plugin_dir;
		
		/**
		 * The link to the plugin documentation.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var string $plugin_docs
		 */
		private $plugin_docs;
		
		/**
		 * Plugin page slug used when creating the administration submenu.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var string $page_slug
		 */
		private $page_slug;
		
		/**
		 * Text domain used for localization.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var string $txt_domain
		 */
		public $txt_domain;
		
		/**
		 * Database options name.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var string $db_options_name
		 */
		public $db_options_name;
		
		/**
		 * Posts shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_posts
		 */
		public $fourbzcore_shortcode_posts;
		
		/**
		 * Recent Posts shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_recent_posts
		 */
		public $fourbzcore_shortcode_recent_posts;
		
		/**
		 * Related Posts shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_related_posts
		 */
		public $fourbzcore_shortcode_related_posts;
		
		/**
		 * Featured Posts shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_featured_posts
		 */
		public $fourbzcore_shortcode_featured_posts;
		
		/**
		 * Popular Posts shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_popular_posts
		 */
		public $fourbzcore_shortcode_popular_posts;
		
		/**
		 * Slideshow shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_slideshow
		 */
		public $fourbzcore_shortcode_slideshow;
		
		/**
		 * Progressbars shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_progressbars
		 */
		public $fourbzcore_shortcode_progressbars;
		
		/**
		 * Contact Info shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_contact_info
		 */
		public $fourbzcore_shortcode_contact_info;
		
		/**
		 * Contact Form shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_contact_form
		 */
		public $fourbzcore_shortcode_contact_form;
		
		/**
		 * Facebook Comments shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_facebook_comments
		 */
		public $fourbzcore_shortcode_facebook_comments;
		
		/**
		 * Flickr Photos shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_flickr_photos
		 */
		public $fourbzcore_shortcode_flickr_photos;
		
		/**
		 * Image Text shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_image_text
		 */
		public $fourbzcore_shortcode_image_text;
		
		/**
		 * Author Bio shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_author_bio
		 */
		public $fourbzcore_shortcode_author_bio;
		
		/**
		 * Column shortcode object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode_column
		 */
		public $fourbzcore_shortcode_column;
		
		/**
		 * Shortcodes object.
		 *
		 * @since 1.0.0
		 * @access public
		 * @var object $fourbzcore_shortcode
		 */
		public $fourbzcore_shortcode;
		
		/**
		 * 2.0 Define Class Methods
		 *-----------------------------------------------------------------------*/
		 
		/**
		 * 2.1 Constructor + Initialization
		 *-----------------------------------------------------------------------*/
		 
		/**
		 * Constructor
		 *
		 * Does the following:
		 * 	- Register the autoload function.
		 *	- Add action and filter hooks.
		 *	- Calls the initialization function.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Auto-load classes on demand.
			if ( function_exists( "__autoload" ) ) {
				spl_autoload_register( "__autoload" );
			}
			
			spl_autoload_register( array( $this, 'autoload' ) );						
			
			// Hooks.
			
			add_action( 'init', array( $this, 'init' ), 0 );
			
			// Setup plugin
			add_action( 'plugins_loaded', array( $this, 'plugin_setup' ) );
			
			// Add theme's options page to the admin menu.
			add_action( 'admin_menu', array( $this, 'add_options_page' ) ); 
			
			// Enqueue scripts needed on administration side.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			
			// Tinymce filters and actions.
			add_filter( 'tiny_mce_before_init', array( $this, 'change_mce_options' ) );
			
			// Add button to tinymce menu to insert shortcodes into posts' content.
			add_action( 'init', array( $this, 'shortcode_builder_tinymce_buttons' ) );
			
			// Register widgets.
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
			
			// Add metaboxes to the post edit page for post options provided by plugin.
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_postdata' ) );
			
			// Add extra fields to user profile form.
			add_action( 'show_user_profile', array( $this, 'profile_form' ), 10, 1 );
			add_action( 'edit_user_profile', array( $this, 'profile_form' ), 10, 1 );
			add_action( 'profile_update', array( $this, 'save_user_cfs' ) );

			// Admin ajax functions.
			add_action( "wp_ajax_display_shortcode_builder", array( $this, "display_shortcode_builder" ) );
			add_action( "wp_ajax_nopriv_display_shortcode_builder", array( $this, "no_go" ) );
			
			$this->init();		
			
			// Enqueue scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_footer', array( $this, 'output_social_scripts' ), 100 );
		}
		
		/**
		 * Initialize class variables and create shortcodes
		 *
		 * @since 1.0.0
		 */
		public function init() {
			$this->txt_domain = '4bzcore';
			$this->plugin_prefix = 'fourbzcore';
			$this->db_options_name = $this->plugin_prefix . '_options';
			$this->plugin_name = __( '4bzCore', $this->txt_domain );
			$this->plugin_url = plugin_dir_url( __FILE__ );
			$this->plugin_dir = plugin_dir_path( __FILE__ );
			$this->plugin_docs =  'http://4bzthemes.com/plugin/4bzcore/';
			$this->page_slug = $this->plugin_prefix . '_options';
			
			// Create the shortcodes.
			$this->include_shortcodes();
		}
		
		/**
		 * 2.2 Setup + Utitity Methods
		 *-----------------------------------------------------------------------*/
		public function plugin_setup() {
			/*
			 * Make plugin available for translation.
			 * Translations can be saved in the /languages/ directory.
			 */
			load_plugin_textdomain( $this->txt_domain, '' ,'/languages' );
		}
		
		/**
		 * Auto-load classes.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $class
		 */
		private function autoload( $class ) {
			$class = strtolower( $class );
			
			if ( 0 === strpos( $class, 'fourbzcore_shortcode_' ) ) {
				$path = dirname( __FILE__ ) . '/includes/shortcodes/';
				$file = 'class-' . str_replace( 'four', '4', str_replace( '_', '-', $class ) ) . '.php';

					
				if ( is_readable( $path . $file ) ) {
					require( $path . $file );
					return;
				}
			} else {
				$path = dirname( __FILE__ ) . '/includes/';
				$file = 'class-' . str_replace( 'four', '4', str_replace( '_', '-', $class ) ) . '.php';
				
				if ( is_readable( $path . $file ) ) {
					require( $path . $file );
					return;
				}
			}
		}
		
		/**
		 * Instantiate shortcodes and the fourbzcore shortcodes class.
		 *
		 * @since 1.0.0
		 */
		public function include_shortcodes() {
			$this->fourbzcore_shortcode_posts = new FourBzCore_Shortcode_Posts();
			$this->fourbzcore_shortcode_recent_posts = new FourBzCore_Shortcode_Recent_Posts();
			$this->fourbzcore_shortcode_related_posts = new FourBzCore_Shortcode_Related_Posts();
			$this->fourbzcore_shortcode_featured_posts = new FourBzCore_Shortcode_Featured_Posts();
			$this->fourbzcore_shortcode_popular_posts = new FourBzCore_Shortcode_Popular_Posts();
			$this->fourbzcore_shortcode_contact_info = new FourBzCore_Shortcode_Contact_Info();
			$this->fourbzcore_shortcode_contact_form = new FourBzCore_Shortcode_Contact_Form();
			$this->fourbzcore_shortcode_slideshow = new FourBzCore_Shortcode_Slideshow();
			$this->fourbzcore_shortcode_progressbars = new FourBzCore_Shortcode_Progressbars();
			$this->fourbzcore_shortcode_facebook_comments = new FourBzCore_Shortcode_Facebook_Comments();
			$this->fourbzcore_shortcode_flickr_photos = new FourBzCore_Shortcode_Flickr_Photos();
			$this->fourbzcore_shortcode_image_text = new FourBzCore_Shortcode_Image_Text();
			$this->fourbzcore_shortcode_author_bio = new FourBzCore_Shortcode_Author_Bio();
			$this->fourbzcore_shortcode_column = new FourBzCore_Shortcode_Column();
			
			$this->fourbzcore_shortcodes = new FourBzCore_Shortcodes();
		}
		
		/**
		 * Register the widgets
		 *
		 * @since 1.0.0
		 */
		public function register_widgets() {
			include_once( 'includes/widgets/class-4bzcore-widget-featured-posts.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-related-posts.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-recent-posts.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-popular-posts.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-image-text.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-contact-info.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-contact-form.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-slideshow.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-progressbars.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-facebook-comments.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-flickr-photos.php' );
			include_once( 'includes/widgets/class-4bzcore-widget-author-bio.php' );
			
			register_widget( 'FourBzCore_Widget_Featured_Posts' );
			register_widget( 'FourBzCore_Widget_Related_Posts' );
			register_widget( 'FourBzCore_Widget_Recent_Posts' );
			register_widget( 'FourBzCore_Widget_Popular_Posts' );
			register_widget( 'FourBzCore_Widget_Image_Text' );
			register_widget( 'FourBzCore_Widget_Contact_Info' );
			register_widget( 'FourBzCore_Widget_Contact_Form' );
			register_widget( 'FourBzCore_Widget_Slideshow' );
			register_widget( 'FourBzCore_Widget_Progressbars' );
			register_widget( 'FourBzCore_Widget_Facebook_Comments' );
			register_widget( 'FourBzCore_Widget_Flickr_Photos' );
			register_widget( 'FourBzCore_Widget_Author_Bio' );
		}
		 
		/**
		 * 2.3 Admin Side Methods
		 *-----------------------------------------------------------------------*/
		
		/**
		 * 2.3.1 Enqueue Scripts + Style
		 *-----------------------------------------------------------------------*/
		
		/**
		 * Enqueue scripts and styles needed on administration side
		 *
		 * @since 1.0.0
		 */
		public function admin_scripts() {
			$screen = get_current_screen();
			
			wp_register_script( $this->plugin_prefix . '-admin-script', $this->plugin_url  . 'js/admin.js',
				array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable', ) );
			
			if ( ( isset( $_GET['page'] ) && ( $this->plugin_prefix . '_options' === $_GET['page'] ) ) || 'post' === $screen->id || 
				'page' === $screen->id || 'product' === $screen->id || 'user-edit' === $screen->id ||
				'profile' === $screen->id || 'widgets' === $screen->id ) {
				$theme = wp_get_theme();
				$theme_name = trim( $theme->get( 'Name' ) );
				
				// Enqueue jquery, jquery ui, dialog, and sortable.
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-dialog' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				
				// Enqueue wordpress media uploader.
				wp_enqueue_media();
			
				// Enqueue admin script.
				wp_enqueue_script( $this->plugin_prefix . '-admin-script', $this->plugin_url  . 'js/admin.js',
					array( 'jquery','jquery-ui-core', 'jquery-ui-sortable', ) );
				
				
				// Enqueue jquery dialog style.
				wp_enqueue_style ( 'wp-jquery-ui-dialog' );
				
				// Enqueue font awesome style.
				wp_enqueue_style( 'font-awesome', $this->plugin_url . 'font-awesome/css/font-awesome.min.css' );
				
				// Need style for the shortcode builder and widgets page.
				wp_enqueue_style( $this->plugin_prefix . '-admin', $this->plugin_url . 'css/admin.css' );
				
				// Font awesome variable.
				$fa = array( 'fa-glass','fa-music','fa-search','fa-envelope-o','fa-heart','fa-star','fa-star-o',
					'fa-user','fa-film','fa-th-large', 'fa-th', 'fa-th-list', 'fa-check', 'fa-times',
					'fa-search-plus', 'fa-search-minus', 'fa-power-off', 'fa-signal', 'fa-gear', 'fa-cog',
					'fa-trash-o', 'fa-home', 'fa-file-o', 'fa-clock-o', 'fa-road', 'fa-download',
					'fa-arrow-circle-o-down', 'fa-arrow-circle-o-up', 'fa-inbox', 'fa-play-circle-o',
					'fa-rotate-right', 'fa-repeat', 'fa-refresh', 'fa-list-alt', 'fa-lock', 'fa-flag',
					'fa-headphones', 'fa-volume-off', 'fa-volume-down', 'fa-volume-up', 'fa-qrcode', 'fa-barcode',
					'fa-tag', 'fa-tags', 'fa-book', 'fa-bookmark', 'fa-print', 'fa-camera',	'fa-font', 'fa-bold',
					'fa-italic', 'fa-text-height', 'fa-text-width', 'fa-align-left', 'fa-align-center',
					'fa-align-right', 'fa-align-justify', 'fa-list', 'fa-dedent', 'fa-outdent', 'fa-indent',
					'fa-video-camera', 'fa-picture-o', 'fa-pencil', 'fa-map-marker', 'fa-adjust', 'fa-tint', 'fa-edit',
					'fa-pencil-square-o', 'fa-share-square-o', 'fa-check-square-o', 'fa-arrows', 'fa-step-backward',
					'fa-fast-backward', 'fa-backward', 'fa-play', 'fa-pause', 'fa-stop', 'fa-forward',
					'fa-fast-forward', 'fa-step-forward', 'fa-eject', 'fa-chevron-left', 'fa-chevron-right',
					'fa-plus-circle', 'fa-minus-circle', 'fa-times-circle', 'fa-check-circle', 'fa-question-circle',
					'fa-info-circle', 'fa-crosshairs', 'fa-times-circle-o', 'fa-check-circle-o', 'fa-ban',
					'fa-arrow-left', 'fa-arrow-right', 'fa-arrow-up', 'fa-arrow-down', 'fa-mail-forward', 'fa-share',
					'fa-expand', 'fa-compress', 'fa-plus', 'fa-minus', 'fa-asterisk', 'fa-exclamation-circle',
					'fa-gift', 'fa-leaf', 'fa-fire', 'fa-eye', 'fa-eye-slash', 'fa-warning', 'fa-exclamation-triangle',
					'fa-plane', 'fa-calendar', 'fa-random', 'fa-comment', 'fa-magnet', 'fa-chevron-up',
					'fa-chevron-down', 'fa-retweet', 'fa-shopping-cart', 'fa-folder', 'fa-folder-open', 'fa-arrows-v',
					'fa-arrows-h', 'fa-bar-chart-o', 'fa-twitter-square', 'fa-facebook-square', 'fa-camera-retro',
					'fa-key', 'fa-gears', 'fa-cogs', 'fa-comments', 'fa-thumbs-o-up',' fa-thumbs-o-down',
					'fa-star-half', 'fa-heart-o', 'fa-sign-out', 'fa-linkedin-square', 'fa-thumb-tack',
					'fa-external-link', 'fa-sign-in', 'fa-trophy', 'fa-github-square', 'fa-upload', 'fa-lemon-o',
					'fa-phone', 'fa-square-o', 'fa-bookmark-o', 'fa-phone-square', 'fa-twitter', 'fa-facebook',
					'fa-github', 'fa-unlock', 'fa-credit-card', 'fa-rss', 'fa-hdd-o', 'fa-bullhorn', 'fa-bell',
					'fa-certificate', 'fa-hand-o-right', 'fa-hand-o-left', 'fa-hand-o-up', 'fa-hand-o-down',
					'fa-arrow-circle-left', 'fa-arrow-circle-right', 'fa-arrow-circle-up', 'fa-arrow-circle-down',
					'fa-globe', 'fa-wrench','fa-tasks', 'fa-filter', 'fa-briefcase', 'fa-arrows-alt', 'fa-group',
					'fa-users', 'fa-chain', 'fa-link', 'fa-cloud', 'fa-flask', 'fa-cut', 'fa-scissors', 'fa-copy',
					'fa-files-o', 'fa-paperclip', 'fa-save', 'fa-floppy-o', 'fa-square', 'fa-bars', 'fa-list-ul',
					'fa-list-ol', 'fa-strikethrough', 'fa-underline', 'fa-table', 'fa-magic', 'fa-truck',
					'fa-pinterest', 'fa-pinterest-square', 'fa-google-plus-square', 'fa-google-plus', 'fa-money',
					'fa-caret-down', 'fa-caret-up', 'fa-caret-left', 'fa-caret-right', 'fa-columns', 'fa-unsorted',
					'fa-sort', 'fa-sort-down', 'fa-sort-asc', 'fa-sort-up', 'fa-sort-desc', 'fa-envelope',
					'fa-linkedin', 'fa-rotate-left', 'fa-undo', 'fa-legal', 'fa-gavel', 'fa-dashboard',
					'fa-tachometer', 'fa-comment-o', 'fa-comments-o', 'fa-flash', 'fa-bolt', 'fa-sitemap',
					'fa-umbrella', 'fa-paste', 'fa-clipboard', 'fa-lightbulb-o', 'fa-exchange', 'fa-cloud-download',
					'fa-cloud-upload', 'fa-user-md', 'fa-stethoscope', 'fa-suitcase', 'fa-bell-o', 'fa-coffee',
					'fa-cutlery', 'fa-file-text-o', 'fa-building-o', 'fa-hospital-o', 'fa-ambulance', 'fa-medkit',
					'fa-fighter-jet', 'fa-beer', 'fa-h-square', 'fa-plus-square', 'fa-angle-double-left',
					'fa-angle-double-right', 'fa-angle-double-up', 'fa-angle-double-down', 'fa-angle-left',
					'fa-angle-right', 'fa-angle-up', 'fa-angle-down', 'fa-desktop', 'fa-laptop', 'fa-tablet',
					'fa-mobile-phone', 'fa-mobile', 'fa-circle-o', 'fa-quote-left', 'fa-quote-right',
					'fa-spinner', 'fa-circle', 'fa-mail-reply', 'fa-reply', 'fa-github-alt', 'fa-folder-o',
					'fa-folder-open-o', 'fa-smile-o', 'fa-frown-o', 'fa-meh-o', 'fa-gamepad', 'fa-keyboard-o',
					'fa-flag-o', 'fa-flag-checkered', 'fa-terminal', 'fa-code', 'fa-reply-all', 'fa-mail-reply-all',
					'fa-star-half-empty', 'fa-star-half-full', 'fa-star-half-o', 'fa-location-arrow', 'fa-crop',
					'fa-code-fork', 'fa-unlink', 'fa-chain-broken', 'fa-question', 'fa-info', 'fa-exclamation',
					'fa-superscript', 'fa-subscript', 'fa-eraser', 'fa-puzzle-piece', 'fa-microphone',
					'fa-microphone-slash', 'fa-shield', 'fa-calendar-o', 'fa-fire-extinguisher', 'fa-rocket',
					'fa-maxcdn', 'fa-chevron-circle-left', 'fa-chevron-circle-right', 'fa-chevron-circle-up',
					'fa-chevron-circle-down', 'fa-html5', 'fa-css3', 'fa-anchor', 'fa-unlock-alt', 'fa-bullseye',
					'fa-ellipsis-h', 'fa-ellipsis-v', 'fa-rss-square', 'fa-play-circle', 'fa-ticket', 'fa-minus-square',
					'fa-minus-square-o', 'fa-level-up', 'fa-level-down', 'fa-check-square', 'fa-pencil-square',
					'fa-external-link-square', 'fa-share-square', 'fa-compass', 'fa-toggle-down',
					'fa-caret-square-o-down', 'fa-toggle-up', 'fa-caret-square-o-up', 'fa-toggle-right',
					'fa-caret-square-o-right', 'fa-euro', 'fa-eur', 'fa-gbp', 'fa-dollar', 'fa-usd', 'fa-rupee',
					'fa-inr', 'fa-cny', 'fa-rmb', 'fa-yen', 'fa-jpy', 'fa-ruble', 'fa-rouble', 'fa-rub', 'fa-won',
					'fa-krw', 'fa-bitcoin', 'fa-btc', 'fa-file', 'fa-file-text', 'fa-sort-alpha-asc',
					'fa-sort-alpha-desc', 'fa-sort-amount-asc', 'fa-sort-amount-desc', 'fa-sort-numeric-asc',
					'fa-sort-numeric-desc', 'fa-thumbs-up', 'fa-thumbs-down', 'fa-youtube-square', 'fa-youtube',
					'fa-xing', 'fa-xing-square', 'fa-youtube-play', 'fa-dropbox', 'fa-stack-overflow',
					'fa-instagram', 'fa-flickr', 'fa-adn', 'fa-bitbucket', 'fa-bitbucket-square', 'fa-tumblr',
					'fa-tumblr-square', 'fa-long-arrow-down', 'fa-long-arrow-up', 'fa-long-arrow-left',
					'fa-long-arrow-right', 'fa-apple', 'fa-windows', 'fa-android', 'fa-linux', 'fa-dribbble',
					'fa-skype', 'fa-foursquare', 'fa-trello', 'fa-female', 'fa-male', 'fa-gittip', 'fa-sun-o',
					'fa-moon-o', 'fa-archive', 'fa-bug', 'fa-vk', 'fa-weibo', 'fa-renren', 'fa-pagelines',
					'fa-stack-exchange', 'fa-arrow-circle-o-right', 'fa-arrow-circle-o-left', 'fa-toggle-left',
					'fa-caret-square-o-left', 'fa-dot-circle-o', 'fa-wheelchair', 'fa-vimeo-square', 'fa-turkish-lira',
					'fa-try', 'fa-plus-square-o', 'fa-area-chart', 'fa-at', 'fa-angellist', 'fa-bell-slash', 'fa-bell-slash-o',
					'fa-bicycle', 'fa-binoculars', 'fa-birthday-cake', 'fa-bus', 'fa-calculator', 'fa-cc', 'fa-cc-amex',
					'fa-cc-discover', 'fa-cc-mastercard', 'fa-cc-paypal', 'fa-cc-stripe', 'fa-cc-visa', 'fa-copyright',
					'fa-eyedropper', 'fa-futbol-o', 'fa-google-wallet', 'fa-ils', 'fa-ioxhost', 'fa-lastfm',
					'fa-lastfm-square', 'fa-line-chart', 'fa-meanpath', 'fa-newspaper-o', 'fa-paint-brush', 'fa-paypal',
					'fa-pie-chart', 'fa-plug', 'fa-shekel', 'fa-sheqel', 'fa-slideshare', 'fa-soccer-ball-o',
					'fa-toggle-off', 'fa-toggle-on', 'fa-trash', 'fa-tty', 'fa-twitch', 'fa-wifi', 'fa-yelp',
					'fa-bed', 'fa-buysellads', 'fa-cart-arrow-down', 'fa-cart-plus', 'fa-connectdevelop', 'fa-dashcube',
					'fa-diamond', 'fa-facebook-official', 'fa-forumbee', 'fa-heartbeat', 'fa-hotel', 'fa-leanpub',
					'fa-mars', 'fa-mars-double', 'fa-mars-stroke', 'fa-mars-stroke-h', 'fa-mars-stroke-v', 'fa-medium',
					'fa-mercury', 'fa-motorcycle', 'fa-neuter', 'fa-pinterest-p', 'fa-sellsy', 'fa-server', 'fa-ship',
					'fa-shirtsinbulk', 'fa-simplybuilt', 'fa-skyatlas', 'fa-street-view', 'fa-subway', 'fa-train',
					'fa-transgender', 'fa-transgender-alt', 'fa-user-plus', 'fa-user-secret', 'fa-user-times', 'fa-venus',
					'fa-venus-double', 'fa-venus-mars', 'fa-viacoin', 'fa-whatsapp',
				);
				
				$nonce = wp_create_nonce( $this->plugin_prefix . "-shortcode-builder-nonce" );
				
				// Localize admin script
				wp_localize_script( $this->plugin_prefix . '-admin-script', 'fourbzcore_object', 
					array(
						'theme_name'			=>	$theme_name,
						'close'					=>	__( 'close', $this->txt_domain ),
						'Delete'				=>	__( 'Delete', $this->txt_domain ),
						'name'					=>	__( 'Name', $this->txt_domain ),
						'enter_percentage'		=>	__( 'Enter percentage as a number 0-100', $this->txt_domain ),	
						'enter_slide'			=>	__( 'Enter the html for the slide', $this->txt_domain ),
						'choose_image'			=>	__( 'Choose Image', $this->txt_domain ),
						'fa_items'				=>	$fa,
						'admin_ajax'			=>	admin_url( 'admin-ajax.php?action=display_shortcode_builder&nonce=' . $nonce ),
					)
				);
			}
		}
		
		/**
		 *  2.3.2 Controller + Clear Cache + Save Options
		 *-----------------------------------------------------------------------*/
		
		/**
		 * Route url requests to the appropriate function.
		 *
		 * @since 1.0.0
		 */
		public function front_controller() {
			$action = isset( $_GET['action'] ) ?
				$_GET['action'] :
				( isset( $_POST['action'] ) ?
					$_POST['action'] :
					'' 
				);
			$screen = get_current_screen();
			
			if ( ( isset( $_GET['page'] ) && ( 'fourbzcore_options' === $_GET['page'] ) ) ||
				'post' === $screen->id   || 'product' === $screen->id || 'user-edit' === $screen->id ||
				'profile' === $screen->id || 'widgets' === $screen->id ) {
				
				/*
				 * If the action is fourbzcore_clear_cache, then clear the cache and show the plugin options page.
				 * If the action is fourbzcore_save_options, then save the plugin options and show the plugin options page.
				 * If the action is fourbzcore_options, then show the plugin options page.
				 */
				switch ( $action ) {
					case 'fourbzcore_clear_cache':
						$this->clear_cache();
						$this->plugin_options();
						break;
					case 'fourbzcore_save_options':
						$this->save_settings();
						$this->plugin_options();
						break;	
					case 'fourbzcore_options':
					default:			
						$this->plugin_options();						
				}
			}// End if
		}
				
		/**
		 * Clear the cache for all widgets
		 *
		 * @since 1.0.0
		 */
		public function clear_cache() {
			if ( ! isset( $_GET['nonce'] ) ) {
				return;
			}
			
			// Check nonce.	
			$nonce = $_GET['nonce'];

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, $this->plugin_prefix . "-cache-nonce" ) ) {
				return;
			}
			
			$keys = array( 'contact_form', 'contact_info', 'facebook_comments', 'featured_posts', 'popular_posts',
				'recent_posts', 'slideshow', 'flickr_photos', 'image_text', 'author_bio', 'column',
			);
			
			$key_count = count( $keys );
			
			for ( $i = 0; $i < $key_count; ++$i ) {
				delete_transient( 'fourbzcore_widget_' . $keys[$i] );
			}
			
			// Send message that the cache was cleared successfully.
			$messages = array();
			$messages[] = __( 'Cache cleared successfully!', $this->txt_domain );
			$_POST['messages'] = $messages;
		}
				
		/**
		 * Save plugin options to database and do security checks
		 *
		 * @since 1.0.0
		 */
		public function save_settings() {
			// Check nonce.	
			check_admin_referer( $this->plugin_prefix . '-save-options' );
			
			$excpts = new WP_Error();
			
			$options = array();
			
			if ( ! is_array( $_POST ) ) {
				// Error do not save.
				$excpts->add( '$_POST is not array', __( 'Error: The post variable is corrupted. Please contact the administrator.', $this->txt_domain ) );
			} else {					
				if ( isset( $_POST['fourbzcore']['caching'] ) ) {
					$options['caching'] = 'on';
				}
				if ( isset( $_POST['fourbzcore']['facebook_app_id'] ) ) {
					$options['facebook_app_id'] = $_POST['fourbzcore']['facebook_app_id'];
				}
				if ( isset( $_POST['fourbzcore']['google_app_id'] ) ) {
					$options['google_app_id'] = $_POST['fourbzcore']['google_app_id'];
				}
				update_option( $this->db_options_name, $options );
			}
			
			// Output any errors or confirmation messages.
			$this->output_admin_options_errors( $excpts );
		}
		
		
		/**
		 * Return any errors created while saving the plugin options
		 *
		 * If no errors, then display a message that all of the options were saved successfully.
		 * 
		 * @since 1.0.0
		 *
		 * @param object $excpts Wordpress error object Optional. Errors to display.
		 */
		public function output_admin_options_errors( $excpts ) {
			if ( isset( $excpts ) && $excpts->get_error_code() ) {
				$messages = array();
				
				foreach ( $excpts->get_error_codes() as $code ) {
					$severity = $excpts->get_error_data( $code );
					
					foreach ( $excpts->get_error_messages( $code ) as $error ) {
						$messages[] = $error;
					}
				}
				$_POST['messages'] = $messages; 
			} else {
				$messages[] = __( 'All options saved successfully!', $this->txt_domain );
				$_POST['messages'] = $messages;
			}
		}

		/**
		 * 2.3.3 Plugin Options Page
		 *-----------------------------------------------------------------------*/
		
		/**
		 * 2.3.3.1 Main
		 *-----------------------------------------------------------------------*/
		
		/**
		 * Add the plugin options page to the wordpress administrative menu
		 *
		 * @since 1.0.0
		 */
		public function add_options_page() {
			$options_page = add_menu_page( __( '4bzCore Options', $this->txt_domain ), 
				__( '4bzCore Options', $this->txt_domain ), 'edit_theme_options', $this->page_slug,
					array( $this, 'front_controller' ) );
			
			if ( ! $options_page ) {
				return;
			}
		}
		
		/**
		 * Display the plugin options page
		 *
		 * @since 1.0.0
		 */
		public function plugin_options() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.', $this->txt_domain ) );
			}
			
			$saved = get_option( $this->db_options_name );
			
			// Defaults
			$defaults = array(
				'caching'	=>	false,
			);
			
			if ( isset( $saved ) && is_array( $saved ) ) {
				$saved = array_map( 'stripslashes_deep', $saved );
				$saved = wp_parse_args( $saved, $defaults );
			} else {
				$saved = $defaults;
			}
			?>
			<br />
			<br />
			<div class="plugin-options fourbzcore-wrap text-format">
				<div id="plugin-options-header">
					<div class="options-logo"> 
						4bzCore
						<span class="version">
							<small><?php _e( 'Version: 1.0.0', $this->txt_domain ); ?></small>
						</span>
						<br />
						<br />
					</div>
					<strong><?php _e( 'For updates, tutorials, and more visit', $this->txt_domain ); ?>
						<a href="http://4bzthemes.com"><?php _e( 'Our Website', $this->txt_domain ); ?></a>
						<?php _e( 'and', $this->txt_domain ); ?>
						<a href="http://wordpress.org/plugins/4bzcore"><?php _e( 'Wordpress.org', $this->txt_domain ); ?></a>
					</strong>
				</div>
				<br />
				<br />
				<form method="post" action="<?php echo admin_url() . 'admin.php?page=' . $this->page_slug; ?>" enctype="multipart/form-data" id="fourbzcore_options_form">
				<?php 
				wp_nonce_field( $this->plugin_prefix . '-save-options' );
				
				if ( isset( $_POST['messages'] ) ) {
					$messages = $_POST['messages'];
					echo '<ul class="message">';
					
					if ( is_array( $messages ) ) {
						$messages_count = count( $messages );
						for ( $i = 0; $i < $messages_count; ++$i ) {
							echo '<li><span>' . esc_html( $messages[$i] ) . '</span></li>';
						}
					}
					
					echo '</ul>';					
				}
				?>
				<table class="form-table">
					<tbody>
						<tr>
							<th><?php _e( 'Facebook', $this->txt_domain ); ?></th>
							<td>
								<label><?php _e( 'Application Id', $this->txt_domain ); ?></label>
								<input type="text" name="fourbzcore[facebook_app_id]"<?php
									if ( isset( $saved['facebook_app_id'] ) ) {
										echo ' value="' . esc_attr( $saved['facebook_app_id'] ) . '"';
									}?> /> 
							</td>
						</tr>
						<tr>
							<th><?php _e( 'Google', $this->txt_domain ); ?></th>
							<td>
								<div>
									<label><?php  _e( 'Application Id', $this->txt_domain ); ?>
									</label>
									<input type="text" name="fourbzcore[google_app_id]"<?php 
										if ( isset( $saved['google_app_id'] ) ) {
											echo ' value="' . esc_attr( $saved['google_app_id'] ) . '"'; 
										}?> />
								</div>
							</td>
						</tr>						 
						<tr>
							<th><?php _e( 'Enable Caching', $this->txt_domain ); ?></th>
							<td>
								<input type="checkbox" class="fourbzcore-displaymaster" name="fourbzcore[caching]"<?php checked( $saved['caching'], 'on' ); ?> />
								<div<?php if ( ! $saved['caching'] ) { echo ' style="display:none;"'; } ?> class="hideshow">
									<br />
									<p><?php _e( 'Clear the cache used to store the widgets output.', $this->txt_domain ); ?></p>
									<br />
									<a href="?page=<?php
									$cache_nonce = wp_create_nonce( $this->plugin_prefix . "-cache-nonce" );
									echo $this->page_slug . '&amp;action=' . $this->plugin_prefix . '_clear_cache&amp;nonce=' .
									$cache_nonce; ?>" class="button">
										<?php echo __( 'Clear Cache', $this->txt_domain ); ?>
									</a>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="submit">
					<input class="button-3" name="Submit" value="<?php _e( 'Save Options', $this->txt_domain );?>" type="submit" /> 
				</div>
				<input name="action" value="<?php echo $this->plugin_prefix; ?>_save_options" type="hidden" />
			</form>
			</div>
			<?php
		}
		 
		/**
		 * Construct name of input field for use on shortcode and widget options pages.
		 *
		 * A wrapper used to name a field regularly or, if displaying options for a widget, use the widgets' get_field_name method.
		 *
		 * @since 1.0.0
		 *
		 * @param string $name Required. Name of input field.
		 * @param string $name_attr_pre Required. Name prefix of input field.
		 * @param object $widget Optional. Widget object to display options for.
		 * @return string. Modified name of input field.
		 */
		public function get_field_name_wrap( $name, $name_attr_pre, & $widget = false ) {
			
			if ( empty( $name ) || empty( $name_attr_pre ) ) {
				return;
			} else {
				$frag = "[$name]";
			}
			
			return ( $widget ? $widget->get_field_name( $name ) : $name_attr_pre . $frag );
		}

		/**
		 * Construct id for use on shortcode and widget options pages.
		 *
		 * A wrapper used to create an id. if displaying options for a widget, uses the widgets' get_field_id method.
		 *
		 * @since 1.0.0
		 *
		 * @param string $id Required. Id of input field.
		 * @param string $name_attr_pre Optional. Prefix for id attribute.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 * @return string. Modified name of input field.
		 */
		public function get_field_id_wrap( $id, $name_attr_pre = '', & $widget = false ) {
			return ( $widget ? $widget->get_field_id( $id ) : $name_attr_pre . '-' . $id );
		}
		
		
		/**
		 * Construct the html to upload an image
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Saved options, if any.
		 * @param string $name Required. Name of the input field.
		 */
		function construct_upload_image( $opts, $name ) {
			if ( ! isset( $name ) ) {
				return;
			}
			
			?>
			<input class="icon icon-image" id="fourbzcore-<?php echo $name; ?>" type="text" name="fourbzcore[<?php echo $name; ?>]"<?php if ( isset( $opts[$name] ) ) { echo ' value="' . esc_attr( $opts[$name] ) . '"'; } ?> /> 
			<input class="icon fourbzcore-icon-image-btn button" data-fourbzcore-textbox="fourbzcore-<?php echo $name; ?>" type="button" value="<?php _e( 'Upload image', $this->txt_domain ); ?>" />
			<label><?php _e( 'Width', $this->txt_domain ); ?></label>
			<input type="text" id="fourbzcore-<?php echo $name; ?>-width" name="fourbzcore[<?php echo $name; ?>_width]"<?php if ( isset( $opts[$name . "_width"] ) ) {
				echo ' value="' . esc_attr( $opts[$name . '_width'] ) . '"'; } ?> />
			<label><?php _e( 'Height', $this->txt_domain ); ?></label>
			<input type="text" id="fourbzcore-<?php echo $name; ?>-height" name="fourbzcore[<?php echo $name; ?>_height]"<?php 
			if ( isset( $opts[$name . "_height"] ) ) {
				echo ' value="' . esc_attr( $opts[$name . '_height'] ) . '"'; } ?> />
			<label><?php _e( 'Alt Text', $this->txt_domain ); ?></label>
			<input type="text" id="fourbzcore-<?php echo $name; ?>-alt" name="fourbzcore[<?php echo $name; ?>_alt]"<?php
			if ( isset( $opts[$name . "_alt"] ) ) {
				echo ' value="' . esc_attr( $opts[$name . '_alt'] ) . '"'; } ?> />
			<?php
		}
		
		/**
		 * 2.3.3.2 General Component Options
		 *-----------------------------------------------------------------------*/
		
		/**
		 * Display basic options that are common to shortcodes and widgets.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Required. Saved options.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_basic_options( $opts, & $widget = false ) {
			if ( ! isset( $opts['name'] ) ) {
				return;
			} else {
				$name = esc_html( $opts['name'] );
			}
			
			$html_str = "<div class='fourbzcore-wrap text-format'><label>" . __( 'Title', $this->txt_domain ) . "</label> <input type='text'";
			
			if ( isset( $opts['title_text'] ) ) {
				$html_str .= " value='" . esc_attr( $opts['title_text'] ) . "'";
			}
			
			$html_str .= " name='" . $this->get_field_name_wrap( 'title_text', "fourbzcore[$name]", $widget ) . "' />";
			
			// These options are not relevant to these shortcodes and widgets.
			if ( ! in_array( $opts['name'], array( 'slideshow', 'contact_form', 'contact_info', 'progressbars', 'image_text', 'author_bio', 'column' ) ) ) {
				$html_str .= "<label>" . __( 'Number of items to display', $this->txt_domain ) . "</label> <input type='text'";
			
				if ( isset( $opts['limit'] ) ) {
					$html_str .= " value='" . esc_attr( $opts['limit'] ) . "'";
				}
				
				$html_str .= " name='" . $this->get_field_name_wrap( 'limit', "fourbzcore[$name]", $widget ) . "' />";
			}
			
			$html_str .= "</div>";
			
			return $html_str;
		}
		
		/**
		 * 2.3.3.3 Posts Options
		 *-----------------------------------------------------------------------*/
		 
		/**
		 * Construct and display options for posts. Used by the featured, recent, related, and popular posts shortcodes and widgets.
		 *
		 * Apply the fourbzcore_options_posts filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param string $component Required. Name of shortcode or widget whose options are being displayed.
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_posts( $component, $opts = array(), & $widget = false ) {
			if ( ! isset( $component ) ) {
				return;
			}
			
			$opts['name'] = $component;
			
			$opts_str =  '<table class="form-table"><tbody>
						<tr>
							<th>' . __( 'General', $this->txt_domain ) . '</th>
							<td>';
			$opts_str .= $this->display_basic_options( $opts, $widget ) . '</td>
						</tr>
					</table>';
			
			return apply_filters( '4bzcore_options_posts', $opts_str, $component, $opts, $widget );
		}
	
		/**
		 * Construct and display options for the featured posts shortcode and widget.
		 *
		 * Apply the fourbzcore_options_featured_posts filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_featured_posts( $opts = array(), & $widget = false ) {
			$opts_str = '<div class="fourbzcore-wrap text-format">' . $this->display_options_posts( 'featured_posts', $opts, $widget ) . '</div>';
			
			echo apply_filters( '4bzcore_options_featured_posts', $opts_str, $opts, $widget );
		}
		
		/**
		 * Construct and display options for related posts shortcode and widget.
		 *
		 * Apply the fourbzcore_options_related_posts filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_related_posts( $opts = array(), & $widget = false ) {
			$opts_str = '<div class="fourbzcore-wrap text-format">' . $this->display_options_posts( 'related_posts', $opts, $widget ) . '</div>';
			
			echo apply_filters( '4bzcore_options_related_posts', $opts_str, $opts, $widget );
		}
		
		/**
		 * Construct and display options for recent posts shortcode and widget.
		 * 
		 * Apply the fourbzcore_options_recent_posts filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_recent_posts( $opts = array(), & $widget = false ) {
			$opts_str = '<div class="fourbzcore-wrap text-format">' . $this->display_options_posts( 'recent_posts', $opts, $widget ) . '</div>';
			
			echo apply_filters( '4bzcore_options_recent_posts', $opts_str, $opts, $widget );
		}
		
		/**
		 * Construct and display options for popular posts shortcode and widget.
		 * 
		 * Apply the fourbzcore_options_popular_posts filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_popular_posts( $opts = array(), & $widget = false ) {
			$opts_str = '<div class="fourbzcore-wrap text-format">' . $this->display_options_posts( 'popular_posts', $opts, $widget ) . '</div>';
			
			echo apply_filters( '4bzcore_options_popular_posts', $opts_str, $opts, $widget );
		}
		
		/**
		 * 2.3.3.4 Shortcodes + Widgets Options: Not Post Based
		 *-----------------------------------------------------------------------*/
		
		/**
		 * Construct and display options for facebook comments shortcode and widget.
		 * 
		 * Apply the fourbzcore_options_facebook_comments filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_facebook_comments( $opts = array(), & $widget = false ) {
			$opts['name'] = 'facebook_comments';
			$opts_str = "<div class='fourbzcore-wrap text-format'>";
			
			$opts_str .= '<table class="form-table"><tbody>
						<tr>
							<th>' . __( 'General', $this->txt_domain ) . '</th>
							<td>' . $this->display_basic_options( $opts, $widget ) . '</td></tr></table></div>';
			
			echo apply_filters( '4bzcore_options_facebook_comments', $opts_str, $opts, $widget );
		}
		
		/**
		 * Construct and display options for the flickr photos shortcode and widget.
		 * 
		 * Apply the fourbzcore_options_flickr_photos filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_flickr_photos( $opts = array(), & $widget = false ) {	
			$opts['name'] = 'flickr_photos';
			$opts_str = '';
			
			$opts_str .=  "<div class='fourbzcore-wrap text-format'>";
			
			$opts_str .= '<table class="form-table"><tbody>
						<tr>
							<th>' . __( 'General', $this->txt_domain ) . '</th>
							<td>' . $this->display_basic_options( $opts, $widget ) . '</td>
						</tr>
					</table>';
			
			$opts_str .= "<table class='form-table'>
					<tbody>
						<tr>
							<th>" . __( 'User id', $this->txt_domain ) . '</th>
							<td><label>' . __( 'User id', $this->txt_domain ) . '</label>
										<input type="text"';
								
								if ( isset( $opts['user_id'] ) ) {
									$opts_str .= ' value="' . esc_attr( $opts['user_id'] ) . '"';
								} 
								
							$opts_str .= ' name="' . $this->get_field_name_wrap( 'user_id', "fourbzcore[flickr_photos]", $widget ) . '" /></td>
								</tr></tbody></table></div>';
			
			echo apply_filters( '4bzcore_options_flickr_photos', $opts_str, $opts, $widget );
		}
		
		/**
		 * Construct and display options for actual contact information.
		 *
		 * Apply the fourbzcore_options_contact_opts filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Saved options.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_contact_opts( $opts = array(), & $widget = false ) {
			$name = isset( $opts['name'] ) ? esc_html( $opts['name'] ) : '';
			
			if ( isset ( $opts['name_attr_pre'] ) ) {
				$name_attr_pre = $opts['name_attr_pre'];
			} else {
				$name_attr_pre = "fourbzcore";
			}
			if ( $name ) {
				$field_name = $name_attr_pre . "[$name]";
			} else {
				$field_name = $name_attr_pre;
			}
			
			?>
			<div>
				<p class="instruction"><small><?php
					echo __( "If a user id is entered, then that user's contact information will be used, and you do not have to enter it below. If you want to display icons with the contact information, you have to enter them below.", $this->txt_domain ); ?>
				</small></p><br />
				<label>
				<?php _e( 'User Id', $this->txt_domain ); ?>
				</label>
				<input type="text" name="<?php
					echo $this->get_field_name_wrap( 'user_id', $field_name, $widget ); ?>"<?php if ( isset( $opts['user_id'] ) ) {
				echo ' value="' . esc_attr( $opts['user_id'] ) . '"'; } ?> /> <label>
				<?php _e( 'Primary Address', $this->txt_domain ); ?>
				</label>
				<input type="text" name="<?php echo $this->get_field_name_wrap( 'contact_address', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_address'] ) ) {
				echo ' value="' . esc_attr( $opts['contact_address'] ) . '"'; } ?> /> 
				<label>
				<?php  _e( 'Choose icon', $this->txt_domain ); ?></label>
				<input type="text"<?php if ( isset( $opts['address_icon'] ) ) {
				echo ' value="' . esc_attr( $opts['address_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'address_icon', $field_name, $widget ); ?>" />
			</div>
			<div>
				<label>
				<?php _e( 'Enter Phone Number', $this->txt_domain ); ?></label>
				<input type="text" name="<?php
					echo $this->get_field_name_wrap( 'contact_phone', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_phone'] ) ) {
				echo ' value="' . esc_attr( $opts['contact_phone'] ) . '"'; } ?> /> 
				<label><?php _e( 'Choose icon to be shown with phone number', $this->txt_domain ); ?></label>
				<input type="text"<?php if ( isset( $opts['phone_icon'] ) ) {
				echo ' value="' . esc_attr( $opts['phone_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'phone_icon', $field_name, $widget ); ?>" />
			</div>
			<div>
				<label>
				<?php _e( 'Enter website url', $this->txt_domain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name_wrap( 'contact_url', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_url'] ) ) {
					echo ' value="' . esc_attr( $opts['contact_url'] ) . '"'; } ?> /> 
				<label>
				<?php  _e( 'Choose icon to be shown with website', $this->txt_domain ); ?>
				</label>
				<input type="text"<?php if ( isset( $opts['url_icon'] ) ) {
					echo ' value="' . esc_attr( $opts['url_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'url_icon', $field_name, $widget ); ?>" />
			</div>
			<div>	
				<label><?php _e( 'Enter email address', $this->txt_domain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name_wrap( 'contact_email', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_email'] ) ) {
					echo ' value="' . esc_attr( $opts['contact_email'] ) . '"'; } ?> /> 
				<label>
				<?php _e( 'Choose icon to be shown with email', $this->txt_domain ); ?>
				</label>
				<input type="text"<?php if ( isset( $opts['email_icon'] ) ) { 
				echo ' value="' . esc_attr( $opts['email_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'email_icon', $field_name, $widget ); ?>" />
			</div>
			<div>
				<label><?php _e( 'Enter facebook handle', $this->txt_domain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name_wrap( 'contact_facebook', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_facebook'] ) ) {
					echo ' value="' . esc_attr( $opts['contact_facebook'] ) . '"'; } ?> /> 
				<label><?php  _e( 'Choose icon to be shown with the facebook handle', $this->txt_domain ); ?></label>
				<input type="text"<?php if ( isset( $opts['facebook_icon'] ) ) {
				echo ' value="' . esc_attr( $opts['facebook_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'facebook_icon', $field_name, $widget ); ?>" />
			</div>
			<div>
				<label><?php _e( 'Enter twitter handle', $this->txt_domain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name_wrap( 'contact_twitter', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_twitter'] ) ) {
					echo ' value="' . esc_attr( $opts['contact_twitter'] ) . '"'; } ?> /> 
				<label><?php  _e( 'Choose icon to be shown with the twitter handle', $this->txt_domain ); ?></label><input type="text"
				<?php if ( isset( $opts['twitter_icon'] ) ) {
				echo ' value="' . esc_attr( $opts['twitter_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'twitter_icon', $field_name, $widget ); ?>" />
			</div>
			<div>
				<label><?php _e( 'Enter google+ handle', $this->txt_domain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name_wrap( 'contact_google', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_google'] ) ) {
					echo ' value="' . esc_attr( $opts['contact_google'] ) . '"'; } ?> /> 
				<label><?php  _e( 'Choose icon to be shown with the google handle', $this->txt_domain ); ?></label><input type="text"
				<?php if ( isset( $opts['google_icon'] ) ) {
				echo ' value="' . esc_attr( $opts['google_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'google_icon', $field_name, $widget ); ?>" />
			</div>
			<div>
				<label><?php _e( 'Enter linkedin handle', $this->txt_domain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name_wrap( 'contact_linkedin', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_linkedin'] ) ) { echo ' value="' . esc_attr( $opts['contact_linkedin'] ) . '"'; } ?> /> 
				<label><?php  _e( 'Choose icon to be shown with the linkedin handle', $this->txt_domain ); ?></label><input type="text"
				<?php if ( isset( $opts['linkedin_icon'] ) ) {
				echo ' value="' . esc_attr( $opts['linkedin_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'linkedin_icon', $field_name, $widget ); ?>" />
			</div>
			<div>
				<label><?php _e( 'Enter instagram handle', $this->txt_domain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name_wrap( 'contact_instagram', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_instagram'] ) ) {
				echo ' value="' . esc_attr( $opts['contact_instagram'] ) . '"'; } ?> /> 
				<label><?php  _e( 'Choose icon to be shown with the instagram handle', $this->txt_domain ); ?></label><input type="text"
				<?php if ( isset( $opts['instagram_icon'] ) ) {
				echo ' value="' . esc_attr( $opts['instagram_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'instagram_icon', $field_name, $widget ); ?>" />
			</div>
			<div>
				<label><?php _e( 'Enter tumblr handle', $this->txt_domain ); ?></label>
				<input type="text" name="<?php echo $this->get_field_name_wrap( 'contact_tumblr', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_tumblr'] ) ) {
				echo ' value="' . esc_attr( $opts['contact_tumblr'] ) . '"'; } ?> /> 
				<label><?php  _e( 'Choose icon to be shown with the tumblr handle', $this->txt_domain ); ?></label><input type="text"
				<?php if ( isset( $opts['tumblr_icon'] ) ) {
				echo ' value="' . esc_attr( $opts['tumblr_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'tumblr_icon', $field_name, $widget ); ?>" />
			</div>
			<div>
				<label><?php _e( 'Enter pinterest handle', $this->txt_domain ); ?></label>
				<input type="text" name="<?php
					echo $this->get_field_name_wrap( 'contact_pinterest', $field_name, $widget ); ?>"
				<?php if ( isset( $opts['contact_pinterest'] ) ) {
				echo ' value="' . esc_attr( $opts['contact_pinterest'] ) . '"'; } ?> /> 
				<label><?php  _e( 'Choose icon to be shown with the pinterest handle', $this->txt_domain ); ?></label><input type="text"
				<?php if ( isset( $opts['pinterest_icon'] ) ) {
				echo ' value="' . esc_attr( $opts['pinterest_icon'] ) . '"'; } ?> class="<?php echo $name_attr_pre;?>-icon-field" name="<?php
					echo $this->get_field_name_wrap( 'pinterest_icon', $field_name, $widget ); ?>" />
			</div>
			<?php
		}
					
		/**
		 * Construct and display options for contact information for an organization or a user
		 *
		 * Apply the fourbzcore_options_contact_info filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Saved options.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_contact_info( $opts = array(), & $widget = false ) {
			ob_start();
			?>
			
			<div class="fourbzcore-wrap text-format">
			<?php
				$opts['name'] = 'contact_info';
				
				echo '<div class="tabs-panel">
					<table class="form-table">
					<tbody>';
				echo '<tr><th>' . __( 'General', $this->txt_domain ) . '</th><td>' . $this->display_basic_options( $opts, $widget );
				echo '</td>
					</tr>';
				echo '<tr><th>' . __( 'Contact Information', $this->txt_domain ) .
						'</th>
						<td>';			
						$this->display_contact_opts( $opts, $widget );
				echo '	</td>
						</tr>
					</tbody>
				</table>';
				
				echo '</div>';
			echo '</div>';
			
			echo apply_filters( '4bzcore_options_contact_info', ob_get_clean(), $opts, $widget );
		}
		
		/**
		 * Construct and display options for a contact form.
		 *
		 * Apply the fourbzcore_options_contact_form filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Saved options.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_contact_form( $opts = array(), & $widget = false ) {
			$opts_str = '<div class="fourbzcore-wrap text-format">';
		
			$opts['name'] = 'contact_form';
			
			$opts_str .= "<table class='form-table'>
				<tbody>
					<tr><th>" . __( 'General', $this->txt_domain ) . '</th><td>' . $this->display_basic_options( $opts, $widget );
			$opts_str .= "</td>
					</tr></td>
				</tbody></table>";
				
			ob_start();
			$this->display_contact_form_specific_opts( $opts, $widget );
			$opts_str .= ob_get_clean() . '</div>';
			
			echo apply_filters( '4bzcore_options_contact_form', $opts_str, $opts, $widget );
		}
		
		/**
		 * Construct and display options specific to the contact form shortcode and widget.
		 *
		 * Requires any plugin that displays a contact form via a shortcode.
		 *
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Saved options.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_contact_form_specific_opts( $opts = array(), & $widget = false ) {
			$defaults = array(
				'contact_form_layout'	=>	'1',
				'show_contact'			=>	false,
				'show_map'				=>	false,
			);
							
			if ( $opts ) {
				$opts = wp_parse_args( $opts, $defaults );
			} else {
				$opts = $defaults;
			}
		
			echo "<p class='section-title'>" . __( 'Contact Form Specific Options', $this->txt_domain ) . "</p>
				<table class='form-table'>
					<tbody>
						<tr><th>" . __( 'Contact Form', $this->txt_domain ) . '</th><td>
													<label>' . __( 'Enter the shortcode here to display a contact form.', $this->txt_domain ) . '</label>
													<textarea name="' . $this->get_field_name_wrap( 'contact_form', "fourbzcore[contact_form]", $widget ) . '" cols="30"  rows="10">'; 
												if ( isset( $opts['contact_form'] ) ) {
													echo esc_attr( $opts['contact_form'] );
												}
												echo "</textarea></td></tr> 
						<tr>
							<th>" . __( 'Contact Information', $this->txt_domain ) . "</th>
							<td>
								<input class='fourbzcore-displaymaster' type='checkbox' name='" .
									$this->get_field_name_wrap( 'show_contact', "fourbzcore[contact_form]", $widget ) . "'" . checked( $opts['show_contact'], 'on', false ) . " /> 
								<label>";
									_e( 'Show Contact Information - depending on the screen size, it will be to the left of the contact form.', $this->txt_domain );
								echo "</label><br /><br />";
								
								echo "<div ";
								if ( ! $opts['show_contact'] ) { echo ' style="display:none;" '; }
								echo "class='hideshow'>";
									
								$this->display_contact_opts( $opts, $widget );
							echo "</div></td>
						</tr>
						<tr>
							<th>" . __( 'Google Map', $this->txt_domain ) . "</th>
							<td>
								<input type='checkbox' name='" . $this->get_field_name_wrap( 'show_map',
									"fourbzcore[contact_form]", $widget ) . "'" . checked( $opts['show_map'], 'on', false ) . " /> 
								<label>";
									_e( 'Show Google Map - it will be above the contact form. Enter the app id on the plugin options page.', $this->txt_domain );
								echo "</label>							
							</td>
						</tr>
					</tbody>
				</table>";
		}
		
		/**
		 * Construct and display options for a flexslider slideshow
		 *
		 * Apply the fourbzcore_options_slideshow filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Saved options.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_slideshow( $opts = array(), & $widget = false ) {
			$opts['name'] = 'slideshow';
			
			$opts_str = '<div class="fourbzcore-wrap text-format">';
			
			$opts_str .= '<table class="form-table"><tbody>
					<tr>
						<th>' . __( 'General', $this->txt_domain ) . '</th>
						<td>' . $this->display_basic_options( $opts, $widget ) . '</td>
					</tr>
				</table>';
			
			ob_start();
			$this->display_slideshow_specific_opts( $opts, $widget );
			$opts_str .= ob_get_clean();
			
			$opts_str .= '</div>';
			
			echo apply_filters( '4bzcore_options_slideshow', $opts_str, $opts, $widget );
		}

		/**
		 * Construct and display options specific to the flexslider slideshow shortcode and widget.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Saved options.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_slideshow_specific_opts( $opts = array(), & $widget = false ) {
			$defaults = array(
				'slideshow_layout'	=>	'2',
				'animation'			=>	'2',
				'useCSS'			=>	'0',
				'template'			=>	'1',
			);
							
			if ( $opts ) {
				$opts = wp_parse_args( $opts, $defaults );
			} else {
				$opts = $defaults;
			}
			
			$slides = isset( $opts['slides'] ) ?  $opts['slides'] : '';
			$slides_name = $this->get_field_name_wrap( 'slides', "fourbzcore[slideshow]", $widget );
			
			echo "<p class='section-title'>" . __( 'Slideshow Specific Options', $this->txt_domain ) . "</p>
				<table class='form-table'>
					<tbody>";
					
					echo "<tr>
							<th>" . __( 'Slides', $this->txt_domain ) . "</th>
							<td>
								<br /><p class='instruction'><small>" . __( 'Sort the slides by hovering over a slide until a hand appears, drag and drop where desired.', $this->txt_domain ) . "</small></p><br /><div id='" . $this->get_field_id_wrap( 'slides', "fourbzcore-slideshow", $widget ) . "'>
									<input type='button' class='button-2 fourbzcore-add-slide' value='Add Slide' data-name='" . $slides_name . "' />
									<ul class='slides-wrap sortable'>";
									
									if ( is_array( $slides ) && 0 < count( $slides ) ) {
										$i = 0;
										
										foreach ( $slides as $slide ) {
											echo "<li class='slides-cont'>
													<button type='button' class='button-2 delete-row'>" .
														__( 'Delete', $this->txt_domain ) .
														"</button><br /><br />";
											
											echo "<label>" . 
														__( 'Enter the html for the slide', $this->txt_domain ) .
														"</label><div class='loader'></div><textarea class='slide-html' name='" . $slides_name . "[]' rows='20' cols='30'>" . esc_html( $slide ) . "</textarea><div class='loader'></div>
												  </li>";
											++$i;
										}
									}
									
								echo "</ul></div>
							</td>
						</tr>
						</tbody></table>";
		}					
		
		/**
		 * Construct and display options for the progressbars shortcode and widget.
		 *
		 * Apply the fourbzcore_options_progressbars filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Saved options.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_progressbars( $opts = array(), & $widget = false ) {
			$progressbars_name = $this->get_field_name_wrap( 'items', "fourbzcore[progressbars]", $widget );
			$skills_html = '';
			$skills = isset( $opts['items'] ) ? $opts['items'] : array();
			$opts['name'] = 'progressbars';
			
			$defaults = array(
				'cols'	=>	'1',
			);
							
			if ( $opts ) {
				$opts = wp_parse_args( $opts, $defaults );
			} else {
				$opts = $defaults;
			}
			
			ob_start();
			
			echo '<div id="progressbars-cont-opts" class="fourbzcore-wrap text-format">';
			
			echo '<table class="form-table"><tbody>
					<tr>
						<th>' . __( 'General', $this->txt_domain ) . '</th>
						<td>' . $this->display_basic_options( $opts, $widget );
			echo		'</td>
					</tr>
				</table>';
			
			// Construct options for the actual progressbars items. 
			echo '<table class="form-table"><tbody>
					<tr>
						<th>' . __( 'Items', $this->txt_domain ) . "</th>
						<td>
							<br /><p class='instruction'><small>" . __( 'Sort the progressbars by hovering over an item until a hand appears, drag and drop where desired.', $this->txt_domain ) . '</small></p><br /><input data-index="0" type="button" class="button-2 add-skill" value="' . __( 'Add Item', $this->txt_domain ) . '" data-name="' . $progressbars_name . '" />
					<ul class="skills-wrap sortable">';
				  
				if ( is_array( $skills ) && 0 < count( $skills ) ) {
					$i = 0;
							
					foreach ( $skills as $key => $val ) {
						echo '<li class="skills-cont" data-skill-index="' . $i . '"><br /><button type="button" class="button-2 delete-row">' .
						__( 'Delete', $this->txt_domain ) . '</button><br /><br />' .
						'<div><label>' .
						__( 'Name', $this->txt_domain ) . '</label><input type="text" class="add-skill-name" data-skill-index="' .
						$i . '" name="' . $progressbars_name . '[' . $i . '][name]" value="' . esc_attr( $key ) . '" style="padding:5px;" /></div>' .
						'<br /><label>' . __( 'Percentage', $this->txt_domain ) . '</label><input type="text" class="add-skill-value"' . ' name="' . $progressbars_name . '[' . $i . '][value]" value="' . esc_attr( $val ) . '"/>' .
						'<span class="instruction"><small>' . __( 'Enter percentage as a number 0-100', $this->txt_domain ) . '</small></span></li>';
						
						++$i;									
					}
				}
				echo "</ul></td></tr></tbody></table>";
			
			echo '</div>';
			
			echo apply_filters( '4bzcore_options_progressbars', ob_get_clean(), $opts, $widget );
		}
		
		/**
		 * Construct and display options for the image text shortcode and widget.
		 * 
		 * Apply the fourbzcore_options_image_text filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_image_text( $opts = array(), & $widget = false ) {	
			$opts['name'] = 'image_text';
			
			$opts_str = '';
			
			$image_url = isset( $opts['image_url'] ) ? esc_attr( $opts['image_url'] ) : '';
			$image_width = isset( $opts['image_url_width'] ) ? esc_attr( $opts['image_url_width'] ) : '';
			$image_height = isset( $opts['image_url_height'] ) ? esc_attr( $opts['image_url_height'] ) : '';
			$image_alt = isset( $opts['image_url_alt'] ) ? esc_attr( $opts['image_url_alt'] ) : '';
			$content = isset( $opts['content'] ) ? esc_textarea( $opts['content'] ) : '';
			
			$opts_str .= '<div class="fourbzcore-wrap text-format"><table class="form-table"><tbody>
						<tr>
							<th>' . __( 'General', $this->txt_domain ) . '</th>
							<td>' . $this->display_basic_options( $opts, $widget );
			$opts_str .= '</td></tr>';
			
			$opts_str .= '<tr>
					<th>' . __( 'Image Text Specific Options', $this->txt_domain ) . '</th><td><p>
					<label>' . __( 'Image', $this->txt_domain ) . '</label>
					<input class="icon icon-image" id="' . $this->get_field_id_wrap( 'image-url', 'fourbzcore-image-text', $widget ) . '" type="text" name="' . esc_attr( $this->get_field_name_wrap( 'image_url', 'fourbzcore[image_text]', $widget ) ) . '" value="' . $image_url . '" /> 
					<input class="icon fourbzcore-icon-image-btn button" data-fourbzcore-textbox="' . $this->get_field_id_wrap( 'image-url', 'fourbzcore-image-text', $widget ) . '" type="button" value="' . __( 'Upload Image', $this->txt_domain ) . '" />
					<input class="icon icon-image" id="' . $this->get_field_id_wrap( 'image-url', 'fourbzcore-image-text', $widget ) . '-width" type="hidden" name="' . esc_attr( $this->get_field_name_wrap( 'image_url_width', 'fourbzcore[image_text]', $widget ) ) . '" value="' . $image_width . '" />
					<input class="icon icon-image" id="' . $this->get_field_id_wrap( 'image-url', 'fourbzcore-image-text', $widget ) . '-height" type="hidden" name="' . esc_attr( $this->get_field_name_wrap( 'image_url_height', 'fourbzcore[image_text]', $widget ) ) . '" value="' . $image_height . '" />
					<input class="icon icon-image" id="' . $this->get_field_id_wrap( 'image-url', 'fourbzcore-image-text', $widget ) . '-alt" type="hidden" name="' . esc_attr( $this->get_field_name_wrap( 'image_url_alt', 'fourbzcore[image_text]', $widget ) ) . '" value="' . $image_alt . '" /> 
							
				</p>
								
				<p>
					<label>' . __( 'Description', $this->txt_domain ) . '</label>
					<textarea id="' . esc_attr( $this->get_field_id_wrap( 'content', 'fourbzcore-image-text', $widget ) ) . '" name="' . esc_attr( $this->get_field_name_wrap( 'content', 'fourbzcore[image_text]', $widget) ) . '" rows="10" cols="20">' . $content . '</textarea>
				</p></td></tr></tbody></table></div>';
				
				
			echo apply_filters( '4bzcore_options_image_text', $opts_str, $opts, $widget );
		}
		
		/**
		 * Construct and display options for the author bio shortcode and widget.
		 * 
		 * Apply the fourbzcore_options_author_bio filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_author_bio( $opts = array(), & $widget = false ) {	
			$opts['name'] = 'author_bio';
			
			$opts_str = "<div class='fourbzcore-wrap text-format'>";
			
			$opts_str .= '<table class="form-table"><tbody>
					<tr>
						<th>' . __( 'General', $this->txt_domain ) . '</th>
						<td>' . $this->display_basic_options( $opts, $widget );
			$opts_str .= '</td></tr></tbody></table></div>';
			
			echo apply_filters( '4bzcore_options_author_bio', $opts_str, $opts, $widget );
		}
		
		/**
		 * Construct and display options for the column shortcode.
		 * 
		 * Apply the fourbzcore_options_column filters on the options.
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. Only relative if displaying a widget's options because these shortcode options are not saved to the database, they are used in the shortcode builder to generate the shortcode string.
		 * @param object $widget Optional. Widget object if displaying options for a widget.
		 */
		public function display_options_column( $opts = array(), & $widget = false ) {	
			$opts['name'] = 'column';
			$opts_str = '';
			
			$opts_str .= "<div class='fourbzcore-wrap text-format'>";
			
			$opts_str .= '<table class="form-table"><tbody>
				<tr>
					<th>' . __( 'General', $this->txt_domain ) . '</th>
					<td>' . $this->display_basic_options( $opts, $widget );
				$opts_str .= '</td></tr>
				<tr>
					<th>' . __( 'Column Width', $this->txt_domain ) . '</th>
					<td><label>' . __( 'Enter the column width with unit of measurement, eg 300px, 25%, etc.', $this->txt_domain ) . '</label>
					<input type="text" name="' . $this->get_field_name_wrap( 'column_width', "fourbzcore[column]", $widget ) . '" />
					</td>
				</tr>
				<tr>
					<th>' . __( 'Column Padding', $this->txt_domain ) . '</th>
					<td><label>' . __( 'Enter the column padding with unit of measurement, eg 12px, 1px 2px 3px 4px, etc.. Leave blank for no padding.', $this->txt_domain ) . '</label>
					<input type="text" name="' . $this->get_field_name_wrap( 'column_padding', "fourbzcore[column]", $widget ) . '" />
					</td>
				</tr>
				<tr>
					<th>' . __( 'Column Margin', $this->txt_domain ) . '</th>
					<td><label>' . __( 'Enter the column margin with unit of measurement, eg 12px, 1px 2px 3px 4px, etc.. Leave blank for no margin.', $this->txt_domain ) . '</label>
						<input type="text" name="' . $this->get_field_name_wrap( 'column_margin', "fourbzcore[column]", $widget ) . '" />
					</td>
				</tr>
				</tbody>
			</table></div>';
			
			echo apply_filters( '4bzcore_options_column', $opts_str, $opts, $widget );
		}
		
		/**
		 *  2.3.4 Tinymce + Shortcode Builder
		 *-----------------------------------------------------------------------*/
		 
		/**
		 * Add filters to add a custom button to the tinymce menu.
		 *
		 * @since 1.0.0
		 */
		public function shortcode_builder_tinymce_buttons() {
			// Load the tinymce plugin
			add_filter( 'mce_external_plugins', array( $this, 'shortcode_builder_register_tinymce_js' ) );
			
			// Add new buttons
			add_filter( 'mce_buttons', array( $this, 'shortcode_builder_register_buttons' ) );
		}
		
		/**
		 * Function that actually adds the plugin's custom button to array of buttons on the tinymce menu.
		 *
		 * @since 1.0.0
		 *
		 * @param array $buttons Required. Array of tinymce buttons.
		 * @return array $buttons. Array of tinymce buttons, augmented with the plugin's tinymce button.
		 *
		 */
		public function shortcode_builder_register_buttons( $buttons ) {
		   array_push( $buttons, 'fourbzcore_shortcodebuilder' );
		   
		   return $buttons;
		}
		
		/**
		 * Register the javascript file that handles the tinymce actions for plugin's custom button.
		 *
		 * @since 1.0.0
		 *
		 * @param array $plugin_array Required. Array of tinymce plugins.
		 * @return array $plugin_array. Array of tinymce plugins, augmented with the plugin's plugin.
		 */
		public function shortcode_builder_register_tinymce_js( $plugin_array ) {
		   $plugin_array['fourbzcore_shortcodebuilder'] =  $this->plugin_url . 'js/4bzcore-tinymce.js';

		   return $plugin_array;
		}
		
		/**
		 * Change the encoding.
		 *
		 * @since 1.0.0
		 *
		 * @param array $init_array Required. Array of initialization data.
		 */
		public function change_mce_options( $init_array ) {
			$init_array['entity_encoding'] = 'named';
			
			return $init_array;
		}
		 
		/**
		 * Construct and display options for the shortcode builder.
		 *
		 * Each shortcode has a function to output its options. Format of the method is 
		 * display_options_$shortcode().
		 *
		 * @since 1.0.0
		 *
		 * @param string $shortcode Optional. If set, then display options for shortcode, otherwise display a select field to choose a shortcode.
		 */
		public function shortcode_options( $shortcode = '' ) {
			$shortcode_options = '';
			
			echo "<div class='fourbzcore-shortcode-options-wrap theme-options'>";
					
				echo "<h3>" . __( 'Select Shortcode', $this->txt_domain ) . "</h3>
					<select class='shortcode-select'>";
					
				$select_options = '';
				
				$shortcodes = array( 'recent_posts', 'featured_posts', 'popular_posts', 'related_posts',
					'contact_info', 'contact_form', 'slideshow', 'progressbars', 'flickr_photos',
					'facebook_comments', 'image_text', 'author_bio', 'column' );
		
				$shortcodes_count = count( $shortcodes );
				
				for ( $i = 0; $i < $shortcodes_count; ++$i ) {
					$select_options .= "<option value='$shortcodes[$i]'>$shortcodes[$i]</option>";
					$shortcode_options .= "<div id='$shortcodes[$i]-shortcode-component'
						class='shortcode-component'>";
						
					ob_start();
					call_user_func( array( $this, 'display_options_' . $shortcodes[$i] ) );
					$shortcode_options .= ob_get_clean();
					
					$shortcode_options .= "<button id='$shortcodes[$i]-btn' class='button-3 insert-shortcode-post'>
						Build $shortcodes[$i]</button></div>";
				}
				
				echo $select_options . '</select><br />' . $shortcode_options;
	
			echo '</div>';
		}
		
		/**
		 *  2.3.5 Post Edit Page
		 *-----------------------------------------------------------------------*/
		 
		/**
		 * Add metaboxes to post and page edit screens for related posts.
		 *
		 * @since 1.0.0
		 */
		public function add_meta_boxes() {
			$screens = array( 'post', 'page', 'product' );

			foreach ( $screens as $screen ) {
				add_meta_box(
					$this->page_slug,
					__( '4bzCore Post Options', $this->txt_domain ),
					array( $this, 'display_media_meta_boxes' ),
					$screen,
					'side'
				);
			}
		}

		/**
		 * Construct and display a meta box that contains options for related posts.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Post $post The object for the current post/page.
		 */
		public function display_meta_boxes( $post ) {
			$i = 0;
			
			// Add a nonce field so we can check for it later.
			wp_nonce_field( $this->plugin_prefix . '_display_meta_box', $this->plugin_prefix . '_display_meta_box_nonce' );

			// Get the saved data.
			$opts = get_post_meta( $post->ID, $this->db_options_name, true );
			
			$this->display_related_meta_box( isset( $opts['related'] ) ?  $opts['related'] : '' );
			echo '<div id="fourbzcore-dialog"></div>';
		}
		
		/**
		 * Construct and display options for related posts to be displayed in the meta box
		 *
		 * @since 1.0.0
		 *
		 * @param string $related Optional. Saved related posts.
		 */
		public function display_related_meta_box( $related ) {
			echo '<div id="fourbzcore-related-meta-box" class="fourbzcore-wrap text-format"><h4>' .
				__( 'Related Posts', $this->txt_domain ) . '</h4><label>' .
				__( 'Enter list of post ids that are related to this post. Format: 34 1 5', $this->txt_domain ) .
				'</label><input type="text" name="fourbzcore[related]"';
			
			if ( $related ) {
				echo ' value="' . esc_attr( trim( $related ) ) . '"';
			}
			
			echo ' /></div>';
		}
		
		/**
		 * Construct and display a meta box that contains options for media
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Post $post The object for the current post/page.
		 */
		public function display_media_meta_boxes( $post ) {
			$i = 0;
			
			// Use get_post_meta() to retrieve an existing value.
			echo '<div id="fourbzcore-meta-box">';
			$opts = get_post_meta( $post->ID, $this->db_options_name, true );
			
			$this->display_media_meta_box( $opts );
			
			$this->display_meta_boxes ( $post );
			
			echo '</div>';
		}

		/**
		 * Construct and display options for media to be displayed in the meta box
		 *
		 * @since 1.0.0
		 *
		 * @param array $opts Optional. The saved options retrieved by get_post_meta.
		 */
		public function display_media_meta_box( $opts ) {
			// Variables
			$i = 0;
			$id;
			$url;
			$media = isset( $opts['media'] ) ? $opts['media'] : array();
			$media_embed = isset( $opts['media_embed'] ) ? $opts['media_embed'] : '';
			
			echo '<div id="fourbzcore-media-meta-box" class="fourbzcore-wrap text-format"><h4>' .
				__( 'Featured Images', $this->txt_domain ) . '</h4><p>' . __( 'Sort the images by hovering over an image until a hand appears, drag and drop where desired.', $this->txt_domain ) . '</p><input class="icon fourbzcore-icon-image-btn button" data-fourbzcore-textbox="fourbzcore-media" type="button" value="Add Media" />' . 
				'<ul class="fourbzcore-media-list sortable">';
			
			if ( is_array( $media ) && 0 < count( $media ) ) {
				foreach ( $media as $key => $media_item ) {
					$id = esc_attr( $key );
					$url = esc_attr( $media_item['url'] );
					$width = esc_attr( $media_item['width'] );
					$height = esc_attr( $media_item['height'] );
					$alt_txt = esc_attr( $media_item['alt_txt'] );
					
					echo '<li><div class="fourbzcore-media-cont"><img id="img-' . $id . '" src="' . $url . '" />' .
						'<input type="hidden" name="fourbzcore[media][' . $id . '][url]" value="' . $url . '" />' .
						'<input type="hidden" name="fourbzcore[media][' . $id . '][id]" value="' . $id . '" />' .
						'<input type="hidden" name="fourbzcore[media][' . $id . '][width]" value="' . $width . '" />' .
						'<input type="hidden" name="fourbzcore[media][' . $id . '][height]" value="' . $height . '" />' .
						'<input type="hidden" name="fourbzcore[media][' . $id . '][alt_txt]" value="' . $alt_txt . '" />' .
						'</div><span class="delete-row overlay">Remove</span></li>';	
				}
			}
			
			echo '</ul>
				<h4>' . __( 'Video or Audio Embed', $this->txt_domain ) . '</h4>' . '<label>' . 
				__( 'Embed Code', $this->txt_domain ) . '</label><br />
				<textarea name="fourbzcore[media_embed][code]" rows="10" cols="25">';
			
			if ( isset( $media_embed['code'] ) ) {
				echo $media_embed['code'];
			}
			
			echo '</textarea></div>';
		}

		/**
		 * Save the post data to the postmeta table
		 *
		 * @since 1.0.0
		 *
		 * @param int $post_id The id of the post.
		 */
		public function save_postdata( $post_id ) {
			
			// If nonce is not set, then return.
			if ( ! isset( $_POST[$this->plugin_prefix . '_display_meta_box_nonce'] ) ) {
				return;
			}
			
			$nonce = $_POST[$this->plugin_prefix . '_display_meta_box_nonce'];

			// Verify nonce.
			if ( ! wp_verify_nonce( $nonce, $this->plugin_prefix . '_display_meta_box' ) ) {
				return;
			}
			
			// If autosaving, then return.
			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || 
				wp_is_post_autosave( $post_id ) ) { 
				return;
			}
			
			// Check the user's permissions.
			if ( 'page' === $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			$data = $_POST['fourbzcore'];
			
			// Post relation is not reflexive
			$new_string = preg_replace( '/\b' . $post_id . '/', '', $data['related'] );
			$data['related'] = trim( $new_string );
			
			$related = $data['related'];
			
			/*
			 *	Before saving the related posts data, need to reciprocate the relationship by updating any relevant
			 *	post meta data.
			 */
			$new_list = explode( ' ', $related );
			
			$new_list_count = count( $new_list );
			$old_list = get_post_meta( $post_id, $this->db_options_name, true );
			
			if ( isset( $old_list['related'] ) ) {
				$old_list = explode( ' ', $old_list['related'] );
				$old_list_count = count( $old_list );
			} else {
				$old_list_count = 0;
			}
			
			// If post is not related anymore, then update its postmeta.
			for ( $i = 0; $i < $old_list_count; ++$i ) {
				if ( ' ' !== $old_list[$i] && ! in_array( $old_list[$i], $new_list ) ) {
					$fourbzcore = get_post_meta( $old_list[$i], $this->db_options_name, true );
					
					if ( $fourbzcore ) {
						$related_list = isset( $fourbzcore['related'] ) ? $fourbzcore['related'] : '' ;
					
						if ( $related_list ) {
							$new_string = preg_replace( '/\b' . $post_id . '/', '', $related_list );
							$fourbzcore['related'] = $new_string;
							update_post_meta( $old_list[$i], $this->db_options_name, $fourbzcore );
						}
					}
				}
			}
			
			// Now need to add new relations.
			for ( $i = 0; $i < $new_list_count; ++$i ) {
				$related_list = get_post_meta( $new_list[$i], $this->db_options_name, true );
				
				// If none saved then just add the post id.
				if ( ! $related_list ) {
					$related_list = array();
					$related_list['related'] = $post_id;
					update_post_meta( $new_list[$i], $this->db_options_name, $related_list );
				} else {
					// If a list is already saved, check if post is already listed, if not then add it.
					$new_string = preg_replace( '/\b' . $post_id . '/', '', $related_list['related'] );
					$new_string .= " $post_id";
					$related_list['related'] = $new_string;
					update_post_meta( $new_list[$i], $this->db_options_name, $related_list );
				}
			}

			$_POST['fourbzcore']['related'] = $data['related'];
			
			// Now save to database.
			update_post_meta( $post_id, $this->db_options_name, $_POST['fourbzcore'] );
		}
		
		/**
		 * 2.3.6 User Profile Page
		 *-----------------------------------------------------------------------*/ 
		 
		/**
		 * Construct and display custom fields for user on the user profile page: avatar, facebook, twitter, google+, 
		 * linkedin, instagram, tumblr, pinterest, title, and skills.
		 *
		 * @since 1.0.0
		 *
		 * @param WP User $user Required. Current user.
		 */
		public function profile_form( $user ) {
			if ( ! isset( $user ) ) {
				return;
			}
			
			$screen = get_current_screen();
			
			$opts = get_the_author_meta( $this->db_options_name, $user->ID );
			
			$skills_html = '';
			$skills = isset( $opts['items'] ) ? $opts['items'] : '';
			$title = isset( $opts['title'] ) ? ' value="' . esc_attr( $opts['title'] ) . '"' : '';
			$address = isset( $opts['contact_address'] ) ? ' value="' . esc_attr( $opts['contact_address'] ) . '"' : '';
			$phone = isset( $opts['contact_phone'] ) ? ' value="' . esc_attr( $opts['contact_phone'] ) . '"' : '';
			$facebook = isset( $opts['contact_facebook'] ) ? ' value="' . esc_attr( $opts['contact_facebook'] ) . '"' : '';
			$twitter = isset( $opts['contact_twitter'] ) ? ' value="' . esc_attr( $opts['contact_twitter'] ) . '"' : '';
			$google = isset( $opts['contact_google'] ) ? ' value="' . esc_attr( $opts['contact_google'] ) . '"' : '';
			$linkedin = isset( $opts['contact_linkedin'] ) ? ' value="' . esc_attr( $opts['contact_linkedin'] ) . '"' : '';
			$instagram = isset( $opts['contact_instagram'] ) ? ' value="' . esc_attr( $opts['contact_instagram'] ) . '"' : '';
			$tumblr = isset( $opts['contact_tumblr'] ) ? ' value="' . esc_attr( $opts['contact_tumblr'] ) . '"' : '';
			$pinterest = isset( $opts['contact_pinterest'] ) ? ' value="' . esc_attr( $opts['contact_pinterest'] ) . '"' : '';
			$avatar = isset( $opts['avatar'] ) ? ' value="' . esc_attr( $opts['avatar'] ) . '"' : '';
			
			// Output saved custom fields.
			
			// Construct skills html.
			if ( is_array( $skills ) && 0 < count( $skills ) ) {
				$i = 0;
				
				foreach ( $skills as $skill ) {
					$skills_html .= '<li class="skills-cont" data-skill-index="' . $i . '"><br /><button type="button" class="button-2 delete-row">' .
					__( 'Delete', $this->txt_domain ) . '</button><br /><br />' . '<div><label>' .
					__( 'Name', $this->txt_domain ) . '</label><br /><input type="text" class="add-skill-name" data-skill-index="' .
					$i . '" name="fourbzcore[items][' . $i . '][name]" value="' . $skill['name'] . '" style="padding:5px;" /></div>' .
					'<label>' . __( 'Percentage', $this->txt_domain ) . '</label><input type="text" class="add-skill-value"' . ' name="fourbzcore[items][' . $i . '][value]" value="' . $skill['value'] . '"/>' .
					'<span class="instruction"><small>' . __( 'Enter percentage as a number 0-100', $this->txt_domain ) . '</small></span></li>';
					
					++$i;									
				}
			}
						
			echo '<h3>' .  __( '4bzCore Options', $this->txt_domain ) . '</h3>
			<table class="form-table fourbzcore-wrap">
				<tbody>';
					echo '
						<tr>
						<th>' . __( 'Title', $this->txt_domain ) . '</th>
						<td><input type="text" name="fourbzcore[title]"' . $title . ' /></td>
						</tr>
						<tr>
						<th>' . __( 'Avatar', $this->txt_domain ) . '</th>
						<td>';
					$this->construct_upload_image( $opts, 'avatar' );
							
					echo '</td>
						</tr> 
						<tr>
							<th>' . __( 'Address', $this->txt_domain ) . '</th>
							<td>
								<input type="text" name="fourbzcore[contact_address]"' . $address . ' " />
							</td>
						</tr> 
						<tr>
							<th>' . __( 'Phone', $this->txt_domain ) . '</th>
							<td>
								<input type="text" name="fourbzcore[contact_phone]"' . $phone . ' " />
							</td>
						</tr>
						<tr>
							<th>' . __( 'Facebook Handle', $this->txt_domain ) . '</th>
							<td>
								<input type="text" name="fourbzcore[contact_facebook]"' . $facebook . ' " />
							</td>
						</tr> 
						<tr>
							<th>' . __( 'Twitter Handle', $this->txt_domain ) . '</th>
							<td>
								<input type="text" name="fourbzcore[contact_twitter]"' . $twitter . ' " />
							</td>
						</tr> 
						<tr>
							<th>' . __( 'Google Handle', $this->txt_domain ) . '</th>
							<td>
								<input type="text" name="fourbzcore[contact_google]"' . $google . ' " />
							</td>
						</tr>
						<tr>
							<th>' . __( 'Linkedin', $this->txt_domain ) . '</th>
							<td>
								<input type="text" name="fourbzcore[contact_linkedin]"' . $linkedin . ' " />
							</td>
						</tr>
						<tr>
							<th>' . __( 'Instagram', $this->txt_domain ) . '</th>
							<td>
								<input type="text" name="fourbzcore[contact_instagram]"' . $instagram . ' " />
							</td>
						</tr>
						<tr>
							<th>' . __( 'Tumblr', $this->txt_domain ) . '</th>
							<td>
								<input type="text" name="fourbzcore[contact_tumblr]"' . $tumblr . ' " />
							</td>
						</tr>
						<tr>
							<th>' . __( 'Pinterest', $this->txt_domain ) . '</th>
							<td>
								<input type="text" name="fourbzcore[contact_pinterest]"' . $pinterest . ' " />
							</td>
						</tr>
						<tr>
							<th>' . __( 'Skills', $this->txt_domain ) . "</th>
							<td>
								<br /><p class='instruction'><small>" . __( 'Sort the skills by hovering over a skill until a hand appears, drag and drop where desired.', $this->txt_domain ) . '</small></p><br /><input id="add-skill-' . count( $skills ) . '" type="button" class="button-2 add-skill" value="' . __( 'Add Skill', $this->txt_domain ) . '" data-name="fourbzcore[items]" />
								<ul class="skills-wrap sortable">' . $skills_html . '</ul>
							</td>
						</tr>';  
			echo '</tbody>
			</table>';
		}

		/**
		 * Save custom fields for user
		 *
		 * @since 1.0.0
		 *
		 * @param int $user_id Required. Id of user.
		 */
		public function save_user_cfs( $user_id ) {
			if ( ! isset( $user_id ) ) {
				return;
			}
			
			$fourbzcore_cust_fields = array();
			$fourbzcore_cust_fields['title'] = sanitize_text_field( $_POST['fourbzcore']['title'] );
			$fourbzcore_cust_fields['contact_address'] = $_POST['fourbzcore']['contact_address'];
			$fourbzcore_cust_fields['contact_phone'] = $_POST['fourbzcore']['contact_phone'];
			$fourbzcore_cust_fields['contact_facebook'] = esc_url_raw( $_POST['fourbzcore']['contact_facebook'] );
			$fourbzcore_cust_fields['contact_twitter'] = esc_url_raw( $_POST['fourbzcore']['contact_twitter'] );
			$fourbzcore_cust_fields['contact_google'] = esc_url_raw( $_POST['fourbzcore']['contact_google'] );
			$fourbzcore_cust_fields['contact_linkedin'] = esc_url_raw( $_POST['fourbzcore']['contact_linkedin'] );
			$fourbzcore_cust_fields['contact_instagram'] = esc_url_raw( $_POST['fourbzcore']['contact_instagram'] );
			$fourbzcore_cust_fields['contact_tumblr'] = esc_url_raw( $_POST['fourbzcore']['contact_tumblr'] );
			$fourbzcore_cust_fields['contact_pinterest'] = esc_url_raw( $_POST['fourbzcore']['contact_pinterest'] );
			$fourbzcore_cust_fields['items'] = $_POST['fourbzcore']['items'];
			$fourbzcore_cust_fields['avatar'] = esc_url_raw( $_POST['fourbzcore']['avatar'] );
			$fourbzcore_cust_fields['avatar_width'] = absint( $_POST['fourbzcore']['avatar_width'] );
			$fourbzcore_cust_fields['avatar_height'] = absint( $_POST['fourbzcore']['avatar_height'] );
			$fourbzcore_cust_fields['avatar_alt'] = sanitize_text_field( $_POST['fourbzcore']['avatar_alt'] );
				
			update_user_meta( $user_id, $this->db_options_name, $fourbzcore_cust_fields ); 
		}
		
		/**
		 *  2.3.7 Admin Ajax
		 *-----------------------------------------------------------------------*/
		 
		/**
		 * Serves an ajax request to show the shortcode builder.
		 *
		 * @since 1.0.0
		 *
		 * @return string json encoded options.
		 */
		public function display_shortcode_builder() {
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], $this->plugin_prefix . "-shortcode-builder-nonce" ) ) {
				exit( __( "No Swiping", $this->txt_domain ) );
			}   
			$result = array();
			
			ob_start();
			$this->shortcode_options();
			$result['shortcode_builder'] = ob_get_clean();
			
			echo json_encode( $result );
			die();
		}
		
		/**
		 * Admin ajax error function
		 *
		 * @since 1.0.0
		 */
		public function no_go() {
			echo __( "No Swiping", $this->txt_domain );
			die();
		}
		
		/**
		 * 2.4 Front End Methods
		 *-----------------------------------------------------------------------*/
		 
		/**
		 * Enqueue all required scripts and styles for the front end
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			
			// Enqueue flexslider js
			wp_enqueue_script( 'flexslider', $this->plugin_url . 'js/jquery.flexslider-min.js', array( 'jquery' ), false, true );
				
			// Enqueue flexslider style
			wp_enqueue_style( 'flexslider', $this->plugin_url . 'css/flexslider.css' );
			
			// Enqueue font awesome style
			wp_enqueue_style( 'font-awesome', $this->plugin_url . 'font-awesome/css/font-awesome.min.css' );
				
			wp_enqueue_script( $this->plugin_prefix . 'frontend', $this->plugin_url . 'js/front-end.js', array( 'jquery' ), false, true );
				
			// Enqueue plugin's style
			wp_enqueue_style( $this->plugin_prefix . '-main', $this->plugin_url . 'css/style.css' );
		}
		
		/**
		 * Construct and display scripts and html necessary for social widgets like facebook comments.
		 *
		 * Requires app id for facebook, if not provided will not output the scripts and html.
		 *
		 * @since 1.0.0
		 */
		public function output_social_scripts() {
			$options =  get_option( $this->db_options_name );
			$facebook_id = '';
			
			if ( isset( $options['facebook_app_id'] ) ) {
				$facebook_id = $options['facebook_app_id'];
			}
			
			if ( $facebook_id ) {
				echo '<div id="fb-root"></div>
					<script>( function( d, s, id ) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if ( d.getElementById( id ) ) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
					  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=' . $facebook_id . '&version=v2.0";

					  fjs.parentNode.insertBefore(js, fjs);
					}(document, "script", "facebook-jssdk"));</script>';
			} 
		}
		
		/**
		 * Get the post meta
		 *
		 * @since 1.0.0
		 *
		 * @param object $post_id Post id Optional. The id of the post of which to get its post meta. If not provided, will try to retrieve it using the $wp_query variable, if that fails, then return.
		 */
		public function get_post_meta( $post_id ) {
			if ( ! isset( $post_id ) ) {
				global $wp_query;
				$post_id = $wp_query->post->ID;
			}
			
			if ( 'null' === $post_id ) {
				return;
			}
			
			return get_post_meta( $post_id, $this->db_options_name, true );
		}
		
		/**
		 * Get the user meta
		 *
		 * @since 1.0.0
		 *
		 * @param object $user_id User id Optional. The id of the user. If not provided, will try to retrieve it using the $wp_query variable, if that fails, then return.
		 */
		public function get_user_meta( $user_id ) {
			if ( ! isset( $user_id ) ) {
				global $wp_query;
				$user_id = $wp_query->post->post_author;
			}
			
			if ( 'null' === $user_id ) {
				return;
			}
			
			return get_user_meta( $user_id, $this->db_options_name, true );
		}
		
	} //End of Class
} //End of if ! class_exists.

// Run the plugin.
$fourbzcore_plugin = new FourBzCore();