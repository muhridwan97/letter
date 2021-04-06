import 'jquery-ui/ui/widgets/sortable';

export default function () {
	const formSortLesson = $('.form-sort-lesson');
	const lessonSortableWrapper = formSortLesson.find('#lesson-sortable-wrapper');

	lessonSortableWrapper.sortable({
		items: ".lesson-list-item",
		handle: ".handle-order-lesson",
		stop: reorderItem
	});

	function reorderItem() {
		lessonSortableWrapper.find('.lesson-list-item').each(function (index) {
			$(this).find('.order-number').text(index + 1);
			$(this).find('.input-order').val(index + 1);
		});
	}
};
