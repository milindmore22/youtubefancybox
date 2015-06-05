<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Vimeo
 *
 * @author milind
 */

namespace YTubeFancy{

        class Vimeo {

            /**
             *  Constuctor for viemo class
             */
            public function __construct() {

                if( is_admin() ){
                     add_action( 'admin_menu', array( $this,'viemofancybox_plugin_menu' ) );
                }
                add_shortcode( 'viemo' , array( $this ,'viemofancybox_url') );
            }

           /**
            * Adding submenu page for settings
            */
           function viemofancybox_plugin_menu() {
                   add_submenu_page('ytubefancybox' ,'Viemo FancyBox Options', 'Viemo', 'manage_options', 'viemo', array( $this,'viemofancybox_options' ) );
           }

           /**
            * function to captrue settings and project form for admin settings 
            */
           function viemofancybox_options(){
                    ?>
                    <div class="wrap">
                            <h2><?php _e( 'Generate Viemo Shortcode','ytubebox' ); ?></h2>
                            <hr />
                            <table class="form-table">
                                    <tr>
                                            <th align="left">
                                                    <?php _e( 'Enter Viemo URL', 'ytubebox' ); ?>
                                            </th>
                                            <td align="left">
                                                    <input type="text" id="viemourl" size="80" />
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
                                                    <input type="button" name="getshortcode" value="<?php _e( 'Generate', 'ytubebox'); ?>" id="genrateviemo" class="button button-primary"/>
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
           
            /* function execute shortcode
             * @param array $attr
             * @return html
             */
            function viemofancybox_url($attr){
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
                    /**
                     * get thumbnail for viemo video
                     */
                    $json = file_get_contents("http://vimeo.com/api/v2/video/".$attr['videoid'].".json");
                    $obj = json_decode($json);
                    //var_dump ( $obj[] );
                    $thumbnail_url=$obj[0]->thumbnail_large;
                ?>
                    <a class="vimeo" href="<?php echo $protocol; ?>player.vimeo.com/video/<?php echo $attr['videoid']; ?>">
                        <img src="<?php echo $thumbnail_url; ?>" width="<?php echo $attr['width']; ?>" height="<?php echo $attr['height']; ?>"/>
                    </a>
                <?php
                }else{
                        echo "\n<br /><span style='clear:both;color:red'>". __( "Please Enter Viemo ID or Viemo URL as [viemo videoid='XXXXX']","ytubebox")."</span>";
                } 
            }

        }
        
}
