(function ($) {
    wp.customize( 'header_textcolor', function( value ) {
        value.bind( function( to ) {
            if ( 'blank' === to ) {
                $( '.site-title a, .site-description' ).css( {
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                } );
            } else {
                $( '.site-title a, .site-description' ).css( {
                    'clip': 'auto',
                    'position': 'relative'
                } );
                $( '.site-title a, .site-description' ).css( {
                    'color': to
                } );
            }
        } );
    } );
    wp.customize('kyma_theme_options[logo_layout]', function (value) {
        value.bind(function (to) {
            $('#logo').css('float', to);
        });
    });

    /* layout option */
    wp.customize('kyma_theme_options[headercolorscheme]', function (value) {
        value.bind(function (to) {
            if (to != '') {
                $('body').addClass(to);
            } else {
                $('body').removeClass('light_header');
            }
        });
    });
    wp.customize('kyma_theme_options[site_layout]', function (value) {
        value.bind(function (to) {
            if (to != '') {
                $('body').addClass(to);
            } else {
                $('body').removeClass('site_boxed');
            }
        });
    });
    wp.customize('kyma_theme_options[footer_layout]', function (value) {
        value.bind(function (to) {
            var col = 12 / parseInt(to);
            $('footer .container .rows_container').children().attr('class', 'col-md-' + col);
        });
    });

    /* Service Options */
    wp.customize('kyma_theme_options[home_service_heading]', function (value) {
        value.bind(function (to) {
            $('h2#service_heading').html('<span class="line"><span class="dot"></span></span>' + to);
        });
    });
    wp.customize('kyma_theme_options[home_service_column]', function (value) {
        value.bind(function (to) {
            if (2 == to) {
                $('.service').removeClass('col-md-4');
                $('.service').removeClass('col-md-3');
                $('.service').addClass('col-md-6');
            } else if (3 == to) {
                $('.service').removeClass('col-md-6');
                $('.service').removeClass('col-md-3');
                $('.service').addClass('col-md-4');
            } else {
                $('.service').removeClass('col-md-4');
                $('.service').removeClass('col-md-6');
                $('.service').addClass('col-md-3');
            }
        });
    });
    wp.customize('kyma_theme_options[service_icon_1]', function (value) {
        value.bind(function (to) {
            $('#service-icon-1').attr('class', to);
        });
    });
    wp.customize('kyma_theme_options[service_icon_2]', function (value) {
        value.bind(function (to) {
            $('#service-icon-2').attr('class', to+' color1');
        });
    });
    wp.customize('kyma_theme_options[service_icon_3]', function (value) {
        value.bind(function (to) {
            $('#service-icon-3').attr('class', to+' color2');
        });
    });
    wp.customize('kyma_theme_options[service_icon_4]', function (value) {
        value.bind(function (to) {
            $('#service-icon-4').attr('class', to+' color3');
        });
    });
    wp.customize('kyma_theme_options[service_title_1]', function (value) {
        value.bind(function (to) {
            $('#service-title-1').html(to);
        });
    });
    wp.customize('kyma_theme_options[service_title_2]', function (value) {
        value.bind(function (to) {
            $('#service-title-2').html(to);
        });
    });
    wp.customize('kyma_theme_options[service_title_3]', function (value) {
        value.bind(function (to) {
            $('#service-title-3').html(to);
        });
    });
    wp.customize('kyma_theme_options[service_title_4]', function (value) {
        value.bind(function (to) {
            $('#service-title-4').html(to);
        });
    });
    wp.customize('kyma_theme_options[service_text_1]', function (value) {
        value.bind(function (to) {
            $('#service-desc-1').html(to);
        });
    });
    wp.customize('kyma_theme_options[service_text_2]', function (value) {
        value.bind(function (to) {
            $('#service-desc-2').html(to);
        });
    });
    wp.customize('kyma_theme_options[service_text_3]', function (value) {
        value.bind(function (to) {
            $('#service-desc-3').html(to);
        });
    });
    wp.customize('kyma_theme_options[service_text_4]', function (value) {
        value.bind(function (to) {
            $('#service-desc-4').html(to);
        });
    });
    wp.customize('kyma_theme_options[service_link_1]', function (value) {
        value.bind(function (to) {
            $('#service-link-1').attr('href', to);
        });
    });
    wp.customize('kyma_theme_options[service_link_2]', function (value) {
        value.bind(function (to) {
            $('#service-link-2').attr('href', to);
        });
    });
    wp.customize('kyma_theme_options[service_link_3]', function (value) {
        value.bind(function (to) {
            $('#service-link-3').attr('href', to);
        });
    });
    wp.customize('kyma_theme_options[service_link_4]', function (value) {
        value.bind(function (to) {
            $('#service-link-4').attr('href', to);
        });
    });
    /* Portfolio Options */
    wp.customize('kyma_theme_options[port_heading]', function (value) {
        value.bind(function (to) {
            $('#port_head').html('<span class="line"><i class="fa fa-folder-open-o"></i></span>' + to);
        });
    });
    wp.customize('kyma_theme_options[portfolio_three_column]', function (value) {
        value.bind(function (to) {
            if (to) {
                $('.wl-gallery').css('width', '33.3%');
            } else {
                $('.wl-gallery').css('width', '50%');
            }
        });
    });
    /* Blog Title */
    wp.customize('kyma_theme_options[home_blog_title]', function (value) {
        value.bind(function (to) {
            $('h2#blog-heading').html('<span class="line"><i class="fa fa-edit"></i></span>' + to);
        });
    });
    /* Footer Callout */
    wp.customize('kyma_theme_options[callout_home]', function (value) {
        value.bind(function (to) {
            if (!to)
                $('#callout').hide();
            else
                $('#callout').show();
        });
    });
    wp.customize('kyma_theme_options[callout_title]', function (value) {
        value.bind(function (to) {
            $('h3#callout-title').html(to);
        });
    });
    wp.customize('kyma_theme_options[callout_description]', function (value) {
        value.bind(function (to) {
            $('.welcome_banner .intro_text').html(to);
        });
    });

    wp.customize('kyma_theme_options[callout_btn_link]', function (value) {
        value.bind(function (to) {
            $('a#call_out_link').attr('href', to);
        });
    });
    wp.customize('kyma_theme_options[callout_btn_text]', function (value) {
        value.bind(function (to) {
            $('#callout-btn-text').html(to);
        });
    });
    /* extra section */
    wp.customize('kyma_theme_options[home_extra_title]', function (value) {
        value.bind(function (to) {
            $('#extra-heading').html(to);
        });
    });
})(jQuery);