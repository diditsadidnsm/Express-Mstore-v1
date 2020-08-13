(function ($) {
    "use strict";
    //----------> Site Preloader
    $(window).load(function () {
        $('#preloader').fadeOut('slow', function () {
            $(this).remove();
        });
    });
    $(document).ready(function () {
        var site_dark = ( $("body").hasClass("dark") ? "yes" : "no" );
        var site_dir = "ltr";
        if ($("html").css('direction') == 'rtl' || $("html").attr('dir') == 'rtl') {
            site_dir = "rtl";
        }
        ;
        //----------> Top Bar Expand
        $(".top_expande").on("click", function () {
            var $thiss = $(this);
            var $conta = $thiss.siblings(".content");
            if ($thiss.hasClass("not_expanded")) {
                $($conta).stop().slideDown(300, function () {
                    $thiss.removeClass("not_expanded");
                });
            } else {
                $($conta).stop().slideUp(300, function () {
                    $thiss.addClass("not_expanded");
                });
            }
        });

        $("ul.sitemap li").each(function (index, element) {
            $(this).has("ul").addClass("has_child_sitmap");
            if ($(this).hasClass("has_child_sitmap")) {
                var num_child = $(this).find(" > ul > li").length;
                $(this).append('<span class="sitemap_count">' + num_child + '</span>');
            }

        });
        //-----------> Menu
        $("#nav_menu").idealtheme({});
		
		jQuery('#navy').slicknav({
            prependTo:'.mob-menu',
            parentTag: 'div',
            allowParentLinks: true,
            //duplicate:false,
            removeIds: false,
            label: '',
            closedSymbol: '<i class="fa fa-plus"></i>',
            openedSymbol: '<i class="fa fa-minus"></i>',
        });

        //----------> Owl Start

        $(".content_slider").owlCarousel({
            direction: site_dir,
            slideSpeed: 1000,
            autoPlay: 4000,
            itemsDesktop: false,
            itemsDesktopSmall: false,
            itemsTablet: false,
            itemsTabletSmall: false,
            itemsMobile: false,
            autoHeight: true,
            items: 1,
            stopOnHover: true,
            navigation: false,
            pagination: true,
        });

        //=====> OWL Carousel Normal Slider and Portfolio Slider
        $(".porto_galla").owlCarousel({
            direction: site_dir,
            slideSpeed: 900,
            autoPlay: 3000,
            autoHeight: false,
            items: 1,
            itemsDesktop: false,
            itemsDesktopSmall: false,
            itemsTablet: false,
            itemsTabletSmall: false,
            itemsMobile: false,
            stopOnHover: true,
            navigation: true,
            pagination: true,
            navigationText: [
                "<span class='kyma_owl_p'><i class='fa fa-angle-left'></i></span>",
                "<span class='kyma_owl_n'><i class='fa fa-angle-right'></i></span>"],
        });

        $(".related_posts_con").owlCarousel({
            direction: site_dir,
            slideSpeed: 900,
            autoPlay: 3000,
            autoHeight: true,
            itemsCustom: [
                [0, 1],
                [450, 2],
                [600, 2],
                [700, 3],
                [1000, 3],
                [1200, 4],
                [1400, 4],
                [1600, 5]
            ],
            itemsDesktop: false,
            itemsDesktopSmall: false,
            itemsTablet: false,
            itemsTabletSmall: false,
            itemsMobile: false,
            stopOnHover: true,
            navigation: true,
            pagination: true,
            navigationText: [
                "<span class='kyma_owl_p'><i class='fa fa-angle-left'></i></span>",
                "<span class='kyma_owl_n'><i class='fa fa-angle-right'></i></span>"],
        });
        var owl = $("#kyma_owl_slider");
        if (site_dir == "ltr") {
            owl.owlCarousel({
                direction: site_dir,
                slideSpeed: 1500,
                autoPlay: 5000,
                itemsDesktop: false,
                itemsDesktopSmall: false,
                itemsTablet: false,
                itemsTabletSmall: false,
                itemsMobile: false,
                autoHeight: true,
                items: 1,
                afterAction: moved,
                stopOnHover: true,
                navigation: true,
                navigationText: [
                    "<span class='kyma_owl_p'><span></span></span>",
                    "<span class='kyma_owl_n'><span></span></span>"
                ],
                pagination: true,
                transitionStyle: slider.effect //fade - fadeUp - backSlide - goDown
            });

        } else {
            owl.owlCarousel({
                direction: site_dir,
                slideSpeed: 900,
                autoPlay: 3000,
                itemsDesktop: false,
                itemsDesktopSmall: false,
                itemsTablet: false,
                itemsTabletSmall: false,
                itemsMobile: false,
                autoHeight: true,
                items: 1,
                afterAction: moved,
                stopOnHover: true,
                navigation: true,
                navigationText: [
                    "<span class='kyma_owl_p'><span></span></span>",
                    "<span class='kyma_owl_n'><span></span></span>"
                ],
                pagination: true,
            });
        }

        function moved(owl) {
            var o_d = owl.data('owlCarousel');
            var sub_lenght = owl.find('.owl-item.active .owl_slider_con > span').length;
            var sub_current = owl.find('.owl-item.active .owl_slider_con > span');

            if (o_d) {
                owl.find('.owl-item').eq(o_d.currentItem).addClass('active').siblings().removeClass('active');
                owl.find('.owl-item:not(.active) .owl_slider_con > span').removeClass('transform_owl');
                owl.find('.owl-item.active .owl_slider_con > span').each(function (index, element) {
                    setTimeout(function () {
                        owl.find('.owl-item.active .owl_slider_con > span').eq(index).addClass('transform_owl');
                    }, ((index + 1) * 200));
                });

            } else {
                owl.find('.owl-item').eq(0).addClass('active').siblings().removeClass('active');
                owl.find('.owl-item.active .owl_slider_con > span').each(function (index, element) {
                    setTimeout(function () {
                        owl.find('.owl-item.active .owl_slider_con > span').eq(index).addClass('transform_owl');
                    }, ((index + 1) * 200));
                });
            }
        }

        //----------> Owl End
        //----------------------------------> Back To Top
        var to_top_offset = 300,
            to_top_offset_opacity = 1200,
            scroll_top_duration = 900,
            $back_to_top = $('.hm_go_top');
        $(window).scroll(function () {
            if ($(this).scrollTop() > to_top_offset) {
                $back_to_top.addClass('hm_go_is-visible');
            } else {
                $back_to_top.removeClass('hm_go_is-visible hm_go_fade-out');
            }
            if ($(this).scrollTop() > to_top_offset_opacity) {
                $back_to_top.addClass('hm_go_fade-out');
            }
            return false;
        });
        $back_to_top.on('click', function (event) {
            event.preventDefault();
            $('body,html').animate({
                    scrollTop: 0,
                    //easing : "easeOutElastic"
                }, {queue: false, duration: scroll_top_duration, easing: "easeInOutExpo"}
            );
        });

        $(window).scroll(function () {
            if ($(this).scrollTop() > 30 && $("body").hasClass("site_boxed") && $("body").hasClass("header_on_side")) {
                $("#side_heder").addClass("start_side_offset");
            } else {
                $("#side_heder").removeClass("start_side_offset");
            }
        });

        //----------------------------------> main_title icon
        $(".content_section:not(.bg_gray)").each(function (index, element) {
            var color = '';
            var section_bg = $(this).css('backgroundColor');
            var parts = section_bg.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
            if (parts !== null) {
                delete(parts[0]);
                for (var i = 1; i <= 3; ++i) {
                    parts[i] = parseInt(parts[i]).toString(16);
                    if (parts[i].length == 1) parts[i] = '0' + parts[i];
                }
                color = '#' + parts.join('');
                $(this).find(".main_title .line i").css({"background-color": color});
                $(this).find(".main_title .line .dot").css({"background-color": color});
            }

        });
        //----------------------------------> Gialog Lightbox
        $("[data-dialog]").each(function (index, element) {
            var dialog_btn = element,
                dialog_name = document.getElementById(dialog_btn.getAttribute('data-dialog')),
                my_dlg = new DialogFx(dialog_name);
            dialog_btn.addEventListener('click', my_dlg.toggle.bind(my_dlg));
        });

        //----------------------------------> Magnific Popup Lightbox
        if ($.isFunction($.fn.magnificPopup)) {
            $('.expand_image').each(function (index, element) {
                $(this).click(function () {
                    $(this).parent().siblings("a").click();
                    $(this).parent().siblings(".porto_galla").find("a:first").click();
                    $(this).parent().siblings(".embed-container").find("a").click();
                    return false;
                });
            });
            $('.featured_slide_block').each(function (index, element) {
                var gall_con = $(this);
                var expander = $(this).find("a.expand_img");
                expander.click(function () {
                    gall_con.find("a:first").click();
                    return false;
                });
            });
            $('.porto_block').each(function (index, element) {
                var gall_con = $(this);
                var expander = $(this).find("a.expand_img");
                var expander_b = $(this).find("a.icon_expand");
                expander.click(function () {
                    gall_con.find("a:first").click();
                    return false;
                });
                expander_b.click(function () {
                    gall_con.find("a:first").click();
                    return false;
                });
            });
            $(".magnific-popup, a[data-rel^='magnific-popup']").magnificPopup({
                type: 'image',
                mainClass: 'mfp-with-zoom', // this class is for CSS animation below

                zoom: {
                    enabled: true,
                    duration: 300,
                    easing: 'ease-in-out',
                    // The "opener" function should return the element from which popup will be zoomed in
                    // and to which popup will be scaled down
                    // By defailt it looks for an image tag:
                    opener: function (openerElement) {
                        // openerElement is the element on which popup was initialized, in this case its <a> tag
                        // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                        return openerElement.is('img') ? openerElement : openerElement.find('img');
                    }
                }

            });

            $('.magnific-gallery, .porto_galla').magnificPopup({
                delegate: 'a',
                type: 'image',

                gallery: {
                    enabled: true
                },
                removalDelay: 500,
                callbacks: {
                    beforeOpen: function () {
                        this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                        this.st.mainClass = /*this.st.el.attr('data-effect')*/ "mfp-zoom-in";
                    }
                },
                closeOnContentClick: true,
                // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source
                midClick: true,
                retina: {
                    ratio: 1,
                    replaceSrc: function (item, ratio) {
                        return item.src.replace(/\.\w+$/, function (m) {
                            return '@2x' + m;
                        });
                    }
                }

            });

            $('.popup-youtube, .popup-vimeo, .popup-gmaps, .vid_con').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                fixedContentPos: false
            });

            $('.ajax-popup-link').magnificPopup({
                type: 'ajax',
                removalDelay: 500,
                mainClass: 'mfp-fade',
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = "mfp-fade hm_script_loaded";
                    },
                    parseAjax: function (mfpResponse) {

                    },
                    ajaxContentAdded: function () {
                        $(".ajax_content_container").on("click", function (event) {
                            var target = $(event.target);
                            if (target.hasClass("mfp-close")) {

                            } else {
                                event.stopPropagation();
                            }

                        });
                        $.getScript('js/functions.js', function (data, textStatus, jqxhr) {
                            $(".hm_script_loaded .ajax_content_container").css({"opacity": "1"});
                        });
                    }
                },

            });

            $('.popup-with-zoom-anim').magnificPopup({
                type: 'inline',
                fixedContentPos: false,
                fixedBgPos: true,
                overflowY: 'auto',
                closeBtnInside: true,
                preloader: false,
                midClick: true,
                removalDelay: 300,
                mainClass: 'my-mfp-zoom-in'
            });
            $('.popup-with-move-anim').magnificPopup({
                type: 'inline',
                fixedContentPos: false,
                fixedBgPos: true,
                overflowY: 'auto',
                closeBtnInside: true,
                preloader: false,
                midClick: true,
                removalDelay: 300,
                mainClass: 'my-mfp-slide-bottom'
            });
        }
        //----------------------------------> Responsive Resize
        var hm_screen_last_width = hm_screen_width();
        $(window).resize(function () {
            hm_screen_last_width = hm_screen_width();

        });
        function hm_screen_width() {
            return document.documentElement.clientWidth || document.body.clientWidth || window.innerWidth;
        }

        //---------------------------------------------> Hosted Video and Audio
        if ($.isFunction($.fn.mediaelementplayer)) {
            $("audio.hosted_audio").mediaelementplayer();
            $("video.hosted_video").mediaelementplayer({
                alwaysShowControls: true,
            });
        }

        //---------------------------------------------> Animated
        $('.animated').appear(function () {
            var elem = $(this);
            var animation = elem.data('animation');
            if (!elem.hasClass('visible')) {
                var animationDelay = elem.data('animation-delay');
                if (animationDelay) {

                    setTimeout(function () {
                        elem.addClass(animation + " visible");
                        elem.removeClass('hiding');
                    }, animationDelay);

                } else {
                    elem.addClass(animation + " visible");
                    elem.removeClass('hiding');
                }
            }
        });

        //---------------------------------------------> Scroll Easing
        $('.scroll').on('click', function (event) {
            var $anchor = $(this);
            var headerH = $('#navigation_bar').outerHeight();
            var my_offset = 0;

            if ($(this).hasClass("reviews_navigate")) {
                var rev_tab = $("a[data-content='reviews']");
                $(rev_tab).click();
            }
            if ($(this).hasClass("onepage")) {
                my_offset = headerH - 2;
            }
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top - my_offset + "px"
            }, 1200, 'easeInOutExpo');
            event.preventDefault();
        });
    });

    //========> Menu
    $.fn.idealtheme = function (options) {
        var whatTheLastWidth = getScreenWidth();
        var ifisdescktop = false;
        var MqL = 1170;

        var settings = {
            duration: 300,
            delayOpen: 0,
            menuType: "horizontal", // horizontal - vertical
            position: "right", // right - left
            parentArrow: true,
            hideClickOut: true,
            submenuTrigger: "hover",
            backText: "Back to ",
            clickToltipText: "Click",
        };
        $.extend(settings, options);
        var nav_con = $(this);
        var $nav_con_parent = nav_con.parent("#main_nav");
        var menu = $(this).find('#navy');


        //=====> Vertical and Horizontal
        if (settings.menuType == "vertical") {
            $(menu).addClass("vertical_menu");
            if (settings.position == "right") {
                $(menu).addClass("position_right");
            } else {
                $(menu).addClass("position_left");
            }
        } else {
            $(menu).addClass("horizontal_menu");
        }

        //=====> Add Arrows To Parent li
        if (settings.parentArrow === true) {
            $(menu).find("li.normal_menu li, li.has_image_menu").each(function () {
                if ($(this).children("ul").length > 0) {
                    $(this).children("a").append("<span class='parent_arrow normal_menu_arrow'></span>");
                }
            });

            /* $(menu).find("ul.mega_menu li ul li, .tab_menu_list > li").each(function () {
                if ($(this).children("ul").length > 0) {
                    $(this).children("a").append("<span class='parent_arrow mega_arrow'></span>");
                }
            }); */
        }

        function TopSearchFunc() {
            $(".top_search").each(function (index, element) {
                var top_search = $(this);
                top_search.submit(function (event) {
                    event.stopPropagation();
                    if (top_search.hasClass("small_top_search")) {
                        top_search.removeClass("small_top_search");
                        top_search.addClass("large_top_search");
                        if (getScreenWidth() <= 315) {
                            top_search.siblings("#top_cart").animate({opacity: 0});
                        }
                        top_search.siblings("#nav_menu:not(.mobile_menu), .logo_container").animate({opacity: 0});
                        return false;
                    }

                });
                $(top_search).on("click touchstart", function (e) {
                    e.stopPropagation();
                });
                $(document).on("click touchstart", function (e) {
                    if (top_search.hasClass("large_top_search")) {
                        top_search.removeClass("large_top_search");
                        top_search.addClass("small_top_search");
                        if (getScreenWidth() <= 315) {
                            top_search.siblings("#top_cart").animate({opacity: 1});
                        }
                        top_search.siblings("#nav_menu:not(.mobile_menu), .logo_container").animate({opacity: 1});
                    }
                });
            });
            if (getScreenWidth() < 1190) {
                $("#navigation_bar").find(".top_search").addClass("small_top_search");
            } else {
                $("#navigation_bar").find(".top_search").removeClass("small_top_search");
            }
        }

        var top_search_func = new TopSearchFunc();

        $(window).resize(function () {
            top_search_func = new TopSearchFunc();
            if (whatTheLastWidth > 992 && getScreenWidth() <= 992 && $("body").hasClass("header_on_side")) {
                $(menu).slideUp();
            }
            if (whatTheLastWidth <= 992 && getScreenWidth() > 992 && $("body").hasClass("header_on_side")) {
                $(menu).slideDown();
            }

            if (whatTheLastWidth <= 992 && getScreenWidth() > 992 && !$("body").hasClass("header_on_side")) {
                resizeTabsMenu();
                removeTrigger();
                playMenuEvents();
            }
            if (whatTheLastWidth > 992 && getScreenWidth() <= 992) {
                releaseTrigger();
                playMobileEvents();
                resizeTabsMenu();
                $(menu).slideUp();
            }
            whatTheLastWidth = getScreenWidth();
            return false;
        });

        //======> After Refresh
        function ActionAfterRefresh() {
            if (getScreenWidth() <= 992 || $("body").hasClass("header_on_side")) {
                releaseTrigger();
                playMobileEvents();
                resizeTabsMenu();

            } else {
                resizeTabsMenu();
                removeTrigger();
                playMenuEvents();
            }
        }

        var action_after_ref = new ActionAfterRefresh();
        
        //======> Mobile Menu
        function playMobileEvents() {
            $(".nav_trigger").removeClass("nav-is-visible");
            $(menu).find("li, a").unbind();
            if ($(nav_con).hasClass("mobile_menu")) {
                $(nav_con).find("li.normal_menu").each(function () {
                    if ($(this).children("ul").length > 0) {
                        $(this).children("a").not(':has(.parent_arrow)').append("<span class='parent_arrow normal_menu_arrow'></span>");
                    }
                });
            }

            $(menu).find("li:not(.has-children):not(.go-back)").each(function () {
                $(this).removeClass("opened_menu");
                if ($(this).children("ul").length > 0) {
                    var $li_li_li = $(this);
                    $(this).children("a").on("click", function (event) {
                        var curr_act = $(this);

                        if (!$(this).parent().hasClass("opened_menu")) {
                            $(this).parent().addClass("opened_menu");
                            $(this).parent().siblings("li").removeClass("opened_menu");
                            if ($(this).parent().hasClass("tab_menu_item")) {
                                $(this).parent().addClass("active");
                                $(this).parent().siblings("li").removeClass("active");
                            }
                            $(this).siblings("ul").slideDown(settings.duration);
                            $(this).parent("li").siblings("li").children("ul").slideUp(settings.duration);
                            setTimeout(function () {
                                var curr_position = curr_act.offset().top;
                                $('body,html').animate({
                                        //scrollTop: curr_position ,
                                    }, {queue: false, duration: 900, easing: "easeInOutExpo"}
                                );
                            }, settings.duration);

                            return false;
                        }
                        else {
                            $(this).parent().removeClass("opened_menu");
                            $(this).siblings("ul").slideUp(settings.duration);
                            if ($li_li_li.hasClass("mobile_menu_toggle") || $li_li_li.hasClass("tab_menu_item")) {
                                return false;
                            }
                        }
                    });
                }
            });
        }

        //======> Desktop Menu
        function playMenuEvents() {
            $(menu).children('li').children('ul').hide(0);
            $(menu).find("li, a").unbind();
            $(menu).slideDown(settings.duration);
            $(menu).find('ul.tab_menu_list').each(function (index, element) {
                var tab_link = $(this).children('li').children('a');
                $("<span class='mega_toltip'>" + settings.clickToltipText + "</span>").prependTo(tab_link);
                $(this).children('li').on('mouseover', function () {
                    if (!$(this).hasClass('active')) {
                        $(this).children('ul').stop().fadeIn();
                        $(this).siblings().children('ul').stop().fadeOut();
                        $(this).addClass('active');
                        $(this).siblings().removeClass('active');
                    }
                });
            });


            $(menu).find('li.normal_menu, > li').hover(function () {
                var li_link = $(this).children('a');
                $(this).children('ul').stop().fadeIn(settings.duration);
            }, function () {
                $(this).children('ul').stop().fadeOut(settings.duration);
            });
        }

        //======> Trigger Button Mobile Menu
        function releaseTrigger() {
            $(nav_con).find(".nav_trigger").unbind();
            $(nav_con).addClass('mobile_menu');
            $nav_con_parent.addClass('has_mobile_menu');

            $(nav_con).find('.nav_trigger').each(function (index, element) {
                var $trigger_mob = $(this);
                $trigger_mob.on('click touchstart', function (e) {
                    e.preventDefault();
                    if ($(this).hasClass('nav-is-visible')) {
                        $(this).removeClass('nav-is-visible');
                        $(menu).slideUp(settings.duration);

                    } else {
                        $(this).addClass('nav-is-visible');
                        $(document).unbind("click");
                        $(document).unbind("touchstart");
                        $(menu).slideDown(settings.duration, function () {
                            $(menu).on("click touchstart", function (event) {
                                event.stopPropagation();
                            });
                            $(document).on('click touchstart', function (event) {
                                if ($trigger_mob.hasClass('nav-is-visible') && getScreenWidth() <= 992) {
                                    $trigger_mob.removeClass('nav-is-visible');
                                    $(menu).slideUp(settings.duration);
                                }
                            });

                        });
                    }
                });

            });

        }

        //=====> get tabs menu height
        function resizeTabsMenu() {
            function thisHeight() {
                return $(this).outerHeight();
            }

            $.fn.sandbox = function (fn) {
                var element = $(this).clone(), result;
                element.css({visibility: 'hidden', display: 'block'}).insertAfter(this);
                element.attr('style', element.attr('style').replace('block', 'block !important'));
                var thisULMax = Math.max.apply(Math, $(element).find("ul:not(.image_menu)").map(thisHeight));
                result = fn.apply(element);
                element.remove();
                return thisULMax;
            };
        }

        resizeTabsMenu();
        //=====> End get tabs menu height

        function removeTrigger() {
            $(nav_con).removeClass('mobile_menu');
            $nav_con_parent.removeClass('has_mobile_menu');
        }

        //----------> sticky menu
        kyma_sticky();

    };

    var offset_header = "";
    get_header_offset();

    $(window).on("resize", function () {
        get_header_offset();
        kyma_sticky();
    });

    function get_header_offset() {
        offset_header = "";
        if (getScreenWidth() <= 992) {
            offset_header = "";
        } else {
            offset_header = "#site_header";
        }
    }

    //----------> sticky menu
    function kyma_sticky() {
        if ($.isFunction($.fn.sticky)) {
            var $navigation_bar = $("#navigation_bar");
            var is_sticky = parseInt($("#stickymenu").html());
            $navigation_bar.unstick();
            var mobile_menu_len = $navigation_bar.find(".mobile_menu").length;
            var side_header = $(".header_on_side").length;
            if (mobile_menu_len === 0 && side_header === 0 && is_sticky) {
                $navigation_bar.sticky({
                    topSpacing: 0,
                    className: "sticky_menu",
                    getWidthFrom: "body"
                });
            } else {
                $navigation_bar.unstick();
            }
        }
    }

    function getScreenWidth() {
        return document.documentElement.clientWidth || document.body.clientWidth || window.innerWidth;
    }
	/* Table */
	jQuery('.post, .page, .comment_content').find('table').addClass('table table-striped table-bordered');
})(window.jQuery);