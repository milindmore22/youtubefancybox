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

$play_icon = '<span class="youtubefancybox-block__play" aria-hidden="true">'
	. '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64" focusable="false">'
	. '<circle cx="32" cy="32" r="32" fill="rgba(0, 0, 0, 0.6)"></circle>'
	. '<path d="M26 20l18 12-18 12z" fill="#fff"></path>'
	. '</svg>'
	. '</span>';

if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
	$light_box_id = wp_unique_id( 'youtubefancybox-block-lightbox-' );
	$player_tag   = 'vimeo' === $provider ? 'amp-vimeo' : 'amp-youtube';
	$amp_src      = '';

	if ( ! empty( $attributes['thumbnailId'] ) ) {
		$image_data = wp_get_attachment_image_src( (int) $attributes['thumbnailId'], 'full' );
		$amp_src    = $image_data[0] ?? '';
	} elseif ( ! empty( $attributes['thumbnailUrl'] ) ) {
		$amp_src = (string) $attributes['thumbnailUrl'];
	}

	if ( '' === $amp_src ) {
		$amp_src = VideoLightbox::get_default_thumbnail_src( $provider, $video_id );
	}

	if ( '' === $amp_src ) {
		return '';
	}

	$amp_alt = (string) $attributes['thumbnailAlt'];

	if ( '' === $amp_alt ) {
		$amp_alt = __( 'Video thumbnail', 'youtubefancybox' );
	}
	?>
	<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<amp-lightbox class="ytfancybox-lightbox alignfull" id="<?php echo esc_attr( $light_box_id ); ?>" layout="nodisplay">
			<div class="youtubefancybox-amp-lightbox" role="button" tabindex="0">
				<span role="button" tabindex="0" on="tap:<?php echo esc_attr( $light_box_id ); ?>.close" class="youtubefancybox-amp-lightbox-close">X</span>
				<<?php echo esc_html( $player_tag ); ?> width="<?php echo esc_attr( (string) $width ); ?>" height="<?php echo esc_attr( (string) $height ); ?>" layout="fill" data-videoid="<?php echo esc_attr( $video_id ); ?>" <?php echo $autoplay ? 'autoplay' : ''; ?>>
				</<?php echo esc_html( $player_tag ); ?>>
			</div>
		</amp-lightbox>
		<span class="youtubefancybox-block__trigger" role="button" tabindex="0" on="tap:<?php echo esc_attr( $light_box_id ); ?>">
			<amp-img src="<?php echo esc_url( $amp_src ); ?>" alt="<?php echo esc_attr( $amp_alt ); ?>" width="<?php echo esc_attr( (string) $width ); ?>" height="<?php echo esc_attr( (string) $height ); ?>" layout="intrinsic"></amp-img>
			<?php echo $play_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</span>
	</div>
	<?php
	return;
}

$embed_url = VideoLightbox::get_embed_url( $provider, $video_id, $autoplay );

/* translators: %s: Video provider name. */
$aria_label = sprintf( esc_html__( 'Play %s video', 'youtubefancybox' ), $provider );
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
