if (!WOOSVIADM) {
    var WOOSVIADM = {}
} else {
    if (WOOSVIADM && typeof WOOSVIADM !== "object") {
        throw new Error("WOOSVIADM is not an Object type")
    }
}
WOOSVIADM.isLoaded = false;
WOOSVIADM.STARTS = function ($) {
    return{NAME: "Application initialize module", VERSION: 1.0, init: function () {
            if ($('body').hasClass('woocommerce_page_woocommerce_svi')) {
                this.loadInits();
                this.licenseOptions();
            }
            this.notices();
            this.initMedia();
            this.goMediaGallery();
            this.initMediaGal();
        },
        loadInits: function () {
            $('input#columns').attr('type', 'number').attr('min', 1).attr('max', 10);
            $('input#lens_size').attr('type', 'number').attr('min', 100).attr('max', 300);

            $('input#lightbox_width').attr('type', 'number').attr('min', 10).attr('max', 90);
            $('input#lightbox_height').attr('type', 'number').attr('min', 100).attr('max', 1000);

        },
        licenseOptions: function () {
            $('div.svival').hide();
            $("form#woosvi_license").submit(function (event) {
                event.preventDefault();
                $('.notice').slideUp('fast').remove();
                $('div.svival').addClass('is-active').fadeIn();
                jQuery.ajax({
                    url: ajaxurl,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'woosvi_licenseverify',
                        data: $('form#woosvi_license').serialize()
                    },
                    success: function (response) {
                        $('div.svival.is-active').fadeOut(function () {
                            if (response.status && response.status != 'warning') {

                                $('form#woosvi_license').append('<div class="notice notice-success is-dismissible">' + response.message + '. Page wil reload shorthly.</div>');
                                setTimeout(function () {
                                    $('.notice.notice-success').slideUp('fast').fadeOut(function () {
                                        window.location.reload();
                                    });
                                }, 2500);
                            } else if (response.status == 'warning') {
                                $('form#woosvi_license').append('<div class="notice notice-warning is-dismissible">' + response.message + '</div>');
                            } else {
                                $('form#woosvi_license').append('<div class="notice notice-error is-dismissible">' + response.message + '</div>');
                            }
                        });
                    }
                });
            })
        },
        notices: function () {
            jQuery(document).on('click', '.woosvi-notice-dismissed .notice-dismiss', function () {

                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'woosvi_dismiss_notice'
                    }
                })

            })
        },
        /**
         * Block edit screen
         */
        block: function () {
            $('#sviproimages_tab_data').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        },
        /**
         * Unblock edit screen
         */
        unblock: function () {
            $('#sviproimages_tab_data').unblock();
        },
        initMedia: function () {
            $('#svibulkbtn:not(.active)').on('click', function () {
                WOOSVIADM.STARTS.reloadSelect();
                //WOOSVIADM.STARTS.reload();
            });
        },
        reloadSelect: function () {
            WOOSVIADM.STARTS.block();
            var wrapper = $('div#sviselect_container');

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'html',
                data: {
                    action: 'woosvi_reloadselect',
                    data: $('#post_ID').val()
                },
                success: function (response) {
                    wrapper.empty().append(response);

                    // $('div[id^=__wp-uploader]').remove();
                    // WOOSVIADM.STARTS.initMediaGal();
                    //WOOSVIADM.STARTS.goMediaGallery();
                    WOOSVIADM.STARTS.unblock();
                    $('select#sviprobulk').select2();
                }
            });
        },
        reload: function () {
            WOOSVIADM.STARTS.block();
            var wrapper = $('div#sviproimages_tab_data');

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'html',
                data: {
                    action: 'woosvi_reload',
                    data: $('#post_ID').val()
                },
                success: function (response) {

                    wrapper.empty().append(response);
                    $('select#sviprobulk').select2("destroy");

                    $('div[id^=__wp-uploader]').remove();
                    WOOSVIADM.STARTS.initMediaGal();
                    WOOSVIADM.STARTS.goMediaGallery();
                    WOOSVIADM.STARTS.unblock();
                    $('select#sviprobulk').select2();
                }
            });
        },
        goMediaGallery: function () {

            $('#addsviprovariation').on('click', function (e) {
                e.preventDefault();
                var $clone = $('div#svipro_clone').clone();
                var $data = $('#sviprobulk').val();

                var $slug = $data.join("_svipro_");
                $svikey = $('div[id^=svipro_]').size() - 1;

                if (jQuery.inArray("sviproglobal", $data) >= 0 && $data.length > 1)
                    return;
                
                if ($slug !== '' && $('div[data-svigal="' + $slug + '"]').length < 1) {
                    $($clone)
                            .attr('id', 'svipro_' + $svikey)
                            .removeClass('hidden')
                            .attr('data-svigal', $slug)
                            .attr('data-svikey', $svikey)
                            .attr('data-svislug', JSON.stringify($data))
                            .find('h2 span').html($data.join(" + ").replace("sviproglobal", "Global") + ' Gallery');
                    $($clone).find('input.svipro-product_image_gallery').attr('name', 'sviproduct_image_gallery[' + $slug + ']');
                    $($clone).prependTo('#svigallery');

                    WOOSVIADM.STARTS.buildMediaGal($slug, $svikey);
                    WOOSVIADM.STARTS.removeMediaGallery($slug, $svikey);
                    WOOSVIADM.STARTS.removeElementMediaGallery($slug, $svikey);
                }
            });

        },
        initMediaGal: function () {
            $('div[id^=svipro_]').each(function (i, v) {
                WOOSVIADM.STARTS.buildMediaGal($(this).data('svigal'), $(this).data('svikey'));
                WOOSVIADM.STARTS.removeMediaGallery($(this).data('svigal'), $(this).data('svikey'));
                WOOSVIADM.STARTS.removeElementMediaGallery($(this).data('svigal'), $(this).data('svikey'));
            })
        },
        buildMediaGal: function ($slug, $svikey) {
            // Product gallery file uploads.
            var product_gallery_frame;
            // var $image_gallery_ids = $('#product_image_gallery');
            var $image_gallery_ids_svi = $('#svipro_' + $svikey).find('input.svipro-product_image_gallery');
            var $product_images = $('#svipro_' + $svikey).find('ul.product_images');
            var $product_images_woo = $('#product_images_container').find('ul.product_images');


            $('#svipro_' + $svikey).find('.add_product_images_svipro').on('click', 'a', function (event) {
                var $el = $(this);

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if (product_gallery_frame) {
                    product_gallery_frame.open();

                    return;
                }

                // Create the media frame.
                product_gallery_frame = wp.media.frames.product_gallery = wp.media({
                    // Set the title of the modal.
                    title: $el.data('choose'),
                    button: {
                        text: $el.data('update')
                    },
                    states: [
                        new wp.media.controller.Library({
                            title: $el.data('choose'),
                            filterable: 'all',
                            multiple: true
                        })
                    ]
                });


                var ezR = setInterval(function () {
                    if ($(product_gallery_frame.el).find('li.attachment').length > 0) {
                        $(product_gallery_frame.el).find('li.attachment').on('click', function () {
                            $(product_gallery_frame.el).find('tr.compat-field-woosvi-slug').closest('table').remove();
                        })
                        clearInterval(ezR);
                    }
                }, 50);

                // When an image is selected, run a callback.
                product_gallery_frame.on('select', function () {

                    var selection = product_gallery_frame.state().get('selection');
                    var attachment_ids_svi = $image_gallery_ids_svi.val();
                    var bulksvi = [];
                    selection.map(function (attachment) {
                        attachment = attachment.toJSON();

                        if (attachment.id && $product_images.find('ul li.image[data-attachment_id="' + attachment.id + '"]').length < 1) {
                            $('div#svigallery #svipro_nullsvi').find('ul li.image[data-attachment_id="' + attachment.id + '"]').remove();
                            // bulksvi.push(attachment.id);

                            attachment_ids_svi = attachment_ids_svi ? attachment_ids_svi + ',' + attachment.id : attachment.id;

                            var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                            $product_images.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>');

                            $('select#attachments[' + attachment.id + '][woosvi-slug]').val($slug);

                            if ($('#product_images_container').find('ul li.image[data-attachment_id="' + attachment.id + '"]').length < 1) {

                                $product_images_woo.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>');

                            }

                        }


                    });

                    $image_gallery_ids_svi.val(attachment_ids_svi);

                    WOOSVIADM.STARTS.updateGal('div#svipro_nullsvi .svipro-product_image_gallery', 'div#svipro_nullsvi .svipro-product_images_container'); //UPDATE NULL SVI GALLERY

                    WOOSVIADM.STARTS.updateGal('#product_image_gallery', '#product_images_container'); //UPDATE MAIN GALLERY

                });

                // Finally, open the modal.
                product_gallery_frame.open();
            });

        },
        removeMediaGallery: function ($slug, $svikey) {
            $('#svipro_' + $svikey).on('click', 'a.sviprobulk_remove', function (event) {
                event.preventDefault();
                var $input = $(this).closest('div.svi-woocommerce-product-images').find('input.svipro-product_image_gallery').val().split(',');

                $(this).closest('div.svi-woocommerce-product-images').remove();

                $.each($input, function (i, v) {
                    if ($('#svigallery').find('ul li.image[data-attachment_id="' + v + '"]').length < 1)
                        $('#product_images_container').find('ul li.image[data-attachment_id="' + v + '"]').remove();
                });

                WOOSVIADM.STARTS.updateGal('#product_image_gallery', '#product_images_container');//UPDATE MAIN GALLERY


            });

        },
        removeElementMediaGallery: function ($slug, $svikey) {
            // Remove images.
            $('#svipro_' + $svikey).find('.svipro-product_images_container').on('click', 'a.delete', function () {
                var att_id = $(this).closest('li.image').attr('data-attachment_id');
                var $image_gallery_ids_svi = $('#svipro_' + $svikey).find('input.svipro-product_image_gallery');
                var attachment_ids_svi;
                $('select#attachments[' + att_id + '][woosvi-slug]').val('');
                $(this).closest('li.image').remove();
                if ($('#svigallery').find('ul li.image[data-attachment_id="' + att_id + '"]').length < 1)
                    $('#product_images_container').find('ul li.image[data-attachment_id="' + att_id + '"]').remove();

                WOOSVIADM.STARTS.updateGal('#product_image_gallery', '#product_images_container');//UPDATE MAIN GALLERY


                $.each($('#svipro_' + $svikey).find('.svipro-product_images_container ul.product_images li.image'), function (i, v) {
                    attachment_ids_svi = attachment_ids_svi ? attachment_ids_svi + ',' + $(v).data('attachment_id') : $(v).data('attachment_id');
                });

                $image_gallery_ids_svi.val(attachment_ids_svi);

                // Remove any lingering tooltips.
                $('#tiptip_holder').removeAttr('style');
                $('#tiptip_arrow').removeAttr('style');

                return false;
            });

            // Remove images.
            $('#product_images_container').on('click', 'a.delete', function () {
                var attachment_id = $(this).closest('li.image').attr('data-attachment_id');

                var $thumb = $('div#svigallery').find('ul li.image[data-attachment_id="' + attachment_id + '"]');
                var $sviblock = $thumb.closest('div.svipro-product_images_container');
                var $image_gallery_ids_svi = $sviblock.find('input.svipro-product_image_gallery');
                $thumb.remove();
                var attachment_ids = '';

                $sviblock.find('ul li.image').each(function () {
                    var attachment_id = $(this).attr('data-attachment_id');
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                $image_gallery_ids_svi.val(attachment_ids);

                return false;
            });

        },
        updateGal: function ($input, $gal) {
            var $image_gallery_ids = $($input);
            var attachment_ids = '';
            $($gal).find('ul li.image').each(function () {
                var attachment_id = $(this).attr('data-attachment_id');

                attachment_ids = attachment_ids ? attachment_ids + ',' + attachment_id : attachment_id;
            });

            $image_gallery_ids.val(attachment_ids);

            // return attachment_ids;
        }

    }
}(jQuery);
jQuery(document).ready(function () {
    WOOSVIADM.STARTS.init();
});
