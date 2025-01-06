<?php
/**
 * Theme function code for vehicle PDF generator.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 * @version 1.0.0
 */

require_once ABSPATH . 'wp-load.php';

$pdf_template_title = isset( $_POST['pdf_template_title'] ) ? sanitize_text_field( wp_unslash( $_POST['pdf_template_title'] ) ) : '';
$do_pdf_post_id     = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';

$post_title = get_the_title( $do_pdf_post_id );
$new_title  = sanitize_title( $post_title );
$new_title  = preg_replace('#[^A-Za-z0-9. -]+#','', $new_title);
$file_name  = $do_pdf_post_id . '_' . $new_title . '.pdf';
$args       = array(
	'post_id'    => $do_pdf_post_id,
	'post_title' => $new_title,
);

/**
 * Filters vehicle PDF brochure file name.
 *
 * @since 1.0
 * @param string  $file_name    Filename of the PDF brochure.
 * @param array   $args Array of arguments used.
 * @visible       true
 */
$file_name              = apply_filters( 'cdhl_pdf_brochure_name', $file_name, $args );
$has_custom_margin      = false;
$template_margin_top    = 'default';
$template_margin_bottom = 'default';
$template_margin_left   = 'default';
$template_margin_right  = 'default';
$html_text              = '';

if ( have_rows( 'html_templates', 'option' ) ) :
	while ( have_rows( 'html_templates', 'option' ) ) :
		the_row();
		$template_title = get_sub_field( 'templates_title' );
		if ( $template_title === $pdf_template_title ) {
			$html_text     = get_sub_field( 'template_content' );
			$custom_margin = get_sub_field( 'custom_margin_set' );
			if ( $custom_margin && is_array( $custom_margin ) && ! empty( $custom_margin ) && 'yes' === $custom_margin[0] ) {
				$has_custom_margin         = true;
				$templates_margin_top_v    = get_sub_field( 'templates_margin_top' );
				$templates_margin_bottom_v = get_sub_field( 'templates_margin_bottom' );
				$templates_margin_left_v   = get_sub_field( 'templates_margin_left' );
				$templates_margin_right_v  = get_sub_field( 'templates_margin_right' );

				if ( false !== $templates_margin_top_v ) {
					$template_margin_top = $templates_margin_top_v;
				}
				if ( false !== $templates_margin_bottom_v ) {
					$template_margin_bottom = $templates_margin_bottom_v;
				}
				if ( false !== $templates_margin_left_v ) {
					$template_margin_left = $templates_margin_left_v;
				}
				if ( false !== $templates_margin_right_v ) {
					$template_margin_right = $templates_margin_right_v;
				}
			}
		}
	endwhile;
endif;

$car_price = '';
if ( function_exists( 'cardealer_get_car_price_array' ) ) {
	$car_price = cardealer_get_car_price_array( $do_pdf_post_id );
}

$car_images = get_field( 'car_images', $do_pdf_post_id );

$citympg  = get_field( 'city_mpg', $do_pdf_post_id );
$city_mpg = ( isset( $citympg ) && ! empty( $citympg ) ) ? $citympg = $citympg : '';

$highwaympg  = get_field( 'highway_mpg', $do_pdf_post_id );
$high_waympg = ( isset( $highwaympg ) && ! empty( $highwaympg ) ) ? $highwaympg : '';

$vehicle_tax_label = get_field( 'tax_label', $do_pdf_post_id );
$tax_label         = ( isset( $vehicle_tax_label ) && ! empty( $vehicle_tax_label ) ) ? $vehicle_tax_label : '';

$getvehiclestatus = get_field( 'car_status', $do_pdf_post_id );
$vehicle_status   = ( isset( $getvehiclestatus ) && ! empty( $getvehiclestatus ) ) ? $getvehiclestatus : '';

$vrs                   = cardealer_get_vehicle_review_stamps( $do_pdf_post_id, true );
$vehicle_review_stamps = '';
if ( ! empty( $vrs ) ) {
	$vrs_stamp_logo = array_column( $vrs, 'img_url' );
	$size           = apply_filters(
		'cd_vehicle_review_stamps_pdf_width_height',
		array(
			'width'  => 100,
			'height' => 70,
		)
	);
	if ( ! empty( $vrs_stamp_logo ) ) {
		$review_stamps_html = '<ul style="list-style-type:none">';
		foreach ( $vrs_stamp_logo as $logo ) {
			$review_stamps_html .= '<li><img src="' . esc_url( $logo ) . '" height="' . $size['height'] . '" width="' . $size['width'] . '"/></li>';
		}
		$review_stamps_html   .= '</ul>';
		$vehicle_review_stamps = $review_stamps_html;
	}
}

$overview         = get_field( 'vehicle_overview', $do_pdf_post_id );
$vehicle_overview = ( isset( $overview ) && ! empty( $overview ) ) ? $overview : '';

$caryear  = wp_get_post_terms( $do_pdf_post_id, 'car_year' );
$car_year = ( ! is_wp_error( $caryear ) && isset( $caryear ) && ! empty( $caryear ) ) ? $caryear[0]->name : '';

$carmake  = wp_get_post_terms( $do_pdf_post_id, 'car_make' );
$car_make = ( ! is_wp_error( $carmake ) && isset( $carmake ) && ! empty( $carmake ) ) ? $carmake[0]->name : '';

$carmodel  = wp_get_post_terms( $do_pdf_post_id, 'car_model' );
$car_model = ( ! is_wp_error( $carmodel ) && isset( $carmodel ) && ! empty( $carmodel ) ) ? $carmodel[0]->name : '';

$bodystyle  = wp_get_post_terms( $do_pdf_post_id, 'car_body_style' );
$body_style = ( ! is_wp_error( $bodystyle ) && isset( $bodystyle ) && ! empty( $bodystyle ) ) ? $bodystyle[0]->name : '';

$condition     = wp_get_post_terms( $do_pdf_post_id, 'car_condition' );
$car_condition = ( ! is_wp_error( $condition ) && isset( $condition ) && ! empty( $condition ) ) ? $condition[0]->name : '';

$mileage     = wp_get_post_terms( $do_pdf_post_id, 'car_mileage' );
$car_mileage = ( ! is_wp_error( $mileage ) && isset( $mileage ) && ! empty( $mileage ) ) ? $mileage[0]->name : '';

$transmission     = wp_get_post_terms( $do_pdf_post_id, 'car_transmission' );
$car_transmission = ( ! is_wp_error( $transmission ) && isset( $transmission ) && ! empty( $transmission ) ) ? $transmission[0]->name : '';

$drivetrain     = wp_get_post_terms( $do_pdf_post_id, 'car_drivetrain' );
$car_drivetrain = ( ! is_wp_error( $drivetrain ) && isset( $drivetrain ) && ! empty( $drivetrain ) ) ? $drivetrain[0]->name : '';

$engine     = wp_get_post_terms( $do_pdf_post_id, 'car_engine' );
$car_engine = ( ! is_wp_error( $engine ) && isset( $engine ) && ! empty( $engine ) ) ? $engine[0]->name : '';

$fuel_economy     = wp_get_post_terms( $do_pdf_post_id, 'car_fuel_economy' );
$car_fuel_economy = ( ! is_wp_error( $fuel_economy ) && isset( $fuel_economy ) && ! empty( $fuel_economy ) ) ? $fuel_economy[0]->name : '';

$exterior_color     = wp_get_post_terms( $do_pdf_post_id, 'car_exterior_color' );
$car_exterior_color = ( ! is_wp_error( $exterior_color ) && isset( $exterior_color ) && ! empty( $exterior_color ) ) ? $exterior_color[0]->name : '';

$interior_color     = wp_get_post_terms( $do_pdf_post_id, 'car_interior_color' );
$car_interior_color = ( ! is_wp_error( $interior_color ) && isset( $interior_color ) && ! empty( $interior_color ) ) ? $interior_color[0]->name : '';

$carstock         = wp_get_post_terms( $do_pdf_post_id, 'car_stock_number' );
$car_stock_number = ( ! is_wp_error( $carstock ) && isset( $carstock ) && ! empty( $carstock ) ) ? $carstock[0]->name : '';

$vin_number     = wp_get_post_terms( $do_pdf_post_id, 'car_vin_number' );
$car_vin_number = ( ! is_wp_error( $vin_number ) && isset( $vin_number ) && ! empty( $vin_number ) ) ? $vin_number[0]->name : '';

$trim     = wp_get_post_terms( $do_pdf_post_id, 'car_trim' );
$car_trim = ( ! is_wp_error( $trim ) && isset( $trim ) && ! empty( $trim ) ) ? $trim[0]->name : '';

$fuel_type     = wp_get_post_terms( $do_pdf_post_id, 'car_fuel_type' );
$car_fuel_type = ( ! is_wp_error( $fuel_type ) && isset( $fuel_type ) && ! empty( $fuel_type ) ) ? $fuel_type[0]->name : '';

$option           = '';
$features_options = wp_get_post_terms( $do_pdf_post_id, 'car_features_options' );
if ( ! is_wp_error( $features_options ) && ! empty( $features_options ) ) {
	foreach ( $features_options as $features_option ) {
		$option .= '<li>' . $features_option->name . '</li>';
	}
}

$attributes                         = array();
$attribute['year']                  = $car_year;
$attribute['make']                  = $car_make;
$attribute['model']                 = $car_model;
$attribute['body_style']            = $body_style;
$attribute['condition']             = $car_condition;
$attribute['mileage']               = $car_mileage;
$attribute['transmission']          = $car_transmission;
$attribute['drivetrain']            = $car_drivetrain;
$attribute['engine']                = $car_engine;
$attribute['fuel_economy']          = $car_fuel_economy;
$attribute['exterior_color']        = $car_exterior_color;
$attribute['interior_color']        = $car_interior_color;
$attribute['stock_number']          = $car_stock_number;
$attribute['vin_number']            = $car_vin_number;
$attribute['features_options']      = $option;
$attribute['trim']                  = $car_trim;
$attribute['city_mpg']              = $city_mpg;
$attribute['high_waympg']           = $high_waympg;
$attribute['vehicle_overview']      = $vehicle_overview;
$attribute['vehicle_review_stamps'] = $vehicle_review_stamps;
$attribute['vehicle_status']        = $vehicle_status;
$attribute['tax_label']             = $tax_label;
$attribute['fuel_type']             = $car_fuel_type;

$taxonomies_raw = get_object_taxonomies( 'cars' );
$tax_arr = array();
foreach ( $taxonomies_raw as $new_tax ) {
	$new_tax_obj = get_taxonomy( $new_tax );
	if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
		$term = wp_get_post_terms( $do_pdf_post_id, $new_tax_obj->name );
		if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
			$label              = $new_tax_obj->name;
			$attributes[$label] = ( isset($term[0]->name) && ! empty($term[0]->name) ) ? $term[0]->name : '';
		}
	}
}

/**
 * Filters the list of attributes to be added in PDF brochure of the vehicle.
 *
 * @since 1.0
 * @param array   $attribute       Vehicle attributes array.
 * @param id      $do_pdf_post_id  Vehicle ID.
 * @visible       true
 */
$attribute = apply_filters( 'cd_vehicle_attributes_pdf', $attribute, $do_pdf_post_id );
foreach ( $attribute as $key => $val ) {
	$html_text = str_replace( '{{' . $key . '}}', $val, $html_text );
}


// ============================================================+
// File name   : example_061.php
// Begin       : 2010-05-24
// Last Update : 2014-01-25
//
// Description : Example 061 for TCPDF class
// XHTML + CSS
// ============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 */

// Include the main TCPDF library (search for installation path).
require_once trailingslashit( CDPG_PATH ) . 'lib/tc-pdf-custom/tcpdf-config.php';
cdpg_load_tcpdf();

// create new PDF document.
$pdf = new TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );

// set document information.
$pdf->SetCreator( PDF_CREATOR );
$pdf->SetAuthor( 'cardealer' );
// set margins.
if ( 'default' === $template_margin_top ) {
	$template_margin_top = PDF_MARGIN_TOP;
}
if ( 'default' === $template_margin_bottom ) {
	$template_margin_bottom = PDF_MARGIN_BOTTOM;
}
if ( 'default' === $template_margin_left ) {
	$template_margin_left = PDF_MARGIN_LEFT;
}
if ( 'default' === $template_margin_right ) {
	$template_margin_right = PDF_MARGIN_RIGHT;
}

$template_margin_top    = apply_filters( 'template_margin_top', $template_margin_top );
$template_margin_bottom = apply_filters( 'template_margin_bottom', $template_margin_bottom );
$template_margin_left   = apply_filters( 'template_margin_left', $template_margin_left );
$template_margin_right  = apply_filters( 'template_margin_right', $template_margin_right );

$pdf->SetMargins( $template_margin_left, $template_margin_top, $template_margin_right );
$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );

// set auto page breaks.
$pdf->SetAutoPageBreak( true, $template_margin_bottom );

// set image scale factor.
$pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );

// set some language-dependent strings (optional).
if ( @file_exists( trailingslashit( CDPG_PATH ) . 'lib/tc-pdf-custom/lang/eng.php' ) ) {
	require_once trailingslashit( CDPG_PATH ) . 'lib/tc-pdf-custom/lang/eng.php';
	$pdf->setLanguageArray( $l );
}

// ---------------------------------------------------------

// set font.
$pdf->SetFont( 'freeserif', '', 12 );

$pdf = apply_filters( 'cardealer_brochure_generator_tcpdf_object', $pdf );

// add a page.
$pdf->AddPage();

/**
 * NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

// define some HTML content with style.
$html_ul = '';
if ( isset( $attribute['features_options'] ) && ! empty( $attribute['features_options'] ) ) {
	$my_array = explode( '-', $attribute['features_options'] );
	$counter  = count( $my_array );
	$html_ul  = '<ul>';
	$i        = '0';
	foreach ( $my_array as $feature ) :
		if ( 0 !== $i ) {
			$html_ul .= '<li>' . $feature . '</li>';
		}
		$i++;
	endforeach;
	$html_ul .= '</ul>';
}

$carimage = '';
if ( isset( $car_images ) && ! empty( $car_images ) ) {
	foreach ( $car_images as $image ) :
		$carimage = $image['url'];
		break;
	endforeach;
}
$regprice  = '';
$saleprice = '';
$symbol    = '';

if ( isset( $car_price ) && ! empty( $car_price ) ) {
	$regprice  = $car_price['regular_price'];
	$saleprice = $car_price['sale_price'];
	$symbol    = $car_price['currency_symbol'];
}

$fuel_image = trailingslashit( CDHL_URL ) . 'images/pdf/fuel.jpg';
$city_image = trailingslashit( CDHL_URL ) . 'images/pdf/city.jpg';
$hwy_image  = trailingslashit( CDHL_URL ) . 'images/pdf/hwy.jpg';
$newhtml    = '';
$newhtml    = str_replace( '{{year}}', $car_year, $html_text );
$newhtml    = str_replace( '{{make}}', $car_make, $newhtml );
$newhtml    = str_replace( '{{model}}', $car_model, $newhtml );
$newhtml    = str_replace( '{{body_style}}', $body_style, $newhtml );
$newhtml    = str_replace( '{{condition}}', $car_condition, $newhtml );
$newhtml    = str_replace( '{{mileage}}', $car_mileage, $newhtml );
$newhtml    = str_replace( '{{transmission}}', $car_transmission, $newhtml );
$newhtml    = str_replace( '{{drivetrain}}', $car_drivetrain, $newhtml );
$newhtml    = str_replace( '{{engine}}', $car_engine, $newhtml );
$newhtml    = str_replace( '{{fuel_economy}}', $car_fuel_economy, $newhtml );
$newhtml    = str_replace( '{{exterior_color}}', $car_exterior_color, $newhtml );
$newhtml    = str_replace( '{{interior_color}}', $car_interior_color, $newhtml );
$newhtml    = str_replace( '{{stock_number}}', $car_stock_number, $newhtml );
$newhtml    = str_replace( '{{vin_number}}', $car_vin_number, $newhtml );
$newhtml    = str_replace( '{{features_options}}', $html_ul, $newhtml );
$newhtml    = str_replace( '{{city_mpg}}', $city_mpg, $newhtml );
$newhtml    = str_replace( '{{high_waympg}}', $high_waympg, $newhtml );
$newhtml    = str_replace( '{{vehicle_overview}}', $vehicle_overview, $newhtml );
$newhtml    = str_replace( '{{vehicle_review_stamps}}', $vehicle_review_stamps, $newhtml );
$newhtml    = str_replace( '{{tax_label}}', $tax_label, $newhtml );
$newhtml    = str_replace( '{{vehicle_status}}', $vehicle_status, $newhtml );
$newhtml    = str_replace( '{{image}}', $carimage, $newhtml );
$newhtml    = str_replace( '{{trim}}', $car_trim, $newhtml );
$newhtml    = str_replace( '{{fuel_image}}', $fuel_image, $newhtml );
$newhtml    = str_replace( '{{city_image}}', $city_image, $newhtml );
$newhtml    = str_replace( '{{hwy_image}}', $hwy_image, $newhtml );
$newhtml    = str_replace( '{{fuel_type}}', $car_fuel_type, $newhtml );
$newhtml    = str_replace( '{{regular_price}}', $regprice, $newhtml );

foreach ( $taxonomies_raw as $new_tax ) {
	$new_tax_obj = get_taxonomy( $new_tax );
	if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
		$term = wp_get_post_terms( $do_pdf_post_id, $new_tax_obj->name );
		$name = $new_tax_obj->name;
		$val  = '';
		if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
			$val  = ( isset($term[0]->name) && ! empty($term[0]->name) ) ? $term[0]->name : '';
			//echo $name . '<===>' . $val;
		}
		$newhtml    = str_replace( '{{' . $name . '}}', $val, $newhtml );
	}
}

if ( $saleprice || apply_filters( 'cardealer_pdf_generator_show_zero_sale_price', true ) ) {
	$newhtml = str_replace( '{{sale_price}}', $saleprice, $newhtml );
} else {
	$newhtml = apply_filters( 'cardealer_pdf_generator_sale_price_empty', $newhtml, '{{sale_price}}', $saleprice, $pdf_template_title );
}

$newhtml = str_replace( '{{currency_symbol}}', $symbol, $newhtml );
/**
 * Filters HTML structure of the vehicle PDF brochure.
 *
 * @since 1.0
 * @param string  $newhtml              HTML structure of the PDF brochure.
 * @param int     $do_pdf_post_id       Vehicle ID.
 * @visible       true
 */
$newhtml = apply_filters( 'cd_vehicle_brochure_html', $newhtml, $do_pdf_post_id );

// output the HTML content.
$pdf->writeHTML( $newhtml, true, false, true, false, '' );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page.
$pdf->lastPage();

// Close and output PDF document.
// This method has several options, check the source code documentation for more information.
$pdf->Output( trailingslashit( CDPG_PATH ) . 'lib/tc-pdf-custom/uploads/' . $file_name, 'F' );


// Need to require these files.
if ( ! function_exists( 'media_handle_upload' ) ) {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
}

$url = trailingslashit( CDPG_URL ) . 'lib/tc-pdf-custom/uploads/' . $file_name;
$tmp = download_url( $url );

$file_array = array();

// Set variables for storage.
// fix file filename for query strings.
preg_match( '/[^\?]+\.(jpg|jpe|jpeg|gif|png|pdf)/i', $url, $matches );
$file_array['name']     = basename( $matches[0] );
$file_array['tmp_name'] = $tmp;

// If error storing temporarily, unlink.
if ( is_wp_error( $tmp ) ) {
	if ( file_exists( $file_array['tmp_name'] ) ) {
		@unlink( $file_array['tmp_name'] );
	}
	$file_array['tmp_name'] = '';
}
// do the validation and storage stuff.
$iid = media_handle_sideload( $file_array, $do_pdf_post_id, $post_title );

// If error storing permanently, unlink.
if ( is_wp_error( $iid ) ) {
	if ( file_exists( $file_array['tmp_name'] ) ) {
		@unlink( $file_array['tmp_name'] );
		return $iid;
	}
}

// change the file attachment for pdf brochure.
update_post_meta( $do_pdf_post_id, 'pdf_file', $iid );
$src = wp_get_attachment_url( $iid );

// unlink pdf file generated inside CDHL plugin.
if ( isset( $src ) ) {
	if ( file_exists( trailingslashit( CDPG_PATH ) . 'lib/tc-pdf-custom/uploads/' . $file_name ) ) {
		@unlink( trailingslashit( CDPG_PATH ) . 'lib/tc-pdf-custom/uploads/' . $file_name );
	}
}

if ( 'cardealer_debug_generate_pdf' === $_POST['action'] ) {
	return $src;
}

return $src;
