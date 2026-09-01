=== Video Lightbox for YouTube/Vimeo ===
Contributors: milindmore22
Tags: youtube, vimeo, lightbox, popup-video, shortcode
Requires at least: 6.7
Requires PHP: 8.1
Tested up to: 7.1
Stable tag: 3.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display YouTube and Vimeo videos in a responsive lightbox using block editor controls or shortcodes, with official AMP support.

== Description ==

**Video Lightbox for YouTube/Vimeo** lets you display responsive YouTube and Vimeo video thumbnails on WordPress posts, pages, and widgets. When a visitor clicks a thumbnail, the video opens in a responsive Colorbox popup.

### Features
* **Block editor support**: Add a Video Lightbox block, paste a YouTube or Vimeo URL, and choose a custom placeholder image.
* **YouTube & Vimeo support**: Embed public YouTube videos, Shorts, and Vimeo videos with a block or shortcode.
* **Responsive lightbox**: Opens video playback in a responsive Colorbox overlay.
* **Block styles**: Choose Default, Dark, or Cinema presentation styles in the block editor.
* **Display controls**: Set thumbnail width, height, and autoplay for each block.
* **Shortcode generator**: Generate a validated YouTube or Vimeo shortcode from **Media > Video Lightbox**. The provider is detected automatically.
* **AMP support**: Renders `amp-lightbox`, `amp-youtube`, and `amp-vimeo` components on AMP requests.

### Block Editor

1. Add the **Video Lightbox for YouTube/Vimeo** block.
2. Select **Edit video URL** and enter a public YouTube or Vimeo URL.
3. Optionally choose a custom placeholder image and adjust display settings in the block sidebar.

== Installation ==

1. Upload the `youtubefancybox` folder to your `/wp-content/plugins/` directory (or search for **Video Lightbox for YouTube/Vimeo** via **Plugins > Add New**).
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Add the Video Lightbox block, or generate a YouTube or Vimeo shortcode from **Media > Video Lightbox** and insert it into any post, page, or widget.

== Frequently Asked Questions ==

= How do I use the plugin after activating it? =
Add the Video Lightbox block, or generate a YouTube or Vimeo shortcode from **Media > Video Lightbox** and insert it into any post, page, or widget.

= Can I override the default dimensions on a specific shortcode? =
Yes. You can override default dimensions using the `height` and `width` attributes in the shortcode, or use the block editor to set custom dimensions.

= Does this plugin support official AMP? =
Yes. When viewing content on an AMP-enabled request, the plugin automatically outputs native `<amp-lightbox>`, `<amp-youtube>`, and `<amp-vimeo>` markup without loading jQuery.

= How do I generate shortcodes quickly? =
Navigate to **Media > Video Lightbox**, enter a YouTube or Vimeo video URL or ID and dimensions, then click **Generate shortcode**. The plugin validates the input and detects the provider automatically.

= Can I use the block editor? =
Yes. Add the **Video Lightbox for YouTube/Vimeo** block, then enter a public YouTube or Vimeo URL. You can choose a custom placeholder image, set dimensions, enable autoplay, and select a block style.

== Source Code ==

The source code is available on <a href="https://github.com/milindmore22/youtubefancybox">GitHub</a>.

== Screenshots ==

1. Video Lightbox block in the editor.
2. Video Lightbox popup for entering a YouTube or Vimeo URL.
3. Video Lightbox block settings in the sidebar.
4. Video Lightbox shortcode generator under Media.
5. Video Lightbox popup playing a video on the frontend.

== Changelog ==

= 3.0.2 = 
* Updated readme.txt to remove shortcode references and highlight block editor usage.

= 3.0.1 =
* Bump version to 3.0.1 for WordPress.org plugin repository.

= 3.0.0 =
* Added the Video Lightbox block for YouTube and Vimeo.
* Added custom placeholder images, responsive dimensions, autoplay, and Default, Dark, and Cinema block styles.
* Added a combined shortcode generator under Media with automatic provider detection and URL or ID validation.
* Updated plugin metadata, dependencies, and code structure.

= 2.7.1 =
* Modernized admin settings and shortcode generator interfaces.
* Optimized JavaScript and modularized admin stylesheet loading.
* Updated compatibility for modern WordPress and PHP standards.

= 2.7.0 =
* Fixed Vimeo data retrieval issue.
* Updated compatibility tested tag.

= 2.6.2 =
* Fixed shortcode generator URL parsing.

= 2.6.1 =
* Renamed plugin and updated assets for WordPress.org compliance.

= 2.6.0 =
* Added official AMP plugin support with native AMP lightbox components.

= 2.0.0 =
* Added support for Vimeo videos and shortcode generation.
* Updated Colorbox script.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 3.0.0 =
* Added the Video Lightbox block for YouTube and Vimeo.
