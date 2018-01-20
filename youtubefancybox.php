<?php
/**
 * Plugin Name: YouTube FancyBox
 * Plugin URI: http://milindmore22.blogspot.com/
 * Description: Display thumbnail of Youtube and Vimeo videos and on clicking on thumbnail it will open in popupbox and play video.
 * Author: Milind More
 * Author URI: http://milindmore22.blogspot.com/
 * Version: 2.0
 * Text Domain: ytubebox
 * Domain Path: /languages/
 *
 * @author milind.
 * @package ytubefancybox
 */

namespace YTubeFancy {

	/**
	 * Youtube Fancybox main class.
	 */
	class Youtubefanybox {

		/**
		 * Class Constructor.
		 */
		public function __construct() {
			/**
			 * If You are admin you will get admin settings
			 */
			if ( is_admin() ) {
				/**
				 * Adding action calling plugin menu and loading header file
				 */
				add_action( 'admin_menu', array( $this, 'youtubefancybox_plugin_main_menu' ) );
				add_action( 'admin_head', array( $this, 'youtubefancybox_adminjs_file' ) );
			}
			/**
			 * Adding Shortcode action filter
			 */
			add_action( 'wp_head', array( $this, 'youtubefancybox_js_file' ) );
			add_filter( 'widget_text', array( $this, 'shortcode_unautop' ) );
			add_filter( 'widget_text', array( $this, 'do_shortcode' ) );
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
		}

		/**
		 * Adds Menu page for Youtueb Fancybox.
		 */
		public function youtubefancybox_plugin_main_menu() {
			add_menu_page( 'YouTube FancyBox', 'YouTube FancyBox', 'manage_options', 'ytubefancybox', array( $this, 'ytubefancybox_default_settings' ), '', 6 );
		}

		/**
		 * Function will make plugin translation ready.
		 *
		 * @todo created pdo or mo file for translation
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'ytubebox', false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Loading js and css files
		 */
		public function youtubefancybox_adminjs_file() {

			wp_enqueue_script( 'jquery' );
			wp_register_script( 'fancybox_admin', plugins_url( 'js/fancybox_admin.js', __FILE__ ) );

			$translation_array = array(
				'youtube_alert' => esc_html__( 'Youtube url you entered might be wrong, Please enter correct URL !', 'ytubebox' ),
				'viemo_alert'   => esc_html__( 'Viemo url you entered might be wrong, Please enter correct URL !', 'ytubebox' ),
			);

			wp_localize_script( 'fancybox_admin', 'fancybox_admin_obj', $translation_array );
			wp_enqueue_script( 'fancybox_admin' );

		}

		/**
		 * Enqueue scritps js nessary.
		 */
		public function youtubefancybox_js_file() {
			wp_enqueue_style( 'colorboxcss', plugins_url( 'css/colorbox.css', __FILE__ ) );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'colorboxjs', plugins_url( 'js/jquery.colorbox-min.js', __FILE__ ) );
			wp_enqueue_script( 'colorboxcaller', plugins_url( 'js/caller.js', __FILE__ ) );
		}

		/**
		 * Sets Default settings.
		 */
		public function ytubefancybox_default_settings() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
			}

			if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
				if ( get_option( 'youtube_height' ) ) {
					update_option( 'youtube_height', filter_input( INPUT_POST, 'youtube_height' ) );
				} else {
					add_option( 'youtube_height', filter_input( INPUT_POST, 'youtube_height' ), '', 'yes' );
				}
				if ( get_option( 'youtube_width' ) ) {
					update_option( 'youtube_width', filter_input( INPUT_POST, 'youtube_width' ) );
				} else {
					add_option( 'youtube_width', filter_input( INPUT_POST, 'youtube_width' ), '', 'yes' );
				}
				if ( get_option( 'autoplay' ) ) {
					update_option( 'autoplay', filter_input( INPUT_POST, 'autoplay' ) );
				} else {
					add_option( 'autoplay', filter_input( INPUT_POST, 'autoplay' ), '', 'yes' );
				}
			}
			?>
			<style type="text/css">
				fieldset { border: 1px solid; }
			</style>
			<div class="wrap">
				<h1><?php esc_html_e( 'Youtube FancyBox', 'ytubebox' ); ?></h1>

				<h2>Set Default Options</h2>
				<hr />
				<form action="<?php echo esc_url( $_SERVER['PHP_SELF'] ); ?>?page=ytubefancybox" method="post">
					<table class="form-table">
						<tr>
							<th align="left"><?php esc_html_e( 'Height', 'ytubebox' ); ?></th>
							<td align="left">
								<input type="text" name="youtube_height" value="<?php echo esc_attr( get_option( 'youtube_height' ) ); ?>" />
							</td>
						</tr>
						<tr>
							<th align="left"><?php esc_html_e( 'Width', 'ytubebox' ); ?></th>
							<td align="left">
								<input type="text" name="youtube_width" value="<?php echo esc_attr( get_option( 'youtube_width' ) ); ?>" />
							</td>
						</tr>
						<tr>
							<th align="left"><?php esc_html_e( 'Autoplay', 'ytubebox' ); ?></th>
							<td align="left">
								<input type="radio" name="autoplay" value="yes"
									<?php
									if ( 'yes' === get_option( 'autoplay' ) ) {
											echo esc_attr( 'checked="checked"' );
									}
									?>
								/>
								<?php esc_html_e( 'Yes', 'ytubebox' ); ?>
								<input type="radio" name="autoplay" value="no"
									<?php
									if ( 'no' === get_option( 'autoplay' ) ) {
										echo esc_attr( 'checked="checked"' );
									}
									?>
								/>
								<?php esc_html_e( 'No', 'ytubebox' ); ?>
							</td>	
						</tr>
						<tr>
							<th align="left"></th>
							<td align="left">
								<input type="submit" value="<?php esc_attr_e( 'Save', 'ytubebox' ); ?>" name="submit" class="button button-primary" />
							</td>
						</tr>
					</table>
				</form>

			</div>

			<?php
		}

	}

}

namespace {

	/**
	 * Include lib files.
	 */
	foreach ( glob( plugin_dir_path( __FILE__ ) . '/lib/*.php' ) as $lib_filename ) {
		require_once $lib_filename;
	}

	global $fancybox, $youtube, $viemo;
	$fancybox = new \YTubeFancy\Youtubefanybox();
	$youtube  = new \YTubeFancy\Youtube();
	$viemo    = new \YTubeFancy\Vimeo();

}
