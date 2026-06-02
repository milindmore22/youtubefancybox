<?php

declare( strict_types = 1 );

namespace YTubeFancy\Contracts\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Singleton {
	protected static $instance;

	protected function __construct() {}

	final public static function instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	final public function __clone() {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf(
				esc_html__( 'The %s class should not be cloned.', 'youtubefancybox' ),
				esc_html( static::class ),
			),
			'2.7.1'
		);
	}

	final public function __wakeup() {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf(
				esc_html__( 'De-serializing instances of %s is not allowed.', 'youtubefancybox' ),
				esc_html( static::class ),
			),
			'2.7.1'
		);
	}
}
