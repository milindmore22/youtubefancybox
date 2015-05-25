<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Youtube
 *
 * @author milind
 */
namespace YTubeFancy{
    class Youtube {
        /**
         *  Constuctor for youtube class
         */
        public function __construct() {
            
            if( is_admin() ){
                 add_action( 'admin_menu', array( $this,'youtubefancybox_plugin_menu' ) );
            }
            add_shortcode( 'youtube' , array( $this ,'youtubefancybox_url') );
        }
        
        /**
        * Adding submenu page for settings
        */
       function youtubefancybox_plugin_menu() {
               add_submenu_page('ytubefancybox' ,'Youtube FancyBox Options', 'Youtube', 'manage_options', 'ytube', array( $this,'youtubefancybox_options' ) );
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
                 ?>
                <div class="wrap">
                   
                        <h2><?php _e( 'Generate Youtube Shortcode','ytubebox' ); ?></h2>
                        <hr />
                        <table>
                                <tr>
                                        <th align="left">
                                                <?php _e( 'Enter Youtube URL', 'ytubebox' ); ?>
                                        </th>
                                        <td align="left">
                                                <input type="text" name="youtubeurl" id="youtubeurl" size="80" />
                                        </td>
                                </tr>
                                <tr>
                                        <th align="left">
                                                <?php _e( 'Height for Image Thumbnail','ytubebox' ); ?>
                                        </th>
                                        <td align="left">
                                                <input type="text" name="t_height" id="t_height" />
                                        </td>	
                                </tr>
                                <tr>
                                        <th align="left">
                                                <?php _e( 'Width for Image Thumbnail', 'ytubebox'); ?>
                                        </th>
                                        <td align="left">
                                                <input type="text" name="t_width" id="t_width" />
                                        </td>	
                                </tr>
                                <tr>
                                        <th align="left">

                                        </th>
                                        <td align="left">
                                                <input type="button" name="getshortcode" value="<?php _e( 'Generate', 'ytubebox'); ?>" id="genrate" class="button button-primary"/>
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