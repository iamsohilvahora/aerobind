=== Smart Variations Images PRO ===
Contributors: drosendo
Tags: WooCommerce, images variations, gallery, woocommerce variations, woocommerce variations images, woocommerce images
Requires at least: 4.0.0
Tested up to: 4.8.3
Stable tag: 3.2.40

This is a WooCommerce extension plugin, that allows the user to add any number of images to the product images gallery and be used as variable product variations images in a very simple and quick way, without having to insert images p/variation.

== Description ==

By default WooCommerce will only swap the main variation image when you select a product variation, not the gallery images below it.

This extension allows visitors to your online store to be able to swap different gallery images when they select a product variation.
Adding this feature will let visitors see different images of a product variation all in the same color and style.

This extension will allow the use of multiple images per variation, and simplifies it! How?
Instead of upload one image per variation, upload all the variation images to the product gallery and for each image choose the corresponding slug of the variation on the dropdown.
As quick and simple as that!

<strong>WooCommerce 3.0+ Ready</strong>

<strong>Please give your review!</strong> Good or bad all is welcomed!

<h4>PRO Version</h4>
<ul>
<li>Main Image/thumbnails swap on choose variation</li>
<li>Multiple Images for Variation</li>
<li>Multiple Images Upload for Variation (Bulk)</li>
<li>Ability to assign images to a <b>Combination of Variations</b>.</li>
<li>Ability to use same image across multiple variations.</li>
<li>Allow same image to be shared across different products with diferent variations</li>
<li>Show Variation as Cart Image</li>
<li>Ligthbox</li>
<li>Advanced Slider (Navigation Arrows & Color + Thumbnail Positions) - Fully Responsive</li>
<li>Advanced Magnifier Lens (Lens Style & Size + Lens Border Color + Zoom Type & Effects)</li>
<li>Extra Thumbnail Options (Disabled Thumbnails + Select Swap + Thumbnail Click Swap + Keep Thumbnails Visible)</li>
<li>Extra Layout Fixes (Add Custom CSS Classes + Remove Image Class)</li>
<li>WPML Compatible</li>
<li>Responsive</li>
<li>Priority Support</li>
</ul>


Visit ROSENDO for more information http://www.rosendo.pt

== Installation ==

1. Upload the entire `smart-variations-images` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. On your product assign the product attributes and save
4. Go to Product Gallery and upload/choose your images
5. Assign the slugs to be used for the variation images for each of the image and save.
6. Good luck with sales :)

== Frequently Asked Questions ==

= The plugin doesn't work with my theme =

Themes that follow the default WooCommerce implementation will usually work with this plugin. However, some themes use an unorthodox method to add their own lightbox/slider, which breaks the hooks this plugin needs.

= The plugin works but messes up the styling of the images =

You can try several options here.

1. Go to WooCommerce > SVI (Smart Variations Images) and activate or deactivate the option "Enable WooCommerce default product image"
2. Disable other plugins that change the Product image default behavior.
3. Read the Support Threads.


= How do I configure it to work? =

1. Assign your product Attributes and click "Save attributes"
2. Create the variations you need, and click "Publish" or "Save as draft"
3. Go to Product Gallery and upload/choose the images you are going to use
4. For each image assign the slugs to be used for the variation images swap
5. Publish you product

You can skip steps 1 and 2 if your product is already setup with Attributes and Variations.

== Screenshots ==

1. Add images to your Product Gallery
2. Choose the images to be used and select the "slug" of the variation in the "Variation Slug" field.
3. Hides all other images that don't match the variation, and show only the default color, if no default is chosen, the gallery is hidden.
4. On change the variation, images in the gallery also change to match the variation. The image in the gallery when click should show in the bigger image(above).
4. Lens Zoom in action (activate it in WooCommerce > SVI (Smart Variations Images)

== Changelog ==

= 3.2.40 =
* Fix show_variation issue triggering wrong event
* Fix if main image not present show correct images on click

= 3.2.39 =
* Improvement - force save svi slug data on save product
* Change fix a small issue caused by data-product_variations returning false
* Fallback for WP_ERROR fatals
* Fix SVI GLOBAL not loading correctly
* Tweak auto hide double gallery for some themes in mobile

= 3.2.38 =
* Fix error on show cart image, not showing correct image
* Fix Keep Thumbnails visible not showing original image on trigger
* Update License control system

= 3.2.37 =
* Fix missing option on slider thumbnail navigation not showing
* Fix Select Swap not triggering if Hidden Thumbnails is active

= 3.2.36 =
* Fix possible missing variations display on SVI Gallery due to enconding
* Fix Select Swap option hidden if thumbnails are hidden
* Fix Safari and iPhone slider image dimensions on window resize
* Added [product_page] shortcode support for 1 instance

= 3.2.35 =
* Fix throwing error on saving variations

= 3.2.34 =
* Fix Possible jQuery issue with .size() function
* Fix Vertical Slider thumbnails not hidden if option Hidden Thumbnails is active

= 3.2.33 =
* Fix possible issue with not loading correct variation images in some cases

= 3.2.32 =
* Prevent White page if no license detected.
* Fix admin saving variations better save
* Fix thumbnail click on Trigger match try to match
* Fix Notice on cart page about get_gallery_attachment_ids
* Improvement Thumbnail Opacity
* Added notice if license key is missing

= 3.2.31 =
* photoSwipe Update to 4.1.2
* Fix photoSwipe bad ratio on zoom
* Fix prettyPhoto not loading image in some instances
* Fix Warning: array_filter() expects parameter 1 to be array, null given

= 3.2.30 =
* Added SVI Global variation, Use this variation too assign global images (displayed in all variations).

= 3.2.29 =
* Disable zoom click on photoswipe
* Fix IE11/Safari easing issue
* Fix Typo on Options

= 3.2.28 =
* Fix Thumb Select issue with Safari
* Added new option Trigger Match. On user selects Images will be showed according to galleries created in Product, no grouping will occur.

= 3.2.27 =
* Added option for containing Lens Zoom in image or not
* Added autoslide stop/play on mouse over
* Fix images not being display if variations doesn't have images

= 3.2.26 =
* Added option for active thumbnails fade on click

= 3.2.25 =
* Added On variation swap if current images display as new combination don´t change images
* Added Auto slide to Slider
* Fix slider-active class not correctly placed

= 3.2.24 =
* Fix possible white page on product save

= 3.2.23 =
* Fix duplicate variation with same order of images not saving variation

= 3.2.22 =
* Fix possible wrong variations save

= 3.2.21 =
* Fix possible white page on product save
* Fix ligthbox not showing correct image

= 3.2.20 =
* Fix Keep thumbnails issue with image swapping

= 3.2.19 =
* Fix admin not adding new galleries
* Fix Magnifier options
* Added support for premium theme 
* Added alert if license not valid for site

= 3.2.18 =
* Improved SVI variations Gallery to prevent encoding issues
* Fix if photoSwipe thumbnails active and title active change title position to TOP

= 3.2.17 =
* Added advanced variation matching, more accurate more possibilities

= 3.2.16 =
* Fix WooCommerce 2.6 retro-compatibility
* Fix possible Warning for is string
* Added ImageLoaded Theme conflict option


= 3.2.15 =
* Added cyrillic support

= 3.2.14 =
* Fix slider on image swap possible break
* Added feature if Keep Thumbnails Active, change image to selected variation

= 3.2.13 =
* Added option for WooCommerce 3 Ligthbox
* Added options for old WooCommerce Ligthbox
* Added Easing to MagnifierLens so that lens runs smoother
* Fix adding image to variation remove image from "No variations assigned"
* Fix prettyPhoto Ligthbox duplicating image
* Fix possible conflict with other themes/plugins adding variation selects in other places
* Localized all JS files

= 3.2.12 =
* Fix JS breaking product page edit for variations with variations with special chars
* Fix Variations with special chars not swapping
* Fix slider main image not showing after image reset
* Fix call with Thumbnail Click Swap active of duplicating call

= 3.2.11 =
* Fix single item not showing ligthbox
* Fix jsonParse errors

= 3.2.10 =
* Minor cleanup

= 3.2.9 =
* Fix cart image variation not showing

= 3.2.8 =
* Fix admin image not removing from variation
* Fix single click not trigger
* Added Unicode compatibility

= 3.2.7 =
* Minor fix for warning messages

= 3.2.6 =
* Added multiple combo variation
* Added Use same image across different variations
* Added position availability to static thumbnails
* Fixed possible JavaScript issue with slider and transition plugins
* Fixed ReduxFramework Fatal Error if function exist
* Fixed white space when loading/swapping images
* Notice: Added notice when license cannot be activated

= 3.2.5 =
* Fixed Select Swap issue on Firefox
* Fixed thumbnails being hidden if no matches occurs
* Fixed no titles appearing on ligthbox
* Prevent conflict with ajax loading content product, if detected SVI will not run.

= 3.2.4 =
* Added: Fallback if no Main image get 1st Thumbnail image
* Improvement: Order thumbnails correctly First/last
* Fix: Duplicate Image where product is variable and with 1 image
* Fix: Duplicate thumbs with main image
* Fix: If no main image set in variations, notice error prevent images loading
* Fix: Hidden thumbnails not showing thumbnails in single product pages
* Cleanup

= 3.2.3 =
* Fix: Show only variations for select
* Fix: WPML translation on non variations

= 3.2.2 =
* Minor fix: If missing srcset skip to prevent images not loading
* Added ability to disable slider center

= 3.2.1 =
* Added extra code

= 3.2 =
* Full code rewrite
* WooCommerce 2.7 Compatible
* Improvement: Better theme integration
* Improvement: WPML Compatibility
* Improvement: Faster Image loading
* Fixed: Slider thumbnail navigation fix on more than 5 elements
* Removed: Included ReduxFramework plugin form SVI to require install for better compatibility
* Added: Pre-loader on page load
* Added: Slider Allow Navigation Color change
* Added: Better thumbnail positioning for slider, now elements are centered
* Added: Magnifier Lens Border color or Transparent
* Added: Bulk variation assign on product page
* Added: Allow same image to have different variations on different products
* Added: Thumbnails "Keep Thumbnails visible" for user that want thumbnails visible all the time
* Minor Fixes


= 3.1.2.1 =
* Minor JS fix to prevent duplicate image on slider pre-selected
* Fix validation url at rosendo.pt some site encoding curl requests
* Fix navigation arrows on vertical mode

= 3.1.2 =
* Minor cleanup

= 3.1.1 =
* Full code rewrite
* Better theme integration
* WPML Compatible
* Faster response
* New options

= 3.1 =
* Full code rewrite

= 3.0.7 =
* Removed extra filters conditions for theme compatibility

= 3.0.6 =
* Faster response
* Minor Lens fix
* Minor slider Fix

= 3.0.5 =
* Code cleanup

= 3.0.4 =
* Reverted images sizes

= 3.0.3 =
* Minor fix: Plugin requesting update after update (wrong version control)
* Added mobile conditions
* Lens fix - Disabled lens if mobile phones detected
* Slider Thumbnail Position, on mobile phones falls back to horizontal.
* Slider Force Mobile Thumbnail Position, force use of selected Thumbnail position on mobile phones.

= 3.0.2 =
* Added right thumbnail position for Slider
* Minor Lens fix
* Minor slider Fix

= 3.0.1 =
* Force SVI fixed responsive when in mobile
* Fix validation requests being made to frequently
* Fix permission access to SVI settings only allowing Admin users, now Shop managers also have access

= 3.0 =
* Major release
* Added new Back-end options panel now with reduxFramework
* Added pre-loader animation on Magnifier Lens
* Added tweaks for more theme support
* Fixed double Lens images

= 2.4.1 =
* Added more thumbnails display, now max 10.
* Added option to change thumbs immediately after select

= 2.4 =
* Added fallback option for variations with "any kind" of option
* Fixes lens issue fading outside image

= 2.3 =
* Fixed a major error causing resource limit due to many calls for license validation
* Added option for hide thumbnails until variation is selected

= 2.2.9 =
* Fixed bug when there are no images in the product gallery, slider would not load anything.
* Added capability to work with variation even if variations are set to "Any Variation"

= 2.2.8 =
* Fixed minor issue with Ligthbox thumbnails opening ligthbox.

= 2.2.7 =
* Fixed minor bug in type comparison not swapping select

= 2.2.6 =
* Feature added option to Swap Variation select on thumbnail click
* Feature added option to display chosen variation image in cart/checkout instead of default Product image
* Fix slider Vertical Thumbnails sometimes not loading properly
* Fix Ligthbox not opening properly in some themes
* Organized Select option for variation in product edit

= 2.2.5 =
* Fix compatibility with WooCommerce throwing "Sorry, this product is unavailable. Please choose a different combination."

= 2.2.4 =
* Fix correct columns display with last image

= 2.2.3 =
* Fix Lens for no conflict
* Make lens load original image
* Fixed notice error showing up in cart

= 2.2.2 =
* Fix automatic update

= 2.2.1 =
* Fix Admin Notice messages

= 2.2 =
* WPML Compatible
* Fix Notice messages
* Automatic Updates

= 2.1 =
* Fix issue with multiple variations being called with ajax not changing
* Added option to force template position
* Added Option to add custom CSS class (normally from theme)

= 2.0 =
* Major release
* Added Vertical Slider
* Added lensZoom extra options
* Better theme compatibility

= 1.2 =
* Fixed issue with some product not showing slides if product is variable and as no variation attributed to images