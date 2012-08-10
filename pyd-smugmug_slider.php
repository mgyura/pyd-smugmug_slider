<?php
    /*
     Plugin Name: SmugMug Responsive Slider
     Plugin URI: http://gyura.com/smugmug-responsive-slider/
     Description: Using FlexSlider and phpSmug this plugin allows you to drop in an image gallery from your SmugMug account to any post or page, either as a responsive slider or thumbnails.  It includes a shortcode generator and options panel.
     Version: 1.05
     Author: Michael Gyura
     Author URI: http://gyura.com
    */

    /*  Copyright 2012  Michael Gyura  (email : michael@gyura.com)

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License, version 2, as
        published by the Free Software Foundation.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    */
    /*-----------------------------------------------------------------------------------*/
    /* Bring in required files and scripts */
    /*-----------------------------------------------------------------------------------*/

    require_once( dirname( __FILE__ ) . '/includes/pyd-settings.php' );
    require_once( dirname( __FILE__ ) . '/includes/lib/phpSmug/phpSmug.php' );
    require_once( dirname( __FILE__ ) . '/includes/pyd-smug_shortcode.php' );


    add_action( 'wp_enqueue_scripts', 'pyd_smugslider_register_scripts' );
    add_action( 'wp_footer', 'pyd_smugslider_print_scripts' );
    add_action( 'admin_enqueue_scripts', 'pyd_smugslider_admin_scripts' );

    function pyd_smugslider_admin_scripts() {
        wp_register_style( 'pydsmugstyles', plugins_url( '/includes/lib/style.css', __FILE__ ) );
        wp_enqueue_style( 'pydsmugstyles' );
    }

    function pyd_smugslider_register_scripts() {
        wp_register_style( 'pydsmugsliderstyles', plugins_url( '/includes/lib/FlexSlider/flexslider.css', __FILE__ ) );

        wp_register_script( 'pydsmugflex', plugins_url( '/includes/lib/FlexSlider/jquery.flexslider.js', __FILE__ ), array( 'jquery' ), 1, true );

        wp_register_script( 'pydsmugflexOptions', plugins_url( '/includes/lib/FlexSlider/pyd-smugslider_options.js', __FILE__ ), array( 'jquery' ), 1, true );

    }

    function pyd_smugslider_print_scripts() {
        global $add_my_script, $pydsmug_slider;

        if ( !$add_my_script ) {
            return;
        }

        wp_enqueue_style( 'pydsmugsliderstyles' );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'pydsmugflex' );
        wp_enqueue_script( 'pydsmugflexOptions' );

        $pydsmug_slider_variables = array(
            'animate'      => $pydsmug_slider[ 'animate' ],
            'startup'      => $pydsmug_slider[ 'startup' ],
            'smoothtall'   => $pydsmug_slider[ 'smoothheight' ],
            'locationicon' => $pydsmug_slider[ 'locationmarkers' ],
            'navdirection' => $pydsmug_slider[ 'nextarrows' ],
            'loopit'       => $pydsmug_slider[ 'loopit' ],
            'slidespeed'   => $pydsmug_slider[ 'cycletime' ],
            'animatespeed' => $pydsmug_slider[ 'animatetime' ],
            'delayinit'    => 0,
            'randomizeit'  => $pydsmug_slider[ 'randomit' ],
            'hoverpause'   => $pydsmug_slider[ 'pausehover' ],
        );

        wp_localize_script( 'pydsmugflexOptions', 'pydsmug', $pydsmug_slider_variables );
    }


    /*-----------------------------------------------------------------------------------*/
    /* Call register settings function */
    /*-----------------------------------------------------------------------------------*/

    function pyd_smugslider_settings() {

        register_setting( 'pyd-smugslider-api-group', 'pyd_smug_api' );
        register_setting( 'pyd-smugslider-api-group', 'pyd_smug_api_progress' );
        register_setting( 'pyd-smugslider-settings-group', 'pyd_smug_cats' );
        register_setting( 'pyd-smugslider-settings-group', 'pyd_smug_slider' );

    }

    add_action( 'admin_init', 'pyd_smugslider_settings' );


    /*-----------------------------------------------------------------------------------*/
    /* Setup functions to use */
    /*-----------------------------------------------------------------------------------*/

    $pydsmug_api      = get_option( 'pyd_smug_api' );
    $pydsmug_progress = get_option( 'pyd_smug_api_progress' );
    $pydsmug_cats     = get_option( 'pyd_smug_cats' );
    $pydsmug_slider   = get_option( 'pyd_smug_slider' );
    $pydsmug_pydapi   = new pyd_phpSmug( "APIKey=9D8IdL53PxaZoZeCzDGLVMQIaYF9Sg6s", "AppName=Poka Yoke Design", "OAuthSecret=99460e933382584b6e6cebfb392f749d" );


    /*-----------------------------------------------------------------------------------*/
    /* Admin Message when plugin needs to be authorized by SmugMug */
    /*-----------------------------------------------------------------------------------*/


    function showMessage() {
        global $pydsmug_progress;
        if ( $pydsmug_progress != 4 ) {
            echo '<div id="message" class="error"><p><strong>SmugMug Responsive Slider needs to be authorized before use.  To start the process, please <a href="/wp-admin/options-general.php?page=smugmug-settings" title="authorize SmugMug Slider">click here</a></strong></p></div>';
        }
    }

    function showAdminMessages() {
        showMessage( "SmugMug Responsive Slider needs to be authorized before it will work.", true );
    }

    add_action( 'admin_notices', 'showAdminMessages' );


    /*-----------------------------------------------------------------------------------*/
    /* Activation Hook.  Check WP Version */
    /*-----------------------------------------------------------------------------------*/

    register_activation_hook( __FILE__, 'pydsmug_activation_shit' );

    function pydsmug_activation_shit() {
        global $wp_version;

        if ( version_compare( $wp_version, "3.2", "<" ) ) {

            deactivate_plugins( basename( __file__ ) );
            wp_die( "This plugin requires WordPress version 3.2 or higher." );
        }

        add_option(
            'pyd_smug_slider', array(
                                    'animate'         => 'fade',
                                    'smoothheight'    => 1,
                                    'locationmarkers' => 1,
                                    'nextarrows'      => 1,
                                    'loopit'          => 1,
                                    'randomit'        => '',
                                    'pausehover'      => '',
                                    'startup'         => 1,
                                    'cycletime'       => 7000,
                                    'animatetime'     => 600
                               )
        );
    }

