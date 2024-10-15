<?php
/**
 * Description of Youtube
 *
 * @author milind
 * @package ytubefancybox
 */

namespace YTubeFancy {

	/**
	 *  Class for Youtube functonality.
	 */
	class Youtube {

		/**
		 *  Constuctor for youtube class
		 */
		public function __construct() {

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'youtubefancybox_plugin_menu' ) );
			}
			add_shortcode( 'youtube', array( $this, 'youtubefancybox_url' ) );
		}

		/**
		 * Adding submenu page for settings
		 */
		public function youtubefancybox_plugin_menu() {
			add_submenu_page( 'ytubefancybox', 'Video Lightbox Options', 'Youtube', 'manage_options', 'ytube', array( $this, 'youtubefancybox_options' ) );
		}

		/**
		 * Function execute shortcode.
		 *
		 * @param array $attr attributes.
		 * @return string
		 */
		public function youtubefancybox_url( $attr ) {

			if ( ! isset( $attr['height'] ) ) {
				$attr['height'] = get_option( 'youtube_height' );
			}

			if ( ! isset( $attr['width'] ) ) {
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
				'youtube'
			);

			if ( ! isset( $attr['videoid'] ) ) {
				if ( isset( $attr['url'] ) ) {
					$matches = array();
					preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $attr['url'], $matches );
					$attr['videoid'] = $matches[0];
				}
			}

			$autoplay        = 'autoplay=1&muted=1';
			$autoplay_option = get_option( 'autoplay' );

			if ( ! empty( $autoplay_option ) && 'yes' === $autoplay_option ) {
					$autoplay = 'autoplay=1&muted=1';
			} else {
				$autoplay = 'autoplay=0&muted=0';
			}

			if ( empty( $attr['videoid'] ) ) {

				return '<br /><span style="clear:both;color:red">' . esc_html__( 'Please Enter Youtube ID or Youtube URL as [youtube videoid="XXXXX"]', 'ytubebox' ) . '</span>';

			}

			if ( is_ssl() ) {

				$embed_url   = 'https://www.youtube.com/embed/' . $attr['videoid'] . '?rel=0&' . $autoplay . '&wmode=transparent';
				$embed_image = 'https://img.youtube.com/vi/' . $attr['videoid'] . '/0.jpg';

			} else {

				$embed_url   = 'http://www.youtube.com/embed/' . $attr['videoid'] . '?rel=0&' . $autoplay . '&wmode=transparent';
				$embed_image = 'http://img.youtube.com/vi/' . $attr['videoid'] . '/0.jpg';

			}

			ob_start();

			if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
				$light_box_id = wp_unique_id( 'youtubefancybox-youtube-lightbox-' );
				?>
					<amp-lightbox class="ytfancybox-lightbox alignfull" id="<?php echo esc_attr( $light_box_id ); ?>" layout="nodisplay">
						<div class="youtubefancybox-amp-lightbox" role="button" tabindex="0">
							<span role="button" tabindex="0" on="tap:<?php echo esc_attr( $light_box_id ); ?>.close" class="youtubefancybox-amp-lightbox-close">X</span>
							<amp-youtube width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" layout="fill" data-videoid="<?php echo esc_attr( $attr['videoid'] ); ?>" <?php echo ( ! empty( $autoplay_option ) && 'yes' === $autoplay_option ) ? 'autoplay' : ''; ?>>
							</amp-youtube>
						</div>
					</amp-lightbox>
					<amp-img class="aligncenter" on="tap:<?php echo esc_attr( $light_box_id ); ?>" src="<?php echo esc_url( $embed_image ); ?>" width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" layout="intrinsic">
					</amp-img>
						<?php
						return ob_get_clean();
			}
			?>
				<div class="youtubefancybox-lightbox-container aligncenter">
					<a class="youtube" href="<?php echo esc_url( $embed_url ); ?>">
						<img src="<?php echo esc_url( $embed_image ); ?>" width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>" />
					</a>
				</div>
				<?php
				return ob_get_clean();

		}

		/**
		 * Function to captrue settings and project form for admin settings.
		 */
		public function youtubefancybox_options() {
			?>
			<div class="wrap">

				<h2><?php esc_html_e( 'Generate Youtube Shortcode', 'ytubebox' ); ?></h2>
				<hr />
				<table class="form-table">
					<tr>
						<th align="left">
							<?php esc_html_e( 'Enter Youtube URL', 'ytubebox' ); ?>
						</th>
						<td align="left">
							<input type="text" name="youtubeurl" id="youtubeurl" size="80" />
						</td>
					</tr>
					<tr>
						<th align="left">
							<?php esc_html_e( 'Height for Image Thumbnail', 'ytubebox' ); ?>
						</th>
						<td align="left">
							<input type="text" name="t_height" id="t_height" />
						</td>
					</tr>
					<tr>
						<th align="left">
							<?php esc_html_e( 'Width for Image Thumbnail', 'ytubebox' ); ?>
						</th>
						<td align="left">
							<input type="text" name="t_width" id="t_width" />
						</td>
					</tr>
					<tr>
						<th align="left">

						</th>
						<td align="left">
							<input type="button" name="getshortcode" value="<?php esc_attr_e( 'Generate', 'ytubebox' ); ?>" id="genrate" class="button button-primary"/>
						</td>
					</tr>
					<tr>
						<th>
						</th>
						<td align="left">
							<input type="text" id="shortcode" readonly="readonly" size="80"/>
						</td>
					</tr>
				</table>
			</div>
			<?php
			/**
			 * Form ends
			 */
		}

	}

}
