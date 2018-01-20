<?php
/**
 * Description of Vimeo
 *
 * @author milind
 * @package ytubefancybox
 */

namespace YTubeFancy {

	/**
	 * Vimeo Class.
	 */
	class Vimeo {

		/**
		 *  Constuctor for vimeo class
		 */
		public function __construct() {

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'vimeofancybox_plugin_menu' ) );
			}
			add_shortcode( 'vimeo', array( $this, 'vimeofancybox_url' ) );
		}

		/**
		 * Adding submenu page for settings
		 */
		public function vimeofancybox_plugin_menu() {
			add_submenu_page( 'ytubefancybox', 'Vimeo FancyBox Options', 'Vimeo', 'manage_options', 'vimeo', array( $this, 'vimeofancybox_options' ) );
		}

		/**
		 * Function to captrue settings and project form for admin settings.
		 */
		public function vimeofancybox_options() {
			?>
			<div class="wrap">
				<h2><?php esc_html_e( 'Generate Vimeo Shortcode', 'ytubebox' ); ?></h2>
				<hr />
				<table class="form-table">
					<tr>
						<th align="left">
							<?php esc_html_e( 'Enter Vimeo URL', 'ytubebox' ); ?>
						</th>
						<td align="left">
							<input type="text" id="vimeourl" size="80" />
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
							<input type="button" name="getshortcode" value="<?php esc_attr_e( 'Generate', 'ytubebox' ); ?>" id="genratevimeo" class="button button-primary"/>
						</td>
					</tr>
					<tr>
						<th align="left">
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

		/**
		 * Function execute shortcode.
		 *
		 * @param array $attr attributes.
		 * @return string
		 */
		public function vimeofancybox_url( $attr ) {

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

			if ( empty( $attr['videoid'] ) ) {

				if ( ! empty( $attr['url'] ) ) {

					$matches = array();
					preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $attr['url'], $matches );
					$attr['videoid'] = $matches[0];

				}
			}

			if ( get_option( 'autoplay' ) ) {
				$autoplay = get_option( 'autoplay' );
				if ( 'yes' === $autoplay ) {
					$autoplay = '1';
				} else {
					$autoplay = '0';
				}
			} else {
				$autoplay = '1';
			}

			if ( empty( $attr['videoid'] ) ) {
				return '<br /><span style="clear:both;color:red">' . esc_html__( 'Please Enter Vimeo ID or Vimeo URL as [vimeo videoid="XXXXX"]', 'ytubebox' ) . '</span>';
			}

			if ( is_ssl() ) {
				$embed_url       = 'https://player.vimeo.com/video/' . $attr['videoid'] . '?autoplay=' . $autoplay . '&color=ffffff';
				$embed_image_url = 'https://vimeo.com/api/v2/video/' . $attr['videoid'] . '.json';
			} else {
				$embed_url       = 'http://player.vimeo.com/video/' . $attr['videoid'] . '?autoplay=' . $autoplay . '&color=ffffff';
				$embed_image_url = 'http://vimeo.com/api/v2/video/' . $attr['videoid'] . '.json';
			}

			$thumbnail_url = wp_cache_get( 'vimeo_thumnail_' . $attr['videoid'], 'ytubefancybox' );

			if ( false === $thumbnail_url ) {
				/**
				 * Get thumbnail for vimeo video.
				 */
				$response      = wp_remote_get( $embed_image_url );
				$response_body = wp_remote_retrieve_body( $response );
				$obj           = json_decode( $response_body );

				$thumbnail_url = $obj[0]->thumbnail_large;

				wp_cache_set( 'vimeo_thumnail_' . $attr['videoid'], $thumbnail_url, 'ytubefancybox', 1 * HOUR_IN_SECONDS );

			}

			ob_start();
			?>
			<a class="vimeo" href="<?php echo esc_url( $embed_url ); ?>">
				<img src="<?php echo esc_url( $thumbnail_url ); ?>" width="<?php echo esc_attr( $attr['width'] ); ?>" height="<?php echo esc_attr( $attr['height'] ); ?>"/>
			</a>
			<?php
			return ob_get_clean();
		}

	}

}
