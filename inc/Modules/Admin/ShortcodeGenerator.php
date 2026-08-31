<?php
/**
 * Shortcode generator admin page.
 *
 * @package YTubeFancy\Modules\Admin
 */

declare( strict_types = 1 );

namespace YTubeFancy\Modules\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use YTubeFancy\Contracts\Interfaces\Registrable;

/**
 * Class ShortcodeGenerator
 *
 * Provides a combined YouTube and Vimeo shortcode generator under Media.
 */
class ShortcodeGenerator implements Registrable {

	/**
	 * Register WordPress hooks.
	 */
	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
	}

	/**
	 * Register the Media submenu page.
	 */
	public function register_menu(): void {
		add_submenu_page(
			'upload.php',
			__( 'Video Lightbox', 'youtubefancybox' ),
			__( 'Video Lightbox', 'youtubefancybox' ),
			'upload_files',
			'youtubefancybox-shortcode-generator',
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Render the shortcode generator page.
	 */
	public function render_page(): void {
		if ( ! current_user_can( 'upload_files' ) ) {
			return;
		}

		$shortcode     = '';
		$error_message = '';

		if ( isset( $_POST['youtubefancybox_generate_shortcode'] ) ) {
			check_admin_referer( 'youtubefancybox_generate_shortcode' );
		}

		$values = $this->get_values();

		if ( isset( $_POST['youtubefancybox_generate_shortcode'] ) ) {
			$provider = $this->detect_provider( $values['video'] );

			if ( '' !== $provider ) {
				$values['provider'] = $provider;
				$shortcode          = $this->build_shortcode( $values );
			} else {
				$error_message = __( 'Enter a valid YouTube or Vimeo video URL or video ID.', 'youtubefancybox' );
			}
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Video Lightbox Shortcode Generator', 'youtubefancybox' ); ?></h1>
			<?php if ( '' !== $error_message ) : ?>
				<div class="notice notice-error"><p><?php echo esc_html( $error_message ); ?></p></div>
			<?php endif; ?>
			<form method="post">
				<?php wp_nonce_field( 'youtubefancybox_generate_shortcode' ); ?>
				<table class="form-table" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><label for="youtubefancybox-video-url"><?php esc_html_e( 'Video URL or ID', 'youtubefancybox' ); ?></label></th>
							<td>
								<input class="regular-text" id="youtubefancybox-video-url" name="video" type="text" value="<?php echo esc_attr( $values['video'] ); ?>" required />
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="youtubefancybox-width"><?php esc_html_e( 'Width', 'youtubefancybox' ); ?></label></th>
							<td><input class="small-text" id="youtubefancybox-width" min="1" name="width" type="number" value="<?php echo esc_attr( (string) $values['width'] ); ?>" /></td>
						</tr>
						<tr>
							<th scope="row"><label for="youtubefancybox-height"><?php esc_html_e( 'Height', 'youtubefancybox' ); ?></label></th>
							<td><input class="small-text" id="youtubefancybox-height" min="1" name="height" type="number" value="<?php echo esc_attr( (string) $values['height'] ); ?>" /></td>
						</tr>
					</tbody>
				</table>
				<?php submit_button( __( 'Generate shortcode', 'youtubefancybox' ), 'primary', 'youtubefancybox_generate_shortcode' ); ?>
			</form>

			<?php if ( '' !== $shortcode ) : ?>
				<h2><?php esc_html_e( 'Shortcode', 'youtubefancybox' ); ?></h2>
				<textarea class="large-text code" id="youtubefancybox-shortcode" readonly rows="2"><?php echo esc_textarea( $shortcode ); ?></textarea>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get sanitized generator values from the submitted form.
	 *
	 * @return array{provider: string, video: string, width: int, height: int} Form values.
	 */
	private function get_values(): array {
		if ( ! isset( $_POST['youtubefancybox_generate_shortcode'], $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'youtubefancybox_generate_shortcode' ) ) {
			return [
				'provider' => 'youtube',
				'video'    => '',
				'width'    => 400,
				'height'   => 350,
			];
		}

		$video  = isset( $_POST['video'] ) ? sanitize_text_field( wp_unslash( $_POST['video'] ) ) : '';
		$width  = isset( $_POST['width'] ) ? absint( $_POST['width'] ) : 400;
		$height = isset( $_POST['height'] ) ? absint( $_POST['height'] ) : 350;

		return [
			'provider' => '',
			'video'    => $video,
			'width'    => $width > 0 ? $width : 400,
			'height'   => $height > 0 ? $height : 350,
		];
	}

	/**
	 * Detect the provider from a valid YouTube or Vimeo URL or video ID.
	 *
	 * @param string $video Video URL or ID.
	 * @return string Provider name, or an empty string when invalid.
	 */
	private function detect_provider( string $video ): string {
		if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
			foreach ( [ 'youtube', 'vimeo' ] as $provider ) {
				if ( $this->is_valid_provider_url( $provider, $video ) ) {
					return $provider;
				}
			}

			return '';
		}

		if ( ctype_digit( $video ) ) {
			return 'vimeo';
		}

		return 1 === preg_match( '/^[A-Za-z0-9_-]{11}$/', $video ) ? 'youtube' : '';
	}

	/**
	 * Determine whether a URL belongs to a provider and identifies a video.
	 *
	 * @param string $provider Video provider.
	 * @param string $url      Video URL.
	 * @return bool Whether the URL is valid for the provider.
	 */
	private function is_valid_provider_url( string $provider, string $url ): bool {
		$url_parts = wp_parse_url( $url );
		$host      = isset( $url_parts['host'] ) ? strtolower( $url_parts['host'] ) : '';
		$path      = isset( $url_parts['path'] ) ? trim( $url_parts['path'], '/' ) : '';

		if ( 'youtube' === $provider ) {
			if ( 'youtu.be' === $host ) {
				return 1 === preg_match( '/^[A-Za-z0-9_-]{11}$/', $path );
			}

			if ( ! in_array( $host, [ 'youtube.com', 'www.youtube.com', 'm.youtube.com' ], true ) ) {
				return false;
			}

			if ( 'watch' === $path && isset( $url_parts['query'] ) ) {
				parse_str( $url_parts['query'], $query_args );
				return isset( $query_args['v'] ) && 1 === preg_match( '/^[A-Za-z0-9_-]{11}$/', $query_args['v'] );
			}

			return 1 === preg_match( '#^(?:embed|shorts|live)/[A-Za-z0-9_-]{11}$#', $path );
		}

		if ( ! in_array( $host, [ 'vimeo.com', 'www.vimeo.com', 'player.vimeo.com' ], true ) ) {
			return false;
		}

		return 1 === preg_match( '#^(?:(?:channels/[^/]+|groups/[^/]+/videos|album/\d+/video|video)/)?\d+$#', $path );
	}

	/**
	 * Build a shortcode supported by the current provider modules.
	 *
	 * @param array{provider: string, video: string, width: int, height: int} $values Form values.
	 * @return string Generated shortcode.
	 */
	private function build_shortcode( array $values ): string {
		$video_attribute = filter_var( $values['video'], FILTER_VALIDATE_URL ) ? 'url' : 'videoid';

		return sprintf(
			'[%1$s %2$s="%3$s" width="%4$d" height="%5$d"]',
			$values['provider'],
			$video_attribute,
			$values['video'],
			$values['width'],
			$values['height']
		);
	}
}
