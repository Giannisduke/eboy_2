jQuery(document).ready(function($) {
"use strict";
$("a").prop("href", "#");
$('.car-thumb').on('click',function(){
  var theId = $(this).attr('data-project-id');
  var div = $('.get_product');


    $.ajax({
        type: "POST",
        url: singleprojectajax.ajaxurl,
        data : {action : 'load_single_product_content', post_id: theId },
        success: function(data){
            div.html(data);
        },
        complete: function(){
          booking_form();
          jqueryScript();
          jqueryblockui();
          woocommerce();
          cart_fragments();
          mainjs();
         add_to_cart_variation();
         datepicker();
          single_product();

          underscore();
          date_picker();
            //  $( ".picker" ).datepicker();
          //  console.log('Done!!!: ', response);
        },
        error : function() {
          console.log('error!!!: ', response);
        }
    });

  //  $('#addItem').load("/plugins/woocommerce-bookings/includes/booking-form/class-wc-booking-form.php");
});



function jqueryScript() {
   $.getScript("https://code.jquery.com/jquery-2.1.3.min.js");
}
function jqueryblockui() {
   $.getScript("http://eboy.test/app/plugins/woocommerce/assets/js/jquery-blockui/jquery.blockUI.js?ver=2.70");
}
function jscookie() {
   $.getScript("http://eboy.test/app/plugins/woocommerce/assets/js/js-cookie/js.cookie.js?ver=2.1.4");
}
function woocommerce() {
   $.getScript("http://eboy.test/app/plugins/woocommerce/assets/js/frontend/woocommerce.js?ver=3.5.4");
}
function cart_fragments() {
   $.getScript("http://eboy.test/app/plugins/woocommerce/assets/js/frontend/cart-fragments.js?ver=3.5.4");
}
function mainjs() {
   $.getScript("http://eboy.test/app/themes/carhub_2/dist/scripts/main.js");
}
function single_product() {
   $.getScript("http://eboy.test/app/plugins/woocommerce/assets/js/frontend/single-product.js?ver=3.5.4");
}
function booking_form() {
   $.getScript("http://eboy.test/app/plugins/woocommerce-bookings/assets/js/booking-form.js?ver=1.10.1");
}
function underscore() {
   $.getScript("http://eboy.test/carhub/wp-includes/js/underscore.min.js?ver=1.8.3");
}
function wp_util() {
   $.getScript("http://eboy.test/carhub/wp-includes/js/wp-util.js?ver=5.0.2");
}
function add_to_cart_variation() {
   $.getScript("http://eboy.test/app/plugins/woocommerce/assets/js/frontend/add-to-cart-variation.js?ver=3.5.4");
}
function core() {
   $.getScript("http://eboy.test/carhub/wp-includes/js/jquery/ui/core.min.js?ver=1.11.4");
}
function datepicker() {
   $.getScript("http://eboy.test/carhub/wp-includes/js/jquery/ui/datepicker.min.js?ver=1.11.4");
}
function date_picker() {
   $.getScript("http://eboy.test/app/plugins/woocommerce-bookings/assets/js/date-picker.js?ver=1.10.1");
}
function query_string() {
   $.getScript("http://eboy.test/app/plugins/facetwp/assets/js/src/query-string.js?ver=3.2.12");
}
function favetwp_woocommerce() {
   $.getScript("http://eboy.test/app/plugins/facetwp/includes/integrations/woocommerce/woocommerce.js?ver=3.2.12");
}
function front() {
   $.getScript("http://eboy.test/app/plugins/facetwp/assets/js/dist/front.min.js?ver=3.2.12");
}
function moment() {
   $.getScript("http://eboy.test/app/plugins/facetwp-bookings/assets/vendor/daterangepicker/moment.min.js?ver=3.2.12");
}
function daterangepicker() {
   $.getScript("http://eboy.test/app/plugins/facetwp-bookings/assets/vendor/daterangepicker/daterangepicker.min.js?ver=3.2.12");
}


});
