=== YouTube FancyBox ===
Contributors: milindmore22
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6KJKMAUVXNHDU
Tags: youtubefancybox, youtube, fancybox, popupvideo, lightbox, lightbox youtube
Requires at least: 3.6
Tested up to: 4.1
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A YouTube FancyBox uses fancy-box(light-box) to show YouTube video in a popup box on click of thumbnail which is generated from YouTube video.

== Description ==

A Youtube Fancybox uses colorbox /lightbox (thanks to  Jack Moore(http://www.jacklmoore.com/colorbox/) ) to show YouTube video in a popup box on click of thumbnail which is generated from YouTube video.
you can use it with shortcode in page, post and text widget.

Eg:
`[youtube videoid="<youtube videoid goes here>" height="<height goes here>" width="<width goes here>" ]`
`[youtube url="<youtube url goes here>"]`

in the backend you can genrated shorcodes also you can set default height, width and a option to play video automatically or not

== Installation ==

1. Upload `youtubefancybox` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place shortcode like`[youtube videoid="<videoid goes here>"]` in your page, post , text widget 

== Frequently asked questions ==

= 1. I have installed plugin but nothing happen? =
=> You need to add [shortcode](http://codex.wordpress.org/Shortcode) to you page, post or your text widget
= 2.Does this plugin has backend =
=>Yes, It does it's in plugin submenu named as "YTubeFancyBox".
= 3.I need to set default height and width =
=>you can set default height and width at admin side
= 4.I don't want to play video after lightbox opens =
=>you can set default auto play option at backend
= 5.I need to set different height and width for each Youtube thumbnail =
=>you can set height and width for each image thumbnail in shorcode `[youtube videoid="<videoid>" height="<height>" width="<width>"]`
= 6.where can i get youtube video id =
=>You can find youtube video id at backend by inserting youtube video url
= 7.can i use youtube video url instead of videoid =
=>Yes, you can add `[youtube url="<youtube video url here>"]`

== Screenshots ==

1. Backend of Plugin
2. add shortcode in page
3. Thumbnails generated from youtube video
4. Youtube Video in Lightbox

== Changelog ==

**first release for 3.8.1**

compatible with IE9,IE10, Chrome, Firefox


**Second release for 3.9.1**

Updated colorboxjs


**third release for 4.0**

Updated for wordpress 4.0

Now supports ssl (https)

Improved look and feel


**fourth release for 4.1**

Updated for wordpress 4.1

fixed iphone ipad bugs

== Upgrade notice ==
Updated to latest jquery colorbox.js
support ssl
fixed iphone ipad bugs