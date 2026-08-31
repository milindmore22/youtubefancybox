=== Video Lightbox for YouTube/Vimeo ===
Contributors: milindmore22
Tags: youtube, vimeo, lightbox, popup-video, shortcode
Requires at least: 6.7
Requires PHP: 8.1
Tested up to: 7.1
Stable tag: 3.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display YouTube and Vimeo thumbnails that open in a sleek lightbox popup with custom sizes, autoplay, and official AMP support.

== Description ==

**Video Lightbox for YouTube/Vimeo** lets you display responsive YouTube and Vimeo video thumbnails on your WordPress posts, pages, and widgets. When a visitor clicks the thumbnail, the video opens in an elegant, responsive Colorbox popup overlay.

### Features
* **YouTube & Vimeo Support**: Embed any public YouTube video/shorts or Vimeo video with a clean shortcode.
* **Responsive Lightbox**: Seamless video overlay powered by Colorbox.
* **Modern Admin Interface**: Configure default thumbnail width, height, and autoplay behavior with a streamlined admin settings panel.
* **Built-in Shortcode Generator**: Quickly generate customized `[youtube]` and `[vimeo]` shortcodes directly from your WordPress dashboard.
* **Official AMP Support**: Automatically renders valid AMP lightbox components (`amp-lightbox`, `amp-youtube`, `amp-vimeo`) on AMP endpoints.
* **High Performance**: Assets are loaded only on pages where required.

### Shortcode Examples

**YouTube:**
`[youtube url="https://www.youtube.com/watch?v=dQw4w9WgXcQ"]`
`[youtube videoid="dQw4w9WgXcQ" width="400" height="350"]`

**Vimeo:**
`[vimeo url="https://vimeo.com/76979871"]`
`[vimeo videoid="76979871" width="400" height="350"]`

== Installation ==

1. Upload the `youtubefancybox` folder to your `/wp-content/plugins/` directory (or search for **Video Lightbox for YouTube/Vimeo** via **Plugins > Add New**).
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Configure your default dimensions and autoplay settings under **Video Lightbox** in your admin menu.
4. Insert `[youtube]` or `[vimeo]` shortcodes into any post, page, or widget.

== Frequently Asked Questions ==

= How do I use the plugin after activating it? =
Insert a shortcode into your content. For example:
`[youtube url="https://www.youtube.com/watch?v=VIDEO_ID"]` or `[vimeo url="https://vimeo.com/VIDEO_ID"]`.

= How do I configure default dimensions or autoplay? =
Go to **Video Lightbox** in the WordPress admin menu to configure your default height, width, and autoplay preference.

= Can I override the default dimensions on a specific shortcode? =
Yes. Pass `height` and `width` attributes directly in the shortcode:
`[youtube videoid="VIDEO_ID" width="600" height="400"]`

= Does this plugin support official AMP? =
Yes. When viewing content on an AMP-enabled request, the plugin automatically outputs native `<amp-lightbox>`, `<amp-youtube>`, and `<amp-vimeo>` markup without loading jQuery.

= How do I generate shortcodes quickly? =
Navigate to **Video Lightbox > Youtube** or **Video Lightbox > Vimeo** in your WordPress dashboard, enter your video URL and dimensions, and click **Generate Shortcode**.

== Screenshots ==

1. Default Settings page for video lightbox dimensions and autoplay.
2. YouTube Shortcode Generator.
3. Vimeo Shortcode Generator.
4. Video Lightbox popup playing a video on the frontend.

== Changelog ==

= 3.0.0 =
* Fix text domain references and update plugin metadata
* Updated block structure and configuration, dependencies and refactored code.
* Added Block for Video lightbox with support for YouTube and Vimeo.

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
