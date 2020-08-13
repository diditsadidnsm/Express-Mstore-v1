( function( $ ) {
	
	var $window = $(window);
	
	$window.scroll(function() {
		if ( $(this).scrollTop() > 500 ) {
			$('.to-top').addClass('show');
		} else {
			$('.to-top').removeClass('show');
		}
	}); 
	
	$('.to-top').click( function() {
		$("html, body").animate({ scrollTop: 0 }, 'slow');
		return false;
	});
	
	var	screenSize = 'full';
	
	$window.on('load resize', function() {
		var curScreenSize = 'full';

		if ( matchMedia( 'only screen and (max-width: 1024px)' ).matches ) {
			curScreenSize = 'responsive';
		}

		if ( curScreenSize !== screenSize ) {
			screenSize = curScreenSize;

			if ( curScreenSize === 'responsive' ) {
				var $responsiveMenu = $('#site-navigation').attr('id', 'site-navigation-mobi').hide();
				var hasSubMenu = $('#site-navigation-mobi').find('li:has(ul)');

				$('#header').find('.head-wrap').after($responsiveMenu);
				hasSubMenu.children('ul').hide();
				hasSubMenu.children('a').after('<span class="btn-submenu"></span>');
				$('.btn-menu').removeClass('active');
			} else {
				var $fullMenu = $('#site-navigation-mobi').attr('id', 'site-navigation').removeAttr('style');

				$fullMenu.find('.submenu').removeAttr('style');
				$('#header').find('.col-md-10').append($fullMenu);
				$('.btn-submenu').remove();
			}
		}
	});
	
	$('.btn-menu').on('click', function() {
		$('#site-navigation-mobi').slideToggle(300);
		$(this).toggleClass('active');
	});

	$(document).on('click', '#site-navigation-mobi li .btn-submenu', function(e) {
		$(this).toggleClass('active').next('ul').slideToggle(300);
		e.stopImmediatePropagation()
	});
	
	$('.site-navigation a[href*="#"], .smoothscroll[href*="#"]').on('click',function (e) {
		var target = this.hash;
		var $target = $(target);

		if ( $target.length ) {
			e.preventDefault();
			$('html, body').stop().animate({
				 'scrollTop': $target.offset().top - 100
			}, 900, 'swing');
			
			return false;
		}
	});
	
	
} )( jQuery );