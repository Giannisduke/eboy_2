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
	  $marka_icon = '<img class="ico" src=" ' .get_template_directory_uri() .'/dist/images/marka_'. get_field('μάρκα') .'.svg">';
	?>

<div class="d-flex flex-row justify-content-between align-items-center">
  <div class="py-2">
		<?php echo $marka_icon; ?>
	</div>
  <div class="p-0">
		<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">
			Εκτύπωση Κάρτας
		</button>

		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Εκτύπωση Κάρτας</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="d-flex flex-row">
							<div class="p-0">
								<?php
								if (get_wc_product_qr_code_src($product->get_id())) {
										echo '<div class="wc-qr-codes-container">';
										echo '<img class="wcqrc-qr-code-img" src="' . get_wc_product_qr_code_src($product->get_id()) . '" alt="QR Code" />';
										echo '</div>';
								} ?>
							</div>
							<div class="p-0"><div class="d-flex flex-column">
								<div class="px-3"><?php the_title( '<h3 class="product_title entry-title">', '</h3>' );?></div>
								<div class="p-0"><?php echo $product->get_price_html(); ?></div>

							</div>
						</div>

						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Κλεισιμο</button>
						<button type="button" class="btn btn-primary">Εκτύπωση</button>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<?php endif; ?>
