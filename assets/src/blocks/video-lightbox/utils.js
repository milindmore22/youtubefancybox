/**
 * URL parsing and thumbnail helpers for the Video Lightbox block.
 *
 * @module youtubefancybox/blocks/video-lightbox/utils
 */

/**
 * Parse a video URL and return its provider and video ID.
 *
 * @param {string} url Raw URL entered by the user.
 * @return {{provider: string, id: string}|null} Parsed result, or null when unsupported.
 */
export function parseVideoUrl( url ) {
	const trimmed = ( url || '' ).trim();

	if ( ! trimmed ) {
		return null;
	}

	const youtubeId = parseYouTubeUrl( trimmed );

	if ( youtubeId ) {
		return { provider: 'youtube', id: youtubeId };
	}

	const vimeoId = parseVimeoUrl( trimmed );

	if ( vimeoId ) {
		return { provider: 'vimeo', id: vimeoId };
	}

	return null;
}

/**
 * Extract a YouTube video ID from a URL.
 *
 * @param {string} url YouTube URL.
 * @return {string|null} Video ID, or null when not a YouTube URL.
 */
function parseYouTubeUrl( url ) {
	const patterns = [
		/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/))([\w-]{6,})/i,
		/(?:youtu\.be\/)([\w-]{6,})/i,
	];

	for ( const pattern of patterns ) {
		const match = url.match( pattern );

		if ( match ) {
			return match[ 1 ];
		}
	}

	return null;
}

/**
 * Extract a Vimeo video ID from a URL.
 *
 * @param {string} url Vimeo URL.
 * @return {string|null} Video ID, or null when not a Vimeo URL.
 */
function parseVimeoUrl( url ) {
	const match = url.match(
		/https?:\/\/(?:www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/[^/]+\/videos\/|album\/\d+\/video\/|video\/|)(\d+)/i,
	);

	return match ? match[ 1 ] : null;
}

/**
 * Build the default YouTube thumbnail URL for a video ID.
 *
 * @param {string} videoId YouTube video ID.
 * @return {string} Thumbnail URL.
 */
export function getYouTubeThumbnail( videoId ) {
	return `https://img.youtube.com/vi/${ videoId }/hqdefault.jpg`;
}

/**
 * Fetch the default Vimeo thumbnail URL via the oEmbed API.
 *
 * @param {string} videoId Vimeo video ID.
 * @return {Promise<string>} Thumbnail URL, or empty string on failure.
 */
export async function getVimeoThumbnail( videoId ) {
	try {
		const response = await fetch(
			`https://vimeo.com/api/oembed.json?url=${ encodeURIComponent(
				`https://vimeo.com/${ videoId }`,
			) }`,
		);

		if ( ! response.ok ) {
			return '';
		}

		const data = await response.json();

		return data.thumbnail_url || '';
	} catch ( error ) {
		return '';
	}
}
