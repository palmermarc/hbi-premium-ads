<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    HBI_Premium_Ads
 * @subpackage HBI_Premium_Ads/includes
 * @author     Marc Palmer <mapalmer@hbi.com>
 */
class HBI_Premium_Ads_Activator {

	/**
	 * @since    1.0.0
	 */
	public static function activate() {
        
        if ( ! is_plugin_active( 'hbi-ad-manager/hbi-ad-manager.php' ) and current_user_can( 'activate_plugins' ) ) {
            // Stop activation redirect and show error
            wp_die('Sorry, but this plugin requires the HBI Ad Manager to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
        }
        
        if( false === get_option( 'hbipa_display_settings' ) ) {
            $defaults = array(
                'show_expanding_ad' => 0,
                'show_wrap_ads' => 0,
                'show_leaderboard_ad' => 0,
            );
            
            add_option( 'hbipa_display_settings', $defaults );
        }

        if( false === get_option( 'hbipa_wrap_ad_settings' ) ) {
            $wrap_defaults = array(
                'default_width' => 160,
                'default_height' => 600,
                'default_left_ad_unit' => 0,
                'default_right_ad_unit' => 0,
            );
            
            add_option( 'hbipa_wrap_ad_settings', $wrap_defaults );
        }
        
        if( false == get_option( 'hbipa_expanding_ad_settings' ) ) {
            $expanding_defaults = array(
                'default_small_width' => 0,
                'default_small_height' => 0,
                'default_large_width' => 0,
                'default_large_height' => 0,
                'default_small_ad_unit' => '',
                'default_large_ad_unit' => ''
            );
            
            add_option( 'hbipa_expanding_ad_settings', $expanding_defaults );
        }

        if( false == get_option( 'hbipa_leaderboard_ad_settings' ) ) {
            $leaderboard_settings = array(
                'leaderboard_width' => 0,
                'leaderboard_height' => 0,
                'default_leaderboard_ad_unit' => '',
            );
            
            add_option( 'hbipa_leaderboard_ad_settings', $leaderboard_settings );
        }
	}
}