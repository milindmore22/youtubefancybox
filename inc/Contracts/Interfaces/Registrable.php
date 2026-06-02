<?php

declare( strict_types = 1 );

namespace YTubeFancy\Contracts\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Registrable {
	public function register_hooks(): void;
}
