import '../components/confirm';
import showConfirm from "../components/confirm";

export default function () {
	const tableExamExercise = $('#table-exam-exercise');
	tableExamExercise.on('click', '.btn-start-quiz', function() {
		const title = $(this).data('title');
		const duration = $(this).data('duration');
		showConfirm('Start Exam', `Timer <strong>${duration}</strong> will start and cannot be cancelled. Are you sure want to start the exam <strong>${title}</strong> right now?`, (e, modalConfirm, button) => {
			$(this).closest('form').submit();
		});
	});
}
