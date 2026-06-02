/**
 * Admin Scripts for Video Lightbox.
 */

jQuery( document ).ready( function( $ ) {
	/**
	 * Generate shortcode for Video Lightbox
	 */
	$( document ).on( 'click', '#genrate', function() {
		const url = $( '#youtubeurl' ).val();
		const height = $( '#t_height' ).val();
		const width = $( '#t_width' ).val();
		let str = '';
		let videoid = '';

		if ( url ) {
			videoid = youtube_parser( url );
			str = '[youtube videoid="' + videoid + '"';

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
	 */
	$( document ).on( 'click', '#shortcode', function() {
		$( '#shortcode' ).trigger( 'select' );
	} );

	/**
	 * Generate shortcode for Vimeo videos
	 */
	$( document ).on( 'click', '#genratevimeo', function() {
		const url = $( '#vimeourl' ).val();
		const height = $( '#t_height' ).val();
		const width = $( '#t_width' ).val();
		let str = '';
		let videoid = '';

		if ( url ) {
			videoid = vimeo_parser( url );
			str = '[vimeo videoid="' + videoid + '"';

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
 * Gets YouTube ID from URL.
 *
 * @param {string} url YouTube URL.
 * @return {string|void} YouTube video ID.
 */
/* eslint-disable-next-line no-unused-vars */
function youtube_parser( url ) {
	const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&?]*).*/;
	const match = url.match( regExp );
	if ( match && match[ 7 ].length === 11 ) {
		return match[ 7 ];
	}
	alert( fancybox_admin_obj.youtube_alert ); // eslint-disable-line no-alert
}

/**
 * Gets Vimeo ID from URL.
 *
 * @param {string} url Vimeo URL.
 * @return {string|void} Vimeo video ID.
 */
/* eslint-disable-next-line no-unused-vars */
function vimeo_parser( url ) {
	const regExp = /^.*(www\.)?vimeo.com\/(\d+)($|\/)/;
	const match = url.match( regExp );
	if ( match ) {
		return match[ 2 ];
	}
	alert( fancybox_admin_obj.viemo_alert ); // eslint-disable-line no-alert
}
