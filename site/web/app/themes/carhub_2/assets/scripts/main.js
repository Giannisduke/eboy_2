/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages

        $(document).on('facetwp-loaded', function() {

      //    carouselitemactive();

          var full_dates = $("input.facetwp-date").val(),

                full_dates_start = full_dates.slice(0, 10),
                full_dates_year = full_dates.slice(0, 4),
                full_dates_month = full_dates.slice(5, 7),
                 full_dates_day = full_dates.slice(8, 10),
                 full_dates_end = full_dates.slice(19, 29),
                 str4 = full_dates.slice(24,26);
                 str5 = full_dates.slice(27,30);


                 var a = moment(full_dates_start,'YYYY-M-D');
                  var b = moment(full_dates_end,'YYYY-M-D');
                  var diffDays = b.diff(a, 'days');
                //  alert(diffDays);

           $('.booking_date_month').val(full_dates_month);
           $('.booking_date_day').val(full_dates_day);
          // $('.booking_date_day').focus();
           $('.booking_to_date_month').val(str4);
           $('.booking_to_date_day').val(str5);
           $('input[name="wc_bookings_field_duration"]').val(diffDays);
           $('input[name="wc_bookings_field_start_date_day"]').val(full_dates_day);
           $('input[name="wc_bookings_field_start_date_month"]').val(full_dates_month);
           $('input[name="wc_bookings_field_start_date_year"]').val(full_dates_year);

           $('.custom_add_to_cart').click(function (e) {
             e.preventDefault();

             var id = $(this).next().next().attr('value');
             var duration = $(this).next().next().next().attr('value');
             var persons = $(this).next().next().next().next().attr('value');
             var start_day = $(this).next().next().next().next().next().attr('value');
             var start_month = $(this).next().next().next().next().next().next().attr('value');
             var start_year = $(this).next().next().next().next().next().next().next().attr('value');
             var start_time = $(this).next().next().next().next().next().next().next().next().attr('value');
             var data = {
               product_id: id,
               quantity: 1,
               wc_bookings_field_duration: diffDays,
               wc_bookings_field_persons: persons,
               wc_bookings_field_start_date_day: start_day,
               wc_bookings_field_start_date_month: start_month,
               wc_bookings_field_start_date_year: start_year,
               wc_bookings_field_start_date_time: start_time
             };
             $(this).parent().addClass('loading');
             $.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), data, function (response) {

               if (!response) {
                 return;
               }
               if (response.error) {
                 alert("Custom Massage ");
                 $('.custom_add_to_cart').parent().removeClass('loading');
                 return;
               }
               if (response) {

                 var url = woocommerce_params.wc_ajax_url;
                 url = url.replace("%%endpoint%%", "get_refreshed_fragments");
                 $.post(url, function (data, status) {
                   $(".woocommerce.widget_shopping_cart").html(data.fragments["div.widget_shopping_cart_content"]);
                   if (data.fragments) {
                     jQuery.each(data.fragments, function (key, value) {

                       jQuery(key).replaceWith(value);
                     });
                   }
                   jQuery("body").trigger("wc_fragments_refreshed");
                 });
                 $('.custom_add_to_cart').parent().removeClass('loading');

               }

             });

          });

           console.log(full_dates);



    });


      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired


          $("#startdate, #enddate").focus(function () {
            $(".facetwp-date").click();
          });

          function jqueryScript() {
             $.getScript("https://code.jquery.com/jquery-2.1.3.min.js");
          }
          function jqueryblockui() {
             $.getScript("https://eboy.gr/app/plugins/woocommerce/assets/js/jquery-blockui/jquery.blockUI.js?ver=2.70");
          }
          function jscookie() {
             $.getScript("https://eboy.gr/app/plugins/woocommerce/assets/js/js-cookie/js.cookie.js?ver=2.1.4");
          }
          function woocommerce() {
             $.getScript("https://eboy.gr/app/plugins/woocommerce/assets/js/frontend/woocommerce.js?ver=3.5.4");
          }
          function cart_fragments() {
             $.getScript("https://eboy.gr/app/plugins/woocommerce/assets/js/frontend/cart-fragments.js?ver=3.5.4");
          }
          function mainjs() {
             $.getScript("https://eboy.gr/app/themes/carhub_2/dist/scripts/main-77ad56b12b.js");
          }
          function single_product() {
             $.getScript("https://eboy.gr/app/plugins/woocommerce/assets/js/frontend/single-product.js?ver=3.5.4");
          }
          function booking_form() {
             $.getScript("https://eboy.gr/app/plugins/woocommerce-bookings/assets/js/booking-form.js?ver=1.10.1");
          }
          function underscore() {
             $.getScript("https://eboy.gr/carhub/wp-includes/js/underscore.min.js?ver=1.8.3");
          }
          function wp_util() {
             $.getScript("https://eboy.gr/carhub/wp-includes/js/wp-util.js?ver=5.0.2");
          }
          function add_to_cart_variation() {
             $.getScript("https://eboy.gr/app/plugins/woocommerce/assets/js/frontend/add-to-cart-variation.js?ver=3.5.4");
          }
          function core() {
             $.getScript("https://eboy.gr/carhub/wp-includes/js/jquery/ui/core.min.js?ver=1.11.4");
          }
          function datepicker() {
             $.getScript("https://eboy.gr/carhub/wp-includes/js/jquery/ui/datepicker.min.js?ver=1.11.4");
          }
          function date_picker() {
             $.getScript("https://eboy.gr/app/plugins/woocommerce-bookings/assets/js/date-picker.js?ver=1.10.1");
          }
          function query_string() {
             $.getScript("https://eboy.gr/app/plugins/facetwp/assets/js/src/query-string.js?ver=3.2.12");
          }
          function favetwp_woocommerce() {
             $.getScript("https://eboy.gr/app/plugins/facetwp/includes/integrations/woocommerce/woocommerce.js?ver=3.2.12");
          }
          function front() {
             $.getScript("https://eboy.gr/app/plugins/facetwp/assets/js/dist/front.min.js?ver=3.2.12");
          }
          function moment() {
             $.getScript("https://eboy.gr/app/plugins/facetwp-bookings-carhub/assets/vendor/daterangepicker/moment.min.js?ver=3.2.12");
          }
          function daterangepicker() {
             $.getScript("https://eboy.gr/app/plugins/facetwp-bookings-carhub/assets/vendor/daterangepicker/daterangepicker.min.js?ver=3.2.12");
          }


          function mykeypress(){
              $('.uppend:first').keypress().removeClass('uppend').addClass('test');
          }

          function carouselitemactive() {

            $('.carousel').on('click', function() {
                $('.carousel').carousel('cycle');
            });

          }



      getScripts = function (urls, callback) {
      var script = urls.shift();
      $.getScript(script, function () {
          if (urls.length + 1 <= 0) {
              if (typeof callback === 'function') {
                  callback();
              }
          } else {
              getScripts(urls, callback);
          }
      });
  };

  if (FWP.loaded) { // after the initial pageload
    booking_form();
//  jqueryScript();
    jqueryblockui();
    woocommerce();
    cart_fragments();
//           mainjs();
   add_to_cart_variation();
   datepicker();
    single_product();
    underscore();
    date_picker();



    console.log("second load");
    var time = 1000;
    $('.booking_date_month').each(function() {
        setTimeout(function() {
            console.log('paused');
            mykeypress();
        }, time);
        time += 2000;
    });

  }

      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page


      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS


      }
    },
    // About us page, note the change from about-us to about_us.
    'shop': {
      init: function() {
        // JavaScript to be fired on the shop page
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
