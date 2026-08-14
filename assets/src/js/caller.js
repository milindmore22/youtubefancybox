/**
 * Frontend script for initializing colorbox on video links.
 */
jQuery( function( $ ) {
	$( '.youtube, .vimeo' ).colorbox( {
		iframe: true,
		width: '80%',
		height: '80%',
	} );
} );

