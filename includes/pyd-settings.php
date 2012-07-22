<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: mgyura
     * Date: 6/26/12
     */


    /*-----------------------------------------------------------------------------------*/
    /* Settings and oAuth approval for SmugMug Slider */
    /*-----------------------------------------------------------------------------------*/

    function pyd_smugslider_option_settings() {
        global $add_my_script, $pydsmug_pydapi, $pydsmug_api, $pydsmug_progress, $pydsmug_cats;
        $add_my_script = true;

        echo '<div class="wrap">';
        echo '<h2>SmugMug Slider Settings</h2>';
        ?>

    <div class="pydsmug_reset">
        <form method="post" action="options.php">
            <?php settings_fields( 'pyd-smugslider-api-group' ); ?>
            <input type="hidden" name="pyd_smug_api" value="" />
            <input type="hidden" name="pyd_smug_api_progress" value="" />
            <p class="submit">
                <input type="submit" class="button-secondary" value="Reset SmugMug Settings" />
            </p>
        </form>
    </div>
    <?php

        /*-----------------------------------------------------------------------------------*/
        /* oAuth process start at the bottom of the page with the last else  */
        /* Now that we have the OAUth credentials we can make a settings page  */
        /* First step is to allow users to filter categories  */
        /*-----------------------------------------------------------------------------------*/

        if ( $pydsmug_progress == 4 ) {
            print_r( $pydsmug_api );
            echo '<br />';
            print_r( $pydsmug_cats );

            try {
                $pydsmug_pydapi->setToken( "id={$pydsmug_api['api']['id']}", "Secret={$pydsmug_api['api']['Secret']}" );
                $categories = $pydsmug_pydapi->categories_get( 'NickName=' . $pydsmug_api[ 'api' ][ 'NickName' ] );
                ?>

            <form method="post" action="options.php">
                <?php settings_fields( 'pyd-smugslider-settings-group' ); ?>
                <h3>SmugMug Categories</h3>
                <table class="form-table">
                    <tr valign="top">
                        <td>
                            <?php

                            echo '<p><b>Leave all unchecked to use every category OR select individual categories to use below</b></p>';
                            echo '<p><b>User Categories</b></p>';
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

                            echo '<p><b>SmugMug Categories</b></p>';
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
                            ?>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="Save" />
                </p>
            </form>
            <?php

            } catch ( Exception $e ) {
                echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
            }

            /*-----------------------------------------------------------------------------------*/
            /* Create settings for the image slider */
            /*-----------------------------------------------------------------------------------*/

            echo '<h3>Image Slider Options</h3>';

        }


        /*-----------------------------------------------------------------------------------*/
        /* Step 3 in API OAuth approval */
        /* Grab the OAuth credentials and save them to the options array for later use  */
        /* Set the progress option to "4" */
        /*-----------------------------------------------------------------------------------*/

        elseif ( $pydsmug_progress == 3 ) {

            //Use the Request token obtained to get an access token
            $pydsmug_pydapi->setToken( "id={$pydsmug_api['temp']['id']}", "Secret={$pydsmug_api['temp']['key']}" );
            $token = $pydsmug_pydapi->auth_getAccessToken();
            ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'pyd-smugslider-api-group' ); ?>
            <input type="hidden" name="pyd_smug_api[temp][id]" value="" />
            <input type="hidden" name="pyd_smug_api[temp][key]" value="" />
            <input type="hidden" name="pyd_smug_api[api][id]" value="<?php echo $token[ 'Token' ][ 'id' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][Secret]" value="<?php echo $token[ 'Token' ][ 'Secret' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][Access]" value="<?php echo $token[ 'Token' ][ 'Access' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][Permissions]" value="<?php echo $token[ 'Token' ][ 'Permissions' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][User]" value="<?php echo $token[ 'User' ][ 'id' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][DisplayName]" value="<?php echo $token[ 'User' ][ 'DisplayName' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][NickName]" value="<?php echo $token[ 'User' ][ 'NickName' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][URL]" value="<?php echo $token[ 'User' ][ 'URL' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][AccountStatus]" value="<?php echo $token[ 'User' ][ 'AccountStatus' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][AccountType]" value="<?php echo $token[ 'User' ][ 'AccountType' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][FileSizeLimit]" value="<?php echo $token[ 'User' ][ 'FileSizeLimit' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[api][SmugVault]" value="<?php echo $token[ 'User' ][ 'SmugVault' ]; ?>" />
            <input type="hidden" name="pyd_smug_api_progress" value="4" />
            <p class="submit">
                <input type="submit" class="button-primary" id="formButton" value="Got the key" />
            </p>
        </form>


        <script language="javascript">
            document.getElementById("formButton").click();
        </script>


        <div class="pydsmug_reset">
            <form method="post" action="options.php">
                <?php settings_fields( 'pyd-smugslider-api-group' ); ?>
                <input type="hidden" name="pyd_smug_api" value="" />
                <input type="hidden" name="pyd_smug_api_progress" value="" />
                <p class="submit">
                    <input type="submit" class="button-secondary" value="Reset SmugMug Settings" />
                </p>
            </form>
        </div>


        <?php

        }

        /*-----------------------------------------------------------------------------------*/
        /* Step 2 in API OAuth approval */
        /* Using the temp ID and Key go out to SmugMug and request approval */
        /* Need to save these to options becuase WP clears all $_SESSION[]  */
        /* Set the progress option to "3" */
        /*-----------------------------------------------------------------------------------*/

        elseif ( $pydsmug_progress == 2 ) {

            echo '<h2>Step 1:</h2>';
            echo "<p><a href='https://secure.smugmug.com/services/oauth/authorize.mg?Access=Full&Permissions=Add&oauth_token=" . $pydsmug_api[ 'temp' ][ 'id' ] . "' class='button-primary'  target='_blank'>Click here to log into SmugMug to approve access</a></p>";
            echo '<h2>Step 2:</h2>';

            ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'pyd-smugslider-api-group' ); ?>
            <table class="form-table">
                <input type="hidden" name="pyd_smug_api[temp][id]" value="<?php echo $pydsmug_api[ 'temp' ][ 'id' ] ?>" />
                <input type="hidden" name="pyd_smug_api[temp][key]" value="<?php echo $pydsmug_api[ 'temp' ][ 'key' ] ?>" />
                <input type="hidden" name="pyd_smug_api_progress" value="3" />
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="Authorization Completed, let's finalize this" />
            </p>
        </form>

        <?php
        }


        /*-----------------------------------------------------------------------------------*/
        /* Step 1 in API OAuth approval */
        /* Grab the temp ID and Key from SmugMug and save it in an options array */
        /* Set the progress option to "2" */
        /*-----------------------------------------------------------------------------------*/

        else {
            print_r( $pydsmug_api );
            // Step 1: Get a Request Token
            $d = $pydsmug_pydapi->auth_getRequestToken();
            ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'pyd-smugslider-api-group' ); ?>
            <input type="hidden" name="pyd_smug_api[temp][id]" value="<?php echo $d[ 'Token' ][ 'id' ]; ?>" />
            <input type="hidden" name="pyd_smug_api[temp][key]" value="<?php echo $d[ 'Token' ][ 'Secret' ]; ?>" />
            <input type="hidden" name="pyd_smug_api_progress" value="2" />
            <p class="submit">
                <input type="submit" class="button-primary" value="Start Activation With SmugMug" />
            </p>
        </form>
        <?php
        }

        echo '</div>';
    }


    /*-----------------------------------------------------------------------------------*/
    /* Create settings menu for our functions */
    /*-----------------------------------------------------------------------------------*/

    function pyd_smugslider_settings_menu() {
        add_submenu_page( 'options-general.php', 'SmugMug Slider', 'SmugMug Slider', 'edit_posts', 'smugmug-settings', 'pyd_smugslider_option_settings' );
    }

    add_action( 'admin_menu', 'pyd_smugslider_settings_menu' );