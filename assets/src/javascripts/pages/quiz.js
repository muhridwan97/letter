import showConfirm from "../components/confirm";
import variables from "../components/variables";

export default function () {
	// sticky header on scroll
	$(window).scroll(function () {
		const quizHeader = $('#quiz-header');
		if ($(this).scrollTop() > 90) {
			quizHeader.addClass("fixed-top rounded-0");
			quizHeader.find('.card-body').addClass('py-2');
			quizHeader.find('h6').addClass('d-none');
			$('body').css('padding-top', (quizHeader.outerHeight() + 16) + 'px');
			quizHeader.find('.card-body').removeClass('py-3');
		} else {
			quizHeader.removeClass("fixed-top rounded-0");
			quizHeader.find('.card-body').removeClass('py-2');
			quizHeader.find('h6').removeClass('d-none');
			$('body').css('padding-top', '0');
		}
	});

	const formQuiz = $('#form-quiz');
	const btnSubmitAnswer = formQuiz.find('#btn-submit-answer');
	const modalTimeOut = $('#modal-timeout');
	const btnTimeoutSubmit = modalTimeOut.find('#btn-timeout-submit');
	const labelDuration = $('#quiz-duration');
	const skipSubmit = labelDuration.data('skip-submit') || 0;

	// Quiz Timer
	const timeString = labelDuration.data('time') || '23:59:59';
	const quizDuration = moment(timeString, 'HH:mm:ss');
	let submitTimer = 5;
	let submitTimerId = null;
	const quizTimerId = setInterval(function () {
		quizDuration.subtract('1', 'second');
		const currentTime = quizDuration.format('HH:mm:ss');
		labelDuration.text(currentTime)

		if (currentTime <= '00:02:00') {
			labelDuration.addClass('text-danger pulse-active');
		}
		if (currentTime === '00:00:00') {
			clearInterval(quizTimerId);
			labelDuration.removeClass('pulse-active');

			if (!skipSubmit) {
				submitTimerId = setInterval(function () {
					submitTimer -= 1;
					btnTimeoutSubmit.text(`Submit Answer (${submitTimer})`);
					if (submitTimer === 0) {
						clearInterval(submitTimerId);
						submitQuizForm();
					}
				}, 1000);

				modalTimeOut.modal({
					backdrop: 'static',
					keyboard: false
				});
			}
		}
	}, 1000);

	// Submit when timeout
	btnTimeoutSubmit.on('click', submitQuizForm);

	// Confirm when manual submit
	btnSubmitAnswer.on('click', function() {
		const title = 'Submit Answer';
		const message = "Are you sure want to submit the answers? You can't edit later and continue to assessment process?<br><small class='text-fade'>All empty answer may be considered as 0 score</small>";
		showConfirm(title, message, (e, modalConfirm, button) => {
			submitQuizForm();
			modalConfirm.find('button').prop('disabled', true);
		});
	});

	/**
	 * Submit the quiz form then stop the timers and disabled buttons
	 */
	function submitQuizForm() {
		formQuiz.submit();
		btnTimeoutSubmit.prop('disabled', true);
		clearInterval(quizTimerId);
		if (submitTimerId) {
			clearInterval(submitTimerId);
		}
	}

	// Save current answer (text only)
	const savingIndicator = $('#saving-progress-indicator');
	formQuiz.on('change', '.radio-answer-choice, .input-answer-essay', function() {
		const id = formQuiz.data('id');
		savingIndicator.show();
		$.ajax({
			type: "POST",
			url: `${variables.baseUrl}/training/exam-exercise/save_current_answer/${id}`,
			data: formQuiz.serialize(),
			success: function(data) {
				setTimeout(function() {
					savingIndicator.hide();
				}, 500);
			}
		});
	});
}
