=== YouTube FancyBox ===
Contributors: milindmore22
Tags: youtubefancybox, youtube, fancy box, popup video, lightbox, lightbox youtube, amp
Requires at least: 3.6
Requires PHP: 5.6
Tested up to: 5.6
Stable tag: 2.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A YouTube/Vimeo FancyBox uses a fancy-box(light-box) to show YouTube/Vimeo video in a popup box on click of thumbnail which is generated from YouTube/Vimeo video.

== Description ==

A Youtube Fancybox uses colorbox thanks to [Jack Moore](http://www.jacklmoore.com/colorbox/) to show YouTube and Vimeo video in a popup box on click of thumbnail which is generated from Youtube and Vimeo video.
you can use it with a shortcode in the page, post, and text widget.

The plugin now has support for the official AMP plugin.

[markdown syntax]: Eg:
    `[youtube videoid="<youtube videoid goes here>" height="<height goes here>" width="<width goes here>"]`
    `[youtube url="<youtube url goes here>"]`

in the backend you can generate shortcodes also you can set default height, width, and an option to play video automatically.

Now Supports Vimeo

[markdown syntax]: Eg:
    `[vimeo videoid="<vimeo videoid goes here>" height="<height goes here>" width="<width goes here>"]`
    `[vimeo url="<vimeo url goes here>"]`


== Installation ==

1. Upload `youtubefancybox` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place shortcode like`[youtube videoid="<videoid goes here>"]` in your page, post, text widget

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
You can set height and width for each image thumbnail in shorcode `[youtube videoid="<videoid>" height="<height>" width="<width>"]`
= Where can I get youtube video id =
You can find youtube video id at the backend by inserting youtube video URL
= Can I use youtube video URL instead of video id =
Yes, you can add `[youtube url="<youtube video URL here>"]`

== Screenshots ==

1. Backend of Plugin
2. add the shortcode on a page
3. Thumbnails generated from youtube video
4. Youtube Video in Lightbox

== Changelog ==

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

Added Official AMP Plugin Support.