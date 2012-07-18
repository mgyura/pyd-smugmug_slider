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


                    $f = new phpSmug("APIKey=9D8IdL53PxaZoZeCzDGLVMQIaYF9Sg6s", "AppName=Poka Yoke Design", "OAuthSecret=99460e933382584b6e6cebfb392f749d");

                    	// Perform the 3 step OAuth Authorisation process.
                    	// NOTE: This is a very simplified example that does NOT store the final token.
                    	// You will need to ensure your application does.




                    if ( ! isset($_SESSION ['SmugGalReqToken'] ) ) {
                    		// Step 1: Get a Request Token
                    		$d = $f->auth_getRequestToken();
                    		$_SESSION['SmugGalReqToken'] = serialize( $d );

                    		// Step 2: Get the User to login to SmugMug and Authorise this demo
                    		echo "<p>Click <a href='".$f->authorize()."' target='_blank'><strong>HERE</strong></a> to Authorize This Demo.</p>";
                            echo "<p>A new window/tab will open asking you to login to SmugMug (if not already logged in).  Once you've logged it, SmugMug will redirect you to a page asking you to approve the access (it's read only) to your public photos.  Approve the request and come back to this page and click REFRESH below.</p>";
                            echo "<p><a href='".$_SERVER['PHP_SELF']."'><strong>REFRESH</strong></a></p>";
                    	}







                        else {
                    		$reqToken = unserialize( $_SESSION['SmugGalReqToken'] );
                    		unset( $_SESSION['SmugGalReqToken'] );

                    		// Step 3: Use the Request token obtained in step 1 to get an access token
                    		$f->setToken("id={$reqToken['Token']['id']}", "Secret={$reqToken['Token']['Secret']}");
                    		$token = $f->auth_getAccessToken();	// The results of this call is what your application needs to store.

                    		// Set the Access token for use by phpSmug.
                    		$f->setToken( "id={$token['Token']['id']}", "Secret={$token['Token']['Secret']}" );

                    		// Get list of public albums
                    		$albums = $f->albums_get( 'Heavy=True' );
                    		// Get list of public images and other useful information
                    		$images = $f->images_get( "AlbumID={$albums['0']['id']}", "AlbumKey={$albums['0']['Key']}", "Heavy=1" );
                    		// Display the thumbnails and link to the Album page for each image
                    		foreach ( $images['Images'] as $image ) {
                    			echo '<a href="'.$image['URL'].'"><img src="'.$image['TinyURL'].'" title="'.$image['Caption'].'" alt="'.$image['id'].'" /></a>';
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
        echo '</div>';
    }

    /*-----------------------------------------------------------------------------------*/
    /* Create settings menu for our functions */
    /*-----------------------------------------------------------------------------------*/

    function pyd_smugslider_settings_menu() {
        add_submenu_page( 'options-general.php', 'SmugMug Slider', 'SmugMug Slider', 'edit_posts', 'smugmug-settings', 'pyd_smugslider_option_settings' );
    }

    add_action( 'admin_menu', 'pyd_smugslider_settings_menu' );