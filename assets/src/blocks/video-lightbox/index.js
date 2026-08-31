/**
 * Video Lightbox for YouTube/Vimeo block.
 *
 * @package
 */

import { registerBlockType, registerBlockStyle } from '@wordpress/blocks';
import { __, sprintf } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import {
	Button,
	Modal,
	Notice,
	PanelBody,
	RangeControl,
	Spinner,
	TextControl,
	ToggleControl,
	ToolbarButton,
	ToolbarGroup,
} from '@wordpress/components';
import {
	BlockControls,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	useBlockProps,
} from '@wordpress/block-editor';
import { replace, trash } from '@wordpress/icons';
import {
	getVimeoThumbnail,
	getYouTubeThumbnail,
	parseVideoUrl,
} from './utils';

const BLOCK_NAME = 'youtubefancybox/video-lightbox';

/**
 * Play button overlay markup shared between the placeholder and the preview.
 *
 * @return {import('react').Element} Play icon.
 */
function PlayIcon() {
	return (
		<span className="youtubefancybox-block__play" aria-hidden="true">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 64 64"
				width="64"
				height="64"
				focusable="false"
			>
				<circle cx="32" cy="32" r="32" fill="rgba(0, 0, 0, 0.6)"></circle>
				<path d="M26 20l18 12-18 12z" fill="#fff"></path>
			</svg>
		</span>
	);
}

/**
 * Editor preview for a configured block.
 *
 * Non-interactive: clicking the thumbnail never opens the URL popup.
 *
 * @param {Object} props        Component props.
 * @param {string} props.src    Thumbnail URL.
 * @param {string} props.alt    Thumbnail alt text.
 * @param {number} props.width  Image width.
 * @param {number} props.height Image height.
 * @return {import('react').Element} Preview markup.
 */
function Preview( { src, alt, width, height } ) {
	return (
		<div
			className="youtubefancybox-block__preview"
			style={ { aspectRatio: `${ width } / ${ height }` } }
		>
			<img
				src={ src }
				alt={ alt || __( 'Video thumbnail', 'youtubefancybox' ) }
				width={ width }
				height={ height }
				loading="lazy"
				decoding="async"
			/>
			<PlayIcon />
		</div>
	);
}

/**
 * Block edit component.
 *
 * @param {Object}   props               Component props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Update block attributes.
 * @return {import('react').Element} Edit markup.
 */
function Edit( { attributes, setAttributes } ) {
	const {
		videoUrl,
		videoId,
		provider,
		thumbnailUrl,
		thumbnailAlt,
		height,
		width,
		autoplay,
	} = attributes;

	const [ isModalOpen, setIsModalOpen ] = useState( false );
	const [ urlDraft, setUrlDraft ] = useState( videoUrl );
	const [ urlError, setUrlError ] = useState( '' );
	const [ vimeoThumb, setVimeoThumb ] = useState( '' );
	const [ vimeoLoading, setVimeoLoading ] = useState( false );

	useEffect( () => {
		if ( provider !== 'vimeo' || ! videoId || thumbnailUrl ) {
			return undefined;
		}

		let cancelled = false;

		setVimeoLoading( true );
		setVimeoThumb( '' );

		getVimeoThumbnail( videoId ).then( ( url ) => {
			if ( cancelled ) {
				return;
			}

			setVimeoThumb( url );
			setVimeoLoading( false );
		} );

		return () => {
			cancelled = true;
		};
	}, [ provider, videoId, thumbnailUrl ] );

	const openModal = () => {
		setUrlDraft( videoUrl );
		setUrlError( '' );
		setIsModalOpen( true );
	};

	const handleAddVideo = () => {
		const parsed = parseVideoUrl( urlDraft );

		if ( ! parsed ) {
			setUrlError(
				__(
					'That does not look like a valid YouTube or Vimeo URL. Please paste the full link.',
					'youtubefancybox',
				),
			);
			return;
		}

		setAttributes( {
			videoUrl: urlDraft.trim(),
			videoId: parsed.id,
			provider: parsed.provider,
		} );
		setUrlError( '' );
		setIsModalOpen( false );
	};

	const handleRemoveVideo = () => {
		setAttributes( {
			videoUrl: '',
			videoId: '',
			provider: '',
		} );
	};

	const hasVideo = Boolean( videoId && provider );

	let previewSrc = vimeoThumb;

	if ( provider === 'youtube' ) {
		previewSrc = getYouTubeThumbnail( videoId );
	}

	if ( thumbnailUrl ) {
		previewSrc = thumbnailUrl;
	}

	let providerLabel = __( 'none yet', 'youtubefancybox' );

	if ( provider === 'youtube' ) {
		providerLabel = __( 'YouTube', 'youtubefancybox' );
	} else if ( provider === 'vimeo' ) {
		providerLabel = __( 'Vimeo', 'youtubefancybox' );
	}

	const blockProps = useBlockProps( {
		className: 'youtubefancybox-block',
		style: width > 0 ? { maxWidth: `${ width }px` } : {},
	} );

	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						icon={ replace }
						label={ __( 'Change video', 'youtubefancybox' ) }
						onClick={ openModal }
					/>
					<ToolbarButton
						icon={ trash }
						label={ __( 'Remove video', 'youtubefancybox' ) }
						onClick={ handleRemoveVideo }
						disabled={ ! hasVideo }
					/>
				</ToolbarGroup>
			</BlockControls>

			<InspectorControls>
				<PanelBody title={ __( 'Video', 'youtubefancybox' ) }>
					<TextControl
						label={ __( 'Video URL', 'youtubefancybox' ) }
						value={ videoUrl }
						readOnly
						help={ __(
							'Use “Edit video URL” to change the video.',
							'youtubefancybox',
						) }
					/>
					<Button variant="secondary" onClick={ openModal }>
						{ __( 'Edit video URL', 'youtubefancybox' ) }
					</Button>
				</PanelBody>

				<PanelBody title={ __( 'Placeholder image', 'youtubefancybox' ) }>
					<p>
						{ __(
							'By default the provider thumbnail is shown. Choose a custom image to use instead.',
							'youtubefancybox',
						) }
					</p>
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( image ) =>
								setAttributes( {
									thumbnailId: image.id,
									thumbnailUrl: image.url,
									thumbnailAlt: image.alt || '',
								} )
							}
							allowedTypes={ [ 'image' ] }
							value={ attributes.thumbnailId }
							render={ ( { open } ) => (
								<>
									<Button variant="secondary" onClick={ open }>
										{ thumbnailUrl
											? __( 'Replace image', 'youtubefancybox' )
											: __( 'Choose custom image', 'youtubefancybox' ) }
									</Button>
									{ thumbnailUrl && (
										<Button
											variant="link"
											isDestructive
											onClick={ () =>
												setAttributes( {
													thumbnailId: 0,
													thumbnailUrl: '',
													thumbnailAlt: '',
												} )
											}
										>
											{ __( 'Use default thumbnail', 'youtubefancybox' ) }
										</Button>
									) }
								</>
							) }
						/>
					</MediaUploadCheck>
				</PanelBody>

				<PanelBody title={ __( 'Display', 'youtubefancybox' ) }>
					<RangeControl
						label={ __( 'Width (px)', 'youtubefancybox' ) }
						value={ width || undefined }
						onChange={ ( value ) => setAttributes( { width: value ?? 0 } ) }
						min={ 100 }
						max={ 1600 }
						step={ 10 }
						allowReset
						help={ ! width ? __( 'Follows alignment width.', 'youtubefancybox' ) : undefined }
					/>
					<RangeControl
						label={ __( 'Height (px)', 'youtubefancybox' ) }
						value={ height || undefined }
						onChange={ ( value ) => setAttributes( { height: value ?? 0 } ) }
						min={ 56 }
						max={ 1600 }
						step={ 10 }
						allowReset
						help={ ! height ? __( '16:9 aspect ratio used.', 'youtubefancybox' ) : undefined }
					/>
					<ToggleControl
						label={ __( 'Autoplay when opened', 'youtubefancybox' ) }
						help={
							autoplay
								? __(
									'The video starts playing as soon as the lightbox opens.',
									'youtubefancybox',
								)
								: __(
									'The viewer presses play inside the lightbox.',
									'youtubefancybox',
								)
						}
						checked={ autoplay }
						onChange={ ( value ) => setAttributes( { autoplay: value } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ ! hasVideo ? (
					<button
						type="button"
						className="youtubefancybox-block__placeholder"
						onClick={ openModal }
					>
						<span className="youtubefancybox-block__placeholder-icon" aria-hidden="true">
							<svg
								xmlns="http://www.w3.org/2000/svg"
								viewBox="0 0 24 24"
								width="48"
								height="48"
								focusable="false"
							>
								<circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" strokeWidth="1.5"></circle>
								<path d="M10 8l6 4-6 4z" fill="currentColor"></path>
							</svg>
						</span>
						<span className="youtubefancybox-block__placeholder-title">
							{ __( 'Add YouTube or Vimeo video', 'youtubefancybox' ) }
						</span>
						<span className="youtubefancybox-block__placeholder-hint">
							{ __( 'Click to add a video from a URL.', 'youtubefancybox' ) }
						</span>
					</button>
				) : (
					<>
						{ previewSrc ? (
							<Preview
								src={ previewSrc }
								alt={ thumbnailAlt }
								width={ width }
								height={ height }
							/>
						) : (
							<div className="youtubefancybox-block__loading">
								{ vimeoLoading && <Spinner /> }
								<span>
									{ __(
										'Loading video thumbnail…',
										'youtubefancybox',
									) }
								</span>
							</div>
						) }
					</>
				) }
			</div>

			{ isModalOpen && (
				<Modal
					title={ __( 'Add video from URL', 'youtubefancybox' ) }
					onRequestClose={ () => setIsModalOpen( false ) }
					className="ytubefancybox-modal"
				>
					<TextControl
						label={ __( 'Video URL', 'youtubefancybox' ) }
						value={ urlDraft }
						onChange={ setUrlDraft }
						placeholder="https://www.youtube.com/watch?v=… or https://vimeo.com/…"
						help={ __(
							'Paste the full link to a YouTube or Vimeo video.',
							'youtubefancybox',
						) }
						onSubmit={ handleAddVideo }
					/>
					{ urlError && (
						<Notice status="error" isDismissible={ false }>
							{ urlError }
						</Notice>
					) }
					<p>
						{ sprintf(
							/* translators: %s: Provider name. */
							__(
								'Provider detected: %s.',
								'youtubefancybox',
							),
							providerLabel,
						) }
					</p>
					<div className="ytubefancybox-modal-actions">
						<Button variant="primary" onClick={ handleAddVideo }>
							{ __( 'Add video', 'youtubefancybox' ) }
						</Button>
						<Button variant="tertiary" onClick={ () => setIsModalOpen( false ) }>
							{ __( 'Cancel', 'youtubefancybox' ) }
						</Button>
					</div>
				</Modal>
			) }
		</>
	);
}

registerBlockType( BLOCK_NAME, {
	edit: Edit,
	save: () => null,
} );

registerBlockStyle( BLOCK_NAME, [
	{ name: 'default', label: __( 'Default', 'youtubefancybox' ), isDefault: true },
	{ name: 'dark', label: __( 'Dark', 'youtubefancybox' ) },
	{ name: 'cinema', label: __( 'Cinema', 'youtubefancybox' ) },
] );
