<?php
    /*
     Plugin Name: PYD SmugMug Slider
     Plugin URI: http://pokayoke.co
     Description: Using the amazing work of FlexSlider http://www.woothemes.com/flexslider/ and phpSmug http://phpsmug.com/ this plugin creates allows you to drop in an image gallery from your SmugMug account, either as a slider or thumbnails to any post or page.  It includes a shortcode generator and options panel.
     Version: 1.01
     Author: Michael Gyura
     Author URI: http://gyura.com
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
            'animate' => $pydsmug_slider['animate'],
            'startup' => $pydsmug_slider['startup'],
            'smoothtall' => $pydsmug_slider[ 'smoothheight' ],
            'locationicon' => $pydsmug_slider[ 'locationmarkers' ],
            'navdirection' => $pydsmug_slider[ 'nextarrows' ],
            'loopit' => $pydsmug_slider[ 'loopit' ],
            'slidespeed' => $pydsmug_slider[ 'cycletime' ],
            'animatespeed' => $pydsmug_slider[ 'animatetime' ],
            'delayinit' => 0,
            'randomizeit' => $pydsmug_slider[ 'randomit' ],
            'hoverpause' => $pydsmug_slider[ 'pausehover' ],
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
    $pydsmug_pydapi   = new phpSmug( "APIKey=9D8IdL53PxaZoZeCzDGLVMQIaYF9Sg6s", "AppName=Poka Yoke Design", "OAuthSecret=99460e933382584b6e6cebfb392f749d" );
