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

namespace YTubeFancy{
    class youtubefanybox{

        public function __construct(){
            /**
             * If You are admin you will get admin settings
             */
            if ( is_admin() ){
                   /**
                    * Adding action calling plugin menu and loading header file
                    */
                   add_action( 'admin_menu', array( $this,'youtubefancybox_plugin_main_menu' ) );
                   add_action( 'admin_head', array( $this, 'youtubefancybox_adminjs_file' ) );
            }
            /**
             * Adding Shortcode action filter
             */
            add_action('wp_head', array ( $this, 'youtubefancybox_js_file' ) );
            add_filter( 'widget_text', array ( $this ,'shortcode_unautop' ) );
            add_filter( 'widget_text', array ( $this, 'do_shortcode' ) );
            add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
        }
        
        function youtubefancybox_plugin_main_menu(){
            add_menu_page( 'YouTube FancyBox', 'YouTube FancyBox', 'manage_options', 'ytubefancybox', array( $this ,'ytubefancybox_default_settings' ),'', 6 );
        }

        /**
         * function will make plugin translation ready
         * @todo created pdo or mo file for translation
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
        * Enqueue scritps js nessary
        */
       function youtubefancybox_js_file(){
                wp_enqueue_style('colorboxcss',plugins_url('css/colorbox.css',__FILE__));
                wp_enqueue_script('jquery');
                wp_enqueue_script('colorboxjs', plugins_url('js/jquery.colorbox-min.js',__FILE__) );
                wp_enqueue_script('colorboxcaller', plugins_url('js/caller.js',__FILE__) );
       }
       
       function ytubefancybox_default_settings(){
           
           if ( !current_user_can( 'manage_options' ) )  {
                        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
                }

                if($_SERVER['REQUEST_METHOD']=="POST"){
                        if(get_option('youtube_height')){
                                update_option('youtube_height', filter_input( INPUT_POST, 'youtube_height' ) );
                        }else{
                        add_option( 'youtube_height', filter_input( INPUT_POST, 'youtube_height' ) , '', 'yes' );
                        }
                        if(get_option('youtube_width')){
                                update_option('youtube_width', filter_input( INPUT_POST, 'youtube_width' ) );
                        }else{
                                add_option('youtube_width',  filter_input(INPUT_POST, 'youtube_width' ) , '', 'yes' );
                        }
                        if(get_option('autoplay')){
                                update_option('autoplay', filter_input ( INPUT_POST, 'autoplay') );
                        }else{
                                add_option('autoplay', filter_input( INPUT_POST, 'autoplay'),'', 'yes' );
                        }

                }
           ?>
<style type="text/css">
    fieldset{
        border: 1px solid;
    }
</style>
                <div class="wrap">
                     <fieldset>
                         <legend><?php _e( 'Set Default options', 'ytubebox'); ?></legend>
                        
                        <form action="<?php echo $_SERVER['PHP_SELF']?>?page=ytubefancybox" method="post">
                                        <table>
                                                <tr>
                                                        <th align="left"><?php _e( 'Height' , 'ytubebox' ) ?></th>
                                                        <td align="left">
                                                            <input type="text" name="youtube_height" value="<?php echo get_option('youtube_height');?>" />
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <th align="left"><?php _e( 'Width', 'ytubebox' ); ?></th>
                                                        <td align="left">
                                                            <input type="text" name="youtube_width" value="<?php echo get_option('youtube_width'); ?>" />
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <th align="left"><?php _e( 'Autoplay','ytubebox'); ?></th>
                                                        <td align="left">
                                                            <input type="radio" name="autoplay" value="yes" <?php if(get_option('autoplay')=="yes"){echo 'checked="checked"';}?>/> Yes <input type="radio" name="autoplay" value="no" <?php if(get_option('autoplay')=="no"){echo 'checked="checked"';}?> /> No
                                                        </td>	
                                                </tr>
                                                <tr>
                                                        <th align="left"></th>
                                                        <td align="left">
                                                            <input type="submit" value="<?php _e( 'Save','ytubebox'); ?>" name="submit" class="button button-primary" />
                                                        </td>
                                                </tr>
                                        </table>
                        </form>
                        </fieldset>
                </div>

           <?php
       }
 
    }
}
namespace{
    
    /**
     * include lib files
     */
    foreach( glob ( plugin_dir_path(__FILE__) . "/lib/*.php" ) as $lib_filename ) {
        require_once( $lib_filename );
    }
    
    global $fancybox, $youtube, $viemo;
    $fancybox=new \YTubeFancy\youtubefanybox();
    $youtube=new \YTubeFancy\Youtube();
    $viemo=new \YTubeFancy\Vimeo();
}