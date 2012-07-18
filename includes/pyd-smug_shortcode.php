<?php
    /**
     * User: mgyura
     * Date: 6/26/12
     */

    /*-----------------------------------------------------------------------------------*/
    /* Create shortcode for SmugMug Slider */
    /*-----------------------------------------------------------------------------------*/


    add_shortcode( 'pydsmugmugslider', 'pyd_smugslider_shortcode' );

    function pyd_smugslider_shortcode( $atts ) {
        global $add_my_script;

        extract(
            shortcode_atts(
                array(
                     'albumid'   => '',
                     'albumkey'  => '',
                     'albumtype' => 'slider'
                ), $atts
            )
        );
        ob_start();
        $add_my_script = true;

        $pydsmug_api      = get_option( 'pyd_smug_api' );
        $pydsmug_secret   = get_option( 'pyd_smug_api_secret' );
        $pydsmug_appname  = get_option( 'pyd_smug_app_name' );
        $pydsmug_url      = get_option( 'pyd_smug_app_url' );
        $pydsmug_nickname = get_option( 'pyd_smug_nickname' );
        $pydsmug_cats     = get_option( 'pyd_smug_cats' );


        try {
            $f = new phpSmug("APIKey=9D8IdL53PxaZoZeCzDGLVMQIaYF9Sg6s", "AppName=SmugMug Slider/1.1 (http://pokayoke.co)>", "OAuthSecret=99460e933382584b6e6cebfb392f749d");
            //$f = new phpSmug( 'APIKey=' . $pydsmug_api, 'AppName=' . $pydsmug_appname . '/1 (' . $pydsmug_url . ')>' );
            $f->login();
            $images = $f->images_get( 'AlbumID=' . $albumid, 'AlbumKey=' . $albumkey, "Heavy=1" );
            $images = ( $f->APIVer == "1.2.2" ) ? $images[ 'Images' ] : $images;

            //echo '<pre>';
            //print_r($images);
            // echo '</pre>';

            if ( $images ) {
                if ( $albumtype == 'slider' ) {

                    $retval = '<div class="flex-container"><div id="slider" class="flexslider"><ul class="slides">';

                    foreach ( $images as $image ) {
                        $retval .= '<li><div class="smugPading"><a href="' . $image[ 'LightboxURL' ] . '" target="_blank"><img src="' . $image[ 'MediumURL' ] . '" title="' . $image[ 'Caption' ] . '" alt="' . $image[ 'id' ] . '" /></a></div></li>';
                    }

                    $retval .= '</ul></div></div>';

                    return $retval;
                }
                else {
                    foreach ( $images as $image ) {
                        echo '<a href="' . $image[ 'LightboxURL' ] . '" target="_blank"><img src="' . $image[ 'ThumbURL' ] . '" title="' . $image[ 'Caption' ] . '" alt="' . $image[ 'id' ] . '" /></a>';
                    }
                }
            }

            return;

        } catch ( Exception $e ) {
            echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
        }
        $output_string = ob_get_contents();
        ob_end_clean();
        return $output_string;
    }

    /*-----------------------------------------------------------------------------------*/
    /* Create new tab in the meida uploader to generate shortcode */
    /*-----------------------------------------------------------------------------------*/

    add_filter( 'media_upload_tabs', 'pydsmug_upload_tab' );

    function pydsmug_upload_tab( $tabs ) {
        $newtab = array( 'pydsmug_insert_tab' => __( 'SmugMug', 'insertgmap' ) );
        return array_merge( $tabs, $newtab );
    }


    add_action( 'media_upload_pydsmug_insert_tab', 'pydsmug_media_upload_tab' );

    function pydsmug_media_upload_tab() {
        return wp_iframe( 'pydsmug_media_upload_form', $errors );
    }

    function pydsmug_media_upload_form() {
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
            $albums = $f->albums_get( 'NickName=' . $pydsmug_nickname );
            ?>

        <script>
            function pydsmuginsertshort() {
                var album_id = jQuery("#pydsmug_album_id").val();
                if (album_id == "") {
                    alert("<?php _e( "Please select a gallery to use", "pydnet" ) ?>");
                    return;
                }

                var album_type = jQuery("#pydsmug_album_type").val();
                if (album_type == "") {
                    alert("<?php _e( "Please select a style for your gallery", "pydnet" ) ?>");
                    return;
                }


                parent.send_to_editor("[pydsmugmugslider " + album_id + "  albumtype=\"" + album_type + "\" ]");
            }
        </script>

        <div id="pydnet_select_pubs_form">
            <div class="wrap">
                <div>
                    <div style="padding:15px 15px 0 15px;">
                        <h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;"><?php _e( "Insert SmugMug Gallery", "pydnet" ); ?></h3>
                        <span>
                            <?php _e( "Select the options below to display your SmugMug gallery on this page.", "pydnet" ); ?>
                        </span>
                    </div>
                    <div style="padding:15px 15px 0 15px;">
                        <p>Select the amount of publications to show per page:<br />
                            <select id="pydsmug_album_id">
                                <option value=""> Select a gallery to insert</option>
                                <?php
                                if ( empty( $pydsmug_cats ) ) {
                                    foreach ( $albums as $album => $albumvalue ) {
                                        ?>
                                        <option value='albumid="<?php echo $albumvalue[ 'id' ] ?>" albumkey="<?php echo $albumvalue[ 'Key' ] ?>"'><?php echo $albumvalue[ 'Title' ] ?></option>

                                        <?php
                                    }
                                }
                                else {
                                    foreach ( $albums as $album => $albumvalue ) {
                                        if ( in_array( $albumvalue[ 'Category' ][ 'id' ], $pydsmug_cats ) ) {
                                            ?>
                                            <option value='albumid="<?php echo $albumvalue[ 'id' ] ?>" albumkey="<?php echo $albumvalue[ 'Key' ] ?>"'><?php echo $albumvalue[ 'Title' ] ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>

                            <select id="pydsmug_album_type">
                                <option value="">Select how to display your gallery</option>
                                <option value="slider">Display gallery as a slider</option>
                                <option value="tab">Display gallery as a thumbnails</option>
                            </select>
                        </p>
                    </div>
                    <div style="padding:15px;">
                        <input type="button" class="button-primary" value="Insert SmugMug Gallery"
                               onclick="pydsmuginsertshort();" />&nbsp;&nbsp;&nbsp;
                        <a class="button" style="color:#bbb;" href="#"
                           onclick="tb_remove(); return false;"><?php _e( "Cancel", "pydnet" ); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        } catch ( Exception $e ) {
            echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
        }

    }