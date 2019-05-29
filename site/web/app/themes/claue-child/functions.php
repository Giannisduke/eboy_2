<?php
/**
 * Claue child theme functions.
 *
 * @since   1.0.0
 * @package Claue
 */

/**
 * Enqueue style of child theme
 */
function jas_claue_enqueue_script() {
	wp_enqueue_style( 'jas-claue-parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'jas_claue_enqueue_script' );

/**
 * Restore CSV upload functionality for WordPress 4.9.9 and up
 */
add_filter('wp_check_filetype_and_ext', function($values, $file, $filename, $mimes) {
	if ( extension_loaded( 'fileinfo' ) ) {
		// with the php-extension, a CSV file is issues type text/plain so we fix that back to
		// text/csv by trusting the file extension.
		$finfo     = finfo_open( FILEINFO_MIME_TYPE );
		$real_mime = finfo_file( $finfo, $file );
		finfo_close( $finfo );
		if ( $real_mime === 'text/plain' && preg_match( '/\.(csv)$/i', $filename ) ) {
			$values['ext']  = 'csv';
			$values['type'] = 'text/csv';
		}
	} else {
		// without the php-extension, we probably don't have the issue at all, but just to be sure...
		if ( preg_match( '/\.(csv)$/i', $filename ) ) {
			$values['ext']  = 'csv';
			$values['type'] = 'text/csv';
		}
	}
	return $values;
}, PHP_INT_MAX, 4);
