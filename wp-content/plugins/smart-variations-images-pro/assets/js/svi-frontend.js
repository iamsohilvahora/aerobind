/* global WOOSVIDATA */
(function ($, window, document, undefined) {

    $.fn.wcsvi_variation_form = function () {

        var $form = this,
                //$product_variations = $form.data('product_variations'),
                $is_reset = false,
                $swselect = (WOOSVIDATA.swselect == '1') ? true : false,
                $keep_thumbs = (WOOSVIDATA.keep_thumbnails == '1') ? true : false;
        // Always visible since 2.5.0
        // Bind new events to form
        $form
                .on('click', '.reset_variations', function (event) {
                    event.preventDefault();
                    $is_reset = true;
                    WOOSVI.STARTS.loadImages(true);
                    WOOSVI.STARTS.imagesLoaded(true);
                    setTimeout(function () {
                        $is_reset = false;
                    }, 500);
                })
                // When the variation is revealed
                .on('show_variation', function (event, variation, purchasable) {
                    //if ($keep_thumbs)
                    //  return false;
                    if (!$swselect) {
                        //console.log("show_variation", $swselect, event);
                        WOOSVI.STARTS.showVariation(); //COMENTADO PORQUE CORRE 2x
                    }
                    if ($keep_thumbs) {
                        //console.log("show_variation 2", $swselect, event);
                        //WOOSVI.STARTS.showVariation(); //COMENTADO PORQUE CORRE 2x
                        WOOSVI.STARTS.loadImages(true);
                        WOOSVI.STARTS.imagesLoaded(true);
                    }
                })
                // On changing an attribute
                .on('change', '.variations select', function (event) {

                    if ($swselect && !$is_reset) {
                        //console.log("change", $swselect, event);
                        WOOSVI.STARTS.showVariation();
                    }
                })
                // Upon gaining focus
                .on('focusin touchstart', '.variations select', function () {
                    //console.log("focusin");

                    $is_reset = false;
                })
                // Show single variation details (price, stock, image)
                .on('found_variation', function (event, variation) {
                    //console.log("found_variation");

                })
                // Check variations
                .on('check_variations', function (event, exclude, focus) {
                    //console.log("check_variations",event, exclude, focus);

                    if (typeof $(event.target).data('product_variations') == 'boolean' && !$(event.target).data('product_variations')) {
                        if ($swselect && !$is_reset) {
                            //console.log("change", $swselect, event);
                            WOOSVI.STARTS.showVariation();
                        }
                    }
                })

                // Disable option fields that are unavaiable for current set of attributes
                .on('update_variation_values', function (event, variations) {
                    //console.log("update_variation_values",event, variations);

                });
        $form.trigger('wc_variation_form');
        return $form;
    };
    $(function () {
        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
            $('.variations_form').each(function () {
                $(this).wcsvi_variation_form();
            });
        }
        WOOSVI.STARTS.init();
    });
})(jQuery, window, document);
if (!WOOSVI) {
    var WOOSVI = {};
} else {
    if (WOOSVI && typeof WOOSVI !== "object") {
        throw new Error("WOOSVI is not an Object type");
    }
}
jQuery.noConflict();
WOOSVI.STARTS = function ($, window, document, undefined) {
    var $form = $('form.variations_form');
    var $container = $("div#woosvi_strap");
    var runningImgLoader = false;
    var $is_variation = ($form.find('.variations select').length > 0) ? true : false;
    var $img_tag = '<img src="{{image_src}}" alt="{{image_alt}}" title="{{image_title}}" data-svikey="{{image_svikey}}" data-woosvislug="{{image_woosvislug}}" data-svizoom-image="{{image_svizoom}}" srcset="{{image_srcset}}" sizes="{{image_sizes}}" width="{{image_width}}" height="{{image_height}}">';
    var $keep_thumbs = (WOOSVIDATA.keep_thumbnails == '1') ? true : false;
    //THUMBNAILS
    var $disable_thumb = (WOOSVIDATA.disable_thumb == '1') ? true : false;
    var $hide_thumbs = (WOOSVIDATA.hide_thumbs == '1') ? true : false;
    //var $swselect = (WOOSVIDATA.swselect == '1') ? true : false;
    //var $staticthumb = (WOOSVIDATA.static_thumb == '1') ? true : false;
    var $thumbselect = (WOOSVIDATA.variation_swap == '1') ? true : false;
    var $woosvi_lens = (WOOSVIDATA.lens == '1') ? true : false;
    var $containLensZoom = (WOOSVIDATA.containlenszoom == '1') ? false : true;
    var $prettyphoto_running = false;
    var $prettyphoto_themestyle = (WOOSVIDATA.prettyphoto_themestyle == '1') ? true : false;
    var $prettyphoto_style = WOOSVIDATA.prettyphoto_style;
    var $woosvi_lightbox = (WOOSVIDATA.lightbox == '1') ? true : false;
    var $woosvi_lightbox_new = (WOOSVIDATA.lightbox_new == '1') ? true : false;
    var $woosvi_ps_thumbs = (WOOSVIDATA.ps_thumbs == '1') ? true : false;
    var gallery = false;
    var $LoadLens_running = false;
    var $thumbnails_showactive = (WOOSVIDATA.thumbnails_showactive == '1') ? 'svi-slide-active' : '';
    var $exist_thumbs = (typeof WOOSVIDATA.gallery.thumbs !== 'undefined' && WOOSVIDATA.gallery.thumbs.length > 0) ? true : false;
    /*SLIDER VARS*/
    var $slider = (WOOSVIDATA.slider == '1') ? true : false;
    var $sync1, $sync2;
    var $slider_navigation = (WOOSVIDATA.slider_navigation == '1') ? true : false;
    var $slider_navigation_thumb = (WOOSVIDATA.slider_navigation_thumb == '1') ? true : false;
    var $slider_center = (WOOSVIDATA.slider_center == '1') ? true : false;
    var $slider_centerindex = true;
    var $slider_navcolor = WOOSVIDATA.slider_navcolor;
    var $slider_position = 'horizontal';
    var $slider_autoslide = (WOOSVIDATA.slider_autoslide == '1') ? true : false;
    var $slider_autoslide_ms = (WOOSVIDATA.slider_autoslide_ms < 1) ? 2500 : WOOSVIDATA.slider_autoslide_ms;
    var $autoHeight = true;
    var $triger_match = (WOOSVIDATA.triger_match == '1') ? true : false;
    var slidesPerView = WOOSVIDATA.columns;
    /*END SLIDER VARS*/
    /*BROWSER SUPPORT*/
    var $browser = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
    //var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
    //var is_explorer = navigator.userAgent.indexOf('MSIE') > -1;
    //var is_firefox = navigator.userAgent.indexOf('Firefox') > -1;
    var is_safari = navigator.userAgent.indexOf("Safari") > -1;
    var $imagesLoaded = (WOOSVIDATA.imagesloaded == '1') ? true : false;
    var indexposel = false;
    var $showactiveIndex = false;
    //var is_opera = navigator.userAgent.toLowerCase().indexOf("op") > -1;
    //var $reset_variations = $form.find('.reset_variations');
    return{NAME: "Application initialize module", VERSION: WOOSVIDATA.jsversion,
        init: function () {

            if (!WOOSVIDATA.gallery.thumbs) {
                $disable_thumb = true;
                $('div#woosvimain').addClass('svi100');
                $('div#woosvithumbs').addClass('svihidden');
            }

            if ($browser)
                $woosvi_lens = false;
            if (typeof wc_add_to_cart_variation_params === 'undefined') { //NOT VARIATIONS
                $hide_thumbs = false;
            }


            if (!$disable_thumb) {
                if (!$slider) {
                    var cols = ' columns-' + WOOSVIDATA.columns;
                    if ($container.find('ul.svithumbnails li').length > 0 && !$keep_thumbs)
                        $container.find('ul.svithumbnails li').remove();
                    else
                        $container.find('div#woosvithumbs').prepend('<ul class="svithumbnails' + cols + '"></ul>');
                }
                if ($hide_thumbs)
                    $container.find('div#woosvithumbs').hide();
            }

            if (!$slider && WOOSVIDATA.slider_position > 0) {

                switch (WOOSVIDATA.slider_position) {
                    case '1':
                        $container.addClass('slider-vertical-l');
                        break;
                    case '2':
                        $container.addClass('slider-vertical-r');
                        break;
                }

            }

            WOOSVI.STARTS.loadImages();
            WOOSVI.STARTS.imagesLoaded();
            WOOSVI.STARTS.ActivateSwapImage();
            WOOSVI.STARTS.prettyPhoto();
            WOOSVI.STARTS.thumbSelect();
        },
        reset: function () {
            if ($slider) {
                if ($sync1)
                    $sync1.removeAllSlides();
                if (!$disable_thumb && $sync2)
                    $sync2.removeAllSlides();
            }
        },
        loadImages: function ($is_reset) {
            if ($is_reset)
                $LoadLens_running = false;
            if (!$slider && !$keep_thumbs) {
                $container.find('div#woosvimain').html('');
                $container.find('ul.svithumbnails li').remove();
                $('div#woosvimain').prepend('<div class="sviLoader_thumb"></div>');
            }

            var sels = WOOSVI.STARTS.getActiveVariationsInSelect();
            //var sels = $form.find('.variations select').size();
            if (sels.length > 0 && !$is_reset) {
                WOOSVI.STARTS.showVariation();
                return;
            }

            if (!$slider && !$is_variation && WOOSVIDATA.gallery.thumbs) {
                if ($exist_thumbs)
                    $('div#woosvimain').append($(WOOSVI.STARTS.buildImgTag(WOOSVIDATA.gallery.thumbs[0].fullimg)));
            }

            if (!WOOSVIDATA.gallery.thumbs && $('div#woosvimain img').length < 1 && !$slider) {
                if ($exist_thumbs) {
                    $('div#woosvimain').append($(WOOSVI.STARTS.buildImgTag(WOOSVIDATA.gallery.thumbs[0].fullimg)));
                }
            }

            if (!$slider && $is_variation && $('div#woosvimain img').length < 1) {
                if ($exist_thumbs)
                    $('div#woosvimain').append($(WOOSVI.STARTS.buildImgTag(WOOSVIDATA.gallery.thumbs[0].fullimg)));
            }
            WOOSVI.STARTS.loadThumb(WOOSVIDATA.gallery.thumbs, false);
            WOOSVI.STARTS.reOrderThumbs();
        },
        buildImgTag: function (img) {

            if (typeof img !== 'undefined') { //NOT VARIATIONS

                return $img_tag
                        .replace(/{{image_src}}/g, img.src)
                        .replace("{{image_class}}", img.class)
                        .replace("{{image_alt}}", img.alt)
                        .replace("{{image_title}}", img.title)
                        .replace("{{image_woosvislug}}", img['data-woosvislug'])
                        .replace("{{image_svizoom}}", img['data-svizoom-image'])
                        .replace("{{image_svikey}}", img['data-svikey'])
                        .replace("{{image_srcset}}", (img.srcset) ? img.srcset : '')
                        .replace("{{image_sizes}}", (img.sizes) ? img.sizes : '')
                        .replace("{{image_width}}", (img.width) ? img.width : '')
                        .replace("{{image_height}}", (img.height) ? img.height : '');
            }
        },
        loadMain: function ($main) {

            if (!$main)
                return;
            var $classes = [''];
            var item = WOOSVI.STARTS.itemBuilder($main, $classes, true);
            if ($slider) {
                var arr_main = $container.find('div#woosvimain .swiper-wrapper .swiper-slide').map(function () {

                    if ($(this).data('thumb') === $(item).data('thumb'))
                        return true;
                }).get();
                if (jQuery.isEmptyObject(arr_main)) {
                    $container.find('div#woosvimain .swiper-wrapper').append($(item));
                }
                if (!$disable_thumb) {
                    var arr = $container.find('div#woosvithumbs .swiper-wrapper .swiper-slide').map(function () {

                        if ($(this).data('thumb') === $(item).data('thumb'))
                            return true;
                    }).get();
                    if (jQuery.isEmptyObject(arr)) {
                        $container.find('div#woosvithumbs .swiper-wrapper').append($(item));
                    }
                }
            } else {
                if (!$keep_thumbs)
                    $container.find('ul.svithumbnails').append($(item));
            }
        },
        loadThumb: function ($thumbs, $is_selected) {

            var $size = Object.keys($thumbs).length;

            WOOSVI.STARTS.reset();

            jQuery.each($thumbs, function ($loop, v) {

                var item = '';
                var item_full = '';
                var $classes = [''];
                var arr, arr_main;
                var $x = $container.find('ul.svithumbnails li').length;
                if ($x === 0 || $x % WOOSVIDATA.columns === 0) {
                    $classes.push('first');
                }
                if (($x + 1) % WOOSVIDATA.columns === 0) {
                    $classes.push('last');
                }
                if ($x === $size)
                    $classes.push('last');
                item = WOOSVI.STARTS.itemBuilder(v, $classes);
                if ($slider) {

                    arr_main = $container.find('div#woosvimain .swiper-wrapper .swiper-slide').map(function () {

                        if ($(this).data('thumb') === $(item).data('thumb'))
                            return true;
                    }).get();
                    arr = $container.find('div#woosvithumbs .swiper-wrapper .swiper-slide').map(function () {

                        if ($(this).data('thumb') === $(item).data('thumb'))
                            return true;
                    }).get();
                    if (jQuery.isEmptyObject(arr_main)) {
                        item_full = WOOSVI.STARTS.itemBuilder(v, $classes, true);
                        if ($sync1)
                            $sync1.appendSlide(item_full);
                        else
                            $container.find('div#woosvimain .swiper-wrapper').append($(item_full));
                    }

                    if (!$disable_thumb && jQuery.isEmptyObject(arr)) {
                        if ($sync2)
                            $sync2.appendSlide(item);
                        else
                            $container.find('div#woosvithumbs .swiper-wrapper').append($(item));
                    }

                } else {

                    arr = $container.find('ul.svithumbnails li').map(function () {

                        if ($(this).data('thumb') === $(item).data('thumb'))
                            return true;
                    }).get();
                    if (!$disable_thumb && jQuery.isEmptyObject(arr)) {
                        $container.find('ul.svithumbnails').append($(item));
                    }

                }
            });

            if ($slider_center) {
                if ($sync1)
                    $sync1.slideTo(0, 0, false);
            }
        },
        reOrderThumbs: function () {
            if ($slider)
                return;
            jQuery.each($container.find('ul.svithumbnails li'), function ($loop, v) {

                var $classes = [''];
                if ($loop === 0 || $loop % WOOSVIDATA.columns === 0) {
                    $classes.push('first');
                }
                if (($loop + 1) % WOOSVIDATA.columns === 0) {
                    $classes.push('last');
                }

                $(v).removeClass('first_pre').removeClass('first').removeClass('last').addClass($classes.join(' '));
            });
        },
        itemBuilder: function (v, $classes, full) {
            var $item = '';
            if ($slider) {
                $item += '<div data-thumb="' + v.thumb[0] + '" data-src="' + v.full[0] + '" class="swiper-slide">';
                if (full)
                    $item += WOOSVI.STARTS.buildImgTag(v.fullimg);
                else
                    $item += WOOSVI.STARTS.buildImgTag(v.thumbimg);
                $item += '</div>';
            } else {
                $item += '<li data-thumb="' + v.thumb[0] + '" data-src="' + v.full[0] + '" class="' + $classes.join(' ') + '">';
                $item += '<div class="sviLoader_thumb"></div>';
                $item += WOOSVI.STARTS.buildImgTag(v.thumbimg);
                $item += '</li>';
            }
            return $item;
        },
        imagesLoaded: function ($is_reset) {
            if ($is_reset)
                $LoadLens_running = false;
            if (runningImgLoader)
                return;
            if ($imagesLoaded) {
                $.getScript("//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.1/imagesloaded.pkgd.min.js")
                        .done(function (script, textStatus) {
                            WOOSVI.STARTS.runImageProcess($is_reset);
                        })
                        .fail(function (jqxhr, settings, exception) {
                            $("div.log").text("Triggered ajaxError handler.");
                        });
            } else {
                WOOSVI.STARTS.runImageProcess($is_reset);
            }


        },
        runImageProcess: function ($is_reset) {
            runningImgLoader = true;
            $container.imagesLoaded()
                    .always(function (instance) {
                        $container.find('.sviLoader_thumb').fadeOut().remove();
                        if ($('.sivmainloader').length > 0) {
                            $('.sivmainloader').fadeOut('fast', function () {
                                $container.find('.svihidden').removeClass('svihidden');
                                WOOSVI.STARTS.completeAction($is_reset);
                            }).remove();
                        } else {
                            $container.removeClass('svihidden');
                            WOOSVI.STARTS.completeAction($is_reset);
                        }
                    })
                    .done(function (instance) {

                    }).progress(function (instance, image) {

            }).fail(function () {
                $container.find('.sviLoader_thumb').fadeOut().remove();
                runningImgLoader = false;
            });
        },
        completeAction: function ($is_reset) {

            if ($is_reset) {
                if ($hide_thumbs)
                    $container.find('div#woosvithumbs').hide();
            }

            // $container.find('.whitespacesvi').remove();
            $container.find('.sviLoader_thumb').fadeOut().remove();
            runningImgLoader = false;
            if ($slider) {
                if ($showactiveIndex)
                    indexposel = $('#woosvimain .swiper-slide').find('[data-svikey="' + $showactiveIndex + '"]').parent().index()
                if (!$sync1)
                    WOOSVI.STARTS.domSlider();
                WOOSVI.STARTS.SwiperSlider();
                WOOSVI.STARTS.LoadLens();
                if ($sync1)
                    $sync1.update();
                $sync1.slideTo(0);
                if ($sync2) {
                    $sync2.update();
                    if ($slider_center)
                        $sync2.slideTo(0);

                    if ($thumbnails_showactive) {
                        if ($showactiveIndex)
                            $('#woosvithumbs .swiper-slide').find('[data-svikey="' + $showactiveIndex + '"]').parent().addClass($thumbnails_showactive);
                        else
                            $('#woosvithumbs .swiper-slide').first().addClass($thumbnails_showactive);
                    }
                    if ($slider_autoslide) {
                        $sync1.startAutoplay();
                    }
                }

            } else {
                if ($thumbnails_showactive) {
                    //console.log('$showactiveIndex', $showactiveIndex)
                    if ($showactiveIndex)
                        $('ul.svithumbnails li').find('[data-svikey="' + $showactiveIndex + '"]').parent().addClass($thumbnails_showactive);
                    else
                        $('ul.svithumbnails li').first().addClass($thumbnails_showactive);
                }
                WOOSVI.STARTS.LoadLens();
            }

            if ($keep_thumbs) {
                if ($slider) {
                    if ($sync1) {
                        $sync1.slideTo(indexposel, 500, false);
                    }
                    if ($sync2) {
                        $sync2.slideTo(indexposel, 500, false);
                    }
                } else {
                    if (indexposel >= 0) {
                        $container.find('ul.svithumbnails li:eq(' + indexposel + ') img').trigger('click');
                    }
                }
            }

            if ($slider && $thumbnails_showactive && $showactiveIndex) {
                if ($sync1) {
                    $sync1.slideTo(indexposel, 500, false);
                }
                if ($sync2) {
                    $sync2.slideTo(indexposel, 500, false);
                }
            }


            if (!$prettyphoto_running && $woosvi_lightbox && !$woosvi_lightbox_new && !$prettyphoto_themestyle) {
                $prettyphoto_running = true;
                $('a[rel^="prettyphoto"], .prettyphoto').prettyPhoto({
                    hook: 'data-rel',
                    social_tools: false,
                    theme: $prettyphoto_style,
                    horizontal_padding: 20,
                    opacity: 0.8,
                    deeplinking: false
                });
            }
            $showactiveIndex = false;
        },
        showVariation: function ($event) {
            //console.log('showVariation')

            $LoadLens_running = false;
            var $items = [];
            $.each(WOOSVI.STARTS.getActiveVariationsInSelect(), function (i, v) {

                var $variation = v.replace(/ /g, '').toLowerCase();
                if (!$variation) {
                    return false;
                }

                var $items_new = WOOSVI.STARTS.getVariationImages($variation);

                if ($items_new) {
                    $items = $items.concat($items_new);
                }

            });

            var a = $.map($items, function (value, key) {
                return value.thumb[0];
            });

            var b = $.map($container.find('div#woosvithumbs').find('[data-thumb]'), function (value, key) {
                return $(value).data('thumb');
            });

            if (JSON.stringify(a) == JSON.stringify(b)) {
                if ($items.length < 1) {
                    //console.log($items.length);
                    $is_reset = true;
                    WOOSVI.STARTS.loadImages(true);
                    WOOSVI.STARTS.imagesLoaded(true);
                    setTimeout(function () {
                        $is_reset = false;
                    }, 500);
                }
                return;
            }

            if ($items.length > 0) {
                if (!$keep_thumbs)
                    $container.find('ul.svithumbnails li').remove();
                if ($hide_thumbs)
                    $container.find('div#woosvithumbs').show();
                if (!$slider && !$keep_thumbs)
                    $container.find('div#woosvimain').html('').prepend($(WOOSVI.STARTS.buildImgTag($items[0].fullimg)));
                if (!$keep_thumbs) {
                    WOOSVI.STARTS.loadThumb($items, true);
                    WOOSVI.STARTS.reOrderThumbs();
                    WOOSVI.STARTS.imagesLoaded();
                } else {

                    $is_reset = true;
                    if (!imagesLoaded) {
                        imagesLoaded = true;
                        WOOSVI.STARTS.loadImages(true);
                    }
                    WOOSVI.STARTS.imagesLoaded(true);
                    setTimeout(function () {
                        $is_reset = false;
                    }, 500);
                }

                if ($keep_thumbs) {
                    indexposel = $container.find('div#woosvithumbs').find('[data-thumb="' + $items[0].thumb[0] + '"]').index();
                }

            } else {
                $is_reset = true;
                WOOSVI.STARTS.loadImages(true);
                WOOSVI.STARTS.imagesLoaded(true);
                setTimeout(function () {
                    $is_reset = false;
                }, 500);
            }

        },
        getCombinations: function (chars) {
            var result = [];
            var f = function (prefix, chars) {
                for (var i = 0; i < chars.length; i++) {
                    if (prefix) {
                        result.push(prefix + '_svipro_' + chars[i]);
                        f(prefix + '_svipro_' + chars[i], chars.slice(i + 1));
                    } else {
                        result.push(prefix + chars[i]);

                        f(prefix + chars[i], chars.slice(i + 1));
                    }
                }
            }
            f('', chars);
            return result;
        },
        getActiveVariationsInSelect: function () {
            var arr = $form.find('.variations select').map(function () {
                if (this.value !== '')
                    return decodeURI(this.value);
            }).get();

            if (!$triger_match) {
                if (arr.length > 1) {
                    var arr = WOOSVI.STARTS.getCombinations(arr);
                }
            } else {
                if (arr.length > 1) {
                    var combo = arr.join('_svipro_');
                    arr = [combo];
                }
            }

            if (arr.length > 0)
                arr.push('sviproglobal');

            return arr;
        },
        isJson: function (str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        },
        getVariationImages: function ($variation) {
            var $items = [];
            if ($variation) {

                jQuery.each(WOOSVIDATA.gallery.thumbs, function ($loop, v) {
                    if (WOOSVI.STARTS.isJson(v.woosvi_slug)) {
                        $.each(JSON.parse(v.woosvi_slug), function (i, slug) {
                            if (typeof (slug) !== 'object' && slug && decodeURIComponent(slug.replace(/ /g, '').toLowerCase()) == $variation)
                                $items.push(v);
                        });
                    }
                });

                var $size = Object.keys($items).length;
                //console.log($items)
                if ($size > 0)
                    return $items;
                else
                    return false;
            }
        },
        ActivateSwapImage: function () {

            $container.on('click', 'ul.svithumbnails img', function (e) {
                if ($thumbnails_showactive) {
                    $('ul.svithumbnails li').removeClass($thumbnails_showactive);
                    $(this).parent().addClass($thumbnails_showactive);
                }
                WOOSVI.STARTS.initSwap(this);
            });
        },
        initSwap: function (v) {

            var $svikey = $(v).data('svikey');
            var $image_data = false;
            //if ($svikey >= 0)
                $image_data = WOOSVIDATA.gallery.thumbs[$svikey];
            var image = new Image();
            if ($image_data)
                image.src = $image_data.single[0];

            if ($('div#woosvimain img').attr('src') != image.src) {
                $('div#woosvimain').prepend('<div class="sviLoader_thumb"></div>');
                $(image).on("load", function () {
                    $('div#woosvimain img').fadeOut('fast').remove();
                    $('div#woosvimain').prepend($(WOOSVI.STARTS.buildImgTag($image_data.fullimg)).hide());
                    $('div#woosvimain img').fadeIn('fast');
                    $('div#woosvimain').find('.sviLoader_thumb').fadeOut().remove();
                    $('div.sviLoader_thumb').remove();
                    $LoadLens_running = false;
                    WOOSVI.STARTS.LoadLens();
                });
            }
        },
        thumbSelect: function (v) {

            if (!$thumbselect)
                return;

            $container.on('click', 'div#woosvithumbs img', function (e) {

                if (!e.originalEvent)
                    return;

                $showactiveIndex = $(this).data('svikey');
                //console.log('KEY', $showactiveIndex)
                var $woosvislug = $(this).data('woosvislug');
                var sels = $form.find('.variations select').length;
                //console.log(sels);
                $.each($woosvislug, function (i, slug) {
                    if ($triger_match && sels > 1) {
                        if (slug.indexOf("_svipro_") > 0) {
                            slug = slug.split('_svipro_');
                            $x = 0;
                            $trigger = false;
                            $.each(slug, function (i, v) {
                                if (i == $x)
                                    $trigger = true;
                                WOOSVI.STARTS.filterOptions(v, $trigger);
                            });
                        }
                    } else {
                        if (slug.indexOf("_svipro_") > 0) {
                            slug = slug.split('_svipro_');
                            $.each(slug, function (i, v) {
                                WOOSVI.STARTS.filterOptions(v);
                            });
                        } else {
                            //console.log($woosvislug)
                            WOOSVI.STARTS.filterOptions(slug);
                        }
                    }
                });
                //console.log("ENDED", $form.find('.variations select:last-child'))
                if ($form.find('.variations select').size > 1) {
                    $form.find('.variations select:last-child').trigger('change')
                } else
                    $form.find('.variations select').trigger('change')
            });
        },
        filterOptions: function ($woosvislug, $trigger) {
            if ($woosvislug === '')
                return;
            //console.log('filterOptions')
            var data = $form.find('option').map(function () {
                var $check = this.value.replace(/ /g, '').toLowerCase();
                var selval = ($(this).closest('select').val()) ? $(this).closest('select').val() : 'false';
                var select = $(this).closest('select');
                if (decodeURIComponent($woosvislug) == $check && decodeURIComponent(selval.replace(/ /g, '').toLowerCase()) !== $woosvislug && select.val() !== this.value) {

                    var possibleclass = 'attribute_' + $(this).closest('select').attr('id') + '_' + $check;
                    //$(this).attr('selected', 'selected');
                    //if (!$keep_thumbs) { Removed in 3.2.20
                    $slider_centerindex = false;
                    //$(this).prop("selected", true);
                    //console.log(this.value);

                    if ($('.' + possibleclass.replace(/^[^a-z]+[\w\u0430-\u044f]+|[^\w:.-]+/gi, "")).length > 0)
                        $('.' + possibleclass.replace(/^[^a-z]+[\w\u0430-\u044f]+|[^\w:.-]+/gi, "")).addClass('selectedswatch');
                    //} Removed in 3.2.20
                    select.val(this.value);
                    $("div.sviZoomContainer").remove();

                    return this.value;
                }
            }).get();
            return data;
        },
        /*LOAD LENS*/
        LoadLens: function (slider) {

            if (!$woosvi_lens)
                return;
            if ($LoadLens_running)
                return;
            $LoadLens_running = true;
            $("div.sviZoomContainer").remove();
            var ez, lensoptions;
            ez = $("div#woosvimain .swiper-slide-active img, div#woosvimain>img");
            lensoptions = {
                container: 'sviZoomContainer',
                cursor: 'pointer',
                attrImageZoomSrc: 'svizoom-image',
                galleryActiveClass: 'active',
                containLensZoom: $containLensZoom,
                loadingIcon: true,
                borderColour: WOOSVIDATA.lens_border,
                lensShape: WOOSVIDATA.lens_type,
                lensSize: WOOSVIDATA.lens_size,
                zoomType: WOOSVIDATA.lens_zoomtype,
                easing: (WOOSVIDATA.lens_easing == '1') ? true : false,
                scrollZoom: (WOOSVIDATA.lens_scrollzoom == '1') ? true : false,
                lensFadeIn: (WOOSVIDATA.lens_fade == '1') ? true : false,
                lensFadeOut: (WOOSVIDATA.lens_fade == '1') ? 500 : false,
                zIndex: 1000,
                slider: (slider) ? slider : false,
                autoslide: $slider_autoslide
            };

            ez.ezPlus(lensoptions);


        },
        /*END LOAD LENS*/
        /*PRETTY PHOTO*/
        prettyPhoto: function () {

            if (!$woosvi_lightbox) //IF LIGTHBOX NOT ACTIVE RETURN
                return;
            if (!$woosvi_lightbox_new) {

                $container.on('click', 'div#woosvimain img', function (e) {
                    e.preventDefault();
                    if (!jQuery.prettyPhoto) {
                        return;
                    }
                    var click_url = $(this).data('svizoom-image');
                    var click_title = $(this).attr('title');
                    var api_images = [];
                    var api_titles = [];
                    $('div#woosvithumbs ul.svithumbnails li, div#woosvithumbs .swiper-slide').each(function (i, v) {

                        var $svikey = $(this).find('img').data('svikey');
                        var $image_data = false;
                        if ($svikey >= 0)
                            $image_data = WOOSVIDATA.gallery.thumbs[$svikey];
                        if ($image_data) {
                            api_images.push($image_data.full[0]);
                            api_titles.push($(this).find('img').attr('title'));
                        }
                    });
                    if (jQuery.isEmptyObject(api_images) || api_images.length === 0) {
                        api_images.push(click_url);
                        api_titles.push(click_title);
                    }

                    jQuery.prettyPhoto.open(api_images, api_titles);
                    $('div.pp_gallery').find('img[src="' + click_url + '"]').parent().trigger('click');
                });
            } else {

                var block = '<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button><button class="pswp__button pswp__button--share" title="Share"></button><button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button><button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"> </button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"> </button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>';
                $container.append(block);
                var pswpElement = document.querySelectorAll('.pswp')[0];
                $container.on('click', 'div#woosvimain img', function (e) {
                    if ($woosvi_ps_thumbs)
                        $('body').append('<div class="photoSwipe_innerthumbs flexslider"><ul class="slides"></ul></div>');
                    // build items array

                    var index_key = $(this).data('svikey');
                    var index = 0;
                    var svi_items = [];
                    $('div#woosvithumbs ul.svithumbnails li, div#woosvithumbs .swiper-slide').each(function (i, v) {

                        var $svikey = $(this).find('img').data('svikey');

                        if (index_key === $svikey)
                            index = i;

                        var $image_data = false;

                        if ($svikey >= 0)
                            $image_data = WOOSVIDATA.gallery.thumbs[$svikey];

                        if ($image_data) {
                            svi_items.push({
                                src: $image_data.full[0],
                                w: $image_data.full[1],
                                h: $image_data.full[2],
                                msrc: $image_data.thumb[0],
                                title: $image_data.title
                            });
                        }
                    });

                    // define options (if needed)
                    var options = {
                        closeEl: (WOOSVIDATA.ps_close == '1') ? true : false,
                        captionEl: (WOOSVIDATA.ps_caption == '1') ? true : false,
                        fullscreenEl: (WOOSVIDATA.ps_fullscreen == '1') ? true : false,
                        zoomEl: (WOOSVIDATA.ps_zoom == '1') ? true : false,
                        shareEl: (WOOSVIDATA.ps_share == '1') ? true : false,
                        counterEl: (WOOSVIDATA.ps_counter == '1') ? true : false,
                        arrowEl: (WOOSVIDATA.ps_arrows == '1') ? true : false,
                        preloaderEl: true,
                        index: index // start at first slide
                    };
                    // Initializes and opens PhotoSwipe
                    gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, svi_items, options);
                    gallery.init();

                    if (WOOSVIDATA.ps_zoom !== '1') {
                        gallery.ui.onGlobalTap = function () {
                            return false;
                        }
                    }
                    WOOSVI.STARTS.photoSwipe();
                });
            }
        },
        photoSwipe: function () {

            if (!$woosvi_ps_thumbs)
                return;
            if (gallery) {

                gallery.listen('afterChange', function () {
                    $('div.photoSwipe_innerthumbs img').removeClass("svifaded");
                    $("div.photoSwipe_innerthumbs img").eq(gallery.getCurrentIndex()).addClass('svifaded');
                });
                gallery.listen('close', function () {
                    $('.photoSwipe_innerthumbs').remove();
                });
                $('div#woosvithumbs img').clone().appendTo("div.photoSwipe_innerthumbs ul");
                $("div.photoSwipe_innerthumbs ul img").wrap("<li class='svili'></div>");
                $("div.photoSwipe_innerthumbs img").eq(gallery.getCurrentIndex()).addClass('svifaded');
                $('body').on('click', 'div.photoSwipe_innerthumbs img', function (e) {
                    $('div.photoSwipe_innerthumbs img').removeClass("svifaded");
                    gallery.goTo($("div.photoSwipe_innerthumbs img").index($(this)));
                });
                $('.photoSwipe_innerthumbs').flexslider({animation: "slide",
                    controlNav: false,
                    animationLoop: false,
                    barsSize: {top: 44, bottom: 44},
                    slideshow: false,
                    itemWidth: 130,
                    itemMargin: 5,
                    prevText: "", //String: Set the text for the "previous" directionNav item
                    nextText: "",
                    minItems: WOOSVIDATA.columns,
                    start: function (slider) {
                        if (slider.pagingCount === 1)
                            slider.addClass('flex-centered');
                    }
                });
                if (WOOSVIDATA.ps_caption == '1') {
                    $('.pswp__caption__center').parent().css({
                        top: 0,
                        bottom: 'auto'
                    });
                }
            }

        },
        /*END PRETTY PHOTO*/
        /*SWIPPER SLIDER*/
        domSlider: function () {
            var sviHide = '';
            var navigation = '';
            var navigation_thumb = '';
            if ($slider_navigation) {

                if ($woosvi_lens)
                    sviHide = ' svihidden';
                navigation = '<div class="swiper-button-next swiper-button-main swiper-button' + $slider_navcolor + sviHide + '"></div><div class="swiper-button-prev swiper-button-main swiper-button' + $slider_navcolor + sviHide + '"></div>';
                if ($woosvi_lens) {
                    $('body').append(navigation);
                    navigation = '';
                }

            }

            if ($slider_navigation_thumb) {
                navigation_thumb = '<div class="swiper-button-next swiper-button-next-thumb thumbssvi-nav swiper-button' + $slider_navcolor + '"></div><div class="swiper-button-prev swiper-button-prev-thumb thumbssvi-nav swiper-button' + $slider_navcolor + '"></div>';
            }

            if (!$woosvi_lens) {
                $container.find('div#woosvimain').append(navigation);
            }
            $container.find('div#woosvithumbs').prepend(navigation_thumb);
            if (is_safari)
                $autoHeight = false;
            if (!$disable_thumb) {
                switch (WOOSVIDATA.slider_position) {
                    case '1':
                        $slider_position = 'vertical';
                        $container.addClass('slider-vertical-l');
                        slidesPerView = 'auto';
                        if (!is_safari)
                            $autoHeight = false;
                        break;
                    case '2':
                        $slider_position = 'vertical';
                        $container.addClass('slider-vertical-r');
                        slidesPerView = 'auto';
                        if (!is_safari)
                            $autoHeight = false;
                        break;
                }
            }
        },
        SwiperSlider: function () {

            if (!$sync1) {

                $sync1 = new Swiper('div.svigallery-main', {
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    spaceBetween: 10,
                    autoHeight: $autoHeight,
                    observer: true,
                    centeredSlides: false,
                    autoplayDisableOnInteraction: true,
                    autoplay: ($slider_autoslide) ? $slider_autoslide_ms : $slider_autoslide,
                    onTransitionEnd: function (swipper) {
                        $LoadLens_running = false;
                        WOOSVI.STARTS.LoadLens(swipper);
                    },
                    onImagesReady: function (swipper) {
                        WOOSVI.STARTS.LoadLens(swipper);

                    },
                    onSlideChangeEnd: function (swiper) {
                        var activeIndex = swiper.activeIndex;
                        if ($slider_center) {

                            $($sync2.slides).removeClass('is-selected').removeClass($thumbnails_showactive);
                            $($sync2.slides).eq(activeIndex).addClass('is-selected').addClass($thumbnails_showactive);
                            $sync2.slideTo(activeIndex, 500, false);
                        } else {
                            $($sync2.slides).removeClass('is-selected').removeClass($thumbnails_showactive);
                            $($sync2.slides).eq(activeIndex).addClass('is-selected').addClass($thumbnails_showactive);
                        }
                    }
                });
                WOOSVI.STARTS.swiperButtonsFix('onSlideChangeEnd');
            }

            if (!$woosvi_lens && $slider_autoslide) {
                $("#woosvi_strap").mouseenter(function () {
                    // restart autoplay

                    $sync1.stopAutoplay();
                });
                $("#woosvi_strap").mouseleave(function () {
                    // restart autoplay

                    $sync1.startAutoplay();
                });
            }
            if ($woosvi_lens && $slider_autoslide) {
                $("#woosvi_strap").mouseenter(function () {

                    $sync1.animating = false;
                    // restart autoplay
                    $sync1.stopAutoplay();
                });
            }


            if (!$sync2 && !$disable_thumb) {
                $sync2 = new Swiper('div.svigallery-thumbs', {
                    nextButton: '.swiper-button-next-thumb',
                    prevButton: '.swiper-button-prev-thumb',
                    spaceBetween: 10,
                    centeredSlides: ($slider_center == '1') ? false : true,
                    autoHeight: $autoHeight,
                    slidesPerView: slidesPerView,
                    touchRatio: 0.2,
                    slideToClickedSlide: true,
                    direction: $slider_position,
                    observer: true,
                    onClick: function (swiper, event) {
                        var clicked = swiper.clickedIndex;
                        if ($slider_center && !$(event.target).hasClass('thumbssvi-nav') && $slider_centerindex) {

                            $(swiper.slides).removeClass('is-selected').removeClass($thumbnails_showactive);
                            $(swiper.clickedSlide).addClass('is-selected').addClass($thumbnails_showactive);
                            $sync1.slideTo(clicked, 500, true);
                        } else {

                            $(swiper.slides).removeClass('is-selected').removeClass($thumbnails_showactive);
                            $(swiper.clickedSlide).addClass('is-selected').addClass($thumbnails_showactive);
                        }
                        $slider_centerindex = true;
                    },
                    onImagesReady: function () {
                        var hidden;
                        if ($hide_thumbs)
                            hidden = 'display:none;';

                        if ((WOOSVIDATA.slider_position == '1' || WOOSVIDATA.slider_position == '2')) {
                            $container.attr('style', 'height:' + $('div#woosvimain').height() + 'px!important;overflow:hidden');
                            $container.find('#woosvithumbs').attr('style', 'max-height:' + $('div#woosvimain').height() + 'px!important;' + hidden);
                        }
                    }
                });
                if (!$slider_center) {
                    $sync1.params.control = $sync2;
                    $sync2.params.control = $sync1;
                }

                $($sync2.slides).eq(0).addClass($thumbnails_showactive);

            }

        },
        swiperButtonsFix: function ($action) {
            if (!$woosvi_lens)
                return;
            WOOSVI.STARTS.runnerSlide();
            $sync1.on($action, function () {

                WOOSVI.STARTS.runnerSlide();
            });
        },
        runnerSlide: function () {
            var self = $("div#woosvimain .swiper-slide-active img");
            if (self.length > 0) {
                var $nzHeight = self.height();
                //get offset of the non sviZoomed image
                var $nzOffset = self.offset();
                var rt = ($(window).width() - (self.offset().left + self.outerWidth())) + 15;
                var lt = self.offset().left + 15;
                var centerY = $nzOffset.top + $nzHeight / 2;
                var next = {
                    position: 'absolute',
                    right: rt,
                    top: centerY,
                    'z-index': 1049,
                };
                var prev = {
                    position: 'absolute',
                    left: lt,
                    top: centerY,
                    'z-index': 1049,
                };
                $(".swiper-button-next.swiper-button-main").animate(next, 250, function () {
                    $(this).show().removeClass('svihidden');
                });
                $(".swiper-button-prev.swiper-button-main").animate(prev, 250, function () {
                    $(this).show().removeClass('svihidden');
                });
            }
        },
        /*END SWIPPER SLIDER*/
    };
}(jQuery, window, document);
