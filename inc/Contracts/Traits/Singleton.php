<?php
/**
 * Singleton trait.
 *
 * @package YTubeFancy\Contracts\Traits
 */

declare( strict_types = 1 );

namespace YTubeFancy\Contracts\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Singleton trait.
 *
 * Provides a singleton pattern implementation for classes that should
 * only have one instance throughout the request lifecycle.
 */
trait Singleton {

	/**
	 * Instance of the class.
	 *
	 * @var ?static
	 */
	protected static $instance;

	/**
	 * Prevent direct instantiation.
	 */
	protected function __construct() {}

	/**
	 * Get the singleton instance.
	 *
	 * @return static
	 */
	final public static function instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Prevent cloning.
	 */
	final public function __clone() {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf(
				/* translators: %s: Class name. */
				esc_html__( 'The %s class should not be cloned.', 'youtubefancybox' ),
				esc_html( static::class ),
			),
			'3.0.0'
		);
	}

	/**
	 * Prevent deserialization.
	 */
	final public function __wakeup() {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf(
				/* translators: %s: Class name. */
				esc_html__( 'De-serializing instances of %s is not allowed.', 'youtubefancybox' ),
				esc_html( static::class ),
			),
			'3.0.0'
		);
	}
}
