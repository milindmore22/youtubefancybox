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
                            <table>
                                    <tr>
                                            <th align="left">
                                                    <?php _e( 'Enter Viemo URL', 'ytubebox' ); ?>
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

        }
        
}
