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




      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
        var jumboHeight = $('.jumbotron').outerHeight();
        function parallax(){
            var scrolled = $(window).scrollTop();
            $('.jumbotron-bg').css('height', (jumboHeight-scrolled-680) + 'px');
        }
        $(window).scroll(function(e){
            parallax();
        });
        $(document).ready(function () {
            $('div.hidden').fadeIn(500).removeClass('hidden');
            parallax();
        });

        $('.carousel').carousel({
              pause: false,
              interval: false
          });
          //$('#startdate').daterangepicker();
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
             $.getScript("https://eboy.gr/app/plugins/facetwp-bookings/assets/vendor/daterangepicker/moment.min.js?ver=3.2.12");
          }
          function daterangepicker() {
             $.getScript("https://eboy.gr/app/plugins/facetwp-bookings/assets/vendor/daterangepicker/daterangepicker.min.js?ver=3.2.12");
          }


          function mykeypress(){
              $('.uppend:first').keypress().removeClass('uppend').addClass('test');
          }




          $(document).on('facetwp-loaded', function() {
            var str1 = $(".facetwp-date").val(),
                  str2 = str1.slice(5, 7),
                   str3 = str1.slice(8, 11),
                   str4 = str1.slice(24,26);
                   str5 = str1.slice(27,30);
            console.log(str1);
            console.log(str2); // OUTPUT: he morn
            console.log(str3);
            console.log(str4);
            console.log(str5);
             $('.booking_date_month').val(str2);
             $('.booking_date_day').val(str3);
            // $('.booking_date_day').focus();
             $('.booking_to_date_month').val(str4);
             $('.booking_to_date_day').val(str5);




             console.log("first load");
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


      });

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

      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page


      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
        $( ".carousel-inner .carousel-item" ).first().addClass( "active" );

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
