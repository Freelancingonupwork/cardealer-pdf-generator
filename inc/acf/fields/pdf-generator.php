<?php
/**
 * Car pdf generator
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

if ( ! function_exists( 'cdhl_get_pdf_acf_info_fields' ) ) {
	/**
	 * PDF  acf info fields
	 */
	function cdhl_get_pdf_acf_info_fields() {
		$info_fields = 'Vehicle Image : {{image}} <br>
		Vehicle Year : {{year}} <br>
		Vehicle Make : {{make}}<br>
		Vehicle Model : {{model}} <br>
		Regular Price : {{regular_price}} <br>
		Currency Symbol : {{currency_symbol}} <br>
		Sale Price : {{sale_price}} <br>
		Body Style : {{body_style}} <br>
		Condition : {{condition}} <br>
		Mileage : {{mileage}} <br>
		Transmission : {{transmission}} <br>
		Drivetrain : {{drivetrain}} <br>
		Engine : {{engine}}<br>
		Fuel Type : {{fuel_type}} <br>
		Fuel Economy : {{fuel_economy}}<br>
		Trim : {{trim}} <br>
		Exterior Color : {{exterior_color}}<br>
		Interior Color : {{interior_color}} <br>
		Stock : {{stock_number}}<br>
		VIN : {{vin_number}} <br>
		Features And Options : {{features_options}}<br>
		Highway MPG : {{high_waympg}} <br>
		City MPG : {{city_mpg}}<br>
		Vehicle Overview : {{vehicle_overview}}<br>
		Tax Label : {{tax_label}}<br>
		Vehicle Status : {{vehicle_status}}<br>
		Vehicle Review Stamps : {{vehicle_review_stamps}}<br>';

		$info             = '';
		$additional_taxes = get_taxonomies(
			array(
				'is_additional_attribute' => true,
				'object_type'             => array(
					'cars',
				),
			),
			'objects'
		);

		foreach ( $additional_taxes as $tax_name => $tax_obj ) {
			$info .= $tax_obj->label . ' : {{' . $tax_obj->name . '}}<br>';
		}

		return $info_fields . $info;
	}
}

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		/**
		 * Filters the arguments of the vehicle brochure generator field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the vehicle brochure generator field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_pdf_generator',
			array(
				'key'                   => 'group_589ac22982773',
				'title'                 => esc_html__( 'Vehicle Brochure Generator', 'cardealer-pdf-generator' ),
				'fields'                => array(
					array(
						'key'               => 'field_589ac266afdf9',
						'label'             => esc_html__( 'HTML Templates', 'cardealer-pdf-generator' ),
						'name'              => 'html_templates',
						'type'              => 'repeater',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => 'acf_field_name- acf_field_name-html_templates',
							'id'    => '',
						),
						'min'               => 0,
						'max'               => 0,
						'layout'            => 'block',
						'button_label'      => '',
						'collapsed'         => '',
						'sub_fields'        => array(
							array(
								'key'               => 'field_589ac54dafdfa',
								'label'             => esc_html__( 'Templates Title', 'cardealer-pdf-generator' ),
								'name'              => 'templates_title',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_title',
									'id'    => '',
								),
								'default_value'     => '',
								'maxlength'         => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_589ac571afdfb',
								'label'             => esc_html__( 'Template Content', 'cardealer-pdf-generator' ),
								'name'              => 'template_content',
								'type'              => 'wysiwyg',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-template_content',
									'id'    => '',
								),
								'tabs'              => 'all',
								'toolbar'           => 'full',
								'media_upload'      => 1,
								'default_value'     => '',
								'delay'             => 0,
							),
							array(
								'key'               => 'field_5c2b0cb780efb',
								'label'             => esc_html__( 'Set Custom Margin?', 'cardealer-pdf-generator' ),
								'name'              => 'custom_margin_set',
								'type'              => 'checkbox',
								'instructions'      => esc_html__( 'If custom margin is not set, the default value will be used.', 'cardealer-pdf-generator' ),
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-custom_margin_set',
									'id'    => '',
								),
								'choices'           => array(
									'yes' => 'Yes',
								),
								'allow_custom'      => 0,
								'default_value'     => array(),
								'layout'            => 'vertical',
								'toggle'            => 0,
								'return_format'     => 'value',
								'save_custom'       => 0,
							),
							array(
								'key'               => 'field_5c29f4760055c',
								'label'             => esc_html__( 'Template Margin Top', 'cardealer-pdf-generator' ),
								'name'              => 'templates_margin_top',
								'type'              => 'range',
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5c2b0cb780efb',
											'operator' => '==',
											'value'    => 'yes',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_margin_top',
									'id'    => '',
								),
								'default_value'     => 0,
								'min'               => '',
								'max'               => '',
								'step'              => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_5c29f59195d8c',
								'label'             => esc_html__( 'Template Margin Bottom', 'cardealer-pdf-generator' ),
								'name'              => 'templates_margin_bottom',
								'type'              => 'range',
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5c2b0cb780efb',
											'operator' => '==',
											'value'    => 'yes',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_margin_bottom',
									'id'    => '',
								),
								'default_value'     => 0,
								'min'               => '',
								'max'               => '',
								'step'              => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_5c29f59b95d8d',
								'label'             => esc_html__( 'Template Margin Left', 'cardealer-pdf-generator' ),
								'name'              => 'templates_margin_left',
								'type'              => 'range',
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5c2b0cb780efb',
											'operator' => '==',
											'value'    => 'yes',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_margin_left',
									'id'    => '',
								),
								'default_value'     => 0,
								'min'               => '',
								'max'               => '15',
								'step'              => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_5c29f59e95d8e',
								'label'             => esc_html__( 'Template Margin Right', 'cardealer-pdf-generator' ),
								'name'              => 'templates_margin_right',
								'type'              => 'range',
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_5c2b0cb780efb',
											'operator' => '==',
											'value'    => 'yes',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => 'acf_field_name-templates_margin_right',
									'id'    => '',
								),
								'default_value'     => 0,
								'min'               => '',
								'max'               => '15',
								'step'              => '',
								'prepend'           => '',
								'append'            => '',
							),

						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'pdf_generator',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'field',
				'hide_on_screen'        => '',
				'active'                => 1,
				'description'           => '',
				'menu_item_level'       => 'all',
			)
		)
	);
	acf_add_local_field_group(
		/**
		 * Filters the arguments of the vehicle brochure generator field group.
		 *
		 * @since 1.0
		 * @param array    $args    Arguments of the vehicle brochure generator field group.
		 * @visible        true
		 */
		apply_filters(
			'cardealer_acf_pdf_generator_settings',
			array(
				'key'                   => 'group_611cb11a43864',
				'title'                 => 'Settings',
				'fields'                => array(
					array(
						'key'               => 'field_611cb2c3e3884',
						'label'             => '',
						'name'              => '',
						'type'              => 'message',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'message'           => sprintf(
							'<a href="javascript:void(0)" id="reset-pdf-sample" class="button button-danger button-large" data-nonce="%2$s">%1$s</a>',
							esc_html__( 'Reset Templates', 'cardealer-pdf-generator' ),
							wp_create_nonce( 'pdf_reset_nonce' )
						)
						. '<span class="spinner" style="float: none;min-height: 32px;margin-top: 6px;"></span>'
						. '<em style="display:block;">' . esc_html__( 'Click the above button to reset all templates to default.', 'cardealer-pdf-generator' ) . '</em>',
						'new_lines'         => 'wpautop',
						'esc_html'          => 0,
					),
					array(
						'key'               => 'field_2c6113e3884cb',
						'label'             => esc_html__( 'Fields', 'cardealer-pdf-generator' ),
						'name'              => '',
						'type'              => 'message',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'message'           => sprintf(
							'<a href="javascript:void(0)" class="button button-primary button-large cd_dialog" data-id="pdf-fields">%1$s</a>',
							esc_html__( 'Display Fields', 'cardealer-pdf-generator' ),
						)
						. '<em style="display:block;">' . esc_html__( 'Click the above button to display list of fields. Use this fields association to make or update the vehicle brochure template.', 'cardealer-pdf-generator' ) . '</em>'
						. sprintf(
							'<div style="display:none;" id="pdf-fields" class="variable-content" title="' . esc_html__( 'Vehicle Fields Association', 'cardealer-pdf-generator' ) . '"><p>%1$s</p></div>',
							cdhl_get_pdf_acf_info_fields()
						),
						'new_lines'         => 'wpautop',
						'esc_html'          => 0,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'pdf_generator',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'side',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			)
		)
	);

endif;
