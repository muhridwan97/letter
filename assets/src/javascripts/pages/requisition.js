import variables from '../components/variables';

export default function () {
    const formRequisition = $('#form-requisition');
    const inputDeadline = formRequisition.find('#deadline');
    const tableItem = formRequisition.find('#table-item');
    const btnAddItem = formRequisition.find('#btn-add-item');

    const modalFormItem = $('#modal-form-item');
    const formItem = modalFormItem.find('form');
    const selectItem = modalFormItem.find('#item');

    let activeRow = null;

    inputDeadline.daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        minDate: new Date(),
        locale: {
            format: 'DD/MM/YYYY'
        }
    }).on("apply.daterangepicker", function (e, picker) {
        picker.element.val(picker.startDate.format(picker.locale.format));

        const startDate = new Date(picker.startDate.valueOf());
        const dayTime = moment(startDate, "dddd HH:mm:ss");

        const day3 = moment().add(3, 'days');
        const day6 = moment().add(7, 'days');
        const day14 = moment().add(14, 'days');

        if (moment.utc(dayTime).isBefore(day3)) {
            $('[name="urgency"]').val('VERY HIGH');
        } else if (moment.utc(dayTime).isBefore(day6)) {
            $('[name="urgency"]').val('HIGH');
        } else if (moment.utc(dayTime).isBefore(day14)) {
            $('[name="urgency"]').val('NORMAL');
        } else {
            $('[name="urgency"]').val('LOW');
        }

    }).on("hide.daterangepicker", function (e, picker) {
        setTimeout(function () {
            if(picker.element.val()) {
                $('.formatted-deadline').text(picker.startDate.format('DD MMMM YYYY'));
            } else {
                $('.formatted-deadline').text('(Pick a date)');
            }
        }, 150);
    });

    btnAddItem.on('click', function (e) {
        e.preventDefault();

		modalFormItem.editedRow = null;
        modalFormItem.modal({
            backdrop: 'static',
            keyboard: false
        });

        clearItemInputs();
    });

	selectItem.on('change', function () {
		const itemName = $(this).find('option:selected').data('item-name');
		const unit = $(this).find('option:selected').data('unit');
		const brand = $(this).find('option:selected').data('brand');
		const specification = $(this).find('option:selected').data('specification');

		modalFormItem.find('#item_name').val(itemName || '');
		modalFormItem.find('#brand').val(brand || '');
		modalFormItem.find('#unit').val(unit || '');
		modalFormItem.find('#specification').val(specification || '');
	});

    modalFormItem.on('submit', function (e) {
        e.preventDefault();

        saveItem({
			id_item: modalFormItem.find('#item').val() || '',
            item_name: modalFormItem.find('#item_name').val() || '',
            brand: modalFormItem.find('#brand').val() || '',
            type: modalFormItem.find('#type').val() || '',
            quantity: modalFormItem.find('#quantity').val() || '1',
            unit: modalFormItem.find('#unit').val() || 'PCS',
			specification: modalFormItem.find('#specification').val() || '',
            description: modalFormItem.find('#description').val() || '',
        }, modalFormItem.editedRow);

        clearItemInputs();
        modalFormItem.modal('hide');
    });

    tableItem.on('click', '.btn-delete', function (e) {
        e.preventDefault();

        $(this).closest('tr').remove();

        if (tableItem.find('tbody tr').length === 0) {
            tableItem.find('tbody').append(`
                <tr class="row-placeholder">
                    <td colspan="8">No items available</td>
                </tr>
            `);
        }

        reorderRows();
    });

    tableItem.on('click', '.btn-edit', function (e) {
        e.preventDefault();

        const row = $(this).closest('tr');
		modalFormItem.editedRow = row;

        modalFormItem.find('#item').val(row.find('#id_item').val() || '').trigger('change');
        modalFormItem.find('#item_name').val(row.find('#item_name').val() || '');
        modalFormItem.find('#brand').val(row.find('#brand').val() || '');
        modalFormItem.find('#type').val(row.find('#type').val() || '');
        modalFormItem.find('#quantity').val(row.find('#quantity').val() || '1');
        modalFormItem.find('#unit').val(row.find('#unit').val() || 'PCS');
        modalFormItem.find('#specification').val(row.find('#specification').val() || '');
        modalFormItem.find('#description').val(row.find('#description').val());

        modalFormItem.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    function saveItem(item, row = null) {
        if (row === null) {
            const placeholderRow = tableItem.find('tbody tr.row-placeholder');
            if (placeholderRow.length) {
                placeholderRow.remove();
            }

            const lastRow = tableItem.find('tbody tr').not('.row-placeholder').length;

            const templateRow = `
                <tr>
                    <td>${lastRow + 1}</td>
                    <td class="label-item">${item.item_name || 'No item'}</td>
                    <td class="label-brand">${item.brand || '-'}</td>
                    <td class="label-type">${item.type || '-'}</td>
                    <td class="label-quantity">${item.quantity || 1}</td>
                    <td class="label-unit">${item.unit || 'PCS'}</td>
                    <td class="label-description">${item.description || '-'}</td>
                    <td class="text-right">
                        <input type="hidden" name="items[][id]" id="id" value="">
                        <input type="hidden" name="items[][id_item]" id="id_item" value="${item.id_item}">
                        <input type="hidden" name="items[][item_name]" id="item_name" value="${item.item_name}">
                        <input type="hidden" name="items[][brand]" id="brand" value="${item.brand}">
                        <input type="hidden" name="items[][type]" id="type" value="${item.type}">
                        <input type="hidden" name="items[][quantity]" id="quantity" value="${item.quantity}">
                        <input type="hidden" name="items[][unit]" id="unit" value="${item.unit}">
                        <input type="hidden" name="items[][specification]" id="specification" value="${item.specification}">
                        <input type="hidden" name="items[][description]" id="description" value="${item.description}">
                        <button class="btn btn-sm btn-outline-info btn-edit" type="button">
                            <i class="mdi mdi-square-edit-outline"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </button>
                    </td>
                </tr>
            `;
            tableItem.find('tbody').first().append(templateRow);

            reorderRows();
        } else {
            row.find('.label-item').text(item.item_name || 'No item');
            row.find('.label-brand').text(item.brand || '-');
            row.find('.label-type').text(item.type || '-');
            row.find('.label-quantity').text(item.quantity || '1');
            row.find('.label-unit').text(item.unit || 'PCS');
            row.find('.label-description').text(item.description || '-');

            row.find('#id_item').val(item.id_item || '');
            row.find('#item_name').val(item.item_name || '');
            row.find('#brand').val(item.brand || '');
            row.find('#type').val(item.type || '');
            row.find('#quantity').val(item.quantity || '1');
            row.find('#unit').val(item.unit || 'PCS');
            row.find('#specification').val(item.specification || '');
            row.find('#description').val(item.description || '');
        }
    }

    function clearItemInputs() {
        modalFormItem.find('#item').val('').trigger('change');
        modalFormItem.find('#item_name').val('');
        modalFormItem.find('#brand').val('');
        modalFormItem.find('#type').val('');
        modalFormItem.find('#quantity').val('1');
        modalFormItem.find('#unit').val('PCS');
        modalFormItem.find('#specification').val('');
        modalFormItem.find('#description').val('');
        formItem.removeClass('was-validated');
    }

    function reorderRows() {
        tableItem.find('tbody tr').not('.row-placeholder').each(function (index) {
            // recount row number
            $(this).children('td').first().html((index + 1).toString());

            // reorder index of inputs
            $(this).find('input[name]').each(function () {
                const pattern = new RegExp("items[([0-9]*\\)?]", "i");
                const attributeName = $(this).attr('name').replace(pattern, 'items[' + index + ']');
                $(this).attr('name', attributeName);
            });
        });
    }


    const tableRequisition = $('#table-requisition');
    const modalRevertRequisition = $('#modal-revert-requisition');


    /**
     * Revert requisition in specific status.
     */
    tableRequisition.on('click', '.btn-revert', function (e) {
        e.preventDefault();

        modalRevertRequisition.find('#approved').addClass("d-none");
        modalRevertRequisition.find('#listed').addClass("d-none");
        modalRevertRequisition.find('#ready').addClass("d-none");

        const row = $(this).closest('.row-requisition');
        const requestTitle = row.data('request-title');
        const requestNo = row.data('no-requisition');
        const status = row.data('status');
        const url = $(this).attr('href');

        if (status === 'LISTED') {
            modalRevertRequisition.find('#approved').removeClass("d-none");
        } else if (status === 'READY' || status === 'ASK SELECTION') {
            modalRevertRequisition.find('#approved').removeClass("d-none");
            modalRevertRequisition.find('#listed').removeClass("d-none");
        } else if (status === 'SELECTED' || status === 'IN PROCESS') {
            modalRevertRequisition.find('#approved').removeClass("d-none");
            modalRevertRequisition.find('#listed').removeClass("d-none");
            modalRevertRequisition.find('#ready').removeClass("d-none");
        }

        modalRevertRequisition.find('#requisition-title').text(requestNo);
        modalRevertRequisition.find('#requisition-description').text(requestTitle);
        modalRevertRequisition.find('form').attr('action', url);

        modalRevertRequisition.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    /**
     * url: /requisition/{index?}
     * Validating employee request, all buttons share same class to be triggered .btn-validate,
     * we check data-validate attribute to detect which action we need to be performed.
     */
    const modalValidation = $('#modal-validate');

    tableRequisition.on('click', '.btn-validate', function (e) {
        e.preventDefault();

        const label = $(this).closest('.row-requisition').data('request-title');
        const email = $(this).closest('.row-requisition').data('email');
        const url = $(this).attr('href');
        const validate = $(this).data('validate');

        modalValidation.find('button[type=submit]').removeClass('btn-danger').removeClass('btn-success');
        if (validate === 'approve') {
            modalValidation.find('.modal-title').text('Approve Requisition');
            modalValidation.find('button[type=submit]').text('APPROVE').addClass('btn-success');
            modalValidation.find('#message').prop('required', false);
        } else if (validate === 'cancel') {
            modalValidation.find('.modal-title').text('Cancel Requisition');
            modalValidation.find('button[type=submit]').text('CANCEL REQUISITION').addClass('btn-danger');
            modalValidation.find('#message').prop('required', false);
        } else if (validate === 'set-checked') {
            modalValidation.find('.modal-title').text('Set Checked');
            modalValidation.find('button[type=submit]').text('CHECKED').addClass('btn-success');
            modalValidation.find('#message').prop('required', false);
        } else {
            modalValidation.find('.modal-title').text('Reject Requisition');
            modalValidation.find('button[type=submit]').text('REJECT').addClass('btn-danger');
            modalValidation.find('#message').prop('required', true);
        }

        modalValidation.find('form').attr('action', url);
        modalValidation.find('.validate-label').text(validate + ' ' + label);
        modalValidation.find('.validate-email').text(email);

        modalValidation.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    const viewValidation =  $('#view-validate');
    viewValidation.on('click', '.btn-validate', function (e) {
        e.preventDefault();
        const label = $(this).data('request-title');
        const email = $(this).data('email');
        const url = $(this).attr('href');
        const validate = $(this).data('validate');

        modalValidation.find('button[type=submit]').removeClass('btn-danger').removeClass('btn-success');
        if (validate === 'approve') {
            modalValidation.find('.modal-title').text('Approve Requisition');
            modalValidation.find('button[type=submit]').text('APPROVE').addClass('btn-success');
            modalValidation.find('#message').prop('required', false);
        } else if (validate === 'cancel') {
            modalValidation.find('.modal-title').text('Cancel Requisition');
            modalValidation.find('button[type=submit]').text('CANCEL REQUISITION').addClass('btn-danger');
            modalValidation.find('#message').prop('required', false);
        } else {
            modalValidation.find('.modal-title').text('Reject Requisition');
            modalValidation.find('button[type=submit]').text('REJECT').addClass('btn-danger');
            modalValidation.find('#message').prop('required', true);
        }

        modalValidation.find('form').attr('action', url);
        modalValidation.find('.validate-label').text(validate + ' ' + label);
        modalValidation.find('.validate-email').text(email);

        modalValidation.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    /**
     * url: /requisition/{index?}
     * Set category request, all buttons share same class to be triggered .btn-set-category,
     * we check data-validate attribute to detect which action we need to be performed.
     */
    const modalSetCategory = $('#modal-set-category');

    tableRequisition.on('click', '.btn-set-category', function (e) {
        e.preventDefault();

        const row = $(this).closest('.row-requisition');
        const requestTitle = row.data('request-title');
        const requestNo = row.data('no-requisition');
        const url = $(this).attr('href');

        modalSetCategory.find('form').attr('action', url);
        modalSetCategory.find('#requisition-title').text(requestNo);
        modalSetCategory.find('#requisition-description').text(requestTitle);

        modalSetCategory.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

	/**
	 * url: /requisition/{index?}
	 * Approve requisition and set type of request.
	 */
    const modalSetApprovedType = $('#modal-set-approved-type');

    viewValidation.on('click', '.btn-set-approve-type', function (e) {
        e.preventDefault();

        const requestNo = $(this).data('no-requisition');
        const url = $(this).attr('href');

        modalSetApprovedType.find('form').attr('action', url);
        modalSetApprovedType.find('#requisition-title').text(requestNo);

        modalSetApprovedType.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

	modalSetApprovedType.on('change', '.type', function (e) {
		e.preventDefault();

		const type = modalSetApprovedType.find('.type').val();
		if (type === "EXTERNAL") {
			modalSetApprovedType.find('.modal-internal').hide();
			modalSetApprovedType.find('.modal-customer').show();
			modalSetApprovedType.find('.customer').attr('required', true);
		} else if (type === "INTERNAL") {
			modalSetApprovedType.find('.modal-internal').show();
			modalSetApprovedType.find('.modal-customer').hide();
			modalSetApprovedType.find('.customer').attr('required', false);
		} else {
			modalSetApprovedType.find('.modal-internal').hide();
			modalSetApprovedType.find('.modal-customer').hide();
			modalSetApprovedType.find('.customer').attr('required', false);
		}

    });
    
    /**
	 * url: /requisition/create
	 * Create requisition and set type of request by approver(manager/spv).
	 */
    const modalRequisitionApproved = $('#modal-requisition-approved');

    formRequisition.on('click', '.btn-set-approve-type', function (e) {
        e.preventDefault();
        let employeeId = formRequisition.find('#employee').val();
        fetch(`${variables.baseUrl}requisition/ajax-is-employee-approver?employeeId=${employeeId}`)
            .then(result => result.json())
            .then(data => {
                console.log(data);
                if(data === 'PENDING'){
                    console.log('masuk');
                    formRequisition.submit();
                }else{
                    const requestNo = $(this).data('no-requisition');
                    const url = $(this).attr('href');
            
                    modalRequisitionApproved.find('form').attr('action', url);
                    modalRequisitionApproved.find('#requisition-title').text(requestNo);
            
                    modalRequisitionApproved.modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }    
            })
            .catch(err => {
            });
            
    });

	modalRequisitionApproved.on('change', '.type', function (e) {
		e.preventDefault();

		const type = modalRequisitionApproved.find('.type').val();
		if (type === "EXTERNAL") {
			modalRequisitionApproved.find('.modal-internal').hide();
			modalRequisitionApproved.find('.modal-customer').show();
			modalRequisitionApproved.find('.customer').attr('required', true);
		} else if (type === "INTERNAL") {
			modalRequisitionApproved.find('.modal-internal').show();
			modalRequisitionApproved.find('.modal-customer').hide();
			modalRequisitionApproved.find('.customer').attr('required', false);
		} else {
			modalRequisitionApproved.find('.modal-internal').hide();
			modalRequisitionApproved.find('.modal-customer').hide();
			modalRequisitionApproved.find('.customer').attr('required', false);
		}

    });
    
    modalRequisitionApproved.on('submit', function (e) {
        e.preventDefault();
        var valType = $(this).find('#type').val();
        var valCustomer = $(this).find('#customer').val();
        var valMessage = $(this).find('#message').val();
        
        var inputType = $("<input>")
               .attr("type", "hidden")
               .attr("name", "type").val(valType);
        var inputCustomer = $("<input>")
               .attr("type", "hidden")
               .attr("name", "customer[]").val(valCustomer);
        var inputMessage = $("<input>")
               .attr("type", "hidden")
               .attr("name", "message").val(valMessage);
        formRequisition.append(inputType);
        formRequisition.append(inputCustomer);
        formRequisition.append(inputMessage);
        formRequisition.submit();
        modalRequisitionApproved.modal('toggle');
    });
    
    const btnRefresh = modalFormItem.find('#refresh');
    btnRefresh.on('click', function () {
        const category = $(this).val();
        console.log('masuk');
        // populate item category by its category
        selectItem.empty().append($('<option>', { value: '', selected: true }).text("-- Select Item --")).prop("disabled", true);
        fetch(`${variables.baseUrl}requisition/ajax-get-item`)
            .then(result => result.json())
            .then(data => {
                selectItem.prop("disabled", false);
                data.forEach(row => {
                    selectItem.append(
                        $('<option>', {
                            value: row.id,
							"data-item-name": row.item_name || 0,
							"data-unit": row.unit || 0,
							"data-brand": row.brand || 0,
							"data-specification": row.specification || 0,
                        }).text(row.item_name + ' - ' + (row.specification != null ? row.specification : (row.brand != null ? row.brand : row.unit)) )
                    );
                });
            })
            .catch(err => {
                selectItem.prop("disabled", false);
            });

    });

    $("#item").select2({
        minimumInputLength: 1,
        formatInputTooShort: function () {
            return "Enter 1 Character";
        },  
    });
};
