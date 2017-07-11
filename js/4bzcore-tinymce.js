/**
 * Plugin Name: 4bzCore
 * Plugin URI: http://wordpress.org/plugins/4bzcore
 * Author:4bzthemes
 * Author URI: http://4bzthemes.com
 * File Description: javascript file to create the tinymce plugin.
 *
 * @since 4bzCore 1.0.0
 *
 * @package 4bzCore
 * @subpackage 4bzcore-tinymce.js
 ------------------------------------------------------------------------*/
 
(function( $ ) {
	"use strict";
	$( '#fourbzcore-dialog' ).dialog({
		modal: true,
		autoOpen: false,
		open: function() {
			$('.shortcode-select' ).trigger( 'change' );
			$( '.fourbzcore-my-color-field' ).wpColorPicker();
		},
		width: 800
	});
	
	tinymce.init({
        entity_encoding: "raw"
	});
	
	tinymce.create('tinymce.plugins.fourbzcore_shortcodebuilder', {
		/**
		 * Initialize the plugin, this will be executed after the plugin has been created.
		 *
		 * @param tinymce.Editor ed Editor instance that the plugin is initialized in.
		 * @param string url Absolute URL to where the plugin is located.
		 */
		init: function(ed, url) {
					ed.addButton( 'fourbzcore_shortcodebuilder', {
						title: '4bzCore shortcodes',
						cmd: 'fourbzcore_shortcodebuilder',
						image: url + '/4bz.png'
					});
			
					ed.addCommand( 'fourbzcore_shortcodebuilder', function() {
						/*
						 * When the shortcode button on the post edit page is clicked, show the shortcode builder
						 * in a dialog, using an ajax request to display the builder, on success, display the data.
						 */
						var url = fourbzcore_object.admin_ajax;
						var paramsJson = '';
						
						$.ajax({ 
							url: url,
							type: "POST",
							timeout: 5000,
							dataType: "json",
							error: function( xhr ) {
								alert( 'error in my ajax '+ xhr.status + ' ' + xhr.statusText );
							},
							success: function( data ) {
										$( '#fourbzcore-dialog' ).html( data.shortcode_builder );
										
										$( '#fourbzcore-dialog' ).dialog('open');
									 }
						});
						
						
					});
				},

		/**
		 * Return information about the plugin as a name/value array.
		 *
		 * @return array. Contains information about the plugin.
		 */
		getInfo: function() {
					return {
						longname: '4bzCore Shortcode Builder',
						author: '4bzthemes',
						authorurl: 'http://4bzthemes.com/about/',
						version: "1.0.1"
					};
				 }
	});

	// Register plugin.
	tinymce.PluginManager.add( 'fourbzcore_shortcodebuilder', tinymce.plugins.fourbzcore_shortcodebuilder );
})( jQuery );