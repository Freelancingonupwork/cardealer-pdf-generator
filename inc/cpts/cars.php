<?php
if ( ! function_exists( 'cdpg_cpt_cars_edit_columns' ) ) {
	/**
	 * Edit colums
	 *
	 * @param string $columns .
	 */
	function cdpg_cpt_cars_edit_columns( $columns ) {
		$new_fields =
			array_slice(
				$columns,
				0,
				7,
				true
			) +
			array(
				'pdf' => esc_html__( 'Brochure generator', 'cardealer-pdf-generator' ),
			) +
			array_slice( $columns, 7, count( $columns ) - 1, true );
		return $new_fields;
	}
	// add_filter( 'manage_edit-cars_columns', 'cdpg_cpt_cars_edit_columns' );
	add_filter( 'manage_edit-cars_columns', 'cdpg_cpt_cars_edit_columns', 99, 1 );

}

if ( ! function_exists( 'cdpg_cpt_cars_custom_columns' ) ) {
	/**
	 * Custom columns
	 *
	 * @param string $column .
	 * @param string $post_id .
	 */
	function cdpg_cpt_cars_custom_columns( $column, $post_id ) {
		if ( 'cars' === get_post_type( $post_id ) && 'pdf' === $column ) {
			echo cdpg_cars_pdf_meta_box( $post_id );
		}
		return $column;
	}

	add_filter( 'manage_cars_posts_custom_column', 'cdpg_cpt_cars_custom_columns', 99, 2 );
}

if ( ! function_exists( 'cdpg_cars_pdf_meta_box' ) ) {
	/**
	 * Car pdf meta box
	 *
	 * @param string $post_id .
	 */
	function cdpg_cars_pdf_meta_box( $post_id ) {
		global $post;
		ob_start();
		?>
		<div id="pdf_section-<?php echo esc_attr( $post_id ); ?>" class="pdf_section admin-column-pdf-actions">
			<div class="download" id="download-<?php echo esc_attr( $post_id ); ?>">
				<label for="casr_pdf_styles"><?php esc_html_e( 'Choose Template', 'cardealer-pdf-generator' ); ?>:</label>
				<?php
				if ( function_exists( 'have_rows' ) ) {
					if ( have_rows( 'html_templates', 'option' ) ) {
						?>
						<select class="casr_pdf_styles" data-id="<?php echo esc_attr( $post->ID ); ?>" name='casr_pdf_styles' id='casr_pdf_styles'>
							<?php
							while ( have_rows( 'html_templates', 'option' ) ) :
								the_row();
								$templates_title = get_sub_field( 'templates_title' );
								?>
								<option value="<?php echo esc_attr( $templates_title ); ?>"><?php echo esc_html( $templates_title ); ?></option>
							<?php endwhile; ?>
						</select>
						<br /><br />
						<a id="<?php echo esc_attr( $post->ID ); ?>" data-post_id="<?php echo esc_attr( $post->ID ); ?>" class="download-pdf button button-primary" href="javascript:void(0)"><?php esc_html_e( 'Generate', 'cardealer-pdf-generator' ); ?></a><span class="spinner"></span>
						<?php
						$pdf_file     = get_field( 'pdf_file', $post_id );
						$pdf_url      = false;
						$pdf_dl_style = 'display:none;';

						// Fallback for pdf_file returing as attachment ID.
						if ( ! is_array( $pdf_file ) && is_string( $pdf_file ) ) {
							$pdf_file = acf_get_attachment( (int) $pdf_file );
						}

						if ( $pdf_file ) {
							$pdf_file_path = get_attached_file( $pdf_file['ID'] );
							if ( isset( $pdf_file['url'] ) && ! empty( $pdf_file['url'] ) && file_exists( $pdf_file_path ) ) {
								$pdf_url      = $pdf_file['url'];
								$pdf_dl_style = 'display:block;';
							}
						}
						?>
						<div class="downloadlink" id="downloadlink-<?php echo esc_attr( $post_id ); ?>" style="<?php echo esc_attr( $pdf_dl_style ); ?>">
							<?php
							if ( $pdf_url ) {
								?>
								<a class="button button-primary" href="<?php echo esc_url( $pdf_file['url'] ); ?>" target="_blank" download=""><?php echo esc_html__( 'Download PDF', 'cardealer-pdf-generator' ); ?></a>
								<?php
							}
							?>
						</div>
						<?php
					}
				}
				?>
			</div><!-- .download -->
		</div>
		<?php
		return ob_get_clean();
	}
}

add_action( 'wp_ajax_cdpg_generate_pdf', 'cdpg_generate_pdf' );
add_action( 'wp_ajax_nopriv_cdpg_generate_pdf', 'cdpg_generate_pdf' );
if ( ! function_exists( 'cdpg_generate_pdf' ) ) {
	/**
	 * Function cdpg_generate_pdf.
	 */
	function cdpg_generate_pdf() {
		try {
			$url    = require_once apply_filters( 'cdpg_pdf_generator_path', CDPG_PATH . 'lib/pdf-generator.php' );
			$result = array(
				'status' => true,
				'msg'    => esc_html__( 'PDF generated successfully.', 'cardealer-pdf-generator' ),
				'url'    => esc_url( $url ),
			);
		} catch ( Exception $e ) {
			$result = array(
				'status' => false,
				'msg'    => sprintf(
					/* translators: exception message */
					esc_html__( 'Something went wrong. Caught exception: %s', 'cardealer-pdf-generator' ),
					$e->getMessage()
				),
			);
		}
		wp_send_json( $result );
	}
}
