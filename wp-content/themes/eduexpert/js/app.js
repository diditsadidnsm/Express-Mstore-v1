/* ======== index ========
1. PREELOADER
2. SCROLL TO
3. NAVBAR
4. ALL DROPDOWN ON HOVER
5. SLICK CAROUSEL
6. TAB
7. SCROLL SPY
8. WOW JS
===================== */

(function($) {
	'use strict';

	/*======== 1. PREELOADER ========*/
	$(window).on('load', function () {
		$('#preloader').fadeOut(500);
	});

	/*======== 2. SCROLL TO ========*/
	$(document).ready(function () {
		$('.scrolling  a[href*="#"], .scrolling .btn-scroll-down').on('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			var target = $(this).attr('href');
			if ($(window).width() < 767) {
			  $(target).velocity('scroll', {
					duration: 800,
					offset: -70,
					easing: 'easeOutExpo',
					mobileHA: false
				});
			} else if($(window).width() >= 768) {
				$(target).velocity('scroll', {
					duration: 800,
					offset: -88,
					easing: 'easeOutExpo',
					mobileHA: false
				});
			}
		});
	});

	/*======== 3. NAVBAR ========*/
	$(window).on('load', function(){
		var header_area = $('.header');
		var main_area = header_area.find('.navbar');
		var zero = 0;
		var navbarSticky = $('.navbar-sticky');

		$(window).scroll(function(){
			var st = $(this).scrollTop();
			if (st > zero){
				navbarSticky.addClass('navbar-scrollUp');
			} else {
				 navbarSticky.removeClass('navbar-scrollUp');
			}
			zero = st;

			if(main_area.hasClass('navbar-sticky') && ( $(this).scrollTop() <= 600 || $(this).width() <= 300)){
	      main_area.removeClass('navbar-sticky').appendTo(header_area);
	      header_area.css('height', 'auto');
	    }else if( !main_area.hasClass('navbar-sticky') && $(this).width() > 300 && $(this).scrollTop() > 600 ){
	      header_area.css('height', header_area.height());
	      main_area.addClass('navbar-scrollUp');
	      main_area.css({'opacity': '0'}).addClass('navbar-sticky');
	      main_area.appendTo($('body')).animate({'opacity': 1});
	    }
		});

		if ($(window).width() < 750) {
			$('.navbar-nav.site_nav .nav-item .nav-link').on('click', function(){
				$('.navbar-collapse.collapse').removeClass('show');
			});
		}

		$(window).trigger('resize');
		$(window).trigger('scroll');
	});

	/*======== 4. ALL DROPDOWN ON HOVER ========*/
  if($('.navbar').width() > 768){
    $('.navbar-nav .dropdown').hover(function() {
      $(this).add($(this).children('.dropdown-menu')).addClass('show');
    },
    function() {
      $(this).add($(this).children('.dropdown-menu')).removeClass('show');
    });
  }

	/*======== 5. SLICK CAROUSEL ========*/
	//Product SLider
	$('.product-slider').slick({
		centerMode: true,
		centerPadding: '10px',
		dots: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 5000,
		arrows: false,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1,
					dots: true
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1,
					infinite: true,
					dots: false
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					infinite: true,
					dots: false
				}
			}
		]
	});

	 // TEAM MEMBER CAROUSEL
	$('.team-carouel').slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 5000,
		arrows: false,
		speed: 1000,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 4,
					dots: true
				}
			},
			{
				breakpoint: 769,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					dots: false
				}
			},
			{
				breakpoint: 750,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}
			}
		]
	});

	//TESTIMONIAL CAROUSEL
	$('.testimonial-carousel').slick({
		autoplay: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplaySpeed: 5000,
		speed: 1000
	});

	/*======== 6. TAB ========*/
	$(function () {
		$(document).off('click.bs.tab.data-api', '[data-hover="tab"]');
		$(document).on('mouseenter.bs.tab.data-api', '[data-toggle="tab"], [data-hover="tab"]', function () {
			$(this).tab('show');
		});
	});

	/*======== 7. SCROLL SPY ========*/
	$('body').scrollspy({
		target: '#navbarContent',
		spy: 'scroll',
		offset: 130
	});

	/*======== 8. WOW JS ========*/
	new WOW().init();

})(jQuery);
