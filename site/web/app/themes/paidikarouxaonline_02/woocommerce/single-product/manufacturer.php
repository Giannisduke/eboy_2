<?php
/**
 * Single Product manufacturer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<?php if( get_field('μάρκα') ):
	  $marka_icon = '<img class="ico svg" src=" ' .get_template_directory_uri() .'/dist/images/marka_'. get_field('μάρκα') .'.svg">';
	?>

<div class="row">
	<div class="col-2">
		<?php echo $marka_icon; ?>
		</div>
</div>

<?php endif; ?>
