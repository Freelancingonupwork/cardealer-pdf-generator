<?php
/**
 * Samples
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

if ( ! function_exists( 'cardealer_get_pdf_sample_core_field_label' ) ) {
	/**
	 * Additional fields for pdf generation
	 */
	function cardealer_get_pdf_sample_core_field_label( $tax = '' ) {

		$label = '';
		if ( ! empty($tax) ) {
			$tax_obj = get_taxonomy( $tax );
			if ( ! is_wp_error( $tax_obj ) && isset($tax_obj->labels->singular_name) ) {
				$label = $tax_obj->labels->singular_name;
			}
		}
		return $label;
	}
}

if ( ! function_exists( 'cardealer_get_pdf_sample_additional_fields' ) ) {
	/**
	 * Additional fields for pdf generation
	 */
	function cardealer_get_pdf_sample_additional_fields() {

		$taxonomies_raw = get_object_taxonomies( 'cars' );
		$tax_arr = array();
		foreach ( $taxonomies_raw as $new_tax ) {
			$new_tax_obj = get_taxonomy( $new_tax );
			if( isset($new_tax_obj->include_in_filters) && $new_tax_obj->include_in_filters == true ) {
				$tax_arr[$new_tax_obj->labels->singular_name] = $new_tax_obj->name;
			}
		}
		return $tax_arr;
	}
}



if ( ! function_exists( 'cardealer_pdf_sample_1' ) ) {
	/**
	 * Pdf-1
	 */
	function cardealer_pdf_sample_1() {
		ob_start();
		?>
		<table style="background-color: #c2c2c2;" border="0" cellspacing="0" cellpadding="20" align="center">
			<tbody>
				<tr>
					<td>
						<table border="0" width="600" cellspacing="0" cellpadding="0" align="center">
							<tbody>
								<tr>
									<td>
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td align="left" valign="top" width="44%">
														<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
															<tbody>
																<tr>
																	<td bgcolor="#FFFFFF" height="94" width="265"><img src="{{image}}" alt="" width="265" height="94" /></td>
																</tr>
															</tbody>
														</table>
													</td>
													<td align="left" valign="top" width="1%"></td>
													<td align="left" valign="top" width="54%">
														<table style="height: 80px;" border="0" width="100%" cellspacing="0" cellpadding="0">
															<tbody>
																<tr>
																	<td align="center" bgcolor="#FFFFFF">
																	<div style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;">Potenza Car Dealer Group</div>
																	<div style="font-size: 16px; font-weight: bold;">Adajan Pal-Gam</div>
																	<div style="font-size: 16px; font-weight: bold;">Surat</div></td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td height="15"></td>
								</tr>
								<tr>
									<td>
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td bgcolor="#FFFFFF">
														<table border="0" width="100%" cellspacing="0" cellpadding="0">
															<tbody>
																<tr>
																	<td align="left" valign="top" width="100%">
																		<table border="0" width="100%" cellspacing="0" cellpadding="0">
																			<tbody>
																				<tr>
																					<td class="headingbig" style="font-family: Arial; font-weight: bold; font-size: 30px;" align="center" height="35">{{year}} {{make}} {{model}}</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr>
									<td height="15"></td>
								</tr>
								<tr>
									<td>
										<table border="0" width="100%" cellspacing="0" cellpadding="0">
											<tbody>
												<tr>
													<td align="left" valign="top" bgcolor="#FFFFFF" width="45%">
														<table border="0" width="100%" cellspacing="0" cellpadding="0">
															<tbody>
																<tr>
																	<td>
																		<table style="height: 276px;" border="0" cellspacing="2" cellpadding="0" align="center">
																			<tbody>
																				<tr>
																					<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 18px;" align="center">SPECIFICATION</td>
																				</tr>
																				<tr>
																					<td align="left" height="10">
																						<ul>
																							<li>
																								<b>Regular Price :</b> {{currency_symbol}}{{regular_price}}
																							</li>
																							<li>
																								<b>Sale Price :</b> {{currency_symbol}}{{sale_price}}
																							</li>
																							<?php
																							$core_arr = array(
																								'car_year'           => 'year',
																								'car_make'           => 'make',
																								'car_model'          => 'model',
																								'car_body_style'     => 'body_style',
																								'car_condition'      => 'condition',
																								'car_mileage'        => 'mileage',
																								'car_transmission'   => 'transmission',
																								'car_drivetrain'     => 'drivetrain',
																								'car_engine'         => 'engine',
																								'car_fuel_type'      => 'fuel_type',
																								'car_fuel_economy'   => 'fuel_economy',
																								'car_trim'           => 'trim',
																								'car_exterior_color' => 'exterior_color',
																								'car_interior_color' => 'interior_color',
																								'car_stock_number'   => 'stock_number',
																								'car_vin_number'     => 'vin_number'
																							);
																							foreach( $core_arr as $ckey => $cval ) {
																								$label = cardealer_get_pdf_sample_core_field_label($ckey);
																								?>
																								<li>
																									<b><?php echo esc_html($label); ?> :</b> {{<?php echo esc_html($cval); ?>}}
																								</li>
																								<?php
																							}

																							$tax_arr = cardealer_get_pdf_sample_additional_fields();
																							if ( ! empty($tax_arr) ) {
																								foreach( $tax_arr as $key => $val ) {
																									?>
																									<li>
																										<b><?php echo esc_html($key); ?> :</b> {{<?php echo esc_html($val); ?>}}
																									</li>
																									<?php
																								}
																							}
																							?>
																						</ul>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
														&nbsp;
														&nbsp;
														&nbsp;
														&nbsp;
														<table style="height: 137px;" border="0" cellspacing="0" cellpadding="0">
															<tbody>
																<tr>
																	<td style="height: 35px;" align="center" width="37%"><img src="{{city_image}}" alt="" width="52" height="34" /></td>
																	<td style="height: 35px;" align="center" width="25%"></td>
																	<td style="height: 35px;" align="center" width="38%"><img src="{{hwy_image}}" alt="" width="62" height="27" /></td>
																</tr>
																<tr>
																	<td align="center" height="90" style="font-size: 30px;"><b>{{city_mpg}}</b></td>
																	<td align="center"><img src="{{fuel_image}}" alt="" /></td>
																	<td align="center" style="font-size: 30px;"><b>{{high_waympg}}</b></td>
																</tr>
															</tbody>
														</table>
													</td>
													<td align="left" valign="top" width="1%"></td>
													<td align="left" valign="top" width="54%">
														<table border="0" width="100%" cellspacing="0" cellpadding="0">
															<tbody>
																<tr>
																	<td>
																		<table border="0" width="100%" cellspacing="0" cellpadding="0">
																			<tbody>
																				<tr>
																					<td align="left" valign="top" bgcolor="#FFFFFF" height="540">
																						<table border="0" width="100%" cellspacing="0" cellpadding="0">
																							<tbody>
																								<tr>
																									<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center"><?php echo cardealer_get_pdf_sample_core_field_label( 'car_features_options' ); ?></td>
																								</tr>
																								<tr style="display: none;">
																									<td height="15"><strong>Certified Pre-owned</strong></td>
																								</tr>
																								<tr>
																									<td>
																										<ul>
																											{{features_options}}
																										</ul>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'cardealer_pdf_sample_2' ) ) {
	/**
	 * Pdf-2
	 */
	function cardealer_pdf_sample_2() {
		ob_start();
		?>
		<table border="1" width="100%" cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td style="font-family: Arial; font-weight: bold; font-size: 40px;" align="center" height="25">{{year}} {{make}} {{model}}</td>
		</tr>
		<tr>
		<td align="center" height="210">
		<table border="0" width="100%" cellspacing="4" cellpadding="4">
		<tbody>
		<tr>
		<td><img src="{{image}}" width="380" height="200" /></td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<table border="1" width="100%" cellspacing="0" cellpadding="0">
		<tbody>
		<tr>
		<td align="left" valign="top" width="56%">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tbody>
		<?php
		$core_arr_2 = array(
			'car_vin_number'     => 'vin_number',
			'car_stock_number'   => 'stock_number',
			'car_engine'         => 'engine',
			'car_transmission'   => 'transmission',
			'car_drivetrain'     => 'drivetrain',
			'car_mileage'        => 'mileage',
			'car_interior_color' => 'interior_color',
			'car_exterior_color' => 'exterior_color',
			'car_fuel_type'      => 'fuel_type',
			'car_fuel_economy'   => 'fuel_economy'
		);

		foreach( $core_arr_2 as $ckey2 => $cval2 ) {
			$label2 = cardealer_get_pdf_sample_core_field_label($ckey2);
			?>
			<tr>
				<td align="left" height="25"><b><?php echo esc_html($label2); ?> :</b> {{<?php echo esc_html($cval2); ?>}}</td>
			</tr>
			<?php
		}

		$tax_arr = cardealer_get_pdf_sample_additional_fields();
		if ( ! empty($tax_arr) ) {
			foreach( $tax_arr as $key => $val ) {
				?>
				<tr>
					<td align="left" height="25"><b><?php echo esc_html($key); ?> :</b> {{<?php echo esc_html($val); ?>}}</td>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
		</table>
		</td>
		<td align="left" valign="top" width="44%">
		<table border="0" width="98%" cellspacing="1" cellpadding="1">
		<tbody>
		<tr>
		<td height="25"><b> Regular Price :</b> {{currency_symbol}}{{regular_price}}</td>
		</tr>
		<tr>
		<td height="25"><b> Sale Price :</b> {{currency_symbol}}{{sale_price}}</td>
		</tr>
		<?php
		$core_arr2 = array(
			'car_year'           => 'year',
			'car_make'           => 'make',
			'car_model'          => 'model',
			'car_body_style'     => 'body_style',
			'car_condition'      => 'condition',
			'car_trim'           => 'trim'
		);
		foreach( $core_arr2 as $ckey2 => $cval2 ) {
			$label2 = cardealer_get_pdf_sample_core_field_label($ckey2);
			?>
			<tr>
				<td height="25"><b><?php echo esc_html($label2); ?> :</b> {{<?php echo esc_html($cval2); ?>}}</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		<tbody>
		<tr>
		<td>
		<table border="0" width="100%" cellspacing="1" cellpadding="1">
		<tbody>
		<tr>
		<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 24px;" align="center">
		<h3>FUEL EFFICIENCY</h3>
		</td>
		</tr>
		<tr>
		<td></td>
		</tr>
		<tr>
		<td align="center">
		<table border="0" width="100%" cellspacing="1" cellpadding="1">
		<tbody>
		<tr>
		<td><img src="{{city_image}}" alt=""/></td>
		<td rowspan="2"><img src="{{fuel_image}}" /></td>
		<td><img src="{{hwy_image}}" alt=""/></td>
		</tr>
		<tr>
		<td style="font-size: 24px;">{{city_mpg}}</td>
		<td style="font-size: 24px;">{{high_waympg}}</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		<td align="left" valign="top" bgcolor="#FFFFFF" height="340">
		<table border="0" width="100%" cellspacing="1" cellpadding="1">
		<tbody>
		<tr>
		<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center"><?php echo cardealer_get_pdf_sample_core_field_label( 'car_features_options' ); ?></td>
		</tr>
		<tr style="display: none;">
		<td height="15"><strong>Certified Pre-owned</strong></td>
		</tr>
		<tr>
		<td>
		<ul>
		{{features_options}}
		</ul>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'cardealer_pdf_sample_3' ) ) {
	/**
	 * Pdf-3
	 */
	function cardealer_pdf_sample_3() {
		ob_start();
		?>

		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<td style="font-family: Arial; font-weight: bold; font-size: 35px;" align="center" height="25">{{year}} {{make}} {{model}}</td>
				</tr>
				<tr>
					<td align="left" height="180" width="50%">
						<table border="0" width="100%" cellspacing="4" cellpadding="4">
							<tbody>
								<tr>
									<td><img src="{{image}}" width="320" height="180" /></td>
								</tr>
							</tbody>
						</table>
					</td>
					<td align="left" valign="top" width="50%">
						<table border="0" cellspacing="1" cellpadding="1">
							<tbody>
								<tr>
									<td align="center">
										<div style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;">Potenza Car Dealer Group</div>
										<div style="font-size: 20px; font-weight: bold;">Adajan Pal-Gam</div>
										<div style="font-size: 20px; font-weight: bold;">Surat</div>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<td align="left" valign="top" width="56%">
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tbody>
								<?php
								$core_arr_2 = array(
									'car_vin_number'     => 'vin_number',
									'car_stock_number'   => 'stock_number',
									'car_engine'         => 'engine',
									'car_transmission'   => 'transmission',
									'car_drivetrain'     => 'drivetrain',
									'car_mileage'        => 'mileage',
									'car_interior_color' => 'interior_color',
									'car_exterior_color' => 'exterior_color',
									'car_fuel_type'      => 'fuel_type',
								);

								foreach( $core_arr_2 as $ckey2 => $cval2 ) {
									$label2 = cardealer_get_pdf_sample_core_field_label($ckey2);
									?>
									<tr>
										<td align="left" height="25"><b><?php echo esc_html($label2); ?> :</b> {{<?php echo esc_html($cval2); ?>}}</td>
									</tr>
									<?php
								}

								$tax_arr = cardealer_get_pdf_sample_additional_fields();
								if ( ! empty($tax_arr) ) {
									foreach( $tax_arr as $key => $val ) {
										?>
										<tr>
											<td align="left" height="25"><b><?php echo esc_html($key); ?> :</b> {{<?php echo esc_html($val); ?>}}</td>
										</tr>
										<?php
									}
								}
								?>
							</tbody>
						</table>
					</td>
					<td align="left" valign="top" width="44%">
						<table border="0" width="98%" cellspacing="1" cellpadding="1">
							<tbody>
								<tr>
									<td height="25"><b> Regular Price :</b> {{currency_symbol}}{{regular_price}}</td>
								</tr>
								<tr>
									<td height="25"><b> Sale Price :</b> {{currency_symbol}}{{sale_price}}</td>
								</tr>
								<?php
								$core_arr2 = array(
									'car_year'           => 'year',
									'car_make'           => 'make',
									'car_model'          => 'model',
									'car_body_style'     => 'body_style',
									'car_condition'      => 'condition',
									'car_trim'           => 'trim'
								);
								foreach( $core_arr2 as $ckey2 => $cval2 ) {
									$label2 = cardealer_get_pdf_sample_core_field_label($ckey2);
									?>
									<tr>
										<td height="25"><b><?php echo esc_html($label2); ?> :</b> {{<?php echo esc_html($cval2); ?>}}</td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<td>
						<table border="0" width="100%" cellspacing="1" cellpadding="1">
							<tbody>
								<tr>
									<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center">
										<h3><?php echo cardealer_get_pdf_sample_core_field_label( 'car_fuel_economy' ); ?></h3>
									</td>
								</tr>
								<tr>
									<td style="font-size: 30px;" align="center">
										{{fuel_economy}}
									</td>
								</tr>
								<tr>
									<td align="center"></td>
								</tr>
								<tr>
									<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center">
										<h3>FUEL EFFICIENCY</h3>
									</td>
								</tr>
								<tr>
									<td align="center">
										<table border="0" width="100%" cellspacing="1" cellpadding="1">
											<tbody>
												<tr>
													<td><img src="{{city_image}}" alt=""/></td>
													<td rowspan="2"><img src="{{fuel_image}}" /></td>
													<td><img src="{{hwy_image}}" alt=""/></td>
												</tr>
												<tr>
													<td style="font-size: 30px;">{{city_mpg}}</td>
													<td style="font-size: 30px;">{{high_waympg}}</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td align="left" valign="top" bgcolor="#FFFFFF" height="380">
						<table border="0" width="100%" cellspacing="1" cellpadding="1">
							<tbody>
								<tr>
									<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center"><?php echo cardealer_get_pdf_sample_core_field_label( 'car_features_options' ); ?></td>
								</tr>
								<tr style="display: none;">
									<td height="15"><strong>Certified Pre-owned</strong></td>
								</tr>
								<tr>
									<td>
										<ul>{{features_options}}</ul>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
		return ob_get_clean();
	}
}

?>
