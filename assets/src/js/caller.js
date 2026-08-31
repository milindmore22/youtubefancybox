/**
 * Frontend script for initializing colorbox on video links.
 */
jQuery( function( $ ) {
	// Returns width/height strings that keep 16:9 inside the viewport with gutters.
	function responsiveDimensions() {
		const vw = window.innerWidth;
		const vh = window.innerHeight;
		const gutterH = vw < 600 ? 0.96 : 0.88;
		const gutterV = vw < 600 ? 0.90 : 0.85;
		let w = Math.round( vw * gutterH );
		let h = Math.round( w * ( 9 / 16 ) );
		if ( h > vh * gutterV ) {
			h = Math.round( vh * gutterV );
			w = Math.round( h * ( 16 / 9 ) );
		}
		return { width: w + 'px', height: h + 'px' };
	}

	const STYLE_CLASSES = [ 'ytfancybox-style-dark', 'ytfancybox-style-cinema' ];

	$( '.youtube, .vimeo' ).colorbox( {
		iframe: true,
		// evaluated fresh on each open and window resize
		width() {
			return responsiveDimensions().width;
		},
		height() {
			return responsiveDimensions().height;
		},
		onOpen() {
			$( 'html' ).addClass( 'ytfancybox-open' );
			const $block = $( this ).closest( '.youtubefancybox-block' );
			if ( $block.hasClass( 'is-style-dark' ) ) {
				$( 'body' ).addClass( 'ytfancybox-style-dark' );
			} else if ( $block.hasClass( 'is-style-cinema' ) ) {
				$( 'body' ).addClass( 'ytfancybox-style-cinema' );
			}
		},
		onClosed() {
			$( 'html' ).removeClass( 'ytfancybox-open' );
			$( 'body' ).removeClass( STYLE_CLASSES.join( ' ' ) );
		},
		onResize() {
			$.colorbox.resize( responsiveDimensions() );
		},
	} );
} );

