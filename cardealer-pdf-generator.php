<?php
/**
 * Plugin Name:       Car Dealer - PDF Generator
 * Plugin URI:        http://www.potenzaglobalsolutions.com/
 * Description:       This plugin contains PDF generation functionality for the "Car Dealer" theme.
 * Version:           2.0.2
 * Author:            Potenza Global Solutions
 * Author URI:        http://www.potenzaglobalsolutions.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cardealer-pdf-generator
 * Domain Path:       /languages
 *
 * @package cardealer-pdf-generator
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'CDPG_PATH' ) ) {
	define( 'CDPG_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CDPG_URL' ) ) {
	define( 'CDPG_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'CDPG_VERSION' ) ) {
	define( 'CDPG_VERSION', '2.0.2' );
}

require_once trailingslashit( CDPG_PATH ) . 'lib-tcpdf/lib-tcpdf.php';

/**
 * Update pdf sample template fields
 */
function cdpg_update_reset_pdf_sample_template_fields() {
	require_once trailingslashit( CDPG_PATH ) . 'lib/samples/samples.php';
	$field = get_field_object( 'field_589ac266afdf9', 'options' );
	if ( ! have_rows( 'html_templates', 'option' ) ) {
		for ( $i = 1; $i <= 3; $i++ ) {
			if ( isset( $field['sub_fields'] ) && is_array( $field['sub_fields'] ) ) {
				foreach ( $field['sub_fields'] as $sub_field ) {
					if ( 1 === (int) $i || '1' === $i ) {
						$pdf_sample = cardealer_pdf_sample_1();
					} elseif ( 2 === (int) $i || '2' === $i ) {
						$pdf_sample = cardealer_pdf_sample_2();
					} elseif ( 3 === (int) $i || '3' === $i ) {
						$pdf_sample = cardealer_pdf_sample_3();
					}
					if ( 'templates_title' === $sub_field['name'] ) {
						$stat_title = update_sub_field( array( $field['key'], $i, $sub_field['key'] ), 'PDF Template ' . $i, 'options' );
					}
					if ( 'template_content' === $sub_field['name'] ) {
						$stat_content = update_sub_field( array( $field['key'], $i, 'template_content' ), $pdf_sample, 'options' );
					}
				}
			}
		}
		update_option( 'options_html_templates', 3 );
		update_option( '_options_html_templates', 'field_589ac266afdf9' );
		update_option( 'is_pdf_sample_templates', 'yes' );
	}
}

add_action( 'admin_init', 'cdpg_pdf_sample_templates', 21 );
if ( ! function_exists( 'cdpg_pdf_sample_templates' ) ) {
	/**
	 * PDF sample templates.
	 */
	function cdpg_pdf_sample_templates() {
		global $pagenow;

		if ( 'plugins.php' === $pagenow || 'themes.php' === $pagenow ) {
			/* Checks to see if the acf pro plugin is activated  */
			$is_pdf_sample_templates = get_option( 'is_pdf_sample_templates' );

			$plugin = 'advanced-custom-fields-pro/acf.php';
			if (
				( in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( $plugin ) ) )
				&& 'yes' !== $is_pdf_sample_templates
			) {
				cdpg_update_reset_pdf_sample_template_fields();
			}
		}
	}
}

add_action( 'wp_ajax_reset_pdf_sample_template_fields', 'cdpg_reset_pdf_sample_template_fields' );
add_action( 'wp_ajax_nopriv_reset_pdf_sample_template_fields', 'cdpg_reset_pdf_sample_template_fields' );

/**
 * Reset pdf sample template fields.
 */
function cdpg_reset_pdf_sample_template_fields() {
	$response['status'] = 'error';
	$response['msg']    = '';

	if ( isset( $_POST['nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'pdf_reset_nonce' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		delete_field( 'html_templates', 'option' ); // Delete exhisting field.
		delete_option( 'is_pdf_sample_templates' );
		cdpg_update_reset_pdf_sample_template_fields();
		$response['status'] = 'success';
		$response['msg']    = '';
	}
	wp_send_json( $response );
	exit();
}

add_action( 'admin_enqueue_scripts', 'cdpg_admin_enqueue_scripts' );
if ( ! function_exists( 'cdpg_admin_enqueue_scripts' ) ) {
	/**
	 * Add script and style in wp-admin side.
	 */
	function cdpg_admin_enqueue_scripts() {

		wp_register_script( 'cdpg-pdf-generator', trailingslashit( CDPG_URL ) . 'js/pdf-generator.js', array(), CDPG_VERSION, true );

		// Add message for pdf brochare.
		wp_localize_script(
			'cdpg-pdf-generator',
			'cars_pdf_message',
			array(
				'ajaxurl'               => admin_url( 'admin-ajax.php' ),
				'cdhl_url'              => CDPG_URL,
				'pdf_generated_message' => wp_kses(
					sprintf(
						'<div id="generate-pdf-notice" class="notice notice-success"><p>%1$s</p></div>',
						__( 'PDF generated successfully. Generated PDF is assigned to the <strong>PDF Brochure</strong> field.', 'cardealer-pdf-generator' )
					),
					array(
						'div'    => array(
							'id'    => true,
							'class' => true,
						),
						'p'      => array(),
						'strong' => array(),
					)
				),
				'download_pdf_str'      => esc_html__( 'Download PDF', 'cardealer-pdf-generator' ),
			)
		);

		if ( 'cars' === get_post_type() || ( isset( $_GET['page'] ) && 'pdf_generator' === $_GET['page'] ) ) {
			wp_enqueue_script( 'cdpg-pdf-generator' );
		}
	}
}

/**
 * Include files.
 *
 * @return void
 */
function cdpg_include_files() {
	require_once trailingslashit( CDPG_PATH ) . 'inc/cpts/cars.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	require_once trailingslashit( CDPG_PATH ) . 'inc/acf/fields/pdf-generator.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	require_once trailingslashit( CDPG_PATH ) . 'inc/cdpg-option-pages.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
}
add_action( 'init', 'cdpg_include_files' );
