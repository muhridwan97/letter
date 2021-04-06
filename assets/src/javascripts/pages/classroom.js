export default function () {
	scrollToNav();

	function scrollToNav() {
		const urlParams = new URLSearchParams(window.location.search);
		const lesson = urlParams.get('lesson');
		const exercise = urlParams.get('exercise');

		let scrollToEl;
		if (lesson) {
			scrollToEl = 'nav-lesson-' + lesson;
		} else if (exercise) {
			scrollToEl = 'nav-exercise-' + exercise;
		} else {
			const paths = window.location.pathname.split("/");
			for (let i = 0; i < paths.length; i++) {
				if (paths[i] === 'course' && i < paths.length - 1) {
					scrollToEl = 'nav-course-' + paths[i + 1];
					break;
				}
			}
		}

		if (scrollToEl) {
			const scrollNav = document.getElementById(scrollToEl).offsetTop;
			setTimeout(() => {
				document.querySelector('.classroom-nav')
					.scrollTo({
						top: scrollNav - 60,
						behavior: 'smooth'
					});
			}, 500);
		}
	}
}
