/**
 * Admin Scripts for Video Lightbox.
 */

/**
 * Extracts YouTube video ID from URL.
 *
 * @param {string} url YouTube URL.
 * @return {string|void} YouTube video ID.
 */
function youtube_parser( url ) {
	const match = url.match( /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/ );
	if ( match && match[ 7 ].length === 11 ) {
		return match[ 7 ];
	}
	alert( fancybox_admin_obj.youtube_alert ); // eslint-disable-line no-alert
}

/**
 * Extracts Vimeo video ID from URL.
 *
 * @param {string} url Vimeo URL.
 * @return {string|void} Vimeo video ID.
 */
function vimeo_parser( url ) {
	const match = url.match( /(?:https?:\/\/)?(?:www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/[^/]+\/videos\/|album\/(?:\d+\/)?video\/|video\/|)(\d+)/i );
	if ( match && match[ 1 ] ) {
		return match[ 1 ];
	}
	alert( fancybox_admin_obj.viemo_alert ); // eslint-disable-line no-alert
}

jQuery( function( $ ) {
	/**
	 * Build shortcode string from video parameters.
	 *
	 * @param {string} tag     Shortcode tag (youtube or vimeo).
	 * @param {string} videoid Video ID.
	 * @param {string} height  Thumbnail height.
	 * @param {string} width   Thumbnail width.
	 * @return {string} Generated shortcode.
	 */
	function buildShortcode( tag, videoid, height, width ) {
		let shortcode = `[${ tag } videoid="${ videoid }"`;
		if ( height ) {
			shortcode += ` height="${ height }"`;
		}
		if ( width ) {
			shortcode += ` width="${ width }"`;
		}
		shortcode += ']';
		return shortcode;
	}

	/**
	 * Generate shortcode for YouTube videos.
	 */
	$( document ).on( 'click', '#genrate', function() {
		const url = $( '#youtubeurl' ).val().trim();
		if ( ! url ) {
			return;
		}

		const videoid = youtube_parser( url );
		if ( videoid ) {
			const height = $( '#t_height' ).val().trim();
			const width = $( '#t_width' ).val().trim();
			$( '#shortcode' )
				.val( buildShortcode( 'youtube', videoid, height, width ) )
				.trigger( 'select' );
		}
	} );

	/**
	 * Generate shortcode for Vimeo videos.
	 */
	$( document ).on( 'click', '#genratevimeo', function() {
		const url = $( '#vimeourl' ).val().trim();
		if ( ! url ) {
			return;
		}

		const videoid = vimeo_parser( url );
		if ( videoid ) {
			const height = $( '#t_height' ).val().trim();
			const width = $( '#t_width' ).val().trim();
			$( '#shortcode' )
				.val( buildShortcode( 'vimeo', videoid, height, width ) )
				.trigger( 'select' );
		}
	} );

	/**
	 * Select Shortcode on click.
	 */
	$( document ).on( 'click', '#shortcode', function() {
		$( this ).trigger( 'select' );
	} );
} );

