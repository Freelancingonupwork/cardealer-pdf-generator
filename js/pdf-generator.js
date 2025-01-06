( function( $ ) {
	jQuery(document).ready( function(){
		/*
		* Code for download PDF Brochure admin side
		*/
		jQuery(document).on("click", ".download-pdf", function (e) {

			e.preventDefault();

			var generate_btn       = $( this ),
				generate_pdf_wrap  = generate_btn.closest( '.admin-column-pdf-actions' ),
				generate_spinner   = generate_pdf_wrap.find( '.spinner' ),
				id                 = jQuery(this).attr('id'),
				pdf_template_title = jQuery( "#download-"+id+" #casr_pdf_styles option:selected" ).text();

			if ( generate_btn.hasClass('disabled') ) {
				return false;
			}

			jQuery.ajax({

				url:ajaxurl,
				type:'post',
				data: {
					'action':'cdpg_generate_pdf',
					'pdf_template_title': pdf_template_title,
					'id' : id
				},
				beforeSend: function(){
					generate_btn.addClass('disabled');
					generate_spinner.addClass('is-active');
					jQuery('#downloadlink-'+id).hide();
				},
				success:function(data) {
					generate_spinner.removeClass('is-active');

					// This outputs the result of the ajax request
					if ( data.status ) {
						if ( jQuery('#generate-pdf-notice').length > 0 ) {
							$('#generate-pdf-notice').remove();
						}
						jQuery( ".wp-header-end" ).after(cars_pdf_message.pdf_generated_message);
						jQuery('#downloadlink-'+id).html('');
						jQuery('#downloadlink-'+id).show();
						jQuery('#downloadlink-'+id).append('<a href="'+data.url+'" class="button button-primary" target="_blank" download>'+cars_pdf_message.download_pdf_str+'</a>');
					} else {
						jQuery('#downloadlink-'+id).show();
						if ( jQuery('#generate-pdf-notice').length > 0 ) {
							$('#generate-pdf-notice').remove();
						}
						jQuery( ".wp-header-end" ).after('<div id="generate-pdf-notice" class="notice notice-error"><p>'+data.msg+'</p></div>');
					}
					generate_btn.removeClass('disabled');

					jQuery('html, body').animate({
						scrollTop: 0
					}, 'slow' );
				},
				error: function(errorThrown){
					console.log(errorThrown);
				}
			});
		});


		jQuery(document).on( 'change', '.casr_pdf_styles', function(){
			var id = jQuery(this).attr('data-id');
			jQuery('#downloadlink-'+id).hide();
		});

		/**
		* Call advance select box for vehicle mapping metabox
		*/
		if(document.getElementById('car_to_woo_product_meta_id')){
			$('#car_to_woo_product_meta_id').select2();
		}

		/**
		 * Reset pdf samples
		 */
		jQuery(document).on('click', "#reset-pdf-sample", function (event) {
			event.preventDefault();
			var spinner = jQuery( this ).closest('.acf-input').find('.spinner');
			var paramData = {
				action: 'reset_pdf_sample_template_fields',
				nonce: jQuery(this).attr('data-nonce')
			};
			jQuery.ajax({
				url : cdhl.ajaxurl,
				type:'POST',
				dataType:'json',
				data: paramData,
				beforeSend: function(){
					spinner.css('visibility','visible');
					jQuery('.publish').prop('disabled',true);
				},
				success: function(response){
					spinner.css('visibility','hidden');
					jQuery('.publish').prop('disabled',false);
					if(response.status) {
						location.reload();
					}
				},
				error: function(){
					console.log('Something went wrong!');
				}
			});
		});
	});
} )( jQuery );
