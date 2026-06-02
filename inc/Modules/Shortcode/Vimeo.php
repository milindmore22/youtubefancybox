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
			add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
		}

		add_shortcode( 'vimeo', array( $this, 'render_shortcode' ) );
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
			array( $this, 'render_options_page' )
		);
	}

	/**
	 * Render the admin options page for generating Vimeo shortcodes.
	 */
	public function render_options_page(): void {
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Generate Vimeo Shortcode', 'youtubefancybox' ); ?></h2>
			<hr />
			<table class="form-table">
				<tr>
					<th align="left"><?php esc_html_e( 'Enter Vimeo URL', 'youtubefancybox' ); ?></th>
					<td align="left">
						<input type="text" id="vimeourl" size="80" />
					</td>
				</tr>
				<tr>
					<th align="left"><?php esc_html_e( 'Height for Image Thumbnail', 'youtubefancybox' ); ?></th>
					<td align="left">
						<input type="text" name="t_height" id="t_height" />
					</td>
				</tr>
				<tr>
					<th align="left"><?php esc_html_e( 'Width for Image Thumbnail', 'youtubefancybox' ); ?></th>
					<td align="left">
						<input type="text" name="t_width" id="t_width" />
					</td>
				</tr>
				<tr>
					<th align="left"></th>
					<td align="left">
						<input type="button" name="getshortcode" value="<?php esc_attr_e( 'Generate', 'youtubefancybox' ); ?>" id="genratevimeo" class="button button-primary"/>
					</td>
				</tr>
				<tr>
					<th align="left"></th>
					<td align="left">
						<input type="text" id="shortcode" readonly="readonly" size="80"/>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render the [vimeo] shortcode.
	 *
	 * @param array  $attr Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function render_shortcode( $attr ): string {
		if ( empty( $attr['height'] ) ) {
			$attr['height'] = get_option( 'youtube_height' );
		}

		if ( empty( $attr['width'] ) ) {
			$attr['width'] = get_option( 'youtube_width' );
		}

		$attr = shortcode_atts(
			array(
				'url'     => '',
				'videoid' => '',
				'height'  => '350',
				'width'   => '400',
			),
			$attr,
			'vimeo'
		);

		if ( empty( $attr['videoid'] ) && ! empty( $attr['url'] ) ) {
			$matches        = array();
			preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $attr['url'], $matches );
			$attr['videoid'] = $matches[0] ?? '';
		}

		$autoplay_option = get_option( 'autoplay' );
		$autoplay        = ( ! empty( $autoplay_option ) && 'yes' === $autoplay_option )
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
			$response = wp_remote_get( $embed_image_url );

			if ( is_wp_error( $response ) ) {
				if ( ! empty( $response ) ) {
					return '<br /><span style="clear:both;color:red">' . $response->get_error_message() . '</span>';
				}
				return '<br /><span style="clear:both;color:red">' . esc_html__( 'Error in fetching Vimeo Video Thumbnail', 'youtubefancybox' ) . '</span>';
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
					<amp-vimeo width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" layout="fill" data-videoid="<?php echo esc_attr( $attr['videoid'] ); ?>" <?php echo ( ! empty( $autoplay_option ) && 'yes' === $autoplay_option ) ? 'autoplay' : ''; ?>>
					</amp-vimeo>
				</div>
			</amp-lightbox>
			<amp-img class="aligncenter" on="tap:<?php echo esc_attr( $light_box_id ); ?>" src="<?php echo esc_url( $thumbnail_url ); ?>" width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" layout="intrinsic">
			</amp-img>
			<?php
			return ob_get_clean();
		}
		?>
		<div class="youtubefancybox-lightbox-container aligncenter">
			<a class="vimeo" href="<?php echo esc_url( $embed_url ); ?>">
				<img src="<?php echo esc_url( $thumbnail_url ); ?>" width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>"/>
			</a>
		</div>
		<?php
		return ob_get_clean();
	}
}
