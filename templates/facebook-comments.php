<?php 
/**
 * Template to display facebook comments
 *
 * @since 4bzCore 1.0.0
 *
 * @param global variable $fourbzcore_layout_opts, passed to this template by the facebook comments shortcode.
 *
 * @package 4bzCore
 * @subpackage facebook-comments.php
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

echo '<div class="fourbzcore-fb-comments-cont">';
// Display the title, if set.
if ( isset( $fourbzcore_layout_opts['title_text'] ) && $fourbzcore_layout_opts['title_text'] ) {
	if ( isset( $fourbzcore_layout_opts['is_widget'] ) && $fourbzcore_layout_opts['is_widget'] ) {
		echo $fourbzcore_layout_opts['title_text'];
	} else {
		echo '<h3 class="widget-title">' . $fourbzcore_layout_opts['title_text'] . '</h3>';
	}
}

// Get the permalink of the post
global $wp_query;

$id = $wp_query->post->ID;
$permalink = get_permalink( $id );

?>
<div class="fb-comments" data-href="<?php echo esc_html( $permalink ); ?>" data-numposts="<?php echo esc_attr( $fourbzcore_layout_opts['limit'] ); ?>" data-colorscheme="light" data-width="100%"></div>
</div>