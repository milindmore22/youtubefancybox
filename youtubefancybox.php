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
*/
/**
 * If You are admin you will get admin settings
 */
if ( is_admin() )
	require_once dirname( __FILE__ ) . '/admin.php';
/**
 * Adding Shortcode action filter
 */
add_shortcode('youtube','youtubefancybox_url');
add_action('wp_head', 'youtubefancybox_js_file');
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');

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
	
	if(!isset($attr['height'])){
		$attr['height']=get_option('youtube_height');
	}
	if(!isset($attr['width']))	{
		$attr['width']=get_option('youtube_width');
	}
	if(!isset($attr['videoid']))
	{
		if(isset($attr['url'])){
			preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $attr['url'], $matches);
			$attr['videoid']=$matches[0];
		}
	}
	
	if(get_option('autoplay'))
	{
		$autoplay=get_option('autoplay');
		if($autoplay=="yes")
		{
			$autoplay="1";
		}else{
			$autoplay="0";
		}
	}else{
		$autoplay="1";
	}
	
	if (isset($_SERVER['HTTPS']) &&
			($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
			isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
			$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		$protocol = 'https://';
	}
	else {
		$protocol = 'http://';
	}
	
	if(isset($attr['videoid']))
	{
	?>
	<a class='youtube' href="<?php echo $protocol; ?>www.youtube.com/embed/<?php echo $attr['videoid']; ?>?rel=0&autoplay=<?php echo $autoplay?>&wmode=transparent"><img src="<?php echo $protocol;?>img.youtube.com/vi/<?php echo $attr['videoid'];?>/0.jpg" width="<?php echo $attr['width']; ?>" height="<?php echo $attr['height']; ?>"/></a>
	<?php
	}else{
		echo "\n<br /><span style='clear:both;color:red'>Please Enter Youtube ID or Youtube URL as [youtube videoid='XXXXX']</span>";
	} 
}