<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    HBI_Ad_Manager
 * @subpackage HBI_Ad_Manager/admin/partials
 */
$active_tab = ( isset( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ): 'display_settings';
$display_settings = get_option( 'hbipa_display_settings' ); 
?>
<div class="wrap">
    <?php screen_icon(); ?>
    <div id="adpanel">
        <ul class="adpanel_tabs">
            <li class="adpanel_tab <?php echo ( 'display_settings' == $active_tab) ? 'active' : ''; ?>">
                <a href="<?php echo admin_url( 'options-general.php?page=hbi_premium_ads&tab=display_settings' ); ?>">Display Settings</a>
            </li>
            <li class="adpanel_tab <?php echo ( 'expanding_ad_settings' == $active_tab) ? 'active' : ''; ?>">
                <a href="<?php echo admin_url( 'options-general.php?page=hbi_premium_ads&tab=expanding_ad_settings' ); ?>">Expanding Ad</a>
            </li>
            <li class="adpanel_tab <?php echo ( 'wrap_ad_settings' == $active_tab) ? 'active' : ''; ?>">
                <a href="<?php echo admin_url( 'options-general.php?page=hbi_premium_ads&tab=wrap_ad_settings' ); ?>">Wrap Ads</a>
            </li>
            <li class="adpanel_tab <?php echo ( 'leaderboard_ad_settings' == $active_tab) ? 'active' : ''; ?>">
                <a href="<?php echo admin_url( 'options-general.php?page=hbi_premium_ads&tab=leaderboard_ad_settings' ); ?>">Leaderboard Ad</a>
            </li>
        </ul>
        <div id="adpanel-content">
            <form method="post" action="options.php">
                <?php switch( $active_tab ) {
                    case 'display_settings':
                        settings_fields( 'hbipa_display_settings' );
                        do_settings_sections( 'hbipa_display_settings' );
                        break;
                    case 'expanding_ad_settings':
                        if( 1 == $display_settings['show_expanding_ad']) {
                            settings_fields( 'hbipa_expanding_ad_settings' );
                            do_settings_sections( 'hbipa_expanding_ad_settings' );
                            echo "<h4>To display the expanding ad, add the following line of code into your template.</h4>";
                            echo "<code>&lt;?php echo do_action( 'display_expanding_ad' ); ?&gt;</code>";
                        } else {
                            echo "<h3>Please turn on the Expanding Ad before trying to configure it.</h3>";
                        }
                        break;
                    case 'wrap_ad_settings':
                        if( 1 == $display_settings['show_wrap_ads']) {
                            settings_fields( 'hbipa_wrap_ad_settings' );
                            do_settings_sections( 'hbipa_wrap_ad_settings' );
                            echo "<h4>To display the wrap ads, add the following line of code into your template.</h4>";
                            echo "<code>&lt;?php echo do_action( 'display_wrap_ads' ); ?&gt;</code>";
                        } else {
                            echo "<h3>Please turn on Wrap Ads before trying to configure them.</h3>";
                        }
                        break;
                    case 'leaderboard_ad_settings':
                        if( 1 == $display_settings['show_leaderboard_ad']) {
                            settings_fields( 'hbipa_leaderboard_ad_settings' );
                            do_settings_sections( 'hbipa_leaderboard_ad_settings' );
                            echo "<h4>To display the leaderboard ad, add the following line of code into your template.</h4>";
                            echo "<code>&lt;?php echo do_action( 'display_leaderboard_ad' ); ?&gt;</code>";
                        } else {
                            echo "<h3>Please turn on the Leaderboard Ad before trying to configure it.</h3>";
                        }
                        break;
                } ?>
                <?php submit_button(); ?>
            </form>
        </div>
    </div>
</div>
