<?php
    /**
     * User: mgyura
     * Date: 7/31/12
     */

    if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        exit();
    }

    else {
        delete_option( 'pyd_smug_api' );
        delete_option( 'pyd_smug_api_progress' );
        delete_option( 'pyd_smug_cats' );
        delete_option( 'pyd_smug_slider' );

        unregister_setting( 'pyd-smugslider-api-group', 'pyd_smug_api' );
        unregister_setting( 'pyd-smugslider-api-group', 'pyd_smug_api_progress' );
        unregister_setting( 'pyd-smugslider-settings-group', 'pyd_smug_cats' );
        unregister_setting( 'pyd-smugslider-settings-group', 'pyd_smug_slider' );
    }



