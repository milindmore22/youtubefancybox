<?php
/**
 * @package YoutubeFancyBox
 * @version 1.3
 */
/*
Plugin Name: YouTube FancyBox
Plugin URI: http://milindmore22.blogspot.com/
Description: This plugin runs with shortcodes [youtube videoid="as-H0sZbbd0" height="100" width="100"] OR [youtube url="https://www.youtube.com/watch?v=DYojBZG5d1Q" height="100" width="100"] for colorbox /lightbox (thanks to  Jack Moore(http://www.jacklmoore.com/colorbox/) )
Author: Milind More
Author URI: http://milindmore22.blogspot.com/
Version: 1.3
Text Domain: ytubebox
Domain Path: /languages/
*/
class youtubefanybox{

    public function __construct(){
        /**
         * If You are admin you will get admin settings
         */
        if ( is_admin() ){
               /**
                * Adding action calling plugin menu and loading header file
                */
               add_action( 'admin_menu', array( $this,'youtubefancybox_plugin_menu' ) );
               add_action( 'admin_head', array( $this, 'youtubefancybox_adminjs_file' ) );
        }
        /**
         * Adding Shortcode action filter
         */
        add_shortcode( 'youtube' , array( $this ,'youtubefancybox_url') );
        add_action('wp_head', array ( $this, 'youtubefancybox_js_file' ) );
        add_filter( 'widget_text', array ( $this ,'shortcode_unautop' ) );
        add_filter( 'widget_text', array ( $this, 'do_shortcode' ) );
        add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
    }
    
    /**
     * 
     */
    function load_plugin_textdomain() {
        load_plugin_textdomain( 'ytubebox', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
    /**
    * Loading js and css files
    */
   function youtubefancybox_adminjs_file(){
           wp_enqueue_script('jquery');
           wp_enqueue_script('fancybox_admin', plugins_url('js/fancybox_admin.js',__FILE__) );
   }
   /**
    * Adding submenu page for settings
    */
   function youtubefancybox_plugin_menu() {
           add_submenu_page('plugins.php' ,'Youtube FancyBox Options', 'YTubeFancyBox', 'manage_options', 'ytubefancybox', array( $this,'youtubefancybox_options' ) );
   }
    /**
     * Enqueue scritps js nessary
     */
    function youtubefancybox_js_file(){
            wp_enqueue_style('colorboxcss',plugins_url('css/colorbox.css',__FILE__));
            wp_enqueue_script('jquery');
            wp_enqueue_script('colorboxjs', plugins_url('js/jquery.colorbox-min.js',__FILE__) );
            wp_enqueue_script('colorboxcaller', plugins_url('js/caller.js',__FILE__) );
    }
    /**
     * function execute shortcode
     * @param array $attr
     * @return html
     */
    function youtubefancybox_url($attr){
            if( !isset( $attr[ 'height' ] ) ){
                    $attr[ 'height' ]= get_option( 'youtube_height' );
            }
            if( !isset($attr[ 'width' ] ) )	{
                    $attr[ 'width' ]= get_option( 'youtube_width' );
            }
            if( !isset( $attr[ 'videoid' ] ) )
            {
                    if( isset(  $attr[ 'url' ] ) ){
                            $matches=array();
                            preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $attr['url'], $matches );
                            $attr[ 'videoid' ]=$matches[0];
                    }
            }
            if( get_option('autoplay') )
            {
                    $autoplay=get_option( 'autoplay' );
                    if( $autoplay=='yes' )
                    {
                            $autoplay='1';
                    }else{
                            $autoplay='0';
                    }
            }else{
                    $autoplay='1';
            }
            if ( isset( $_SERVER[ 'HTTPS' ] ) &&
                            ( $_SERVER[ 'HTTPS' ] == 'on' || $_SERVER[ 'HTTPS' ] == 1 ) ||
                            isset( $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ) &&
                            $_SERVER[ 'HTTP_X_FORWARDED_PROTO'] == 'https' ) {
                    $protocol = 'https://';
            }
            else {
                    $protocol = 'http://';
            }

            if( isset( $attr['videoid'] ) )
            {
            ?>
                <a class='youtube' href="<?php echo $protocol; ?>www.youtube.com/embed/<?php echo $attr['videoid']; ?>?rel=0&autoplay=<?php echo $autoplay?>&wmode=transparent"><img src="<?php echo $protocol;?>img.youtube.com/vi/<?php echo $attr['videoid'];?>/0.jpg" width="<?php echo $attr['width']; ?>" height="<?php echo $attr['height']; ?>"/></a>
            <?php
            }else{
                    echo "\n<br /><span style='clear:both;color:red'>". __( "Please Enter Youtube ID or Youtube URL as [youtube videoid='XXXXX']","ytubebox")."</span>";
            } 
    }
    
    /**
     * function to captrue settings and project form for admin settings 
     */
    function youtubefancybox_options(){
            if ( !current_user_can( 'manage_options' ) )  {
                    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }

            if($_SERVER['REQUEST_METHOD']=="POST"){
                    if(get_option('youtube_height')){
                            update_option('youtube_height', $_POST['youtube_height']);
                    }else{
                    add_option( 'youtube_height',$_POST['youtube_height'] , '', 'yes' );
                    }
                    if(get_option('youtube_width')){
                            update_option('youtube_width', $_POST['youtube_width']);
                    }else{
                            add_option('youtube_width',$_POST['youtube_width'] , '', 'yes' );
                    }
                    if(get_option('autoplay')){
                            update_option('autoplay', $_POST['autoplay']);
                    }else{
                            add_option('autoplay',$_POST['autoplay'],'', 'yes' );
                    }

            }
            /**
             * Form start from 
             */
            ?>
            <div class="wrap">
            <h2><?php _e( 'Youtube video in FancyBox','ytubebox'); ?></h2>

                    <h4><?php _e( 'Set Default options here', 'ytubebox'); ?> </h4>
                    <form action="<?php echo $_SERVER['PHP_SELF']?>?page=ytubefancybox" method="post">
                                    <table>
                                            <tr>
                                                    <th><?php _e( 'Height' , 'ytubebox' ) ?></th>
                                                    <td>
                                                        <input type="text" name="youtube_height" value="<?php echo get_option('youtube_height');?>" />
                                                    </td>
                                            </tr>
                                            <tr>
                                                    <th><?php _e( 'Width', 'ytubebox' ); ?></th>
                                                    <td>
                                                        <input type="text" name="youtube_width" value="<?php echo get_option('youtube_width'); ?>" />
                                                    </td>
                                            </tr>
                                            <tr>
                                                    <th><?php _e( 'Autoplay','ytubebox'); ?></th>
                                                    <td>
                                                        <input type="radio" name="autoplay" value="yes" <?php if(get_option('autoplay')=="yes"){echo 'checked="checked"';}?>/> Yes <input type="radio" name="autoplay" value="no" <?php if(get_option('autoplay')=="no"){echo 'checked="checked"';}?> /> No
                                                    </td>	
                                            </tr>
                                            <tr>
                                                    <th></th>
                                                    <td>
                                                        <input type="submit" value="<?php _e( 'Save','ytubebox'); ?>" name="submit" class="button button-primary" />
                                                    </td>
                                            </tr>
                                    </table>
                    </form>
            </div>
            <div class="wrap">
                    <h4><?php _e( 'Generate Shortcode','ytubebox' ); ?></h4>
                    <table>
                            <tr>
                                    <th>
                                            <?php _e( 'Enter Youtube URL', 'ytubebox' ); ?>
                                    </th>
                                    <td>
                                            <input type="text" name="youtubeurl" id="youtubeurl" size="80" />
                                    </td>
                            </tr>
                            <tr>
                                    <th>
                                            <?php _e( 'Height for Image Thumbnail','ytubebox' ); ?>
                                    </th>
                                    <td>
                                            <input type="text" name="t_height" id="t_height" />
                                    </td>	
                            </tr>
                            <tr>
                                    <th>
                                            <?php _e( 'Width for Image Thumbnail', 'ytubebox'); ?>
                                    </th>
                                    <td>
                                            <input type="text" name="t_width" id="t_width" />
                                    </td>	
                            </tr>
                            <tr>
                                    <th>

                                    </th>
                                    <td>
                                            <input type="button" name="getshortcode" value="<?php _e( 'Generate', 'ytubebox'); ?>" id="genrate" class="button button-primary"/>
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

}
global $youtubefancybox;
$youtubefancybox=new youtubefanybox();