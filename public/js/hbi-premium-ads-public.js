(function( $ ) {
	'use strict';

	function reset_iframe_height() {
		reset_small_iframe_height();
		reset_large_iframe_height();
	}
	
	function reset_small_iframe_height() {
		if( jQuery('#expand-small').find('ins, iframe').length )  {
			var small_iframe_height = jQuery('#expand-small').find('ins, iframe').contents().find('img').height();
			jQuery('#expand-small').find('ins, iframe').each(function() {
				jQuery(this).css( 'height', small_iframe_height );
			});
		} 
	}
	
	function reset_large_iframe_height() {
		if( jQuery('#expand-large').find('ins, iframe').length )  {
			var large_iframe_height = jQuery('#expand-large').find('ins, iframe').contents().find('img').height();
			jQuery('#expand-large').find('ins, iframe').each(function() {
				jQuery(this).css( 'height', large_iframe_height );
			});
		}
	}
	
	function set_default_styles() {
		
		jQuery('#expand-small').find('ins, iframe').each(function() {
			jQuery(this).css( 'width', '100%' );
			jQuery(this).contents().find('div').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('a').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('img').css({ "width" : "100%", "height" : "auto" });
		});
		
		jQuery('#expand-large').find('ins, iframe').each(function() {
			jQuery(this).css( 'width', '100%' );
			jQuery(this).contents().find('div').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('a').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('img').css({ "width" : "100%", "height" : "auto" }); 
		});
		
		jQuery('#ad-unit-620x100-bernie-show-ad').find('ins, iframe').each(function() {
			jQuery(this).css( 'width', '100%' );
			jQuery(this).contents().find('div').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('a').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('img').css({ "width" : "100%", "height" : "auto" }); 
		});
		
		jQuery('#ad-unit-bernie-article-ad').find('ins, iframe').each(function() {
			jQuery(this).css( 'width', '100%' );
			jQuery(this).contents().find('div').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('a').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('img').css({ "width" : "100%", "height" : "auto" }); 
		});
		
		jQuery('#ad-unit-620x100-bernie-writers-ad').find('ins, iframe').each(function() {
			jQuery(this).css( 'width', '100%' );
			jQuery(this).contents().find('div').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('a').css({ "width" : "100%", "height" : "auto" });
			jQuery(this).contents().find('img').css({ "width" : "100%", "height" : "auto" }); 
		});
		
		jQuery('.leaderboard').find('div iframe, div, ins, iframe').each(function() {
			var leaderboard_height = jQuery( '.leaderboard' ).height() + 'px';
			var leaderboard_width = jQuery( '.leaderboard' ).width() + 'px';
			
			jQuery(this).css({ "width" : leaderboard_width, "height" : leaderboard_height });
			jQuery(this).contents().find('div').css({ "width" : leaderboard_width, "height" : leaderboard_height });
			jQuery(this).contents().find('a').css({ "width" : leaderboard_width, "height" : leaderboard_height });
			jQuery(this).contents().find('img').css({ "width" : leaderboard_width, "height" : leaderboard_height });
			jQuery(this).contents().find('object').css({ "width" : leaderboard_width, "height" : leaderboard_height });
			jQuery(this).contents().find('embed').css({ "width" : leaderboard_width, "height" : leaderboard_height });
			jQuery(this).contents().find('#ad_wrapper').css({ "width" : leaderboard_width, "height" : leaderboard_height });
		});
		
		jQuery('#ad-unit-728x90-traffic').find('div iframe, div, ins, iframe').each(function() {
			var traffic_height = jQuery('#ad-unit-728x90-traffic').height() + 'px';
			var traffic_width = jQuery('#ad-unit-728x90-traffic').width() + 'px';
			jQuery(this).css({ "width" : traffic_width, "height" : traffic_height });
			jQuery(this).contents().find('div').css({ "width" : traffic_width, "height" : traffic_height });
			jQuery(this).contents().find('a').css({ "width" : traffic_width, "height" : traffic_height });
			jQuery(this).contents().find('img').css({ "width" : traffic_width, "height" : traffic_height });
			jQuery(this).contents().find('object').css({ "width" : traffic_width, "height" : traffic_height });
			jQuery(this).contents().find('embed').css({ "width" : traffic_width, "height" : traffic_height });
			jQuery(this).contents().find('#ad_wrapper').css({ "width" : traffic_width, "height" : traffic_height });
		});
	}
	
	window.addEventListener( 'orientationchange', function() {
		set_default_styles();
		reset_iframe_height();
	}, false);
	
	window.addEventListener( 'resize', function() {
		set_default_styles();
		reset_iframe_height();
	}, false );

	$(document).ready(function() {
		
		setTimeout(function() {
			set_default_styles();
			reset_iframe_height();
		}, 1500);
		
		$('#expand-open').click(function() {
		   	$( '#expand-o-span' ).attr( 'class', 'opened' );
		   	$( '#expand-small' ).slideUp( '150' );
		   	$( '#expand-large' ).slideDown( '150' );
		   	reset_large_iframe_height();
	 	});
		
		$('#expand-close').click(function() {
			$( '#expand-o-span' ).attr( 'class', 'closed' );
		   	$( '#expand-large' ).slideUp( '150' );
			$( '#expand-small' ).slideDown( '150' );
			reset_small_iframe_height();
		});

		if( $(window).width() > 767 ) {
			$('#expand-open').trigger('click');
			setTimeout(function() { jQuery( "#expand-close" ).trigger( "click" ); }, 3500);
		}
		
		$(window).resize(function() {
			reset_iframe_height();
		});
		
	});
})( jQuery );
