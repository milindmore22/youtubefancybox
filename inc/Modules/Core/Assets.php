<?php

declare( strict_types = 1 );

namespace YTubeFancy\Modules\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use YTubeFancy\Contracts\Interfaces\Registrable;

class Assets implements Registrable {

	public function register_hooks(): void {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_filter( 'widget_text', 'shortcode_unautop' );
		add_filter( 'widget_text', 'do_shortcode' );
	}

	public function enqueue_admin_assets( string $hook ): void {
		$admin_screens = array(
			'video-lightbox_page_vimeo',
			'video-lightbox_page_ytube',
			'toplevel_page_ytubefancybox',
		);

		if ( ! in_array( $hook, $admin_screens, true ) ) {
			return;
		}

		wp_enqueue_script( 'jquery' );

		$asset = $this->get_asset( 'build/js/fancybox_admin.asset.php' );

		wp_register_script(
			'fancybox_admin',
			YTUBE_FANCY_URL . 'build/js/fancybox_admin.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_localize_script(
			'fancybox_admin',
			'fancybox_admin_obj',
			array(
				'youtube_alert' => esc_html__( 'Youtube URL you entered might be wrong, Please enter correct URL!', 'youtubefancybox' ),
				'viemo_alert'   => esc_html__( 'Viemo URL you entered might be wrong, Please enter correct URL!', 'youtubefancybox' ),
			)
		);

		wp_enqueue_script( 'fancybox_admin' );
	}

	public function enqueue_frontend_assets(): void {
		if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
			$asset = $this->get_asset( 'build/css/youtubefancybox-amp.asset.php' );

			wp_enqueue_style(
				'youtubefancybox-amp',
				YTUBE_FANCY_URL . 'build/css/youtubefancybox-amp.css',
				$asset['dependencies'],
				$asset['version']
			);
			return;
		}

		$colorbox_css_asset = $this->get_asset( 'build/css/colorbox.asset.php' );

		wp_enqueue_style(
			'colorbox-css',
			YTUBE_FANCY_URL . 'build/css/colorbox.css',
			$colorbox_css_asset['dependencies'],
			$colorbox_css_asset['version']
		);

		wp_enqueue_script( 'jquery' );

		$colorbox_js_asset = $this->get_asset( 'build/js/jquery.colorbox.asset.php' );

		wp_enqueue_script(
			'colorbox-js',
			YTUBE_FANCY_URL . 'build/js/jquery.colorbox.js',
			$colorbox_js_asset['dependencies'],
			$colorbox_js_asset['version'],
			true
		);

		$caller_asset = $this->get_asset( 'build/js/caller.asset.php' );

		wp_enqueue_script(
			'colorbox-caller',
			YTUBE_FANCY_URL . 'build/js/caller.js',
			$caller_asset['dependencies'],
			$caller_asset['version'],
			true
		);
	}

	private function get_asset( string $relative_path ): array {
		$path = YTUBE_FANCY_DIR . $relative_path;

		if ( file_exists( $path ) ) {
			return require $path;
		}

		return array(
			'dependencies' => array(),
			'version'      => YTUBE_FANCY_VERSION,
		);
	}
}
