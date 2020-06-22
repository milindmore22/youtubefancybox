=== YouTube FancyBox ===
Contributors: milindmore22
Tags: youtubefancybox, youtube, fancybox, popupvideo, lightbox, lightbox youtube
Requires at least: 3.6
Tested up to: 5.4.2
Stable tag: 2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A YouTube FancyBox uses fancy-box(light-box) to show YouTube video in a popup box on click of thumbnail which is generated from YouTube video.

== Description ==

A Youtube Fancybox uses colorbox thanks to [Jack Moore](http://www.jacklmoore.com/colorbox/) to show YouTube and Vimeo video in a popup box on click of thumbnail which is generated from Youtube and Vimeo video.
you can use it with shortcode in page, post and text widget.

[markdown syntax]: Eg:
    `&#91;youtube videoid="<youtube videoid goes here>" height="<height goes here>" width="<width goes here>"&#93;`
    `&#91;youtube url="<youtube url goes here>"&#93;`

in the backend you can generate shortcodes also you can set default height, width and a option to play video automatically.

Now Supports Vimeo

[markdown syntax]: Eg:
    `&#91;vimeo videoid="<vimeo videoid goes here>" height="<height goes here>" width="<width goes here>"&#93;`
    `&#91;vimeo url="<vimeo url goes here>"&#93;`


== Installation ==

1. Upload `youtubefancybox` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place shortcode like`[youtube videoid="<videoid goes here>"]` in your page, post , text widget

== Frequently Asked Questions ==

= I have installed plugin but nothing happen? =
You need to add [shortcode](http://codex.wordpress.org/Shortcode) to you page, post or your text widget
= Does this plugin has backend =
Yes, It does it's in plugin submenu named as "YTubeFancyBox".
= I need to set default height and width =
You can set default height and width at admin side
= I don't want to play video after lightbox opens =
You can set default auto play option at backend
= I need to set different height and width for each Youtube thumbnail =
You can set height and width for each image thumbnail in shorcode `[youtube videoid="<videoid>" height="<height>" width="<width>"]`
= Where can I get youtube video id =
You can find youtube video id at backend by inserting youtube video url
= Can I use youtube video url instead of videoid =
Yes, you can add `[youtube url="<youtube video url here>"]`

== Screenshots ==

1. Backend of Plugin
2. add shortcode in page
3. Thumbnails generated from youtube video
4. Youtube Video in Lightbox

== Changelog ==

= 2.5 =

Tested upto 5.4.2

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

Updated for wordpress 4.0

Now supports ssl (https)

Improved look and feel

Updated text domain to be same as slug

Updated code to make plugin translation ready, latest version of fancybox js updated

Fixed shorcode inside shortcode bug with return output

Updated for wordpress 4.1

Fixed iphone ipad bugs

= 1.0 =

First release. Compatible with IE9,IE10, Chrome, Firefox


== Upgrade notice ==

Tested upto 5.4.2
