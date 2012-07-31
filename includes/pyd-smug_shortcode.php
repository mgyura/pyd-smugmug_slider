<?php
    /**
     * User: mgyura
     * Date: 7/27/12
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


        try {

            ob_start();

            $pydsmug_pydapi->setToken( "id={$pydsmug_api['api']['id']}", "Secret={$pydsmug_api['api']['Secret']}" );

            $images = $pydsmug_pydapi->images_get( 'AlbumID=' . $albumid, 'AlbumKey=' . $albumkey, "Heavy=1" );
            $images = ( $pydsmug_pydapi->APIVer == "1.2.2" ) ? $images[ 'Images' ] : $images;


            /*-----------------------------------------------------------------------------------*/
            /* Grab SmugMug images and dump into WP set_transient()  */
            /*-----------------------------------------------------------------------------------*/


            if ( $images ) {

                $pydsmug_get_transient = get_transient( 'pydsmug_albums_' . $albumid . '_' . $imagesize . '_' . $imagelink );

                if ( !$pydsmug_get_transient  ) {

                    //Grab the images from SmugMug, using the sizes chosen in the shortcode generator.
                    foreach ( $images as $image => $imagevalue ) {

                        /*
                        * Took out.  This would upload the images to the local wp uploads folder.  -MG
                        *
                        * $image_resource[ 'id' ][ $imagevalue[ 'id' ] ] = imagecreatefromjpeg( $imagevalue[ $imagesize ] );
                        * $upload_dir                         = wp_upload_dir();
                        * $upload_file[ $imagevalue[ 'id' ] ] = $upload_dir[ 'path' ] . '/' . $imagevalue[ 'id' ] . '.jpg';
                        * $pydsmug_saved_data[ 'url' ][ $imagevalue[ 'id' ] ]     = $upload_dir[ 'url' ] . '/' . $imagevalue[ 'id' ] . '.jpg';
                        * imagejpeg( $image_resource[ 'id' ][ $imagevalue[ 'id' ] ], $upload_file[ $imagevalue[ 'id' ] ] );
                        *
                        */

                        $pydsmug_saved_data[ $imagevalue[ 'id' ] ] = array(
                            'image_url'     => $imagevalue[ $imagesize ],
                            'image_link'    => $imagevalue[ $imagelink ],
                            'image_caption' => $imagevalue[ 'Caption' ]
                        );

                        set_transient( 'pydsmug_albums_' . $albumid, $pydsmug_saved_data, 3600 );

                    }
                }

                $images = get_transient( 'pydsmug_albums_' . $albumid );



                /*-----------------------------------------------------------------------------------*/
                /* Run the selected gallery as a slider or thumbs */
                /*-----------------------------------------------------------------------------------*/
                if ( $albumtype == 'slider' ) {
                    $retval = '<div class="flex-container"><div id="slider" class="flexslider"><ul class="slides">';
                    foreach ( $images as $image ) {

                        if ( $imagelink == '0' ) {
                            $retval .= '<li><div class="smugPading"><img src="' . $image[ 'image_url' ] . '" title="' . $image[ 'image_caption' ] . '" alt="' . $image[ 'image_caption' ] . '" /></div></li>';
                        }
                        else {
                            $retval .= '<li><div class="smugPading"><a href="' . $image[ 'image_link' ] . '" target="_blank"><img src="' . $image[ 'image_url' ] . '" title="' . $image[ 'image_caption' ] . '" alt="' . $image[ 'image_caption' ] . '" /></a></div></li>';
                        }
                    }
                    $retval .= '</ul></div></div>';
                    return $retval;
                }

                else {
                    foreach ( $images as $image ) {
                        if ( $imagelink == '0' ) {
                            echo '<img src="' . $image[ 'image_url' ] . '" class="pydsmug_thumbnail" title="' . $image[ 'image_caption' ] . '" alt="' . $image[ 'image_caption' ] . '" />';
                        }
                        else {
                            echo '<a href="' . $image[ 'image_link' ] . '" target="_blank"><img src="' . $image[ 'image_url' ] . '" class="pydsmug_thumbnail" title="' . $image[ 'image_caption' ] . '" alt="' . $image[ 'image_caption' ] . '" /></a>';
                        }
                    }
                }
            }

            $output_string = ob_get_contents();
                    ob_end_clean();
                    return $output_string;

        } catch ( Exception $e ) {
            echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
        }

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
        global $errors;

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
                                <option value="0"> No link </option>
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