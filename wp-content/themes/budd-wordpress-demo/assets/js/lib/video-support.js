/**
 * This class allows us to embed youtube/vimeo videos into our theme
 */
class VideoSupport {
	/**
	 * Video support constructor
	 * @param {HTMLElement} module
	 */
	constructor( module ) {
		this.videos = module.querySelectorAll(
			'.js-support-video, .js-support-video-local'
		);
		this.init();
	}
	/**
	 * VideoSupport init method.
	 * You should place here any new methods.
	 */
	init() {
		this.playVideo();
	}
	/**
	 * This method adds a click event to play the button
	 */
	playVideo() {
		this.videos.forEach( ( video ) => {
			const videoId = video.getAttribute( 'video-id' );
			let moduleId = video.getAttribute( 'id' );
			const service = video.getAttribute( 'service' );
			const playBtn = video.querySelector( '.js-support-video-play' );

			// Check if it's a local video
			const isLocal = video.classList.contains(
				'js-support-video-local'
			);

			playBtn.addEventListener( 'click', () => {
				playBtn.remove();

				if ( isLocal ) {
					this.local( video );
				} else {
					switch ( service ) {
						case 'youtube':
							moduleId = video.getAttribute( 'youtube-dom' );
							this.youtube( videoId, moduleId );
							break;
						case 'vimeo':
							this.vimeo( videoId, moduleId );
							break;
					}
				}
			} );
		} );
	}

	/**
	 * This method handles local video playback
	 * @param {HTMLElement} videoContainer The video container element
	 */
	local( videoContainer ) {
		const videoElement = videoContainer.querySelector(
			'.wcb-support-video__video'
		);
		const placeholder = videoContainer.querySelector( 'img' );

		if ( videoElement ) {
			// Set the actual source from data-src
			videoElement.src = videoElement.getAttribute( 'data-src' );

			// Show video and hide placeholder
			videoElement.classList.remove( 'wcb-hidden' );
			if ( placeholder ) {
				placeholder.style.display = 'none';
			}

			// Play the video
			videoElement.play().catch( () => {
				// If video playback fails, show the placeholder again
				if ( placeholder ) {
					placeholder.style.display = 'block';
				}
				videoElement.classList.add( 'wcb-hidden' );
			} );
		}
	}

	/**
	 * This method creates an script that outputs a youtube iframe video
	 * @param {string} videoId  e.g: '1'
	 * @param {string} moduleId e.g: '3'
	 */
	youtube( videoId, moduleId ) {
		if ( ! document.querySelector( '.youtube-api' ) ) {
			const api = document.createElement( 'script' );
			api.src = 'https://www.youtube.com/iframe_api';
			api.setAttribute( 'class', 'youtube-api' );
			document.head.appendChild( api );

			api.onload = function () {
				window.YT.ready( function () {
					// eslint-disable-next-line no-unused-vars
					const player = new YT.Player( moduleId, {
						height: '360',
						width: '640',
						videoId,
						playerVars: {
							controls: 1,
							showinfo: 0,
							rel: 0,
							enablejsapi: 1,
							autoplay: 1,
							wmode: 'transparent',
						},
						events: {
							onReady( e ) {
								e.target.playVideo();
								e.target.setPlaybackQuality( 'hd720' );
							},
						},
					} );
				} );
			};
		} else {
			// eslint-disable-next-line no-unused-vars
			const player = new YT.Player( moduleId, {
				height: '360',
				width: '640',
				videoId,
				playerVars: {
					controls: 1,
					showinfo: 0,
					rel: 0,
					enablejsapi: 1,
					autoplay: 1,
					wmode: 'transparent',
				},
				events: {
					onReady( e ) {
						e.target.playVideo();
						e.target.setPlaybackQuality( 'hd720' );
					},
				},
			} );
		}
	}
	/**
	 * This method creates an script that outputs a vimeo iframe video
	 * @param {string} videoId
	 * @param {string} moduleId
	 */
	vimeo( videoId, moduleId ) {
		if ( ! document.querySelector( '.vimeo-api' ) ) {
			const api = document.createElement( 'script' );
			api.src = 'https://player.vimeo.com/api/player.js';
			api.setAttribute( 'class', 'vimeo-api' );
			document.head.appendChild( api );

			api.onload = function () {
				const options = {
					id: videoId,
					width: 640,
					loop: false,
				};

				const player = new Vimeo.Player( moduleId, options );

				player.play();
			};
		} else {
			const options = {
				id: videoId,
				width: 640,
				loop: false,
			};
			const player = new Vimeo.Player( moduleId, options );
			player.play();
		}
	}
}

export default VideoSupport;
