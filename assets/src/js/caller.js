/**
 * Frontend script for initializing colorbox on video links.
 */
jQuery( function( $ ) {
	// Returns width/height strings that keep 16:9 inside the viewport with gutters.
	function responsiveDimensions() {
		var vw = window.innerWidth;
		var vh = window.innerHeight;
		var gutterH = vw < 600 ? 0.96 : 0.88;
		var gutterV = vw < 600 ? 0.90 : 0.85;
		var w = Math.round( vw * gutterH );
		var h = Math.round( w * ( 9 / 16 ) );
		if ( h > vh * gutterV ) {
			h = Math.round( vh * gutterV );
			w = Math.round( h * ( 16 / 9 ) );
		}
		return { width: w + 'px', height: h + 'px' };
	}

	$( '.youtube, .vimeo' ).colorbox( {
		iframe: true,
		// evaluated fresh on each open and window resize
		width:  function() { return responsiveDimensions().width; },
		height: function() { return responsiveDimensions().height; },
		onOpen: function() {
			$( 'html' ).addClass( 'ytfancybox-open' );
		},
		onClosed: function() {
			$( 'html' ).removeClass( 'ytfancybox-open' );
		},
		onResize: function() {
			$.colorbox.resize( responsiveDimensions() );
		},
	} );
} );

