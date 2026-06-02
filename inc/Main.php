<?php
/**
 * Main plugin bootstrap.
 *
 * @package YTubeFancy
 */

declare( strict_types = 1 );

namespace YTubeFancy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use YTubeFancy\Contracts\Traits\Singleton;

/**
 * Main plugin class.
 *
 * Bootstraps all plugin modules by iterating over registrable classes
 * and invoking their hook registration methods.
 */
final class Main {
	use Singleton;

	/**
	 * List of registrable module classes.
	 *
	 * Each class must implement the Registrable interface.
	 *
	 * @var class-string<\YTubeFancy\Contracts\Interfaces\Registrable>[]
	 */
	private const REGISTRABLE_CLASSES = array(
		Modules\Admin\Settings::class,
		Modules\Shortcode\Youtube::class,
		Modules\Shortcode\Vimeo::class,
		Modules\Core\Assets::class,
	);

	/**
	 * {@inheritDoc}
	 */
	public static function instance(): self {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->setup();
		}

		return self::$instance;
	}

	/**
	 * Setup the plugin.
	 */
	private function setup(): void {
		$this->load();
	}

	/**
	 * Load registrable modules and register their hooks.
	 */
	private function load(): void {
		foreach ( self::REGISTRABLE_CLASSES as $class_name ) {
			$instance = new $class_name();
			$instance->register_hooks();
		}
	}
}
