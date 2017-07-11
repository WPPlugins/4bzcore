/**
 * Plugin Name: 4bzCore
 * Plugin URI: http://wordpress.org/plugins/4bzcore
 * Author: 4bzthemes
 * Author URI: http://4bzthemes.com
 * File Description: javascript front end file.
 *
 * @since 4bzCore 1.0.0
 *
 * @package 4bzCore
 * @subpackage front-end.js
 ------------------------------------------------------------------------
	Table of Contents
	
	1. Utility Functions 
	2. Initialization
	3. Progressbars
	4. Flexslider
-------------------------------------------------------------------------*/

/**
 * 1. Utility Functions
 *-----------------------------------------------------------------------*/

/**
 * Split an id by '-'
 *
 * @since 1.0
 * @param string id. Required. Id to be parsed.
 * @return Array. The id parsed into parts.
 */
function parse_id( id ) {
	return id.split( "-" );
}

/**
 * Get the index of an element by parsing its id.
 *
 * @since 1.0.0
 *
 * @param string id. Required. Id to be parsed.
 * @param int offset. Required. Array offset.
 * @return Int. The index.
 */
function fourbzcore_get_index( id, offset ) {
	var split = parse_id( id );
	
	return parseInt( split[split.length-offset] );
}

/**
 * Calculate number of columns to display based on the value of width.
 *
 * @since 1.0.0
 *
 * @param int width. Required. Width used to determine number of columns.
 * @param int cols. Required. Original number of cols.
 * @return int. The number of columns.
 */
function get_grid_size( width, cols ) {
	return ( width <= 640 ) ? 1 : cols;
}

( function( $ ) {
	"use strict";
	$( document ).on( 'ready', function() {
		/**
		 * 2. Initialization
		 *-----------------------------------------------------------------------*/
 
		/**
		 * Initialization code does the following
		 *		1. Hide all elements with class hide.
		 *
		 * @since 1.0.0
		 */
		function initialize() {
			$( '.hide' ).css( 'display', 'none' );
		}
		
		initialize();
		
		/**
		 * 3. Progressbars
		 *-----------------------------------------------------------------------*/
		
		/**
		 * Handle the animation of the progressbars
		 *
		 * @since 1.0.0
		 */
		function animate_progressbars() {	
			$( '.progressbar' ).each( function( i ) {
				var value = $( this ).data( 'percentage' );
				
				$( this ).animate( { width: value + '%' }, 150 );
			});
		}
		
		// Animate the progressbars.
		animate_progressbars();
		
		/**
		 * 4. Flexslider
		 *-----------------------------------------------------------------------*/
		// Flexslider for the slideshow component.
		$( '.fourbzcore-slideshow.flexslider' ).each( function( i ) {
			$( this ).flexslider( {
				animation: 'slide',
				animationLoop: true,
				controlNav: false,
				smoothHeight: true,
				itemWidth: 3000
			} );
		});
	});
})( jQuery );