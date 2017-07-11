<?php
/**
 * FourBzCore Database class.
 *
 * Class used by shortcodes to interact with the wordpress database.
 *
 * @since 4bzCore 1.0.0
 *
 * @package 4bzCore 
 * @subpackage class-4bzcore-database.php
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if ( ! class_exists( 'FourBzCore_Database' ) ) {  
	class FourBzCore_Database {
		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			if ( ! is_admin() ) {
				add_filter( 'posts_where', array( $this, 'extra_db_where' ), 10, 2 );
			}
		}
		
		/**
		 * Add extra where clause to the wp query
		 *
		 * Uses the posts_where filter.
		 *
		 * Checks the query_var. If it is fourbzcore_related_posts, then add the extra where clause.
		 *
		 * @since 1.0.0
		 */
		public function extra_db_where( $where, $query ) {
			if ( isset( $query->query_vars['fourbzcore_related_posts'] ) && ! $query->is_main_query() &&
				( false == $query->query_vars['suppress_filters'] ) ) {
				global $wpdb;
				
				if ( isset( $query->query_vars['data'] ) ) {
					$where .= " AND $wpdb->posts.ID IN ( " . $query->query_vars['data'] . " )";
				}
			}
			
			return $where;
		}
		
		/**
		 * Get the user's contact information.
		 *
		 * User contact information could be a combination of wordpress default contact fields and the custom contact fields provided with the plugin.
		 *
		 * @since 4bzCore 1.0.0
		 *
		 * @param array $user_id Required. Id of user.
		 */
		public function get_user_contact_info( $user_id ) {
			global $wpdb;
			
			$query = $wpdb->prepare( "SELECT u.user_email as contact_email, u.user_url as contact_url,
				um.fourbzcore_options FROM $wpdb->users u JOIN ( SELECT $wpdb->usermeta.user_id,
				$wpdb->usermeta.meta_value as fourbzcore_options FROM $wpdb->usermeta Where
				$wpdb->usermeta.meta_key='fourbzcore_options') um on u.ID = um.user_id WHERE u.ID=%d", $user_id );
			
			$results = $wpdb->get_results( $query, ARRAY_A );
			
			return $results;
		}
		
		/**
		 * General get posts method.
		 *
		 * @since 4bzCore 1.0.0
		 *
		 * @param array $args Optional. If not provided, then will get all posts of type post, else will be the arguments passed to the WP Query object.
		 * @return WP_Query object.
		 */
		public function get_posts( $args ) {
			// Initialize variables.
			$data = $ignore_sticky = $sticky_only = $orderby = $limit = $query_var = $posts_per_page = '';
			
			extract( $args );
			
			// If sticky_only, then set the post__in argument and ignore sticky posts.
			if ( $sticky_only ) {
				$query_args['post__in'] = get_option( 'sticky_posts' );
				$query_args['ignore_sticky_posts'] = 1;
			}
			
			// Pagination argument
			if ( $posts_per_page ) {
				$query_args['posts_per_page'] = $posts_per_page;
			}
			
			// Ignore sticky posts.
			if ( $ignore_sticky ) {
				$query_args['ignore_sticky_posts'] = 1;
			}
			
			// Set a limit
			if ( $limit ) {
				$query_args['posts_per_page'] = intval( $limit );
			}
			
			// Set the query variable.
			if ( $query_var ) {
				$query_args[$query_var] = 1;
			}
			
			// Set any data argument.
			if ( $data ) {
				$query_args['data'] = $data;
			}
			
			// Order by
			if ( $orderby ) {
				$query_args['orderby'] = $orderby;
			}
			
			// Retrieve the results.
			$results = new WP_Query( $query_args );
			
			return $results;
		}
	}
}