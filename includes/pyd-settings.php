<?php
    /**
     * User: mgyura
     * Date: 6/26/12
     */


    /*-----------------------------------------------------------------------------------*/
    /* Settings and oAuth approval for SmugMug Slider */
    /*-----------------------------------------------------------------------------------*/

    function pyd_smugslider_option_settings() {
        global $add_my_script, $pydsmug_pydapi, $pydsmug_api, $pydsmug_progress, $pydsmug_cats, $pydsmug_slider;
        $add_my_script = true;

        echo '<div class="wrap">';
        echo '<h2>SmugMug Slider Settings</h2>';


        /*-----------------------------------------------------------------------------------*/
        /* oAuth process start at the bottom of the page with the last else  */
        /* Now that we have the OAUth credentials we can make a settings page  */
        /* First step is to allow users to filter categories  */
        /*-----------------------------------------------------------------------------------*/

        if ( $pydsmug_progress == 4 ) {

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
                            echo '<div class="pydsmug_cats"><h4>User Categories</h4>';
                            foreach ( $categories as $category => $categoryvalue ) {
                                if ( $categoryvalue[ 'Type' ] == 'User' ) {
                                    ?>
                                    <div class="pydsmug_checkbox">
                                        <input type="checkbox" name="pyd_smug_cats[<?php echo $categoryvalue[ 'id' ] ?>]" id="<?php echo $categoryvalue[ 'id' ] ?>" value="<?php echo $categoryvalue[ 'id' ] ?>" <?php if ( isset( $pydsmug_cats[ $categoryvalue[ 'id' ] ] ) ) {
                                            echo 'checked="checked"';
                                        } ?> />
                                        <label for="<?php echo $categoryvalue[ 'id' ] ?>">
                                            <?php echo $categoryvalue[ 'Name' ] ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                            }
                            echo '<div class="pydClear"></div></div><div class="pydsmug_cats">';

                            echo '<h4>SmugMug Categories</h4>';
                            foreach ( $categories as $category => $categoryvalue ) {
                                if ( $categoryvalue[ 'Type' ] == 'SmugMug' ) {
                                    ?>

                                    <div class="pydsmug_checkbox">
                                        <input type="checkbox" name="pyd_smug_cats[<?php echo $categoryvalue[ 'id' ] ?>]" id="<?php echo $categoryvalue[ 'id' ] ?>" value="<?php echo $categoryvalue[ 'id' ] ?>" <?php if ( isset( $pydsmug_cats[ $categoryvalue[ 'id' ] ] ) ) {
                                            echo 'checked="checked"';
                                        } ?> />
                                        <label for="<?php echo $categoryvalue[ 'id' ] ?>">
                                            <?php echo $categoryvalue[ 'Name' ] ?>
                                        </label>
                                    </div>
                                    <?php
                                }
                            }
                            echo '<div class="pydClear"></div></div>';
                            ?>

                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="Save All" />
                </p>
                <?php


                /*-----------------------------------------------------------------------------------*/
                /* Create settings for the image slider */
                /*-----------------------------------------------------------------------------------*/

                echo '<h3>Image Slider Options</h3>';
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Slider Animation: Fade or Slide: </th>
                        <td>
                            <select name="pyd_smug_slider[animate]">
                                <option value="fade" <?php selected( $pydsmug_slider[ 'animate' ], 'fade' ); ?>> Fade </option>
                                <option value="slide" <?php selected( $pydsmug_slider[ 'animate' ], 'slide' ); ?>> Slide </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Smooth Height Adjustment: </th>
                        <td>
                            <select name="pyd_smug_slider[smoothheight]">
                                <option value="1" <?php selected( $pydsmug_slider[ 'smoothheight' ], '1' ); ?>> True </option>
                                <option value="" <?php selected( $pydsmug_slider[ 'smoothheight' ], '' ); ?>> False </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Show Location Markers: </th>
                        <td>
                            <select name="pyd_smug_slider[locationmarkers]">
                                <option value="1" <?php selected( $pydsmug_slider[ 'locationmarkers' ], '1' ); ?>> True </option>
                                <option value="" <?php selected( $pydsmug_slider[ 'locationmarkers' ], '' ); ?>> False </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Show Prev/Next Arrows: </th>
                        <td>
                            <select name="pyd_smug_slider[nextarrows]">
                                <option value="1" <?php selected( $pydsmug_slider[ 'nextarrows' ], '1' ); ?>> True </option>
                                <option value="" <?php selected( $pydsmug_slider[ 'nextarrows' ], '' ); ?>> False </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Loop Slide Show: </th>
                        <td>
                            <select name="pyd_smug_slider[loopit]">
                                <option value="1" <?php selected( $pydsmug_slider[ 'loopit' ], '1' ); ?>> True </option>
                                <option value="" <?php selected( $pydsmug_slider[ 'loopit' ], '' ); ?>> False </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Randomize the Images: </th>
                        <td>
                            <select name="pyd_smug_slider[randomit]">
                                <option value="1" <?php selected( $pydsmug_slider[ 'randomit' ], '1' ); ?>> True </option>
                                <option value="" <?php selected( $pydsmug_slider[ 'randomit' ], '' ); ?>> False </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Pause Slide Show on Mouse Hover: </th>
                        <td>
                            <select name="pyd_smug_slider[pausehover]">
                                <option value="1" <?php selected( $pydsmug_slider[ 'pausehover' ], '1' ); ?>> True </option>
                                <option value="" <?php selected( $pydsmug_slider[ 'pausehover' ], '' ); ?>> False </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Start Slide Show Automatically: </th>
                        <td>
                            <select name="pyd_smug_slider[startup]">
                                <option value="1" <?php selected( $pydsmug_slider[ 'startup' ], '1' ); ?>> True </option>
                                <option value="" <?php selected( $pydsmug_slider[ 'startup' ], '' ); ?>> False </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Speed of the Slide Show Cycling: </th>
                        <td>
                            <select name="pyd_smug_slider[cycletime]">
                                <option value="1000" <?php selected( $pydsmug_slider[ 'cycletime' ], '1000' ); ?>> 1 sec </option>
                                <option value="2000" <?php selected( $pydsmug_slider[ 'cycletime' ], '2000' ); ?>> 2 sec </option>
                                <option value="3000" <?php selected( $pydsmug_slider[ 'cycletime' ], '3000' ); ?>> 3 sec </option>
                                <option value="4000" <?php selected( $pydsmug_slider[ 'cycletime' ], '4000' ); ?>> 4 sec </option>
                                <option value="5000" <?php selected( $pydsmug_slider[ 'cycletime' ], '5000' ); ?>> 5 sec </option>
                                <option value="6000" <?php selected( $pydsmug_slider[ 'cycletime' ], '6000' ); ?>> 6 sec </option>
                                <option value="7000" <?php selected( $pydsmug_slider[ 'cycletime' ], '7000' ); ?>> 7 sec </option>
                                <option value="8000" <?php selected( $pydsmug_slider[ 'cycletime' ], '8000' ); ?>> 8 sec </option>
                                <option value="9000" <?php selected( $pydsmug_slider[ 'cycletime' ], '9000' ); ?>> 9 sec </option>
                                <option value="10000" <?php selected( $pydsmug_slider[ 'cycletime' ], '10000' ); ?>> 10 sec </option>
                                <option value="11000" <?php selected( $pydsmug_slider[ 'cycletime' ], '11000' ); ?>> 11 sec </option>
                                <option value="12000" <?php selected( $pydsmug_slider[ 'cycletime' ], '12000' ); ?>> 12 sec </option>
                                <option value="13000" <?php selected( $pydsmug_slider[ 'cycletime' ], '13000' ); ?>> 13 sec </option>
                                <option value="14000" <?php selected( $pydsmug_slider[ 'cycletime' ], '14000' ); ?>> 14 sec </option>
                                <option value="15000" <?php selected( $pydsmug_slider[ 'cycletime' ], '15000' ); ?>> 15 sec </option>
                                <option value="20000" <?php selected( $pydsmug_slider[ 'cycletime' ], '20000' ); ?>> 20 sec </option>
                                <option value="30000" <?php selected( $pydsmug_slider[ 'cycletime' ], '30000' ); ?>> 30 sec </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Speed of Animations: </th>
                        <td>
                            <select name="pyd_smug_slider[animatetime]">
                                <option value="500" <?php selected( $pydsmug_slider[ 'animatetime' ], '500' ); ?>> .5 sec </option>
                                <option value="600" <?php selected( $pydsmug_slider[ 'animatetime' ], '600' ); ?>> .6 sec </option>
                                <option value="700" <?php selected( $pydsmug_slider[ 'animatetime' ], '700' ); ?>> .7 sec </option>
                                <option value="800" <?php selected( $pydsmug_slider[ 'animatetime' ], '800' ); ?>> .8 sec </option>
                                <option value="900" <?php selected( $pydsmug_slider[ 'animatetime' ], '900' ); ?>> .9 sec </option>
                                <option value="1000" <?php selected( $pydsmug_slider[ 'animatetime' ], '1000' ); ?>> 1 sec </option>
                                <option value="1500" <?php selected( $pydsmug_slider[ 'animatetime' ], '1500' ); ?>> 1.5 sec </option>
                                <option value="2000" <?php selected( $pydsmug_slider[ 'animatetime' ], '2000' ); ?>> 2 sec </option>
                                <option value="3000" <?php selected( $pydsmug_slider[ 'animatetime' ], '3000' ); ?>> 3 sec </option>
                                <option value="4000" <?php selected( $pydsmug_slider[ 'animatetime' ], '4000' ); ?>> 4 sec </option>
                                <option value="5000" <?php selected( $pydsmug_slider[ 'animatetime' ], '5000' ); ?>> 5 sec </option>
                                <option value="6000" <?php selected( $pydsmug_slider[ 'animatetime' ], '6000' ); ?>> 6 sec </option>
                                <option value="7000" <?php selected( $pydsmug_slider[ 'animatetime' ], '7000' ); ?>> 7 sec </option>
                                <option value="8000" <?php selected( $pydsmug_slider[ 'animatetime' ], '8000' ); ?>> 8 sec </option>
                                <option value="9000" <?php selected( $pydsmug_slider[ 'animatetime' ], '9000' ); ?>> 9 sec </option>
                                <option value="10000" <?php selected( $pydsmug_slider[ 'animatetime' ], '10000' ); ?>> 10 sec </option>
                            </select>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="Save All" />
                </p>
            </form>
            <?php

            } catch ( Exception $e ) {
                echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
            }

            ?>

        <hr />
        <div class="pydsmug_reset">
            <p>If you want to reset the selected categories, click this button</p>
            <form method="post" action="options.php">
                <?php settings_fields( 'pyd-smugslider-settings-group' ); ?>
                <input type="hidden" name="pyd_smug_cats" value="" />
                <p class="submit">
                    <input type="submit" class="button-secondary" value="Reset SmugMug Categories" />
                </p>
            </form>
        </div>

        <div class="pydsmug_reset">
            <p>If you want to link to a different SmugMug account, or have an error with the current SmugMug Slider authorization, click this button</p>
            <form method="post" action="options.php">
                <?php settings_fields( 'pyd-smugslider-api-group' ); ?>
                <input type="hidden" name="pyd_smug_api" value="" />
                <input type="hidden" name="pyd_smug_api_progress" value="" />
                <p class="submit">
                    <input type="submit" class="button-secondary" value="Delete SmugMug Authorization" />
                </p>
            </form>
        </div>
        <hr class="pydClear" />

        <?php
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

        <p>This page will automatically refresh in 5 seconds.  If it does not, click the below button.</p>
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


        <hr />
        <div class="pydsmug_reset">
            <p>If there was an error in the approval process, click this button to restart approval</p>
            <form method="post" action="options.php">
                <?php settings_fields( 'pyd-smugslider-api-group' ); ?>
                <input type="hidden" name="pyd_smug_api" value="" />
                <input type="hidden" name="pyd_smug_api_progress" value="" />
                <p class="submit">
                    <input type="submit" class="button-secondary" value="Delete SmugMug Authorization" />
                </p>
            </form>
        </div>
        <hr class="pydClear" />


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
            echo '<p>Click the button below to send a request to SmugMug for approval.  A new browser tab will open up and you will be asked to log into your SmugMug account and approve access for this app.</p>';
            echo "<p><a href='https://secure.smugmug.com/services/oauth/authorize.mg?Access=Full&Permissions=Add&oauth_token=" . $pydsmug_api[ 'temp' ][ 'id' ] . "' class='button-primary'  target='_blank'>Click here to log into SmugMug to approve access</a></p>";
            echo '<h2>Step 2:</h2>';
            echo '<p>Once you have given this app permission to access your account, click the below button.  This will save the approval credentials to your WordPress database.</p>'

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

        <hr />
        <div class="pydsmug_reset">
            <p>If there was an error in the approval process, click this button to restart approval</p>
            <form method="post" action="options.php">
                <?php settings_fields( 'pyd-smugslider-api-group' ); ?>
                <input type="hidden" name="pyd_smug_api" value="" />
                <input type="hidden" name="pyd_smug_api_progress" value="" />
                <p class="submit">
                    <input type="submit" class="button-secondary" value="Delete SmugMug Authorization" />
                </p>
            </form>
        </div>
        <hr class="pydClear" />


        <?php
        }


        /*-----------------------------------------------------------------------------------*/
        /* Step 1 in API OAuth approval */
        /* Grab the temp ID and Key from SmugMug and save it in an options array */
        /* Set the progress option to "2" */
        /*-----------------------------------------------------------------------------------*/

        else {

            // Step 1: Get a Request Token
            $d = $pydsmug_pydapi->auth_getRequestToken();
            ?>

        <p>Before SmugMug Responsive Slider can be used it needs to have permission from your account to access photos.  Click the button below to start the approval process.  </p>
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