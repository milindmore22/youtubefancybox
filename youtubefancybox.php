<?php
/**
 * Plugin Name: Video Lightbox for YouTube/Vimeo
 * Plugin URI: https://wordpress.org/plugins/youtubefancybox/
 * Description: Display thumbnail of Youtube and Vimeo videos and on clicking on thumbnail it will open in popupbox and play video.
 * Author: Milind More
 * Author URI: https://milindmore.wordpress.com/
 * Version: 3.0.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.7
 * Tested up to: 7.1
 * Text Domain: youtubefancybox
 * Domain Path: /languages/
 * Requires PHP: 8.1
 *
 * @author milind
 * @package ytubefancybox
 */

namespace YTubeFancy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin version.
 */
define( 'YTUBE_FANCY_VERSION', '3.0.0' );

/**
 * Absolute path to the plugin directory.
 */
define( 'YTUBE_FANCY_DIR', plugin_dir_path( __FILE__ ) );

/**
 * URL to the plugin directory.
 */
define( 'YTUBE_FANCY_URL', plugin_dir_url( __FILE__ ) );

// Attempt to load the Composer autoloader.
require_once __DIR__ . '/inc/Autoloader.php';
if ( ! Autoloader::autoload() ) {
	return;
}

// Bootstrap the plugin on plugins_loaded.
if ( class_exists( 'YTubeFancy\Main' ) ) {
	add_action( 'plugins_loaded', '\YTubeFancy\load_plugin' );
}

/**
 * Load plugin functionality.
 */
function load_plugin(): void {
	Main::instance();
}
