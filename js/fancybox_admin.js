/**
 * Admin Scripts for Youtube Fancybox.
 */

jQuery( document ).ready( function ( $ ) {
    /**
     * Generate shortcode for Youtube
     */
    $( document ).on( 'click', '#genrate', function () {
	var url = $( '#youtubeurl' ).val();
	var height = $( '#t_height' ).val();
	var width = $( '#t_width' ).val();
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
	    $( '#shortcode' ).val( str );
	    $( '#shortcode' ).trigger( 'select' );
	}
    } );

    /**
     * Select Shortcode on click
     **/
    $( document ).on( 'click', '#shortcode', function () {
	$( "#shortcode" ).trigger( 'select' );
    } );

    /**
     *  Genrate shortcode for Viemo videos
     **/
    $( document ).on( 'click', '#genratevimeo', function () {
	var url = $( '#vimeourl' ).val();
	var height = $( '#t_height' ).val();
	var width = $( '#t_width' ).val();
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
	    $( '#shortcode' ).val( str );
	    $( '#shortcode' ).trigger( 'select' );
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
