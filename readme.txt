=== Video Lightbox for YouTube/Vimeo ===
Contributors: milindmore22
Tags: youtube, vimeo, lightbox, popup-video, shortcode
Requires at least: 5.6
Requires PHP: 8.0
Tested up to: 6.6.2
Stable tag: 2.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed YouTube/Vimeo videos in a lightbox popup. Easily create thumbnails and customize playback settings. Supports both platforms and is compatible with WordPress.

== Description ==

Easy YouTube & Vimeo popups! Click thumbnails to watch videos in a sleek lightbox. Works with shortcodes in posts, pages & widgets.

The plugin now has support for the official AMP plugin.

[markdown syntax]: Eg:
    `&#91;youtube videoid="<youtube videoid goes here>" height="<height goes here>" width="<width goes here>"&#93;`
    `&#91;youtube url="<youtube url goes here>"&#93;`

in the backend you can generate shortcodes also you can set default height, width, and an option to play video automatically.

Now Supports Vimeo

[markdown syntax]: Eg:
    `&#91;vimeo videoid="<vimeo videoid goes here>" height="<height goes here>" width="<width goes here>"&#93;`
    `&#91;vimeo url="<vimeo url goes here>"&#93;`


== Installation ==

1. Upload `youtubefancybox` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place shortcode like`&#91;youtube videoid="<videoid goes here>"&#93;` in your page, post, text widget

== Frequently Asked Questions ==

= I have installed the plugin but nothing happens? =
You need to add [shortcode](http://codex.wordpress.org/Shortcode) to your page, post, or your text widget
= Does this plugin has backend =
Yes, It does it's in the plugin submenu named "YTubeFancyBox".
= I need to set default height and width =
You can set the default height and width on the admin side
= I don't want to play video after lightbox opens =
You can set the default autoplay option at the backend
= I need to set different height and width for each Youtube thumbnail =
You can set height and width for each image thumbnail in shorcode `&#91;youtube videoid="<videoid>" height="<height>" width="<width>"&#93;`
= Where can I get youtube video id =
You can find youtube video id at the backend by inserting youtube video URL
= Can I use youtube video URL instead of video id =
Yes, you can add `&#91;youtube url="<youtube video URL here>"&#93;`

== Screenshots ==

1. Backend of Plugin
2. add the shortcode on a page
3. Thumbnails generated from youtube video
4. Youtube Video in Lightbox

== Changelog ==

= 2.7.1 =
Version update

= 2.7.0 =
Fix : Fixed error where the vimeo data was not reetrieved.
Updated Tested tag to 6.6.2

= 2.6.2 =
Fix: short code Generator.
Updated Tested Tag to 5.8

= 2.6.1 =
Renamed plugin and updated logo to avoid copyright violations.

= 2.6 =
Added Official AMP Plugin Support.
Tested up to 5.6

= 2.5 =

Tested up to 5.4.2

= 2.4 =

Fixes warning.

= 2.3 =

Fixed issue with directly redirecting to video.
Added Extended version of colorboxjs

= 2.2 =

Added muted autoplay for Chrome and Safari.
https://developers.google.com/web/updates/2017/09/autoplay-policy-changes

= 2.1 =

Fixes Widget text minor issue.

= 2.0 =

Updated colorboxjs

Now supports Vimeo.

= 1.6 =

Updated colorboxjs

Updated for WordPress 4.0

Now supports SSL (HTTPS)

Improved look and feel

Updated text domain to be the same as a slug

Updated code to make plugin translation ready, the latest version of colorboxjs updated.

Fixed shortcode inside shortcode bug with return output

Updated for WordPress 4.1

Fixed iPhone iPad bugs

= 1.0 =

First release. Compatible with IE9,IE10, Chrome, Firefox


== Upgrade notice ==

Fix: short code Generator.