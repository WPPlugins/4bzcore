<?php
/** 
 * Template to output a contact form
 *
 * @since 4bzCore 1.0.0
 *
 * @param global variable $fourbzcore_layout_opts, passed to this template by the fourbzcore contact form shortcode.
 *
 * @package 4bzCore
 * @subpackage contact-form.php
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

global $fourbzcore_plugin;

$extra_class = '';

if ( isset( $fourbzcore_layout_opts['contact_form'] ) && $fourbzcore_layout_opts['contact_form'] &&
	isset( $fourbzcore_layout_opts['show_contact'] ) && $fourbzcore_layout_opts['show_contact'] ) {
	$extra_class = ' contact-form-cols-2';
}

echo '<div class="fourbzcore-contact-form-cont' . $extra_class . '">';
  
// Display the title, if it's set.
if ( isset( $fourbzcore_layout_opts['title_text'] ) && $fourbzcore_layout_opts['title_text'] ) {
	if ( isset( $fourbzcore_layout_opts['is_widget'] ) && $fourbzcore_layout_opts['is_widget'] ) {
		echo $fourbzcore_layout_opts['title_text'];
	} else {
		echo '<h3 class="widget-title">' . $fourbzcore_layout_opts['title_text'] . '</h3>';
	}
}

// Get the google maps app id.
$key = isset( $fourbzcore_layout_opts['google_app_id'] ) ? $fourbzcore_layout_opts['google_app_id'] : null;

if ( $key && isset( $fourbzcore_layout_opts['show_map'] ) && $fourbzcore_layout_opts['show_map'] &&
	isset( $fourbzcore_layout_opts['contact_address'] ) ) {
	$address = str_replace( ' ', '+', $fourbzcore_layout_opts['contact_address'] );
?>
<div class="map"><iframe style="border:0" height="450" src="<?php echo esc_url( 'https://www.google.com/maps/embed/v1/search?key=' . $key . '&q=' . $address ); ?>">
</iframe></div>
<?php
}
if ( isset( $fourbzcore_layout_opts['show_contact'] ) && $fourbzcore_layout_opts['show_contact'] ) :
?>	
	<div class="contact-info-main">
		<?php 
			// Call the shortcode to construct and display the contact information.
			
			// Don't show the contact form's title and tagline text again.
			$fourbzcore_layout_opts['title_text'] = '';
			$fourbzcore_layout_opts['tagline_text'] = '';
			
			echo $fourbzcore_plugin->fourbzcore_shortcodes->contact_info( $fourbzcore_layout_opts );
		?>
	</div>
<?php 
endif;

// Now call the shortcode to display the contact form.
if ( isset( $fourbzcore_layout_opts['contact_form'] ) && $fourbzcore_layout_opts['contact_form'] ) {
	echo '<div class="contact-info-form">';
	echo do_shortcode( stripslashes( $fourbzcore_layout_opts['contact_form'] ) );
	echo '</div>';
}

echo '</div>';
?>