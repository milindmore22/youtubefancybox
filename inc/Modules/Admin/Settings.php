<?php
/**
 * Admin settings module.
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
 * Class Settings
 *
 * Handles the admin settings page for the plugin, including the menu
 * registration and the default options form (height, width, autoplay).
 *
 * Uses the WordPress Settings API so that option saving is handled by
 * core, including nonce/CSRF validation via `settings_fields()`.
 */
class Settings implements Registrable {

	/**
	 * Option group used by register_setting().
	 *
	 * @var string
	 */
	private const OPTION_GROUP = 'ytubefancybox_settings';

	/**
	 * The names of the individual options managed on this page.
	 *
	 * Kept as separate options for backward compatibility with the
	 * shortcodes, which read them directly via get_option().
	 *
	 * @var string[]
	 */
	private const OPTIONS = [
		'youtube_height',
		'youtube_width',
		'autoplay',
	];

	/**
	 * Register WordPress hooks.
	 */
	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Add the main admin menu page.
	 */
	public function add_admin_menu(): void {
		add_menu_page(
			'Video Lightbox for YouTube/Vimeo',
			'Video Lightbox',
			'manage_options',
			'ytubefancybox',
			[ $this, 'render_settings_page' ],
			'dashicons-format-video',
			6
		);
	}

	/**
	 * Register the plugin settings and their sanitize callbacks.
	 */
	public function register_settings(): void {
		foreach ( self::OPTIONS as $option ) {
			register_setting(
				self::OPTION_GROUP,
				$option,
				[
					'type'              => 'string',
					'sanitize_callback' => [ $this, 'sanitize_' . $option ],
					'default'           => $this->default_for( $option ),
				]
			);
		}
	}

	/**
	 * Render the settings page.
	 */
	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'youtubefancybox' ) );
		}

		$this->display_settings_form();
	}

	/**
	 * Display the settings form.
	 */
	private function display_settings_form(): void {
		?>
		<div class="wrap ytubefancybox-settings">
			<h1><?php esc_html_e( 'Video Lightbox', 'youtubefancybox' ); ?></h1>
			<div class="ytubefancybox-settings-card">
				<h2><?php esc_html_e( 'Set Default Options', 'youtubefancybox' ); ?></h2>
				<form action="options.php" method="post">
				<?php settings_fields( self::OPTION_GROUP ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="youtube_height"><?php esc_html_e( 'Height', 'youtubefancybox' ); ?></label>
						</th>
						<td>
							<input type="number" id="youtube_height" name="youtube_height" min="1" max="4096" step="1"
								autocomplete="off" aria-describedby="youtube_height_description"
								value="<?php echo esc_attr( get_option( 'youtube_height', $this->default_for( 'youtube_height' ) ) ); ?>"
							/>
							<p class="description" id="youtube_height_description">
								<?php esc_html_e( 'Default height for the video thumbnail and lightbox.', 'youtubefancybox' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="youtube_width"><?php esc_html_e( 'Width', 'youtubefancybox' ); ?></label>
						</th>
						<td>
							<input type="number" id="youtube_width" name="youtube_width" min="1" max="4096" step="1"
								autocomplete="off" aria-describedby="youtube_width_description"
								value="<?php echo esc_attr( get_option( 'youtube_width', $this->default_for( 'youtube_width' ) ) ); ?>"
							/>
							<p class="description" id="youtube_width_description">
								<?php esc_html_e( 'Default width for the video thumbnail and lightbox.', 'youtubefancybox' ); ?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Autoplay', 'youtubefancybox' ); ?></th>
						<td>
							<fieldset id="ytubefancybox-autoplay" class="ytubefancybox-segmented">
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Autoplay', 'youtubefancybox' ); ?></span>
								</legend>
								<label for="autoplay-yes" class="ytubefancybox-segmented__item">
									<input type="radio" id="autoplay-yes" class="ytubefancybox-segmented__input" name="autoplay" value="yes"
										aria-describedby="autoplay_description"
										<?php checked( 'yes', get_option( 'autoplay', $this->default_for( 'autoplay' ) ) ); ?>
									/>
									<span class="ytubefancybox-segmented__label"><?php esc_html_e( 'Yes', 'youtubefancybox' ); ?></span>
								</label>
								<label for="autoplay-no" class="ytubefancybox-segmented__item">
									<input type="radio" id="autoplay-no" class="ytubefancybox-segmented__input" name="autoplay" value="no"
										aria-describedby="autoplay_description"
										<?php checked( 'no', get_option( 'autoplay', $this->default_for( 'autoplay' ) ) ); ?>
									/>
									<span class="ytubefancybox-segmented__label"><?php esc_html_e( 'No', 'youtubefancybox' ); ?></span>
								</label>
							</fieldset>
							<p class="description" id="autoplay_description">
								<?php esc_html_e( 'Whether videos should play automatically when the lightbox opens.', 'youtubefancybox' ); ?>
							</p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Sanitize the youtube_height option.
	 *
	 * @param mixed $value Raw value submitted by the form.
	 * @return int|string Positive integer value, or empty string when invalid.
	 */
	public function sanitize_youtube_height( $value ) {
		return $this->sanitize_dimension( $value, 'youtube_height' );
	}

	/**
	 * Sanitize the youtube_width option.
	 *
	 * @param mixed $value Raw value submitted by the form.
	 * @return int|string Positive integer value, or empty string when invalid.
	 */
	public function sanitize_youtube_width( $value ) {
		return $this->sanitize_dimension( $value, 'youtube_width' );
	}

	/**
	 * Sanitize the autoplay option.
	 *
	 * @param mixed $value Raw value submitted by the form.
	 * @return string 'yes' or 'no'.
	 */
	public function sanitize_autoplay( $value ): string {
		return 'yes' === $value ? 'yes' : 'no';
	}

	/**
	 * Validate a dimension (height/width) as a positive integer.
	 *
	 * When the submitted value is invalid and a non-empty value was already
	 * stored, the previous value is kept to avoid wiping the configuration.
	 *
	 * @param mixed  $value  Raw value submitted by the form.
	 * @param string $option Option name being sanitized.
	 * @return int|string Positive integer, or empty string when invalid.
	 */
	private function sanitize_dimension( $value, string $option ) {
		$value = (string) $value;

		if ( '' === $value ) {
			return '';
		}

		if ( ! is_numeric( $value ) || (int) $value < 1 || (string) (int) $value !== $value ) {
			return '';
		}

		return (int) $value;
	}

	/**
	 * Get the default value for a given option.
	 *
	 * @param string $option Option name.
	 * @return string Default value.
	 */
	private function default_for( string $option ): string {
		$defaults = [
			'youtube_height' => '350',
			'youtube_width'  => '400',
			'autoplay'       => 'no',
		];

		return $defaults[ $option ] ?? '';
	}
}