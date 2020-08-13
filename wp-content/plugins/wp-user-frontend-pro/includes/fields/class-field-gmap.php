<?php
/**
 * Google Map Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_GMap extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'Google Map', 'wpuf-pro' );
        $this->input_type = 'google_map';
        $this->icon       = 'map-marker';
    }

    /**
     * Render the Google Map field
     *
     * @param  array  $field_settings
     *
     * @param  integer  $form_id
     *
     * @param  string  $type
     *
     * @param  integer  $post_id
     *
     * @return void
     */
    public function render( $field_settings, $form_id, $type = 'post', $post_id = null ) {
        $value          = '';
        $def_address    = '';
        $def_lat        = '';
        $def_long       = '';
        $attr           = $field_settings;

        if ( isset( $post_id ) && $post_id != '0' ) {
            $map_meta_value = $this->get_meta( $post_id, $attr['name'], $type);
            $type           = $attr['show_lat'] == 'yes' ? 'text' : 'hidden';

            if ( is_array( $map_meta_value ) ) {
                $def_address = isset( $map_meta_value['address'] ) ? $map_meta_value['address'] : '';
                $def_lat     = isset( $map_meta_value['lat'] ) ? $map_meta_value['lat'] : '';
                $def_long    = isset( $map_meta_value['lng'] ) ? $map_meta_value['lng'] : '';
                $value       = implode( ' || ', $map_meta_value );
            } else {
                list( $def_lat, $def_long ) = explode( ',', $attr['default_pos'] );

                if ( !empty( $map_meta_value ) ) {
                    list( $def_lat, $def_long ) = explode( ',', $map_meta_value );
                }
            }
        } else {
            $type  = $attr['show_lat'] == 'yes' ? 'text' : 'hidden';

            list( $def_lat, $def_long ) = explode( ',', $attr['default_pos'] );
        }

        $this->field_print_label( $attr, $form_id );

        ?>

        <div id="<?php echo 'wpuf_'.$attr['name'].'_'.$form_id; ?>" class="wpuf-fields <?php echo ' wpuf_'.$attr['name'].'_'.$form_id; ?>" >
            <div class="wpuf-form-google-map-container <?php echo ( $attr['address'] == 'yes' ) ? 'show-search-box' : 'hide-search-box'; ?>">
                <input id="wpuf-map-lat-<?php echo $attr['name']; ?>"
                    type="<?php echo $type; ?>"
                    data-required="<?php echo $attr['required'] ?>"
                    data-type="text" <?php // $this->required_html5( $attr ); ?>
                    name="<?php echo esc_attr( $attr['name'] ); ?>"
                    value="<?php echo esc_attr( $value ) ?>" size="30" />

                <input class="wpuf-google-map-search" type="text"
                    id="wpuf-map-add-<?php echo $attr['name']; ?>"
                    placeholder="<?php _e( 'Search address', 'wpuf-pro' ); ?>"
                    value="<?php echo esc_attr( $def_address ) ?>">
                <div class="wpuf-form-google-map" id="wpuf-map-<?php echo $attr['name']; ?>"></div>
            </div>

            <span class="wpuf-help"><?php echo stripslashes( $attr['help'] ); ?></span>
            <?php if ( isset( $attr['directions'] ) && $attr['directions'] ) : ?>
                <div>
                    <a class="btn btn-brand btn-sm" href="https://www.google.com/maps/dir/?api=1&amp;destination=<?php echo $def_lat ? $def_lat : 40.7143528; ?>,<?php echo $def_long ? $def_long : -74.0059731; ?>" target="_blank" rel="nofollow external"><i class="fa fa-map-marker" aria-hidden="true"></i><?php _e( 'Directions »', 'wpuf-pro' ); ?></a>
                </div>
            <?php endif; ?>

            <script type="text/javascript">

                (function($) {
                    $(function() {
                            var attr_name   = '<?php echo $attr['name']; ?>',
                            def_zoomval     = <?php echo $attr['zoom'] ? $attr['zoom'] : null; ?>,
                            def_latval      = <?php echo $def_lat ? $def_lat : 0; ?>,
                            def_longval     = <?php echo $def_long ? $def_long : 0; ?>;

                            var map_area    = $('#wpuf-map-' + attr_name ),
                            input_area      = $( '#wpuf-map-lat-' + attr_name ),
                            input_add       = $( '#wpuf-map-add-' + attr_name );

                            var default_pos = {},
                            default_pos_str = def_latval + ', ' + def_longval,
                            default_zoom    = def_zoomval;

                        if (isFinite(def_latval) && isFinite(def_longval)) {
                            default_pos = {lat: parseFloat(def_latval), lng: parseFloat(def_longval)};
                        } else {
                            default_pos = {lat: 40.7143528, lng: -74.0059731};
                        }

                        var map = new google.maps.Map(map_area.get(0), {
                            center: default_pos,
                            zoom: parseInt(default_zoom) || 12,
                            mapTypeId: 'roadmap',
                            streetViewControl: false,
                        });

                        var geocoder = new google.maps.Geocoder();

                        // Create the search box and link it to the UI element.
                        var input     = input_add.get(0);
                        var searchBox = new google.maps.places.SearchBox(input);
                        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                        // Bias the SearchBox results towards current map's viewport.
                        map.addListener('bounds_changed', function() {
                            searchBox.setBounds(map.getBounds());
                        });

                        var markers = [];

                        set_marker(default_pos_str);

                        input.addEventListener('input', function () {
                            set_marker(this.value);
                        });

                        // Listen for the event fired when the user selects a prediction and retrieve
                        // more details for that place.
                        searchBox.addListener('places_changed', function() {
                            var places = searchBox.getPlaces();

                            if (places.length === 0) {
                                return;
                            }

                            // Clear out the old markers.
                            markers.forEach(function (marker) {
                                marker.setMap(null);
                            });

                            markers = [];

                            // For each place, get the icon, name and location.
                            var bounds = new google.maps.LatLngBounds();

                            places.forEach(function (place) {
                                if (!place.geometry) {
                                    console.log('Returned place contains no geometry');

                                    return;
                                }

                                // Create a marker for each place.
                                markers.push(new google.maps.Marker({
                                    map: map,
                                    position: place.geometry.location
                                }));

                                updatePositionInput(place.geometry.location);

                                if (place.geometry.viewport) {
                                    // Only geocodes have viewport.
                                    bounds.union(place.geometry.viewport);

                                } else {
                                    bounds.extend(place.geometry.location);
                                }
                            });

                            map.fitBounds(bounds);
                        });

                        map.addListener('click', function(e) {
                            var latLng = e.latLng;

                            // Clear out the old markers.
                            markers.forEach(function (marker) {
                                marker.setMap(null);
                            });

                            markers = [];

                            markers.push(new google.maps.Marker({
                                position: latLng,
                                map: map
                            }));

                            updatePositionInput(latLng);

                            map.panTo(latLng);
                        });

                        map.addListener('zoom_changed', function () {
                            var zoom = map.getZoom();
                        });

                        function set_marker(address) {

                            geocoder.geocode({'address': address}, function(results, status) {
                                if (status === 'OK') {
                                    // Clear out the old markers.
                                    markers.forEach(function (marker) {
                                        marker.setMap(null);
                                    });

                                    markers = [];

                                    // Create a marker for each place.
                                    markers.push(new google.maps.Marker({
                                        map: map,
                                        position: results[0].geometry.location
                                    }));

                                    map.setCenter(results[0].geometry.location);
                                }
                            });
                        }

                        var firstEl = $('<button>', {
                            title: 'My Location',
                            css: {
                                backgroundColor: '#fff',
                                border: 'none',
                                outline: 'none',
                                width: '18px',
                                height: '18px',
                                borderRadius: '2px',
                                boxShadow: '0 1px 4px rgba(0,0,0,0.3)',
                                cursor: 'pointer',
                                margin: '15px',
                                padding: '0',
                                float: 'right',
                                zIndex: '999',
                                backgroundImage: 'url(https://maps.gstatic.com/tactile/mylocation/mylocation-sprite-1x.png)',
                            }

                        });

                        var secondEl = $('.wpuf-form-google-map-container').append(firstEl);

                        $(firstEl).on('click', function(ev) {
                            if("geolocation" in navigator) {
                                ev.preventDefault();
                                if ("geolocation" in navigator) {
                                    window.navigator.geolocation.getCurrentPosition(function(position) {
                                        set_marker( position.coords.latitude + ', ' + position.coords.longitude );
                                        var latLng = {lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude)};

                                        geocoder.geocode({'location': latLng}, function(results, status) {
                                            var formatted_address = '';

                                            if ( status === 'OK' ) {
                                                var address = results[0];
                                                formatted_address = address.formatted_address;
                                            }

                                            input_area.val( formatted_address + ' || ' + position.coords.latitude + ' || ' + position.coords.longitude  );
                                        })
                                    });
                                }
                            }
                        });

                        function updatePositionInput( latLng ) {
                            geocoder.geocode({'location': latLng}, function(results, status) {
                                var formatted_address = '';

                                if ( status === 'OK' ) {
                                    var address = results[0];
                                    formatted_address = address.formatted_address;
                                }

                                input_area.val( formatted_address + ' || ' + latLng.lat() + ' || ' + latLng.lng() );
                            })
                        }

                        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(firstEl[0]);
                        wpuf_map_items.push( { 'map' : map, 'center' : default_pos });

                        google.maps.event.addListener(map, 'click', function(event) {
                            var latlng = event.latLng;
                            geocoder.geocode({'location': latlng}, function( results, status ) {
                                if (status === 'OK') {
                                    if (results[0]) {
                                        var city, state, zip, country_short, country_long;
                                        var arr = results[0].address_components;
                                        for (var i = 0 ; i < arr.length; i++) {
                                            if ( arr[i].types[0] == 'administrative_area_level_2' ) {
                                                city = arr[i].long_name;
                                            } else if ( arr[i].types[0] == 'administrative_area_level_1' ) {
                                                state = arr[i].long_name;
                                            } else if ( arr[i].types == 'postal_code' ) {
                                                zip = arr[i].long_name;
                                            } else if ( arr[i].types[0] == 'country' ) {
                                                country_short = arr[i].short_name;
                                                country_long = arr[i].long_name;
                                            }
                                        }

                                        var add = results[0].formatted_address.split(", ");

                                        jQuery( "input[name*='[street_address]']" ).val( add[0] );
                                        jQuery( "input[name*='[city_name]']" ).val( city );
                                        jQuery( "input[name*='[state]']" ).val( state );
                                        jQuery( "input[name*='[zip]']" ).val( zip );
                                        jQuery( "[name*='[country_select]'] option" ).filter( function() {
                                            return ( jQuery(this).text() == country_long );
                                        }).prop('selected', true);

                                    } else {
                                        console.log('No results found');
                                    }
                                } else {
                                    console.log('Geocoder failed due to: ' + status);
                                }
                            });
                        });


                    });
                })(jQuery);
            </script>

        </div>

        <?php $this->after_field_print_label();
    }

    /**
     * Validation callback
     *
     * @return array
     */
    public function get_validator() {
        return array(
            'callback'      => 'has_gmap_api_key',
            'button_class'  => 'button-faded',
            'msg_title'     => __( 'Google Map API key', 'wpuf-pro' ),
            'msg'           => sprintf(
                __( 'You need to set Google Map API key in <a href="%s" target="_blank">Settings</a> in order to use "Google Map" field. <a href="%s" target="_blank">Click here to get the API key</a>.', 'wpuf-pro' ),
                admin_url( 'admin.php?page=wpuf#/settings' ),
                'https://developers.google.com/maps/documentation/javascript'
            )
        );
    }

    /**
     * Get field options setting
     *
     * @return array
     */
    public function get_options_settings() {
        $default_options = $this->get_default_option_settings( true, array( 'width' ) );

        $settings = array(
            array(
                'name'          => 'default_pos',
                'title'         => '',
                'type'          => 'gmap-set-position',
                'section'       => 'basic',
                'priority'      => 21,
                'help_text'     => __( 'Enter default latitude and longitude to center the map', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'directions',
                'type'          => 'checkbox',
                'options'       => array(
                    true        => __( 'Show directions link', 'wpuf-pro' )
                ),
                'section'       => 'basic',
                'priority'      => 11,
            )
        );

        return array_merge( $default_options, $settings );
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = array(
            'input_type'    => 'map',
            'zoom'          => '12',
            'default_pos'   => '40.7143528,-74.0059731',
            'directions'    => true,
            'address'       => 'no',
            'show_lat'      => 'no',
        );

        return array_merge( $defaults, $props );
    }
}
