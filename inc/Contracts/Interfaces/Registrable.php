<?php
/**
 * Interface for registrable classes.
 *
 * Registrable classes are those that register hooks (actions/filters)
 * with WordPress.
 *
 * @package YTubeFancy\Contracts\Interfaces
 */

declare( strict_types = 1 );

namespace YTubeFancy\Contracts\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface Registrable
 *
 * Any class that registers WordPress hooks should implement this interface.
 */
interface Registrable {

	/**
	 * Register WordPress hooks.
	 *
	 * WordPress actions/filters should be included here.
	 */
	public function register_hooks(): void;
}
