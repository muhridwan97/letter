import 'jquery-ui/ui/widgets/sortable';

export default function () {
	const formSortCurriculum = $('.form-sort-curriculum');
	const curriculumSortableWrapper = formSortCurriculum.find('#curriculum-sortable-wrapper');

	curriculumSortableWrapper.sortable({
		items: ".curriculum-list-item",
		handle: ".handle-order-curriculum",
		stop: reorderItem
	});

	function reorderItem() {
		curriculumSortableWrapper.find('.curriculum-list-item').each(function (index) {
			$(this).find('.order-number').text(index + 1);
			$(this).find('.input-order').val(index + 1);
		});
	}
};
