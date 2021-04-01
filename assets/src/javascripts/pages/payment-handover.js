
import variables from "../components/variables";
import formatter from "../components/formatter";

export default function () {
    
	const formEditPo = $('#form-edit-po');
    const tableItem = formEditPo.find('#table-purchase-order');
    const btnAddItem = formEditPo.find('#btn-add-item');

    const modalFormItem = $('#modal-form-item');
    const formItem = modalFormItem.find('form');
    
    let activeRow = null;

    btnAddItem.on('click', function (e) {
        e.preventDefault();

        activeRow = null;

        modalFormItem.modal({
            backdrop: 'static',
            keyboard: false
        });

        clearItemInputs();
    });

    modalFormItem.on('submit', function (e) {
        e.preventDefault();
        saveItem({
            id_purchase_order: modalFormItem.find('#purchase_order option:selected').val() || '',
            no_purchase: modalFormItem.find('#purchase_order option:selected').data('no-purchase') || '',
            request_title: modalFormItem.find('#purchase_order option:selected').data('request-title') || '',
            vendor: modalFormItem.find('#purchase_order option:selected').data('vendor') || '',
        }, activeRow);

        clearItemInputs();
        modalFormItem.modal('hide');
    });

    tableItem.on('click', '.btn-delete', function (e) {
        e.preventDefault();

        $(this).closest('tr').remove();

        if (tableItem.find('tbody tr').length === 0) {
            tableItem.find('tbody').append(`
                <tr class="row-placeholder">
                    <td colspan="5">No purchase_orders available</td>
                </tr>
            `);
        }

        reorderRows();
    });

    tableItem.on('click', '.btn-edit', function (e) {
        e.preventDefault();

        const row = $(this).closest('tr');
        activeRow = row;
        
        let purchaseOrderId = row.find('#id_purchase_order').val();
        modalFormItem.find('#purchase_order option[value="'+purchaseOrderId+'"]').prop("disabled", false);
        modalFormItem.find('#purchase_order').select2();

        modalFormItem.find('#purchase_order').val(row.find('#id_purchase_order').val() || '').trigger('change');

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
                    <td class="label-no-purchase">
                        <a href="${variables.baseUrl}purchasing/order/view/${item.id_purchase_order}" target="_blank">
                            ${item.no_purchase}
                        </a>
                    </td>
                    <td class="label-requisition">${item.request_title || '-'}</td>
                    <td class="label-vendor">${item.vendor || '-'}</td>
                    <td><input type="text" class="form-control" name="purchase_orders[${lastRow}][vendor_invoice]" placeholder="No invoice" value=""></td>
                    <td><input type="text" class="form-control datepicker" name="purchase_orders[${lastRow}][invoice_date]" placeholder="Invoice date" value=""></td>
                    <td class="text-right">
                        <input type="hidden" name="purchase_orders[${lastRow}][id_purchase_order]" id="id_purchase_order" value="${item.id_purchase_order}">
                        <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </button>
                    </td>
                </tr>
            `;
            tableItem.find('tbody').first().append(templateRow);

            reorderRows();
        } else {
            row.find('.label-no-purchase').html(`
                <a href="${variables.baseUrl}master/equipment/view/${item.id_purchase_order}" target="_blank">
                    ${item.equipment_name}
                </a>
            `);
            row.find('.label-requisition').text(item.request_title || '-');
            row.find('.label-vendor').text(item.vendor || '-');

            row.find('#id_purchase_order').val(item.id_purchase_order || '');
        }
    }

    function clearItemInputs() {
        modalFormItem.find('#purchase_order').val('').trigger('change');
        formItem.removeClass('was-validated');
    }

    function reorderRows() {
        modalFormItem.find('#purchase_order option').prop("disabled", false);
        modalFormItem.find('#purchase_order').select2();
        tableItem.find('tbody tr').not('.row-placeholder').each(function (index) {
            // recount row number
            $(this).children('td').first().html((index + 1).toString());
            //disabled
            let purchaseOrderId = $(this).find('#id_purchase_order').val();
            modalFormItem.find('#purchase_order option[value="'+purchaseOrderId+'"]').prop("disabled", true);
            modalFormItem.find('#purchase_order').select2();
            // reorder index of inputs
            $(this).find('input[name]').each(function () {
                const pattern = new RegExp("purchase_orders[([0-9]*\\)?]", "i");
                const attributeName = $(this).attr('name').replace(pattern, 'purchase_orders[' + index + ']');
                $(this).attr('name', attributeName);
            });
        });
    }
};