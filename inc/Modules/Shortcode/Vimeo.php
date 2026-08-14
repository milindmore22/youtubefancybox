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
 * Registers the [vimeo] shortcode and the admin submenu page
 * for generating Vimeo shortcodes.
 */
class Vimeo implements Registrable {

	/**
	 * Register WordPress hooks.
	 */
	public function register_hooks(): void {
		if ( is_admin() ) {
			add_action( 'admin_menu', [ $this, 'add_submenu_page' ] );
		}

		add_shortcode( 'vimeo', [ $this, 'render_shortcode' ] );
	}

	/**
	 * Add the Vimeo submenu page.
	 */
	public function add_submenu_page(): void {
		add_submenu_page(
			'ytubefancybox',
			'Vimeo FancyBox Options',
			'Vimeo',
			'manage_options',
			'vimeo',
			[ $this, 'render_options_page' ]
		);
	}

	/**
	 * Render the admin options page for generating Vimeo shortcodes.
	 */
	public function render_options_page(): void {
		?>
		<div class="wrap ytubefancybox-settings">
			<h1><?php esc_html_e( 'Video Lightbox', 'youtubefancybox' ); ?></h1>
			<div class="ytubefancybox-settings-card">
				<h2><?php esc_html_e( 'Generate Vimeo Shortcode', 'youtubefancybox' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="vimeourl"><?php esc_html_e( 'Enter Vimeo URL', 'youtubefancybox' ); ?></label>
						</th>
						<td>
							<input type="url" name="vimeourl" id="vimeourl" class="regular-text" placeholder="https://vimeo.com/..." autocomplete="off" aria-describedby="vimeourl_description" />
							<p class="description" id="vimeourl_description">
								<?php esc_html_e( 'Paste a Vimeo video link.', 'youtubefancybox' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="t_height"><?php esc_html_e( 'Thumbnail Height', 'youtubefancybox' ); ?></label>
						</th>
						<td>
							<input type="number" name="t_height" id="t_height" min="1" max="4096" step="1" autocomplete="off" placeholder="350" aria-describedby="t_height_description" />
							<p class="description" id="t_height_description">
								<?php esc_html_e( 'Height for the image thumbnail and lightbox.', 'youtubefancybox' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="t_width"><?php esc_html_e( 'Thumbnail Width', 'youtubefancybox' ); ?></label>
						</th>
						<td>
							<input type="number" name="t_width" id="t_width" min="1" max="4096" step="1" autocomplete="off" placeholder="400" aria-describedby="t_width_description" />
							<p class="description" id="t_width_description">
								<?php esc_html_e( 'Width for the image thumbnail and lightbox.', 'youtubefancybox' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"></th>
						<td>
							<button type="button" name="getshortcode" id="genratevimeo" class="button button-primary">
								<?php esc_html_e( 'Generate Shortcode', 'youtubefancybox' ); ?>
							</button>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="shortcode"><?php esc_html_e( 'Generated Shortcode', 'youtubefancybox' ); ?></label>
						</th>
						<td>
							<input type="text" id="shortcode" class="regular-text ytubefancybox-shortcode-output" readonly="readonly" placeholder="<?php esc_attr_e( 'Generated shortcode will appear here...', 'youtubefancybox' ); ?>" />
							<p class="description">
								<?php esc_html_e( 'Click to select and copy this shortcode to your post or page.', 'youtubefancybox' ); ?>
							</p>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the [vimeo] shortcode.
	 *
	 * @param array<string, string> $attr Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function render_shortcode( array $attr ): string {
		if ( empty( $attr['height'] ) ) {
			$attr['height'] = get_option( 'youtube_height' );
		}

		if ( empty( $attr['width'] ) ) {
			$attr['width'] = get_option( 'youtube_width' );
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
			preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $attr['url'], $matches );
			$attr['videoid'] = $matches[0] ?? '';
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
