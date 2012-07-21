<?php
    /*
     Plugin Name: PYD SmugMug Slider
     Plugin URI: http://pokayoke.co
     Description: Using the amazing work of Woo Themes Flex Slider http://www.woothemes.com/flexslider/ and phpSmug http://phpsmug.com/ this plugin creates a shortcode generator that displays a responsive slider from a SmugMug album.
     Version: 1.1
     Author: Michael Gyura
     Author URI: http://pokayoke.co
    */


    /*-----------------------------------------------------------------------------------*/
    /* Bring in required files and scripts */
    /*-----------------------------------------------------------------------------------*/

    require_once( dirname( __FILE__ ) . '/includes/pyd-settings.php' );
    require_once( dirname( __FILE__ ) . '/includes/lib/phpSmug/phpSmug.php' );
    require_once( dirname( __FILE__ ) . '/includes/pyd-smug_shortcode.php' );


    add_action( 'wp_enqueue_scripts', 'pyd_smugslider_register_scripts' );
    add_action( 'wp_footer', 'pyd_smugslider_print_scripts' );

    function pyd_smugslider_register_scripts() {
        wp_register_style( 'pydsmugstyles', plugins_url( '/includes/lib/FlexSlider/flexslider.css', __FILE__ ) );

        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', null, '1.7.2' );

        wp_register_script( 'pydsmugflex', plugins_url( '/includes/lib/FlexSlider/jquery.flexslider.js', __FILE__ ), array( 'jquery' ), 1, true );

        wp_register_script( 'pydsmugflexOptions', plugins_url( '/includes/lib/FlexSlider/pyd-smugslider_options.js', __FILE__ ), array( 'jquery' ), 1, true );

    }

    function pyd_smugslider_print_scripts() {
        global $add_my_script;

        if ( !$add_my_script ) {
            return;
        }

        wp_enqueue_style( 'pydsmugstyles' );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'pydsmugflex' );
        wp_enqueue_script( 'pydsmugflexOptions' );
    }


    /*-----------------------------------------------------------------------------------*/
    /* Call register settings function */
    /*-----------------------------------------------------------------------------------*/

    function pyd_smugslider_settings() {

        register_setting( 'pyd-smugslider-api-group', 'pyd_smug_api' );
        register_setting( 'pyd-smugslider-api-group', 'pyd_smug_api_progress' );
        register_setting( 'pyd-smugslider-settings-group', 'pyd_smug_cats' );

    }

    add_action( 'admin_init', 'pyd_smugslider_settings' );


    /*-----------------------------------------------------------------------------------*/
    /* Setup functions to use */
    /*-----------------------------------------------------------------------------------*/

    $pydsmug_api      = get_option( 'pyd_smug_api' );
    $pydsmug_progress = get_option( 'pyd_smug_api_progress' );
    $pydsmug_cats     = get_option( 'pyd_smug_cats' );
    $pydsmug_pydapi = new phpSmug( "APIKey=9D8IdL53PxaZoZeCzDGLVMQIaYF9Sg6s", "AppName=Poka Yoke Design", "OAuthSecret=99460e933382584b6e6cebfb392f749d" );
