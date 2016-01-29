(function( $ ) {
	'use strict';
	
	$(function() {
		
		if( $( '#hide_expanding_ad' ).is( ':checked' ) ) {
			$('.hide_if_expanding_hidden').toggle(); }
		
		if( $( '#hide_wrap_ads' ).is( ':checked' ) ) {
			$('.hide_if_wrap_hidden').toggle();
		}
		
		if( $( '#hide_leaderboard_ad' ).is( ':checked' ) ) {
			$('.hide_if_leaderboard_hidden').toggle();
		}
		
		$('#hide_expanding_ad').live('change', function() {
			$('.hide_if_expanding_hidden').toggle();
		});
		
		$('#hide_wrap_ads').live('change', function() {
			$('.hide_if_wrap_hidden').toggle();
		});
		
		$('#hide_leaderboard_ad').live('change', function() {
			$('.hide_if_leaderboard_hidden').toggle();
		});
		
		$('.my-color-field').wpColorPicker();
		
		$('.datepicker').datepicker({ dateFormat : 'yy-mm-dd' });
		
		var file_frame;
		
		$('.upload_image_button').live('click', function( event ){
			event.preventDefault();
			
			if ( file_frame ) {
				file_frame.open();
				return;
			}
			
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
					text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});
			
			file_frame.on( 'select', function() {
				var selection = file_frame.state().get('selection');
			    selection.map( function( attachment ) {
			 
					attachment = attachment.toJSON();
					$('#background_image').val( attachment.url );
		 		});
			});
			
			file_frame.open();
		});
	});
})( jQuery );