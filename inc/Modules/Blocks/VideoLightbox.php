<?php
/**
 * Video Lightbox block module.
 *
 * @package YTubeFancy\Modules\Blocks
 */

declare( strict_types = 1 );

namespace YTubeFancy\Modules\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use YTubeFancy\Contracts\Interfaces\Registrable;

/**
 * Class VideoLightbox
 *
 * Registers the Video Lightbox for YouTube/Vimeo Gutenberg block and
 * provides shared helpers used by the block's render file.
 */
class VideoLightbox implements Registrable {

	/**
	 * Path to the block directory (contains block.json).
	 *
	 * @var string
	 */
	private const BLOCK_DIR = YTUBE_FANCY_DIR . 'inc/Blocks/VideoLightbox';

	/**
	 * Block name as declared in block.json.
	 *
	 * @var string
	 */
	private const BLOCK_NAME = 'youtubefancybox/video-lightbox';

	/**
	 * Register WordPress hooks.
	 */
	public function register_hooks(): void {
		add_action( 'init', [ $this, 'register_block' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'set_script_translations' ] );
	}

	/**
	 * Register the block from its metadata.
	 */
	public function register_block(): void {
		register_block_type_from_metadata(
			self::BLOCK_DIR,
			[
				'attributes' => self::attributes(),
			]
		);
	}

	/**
	 * Attach translations to the block's editor script.
	 */
	public function set_script_translations(): void {
		$handle = str_replace( '/', '-', self::BLOCK_NAME ) . '-editor-script';

		if ( ! wp_script_is( $handle, 'registered' ) ) {
			return;
		}

		wp_set_script_translations( $handle, 'youtubefancybox' );
	}

	/**
	 * Build the block attributes map.
	 *
	 * Height, width and autoplay defaults fall back to the plugin's global
	 * settings so new blocks pick up the configured site defaults.
	 *
	 * @return array<string, array<string, mixed>> Attributes map.
	 */
	private static function attributes(): array {
		return [
			'videoUrl'     => [
				'type'    => 'string',
				'default' => '',
			],
			'videoId'      => [
				'type'    => 'string',
				'default' => '',
			],
			'provider'     => [
				'type'    => 'string',
				'enum'    => [ '', 'youtube', 'vimeo' ],
				'default' => '',
			],
			'thumbnailId'  => [
				'type'    => 'number',
				'default' => 0,
			],
			'thumbnailUrl' => [
				'type'    => 'string',
				'default' => '',
			],
			'thumbnailAlt' => [
				'type'    => 'string',
				'default' => '',
			],
			'height'       => [
				'type'    => 'number',
				'default' => self::default_height(),
			],
			'width'        => [
				'type'    => 'number',
				'default' => self::default_width(),
			],
			'autoplay'     => [
				'type'    => 'boolean',
				'default' => self::default_autoplay(),
			],
		];
	}

	/**
	 * Build the embed URL for a video.
	 *
	 * @param string $provider  Video provider ('youtube' or 'vimeo').
	 * @param string $video_id  Video ID.
	 * @param bool   $autoplay  Whether to autoplay when the lightbox opens.
	 * @return string Embed URL.
	 */
	public static function get_embed_url( string $provider, string $video_id, bool $autoplay ): string {
		if ( 'vimeo' === $provider ) {
			return add_query_arg(
				[
					'autoplay' => $autoplay ? 1 : 0,
					'color'    => 'ffffff',
				],
				'https://player.vimeo.com/video/' . rawurlencode( $video_id )
			);
		}

		return add_query_arg(
			[
				'rel'      => 0,
				'autoplay' => $autoplay ? 1 : 0,
				'muted'    => $autoplay ? 1 : 0,
				'wmode'    => 'transparent',
			],
			'https://www.youtube-nocookie.com/embed/' . rawurlencode( $video_id )
		);
	}

	/**
	 * Get the default provider thumbnail URL.
	 *
	 * @param string $provider  Video provider ('youtube' or 'vimeo').
	 * @param string $video_id  Video ID.
	 * @return string Thumbnail URL, or an empty string when no thumbnail is available.
	 */
	public static function get_default_thumbnail_src( string $provider, string $video_id ): string {
		if ( 'vimeo' === $provider ) {
			return self::get_vimeo_thumbnail( $video_id );
		}

		return 'https://img.youtube.com/vi/' . rawurlencode( $video_id ) . '/hqdefault.jpg';
	}

	/**
	 * Render the default provider thumbnail as an `<img>` tag.
	 *
	 * @param string $provider  Video provider ('youtube' or 'vimeo').
	 * @param string $video_id  Video ID.
	 * @param int    $width     Image width.
	 * @param int    $height    Image height.
	 * @return string `<img>` tag, or an empty string when no thumbnail is available.
	 */
	public static function get_default_thumbnail( string $provider, string $video_id, int $width, int $height ): string {
		$thumbnail_url = self::get_default_thumbnail_src( $provider, $video_id );

		if ( '' === $thumbnail_url ) {
			return '';
		}

		$size_attrs = ( $width > 0 && $height > 0 ) ? sprintf( ' width="%1$d" height="%2$d"', $width, $height ) : '';

		return sprintf(
			'<img src="%1$s" alt="%2$s"%3$s loading="lazy" decoding="async" />',
			esc_url( $thumbnail_url ),
			esc_attr__( 'Video thumbnail', 'youtubefancybox' ),
			$size_attrs
		);
	}

	/**
	 * Get the default height, falling back to the global plugin option.
	 *
	 * @return int Default height.
	 */
	private static function default_height(): int {
		$option = (int) get_option( 'youtube_height' );

		return $option > 0 ? $option : 350;
	}

	/**
	 * Get the default width, falling back to the global plugin option.
	 *
	 * @return int Default width.
	 */
	private static function default_width(): int {
		$option = (int) get_option( 'youtube_width' );

		return $option > 0 ? $option : 400;
	}

	/**
	 * Get the default autoplay value, falling back to the global plugin option.
	 *
	 * @return bool Default autoplay.
	 */
	private static function default_autoplay(): bool {
		return 'yes' === get_option( 'autoplay' );
	}

	/**
	 * Fetch the default Vimeo thumbnail URL via the oEmbed API.
	 *
	 * Results are cached in a transient for one hour.
	 *
	 * @param string $video_id Vimeo video ID.
	 * @return string Thumbnail URL, or an empty string on failure.
	 */
	private static function get_vimeo_thumbnail( string $video_id ): string {
		$cache_key = 'ytubefancybox_vimeo_thumb_' . $video_id;
		$cached    = get_transient( $cache_key );

		if ( false !== $cached ) {
			return (string) $cached;
		}

		$oembed_url = add_query_arg(
			[ 'url' => 'https://vimeo.com/' . $video_id ],
			'https://vimeo.com/api/oembed.json'
		);

		$response = wp_remote_get( $oembed_url ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		$url  = $body['thumbnail_url'] ?? '';

		if ( '' !== $url ) {
			set_transient( $cache_key, (string) $url, HOUR_IN_SECONDS );
		}

		return (string) $url;
	}
}
