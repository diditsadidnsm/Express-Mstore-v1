(function($){

    var WPUF_Userlisting = {

        init: function() {
            $('.wpuf-add-field').on('click', this.fieldAdd);
            $('.wpuf-section-field').on('click', this.fieldAdd);
            $('.wpuf-postype-field').on('click', this.fieldAdd);
            $('.wpuf-comment-field').on('click', this.fieldAdd);
            $('.wpuf-social-field').on('click', this.fieldAdd);
            $('.wpuf-file-field').on('click', this.fieldAdd);
            $('.del-social').live('click', this.remove);

            $('#wpuf-all-field').on('click', '.social-row-add', this.socialfieldAdd);
            $('#wpuf-all-field').on('click', '.social-fild-del', this.remove);

			$('ul#wpuf-all-field').sortable({ handle: '.wpuf-legend' });
            $('ul#wpuf-all-field').on('click', 'a.wpuf-file-upload', this.uploadFile);
            $('ul#wpuf-all-field').on('click', 'a.wpuf-remove', this.remove);
			$('ul#wpuf-all-field').on('click', 'a.wpuf-toggle', this.toggleFormField);
			$('span.wpuf-userlisting-toggle').on('click', this.toggleAllField);

			$('ul#wpuf-all-field').on('change', 'label.show input', this.showHide);

			self = this;
        },

        showHide: function(e) {
        	e.preventDefault();
        	if ($(this).prop('checked')) {
	           $(this).parent('label.show').siblings('label.search-by').removeClass('wpuf-hide');
	           $(this).parent('label.show').siblings('label.sort-by').removeClass('wpuf-hide');
	        }
	        else {
	           $(this).parent('label.show').siblings('label.search-by').addClass('wpuf-hide');
	           $(this).parent('label.show').siblings('label.sort-by').addClass('wpuf-hide');
	        }
        },

        toggleAllField: function(e) {
        	e.preventDefault();

        	$('ul#wpuf-all-field').find('div.wpuf-legend').next('div').slideToggle('fast');
        },

        socialfieldAdd: function(e) {
        	e.preventDefault();

        	var cole_social_field = $('#wpuf-extr-social-field').html();

			$(this).closest('.wpuf-form-rows')
					.after( _.template(cole_social_field) );

        },

        toggleFormField: function(e) {
            e.preventDefault();

           	$(this).closest('li').find('.wpuf-form-holder').slideToggle('fast');

        },

		remove: function(e) {
			e.preventDefault();
			var del_tr = $(this).data('close_social');

			if( confirm('Are you sure?') ) {
				if(del_tr == 'rmv_social') {
					$(this).closest('.wpuf-form-rows').remove();
				} else if(del_tr == 'social_field_rmv') {
					$('li.wpuf-social-li').remove();
				} else {
					$(this).closest('li').remove();
				}
			}
		},


        fieldAdd: function(e) {
            e.preventDefault();
            var $elem = $('.wpuf-form');
				$('html, body').animate({scrollTop: $elem.height()}, 800);

			var count = $('ul#wpuf-all-field > li').length,
				all_field = $('#wpuf-all-field'),
				tpl_id = $(this).data('field_type');

            var cole_field = $(tpl_id).html(),
				data = {
					'count' : count,
				};

			if( tpl_id == '#wpuf-userlisting-social' && !$('li').hasClass('wpuf-social-li') ) {
				$('ul#wpuf-all-field')
					.append( _.template(cole_field)(data) )
					.children('li')
					.last()
					.hide()
					.toggle(300);
			} else if( tpl_id != '#wpuf-userlisting-social' ) {

				$('ul#wpuf-all-field')
					.append( _.template(cole_field)(data) )
					.children('li')
					.last()
					.hide()
					.toggle(300);
			}

        },

        uploadFile: function(e) {
        	e.preventDefault();

        	//console.log('hello');
			var file_frame,
				self = $(this);

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
					text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false // Set to true to allow multiple files to be selected
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();

				// Do something with attachment.id and/or attachment.url here
				//console.log(attachment);
				self.closest('div').find('input.wpuf-file-field').val(attachment.url);
			});

			// Finally, open the modal
			file_frame.open();
        }
    };

        // settings api - radio_image
    // $('.user-directory-settings-radio-image button').on('click', function (e) {
    //     e.preventDefault();

    //     var btn = $(this),
    //         template = btn.data('template'),
    //         input = btn.data('input'),
    //         container = btn.parents('.user-directory-settings-radio-image-container');

    //     $('#' + input).val(template);

    //     container.find('.active').removeClass('active').addClass('not-active');

    //     btn.parents('.user-directory-settings-radio-image').addClass('active').removeClass('not-active');
    // });

    $(function(){

        WPUF_Userlisting.init();
    });

})(jQuery);