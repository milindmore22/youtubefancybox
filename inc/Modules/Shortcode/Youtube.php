<?php
/**
 * YouTube shortcode module.
 *
 * @package YTubeFancy\Modules\Shortcode
 */

declare( strict_types = 1 );

namespace YTubeFancy\Modules\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use YTubeFancy\Contracts\Interfaces\Registrable;

/**
 * Class Youtube
 *
 * Registers the [youtube] shortcode.
 */
class Youtube implements Registrable {

	/**
	 * Register WordPress hooks.
	 */
	public function register_hooks(): void {
		add_shortcode( 'youtube', [ $this, 'render_shortcode' ] );
	}

	/**
	 * Render the [youtube] shortcode.
	 *
	 * @param array<string, string> $attr Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function render_shortcode( array $attr ): string {
		if ( ! isset( $attr['height'] ) ) {
			$attr['height'] = get_option( 'youtube_height', '350' );
		}

		if ( ! isset( $attr['width'] ) ) {
			$attr['width'] = get_option( 'youtube_width', '400' );
		}

		$attr = shortcode_atts(
			[
				'url'     => '',
				'videoid' => '',
				'height'  => '350',
				'width'   => '400',
			],
			$attr,
			'youtube'
		);

		if ( empty( $attr['videoid'] ) && ! empty( $attr['url'] ) ) {
			$matches = [];
			preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $attr['url'], $matches );
			$attr['videoid'] = $matches[0] ?? '';
		}

		$autoplay_option = get_option( 'autoplay' );
		$autoplay        = ( ! empty( $autoplay_option ) && 'yes' === $autoplay_option ) // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
			? 'autoplay=1&muted=1'
			: 'autoplay=0&muted=0';

		if ( empty( $attr['videoid'] ) ) {
			return '<br /><span style="clear:both;color:red">' . esc_html__( 'Please Enter Youtube ID or Youtube URL as [youtube videoid="XXXXX"]', 'youtubefancybox' ) . '</span>';
		}

		$protocol    = is_ssl() ? 'https' : 'http';
		$video_id    = rawurlencode( $attr['videoid'] );
		$embed_url   = $protocol . '://www.youtube.com/embed/' . $video_id . '?rel=0&' . $autoplay . '&wmode=transparent';
		$embed_image = $protocol . '://img.youtube.com/vi/' . $video_id . '/0.jpg';

		ob_start();

		if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
			$light_box_id = wp_unique_id( 'youtubefancybox-youtube-lightbox-' );
			?>
			<amp-lightbox class="ytfancybox-lightbox alignfull" id="<?php echo esc_attr( $light_box_id ); ?>" layout="nodisplay">
				<div class="youtubefancybox-amp-lightbox" role="button" tabindex="0">
					<span role="button" tabindex="0" on="tap:<?php echo esc_attr( $light_box_id ); ?>.close" class="youtubefancybox-amp-lightbox-close">X</span>
					<amp-youtube width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" layout="fill" data-videoid="<?php echo esc_attr( $attr['videoid'] ); ?>" <?php echo ( ! empty( $autoplay_option ) && 'yes' === $autoplay_option ) ? 'autoplay' : ''; // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses ?>>
					</amp-youtube>
				</div>
			</amp-lightbox>
			<amp-img class="aligncenter" on="tap:<?php echo esc_attr( $light_box_id ); ?>" src="<?php echo esc_url( $embed_image ); ?>" width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" layout="intrinsic">
			</amp-img>
			<?php
			return (string) ob_get_clean();
		}
		?>
		<div class="youtubefancybox-lightbox-container aligncenter">
			<a class="youtube" href="<?php echo esc_url( $embed_url ); ?>">
				<img src="<?php echo esc_url( $embed_image ); ?>" width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" />
			</a>
		</div>
		<?php
		return (string) ob_get_clean();
	}
}
