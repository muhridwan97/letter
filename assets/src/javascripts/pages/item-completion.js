export default function () {
	const formItemCompletion = $('#form-item-completion');
	$.validator.addClassRules({
		"needed-quantity": {
			required: true,
			number: true,
			greater_than: 0
		},
		"selected-quantity": {
			required: true,
			number: true,
			max: function(element){
				console.log($(element).closest('tr').find('.needed-quantity').val());
				return $(element).closest('tr').find('.needed-quantity').val();
			}
		},
	});
}
