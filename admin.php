<?php
/**
 * Adding action calling plugin menu and loading header file
 */
add_action( 'admin_menu', 'youtubefancybox_plugin_menu' );
add_action( 'admin_head', 'youtubefancybox_adminjs_file' );

/**
 * Loading js and css files
 */
function youtubefancybox_adminjs_file() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'fancybox_admin', plugins_url( 'js/fancybox_admin.js', __FILE__ ) );
}

/**
 * Adding submenu page for settings
 */
function youtubefancybox_plugin_menu() {
	add_submenu_page( 'plugins.php', 'Youtube FancyBox Options', 'YTubeFancyBox', 'manage_options', 'ytubefancybox', 'youtubefancybox_options' );
}

/**
 * function to captrue settings and project form for admin settings
 */
function youtubefancybox_options() {
	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	if ( $_SERVER[ 'REQUEST_METHOD' ] == "POST" ) {
		if ( get_option( 'youtube_height' ) ) {
			update_option( 'youtube_height', $_POST[ 'youtube_height' ] );
		} else {
			add_option( 'youtube_height', $_POST[ 'youtube_height' ], '', 'yes' );
		}
		if ( get_option( 'youtube_width' ) ) {
			update_option( 'youtube_width', $_POST[ 'youtube_width' ] );
		} else {
			add_option( 'youtube_width', $_POST[ 'youtube_width' ], '', 'yes' );
		}
		if ( get_option( 'autoplay' ) ) {
			update_option( 'autoplay', $_POST[ 'autoplay' ] );
		} else {
			add_option( 'autoplay', $_POST[ 'autoplay' ], '', 'yes' );
		}
	}
	/**
	 * Form start from
	 */
	?>
	<div class="wrap">
		<h2>Youtube video in FancyBox</h2>

		<h4>Set Default options here </h4>
		<form action="<?php echo $_SERVER[ 'PHP_SELF' ] ?>?page=ytubefancybox" method="post">
			<table>
				<tr>
					<th>Height</th>
					<td><input type="text" name="youtube_height" value="<?php echo get_option( 'youtube_height' ); ?>" /></td>
				</tr>
				<tr>
					<th>Width</th>
					<td><input type="text" name="youtube_width" value="<?php echo get_option( 'youtube_width' ); ?>" /></td>
				</tr>
				<tr>
					<th>
						Autoplay
					</th>
					<td>

						<input type="radio" name="autoplay" value="yes" <?php if ( get_option( 'autoplay' ) == "yes" ) {
		echo 'checked="checked"';
	} ?>/> Yes <input type="radio" name="autoplay" value="no" <?php if ( get_option( 'autoplay' ) == "no" ) {
		echo 'checked="checked"';
	} ?> /> No
					</td>
				</tr>
				<tr>
					<th></th>
					<td><input type="submit" value="Save" name="submit" class="button button-primary" /></td>
				</tr>
			</table>
		</form>
	</div>
	<div class="wrap">
		<h4>Generate Shortcode</h4>
		<table>
			<tr>
				<th>
					Enter Youtube URL
				</th>
				<td>
					<input type="text" name="youtubeurl" id="youtubeurl" size="80" />
				</td>
			</tr>
			<tr>
				<th>
					Height for Image Thumbnail
				</th>
				<td>
					<input type="text" name="t_height" id="t_height" />
				</td>
			</tr>
			<tr>
				<th>
					Width for Image Thumbnail
				</th>
				<td>
					<input type="text" name="t_width" id="t_width" />
				</td>
			</tr>
			<tr>
				<th>

				</th>
				<td>
					<input type="button" name="getshortcode" value="Generate" id="genrate" class="button button-primary"/>
				</td>
			</tr>
			<tr>
				<th>
				</th>
				<td>
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
?>
