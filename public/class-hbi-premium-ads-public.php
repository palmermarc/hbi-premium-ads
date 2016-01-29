<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    HBI_Premium_Ads
 * @subpackage HBI_Premium_Ads/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    HBI_Premium_Ads
 * @subpackage HBI_Premium_Ads/public
 * @author     Marc Palmer <mapalmer@hbi.com>
 */
class HBI_Premium_Ads_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hbi-premium-ads-public.js', array( 'jquery' ), $this->version, true );
	}
    
    /**
     * Display the expanding ad. Please note, this does not require a default ad set up
     * 
     * It is completely possible to have it off by default and turned on for specific pages.
     * 
     * @since 1.0.0 
     */
    public function display_expanding_ad() {
        
        $post_ID = get_the_ID();
        
        // Let's make sure that we're supposed to be displaying these ads before we even attempt it.
        $display_settings = get_option( 'hbipa_display_settings' );
        if( 1 != $display_settings['show_expanding_ad'] )
            return false;
        
        $expanding_settings = get_option( 'hbipa_expanding_ad_settings' );
        $small_expanding_ad_unit = $expanding_settings['default_small_ad_unit'];
        $large_expanding_ad_unit = $expanding_settings['default_large_ad_unit'];
        
        $takeover_ID = get_post_meta( $post_ID, 'takeover', TRUE );
        $use_takeover = ( 0 != absint( $takeover_ID ) ) ? 1 : 0;
        
        if( $use_takeover != 0 ) {
            $start_time = strtotime( get_post_meta( $takeover_ID, 'start_date', TRUE )  . " " . get_post_meta( $takeover_ID, 'start_time', TRUE )  );
            $end_time = strtotime( get_post_meta( $takeover_ID, 'end_date', TRUE )  . " " . get_post_meta( $takeover_ID, 'end_time', TRUE )  );
            $now = current_time( 'timestamp' );
            if( $start_time < $now && $now < $end_time ) {
                if( 1 == get_post_meta( $takeover_ID, 'hide_expanding_ad', TRUE ) )
                    return;
                
                if( 1 == get_post_meta( $takeover_ID, 'use_custom_expanding_ad', TRUE ) ) {
                    $left_wrap_ad_unit = get_post_meta( $takeover_ID, 'custom_small_expanding_ad', TRUE );
                    $right_wrap_ad_unit = get_post_meta( $takeover_ID, 'custom_large_expanding_ad', TRUE );
                }
            } else {
                $use_takeover = 0;
            }
        } else if ( 0 == $use_takeover ) {
            
            // If this page/post has the ad turned off, then bail immediately.
            if( 1 == get_post_meta( $post_ID, 'hide_expanding_ad', TRUE ) )
                return false;
            
            if( 1 == get_post_meta( $post_ID, 'use_custom_expanding_ad', TRUE ) ) {
                $small_expanding_ad_unit = get_post_meta( $post_ID, 'custom_small_expanding_ad', TRUE );
                $large_expanding_ad_unit = get_post_meta( $post_ID, 'custom_large_expanding_ad', TRUE );
            }
        }
        ?>
        <div id="expand-o-span">
            <div id="expand-small">
                <?php echo do_shortcode( '[display_dfp_ad ad_zone="' . $small_expanding_ad_unit . '"]' ); ?>
                <div id="expand-open" class="expand-message">Open</div>
            </div>
            <div id="expand-large">
                <?php echo do_shortcode( '[display_dfp_ad ad_zone="' . $large_expanding_ad_unit . '"]' ); ?>
                <div id="expand-close" class="expand-message">Close</div>
            </div>
        </div>
        <?php
    }
    
    public function display_wrap_ads() {
        
        $post_ID = get_the_ID();
        
        // Let's make sure that we're supposed to be displaying these ads before we even attempt it.
        $display_settings = get_option( 'hbipa_display_settings' );
        if( 1 != $display_settings['show_wrap_ads'] )
            return;
        
        if( is_home() && date( 'Ymd' ) == '20151129' )
            return;
        
        $wrap_ads_settings = get_option( 'hbipa_wrap_ad_settings' );
        $left_wrap_ad_unit = $wrap_ads_settings['default_left_ad_unit'];
        $right_wrap_ad_unit = $wrap_ads_settings['default_right_ad_unit'];
        
        $takeover_ID = get_post_meta( $post_ID, 'takeover', TRUE );
        $use_takeover = ( 0 != absint( $takeover_ID ) ) ? 1 : 0;
        
        // There is a takeover, so let's move forward
        if( $use_takeover ) {
            $start_time = strtotime( get_post_meta( $takeover_ID, 'start_date', TRUE )  . " " . get_post_meta( $takeover_ID, 'start_time', TRUE )  );
            $end_time = strtotime( get_post_meta( $takeover_ID, 'end_date', TRUE )  . " " . get_post_meta( $takeover_ID, 'end_time', TRUE )  );
            $now = current_time( 'timestamp' );
            if( $start_time < $now && $now < $end_time ) {
                if( 1 == get_post_meta( $takeover_ID, 'hide_wrap_ads', TRUE ) )
                    return;
                
                if( 1 == get_post_meta( $takeover_ID, 'use_custom_wrap_ads', TRUE ) ) {
                    $left_wrap_ad_unit = get_post_meta( $takeover_ID, 'custom_wrap_left_ad_unit', TRUE );
                    $right_wrap_ad_unit = get_post_meta( $takeover_ID, 'custom_wrap_right_ad_unit', TRUE );
                }
            } else {
                $use_takeover = 0;
            }
        } else if( 0 == $use_takeover ) {
            // If this page/post has the ad turned off, then bail immediately.
            if( 1 == get_post_meta( $post_ID, 'hide_wrap_ads', TRUE ) )
                return;
            
            if( !is_home() && 1 == get_post_meta( $post_ID, 'use_custom_wrap_ads', TRUE ) ) {
                $left_wrap_ad_unit = get_post_meta( $post_ID, 'custom_wrap_left_ad_unit', TRUE );
                $right_wrap_ad_unit = get_post_meta( $post_ID, 'custom_wrap_right_ad_unit', TRUE );
            }
        }
        ?>
        <div id="left-wrap-ad" class="wrap-ad left_wrap">
            <?php echo do_shortcode( '[display_dfp_ad ad_zone="' . $left_wrap_ad_unit . '"]' ); ?>
        </div>
        <div id="right-wrap-ad" class="wrap-ad right_wrap">
            <?php echo do_shortcode( '[display_dfp_ad ad_zone="' . $right_wrap_ad_unit . '"]' ); ?>
        </div>
        <?php
    }
    
    function display_leaderboard_ad() {
        $post_ID = get_the_ID();
        $display_settings = get_option( 'hbipa_display_settings' );
        if( 1 != $display_settings['show_leaderboard_ad'] )
            return;
        
        // If the page is disabling wrap ads, then do nothing
        if( !is_home() && 1 == get_post_meta( get_the_ID(), 'hide_leaderboard_ad', TRUE ) )
            return;
        
        $leaderboard_settings = get_option( 'hbipa_leaderboard_ad_settings' );
        $leaderboard_ad_unit = $leaderboard_settings['default_leaderboard_ad_unit'];
        
        $takeover_ID = get_post_meta( $post_ID, 'takeover', TRUE );
        $use_takeover = ( 0 != absint( $takeover_ID ) ) ? 1 : 0;
        
        // There is a takeover, so let's move forward
        if( $use_takeover ) {
            $start_time = strtotime( get_post_meta( $takeover_ID, 'start_date', TRUE )  . " " . get_post_meta( $takeover_ID, 'start_time', TRUE )  );
            $end_time = strtotime( get_post_meta( $takeover_ID, 'end_date', TRUE )  . " " . get_post_meta( $takeover_ID, 'end_time', TRUE )  );
            $now = current_time( 'timestamp' );
            if( $start_time < $now && $now < $end_time ) {
                if( 1 == get_post_meta( $takeover_ID, 'hide_leaderboard_ad', TRUE ) )
                    return;
                
                if( 1 == get_post_meta( $takeover_ID, 'use_custom_leaderboard_ad', TRUE ) ) {
                    $leaderboard_ad_unit = get_post_meta( $takeover_ID, 'custom_leaderboard_ad_unit', TRUE );
                }
            } else {
                $use_takeover = 0;
            }
        } else if( 0 == $use_takeover ) {
            // If this page/post has the ad turned off, then bail immediately.
            if( 1 == get_post_meta( $post_ID, 'hide_leaderboard_ad', TRUE ) )
                return;
            
            if( !is_home() && 1 == get_post_meta( $post_ID, 'use_custom_leaderboard_ad', TRUE ) )
                $leaderboard_ad_unit = get_post_meta( $post_ID, 'custom_leaderboard_ad_unit', TRUE );
        }
        
        echo "<div class='leaderboard'>";
        echo do_shortcode( '[display_dfp_ad ad_zone="' . $leaderboard_ad_unit . '"]' );
        echo "</div>";
    }

    function add_takeover_background( ) {
        $post_ID = get_the_ID();
        $takeover_ID = get_post_meta( $post_ID, 'takeover', TRUE );
        
        // There is a takeover, so let's move forward
        if( 0 != absint( $takeover_ID ) ) {
            $start_time = strtotime( get_post_meta( $takeover_ID, 'start_date', TRUE )  . " " . get_post_meta( $takeover_ID, 'start_time', TRUE )  );
            $end_time = strtotime( get_post_meta( $takeover_ID, 'end_date', TRUE )  . " " . get_post_meta( $takeover_ID, 'end_time', TRUE )  );
            $now = current_time( 'timestamp' );
            if( $start_time < $now && $now < $end_time ) {
                $background_attachment = ( '' != get_post_meta( $takeover_ID, 'background_attachment', TRUE ) ) 
                    ? "background-attachment: " . get_post_meta( $takeover_ID, 'background_attachment', TRUE ). " !important;" 
                    : '';
                $background_image = ( '' != get_post_meta( $takeover_ID, 'background_image', TRUE ) ) 
                    ? "background-image: url('" . get_post_meta( $takeover_ID, 'background_image', TRUE  ) . "') !important;" 
                    : '';
                $background_color = ( '' != get_post_meta( $takeover_ID, 'background_color', TRUE ) ) 
                    ? "background-color: " . get_post_meta( $takeover_ID, 'background_color', TRUE ) . " !important;" 
                    : '';
                $background_position = ( '' != get_post_meta( $takeover_ID, 'background_position_x', TRUE ) && '' != get_post_meta( $takeover_ID, 'background_position_y', TRUE ) ) 
                    ? "background-position: " . get_post_meta( $takeover_ID, 'background_position_x', TRUE ) . " " . get_post_meta( $takeover_ID, 'background_position_y', TRUE ) . " !important;" 
                    : '';
                $background_repeat = ( '' != get_post_meta( $takeover_ID, 'background_repeat', TRUE ) ) 
                    ? "background-repeat: " . get_post_meta( $takeover_ID, 'background_repeat', TRUE ) . " !important;" 
                    : '';
                if( $start_time < $now && $now < $end_time ) {
                    ?> <style type="text/css" media="screen"> body { <?php echo $background_attachment; ?> <?php echo $background_color; ?> <?php echo $background_image; ?> <?php echo $background_position; ?> <?php echo $background_repeat; ?> } </style> <?php
                }
            }
        }
    }
	function add_body_classes( $classes ) {
		$display_settings = get_option( 'hbipa_display_settings' );
		
        if( 1 == $display_settings['show_expanding_ad'] ) {
        	if( is_home() || 1 != get_post_meta( get_the_ID(), 'hide_expanding_ad', TRUE ) )
				$classes[] = 'has_expanding_ad';
        }
		
		if( 1 == $display_settings['show_leaderboard_ad'] ) {
        	if( is_home() || 1 != get_post_meta( get_the_ID(), 'hide_leaderboard_ad', TRUE ) )
				$classes[] = 'has_leaderboard_ad';
        }
		
		if( 1 == $display_settings['show_wrap_ads'] ) {
        	if( is_home() || 1 != get_post_meta( get_the_ID(), 'hide_wrap_ads', TRUE ) )
				$classes[] = 'has_wrap_ads';
        }
		
		return $classes;
	}
}