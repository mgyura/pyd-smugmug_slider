<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: mgyura
     * Date: 6/26/12
     */


    /*-----------------------------------------------------------------------------------*/
    /* Collect the default organizational settings */
    /*-----------------------------------------------------------------------------------*/

    function pyd_smugslider_option_settings() {
        echo '<div class="wrap">';
        echo '<h2>SmugMug Slider Settings</h2>';
        echo '<p>Settings for the SmugMug Slider Plugin</p>'
        ?>
    <form method="post" action="options.php">
        <?php settings_fields( 'pyd-smugslider-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">SmugMug API Key</th>
                <td><input size="50" type="password" name="pyd_smug_api"
                           value="<?php echo get_option( 'pyd_smug_api' ); ?>" />
                </td>
            </tr>
        <tr valign="top">
                <th scope="row">SmugMug API Secret</th>
                <td><input size="50" type="password" name="pyd_smug_api_secret"
                           value="<?php echo get_option( 'pyd_smug_api_secret' ); ?>" />
                </td>
            </tr>
        <tr valign="top">
                <th scope="row">SmugMug App Name</th>
                <td><input size="50" type="text" name="pyd_smug_app_name"
                           value="<?php echo get_option( 'pyd_smug_app_name' ); ?>" />
                </td>
            </tr>
        <tr valign="top">
                <th scope="row">SmugMug App URL</th>
                <td><input size="50" type="text" name="pyd_smug_app_url"
                           value="<?php echo get_option( 'pyd_smug_app_url' ); ?>" />
                </td>
            </tr>
        <tr valign="top">
                <th scope="row">SmugMug Nickname</th>
                <td><input size="50" type="text" name="pyd_smug_nickname"
                           value="<?php echo get_option( 'pyd_smug_nickname' ); ?>" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">SmugMug Categories to Use</th>
                <td>
                    <?php
                    global $add_my_script;

                    $add_my_script = true;

                    $pydsmug_api      = get_option( 'pyd_smug_api' );
                    $pydsmug_secret   = get_option( 'pyd_smug_api_secret' );
                    $pydsmug_appname  = get_option( 'pyd_smug_app_name' );
                    $pydsmug_url      = get_option( 'pyd_smug_app_url' );
                    $pydsmug_nickname = get_option( 'pyd_smug_nickname' );
                    $pydsmug_cats     = get_option( 'pyd_smug_cats' );


                    try {
                        $f = new phpSmug( 'APIKey=' . $pydsmug_api, 'AppName=' . $pydsmug_appname . '/1 (' . $pydsmug_url . ')>' );

                        $f->login();

                        $categories = $f->categories_get( 'NickName=' . $pydsmug_nickname );

                        echo '<p><b>Leave all unchecked to use every category OR select individual categories to use below</b></p>';
                        echo '<hr><p><b>User Categories</b></p>';

                        foreach ( $categories as $category => $categoryvalue ) {
                            if ( $categoryvalue[ 'Type' ] == 'User' ) {

                                ?>

                                <input type="checkbox" name="pyd_smug_cats[<?php echo $categoryvalue[ 'id' ] ?>]" id="<?php echo $categoryvalue[ 'id' ] ?>" value="<?php echo $categoryvalue[ 'id' ] ?>" <?php checked( $pydsmug_cats[ $categoryvalue[ 'id' ] ], $categoryvalue[ 'id' ] ); ?> />
                                <label for="<?php echo $categoryvalue[ 'id' ] ?>">
                                    <?php echo $categoryvalue[ 'Name' ] ?>
                                </label>
                                <br />

                                <?php

                            }
                        }

                        echo '<hr><p><b>SmugMug Categories</b></p>';

                        foreach ( $categories as $category => $categoryvalue ) {
                            if ( $categoryvalue[ 'Type' ] == 'SmugMug' ) {

                                ?>

                                <input type="checkbox" name="pyd_smug_cats[<?php echo $categoryvalue[ 'id' ] ?>]" id="<?php echo $categoryvalue[ 'id' ] ?>" value="<?php echo $categoryvalue[ 'id' ] ?>" <?php checked( $pydsmug_cats[ $categoryvalue[ 'id' ] ], $categoryvalue[ 'id' ] ); ?> />
                                <label for="<?php echo $categoryvalue[ 'id' ] ?>">
                                    <?php echo $categoryvalue[ 'Name' ] ?>
                                </label>

                                <?php

                            }
                        }

                    } catch ( Exception $e ) {
                        echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
                    }

                    ?>

                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="Save" />
        </p>
    </form>
    <?php
        echo '</div>';
    }

    /*-----------------------------------------------------------------------------------*/
    /* Create settings menu for our functions */
    /*-----------------------------------------------------------------------------------*/

    function pyd_smugslider_settings_menu() {
        add_submenu_page( 'options-general.php', 'SmugMug Slider', 'SmugMug Slider', 'edit_posts', 'smugmug-settings', 'pyd_smugslider_option_settings' );
    }

    add_action( 'admin_menu', 'pyd_smugslider_settings_menu' );