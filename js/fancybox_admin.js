jQuery( document ).ready( function () {
    /**
     * Generate shortcode for Youtube
     */
    jQuery( "#genrate" ).click( function () {
	var url = jQuery( "#youtubeurl" ).val();
	var height = jQuery( "#t_height" ).val();
	var width = jQuery( "#t_width" ).val();
	if ( url ) {
	    var videoid = youtube_parser( url );
	    var str = '[youtube videoid="' + videoid + '"';

	    if ( height ) {
		str += ' height="' + height + '"';
	    }
	    if ( width ) {
		str += ' width="' + width + '"';
	    }
	    str += ']';
	}
	if ( videoid ) {
	    jQuery( "#shortcode" ).val( str );
	    jQuery( "#shortcode" ).select();
	}
    } );
    /**
     * Select Shortcode on click
     **/
    jQuery( "#shortcode" ).click( function () {
	jQuery( "#shortcode" ).select();
    } );

    /**
     *  Genrate shortcode for Viemo videos
     **/
    jQuery( "#genratevimeo" ).click( function () {
	var url = jQuery( "#vimeourl" ).val();
	var height = jQuery( "#t_height" ).val();
	var width = jQuery( "#t_width" ).val();
	if ( url ) {
	    var videoid = vimeo_parser( url );
	    var str = '[vimeo videoid="' + videoid + '"';

	    if ( height ) {
		str += ' height="' + height + '"';
	    }
	    if ( width ) {
		str += ' width="' + width + '"';
	    }
	    str += ']';
	}
	if ( videoid ) {
	    jQuery( "#shortcode" ).val( str );
	    jQuery( "#shortcode" ).select();
	}
    } );
} );
/**
 * function for gets youtube id from url
 **/
function youtube_parser( url ) {
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match( regExp );
    if ( match && match[7].length == 11 ) {
	return match[7];
    } else {
	alert( fancybox_admin_obj.youtube_alert );
    }
}
/**
 * Functin gets viemo id from url
 * @returns {undefined}
 */
function vimeo_parser( url ) {
    var regExp = /^.*(www\.)?vimeo.com\/(\d+)($|\/)/;
    var match = url.match( regExp );
    if ( match ) {
	return match[2];
    } else {
	alert( fancybox_admin_obj.viemo_alert );
    }
}