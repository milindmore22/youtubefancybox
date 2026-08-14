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
 * Registers the [youtube] shortcode and the admin submenu page
 * for generating YouTube shortcodes.
 */
class Youtube implements Registrable {

	/**
	 * Register WordPress hooks.
	 */
	public function register_hooks(): void {
		if ( is_admin() ) {
			add_action( 'admin_menu', [ $this, 'add_submenu_page' ] );
		}

		add_shortcode( 'youtube', [ $this, 'render_shortcode' ] );
	}

	/**
	 * Add the Youtube submenu page.
	 */
	public function add_submenu_page(): void {
		add_submenu_page(
			'ytubefancybox',
			'Video Lightbox Options',
			'Youtube',
			'manage_options',
			'ytube',
			[ $this, 'render_options_page' ]
		);
	}

	/**
	 * Render the [youtube] shortcode.
	 *
	 * @param array<string, string> $attr Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function render_shortcode( array $attr ): string {
		if ( ! isset( $attr['height'] ) ) {
			$attr['height'] = get_option( 'youtube_height' );
		}

		if ( ! isset( $attr['width'] ) ) {
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
			'youtube'
		);

		if ( ! isset( $attr['videoid'] ) && isset( $attr['url'] ) ) {
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
		$embed_url   = $protocol . '://www.youtube.com/embed/' . $attr['videoid'] . '?rel=0&' . $autoplay . '&wmode=transparent';
		$embed_image = $protocol . '://img.youtube.com/vi/' . $attr['videoid'] . '/0.jpg';

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

	/**
	 * Render the admin options page for generating YouTube shortcodes.
	 */
	public function render_options_page(): void {
		?>
		<div class="wrap ytubefancybox-settings">
			<h1><?php esc_html_e( 'Video Lightbox', 'youtubefancybox' ); ?></h1>
			<div class="ytubefancybox-settings-card">
				<h2><?php esc_html_e( 'Generate YouTube Shortcode', 'youtubefancybox' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="youtubeurl"><?php esc_html_e( 'Enter YouTube URL', 'youtubefancybox' ); ?></label>
						</th>
						<td>
							<input type="url" name="youtubeurl" id="youtubeurl" class="regular-text" placeholder="https://www.youtube.com/watch?v=..." autocomplete="off" aria-describedby="youtubeurl_description" />
							<p class="description" id="youtubeurl_description">
								<?php esc_html_e( 'Paste a YouTube video or shorts link.', 'youtubefancybox' ); ?>
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
							<button type="button" name="getshortcode" id="genrate" class="button button-primary">
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
}
