import variables from '../components/variables';
import formatter from '../components/formatter';

export default function () {
    const formOffer = $('#form-offer');
    const selectRequisition = formOffer.find('#requisition');
    const selectVendor = formOffer.find('#vendor');

    const modalTakeItem = $('#modal-take-item');
    const formTake = modalTakeItem.find('form');

    const tableItem = formOffer.find('#table-item');
    const tableTaken = formOffer.find('#table-taken');

    let activeRow = null;
    let activeRequisitionItems = (typeof _requisitionItems !== 'undefined') ? _requisitionItems : null;

    selectRequisition.on('change', function () {
        const requisitionId = $(this).val();
        selectVendor.empty().append($('<option>').text('-- Select Vendor --')).prop("disabled", true);
        fetch(`${variables.baseUrl}requisition/ajax-get-requisition?id_requisition=${requisitionId}`)
            .then(result => result.json())
            .then(data => {
                const requisitionItems = data['requisition_items'];
                const vendors = data['vendors'];

                selectVendor.prop("disabled", false);
                vendors.forEach(row => {
                    selectVendor.append(
                        $('<option>', {value: row.id}).text(row.vendor)
                    );
                });

                activeRequisitionItems = requisitionItems;

                tableItem.find('tbody').empty();
                if (requisitionItems.length) {
                    requisitionItems.forEach(item => {
                        const lastRow = tableItem.find('tbody tr').not('.row-placeholder').length;
                        const templateRow = `
                            <tr>
                                <td>${lastRow + 1}</td>
                                <td class="label-item">${item.item_name}</td>
                                <td class="label-brand">${item.brand || '-'}</td>
                                <td class="label-quantity">${item.quantity || 0}</td>
                                <td class="label-unit">${item.unit || '-'}</td>
                                <td class="label-description">${item.description || '-'}</td>
                                <td class="text-md-right">
                                    <input type="hidden" id="id_requisition_item" class="id_requisition_item"  value="${item.id}">
                                    <input type="hidden" id="quantity" value="${item.quantity}">
                                    <input type="hidden" id="item_name" value="${item.item_name}">
                                    <button class="btn btn-sm btn-info btn-take" type="button">
                                        Take
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableItem.find('tbody').append(templateRow);
                    });
                } else {
                    tableItem.find('tbody').html(`
                        <tr class="row-placeholder">
                            <td colspan="7">No items available</td>
                        </tr>
                    `);

                    tableTaken.find('tbody').html(`
                        <tr class="row-placeholder">
                            <td colspan="9">No taken items available</td>
                        </tr>
                    `);
                }
            })
            .catch(console.log);
    });

    tableItem.on('click', '.btn-take', function (e) {
        e.preventDefault();

        const row = $(this).closest('tr');
        activeRow = row;

        modalTakeItem.find('#item_name').val(row.find('#item_name').val() || '');
        modalTakeItem.find('#quantity').val(row.find('#quantity').val() || '1');
        modalTakeItem.find('#quantity').attr('max', row.find('#quantity').val() || 1);

        modalTakeItem.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    modalTakeItem.on('submit', function (e) {
        e.preventDefault();

        const placeholderRow = tableTaken.find('tbody tr.row-placeholder');
        if (placeholderRow.length) {
            placeholderRow.remove();
        }

        // populate data to add taken
        const requisitionItemId = activeRow.find('#id_requisition_item').val();
        const itemName = activeRow.find('.label-item').text() || '';
        const brand = activeRow.find('.label-brand').text() || '';
        const unit = activeRow.find('.label-unit').text() || '';

        const quantity = modalTakeItem.find('#quantity').val() || 0;
        const price = modalTakeItem.find('#price').val() || 'Rp. 0';
        const discount = modalTakeItem.find('#discount').val() || 'Rp. 0';
        const total = (formatter.getNumberValue(price) * quantity) - (formatter.getNumberValue(discount) * formatter.getNumberValue(quantity));
        const description = modalTakeItem.find('#description').val() || '';

        // update active row
        const sourceQuantity = activeRow.find('#quantity');
        const leftQuantity = Number(sourceQuantity.val()) - Number(quantity);

        // put into taken table
        const lastRow = tableTaken.find('tbody tr').not('.row-placeholder').length;
        const templateRow = `
            <tr>
                <td>${lastRow + 1}</td>
                <td class="label-item">${itemName}</td>
                <td class="label-brand">${brand}</td>
                <td class="label-quantity">${formatter.setCurrencyValue(quantity, '', ',', '.')}</td>
                <td class="label-unit">${unit}</td>
                <td class="label-price">${price}</td>
                <td class="label-discount">${discount}</td>
                <td class="label-total">${formatter.setCurrencyValue(total, 'Rp. ', ',', '.')}</td>
                <td class="label-description">${description || '-'}</td>
                <td class="text-md-right">
                    <input type="hidden" name="taken_items[][id_requisition_item]" id="id_requisition_item" value="${requisitionItemId}">
                    <input type="hidden" name="taken_items[][item_name]" id="item_name" value="${itemName}">
                    <input type="hidden" name="taken_items[][brand]" id="brand" value="${brand}">
                    <input type="hidden" name="taken_items[][unit]" id="unit" value="${unit}">
                    <input type="hidden" name="taken_items[][total]" id="unit" value="${total}">
                    <input type="hidden" name="taken_items[][quantity]" id="quantity" value="${quantity}">
                    <input type="hidden" name="taken_items[][price]" id="price" value="${formatter.getNumberValue(price)}">
                    <input type="hidden" name="taken_items[][discount]" id="discount" value="${formatter.getNumberValue(discount)}">
                    <input type="hidden" name="taken_items[][description]" id="description" value="${description}">
                    <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                        <i class="mdi mdi-trash-can-outline"></i>
                    </button>
                </td>
            </tr>
        `;
        tableTaken.find('tbody').append(templateRow);

        if (leftQuantity <= 0) {
            $(activeRow).remove();
        } else {
            sourceQuantity.val(leftQuantity);
            activeRow.find('.label-quantity').text(leftQuantity);
        }

        clearItemInputs();
        reorderRows();

        modalTakeItem.modal('hide');
    });

    tableTaken.on('click', '.btn-delete', function (e) {
        e.preventDefault();

        const currentRow = $(this).closest('tr');

        const requisitionItemId = currentRow.find('#id_requisition_item').val();
        const sourceRequisitionItem = tableItem.find(".id_requisition_item[value=" + requisitionItemId + "]");
        if (sourceRequisitionItem.length) {
            const sourceRow = sourceRequisitionItem.closest('tr');
            const updatedQuantity = Number(sourceRow.find('#quantity').val()) + Number(currentRow.find('#quantity').val());
            sourceRow.find('.label-quantity').text(updatedQuantity);
            sourceRow.find('#quantity').val(updatedQuantity);
        } else {
            const item = activeRequisitionItems.filter(item => {
                return item.id === requisitionItemId;
            });

            const lastRow = tableItem.find('tbody tr').not('.row-placeholder').length;
            const templateRow = `
                <tr>
                    <td>${lastRow + 1}</td>
                    <td class="label-item">${currentRow.find('.label-item').text()}</td>
                    <td class="label-brand">${currentRow.find('.label-brand').text() || '-'}</td>
                    <td class="label-quantity">${currentRow.find('#quantity').val() || 0}</td>
                    <td class="label-unit">${currentRow.find('.label-unit').text() || '-'}</td>
                    <td class="label-description">${item.length ? (item[0].description || '-') : '-'}</td>
                    <td class="text-md-right">
                        <input type="hidden" id="id_requisition_item" value="${requisitionItemId}">
                        <input type="hidden" id="quantity" value="${currentRow.find('#quantity').val()}">
                        <input type="hidden" id="item_name" value="${currentRow.find('.label-item').text()}">
                        <button class="btn btn-sm btn-info btn-take" type="button">
                            Take
                        </button>
                    </td>
                </tr>
            `;
            tableItem.find('tbody').append(templateRow);
        }

        currentRow.remove();

        if (tableTaken.find('tbody tr').length === 0) {
            tableTaken.find('tbody').append(`
                <tr class="row-placeholder">
                    <td colspan="9">No taken items available</td>
                </tr>
            `);
        }

        reorderRows();
    });

    function clearItemInputs() {
        modalTakeItem.find('#item_name').val('');
        modalTakeItem.find('#quantity').val('1');
        modalTakeItem.find('#price').val('');
        modalTakeItem.find('#discount').val('');
        modalTakeItem.find('#description').val('');
        formTake.removeClass('was-validated');
    }

    function reorderRows() {
        tableItem.find('tbody tr').not('.row-placeholder').each(function (index) {
            $(this).children('td').first().html((index + 1).toString());
        });

        tableTaken.find('tbody tr').not('.row-placeholder').each(function (index) {
            $(this).children('td').first().html((index + 1).toString());

            $(this).find('input[name]').each(function () {
                const pattern = new RegExp("taken_items[([0-9]*\\)?]", "i");
                const attributeName = $(this).attr('name').replace(pattern, 'taken_items[' + index + ']');
                $(this).attr('name', attributeName);
            });
        });
    }

    const modalAskSelection = $('#modal-ask-selection');
	const selectTopManager = modalAskSelection.find('#decision_maker');
	const decisionMakerErrorLabel = modalAskSelection.find('#decision-maker-error');
    $('.btn-ask-selection').on('click', function (e) {
        e.preventDefault();

        const row = $(this).closest('.row-requisition');
        const confirmTopManager = $(this).data('confirm-top-manager');
        const topManagerId = selectTopManager.data('top-manager');

        modalAskSelection.find('#requisition-title').text(row.data('request-title'));
        modalAskSelection.find('form').attr('action', $(this).attr('href'));

		selectTopManager.val('').trigger('change');
		decisionMakerErrorLabel.hide();

		if (confirmTopManager === 1 || confirmTopManager === true) {
			selectTopManager.next('.select2').css('pointer-events', 'none');
			if (topManagerId) {
				selectTopManager.val(selectTopManager.data('top-manager')).trigger('change');
				decisionMakerErrorLabel.text('Requisition contains offer that equal or more than IDR 5 millions').show();
			} else {
				decisionMakerErrorLabel.html('Please set <strong>Top Manager</strong> in setting menu').show();
			}
		} else {
			selectTopManager.next('.select2').css('pointer-events', 'auto');
		}

        modalAskSelection.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

};
