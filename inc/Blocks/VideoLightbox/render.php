<?php
/**
 * Server-side rendering for the Video Lightbox block.
 *
 * Outputs a thumbnail (custom placeholder or the provider's default) wrapped
 * in an anchor that the plugin's existing colorbox frontend script opens.
 *
 * @package YTubeFancy
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use YTubeFancy\Modules\Blocks\VideoLightbox;

$attributes = wp_parse_args(
	$attributes ?? [],
	[
		'videoUrl'     => '',
		'videoId'      => '',
		'provider'     => '',
		'thumbnailId'  => 0,
		'thumbnailUrl' => '',
		'thumbnailAlt' => '',
		'height'       => 350,
		'width'        => 400,
		'autoplay'     => false,
	]
);

$video_id = trim( (string) $attributes['videoId'] );

if ( '' === $video_id ) {
	return '';
}

$provider = in_array( $attributes['provider'], [ 'youtube', 'vimeo' ], true ) ? $attributes['provider'] : '';

if ( '' === $provider ) {
	return '';
}

$width    = max( 1, (int) $attributes['width'] );
$height   = max( 1, (int) $attributes['height'] );
$autoplay = ! empty( $attributes['autoplay'] );

$thumbnail = '';

if ( ! empty( $attributes['thumbnailId'] ) ) {
	$thumbnail = wp_get_attachment_image(
		(int) $attributes['thumbnailId'],
		'full',
		false,
		[
			'alt'      => (string) $attributes['thumbnailAlt'],
			'width'    => $width,
			'height'   => $height,
			'loading'  => 'lazy',
			'decoding' => 'async',
		]
	);
}

if ( '' === $thumbnail && ! empty( $attributes['thumbnailUrl'] ) ) {
	$thumbnail = sprintf(
		'<img src="%1$s" alt="%2$s" width="%3$d" height="%4$d" loading="lazy" decoding="async" />',
		esc_url( (string) $attributes['thumbnailUrl'] ),
		esc_attr( (string) $attributes['thumbnailAlt'] ),
		$width,
		$height
	);
}

if ( '' === $thumbnail ) {
	$thumbnail = VideoLightbox::get_default_thumbnail( $provider, $video_id, $width, $height );
}

if ( '' === $thumbnail ) {
	return '';
}

$embed_url = VideoLightbox::get_embed_url( $provider, $video_id, $autoplay );

/* translators: %s: Video provider name. */
$aria_label = sprintf( esc_html__( 'Play %s video', 'youtubefancybox' ), $provider );

$play_icon = '<span class="youtubefancybox-block__play" aria-hidden="true">'
	. '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64" focusable="false">'
	. '<circle cx="32" cy="32" r="32" fill="rgba(0, 0, 0, 0.6)"></circle>'
	. '<path d="M26 20l18 12-18 12z" fill="#fff"></path>'
	. '</svg>'
	. '</span>';
?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<a
		class="<?php echo esc_attr( $provider ); ?> youtubefancybox-block__trigger"
		href="<?php echo esc_url( $embed_url ); ?>"
		aria-label="<?php echo esc_attr( $aria_label ); ?>"
	>
		<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo $play_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a>
</div>
