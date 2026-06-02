<?php
/**
 * Autoloader for PHP classes.
 *
 * Wraps the Composer autoloader to provide graceful failure if it is missing.
 *
 * @package YTubeFancy
 */

declare( strict_types = 1 );

namespace YTubeFancy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Autoloader
 */
final class Autoloader {

	/**
	 * Whether the autoloader has been loaded.
	 *
	 * @var bool
	 */
	protected static bool $is_loaded = false;

	/**
	 * Attempts to autoload the Composer dependencies.
	 *
	 * @return bool True if autoloader is available, false otherwise.
	 */
	public static function autoload(): bool {
		if ( defined( 'YTUBE_FANCY_AUTOLOAD' ) && false === YTUBE_FANCY_AUTOLOAD ) {
			return true;
		}

		if ( self::$is_loaded ) {
			return true;
		}

		$autoloader      = YTUBE_FANCY_DIR . 'vendor/autoload.php';
		self::$is_loaded = self::require_autoloader( $autoloader );

		return self::$is_loaded;
	}

	/**
	 * Attempts to load the autoloader file, if it exists.
	 *
	 * @param string $autoloader_file The path to the autoloader file.
	 * @return bool True if loaded successfully, false otherwise.
	 */
	protected static function require_autoloader( string $autoloader_file ): bool {
		if ( ! is_readable( $autoloader_file ) ) {
			self::missing_autoloader_notice();
			return false;
		}

		return (bool) require_once $autoloader_file;
	}

	/**
	 * Displays a notice if the autoloader is missing.
	 */
	protected static function missing_autoloader_notice(): void {
		$hooks = array(
			'admin_notices',
			'network_admin_notices',
		);

		foreach ( $hooks as $hook ) {
			add_action(
				$hook,
				static function (): void {
					$error_message = __( 'Video Lightbox for YouTube/Vimeo: The Composer autoloader was not found. If you installed the plugin from the GitHub source code, make sure to run `composer install`.', 'youtubefancybox' );

					if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
						error_log( esc_html( $error_message ) );
					}

					wp_admin_notice(
						$error_message,
						array(
							'type'    => 'error',
							'dismiss' => false,
						)
					);
				}
			);
		}
	}
}
