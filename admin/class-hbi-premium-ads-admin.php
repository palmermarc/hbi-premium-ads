<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    HBI_Premium_Ads
 * @subpackage HBI_Premium_Ads/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    HBI_Premium_Ads
 * @subpackage HBI_Premium_Ads/admin
 * @author     Marc Palmer <mapalmer@hbi.com>
 */
class HBI_Premium_Ads_Admin {

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
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$screen = get_current_screen();
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/hbi-premium-ads-admin.css', array(), $this->version, 'all' );
        
        if( 'takeover' == $screen->post_type ) {
            wp_enqueue_style( 'wp-color-picker' );
        }
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        $screen = get_current_screen();
        
	    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/hbi-premium-ads-admin.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-datepicker' ), $this->version, false );
	}
    
    public function hbi_premium_ads_options_page() {
        add_options_page( 'HBI Premium Ads', 'HBI Premium Ads', 'administrator', 'hbi_premium_ads', array( $this, 'display_hbi_premium_ads_admin' ) );
    }
    
    public function display_hbi_premium_ads_admin() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/hbi-premium-ads-admin-display.php';
    }
    
    public function register_hbi_premium_ads_display_settings() {
        register_setting( 
            'hbipa_display_settings',
            'hbipa_display_settings',
            array( $this, 'sanitize_display_settings' )
        );
        
        add_settings_section( 
            'hbi_premium_ads_display_section', 
            'HBI Premium Ads', 
            NULL, 
            'hbipa_display_settings'
        );

        add_settings_field(
            'show_expanding_ad', 
            'Display Expanding Ads?', 
            array( $this, 'show_expanding_ad_callback' ), 
            'hbipa_display_settings', 
            'hbi_premium_ads_display_section'
        );
        
        add_settings_field(
            'show_wrap_ads', 
            'Display Wrap Ads?', 
            array( $this, 'show_wrap_ads_callback' ), 
            'hbipa_display_settings', 
            'hbi_premium_ads_display_section'
        );
        
        add_settings_field(
            'show_leaderboard_ad', 
            'Display Leaderboard Ad?', 
            array( $this, 'show_leaderboard_ad_callback' ), 
            'hbipa_display_settings', 
            'hbi_premium_ads_display_section'
        );
        
    }

    function register_hbi_premium_ads_expanding_ad_settings() {
        register_setting( 'hbipa_expanding_ad_settings', 'hbipa_expanding_ad_settings', array( $this, 'sanitize_expanding_ads' ) );
        
        add_settings_section( 
            'hbi_premium_ads_expanding_settings', 
            'Expanding Ads Setting', 
            NULL, 
            'hbipa_expanding_ad_settings'
        );
        
        add_settings_field(
            'expanding_ad_sizes', 
            'Expanding Ad Sizes', 
            array( $this, 'show_expanding_ads_sizes_callback' ), 
            'hbipa_expanding_ad_settings',
            'hbi_premium_ads_expanding_settings'
        );
        
        add_settings_field(
            'default_small_expanding_ad',
            'Default Small Expanding Ad',
            array( $this, 'default_small_expanding_ad_callback' ),
            'hbipa_expanding_ad_settings',
            'hbi_premium_ads_expanding_settings'
        );
        
        add_settings_field(
            'default_large_expanding_ad',
            'Default Large Expanding Ad',
            array( $this, 'default_large_expanding_ad_callback' ),
            'hbipa_expanding_ad_settings',
            'hbi_premium_ads_expanding_settings'
        );
        
    }

    function register_hbi_premium_ads_wrap_ad_settings() {
        register_setting( 'hbipa_wrap_ad_settings', 'hbipa_wrap_ad_settings', array( $this, 'sanitize_wrap_ads' ) );
        
        add_settings_section(
            'hbi_premium_ads_wrap_ad_settings',
            'Wrap Ad Settings',
            NULL,
            'hbipa_wrap_ad_settings'
        );
        
        add_settings_field(
            'default_wrap_ad_size',
            'Wrap Ad Size',
            array( $this, 'default_wrap_ad_size_callback' ),
            'hbipa_wrap_ad_settings',
            'hbi_premium_ads_wrap_ad_settings'
        );
        
        add_settings_field(
            'default_wrap_ad_unit',
            'Wrap Ad Size',
            array( $this, 'default_wrap_ad_unit_callback' ),
            'hbipa_wrap_ad_settings',
            'hbi_premium_ads_wrap_ad_settings'
        );
    }
    
    function register_hbi_premium_ads_leaderboard_ad_settings() {
        register_setting( 'hbipa_leaderboard_ad_settings', 'hbipa_leaderboard_ad_settings', array( $this, 'sanitize_leaderboard_settings' ) );
        
        add_settings_section(
            'hbi_premium_ads_leaderboard_ad_settings',
            'Leaderboard Ad Settings',
            NULL,
            'hbipa_leaderboard_ad_settings'
        );
        
        add_settings_field(
            'leaderboard_width',
            'Leaderboard Width',
            array( $this, 'leaderboard_width_callback' ),
            'hbipa_leaderboard_ad_settings',
            'hbi_premium_ads_leaderboard_ad_settings'
        );
        
        add_settings_field(
            'leaderboard_height',
            'Leaderboard Height',
            array( $this, 'leaderboard_height_callback' ),
            'hbipa_leaderboard_ad_settings',
            'hbi_premium_ads_leaderboard_ad_settings'
        );
        
        add_settings_field(
            'default_leaderboard_ad_unit',
            'Default Leaderboard Ad',
            array( $this, 'default_leaderboard_ad_unit_callback' ),
            'hbipa_leaderboard_ad_settings',
            'hbi_premium_ads_leaderboard_ad_settings'
        );
    }
    
    function leaderboard_width_callback() {
        $options = get_option( 'hbipa_leaderboard_ad_settings' ); 
        ?><input type="text" name="hbipa_leaderboard_ad_settings[leaderboard_width]" value="<?php echo $options['leaderboard_width']; ?>" /> <?php 
    }

    function leaderboard_height_callback() {
        $options = get_option( 'hbipa_leaderboard_ad_settings' ); 
        ?><input type="text" name="hbipa_leaderboard_ad_settings[leaderboard_height]" value="<?php echo $options['leaderboard_height']; ?>" /> <?php 
    }
    function default_leaderboard_ad_unit_callback() {
        $options = get_option( 'hbipa_leaderboard_ad_settings' );
        
        if( 0 == $options['leaderboard_height'] || 0 == $options['leaderboard_width'] ) {
            echo '<h3>Please select a leaderboard height and width before trying to set the default leaderboard ad.</h3>';
            return;
        }
        
        $leaderboard_ads = $this->get_ads_by_height_width( $options['leaderboard_width'], $options['leaderboard_height'] );
        if( empty( $leaderboard_ads ) ) {
            echo "<h3>No leaderboard ads found with the above height and width.</h3>";
            return;
        }
        ?>
        
        <select name="hbipa_leaderboard_ad_settings[default_leaderboard_ad_unit]">
            <option value=""></option>
            <?php foreach( $leaderboard_ads as $leaderboard_ad ) : ?>
                <option <?php selected( $options['default_leaderboard_ad_unit'], $leaderboard_ad->post_title ); ?> value="<?php echo $leaderboard_ad->post_title; ?>"><?php echo $leaderboard_ad->post_title; ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }
        
    function sanitize_leaderboard_settings( $input ) {
        $new_input = array();
        
        $new_input['leaderboard_width'] = absint( $input['leaderboard_width'] );
        $new_input['leaderboard_height'] = absint( $input['leaderboard_height'] );
        $new_input['default_leaderboard_ad_unit'] = sanitize_text_field( $input['default_leaderboard_ad_unit'] );
        
        return $new_input;
    }
    
    function sanitize_display_settings( $input ) {
        $new_input = array();
        
        $new_input['show_expanding_ad'] = absint( $input['show_expanding_ad'] );
        $new_input['show_wrap_ads'] = absint( $input['show_wrap_ads'] );
        $new_input['show_leaderboard_ad'] = absint( $input['show_leaderboard_ad'] );
        
        return $new_input;
    }
    
    function sanitize_wrap_ads( $input ) {
        $new_input = array();
        
        $new_input['default_width'] = absint( $input['default_width'] );
        $new_input['default_height'] = absint( $input['default_height'] );
        $new_input['default_left_ad_unit'] = sanitize_text_field( $input['default_left_ad_unit'] );
        $new_input['default_right_ad_unit'] = sanitize_text_field( $input['default_right_ad_unit'] );
        
        return $new_input;
    }
    
    function sanitize_expanding_ads( $input ) {
        $new_input = array();
        
        $new_input['default_small_width'] = absint( $input['default_small_width'] );
        $new_input['default_small_height'] = absint( $input['default_small_height'] );
        $new_input['default_large_width'] = absint( $input['default_large_width'] );
        $new_input['default_large_height'] = absint( $input['default_large_height'] );
        $new_input['default_small_ad_unit'] = sanitize_text_field( $input['default_small_ad_unit'] );
        $new_input['default_large_ad_unit'] = sanitize_text_field( $input['default_large_ad_unit'] );
        
        return $new_input;
    }
    
    function default_wrap_ad_size_callback(){
        $options = get_option( 'hbipa_wrap_ad_settings' ); 
        ?>
        <table>
            <tr>
                <td>
                    <input type="text" name="hbipa_wrap_ad_settings[default_width]" value="<?php echo $options['default_width']; ?>" /> <br />
                    <span class="description">width</span>
                </td>
                <td>
                    <input type="text" name="hbipa_wrap_ad_settings[default_height]" value="<?php echo $options['default_height']; ?>" /><br />
                    <span class="description">height</span>
                </td>
            </tr>
        </table>
        <?php
    }
    
    function default_wrap_ad_unit_callback() {
        $options = get_option( 'hbipa_wrap_ad_settings' ); 
        if( 0 == $options['default_width'] || 0 == $options['default_height'] ) {
            echo 'Please enter a default ad size before trying to select an ad unit.';
            return false;
        }
        
        $wrap_ads = $this->get_ads_by_height_width( $options['default_width'], $options['default_height'] );
        if( empty( $wrap_ads ) ){
            echo 'There are no ad units created that have the width and height specified.';
            return false;
        }
        
        $left_wrap = $options['default_left_ad_unit'];
        $right_wrap = $options['default_right_ad_unit'];
        ?>
        <p>
            <label for="default_left_wrap_ad">Default Left Ad Unit: </label>
            <select id="default_left_wrap_ad" name="hbipa_wrap_ad_settings[default_left_ad_unit]">
                <option></option>
                <?php foreach( $wrap_ads as $wrap_ad ) : ?>
                    <option <?php selected( $wrap_ad->post_title, $left_wrap ); ?> value="<?php echo $wrap_ad->post_title; ?>"><?php echo $wrap_ad->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="default_right_ad">Default Right Ad Unit: </label>
            <select id="default_right_ad" name="hbipa_wrap_ad_settings[default_right_ad_unit]">
                <option></option>
                <?php foreach( $wrap_ads as $wrap_ad ) : ?>
                    <option <?php selected( $wrap_ad->post_title, $right_wrap ); ?> value="<?php echo $wrap_ad->post_title; ?>"><?php echo $wrap_ad->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }
       
    function show_expanding_ads_sizes_callback() {
        $options = get_option('hbipa_expanding_ad_settings');
        ?>
        <p>
            <label for="small_width">Collapsed Ad Width: </label>
            <input id="small_width" type="text" name="hbipa_expanding_ad_settings[default_small_width]" value="<?php echo $options['default_small_width']; ?>" />
        </p>
        <p>
            <label for="small_height">Collapsed Ad Height: </label>
            <input id="small_height" type="text" name="hbipa_expanding_ad_settings[default_small_height]" value="<?php echo $options['default_small_height']; ?>" />
        </p>
        <p>
            <label for="large_width">Expanded Ad Width: </label>
            <input id="large_width" type="text" name="hbipa_expanding_ad_settings[default_large_width]" value="<?php echo $options['default_large_width']; ?>" />
        </p>
        <p>
            <label for="large_height">Expanded Ad Width: </label>
            <input id="large_height" type="text" name="hbipa_expanding_ad_settings[default_large_height]" value="<?php echo $options['default_large_height']; ?>" />
        </p>
        <?php
    }
    
    function default_small_expanding_ad_callback() {
        $options = get_option('hbipa_expanding_ad_settings');
        
        $ad_height = $options['default_small_height'];
        $ad_width = $options['default_small_width'];
        
        if( 0 == $ad_width || 0 == $ad_height ) {
            echo 'Please enter a height and width before selecting an ad unit.';
            return;
        }
        
        $expanding_ads = $this->get_ads_by_height_width( $ad_width, $ad_height );
        
        if( !empty ( $expanding_ads ) ) : ?>
            <select name="hbipa_expanding_ad_settings[default_small_ad_unit]">
                <option value=""></option>
                <?php foreach( $expanding_ads as $expanding_ad ) : ?>
                    <option <?php selected( $expanding_ad->post_title, $options['default_small_ad_unit'] ); ?> value="<?php echo $expanding_ad->post_title; ?>"><?php echo $expanding_ad->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        <?php else: 
            echo "Please create a $ad_width x $ad_height ad unit before trying to select a default ad unit.";
        endif;
    }

    function default_large_expanding_ad_callback() {
        $options = get_option('hbipa_expanding_ad_settings');
        if( 0 == $options['default_large_width'] || 0 == $options['default_large_width'] ) {
            echo 'Please enter a height and width before selecting an ad unit.';
            return false;
        }
        
        $ad_height = $options['default_large_height'];
        $ad_width = $options['default_large_width'];
        
        $expanding_ads = $this->get_ads_by_height_width( $ad_width, $ad_height );
        
        if( !empty ( $expanding_ads ) ) : ?>
            <select name="hbipa_expanding_ad_settings[default_large_ad_unit]">
                <option value=""></option>
                <?php foreach( $expanding_ads as $expanding_ad ) : ?>
                    <option <?php selected( $expanding_ad->post_title, $options['default_large_ad_unit'] ); ?> value="<?php echo $expanding_ad->post_title; ?>"><?php echo $expanding_ad->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        <?php else: 
            echo "Please create a $ad_width x $ad_height ad unit before trying to select a default ad unit.";
        endif;
    }

    function show_expanding_ad_callback() {
        $options = get_option('hbipa_display_settings');
        ?><input type="checkbox" name="hbipa_display_settings[show_expanding_ad]" value="1" <?php checked( $options['show_expanding_ad'], 1, true ); ?> /><?php
    }

    function show_wrap_ads_callback() {
        $options = get_option('hbipa_display_settings');
        ?><input type="checkbox" name="hbipa_display_settings[show_wrap_ads]" value="1" <?php checked( $options['show_wrap_ads'], 1, true ); ?> /><?php
    }
    
    function show_leaderboard_ad_callback() {
        $options = get_option('hbipa_display_settings');
        ?><input type="checkbox" name="hbipa_display_settings[show_leaderboard_ad]" value="1" <?php checked( $options['show_leaderboard_ad'], 1, true ); ?> /><?php
    }
    
    function get_ads_by_height_width( $width, $height ) {
        $args = array(
            'post_type' => 'ad_unit',
            'order_by' => 'title',
            'order' => 'ASC',
            'posts_per_page' => '-1',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'ad_width',
                    'value' => $width
                ),
                array(
                    'key' => 'ad_height',
                    'value' => $height
                )
            )
        );
        
        return get_posts( $args );
    }
    
    
    /**
     * Register the Premium Ads metabox using the HBI Meta Box library
     */
    public function register_hbi_premium_ads_metabox() {
        add_meta_box( 'hbipa_meta_box', 'HBI Premium Ads - On Page Customizations', array( $this, 'hbipa_metabox' ), 'page', 'normal', 'high' );
        add_meta_box( 'hbipa_meta_box', 'HBI Premium Ads - On Post Customizations', array( $this, 'hbipa_metabox' ), 'post', 'normal', 'high' );
    }
    
    /**
     * This is the meta box form that will automatically be imported into the HBI Meta Box Library
     */
    public function hbipa_metabox( $post ) {
        
        wp_nonce_field( 'hbipa_meta_box', 'hbipa_meta_box_nonce' );
        
        /**
         * Load all of the settings we're going to need for this beast
         */
        $display_settings = get_option('hbipa_display_settings');
        $expanding_settings = get_option( 'hbipa_expanding_ad_settings');
        $wrap_settings = get_option( 'hbipa_wrap_ad_settings' );
        $leaderboard_setings = get_option( 'hbipa_leaderboard_ad_settings' );
        /**
         * Load all of the actual ad slots we're going to need for this beast
         */
        $small_expanding_ads = $this->get_ads_by_height_width( $expanding_settings['default_small_width'] , $expanding_settings['default_small_height'] );
        $large_expanding_ads = $this->get_ads_by_height_width( $expanding_settings['default_large_width'] , $expanding_settings['default_large_height'] );
        $wrap_ads = $this->get_ads_by_height_width( $wrap_settings['default_width'] , $wrap_settings['default_height'] );
        $leaderboard_ads = $this->get_ads_by_height_width( $leaderboard_setings['leaderboard_width'] , $leaderboard_setings['leaderboard_height'] );
        $themeta = get_post_meta( $post->ID );
        $takeovers = get_posts( array( 'post_type' => 'takeover' ) );
        ?>
         <table cellpadding="3" cellspacing="3" border="0">
            <tbody>
                <?php if( !empty( $takeovers ) && 'takeover' != get_post_type( $post->ID ) ): ?>
                <tr>
                    <th><label for="takeover">Takeover: </label></th>
                    <td>
                        <select name="takeover">
                            <option value=""></option>
                            <?php foreach( $takeovers as $takeover ) : ?>
                            <option <?php selected( $themeta['takeover'][0], $takeover->ID ); ?> value="<?php echo $takeover->ID; ?>"><?php echo $takeover->post_title; ?></option>    
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if( $display_settings['show_expanding_ad'] ) : ?>
                <tr>
                    <th><label for="hide_expanding_ad">Hide Expanding Ad: </label></th>
                    <td><input <?php checked( $themeta['hide_expanding_ad'][0], 1 ); ?> id="hide_expanding_ad" type="checkbox" name="hide_expanding_ad" value="1" /></td>
                </tr>
                <tr class="hide_if_expanding_hidden">
                    <th><label for="use_custom_expanding_ad">Use Custom Expanding Ad: </label></th>
                    <td><input <?php checked( $themeta['use_custom_expanding_ad'][0], 1 ); ?> id="use_custom_expanding_ad" type="checkbox" name="use_custom_expanding_ad" value="1" /></td>
                </tr>
                <tr class="hide_if_expanding_hidden">
                    <th><label for="custom_small_expanding_ad">Custom Small Expanding Ad: </label></th>
                    <td>
                        <?php if( !empty( $small_expanding_ads ) ) : ?>
                            <select name="custom_small_expanding_ad">
                                <option value=""></option>
                                <?php foreach( $small_expanding_ads as $expanding_ad ) : ?>
                                    <option <?php selected( $expanding_ad->post_title, $themeta['custom_small_expanding_ad'][0] ); ?> value="<?php echo $expanding_ad->post_title; ?>"> <?php echo $expanding_ad->post_title;; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: 
                            echo "Please create a $small_width x $small_height ad unit before trying to select a default ad unit.";
                        endif; ?>
                    </td>
                </tr>
                <tr class="hide_if_expanding_hidden">
                    <th><label for="custom_large_expanding_ad">Custom Large Expanging Ad Ad: </label></th>
                    <td>
                        <?php if( !empty( $large_expanding_ads ) ) : ?>
                            <select name="custom_large_expanding_ad">
                                <option value=""></option>
                                <?php foreach( $large_expanding_ads as $expanding_ad ) : ?>
                                    <option <?php selected( $expanding_ad->post_title, $themeta['custom_large_expanding_ad'][0] ); ?> value="<?php echo $expanding_ad->post_title; ?>"> <?php echo $expanding_ad->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: 
                            echo "Please create a $small_width x $small_height ad unit before trying to select a default ad unit.";
                        endif; ?>
                </tr>
                    <?php endif; ?>
                <?php if( $display_settings['show_wrap_ads'] ) : ?>
                <tr>
                    <th><label for="hide_wrap_ads">Hide Wrap Ads: </label></th>
                    <td><input <?php checked( $themeta['hide_wrap_ads'][0], 1 ); ?> id="hide_wrap_ads" type="checkbox" name="hide_wrap_ads" value="1" /></td>
                </tr>
                <tr class="hide_if_wrap_hidden">
                    <th><label for="use_custom_wrap_ads">Use Custom Wrap Ads: </label></th>
                    <td><input <?php checked( $themeta['use_custom_wrap_ads'][0], 1 ); ?> id="use_custom_wrap_ads" type="checkbox" name="use_custom_wrap_ads" value="1" /></td>
                </tr>
                <tr class="hide_if_wrap_hidden">
                    <th><label for="custom_wrap_left_ad_unit">Custom Left Wrap Ad: </label></th>
                    <td>
                        <?php if( !empty( $wrap_ads ) ) : ?>
                            <select name="custom_wrap_left_ad_unit">
                                <option value=""></option>
                                <?php foreach( $wrap_ads as $wrap_ad ) : ?>
                                    <option <?php selected( $wrap_ad->post_title, $themeta['custom_wrap_left_ad_unit'][0] ); ?> value="<?php echo $wrap_ad->post_title; ?>"> <?php echo $wrap_ad->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: 
                            echo "Please create a $small_width x $small_height ad unit before trying to select a custom left wrap ad unit.";
                        endif; ?>
                    </td>
                </tr>
                <tr class="hide_if_wrap_hidden">
                    <th><label for="custom_wrap_right_ad_unit">Custom Right Wrap Ad: </label></th>
                    <td>
                        <?php if( !empty( $wrap_ads ) ) : ?>
                            <select name="custom_wrap_right_ad_unit">
                                <option value=""></option>
                                <?php foreach( $wrap_ads as $wrap_ad ) : ?>
                                    <option <?php selected( $wrap_ad->post_title, $themeta['custom_wrap_right_ad_unit'][0] ); ?> value="<?php echo $wrap_ad->post_title; ?>"> <?php echo $wrap_ad->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: 
                            echo "Please create a $small_width x $small_height ad unit before trying to select a custom left wrap ad unit.";
                        endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if( $display_settings['show_leaderboard_ad'] ) : ?>
                <tr>
                    <th><label for="hide_leaderboard_ad">Hide Leaderboard Ad: </label></th>
                    <td><input <?php checked( $themeta['hide_leaderboard_ad'][0], 1 ); ?> id="hide_leaderboard_ad" type="checkbox" name="hide_leaderboard_ad" value="1" /></td>
                </tr>
                <tr class="hide_if_leaderboard_hidden">
                    <th><label for="use_custom_leaderboard_ad">Use Leaderboard Ad: </label></th>
                    <td><input <?php checked( $themeta['use_custom_leaderboard_ad'][0], 1 ); ?> id="use_custom_leaderboard_ad" type="checkbox" name="use_custom_leaderboard_ad" value="1" /></td>
                </tr>
                <tr class="hide_if_leaderboard_hidden">
                    <th><label for="custom_leaderboard_ad_unit">Custom Leaderboard Ad Unit: </label></th>
                    <td>
                        <?php if( !empty( $leaderboard_ads ) ) : ?>
                            <select id="custom_leaderboard_ad_unit" name="custom_leaderboard_ad_unit">
                                <option value=""></option>
                                <?php foreach( $leaderboard_ads as $leaderboard_ad ) : ?>
                                    <option <?php selected( $leaderboard_ad->post_title, $themeta['custom_leaderboard_ad_unit'][0] ); ?> value="<?php echo $leaderboard_ad->post_title; ?>"> <?php echo $leaderboard_ad->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: 
                            echo "Please create a $small_width x $small_height ad unit before trying to select a custom leadeboard ad unit.";
                        endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
    
    public function validate_metaboxes( $post_id ) {
        
        /**
         * If this is an autosave, our form has not been submitted, so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
		
		if( isset( $_POST['post_type'] ) ):
	        if( 'takeover' == $_POST['post_type'] ) {
	            $this->validate_takeover_metabox( $post_id, $_POST );
	            $this->validate_premium_ads_metabox( $post_id, $_POST );
	        }
	        
	        if( 'post' == $_POST['post_type'] || 'page' == $_POST['post_type'] ) {
	            $this->validate_premium_ads_metabox( $post_id, $_POST );
	        }
		endif;
        
    }
    
    function validate_premium_ads_metabox( $post_id, $post_vars ) {
         /**
         * Check if our nonce is set and verify it's value
         */
        if ( ! isset( $_POST['hbipa_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['hbipa_meta_box_nonce'], 'hbipa_meta_box' ) ) 
            return;
        
        $takeover = absint( $post_vars['takeover'] );
    
        $hide_wrap_ads = absint( $post_vars['hide_wrap_ads'] );
        $hide_expanding_ad = absint( $post_vars['hide_expanding_ad'] );
        $hide_leaderboard_ad = absint( $post_vars['hide_leaderboard_ad'] );
        
        $use_custom_wrap_ads = absint( $post_vars['use_custom_wrap_ads'] );
        $use_custom_expanding_ad = absint( $post_vars['use_custom_expanding_ad'] );
        $use_custom_leaderboard_ad = absint( $post_vars['use_custom_leaderboard_ad'] );
        
        $custom_small_expanding_ad = sanitize_text_field( $post_vars['custom_small_expanding_ad'] );
        $custom_large_expanding_ad = sanitize_text_field( $post_vars['custom_large_expanding_ad'] );
        $custom_wrap_left_ad_unit = sanitize_text_field( $post_vars['custom_wrap_left_ad_unit'] );
        $custom_wrap_right_ad_unit = sanitize_text_field( $post_vars['custom_wrap_right_ad_unit'] );
        $custom_leaderboard_ad_unit = sanitize_text_field( $post_vars['custom_leaderboard_ad_unit'] );
        
        update_post_meta( $post_id, 'takeover', $takeover );
        update_post_meta( $post_id, 'hide_wrap_ads', $hide_wrap_ads );
        update_post_meta( $post_id, 'hide_expanding_ad', $hide_expanding_ad );
        update_post_meta( $post_id, 'hide_leaderboard_ad', $hide_leaderboard_ad );
        update_post_meta( $post_id, 'use_custom_wrap_ads', $use_custom_wrap_ads );
        update_post_meta( $post_id, 'use_custom_expanding_ad', $use_custom_expanding_ad );
        update_post_meta( $post_id, 'use_custom_leaderboard_ad', $use_custom_leaderboard_ad );
        update_post_meta( $post_id, 'custom_small_expanding_ad', $custom_small_expanding_ad );
        update_post_meta( $post_id, 'custom_large_expanding_ad', $custom_large_expanding_ad );
        update_post_meta( $post_id, 'custom_wrap_left_ad_unit', $custom_wrap_left_ad_unit );
        update_post_meta( $post_id, 'custom_wrap_right_ad_unit', $custom_wrap_right_ad_unit );
        update_post_meta( $post_id, 'custom_leaderboard_ad_unit', $custom_leaderboard_ad_unit );
    }

    function validate_takeover_metabox( $post_id, $post_vars ) {
        if ( ! isset( $_POST['hbipa_takeover_metabox_security'] ) || ! wp_verify_nonce( $_POST['hbipa_takeover_metabox_security'], 'hbipa_takeover_metabox' ) ) 
            return;
        
        foreach( $post_vars as $key => $val ) {
            $$key = sanitize_text_field( $val );
        }
        
        update_post_meta( $post_id, 'start_date', $start_date );
        update_post_meta( $post_id, 'start_time', $start_time );
        update_post_meta( $post_id, 'end_date', $end_date );
        update_post_meta( $post_id, 'end_time', $end_time );
        update_post_meta( $post_id, 'background_position_x', $background_position_x );
        update_post_meta( $post_id, 'background_position_y', $background_position_y );
        update_post_meta( $post_id, 'background_repeat', $background_repeat );
        update_post_meta( $post_id, 'background_attachment', $background_attachment );
        update_post_meta( $post_id, 'background_color', $background_color );
        update_post_meta( $post_id, 'background_image', $background_image );
    }

    function register_takeover_post_type() {
        $labels = array(
            'name'                => 'Takeovers',
            'singular_name'       => 'Takeover',
            'menu_name'           => 'Takeovers',
            'parent_item_colon'   => 'Parent Takeover:',
            'all_items'           => 'All Takeovers',
            'view_item'           => 'View Takeover',
            'add_new_item'        => 'Add New Takeover',
            'add_new'             => 'Add New',
            'edit_item'           => 'Edit Takeover',
            'update_item'         => 'Update Takeover',
            'search_items'        => 'Search Takeover',
            'not_found'           => 'Not found',
            'not_found_in_trash'  => 'Not found in Trash',
        );
        $args = array(
            'label'                 => 'takeover',
            'description'           => 'Takeovers allow for customization of a page\'s background and premium ad slots during a specific time period',
            'labels'                => $labels,
            'supports'              => array( 'title', 'custom-fields', ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => 'edit.php?post_type=ad_unit',
            'show_in_nav_menus'     => false,
            'show_in_admin_bar'     => false,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-admin-page',
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'rewrite'               => false,
            'capability_type'       => 'page',
            'register_meta_box_cb'  => array( $this, 'add_takeover_metaboxes' )
        );
        register_post_type( 'takeover', $args );
    }

    function add_takeover_metaboxes() {
        add_meta_box( 'hbipa-takeovers', 'Page Takeovers', array( $this, 'hbipa_metabox' ), 'page' );
        add_meta_box( 'hbipa-takeovers', 'Post Takeovers', array( $this, 'hbipa_metabox' ), 'post' );
        add_meta_box( 'hbipa-takeover-background-settings', 'Takeover Settings', array( $this, 'takeover_settings_metabox' ), 'takeover' );
        add_meta_box( 'hbipa-takeovers', 'Takeover Ad Settings', array( $this, 'hbipa_metabox' ), 'takeover' );
    }
    
    /**
     * The meta box that allows for the creation of a meta box
     */
    function takeover_settings_metabox( $post ) {
        $post_meta = get_post_meta( $post->ID );
        wp_enqueue_media();
        wp_nonce_field( 'hbipa_takeover_metabox', 'hbipa_takeover_metabox_security' );
        ?>
        <p>
            <label>Takeover start date/time</label>
            <input type="text" name="start_date" class="datepicker" value="<?echo $post_meta['start_date'][0]; ?>" />
            <input type="text" name="start_time" value="<?echo $post_meta['start_time'][0]; ?>" />
        </p>
        <p>
            <label>Takeover end date/time</label>
            <input type="text" name="end_date" class="datepicker" value="<?echo $post_meta['end_date'][0]; ?>" />
            <input type="text" name="end_time" value="<?echo $post_meta['end_time'][0]; ?>" />
        </p>
        <p>
            <label class="bold">Background Position: </label>
            <select name="background_position_x">
                <option value=""></option>
                <option <?php selected( 'left', $post_meta['background_position_x'][0] ); ?> value="top">Top</option>
                <option <?php selected( 'center', $post_meta['background_position_x'][0] ); ?> value="center">Center</option>
                <option <?php selected( 'right', $post_meta['background_position_x'][0] ); ?> value="bottom">Bottom</option>
            </select>
            <select name="background_position_y">
                <option value=""></option>
                <option <?php selected( 'left', $post_meta['background_position_y'][0] ); ?> value="left">Left</option>
                <option <?php selected( 'center', $post_meta['background_position_y'][0] ); ?> value="center">Center</option>
                <option <?php selected( 'right', $post_meta['background_position_y'][0] ); ?> value="right">Right</option>
            </select>
        </p>
        <p>
            <label class="bold">Background Repeat</label>
            <select name="background_repeat">
                <option value=""></option>
                <option <?php selected( 'no-repeat', $post_meta['background_repeat'][0] ); ?> value="no-repeat">no-repeat</option>
                <option <?php selected( 'repeat', $post_meta['background_repeat'][0] ); ?> value="repeat">repeat</option>
                <option <?php selected( 'repeat-x', $post_meta['background_repeat'][0] ); ?> value="repeat-x">repeat-x</option>
                <option <?php selected( 'repeat-y', $post_meta['background_repeat'][0] ); ?> value="repeat-y">repeat-y</option>
            </select>
        </p>
        <p>
            <label class="bold">Background Attachment</label>
            <select name="background_attachment">
                <option value=""></option>
                <option <?php selected( 'fixed', $post_meta['background_attachment'][0] ); ?> value="fixed">Fixed</option>
                <option <?php selected( 'scroll', $post_meta['background_attachment'][0] ); ?> value="scroll">Scroll</option>
            </select>
        </p>
        <p>
            <label class="bold">Background Color</label>
            <input type="text" name="background_color" value="<?php echo $post_meta['background_color'][0]; ?>" class="my-color-field" />
        </p>
        <p>
            <label class="bold">Background Image</label>
            <input id="background_image" type="text" name="background_image" value="<?php echo $post_meta['background_image'][0]; ?>" />
            <span data-uploader_title="Select a custom background for this takeover" class="button upload_image_button">Upload</span>
        </p>
        <?php
    }
}