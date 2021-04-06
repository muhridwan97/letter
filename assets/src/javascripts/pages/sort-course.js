import 'jquery-ui/ui/widgets/sortable';

export default function () {
	const formSortCourse = $('.form-sort-course');
	const courseSortableWrapper = formSortCourse.find('#course-sortable-wrapper');

	courseSortableWrapper.sortable({
		items: ".course-list-item",
		handle: ".handle-order-course",
		stop: reorderItem
	});

	function reorderItem() {
		courseSortableWrapper.find('.course-list-item').each(function (index) {
			$(this).find('.order-number').text(index + 1);
			$(this).find('.input-order').val(index + 1);
		});
	}
};
