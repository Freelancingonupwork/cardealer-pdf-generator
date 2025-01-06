<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Option Page
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( function_exists( 'acf_add_options_sub_page' ) ) {
	acf_add_options_sub_page(
		array(
			'page_title'  => esc_html__( 'Vehicle Brochure Generator', 'cardealer-pdf-generator' ),
			'menu_title'  => esc_html__( 'PDF Brochure Generator', 'cardealer-pdf-generator' ),
			'parent_slug' => 'edit.php?post_type=cars',
			'capability'  => 'manage_options',
			'menu_slug'   => 'pdf_generator',
		)
	);
}
