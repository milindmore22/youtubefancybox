=== Video Lightbox for YouTube/Vimeo ===
Contributors: milindmore22
Tags: youtube, vimeo, lightbox, popup-video, shortcode
Requires at least: 6.7
Requires PHP: 8.1
Tested up to: 7.1
Stable tag: 3.0.0
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

### Shortcode Examples

**YouTube:**
`[youtube url="https://www.youtube.com/watch?v=dQw4w9WgXcQ"]`
`[youtube videoid="dQw4w9WgXcQ" width="400" height="350"]`

**Vimeo:**
`[vimeo url="https://vimeo.com/76979871"]`
`[vimeo videoid="76979871" width="400" height="350"]`

### Block Editor

1. Add the **Video Lightbox for YouTube/Vimeo** block.
2. Select **Edit video URL** and enter a public YouTube or Vimeo URL.
3. Optionally choose a custom placeholder image and adjust display settings in the block sidebar.

== Installation ==

1. Upload the `youtubefancybox` folder to your `/wp-content/plugins/` directory (or search for **Video Lightbox for YouTube/Vimeo** via **Plugins > Add New**).
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Add the Video Lightbox block, or insert a `[youtube]` or `[vimeo]` shortcode into any post, page, or widget.

== Frequently Asked Questions ==

= How do I use the plugin after activating it? =
Insert a shortcode into your content. For example:
`[youtube url="https://www.youtube.com/watch?v=VIDEO_ID"]` or `[vimeo url="https://vimeo.com/VIDEO_ID"]`.

= Can I override the default dimensions on a specific shortcode? =
Yes. Pass `height` and `width` attributes directly in the shortcode:
`[youtube videoid="VIDEO_ID" width="600" height="400"]`

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
2. Video Lightbox shortcode generator under Media.
3. Video Lightbox popup playing a video on the frontend.

== Changelog ==

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
