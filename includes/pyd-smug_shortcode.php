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
        global $add_my_script, $pydsmug_pydapi, $pydsmug_api;
        $add_my_script = true;

        extract(
            shortcode_atts(
                array(
                     'albumid'   => '',
                     'albumkey'  => '',
                     'albumtype' => 'slider',
                     'imagesize' => 'MediumURL',
                     'imagelink' => 'LightboxURL'
                ), $atts
            )
        );
        ob_start();

        try {
            $pydsmug_pydapi->setToken( "id={$pydsmug_api['api']['id']}", "Secret={$pydsmug_api['api']['Secret']}" );

            $images = $pydsmug_pydapi->images_get( 'AlbumID=' . $albumid, 'AlbumKey=' . $albumkey, "Heavy=1" );
            $images = ( $pydsmug_pydapi->APIVer == "1.2.2" ) ? $images[ 'Images' ] : $images;


            echo '<pre>';
            print_r( $images );
            echo '</pre>';


            if ( $images ) {
                if ( $albumtype == 'slider' ) {
                    $retval = '<div class="flex-container"><div id="slider" class="flexslider"><ul class="slides">';
                    foreach ( $images as $image ) {
                        if($imagelink == '#') {
                            $retval .= '<li><div class="smugPading"><img src="' . $image[ $imagesize ] . '" title="' . $image[ 'Caption' ] . '" alt="' . $image[ 'Caption' ] . '" /></div></li>';
                        }
                        else {
                            $retval .= '<li><div class="smugPading"><a href="' . $image[ $imagelink ] . '" target="_blank"><img src="' . $image[ $imagesize ] . '" title="' . $image[ 'Caption' ] . '" alt="' . $image[ 'Caption' ] . '" /></a></div></li>';
                        }
                    }
                    $retval .= '</ul></div></div>';
                    return $retval;
                }

                else {
                    foreach ( $images as $image ) {
                        if($imagelink == '#') {
                            echo '<img src="' . $image[ $imagesize ] . '" title="' . $image[ 'Caption' ] . '" alt="' . $image[ 'Caption' ] . '" />';
                        }
                        else {
                            echo '<a href="' . $image[ $imagelink ] . '" target="_blank"><img src="' . $image[ $imagesize ] . '" title="' . $image[ 'Caption' ] . '" alt="' . $image[ 'Caption' ] . '" /></a>';
                        }
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
        global $add_my_script, $pydsmug_pydapi, $pydsmug_cats, $pydsmug_api;
        $add_my_script = true;

        try {
            $pydsmug_pydapi->setToken( "id={$pydsmug_api['api']['id']}", "Secret={$pydsmug_api['api']['Secret']}" );
            $albums = $pydsmug_pydapi->albums_get( 'NickName=' . $pydsmug_api[ 'api' ][ 'NickName' ] );
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

                var image_size = jQuery("#pydsmug_image_size").val();
                if (image_size == "") {
                    alert("<?php _e( "Please select a size for your image", "pydnet" ) ?>");
                    return;
                }

                var image_link = jQuery("#pydsmug_image_link").val();
                if (image_link == "") {
                    alert("<?php _e( "Please select a link for your image", "pydnet" ) ?>");
                    return;
                }


                parent.send_to_editor("[pydsmugmugslider " + album_id + "  albumtype=\"" + album_type + "\" imagesize=\"" + image_size + "\" imagelink=\"" + image_link + "\" ]");
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
                        <p>Select the image size and link: <br />
                            <select id="pydsmug_image_size">
                                <option value=""> Select the image size for your gallery display </option>
                                <option value="OriginalURL"> Original image size</option>
                                <option value="LargeURL"> Large image size</option>
                                <option value="MediumURL"> Medium image size</option>
                                <option value="SmallURL"> Small image size</option>
                                <option value="ThumbURL"> Thumb image size</option>
                            </select>
                        </p>
                        <p>
                            <select id="pydsmug_image_link">
                                <option value=""> Select where your image should link to </option>
                                <option value="#"> No link </option>
                                <option value="OriginalURL"> Original image size</option>
                                <option value="LightboxURL"> Lightbox </option>
                                <option value="LargeURL"> Large image size</option>
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