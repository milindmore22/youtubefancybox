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
 */
class Settings implements Registrable {

	/**
	 * Register WordPress hooks.
	 */
	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
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
	 * Render the settings page.
	 */
	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'youtubefancybox' ) );
		}

		if ( 'POST' === filter_input( INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) ) {
			$this->save_settings();
		}

		$this->display_settings_form();
	}

	/**
	 * Save the default settings.
	 */
	private function save_settings(): void {
		$fields = [
			'youtube_height',
			'youtube_width',
			'autoplay',
		];

		foreach ( $fields as $field ) {
			$value = filter_input( INPUT_POST, $field, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			if ( get_option( $field ) ) {
				update_option( $field, $value );
			} else {
				add_option( $field, $value, '', 'yes' );
			}
		}
	}

	/**
	 * Display the settings form.
	 */
	private function display_settings_form(): void {
		?>
		<style type="text/css">
			fieldset { border: 1px solid; }
		</style>
		<div class="wrap">
			<h1><?php esc_html_e( 'Video Lightbox', 'youtubefancybox' ); ?></h1>
			<h2>Set Default Options</h2>
			<hr />
			<form action="" method="post">
				<table class="form-table">
					<tr>
						<th align="left"><?php esc_html_e( 'Height', 'youtubefancybox' ); ?></th>
						<td align="left">
							<input type="text" name="youtube_height" value="<?php echo esc_attr( get_option( 'youtube_height' ) ); ?>" />
						</td>
					</tr>
					<tr>
						<th align="left"><?php esc_html_e( 'Width', 'youtubefancybox' ); ?></th>
						<td align="left">
							<input type="text" name="youtube_width" value="<?php echo esc_attr( get_option( 'youtube_width' ) ); ?>" />
						</td>
					</tr>
					<tr>
						<th align="left"><?php esc_html_e( 'Autoplay', 'youtubefancybox' ); ?></th>
						<td align="left">
							<input type="radio" name="autoplay" value="yes"
								<?php
								if ( 'yes' === get_option( 'autoplay' ) ) {
									echo esc_attr( 'checked="checked"' );
								}
								?>
							/>
							<?php esc_html_e( 'Yes', 'youtubefancybox' ); ?>
							<input type="radio" name="autoplay" value="no"
								<?php
								if ( 'no' === get_option( 'autoplay' ) ) {
									echo esc_attr( 'checked="checked"' );
								}
								?>
							/>
							<?php esc_html_e( 'No', 'youtubefancybox' ); ?>
						</td>
					</tr>
					<tr>
						<th align="left"></th>
						<td align="left">
							<input type="submit" value="<?php esc_attr_e( 'Save', 'youtubefancybox' ); ?>" name="submit" class="button button-primary" />
						</td>
					</tr>
				</table>
			</form>
		</div>
		<?php
	}
}
