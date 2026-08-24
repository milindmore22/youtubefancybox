<?php
/**
 * Vimeo shortcode module.
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
 * Class Vimeo
 *
 * Registers the [vimeo] shortcode.
 */
class Vimeo implements Registrable {

	/**
	 * Register WordPress hooks.
	 */
	public function register_hooks(): void {
		add_shortcode( 'vimeo', [ $this, 'render_shortcode' ] );
	}

	/**
	 * Render the [vimeo] shortcode.
	 *
	 * @param array<string, string> $attr Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function render_shortcode( array $attr ): string {
		if ( empty( $attr['height'] ) ) {
			$attr['height'] = get_option( 'youtube_height', '350' );
		}

		if ( empty( $attr['width'] ) ) {
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
			'vimeo'
		);

		if ( empty( $attr['videoid'] ) && ! empty( $attr['url'] ) ) {
			$matches = [];
			if ( preg_match( '#(?:https?://)?(?:www\.|player\.)?vimeo\.com/(?:channels/(?:\w+/)?|groups/[^/]+/videos/|album/(?:\d+/)?video/|video/|)(\d+)#i', $attr['url'], $matches ) ) {
				$attr['videoid'] = $matches[1];
			}
		}

		$autoplay_option = get_option( 'autoplay' );
		$autoplay        = ( ! empty( $autoplay_option ) && 'yes' === $autoplay_option ) // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
			? 'autoplay=1&muted=0'
			: 'autoplay=0&muted=0';

		if ( empty( $attr['videoid'] ) ) {
			return '<br /><span style="clear:both;color:red">' . esc_html__( 'Please Enter Vimeo ID or Vimeo URL as [vimeo videoid="XXXXX"]', 'youtubefancybox' ) . '</span>';
		}

		$protocol        = is_ssl() ? 'https' : 'http';
		$embed_url       = $protocol . '://player.vimeo.com/video/' . $attr['videoid'] . '?' . $autoplay . '&color=ffffff';
		$embed_image_url = $protocol . '://vimeo.com/api/v2/video/' . $attr['videoid'] . '.json';

		$thumbnail_url = wp_cache_get( 'vimeo_thumnail_' . $attr['videoid'], 'ytubefancybox' );

		if ( false === $thumbnail_url ) {
			$response = wp_remote_get( $embed_image_url ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get

			if ( is_wp_error( $response ) ) {
				return '<br /><span style="clear:both;color:red">' . $response->get_error_message() . '</span>';
			}

			$response_body = wp_remote_retrieve_body( $response );
			$obj           = json_decode( $response_body );
			$thumbnail_url = $obj[0]->thumbnail_large ?? '';

			wp_cache_set( 'vimeo_thumnail_' . $attr['videoid'], $thumbnail_url, 'ytubefancybox', 1 * HOUR_IN_SECONDS );
		}

		ob_start();

		if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
			$light_box_id = wp_unique_id( 'youtubefancybox-vimeo-lightbox-' );
			?>
			<amp-lightbox class="ytfancybox-lightbox alignfull" id="<?php echo esc_attr( $light_box_id ); ?>" layout="nodisplay">
				<div class="youtubefancybox-amp-lightbox" role="button" tabindex="0">
					<span role="button" tabindex="0" on="tap:<?php echo esc_attr( $light_box_id ); ?>.close" class="youtubefancybox-amp-lightbox-close">X</span>
					<amp-vimeo width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" layout="fill" data-videoid="<?php echo esc_attr( $attr['videoid'] ); ?>" <?php echo ( ! empty( $autoplay_option ) && 'yes' === $autoplay_option ) ? 'autoplay' : ''; // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses ?>>
					</amp-vimeo>
				</div>
			</amp-lightbox>
			<amp-img class="aligncenter" on="tap:<?php echo esc_attr( $light_box_id ); ?>" src="<?php echo esc_url( $thumbnail_url ); ?>" width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" layout="intrinsic">
			</amp-img>
			<?php
			return (string) ob_get_clean();
		}
		?>
		<div class="youtubefancybox-lightbox-container aligncenter">
			<a class="vimeo" href="<?php echo esc_url( $embed_url ); ?>">
				<img src="<?php echo esc_url( $thumbnail_url ); ?>" width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>"/>
			</a>
		</div>
		<?php
		return (string) ob_get_clean();
	}
}
