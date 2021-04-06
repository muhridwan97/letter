import videojs from 'video.js';

export default function () {
	const viewerWrapper = document.getElementById('media-viewer-wrapper');
	viewerWrapper.addEventListener('contextmenu', event => event.preventDefault());

	// Video player
	if ($('#app-video-player').length) {
		const options = {
			fluid: true,
			responsive: true
		};
		const player = videojs('app-video-player', options, function onPlayerReady() {
			videojs.log('Player is ready!');
			this.on('ended', function () {
				videojs.log('Player is ended');
			});
		});
	}

	// Google viewer retry
	const googleViewer = $('iframe#google-viewer');
	const googleViewerNotResponding = $('#google-viewer-not-responding');
	const timeOutWaiting = 30 * 1000; // 30 seconds
	let totalRetry = 0;
	let isGoogleViewerLoaded = false;

	googleViewer.on('load', function () {
		isGoogleViewerLoaded = true;
		clearInterval(embedGoogleViewerCheck);
	});

	let embedGoogleViewerCheck = setInterval(function () {
		if (totalRetry >= 2) {
			clearInterval(embedGoogleViewerCheck);
			googleViewerNotResponding.show();
		} else {
			if (googleViewer.contents().find("body").contents().length === 0) {
				totalRetry += 1;
				googleViewer.attr('src', googleViewer.attr('src'));
				console.log('retry', totalRetry, googleViewer.attr('src'));
			}
		}
	}, timeOutWaiting);
};
