/**
 * Plugin Name: 4bzCore
 * Plugin URI: http://wordpress.org/plugins/4bzcore
 * Author: 4bzthemes
 * Author URI: http://4bzthemes.com
 * File Description: javascript admin file included on the widgets page, post edit page, and shortcode builder.
 *
 * @since 4bzCore 1.0.0
 *
 * @package 4bzCore
 * @subpackage admin.js
 ------------------------------------------------------------------------
	Table of Contents
	
	1. Utility Functions 
	2. Helper Functions 
	3. Options Pages Functions 
	4. Shortcode Builder Functions
	5. Media Uploader Functions
	6. Event Handlers
-------------------------------------------------------------------------*/
 
/**
 * 1. Utility Functions
 *-----------------------------------------------------------------------*/

/**
 * Split an id by '-'
 *
 * @since 1.0.0
 * @param string id. Required. Id to be parsed.
 * @return Array. The id parsed into parts.
 */
function fourbzcore_parse_id( id ) {
	return id.split( "-" );
}

/**
 * Get the index of an element by parsing its id
 *
 * @since 1.0.0
 *
 * @param string id. Required. Id to be parsed.
 * @param int offset. Required. Array offset.
 * @return Int. The index.
 */
function fourbzcore_get_index( id, offset ) {
	var split = fourbzcore_parse_id( id );
	
	return parseInt( split[split.length-offset] );
}

/**
 * Get the prefix of an element's id
 *
 * Used when there's a group of elements with the same base id, differing only in its prefix.
 *
 * @since 1.0.0
 *
 * @param string id. Id to be parsed.
 * @return string. The id's prefix.
 */
function fourbzcore_get_name( id ) {
	var split = fourbzcore_parse_id( id );
	
	return split[0];
}

var fourbzcore = {};

( function( $ ) {
	"use strict";
	
	/**
	 * 2. Helper Functions
	 *-----------------------------------------------------------------------*/
	
	/**
	 * Handle click to delete a row. The target must be a direct child node of the container to be deleted
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 * @return false.
	 */
	function fourbzcore_delete_row( event ) {
		var parent = $( event.target ).parent();
	
		parent.remove();
		
		return false;							
	}
	
	/**
	 * Handle selection to hide and show a container
	 * The target and the container to hide and show must be siblings.
	 *
	 * Works for select and checkboxes.
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 */
	function fourbzcore_hide_show( event ) {
		var parent = $( event.target ).parent(), 
			display = $( '.hideshow', parent ).css( 'display' );
		
		if ( $( event.target ).attr( 'checked' ) ) {
			display = 'table-row';
		} else {
			display = 'none';
		}
		
		$( '> .hideshow', parent ).css( 'display', display );
	}
	
	/**
	 * Handle click of the x button to close a box
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 */
	function fourbzcore_close_box( event ) {
		var target = $( event.target ), 
			parent = target.parent();
		
		parent.remove();
	}
	
	/**
	 * 3. Options Pages Functions
	 *		Includes the widget, post edit, and shortcode builder pages.
	 *-----------------------------------------------------------------------*/
	
	/**
	 * Handle click to show the font awesome icons box to choose an icon.
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 */
	function fourbzcore_display_icons_box( event ) {
		var i, item, 
			html = '<div class="fourbzcore-icon-chooser"><div class="fourbzcore-closer">' + fourbzcore_object.close + ' X</div>',
			target = $( event.target ), 
			parent = target.parent(), 
			fa = fourbzcore_object.fa_items;
		
		for ( i = 0; i < fa.length; ++i ) {
			item = fa[i];
			html += '<i value="' + item + '" class="fa ' + item + '"></i>';
		}
		
		html += '</div>';
		
		$( '.fourbzcore-icon-chooser', parent ).remove();
		
		parent.append( html );
		
		$( '.fourbzcore-icon-chooser .fa' ).on( 'click', fourbzcore_add_icon_field );
	}
	
	/**
	 * Handle selection of a font awesome icon. 
	 * Inserts the icon into the corresponding text field.
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 */
	function fourbzcore_add_icon_field( event ) {
		var target = $( event.target ), 
			parent = target.parent().parent(), 
			val = target.attr( 'value' );
	
		$( '.fourbzcore-icon-field', parent ).val( val );
	}
	
	/**
	 * 4. Shortcode Builder Functions
	 *-----------------------------------------------------------------------*/
	 
	/**
	 * Handle the selection of a shortcode and displays its options in the shortcode builder
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 */
	function fourbzcore_show_shortcode_options( event ) {
		var target = $( event.target ), 
			val = target.val();
		
		$( '.shortcode-component' ).css( 'display', 'none' );
		
		$( '#' + val + '-shortcode-component' ).css( 'display', 'block' );
	}
	
	/**
	 * Handle click to build the shortcode
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 */
	function fourbzcore_build_shortcode( event ) {
		var frag,
			target = $( event.target ),
			id = target.attr( 'id' ),
			name = fourbzcore_get_name( id ),
			ed = tinymce.activeEditor;
			
		switch ( name ) {
			case 'related_posts': 		
				frag = fourbzcore.fourbzcore_build_posts( 'related_posts' );
				break;
			case 'recent_posts': 		
				frag = fourbzcore.fourbzcore_build_posts( 'recent_posts' );
				break;
			case 'featured_posts':		
				frag = fourbzcore.fourbzcore_build_posts( 'featured_posts' );
				break;
			case 'popular_posts':		
				frag = fourbzcore.fourbzcore_build_posts( 'popular_posts' );
				break;
			case 'contact_info': 		
				frag = fourbzcore.fourbzcore_build_contact_info();
				break;
			case 'contact_form': 		
				frag = fourbzcore.fourbzcore_build_contact_form();
				break;
			case 'progressbars': 		
				frag = fourbzcore.fourbzcore_build_progressbars();
				break;
			case 'slideshow': 			
				frag = fourbzcore.fourbzcore_build_slideshow();
				break;
			case 'flickr_photos': 			
				frag = fourbzcore.fourbzcore_build_flickr_photos();
				break;
			case 'facebook_comments': 			
				frag = fourbzcore.fourbzcore_build_facebook_comments();
				break;
			case 'author_bio': 			
				frag = fourbzcore.fourbzcore_build_author_bio();
				break;
			case 'column': 			
				frag = fourbzcore.fourbzcore_build_column();
				break;
			case 'image_text': 			
				frag = fourbzcore.fourbzcore_build_image_text();
				break;
		}
		
		// Now insert the shortcode into the post and close the dialog.
		ed.execCommand( 'mceInsertContent', 0, frag );
		
		$( '#fourbzcore-dialog' ).dialog( 'close' );
	}
	
	/**
	 * Build the component wrapper options from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @param string shortcode. Required. The name of the shortcode that is being built.
	 * @return string. The wrapper options fragment of the shortcode.
	 */
	function fourbzcore_build_wrapper_options( shortcode ) {
		if ( ! shortcode ) {
			return;
		}
		
		var shortcode_frag = '',
			tag = '',
			// The regular expression used to delete all "'" from the values, so not to close attributes prematurely.	
			regexp = /'/g;
		
		tag = $( "[name='fourbzcore[" + shortcode + "][title_text]']" ).val();
		if ( tag ) {
			shortcode_frag += " title_text='" + tag.replace( regexp, '' ) + "'";
		}
		
		return shortcode_frag;
	}
	
	/**
	 * Build the post-based shortcode from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @param string shortcode. Required. The name of the shortcode that is being built.
	 * @return string. The constructed post-based shortcode.
	 */
	fourbzcore.fourbzcore_build_posts = function( shortcode ) {
		if ( ! shortcode ) {
			return;
		}
		
		var shortcode_frag = "[4bzcore_" + shortcode;
		
		shortcode_frag += fourbzcore_build_wrapper_options( shortcode );
		
		tag = $( "[name='fourbzcore[" + shortcode + "][limit]']" ).val();
		if ( tag ) {
			shortcode_frag += " limit='" + tag.replace( regexp, '' ) + "'";	
		}
		
		shortcode_frag += ']';
		
		return shortcode_frag;
	}
	
	/**
	 * Build the contact info shortcode from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @return string. The constructed contact info shortcode.
	 */
	fourbzcore.fourbzcore_build_contact_info = function() {
		var shortcode_frag = "[4bzcore_contact_info";
		
		shortcode_frag += fourbzcore_build_wrapper_options( 'contact_info' );
		shortcode_frag += fourbzcore_build_contact_info_specific_opts( 'contact_info' );
		shortcode_frag += fourbzcore_build_contact_info_general( 'contact_info' );
		shortcode_frag += ']';
		
		return shortcode_frag;
	}
	
	/**
	 * Build the contact info specific options from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @param string shortcode. Required. The name of the shortcode that is being built.
	 * @return string. The contact info specific options fragment of the shortcode.
	 */
	function fourbzcore_build_contact_info_specific_opts( shortcode ) {
		if ( ! shortcode ) {
			return;
		}
		
		var shortcode_frag = '',
			tag = '',
			// The regular expression used to delete all "'" from the values, so not to close attributes prematurely.
			regexp = /'/g;
		
		tag = $( "[name='fourbzcore[" + shortcode + "][user_id]']" ).val();
		if ( tag ) {
			shortcode_frag += " user_id='" + tag.replace( regexp, '' ) + "'";
		}
		
		return shortcode_frag;
	}
	
	/**
	 * Build the contact info general options from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @param string shortcode. Required. The name of the shortcode that is being built.
	 * @return string. The contact info general options fragment of the shortcode.
	 */
	function fourbzcore_build_contact_info_general( shortcode ) {
		if ( ! shortcode ) {
			return;
		}
		
		var shortcode_frag = '',
			tag = '',
			// The regular expression used to delete all "'" from the values, so not to close attributes prematurely.
			regexp = /'/g;
			
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_address]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_address='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][address_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " address_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_phone]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_phone='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][phone_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " phone_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_url]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_url='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][url_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " url_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_email]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_email='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][email_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " email_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_facebook]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_facebook='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][facebook_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " facebook_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_twitter]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_twitter='" + tag.replace( regexp, '' ) + "'";
		}
		tag = $( "[name='fourbzcore[" + shortcode + "][twitter_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " twitter_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_google]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_google='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][google_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " google_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_linkedin]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_linkedin='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][linkedin_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " linkedin_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_tumblr]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_tumblr='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][tumblr_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " tumblr_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_instagram]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_instagram='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][instagram_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " instagram_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][contact_pinterest]']" ).val();
		if ( tag ) {
			shortcode_frag += " contact_pinterest='" + tag.replace( regexp, '' ) + "'";
		}
		
		tag = $( "[name='fourbzcore[" + shortcode + "][pinterest_icon]']" ).val();
		if ( tag ) {
			shortcode_frag += " pinterest_icon='" + tag.replace( regexp, '' ) + "'";
		}
		
		return shortcode_frag;
	}
	
	/**
	 * Build the contact form shortcode from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @return string. The constructed contact form shortcode.
	 */
	fourbzcore.fourbzcore_build_contact_form = function() {
		var shortcode_frag = "[4bzcore_contact_form";
		
		shortcode_frag += fourbzcore_build_wrapper_options( 'contact_form' );
		shortcode_frag += fourbzcore_build_contact_form_specific_opts();
		shortcode_frag += fourbzcore_build_contact_info_specific_opts( 'contact_form' );
		shortcode_frag += fourbzcore_build_contact_info_general( 'contact_form' );
		shortcode_frag += ']';
		
		return shortcode_frag;
	}
	
	/**
	 * Build the contact form specific options from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @return string. The contact form specific options fragment of the shortcode.
	 */
	function fourbzcore_build_contact_form_specific_opts() {
		var shortcode_frag = '',
			tag = '',
			// The regular expression used to delete all "'" from the values, so not to close attributes prematurely.
			regexp = /'/g;
		
		tag = $( "[name='fourbzcore[contact_form][show_contact]']" ).attr( "checked" );
		if ( tag ) { 
			shortcode_frag += " show_contact='" + tag + "'";
		}
		
		tag = $( "[name='fourbzcore[contact_form][show_map]']" ).attr( "checked" );
		if ( tag ) {
			shortcode_frag += " show_map='" + tag + "'";
		}
			
		tag = $( "[name='fourbzcore[contact_form][contact_form]']" ).val();
		if ( tag ) {
			var regexp2 = /\[/g;
			
			// Replace '[' and ']' with '(' and ')' respectively, so not to cause any problems with the shortcode syntax.
			shortcode_frag += " contact_form='" + tag
				.replace( regexp, '' )
				.replace( regexp2, '(' )
				.replace( /\]/g, ')' ) + "'";
		}
		
		return shortcode_frag;
	}
	
	/**
	 * Build the slideshow shortcode from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @return string. The constructed slideshow shortcode.
	 */
	fourbzcore.fourbzcore_build_slideshow = function() {
		var shortcode_frag = "[4bzcore_slideshow";
		
		shortcode_frag += fourbzcore_build_wrapper_options( 'slideshow' );
		shortcode_frag += fourbzcore_build_slideshow_specific_opts();
		shortcode_frag += ']';
		
		return shortcode_frag;
	}
	
	/**
	 * Build the slideshow specific options from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @return string. The slideshow specific options fragment of the shortcode.
	 */
	function fourbzcore_build_slideshow_specific_opts() {
		var shortcode_frag = '',
			tag = '',
			// The regular expression used to delete all "'" from the values, so not to close attributes prematurely.
			regexp = /'/g,
			val = '',
			sep = '';
			
		tag = new Array();

		// Construct the slides. Separate them with '=>'.
		$( '[name="fourbzcore[slideshow][slides][]"]' ).each( function( i ) {
			val += sep + $( this ).val().replace( regexp, '' );
			sep = '=>';
		});
		
		if ( tag ) {
			shortcode_frag += " slides='" + val + "'";
		}	
		
		return shortcode_frag;
	}
	
	/**
	 * Build the progressbars shortcode from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @return string. The constructed progressbars shortcode.
	 */
	fourbzcore.fourbzcore_build_progressbars = function() {
		var shortcode_frag = "[4bzcore_progressbars",
			tag = '',
			// The regular expression used to delete all "'" from the values, so not to close attributes prematurely.
			regexp = /'/g;
			
		shortcode_frag += fourbzcore_build_wrapper_options( 'progressbars' );
		
		tag = $( "[name='fourbzcore[progressbars][cols]']" ).val();
		if ( tag ) {
			shortcode_frag += " cols='" + tag + "'";
		}
		
		tag = {};
		
		// Get each skill and add to tag array.
		$( '.add-skill-name').each(
			function( i ) {
				var index = $( this ).data( 'skill-index' ),
					name = $( this ).val().replace( regexp, '' ),
					value = $( '[name="fourbzcore[progressbars][items][' + index + '][value]"]' ).val().replace( regexp, '' );
					
				tag[name] = value;
			} );
		
		regexp = /\[/g;
		
		/*
		 * Convert the tag array into a json string, replacing the brackets with parentheses, so not to cause
		 * any problems with the brackets in the shortcode string.
		 */
		if ( tag ) {
			shortcode_frag += " items='" + JSON.stringify( tag )
				.replace( regexp, '(' )
				.replace( /\]/g, ')' ) + 
				"'";
		}
		
		shortcode_frag += ']';
		
		return shortcode_frag;
	}
	
	/**
	 * Build the flickr photos shortcode from the user inputs
	 *
	 * @since 1.0.0
	 *
	 * @return string. The constructed flickr photos shortcode.
	 */
	fourbzcore.fourbzcore_build_flickr_photos = function () {
		var shortcode_frag = "[4bzcore_flickr_photos",
			tag = '';
		 
		//shortcode_frag += fourbzcore_build_wrapper_options( 'flickr_photos' );
		
		tag = $( '[name="fourbzcore[flickr_photos][title_text]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " title_text='" + tag + "'";
		}
		
		tag = $( '[name="fourbzcore[flickr_photos][limit]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " limit='" + tag + "'";
		}
		
		tag = $( '[name="fourbzcore[flickr_photos][user_id]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " user_id='" + tag + "'";
		}
		
		shortcode_frag += ']';
		
		return shortcode_frag;
	}
	
	/**
	 * Build the facebook comments shortcode from the user inputs
	 *
	 * @since 1.0.0 
	 *
	 * @return string. The constructed facebook comments shortcode.
	 */
	fourbzcore.fourbzcore_build_facebook_comments = function () {
		var shortcode_frag = "[4bzcore_facebook_comments",
			tag = '';
		 
		shortcode_frag += fourbzcore_build_wrapper_options( 'facebook_comments' );
		
		tag = $( '[name="fourbzcore[facebook_comments][limit]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " limit='" + tag + "'";
		}
		
		shortcode_frag += ']';
		
		return shortcode_frag;
	}
	
	/**
	 * Build the author bio shortcode from the user inputs
	 *
	 * @since 1.0.0 
	 *
	 * @return string. The constructed author bio shortcode.
	 */
	fourbzcore.fourbzcore_build_author_bio = function () {
		var shortcode_frag = "[4bzcore_author_bio",
			tag = '';
		 
		shortcode_frag += fourbzcore_build_wrapper_options( 'author_bio' );
		
		shortcode_frag += ']';
		
		return shortcode_frag;
	}
	
	/**
	 * Build the column shortcode from the user inputs
	 *
	 * @since 1.0.0 
	 *
	 * @return string. The constructed column shortcode.
	 */
	fourbzcore.fourbzcore_build_column = function () {
		var shortcode_frag = "[4bzcore_column",
			tag = '';
		 
		tag = $( '[name="fourbzcore[column][column_width]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " column_width='" + tag + "'";
		}
		
		tag = $( '[name="fourbzcore[column][column_padding]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " column_padding='" + tag + "'";
		}
		
		tag = $( '[name="fourbzcore[column][column_margin]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " column_margin='" + tag + "'";
		}
		
		shortcode_frag += '][/fourbzcore_column]';
		
		return shortcode_frag;
	}
	
	/**
	 * Build the image text shortcode from the user inputs
	 *
	 * @since 1.0.0 
	 *
	 * @return string. The constructed image text shortcode.
	 */
	fourbzcore.fourbzcore_build_image_text = function () {
		var shortcode_frag = "[4bzcore_image_text",
			tag = '';
		 
		shortcode_frag += fourbzcore_build_wrapper_options( 'image_text' );
		
		tag = $( '[name="fourbzcore[image_text][image_url]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " image_url='" + tag + "'";
		}
		
		tag = $( '[name="fourbzcore[image_text][image_url_width]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " image_url_width='" + tag + "'";
		}
		
		tag = $( '[name="fourbzcore[image_text][image_url_height]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " image_url_height='" + tag + "'";
		}
		
		tag = $( '[name="fourbzcore[image_text][image_url_alt]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " image_url_alt='" + tag + "'";
		}
		
		tag = $( '[name="fourbzcore[image_text][content]"]' ).val();
		if ( tag ) { 
			shortcode_frag += " content='" + tag + "'";
		}
		
		shortcode_frag += ']';
		
		return shortcode_frag;
	}
	
	/**
	 * Add a dynamically created skill to the skills container
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 * @return false.
	 */
	function fourbzcore_add_skill( event ) {
		var html,
			target = $( event.target ),
			name = target.attr( 'data-name' ),
			count =  $( ".skills-wrap > li", $( this ).closest( 'div' ) ).length,
			parent = $( ".skills-wrap", $( this ).closest( 'div' ) );
			
		if ( 'undefined' === typeof fourbzcore_add_skill.index ) {
			fourbzcore_add_skill.index = count;
		}
		
		html = '<li class="skills-cont"><br /><button type="button" class="button-2 delete-row">' + 
					fourbzcore_object.Delete + '</button><br /><br />' + 
					'<div><label>' + 
					fourbzcore_object.name + '</label><input type="text" class="add-skill-name" data-skill-index="' + 
					fourbzcore_add_skill.index + '" name="' + name + '[' + fourbzcore_add_skill.index + 
					'][name]" style="padding:5px;" /></div>' + 
					'<label>Percentage</label><input type="text" class="add-skill-value"' +
					' name="' + name + '[' + fourbzcore_add_skill.index + '][value]" />' +
					'<span class="instruction"><small>' + fourbzcore_object.enter_percentage + '</small></span></li>';
	
		parent.append( html );
		
		$( '.sortable' ).sortable();
		
		++fourbzcore_add_skill.index;
		
		return false;
	}
	
	/**
	 * Add markup to the slides-wrap container for a new slide
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 * @return false.
	 */
	function fourbzcore_add_slide( event ) {
		var target = $( event.target ),
			name = target.attr( 'data-name' ),
			parent = $( ".slides-wrap", $( this ).closest( 'td' ) ),
			html = '<li class="slides-cont"><button type="button" class="button-2 delete-row">' + 
					fourbzcore_object.Delete + '</button><br /><br />';
			
			html += '<label>' + fourbzcore_object.enter_slide + 
				'</label><div class="loader"></div><textarea class="slide-html" name="' + name + 
				'[]" rows="20" cols="50"></textarea></li>';
		
		parent.append( html );
		
		$( '.sortable' ).sortable();
		
		return false;
	}
	
	/**
	 * 5. Media Uploader Functions
	 *-----------------------------------------------------------------------*/
	
	// Global variables used in the media uploader functions.
	var custom_uploader, fourbzcore_index;
	
	/**
	 * Handle media file(s) selection in the media uploader, specifically images
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 */
	function fourbzcore_update_file_url( event ) {
		var image = '',
			width = '',
			height = '',
			alt = '',
			imgs_html = '',
			selection = custom_uploader.state().get( 'selection' );
		
		// Map image(s) selections.
		selection.map( function( attachment ) {
			attachment = attachment.toJSON();
			
			var id = attachment.id,
				i;
		
			if ( 'image' === attachment.type ) {
				if ( fourbzcore_index.match(/image-text/g) || ! $( '.fourbzcore-media-list' ).length ) {
					image = attachment.url;
					width = attachment.width;
					height = attachment.height;
					alt	= attachment.alt;
					return;
				} else {
					imgs_html += '<li><div class="fourbzcore-media-cont"><img id="img-' + id + '" src="' + 
								 attachment.url + '" /><input type="hidden" name="fourbzcore[media][' + id + '][url]" value="' + attachment.url + '" />' + '<input type="hidden"' +
								 ' name="fourbzcore[media][' + id + '][id]" value="' + id + '" />' + 
								 '<input type="hidden" name="fourbzcore[media][' + id + '][width]" value="' + 
								 attachment.width + '" />' + '<input type="hidden" name="fourbzcore[media][' + id + '][height]" value="' + attachment.height + '" />' + 
								 '<input type="hidden" name="fourbzcore[media][' + id + '][alt_txt]" value="' +
								 attachment.alt + '" />' + '</div><span class="delete-row overlay">Remove' +
								 '</span></li>';
				}
			}
		});
		
		if ( fourbzcore_index.match(/image-text/g) || ! $( '.fourbzcore-media-list' ).length ) {
			$( '#' + fourbzcore_index + '-width' ).val( width );
			$( '#' + fourbzcore_index + '-height' ).val( height );
			$( '#' + fourbzcore_index + '-alt' ).val( alt );
			$( '#' + fourbzcore_index ).val( image );
		} else {
			$( '.fourbzcore-media-list' ).append( imgs_html );
		}
	}
	
	/**
	 * Handle the display of the WP Media Uploader
	 *
	 * Add handler for selection of files in the uploader.
	 *
	 * @since 1.0.0
	 *
	 * @param object event. Object passed to handler by jquery.
	 */
	function fourbzcore_display_media_uploader( event ) {
		var id = $( event.target ).data( 'fourbzcore-textbox' );
		
		fourbzcore_index = id;
		
		event.preventDefault();
		
		// If the uploader object has already been created, reopen the dialog.
		if ( custom_uploader ) {
			custom_uploader.open();
			return;
		}
		
		// Extend the wp.media object.
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: fourbzcore_object.choose_image,
			button: {
				text: fourbzcore_object.choose_image
			},
			multiple: true
		});
		
		// Handle the file selection.
		custom_uploader.on( 'select', fourbzcore_update_file_url );

		// Open the uploader dialog.
		custom_uploader.open();
	}
	
	/**
	 * 6. Event Handlers
	 *-----------------------------------------------------------------------*/
	$( document ).on( 'ready', function() {	
		// Sortable
		$( '.sortable' ).sortable();
		
		/**
		 * General Plugin Options
		 *-----------------------------------------------------------------------*/
		$( 'body' ).on( 'click', '.delete-row', fourbzcore_delete_row );
		$( 'body' ).on( 'click', '.add-skill', fourbzcore_add_skill );
		$( 'body' ).on( 'click', '.fourbzcore-add-slide', fourbzcore_add_slide );
		$( 'body' ).on( 'click', '.fourbzcore-displaymaster', fourbzcore_hide_show );
		$( 'body' ).on( 'focus', '.fourbzcore-icon-field', fourbzcore_display_icons_box );
		$( 'body' ).on( 'click', '.fourbzcore-closer', fourbzcore_close_box );
		$( 'body' ).on( 'click', '.fourbzcore-icon-image-btn', fourbzcore_display_media_uploader );
		
		/**
		 * Shortcode Builder
		 *-----------------------------------------------------------------------*/
		$( 'body' ).on( 'change', '.shortcode-select', fourbzcore_show_shortcode_options );
		$( '.shortcode-select' ).trigger( 'change' );
		$( 'body' ).on( 'click', '.insert-shortcode-post', fourbzcore_build_shortcode );
	});
})( jQuery );