<?php

declare( strict_types = 1 );

namespace YTubeFancy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use YTubeFancy\Contracts\Traits\Singleton;

final class Main {
	use Singleton;

	private const REGISTRABLE_CLASSES = array(
		Modules\Admin\Settings::class,
		Modules\Shortcode\Youtube::class,
		Modules\Shortcode\Vimeo::class,
		Modules\Core\Assets::class,
	);

	public static function instance(): self {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->setup();
		}

		return self::$instance;
	}

	private function setup(): void {
		$this->load();
	}

	private function load(): void {
		foreach ( self::REGISTRABLE_CLASSES as $class_name ) {
			$instance = new $class_name();
			$instance->register_hooks();
		}
	}
}
