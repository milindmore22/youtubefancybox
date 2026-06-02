<?php

declare( strict_types = 1 );

namespace YTubeFancy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Autoloader {
	protected static bool $is_loaded = false;

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

	protected static function require_autoloader( string $autoloader_file ): bool {
		if ( ! is_readable( $autoloader_file ) ) {
			self::missing_autoloader_notice();
			return false;
		}

		return (bool) require_once $autoloader_file;
	}

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
