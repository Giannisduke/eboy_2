<?php
/*
Plugin Name: FacetWP - Bookings Integration
Description: WooCommerce Bookings support for Carhub
Version: 0.7.0
Author: FacetWP, LLC
Author URI: https://facetwp.com/
GitHub URI: facetwp/facetwp-bookings
*/

defined( 'ABSPATH' ) or exit;

/**
 * Register facet type
 */
add_filter( 'facetwp_facet_types', function( $facet_types ) {
    $facet_types['availability'] = new FacetWP_Facet_Availability();
    return $facet_types;
});


/**
 * Availability facet
 */
class FacetWP_Facet_Availability
{
    public $product_ids;
    public $product_to_job_listings; // key = product ID, value = array of job_listing IDs
    public $facet;


    function __construct() {
        $this->label = __( 'Availability', 'fwp' );

        // setup variables
        define( 'FACETWP_BOOKINGS_URL', plugins_url( '', __FILE__ ) );

        // hooks
        add_filter( 'facetwp_store_unfiltered_post_ids', '__return_true' );
        add_filter( 'facetwp_bookings_filter_posts', array( $this, 'wpjm_products_integration' ) );
    }


    /**
     * Generate the facet HTML
     */
     function render( $params ) {
         $value = $params['selected_values'];
         $dates = empty( $value ) ?  '' : $value[0] . ' - ' . $value[1];
         $date_01 = empty( $value ) ?  '' : $value[0] ;
         $date_02 = empty( $value ) ?  '' : $value[1] ;
         $quantity = empty( $value ) ? 1 : $value[2];
         $time  = 'yes' === $params['facet']['time'] ? 'true' : 'false';
         $time_format = empty( $params['facet']['time_format'] ) ? '24hr' : $params['facet']['time_format'];


         $output = '<input type="text" class="facetwp-date" value="' . esc_attr( $dates ) . '" placeholder="' . __( 'Select date range', 'fwp-bookings' ) . '" data-enable-time="' . $time . '" data-time-format="' . $time_format . '"  />';

         $output .= '<input type="number" class="facetwp-quantity" value="'. esc_attr( $quantity ) .'" min="0" placeholder="' . __( 'Quantity', 'fwp-bookings' ) . '" style="display:none" />';
         return $output;
     }


    /**
     * Filter the query based on selected values
     */
    function filter_posts( $params ) {
        global $wpdb;

        $output = array();
        $facet = $params['facet'];
        $values = $params['selected_values'];
        $behavior = empty( $facet['behavior'] ) ? 'default' : $facet['behavior'];
        $this->facet = $facet;

        $start_date = $values[0];
        $end_date = $values[1];
        $quantity = empty( $values[2] ) ? 1 : (int) $values[2];

        // WPJM Products integration
        if ( function_exists( 'wpjmp' ) ) {
            $temp = array();
            foreach ( FWP()->unfiltered_post_ids as $post_id ) {
                if ( 'job_listing' == get_post_type( $post_id ) ) {
                    $related_product_ids = (array) get_post_meta( $post_id, '_products', true );
                    foreach ( $related_product_ids as $id ) {
                        $this->product_to_job_listings[ $id ][] = $post_id;
                        $temp[ $id ] = true;
                    }
                }
            }
            $this->product_ids = array_keys( $temp );
        }
        else {
            $this->product_ids = FWP()->unfiltered_post_ids;
        }

        // Get available bookings
        if ( $this->is_valid_date( $start_date ) && $this->is_valid_date( $end_date ) ) {
            $output = $this->get_available_bookings( $start_date, $end_date, $quantity, $behavior );
        }

        return apply_filters( 'facetwp_bookings_filter_posts', $output );
    }


    /**
     * Get all available booking products
     *
     * @param string $start_date_raw YYYY-MM-DD format
     * @param string $end_date_raw YYYY-MM-DD format
     * @param int $quantity Number of people to book
     * @param string $behavior Whether to return exact matches
     * @return array Available post IDs
     */
    function get_available_bookings( $start_date_raw, $end_date_raw, $quantity = 1, $behavior = 'default' ) {
        $matches = array();

        // Separate dates from times
        $start_date = explode( ' ', $start_date_raw );
        $end_date = explode( ' ', $end_date_raw );

        // If time wasn't passed, define defaults.
        if ( ! isset( $start_date[1] ) ) {
            $start_date[1] = '00:00';
        }

        $start = explode( '-', $start_date[0] );

        $args = array(
            'wc_bookings_field_resource' => 0,
            'wc_bookings_field_persons' => $quantity,
            'wc_bookings_field_duration' => 1,
            'wc_bookings_field_start_date_year' => $start[0],
            'wc_bookings_field_start_date_month' => $start[1],
            'wc_bookings_field_start_date_day' => $start[2],
        );

        // Loop through all posts
        foreach ( $this->product_ids as $post_id ) {
            if ( 'product' == get_post_type( $post_id ) ) {
                $product = wc_get_product( $post_id );

                if ( is_wc_booking_product( $product ) ) {

                    // Grab the duration unit
                    $unit = $product->is_type( 'accommodation-booking' ) ? 'night' : $product->get_duration_unit();

                    // Support time
                    if ( in_array( $unit, array( 'minute', 'hour' ) ) ) {
                        if ( ! empty( $start_date[1] ) ) {
                            $args['wc_bookings_field_start_date_time'] = $start_date[1];
                        }
                    }

                    if ( 'exact' === $behavior ) {
                        $duration = $this->calculate_duration( $start_date_raw, $end_date_raw, $product->get_duration(), $unit );
                        $args['wc_bookings_field_duration'] = $duration;
                    }

                    $booking_form = new WC_Booking_Form( $product );
                    $posted_data = $booking_form->get_posted_data( $args );

                    // All slots are available (exact match)
                    if ( true === $booking_form->is_bookable( $posted_data ) ) {
                        $matches[] = $post_id;
                    }

                    // Any slot between the given dates are available
                    elseif ( 'exact' !== $behavior ) {
                        $from = strtotime( $start_date_raw );
                        $to = strtotime( $end_date_raw );

                        // If day-based, count the end date as a full day
                        if ( empty( $this->facet['time'] ) || 'no' == $this->facet['time'] ) {
                            $to += 86399; // -1 second so it's 23:59:59
                        }

                        $blocks_in_range = $booking_form->product->get_blocks_in_range( $from, $to );

                        // Arguments changed in WC Bookings 1.11.1
                        $available_blocks = $booking_form->product->get_available_blocks( array(
                            'blocks' => $blocks_in_range,
                            'from' => $from,
                            'to' => $to
                        ) );

                        foreach ( $available_blocks as $check ) {
                            if ( true === $booking_form->product->check_availability_rules_against_date( $check, '' ) ) {
                                $matches[] = $post_id;
                                break; // check passed
                            }
                        }
                    }
                }
            }
        }

        return $matches;
    }


    /**
     * WPJM - Products plugin integration
     * Use $this->product_to_job_listings to include related job_listing IDs
     */
    function wpjm_products_integration( $product_ids ) {
        if ( function_exists( 'wpjmp' ) ) {
            $job_listing_ids = array();
            foreach ( $product_ids as $pid ) {
                if ( isset( $this->product_to_job_listings[ $pid ] ) ) {
                    foreach ( $this->product_to_job_listings[ $pid ] as $job_listing_id ) {
                        $job_listing_ids[ $job_listing_id ] = true; // prevents duplicate IDs
                    }
                }
            }

            foreach ( array_keys( $job_listing_ids ) as $id ) {
                $product_ids[] = $id;
            }
        }

        return $product_ids;
    }


    /**
     * Calculate days between 2 date intervals
     */
    function calculate_duration( $start_date, $end_date, $block_unit, $unit = 'day' ) {
        if ( $start_date == $end_date ) {
            return 1;
        }

        $diff = strtotime( $end_date ) - strtotime( $start_date );

        $units = array(
            'minute'    => 60,
            'hour'      => 3600,
            'day'       => 86400,
            'night'     => 86400,
            'month'     => 2592000,
        );

        $value = floor( $diff / $units[ $unit ] );

        // Dec 1-3 should count as 3 days, not 2 (except for accommodation bookings)
        if ( 'day' == $unit ) {
            $value++;
        }

        // Return total number divided by block unit
        return ( $value / $block_unit );
    }


    /**
     * Validate date input
     *
     * @requires PHP 5.3+
     */
    function is_valid_date( $date ) {
        if ( empty( $date ) ) {
            return false;
        }
        elseif ( 10 === strlen( $date ) ) {
            $d = DateTime::createFromFormat( 'Y-m-d', $date );
            return $d && $d->format( 'Y-m-d' ) === $date;
        }
        elseif ( 16 === strlen( $date ) ) {
            $d = DateTime::createFromFormat( 'Y-m-d H:i', $date );
            return $d && $d->format( 'Y-m-d H:i' ) === $date;
        }

        return false;
    }


    /**
     * Output any front-end scripts
     */
    function front_scripts() {
      $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        FWP()->display->assets['moment.js'] = FACETWP_BOOKINGS_URL . '/assets/vendor/daterangepicker/moment.min.js';
        FWP()->display->assets['daterangepicker.js'] = FACETWP_BOOKINGS_URL . '/assets/vendor/daterangepicker/daterangepicker.min.js';
        FWP()->display->assets['daterangepicker.css'] = FACETWP_BOOKINGS_URL . '/assets/vendor/daterangepicker/daterangepicker.css';
        FWP()->display->assets['bootstrap.css'] = FACETWP_BOOKINGS_URL . '/assets/vendor/daterangepicker/bootstrap.css';


?>
<script>
(function($) {
    FWP.hooks.addAction('facetwp/refresh/availability', function($this, facet_name) {
        var $input = $this.find('.facetwp-date');
        var date = $input.val() || '';
        var dates = ('' !== date) ? date.split(' - ') : '';
        var quantity = $this.find('.facetwp-quantity').val() || 1;
        FWP.facets[facet_name] = ('' != date) ? [dates[0], dates[1], quantity] : [];
        if (FWP.loaded) {
            $input.data('daterangepicker').remove(); // cleanup the datepicker
        }
    });
    FWP.hooks.addFilter('facetwp/selections/availability', function(output, params) {
        return params.selected_values[0] + ' - ' + params.selected_values[1];
    });
    $(document).on('facetwp-loaded', function() {
        $('.facetwp-type-availability .facetwp-date:not(.ready)').each(function() {
            var $this = $(this);
            var isTimeEnabled = $this.attr('data-enable-time') === 'true';
            var is24Hour = $this.attr('data-time-format') === '24hr';
            var dateFormat = isTimeEnabled ? 'YYYY-MM-DD HH:mm' : 'YYYY-MM-DD';

            $this.daterangepicker({
                autoUpdateInput: true,
                minDate: moment().startOf('hour'),
                timePicker: isTimeEnabled,
                timePicker24Hour: is24Hour,
                timePickerIncrement: 5,
                locale: {
                    cancelLabel: 'Clear',
                    format: dateFormat
                }
            });
            $this.on('apply.daterangepicker', function(ev, picker) {
                var startDate = moment(picker.startDate).format(dateFormat);
                var endDate = moment(picker.endDate).format(dateFormat);
                $(this).val(startDate + ' - ' + endDate);
                FWP.autoload();
            });
            $this.on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                FWP.autoload();
            });
        });
    });
})(jQuery);
</script>
<?php
    }


    /**
     * Output admin settings HTML
     */
    function settings_html() {
?>
        <div class="facetwp-row">
            <div>
                <?php _e('Use time?', 'fwp'); ?>:
                <div class="facetwp-tooltip">
                    <span class="icon-question">?</span>
                    <div class="facetwp-tooltip-content"><?php _e( 'Support time based bookings?', 'fwp' ); ?></div>
                </div>
            </div>
            <div>
                <select class="facet-time">
                    <option value="no"><?php _e( 'No', 'fwp' ); ?></option>
                    <option value="yes"><?php _e( 'Yes', 'fwp' ); ?></option>
                </select>
            </div>
        </div>
        <div class="facetwp-row">
            <div>
                <?php _e('Behavior', 'fwp'); ?>:
                <div class="facetwp-tooltip">
                    <span class="icon-question">?</span>
                    <div class="facetwp-tooltip-content"><?php _e( 'Set how the range is handled.', 'fwp' ); ?></div>
                </div>
            </div>
            <div>
                <select class="facet-behavior">
                    <option value="default"><?php _e( 'Any results within range', 'fwp' ); ?></option>
                    <option value="exact"><?php _e( 'Results that match the exact range', 'fwp' ); ?></option>
                </select>
            </div>
        </div>
        <div class="facetwp-row" v-show="facet.time == 'yes'">
            <div>
                <?php _e('Time Format', 'fwp'); ?>:
            </div>
            <div>
                <select class="facet-time-format">
                    <option value="12hr"><?php _e( 'AM / PM', 'fwp' ); ?></option>
                    <option value="24hr"><?php _e( '24 Hour', 'fwp' ); ?></option>
                </select>
            </div>
        </div>
<?php
    }
}
