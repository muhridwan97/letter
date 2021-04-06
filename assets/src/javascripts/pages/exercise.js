import 'jquery-ui/ui/widgets/sortable';
import showAlert from "../components/alert";

export default function () {
	const CATEGORY_CHOICES = 'CHOICES';
	const CATEGORY_ESSAY = 'ESSAY';
	const CATEGORY_PRACTICE = 'PRACTICE';

	const formExercise = $('#form-exercise');
	const selectCategory = formExercise.find('#category');
	const btnAddQuestion = formExercise.find('.btn-add-question');
	const btnCollapseQuestions = formExercise.find('.btn-collapse-questions');
	const questionWrapper = formExercise.find('.question-wrapper');
	const questionPlaceholder = questionWrapper.find('.question-placeholder');
	const questionTemplate = $('#question-template').html();
	const answerChoiceTemplate = $('#answer-choice-template').html();
	const answerEssayTemplate = $('#answer-essay-template').html();

	questionWrapper.sortable({
		items: ".question-item",
		handle: ".handle-order-question",
		stop: function() {
			orderInputQuestion(questionWrapper)
		}
	});

	/**
	 * Structure question-answer template.
	 *
	 * <question-wrapper>
	 * 		<question-item>
	 * 			<question>
	 * 			<answer-wrapper>
	 * 				<answer-item></answer-item>
	 * 				<answer-item></answer-item>
	 * 				...
	 * 			<answer-wrapper>
	 * 		</question-item>
	 * 		<question-item></question-item>
	 *     	...
	 * </question-wrapper>
	 */

	selectCategory.on('change', function () {
		questionWrapper.find('.question-item').remove();
		questionPlaceholder.show();
	});

	btnCollapseQuestions.on('click', function () {
		if ($('.question-content-item.show').length) {
			$('.question-content-item').collapse('hide');
		} else {
			$('.question-content-item').collapse('show');
		}
	});

	btnAddQuestion.on('click', function () {
		if (!selectCategory.val()) {
			showAlert('Question Category', 'Please select question category first!')
			return;
		}
		questionPlaceholder.hide();
		const totalQuestion = questionWrapper.find('.question-item').length;
		if (totalQuestion > 0) {
			$('.question-content-item').collapse('hide');
		}
		const renderedQuestion = Mustache.render(questionTemplate, {
			question_index: totalQuestion,
			question_order: totalQuestion + 1,
			question: '',
			hint: '',
			attachment: '',
			description: '',
		});
		const parsedQuestion = $.parseHTML(renderedQuestion);

		// remove button choices and add essay answer template instead
		if (selectCategory.val() !== CATEGORY_CHOICES) {
			const renderedAnswerEssay = Mustache.render(answerEssayTemplate, {
				question_index: totalQuestion,
				answer: '',
			});
			$(parsedQuestion).find('.btn-add-answer-choice').remove();
			$(parsedQuestion).find('.answer-placeholder').remove();
			if ($(parsedQuestion).find('.answer-item').length === 0) {
				$(parsedQuestion).find('.answer-wrapper').append(renderedAnswerEssay);
			}
		}

		questionWrapper.append(parsedQuestion);

		orderInputQuestion(questionWrapper);
	});

	questionWrapper.on('click', '.btn-remove-question', function () {
		$(this).closest('.question-item').remove();

		const totalQuestionItem = questionWrapper.find('.question-item').length;

		if (totalQuestionItem === 0) {
			questionPlaceholder.show();
		}

		if (totalQuestionItem === 1 && !questionWrapper.find('.question-content-item').hasClass('show')) {
			$('.question-content-item').collapse('show');
		}

		orderInputQuestion(questionWrapper);
	});

	questionWrapper.on('click', '.btn-add-answer-choice', function () {
		const answerWrapper = $(this).closest('.question-item').find('.answer-wrapper');
		const answerPlaceholder = answerWrapper.find('.answer-placeholder');

		answerPlaceholder.hide();
		const totalAnswer = answerWrapper.find('.answer-item').length;
		const renderedAnswer = Mustache.render(answerChoiceTemplate, {
			question_index: answerWrapper.closest('.question-item').data('index'),
			answer_index: totalAnswer,
			answer_order: totalAnswer + 1,
			answer: '',
			is_correct_answer: totalAnswer === 0 ? 'checked' : '',
		});
		answerWrapper.append(renderedAnswer);
		orderInputQuestion(questionWrapper);
	});

	questionWrapper.on('change', '.radio-answer-choice', function () {
		const answerWrapper = $(this).closest('.answer-wrapper');
		answerWrapper.find('.radio-answer-choice').not(this).prop('checked', false).data('checked', '');
		// save checked state to data-checked, use later for ordering question
		console.log('changed', this);
		if ($(this).is(':checked')) {
			$(this).data('checked', 'checked');
		}
	});

	questionWrapper.on('click', '.btn-remove-answer', function () {
		const answerWrapper = $(this).closest('.answer-wrapper');
		const answerPlaceholder = answerWrapper.find('.answer-placeholder');
		const answerItem = $(this).closest('.answer-item');

		answerItem.remove();

		const isCheckedAnswerRemoved = answerItem.find('.radio-answer-choice').first().is(':checked');
		if (isCheckedAnswerRemoved) {
			answerWrapper.find('.radio-answer-choice').first().prop('checked', true);
		}

		const totalAnswerItem = answerWrapper.find('.answer-item').length;

		if (totalAnswerItem === 0) {
			answerPlaceholder.show();
		}

		orderInputQuestion(questionWrapper);
	})

	/**
	 * Reorder input question.
	 *
	 * @param wrapper
	 */
	function orderInputQuestion(wrapper) {
		wrapper.find('.question-item').each(function (index, item) {
			// reorder question label and index
			$(item).find('.item-order').text((index + 1).toString());
			$(item).data('index', (index + 1).toString());
			$(item).find('.input-order').val(index + 1);

			// check question inputs
			$(item).find('input[name],textarea[name],select[name]').each(function (innerIdx, input) {
				const pattern = new RegExp("questions[([0-9]*\\)?]", "i");
				const attributeName = $(input).attr('name').replace(pattern, 'questions[' + index + ']');
				$(input).attr('name', attributeName);

				// restore radio-checkbox from data-checked attribute
				if (input.type === 'radio' && $(input).data('checked')) {
					if ($(input).data('checked')) {
						setTimeout(function() {
							$(input).prop('checked', true);
						}, 200);
					}
				}
			});

			// check if it has answer choice
			$(this).find('.answer-item').each(function (answerIndex) {
				// reorder answer placeholder
				$(this).find('.input-answer').attr('placeholder', 'Answer choice ' + (answerIndex + 1).toString());

				// reorder index of inputs
				$(this).find('input[name],textarea[name],select[name]').each(function () {
					const pattern = new RegExp("\\[answers][([0-9]*\\)?]", "i");
					const attributeName = $(this).attr('name').replace(pattern, '[answers][' + answerIndex + ']');
					$(this).attr('name', attributeName);
				});
			});
		});
	}

};
