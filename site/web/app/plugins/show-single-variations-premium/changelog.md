**v1.1.15** (2 Mar 2019)  
[fix] Security Fix  

**v1.1.14** (6 Dec 2018)  
[new] Added WooCommerce Ajax Filters by BeRocket compatibility  
[update] Ensured compatibility with WP 5.0  
[update] Ensured compatibility with Woo 3.5.0  
[update] Update deps  
[fix] Product visibility doesn't save when using a translated 'save' button on the product-edit page  
[fix] Improve compatibility with themes that rely on the type-product class on the loop  
[fix] Send all the parameters when triggering the switch_theme hook to avoid issues with other plugins/themes listening to this action  
[fix] Fatal error when themes change the $product global to a string  
[fix] Issue where checkboxes weren't saving  
[fix] Avada compatibility doesn't load on normal pages using the products shortcode  

**v1.1.13** (10 Sep 2018)  
[new] Atelier compatibility  
[new] Avada compatibility  
[new] Leto compatibility  
[change] Use product methods when adding loop classes  
[change] Refresh variation titles on product save  
[update] Implement Iconic core classes  
[update] Update dependencies  
[fix] Ensure taxonomies are assigned when processing visibility  
[fix] WP All Export visibility formatting (using official visibility field)  
[fix] WP All Import compatibility update (v4.5.5)  
[fix] Fix typo in query class for `include_children`  
[fix] Make sure filtered variation settings are maintained when updating via the API  
[fix] Ensure term counts are correct  
[fix] Ensure variations are only saved once  
[fix] Clear term count transient when saving attributes to variation  

**v1.1.12** (11 Jun 2018)  
[update] Update Freemius  
[update] Update POT  
[update] Don't load settings framework on frontend  
[fix] Issue when filtering visibility for non products  
[fix] Fix sorting issue on some category pages  
[fix] Fix issue when saving "Exclude from filtered results"  
[fix] Ensure products hidden from filtered don't show in FacetWP  
[fix] Make sure attributes "not for variations" work with variations when filtered via FacetWP  
[fix] Make sure filtered works for fwp_ and _ prefixes when using FacetWP  

**v1.1.11** (11 Apr 2018)  
[update] Add WP All Export compatibility  
[update] Ability to add products to show only in the product listings. Not purchasable on the product page.  
[update] FacetWP compatibility  
[update] Update freemius  
[update] Add POT file  
[fix] Featured/add to cart settings issue when stock is managed and variation is updated/purchased  
[fix] Ajax add to cart button when product is not purchasable  
[fix] Cart item links for hidden variations  
[fix] Flatsome lightbox button issue  
[fix] non-numeric value bug in term counts  
[fix] Hide variations if parent is not published  
[fix] Assume variations in cart are visible  
[fix] Don't show disabled variations in listings  
[fix] Issue preventing WP CLI from running  
[fix] Show variations in filtered when not shown in catalog  
[fix] Variation order when using a shortcode  
[fix] Fix links to docs

**v1.1.10** (7 Feb 2018)  
[update] Update Freemius  
[update] Update settings framework  
[update] Allow variation taxonomies to be filtered with iconic_wssv_variation_taxonomies  
[update] Allow add to cart button text to be filtered with iconic_wssv_add_to_cart_button_text  
[update] Add variations to related products module  
[update] Allow basic tags in variation title  
[update] Add Flatsome compatibility  
[update] Add option to exclude parent from filtered results  
[fix] Set transient for each term count instead of combined  
[fix] Fix menu order of variations when parent is set to menu_order 0  
[fix] Fix when saving "unset" checkboxes  
[fix] Term counts incorrect when not showing out of stock items  
[fix] Variation term counts auto reset when Woo resets product term counts  
[fix] Fix filtered visibility when bulk editing  
[fix] Prevent API update removing "featured" setting  
[fix] Only run menu order methods on frontend  
[fix] Don't escape button text  
[fix] Allow br in variation title

**v1.1.9** (9 Sep 2017)  
[update] Variations inherit parent star ratings  
[update] Variations inherit parent menu_order  
[update] Add is_visible filter to bypass parent settings  
[fix] Fix issue with bulk toggling visibility settings  
[fix] Disabled add to cart button not working  
[fix] Make sure hidden variations are not shown  
[fix] Issue where variation visibility was reset on bulk actions  
[fix] Potential issue where is_ajax() is undefined  
[fix] Prevent variations appearing in catalog after purchase  
[fix] Variation titles being overwritten

**v1.1.8** (7 Aug 2017)  
[fix] Visibility issues with WP All Import script for new variations  
[fix] wp_mail definition issue  
[fix] Remove Envato update script

**v1.1.7** (1 Jun 2017)  
[update] New licensing system

**v1.1.6** (24 May 2017)  
[update] WP All Import script for new WooCommerce  
[update] Move total sales update to batch process action in settings page  
[fix] Set new "outofstock" visibility  
[fix] Hide draft products from the catalog  
[fix] Only add button classes to variations  
[fix] Issue on with product visibility in Woo 3.*  
[fix] Set new "featured" visibility  
[fix] Only use AJAX add to cart if option is enabled  
[fix] Issue when new variations were added and no visibility settings were saved  
[fix] Error when sorting by popularity  
[fix] Total sales updated when new order placed

**v1.1.5** (6 may 2017)  
[fix] Modify loop class so it assigns correctly  
[fix] Fix database prefix for one method  
[fix] Make sure variations added via "Create all variations" are updated  
[fix] Add new taxonomy terms for visibility  
[fix] Fix filter and category counts  
[fix] Toggle "Show in..." to account for new visibility terms

**v1.1.4** (2 April 2017)  
[update] WooCommerce 3.0.0 compatibility  
[fix] Term count when not int issue

**v1.1.3** (22 Dec 2016)  
[update] Add filter to catalog add to cart button  
[update] Remove dashboard  
[fix] Allow WP All Import functions to run when not in admin  
[fix] Issue where wp_query was too resource intensive for getting term counts  
[fix] Update term counts when running a bulk toggle

**v1.1.2** (19 Sep 2016)  
[fix] Remove menu order filter, for now. Need to reassess and add back in.  
[fix] Don't get variations with missing parents  
[update] Add Iconic dashboard  
[fix] Don't get broken variations in shortcode  
[fix] Add filter to price filter widget to include variations (pending WooCommerce update)

**v1.1.1** (24 Aug 2016)  
[fix] Make sure the URL is always using a variation  
[fix] Issue where all variations were showing when translating a product via WPML  
[fix] Bulk actions reset the filter counts  
[fix] Issue when adding attributes not used for variations  
[fix] Disable function that updates product order temporarily  
[fix] Prevent infinite loop on wp_update_post  
[fix] Issue where no ID is present for title  
[update] Adding ability to import data to variations when using WP All Import

**v1.1.0** (14 Jun 2016)  
[update] Compatibility with WooCommerce 2.6.0 - filter counts will work again once WooCommerce release the next patch
[update] Change method for checking if parent variable product is published

**v1.0.13** (9 May 2016)  
[fix] Add padding to variation menu order to fix when there's more than 10 variations  
[fix] Load plugin textdomain

**v1.0.12** (4 May 2016)  
[update] Official WPML compatibility

**v1.0.11** (18 Apr 2016)  
[fix] Fix manual product ordering  
[fix] Variations were always being enabled one product save  
[fix] Fix product status transition to account for draft products

**v1.0.10** (10 Feb 2016)  
[update] Option to disable "Add to Cart" button per variation  
[update] Add "non-variation" attributes to variations on product save  
[fix] Change layered nav query to use filter  
[update] Add add_to_cart class on button

**v1.0.9** (3 Feb 2016)  
[fix] Update category counts on frontend to include variations  
[fix] Update attribute counts on frontend to include variations

**v1.0.8** (20 Jan 2016)  
[update] Compatibility with Atelier theme

**v1.0.7** (15 Jan 2016)  
[fix] Variations are now added to filter counts on save  
[update] Bulk option to update filter counts  
[update] Variations are now added to tags  
[fix] Variations are added when a category is added to an existing product

**v1.0.6** (30 Dec 2015)  
[update] You can now set individual variations as "Featured"

**v1.0.5** (30 Dec 2015)  
[fix] Index 'key' not set  
[update] Include display title in search  
[update] Bulk action - Update "total_sales" for popularity ordering  
[fix] Variations displayed in popularity order

**v1.0.4** (17 Dec 2015)  
[update] Added compatibility for shortcodes  
[fix] Variations removed if parent is deleted

**v1.0.3** (18 Nov 2015)  
[fix] Change is_purchasable method  
[fix] Change permalink method to cover more ground  
[update] Bulk actions for toggling visibility  
[fix] When variations filtered, they were being limited to 12

**v1.0.2** (8 Nov 2015)  
[fix] Price filter only fix

**v1.0.1** (6 Nov 2015)  
[fix] Change "add to cart" to "select options" if not all attributes for variation have been selected

**v1.0.0** (2 Nov 2015)  
Initial Release