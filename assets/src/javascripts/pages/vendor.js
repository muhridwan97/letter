import formatter from '../components/formatter';
import variables from '../components/variables';

export default function () {
    const formVendor = $('#form-vendor');
    const selectCategory = formVendor.find('#category');
    const selectItemCategory = formVendor.find('#item_category');
    const tableContact = formVendor.find('#table-contact');
    const tableBank = formVendor.find('#table-bank');
    const btnAddContact = formVendor.find('#btn-add-contact');
    const btnAddBank = formVendor.find('#btn-add-bank');

    /**
     * Form vendor interaction
     */
    let oldTaxNumber = '';
    const fieldHasTax = formVendor.find('[name=has_tax_number]');
    const fieldTaxNo = formVendor.find('#tax_number');
    const fieldTaxDoc = formVendor.find('.tax_file');
    fieldHasTax.on('change', function () {
        if ($(this).val() === '1') {
            fieldTaxNo.val(oldTaxNumber).attr('disabled', false);
            fieldTaxDoc.attr('disabled', false);
        } else {
            oldTaxNumber = fieldTaxNo.val();
            fieldTaxNo.val('').attr('disabled', true);
            fieldTaxDoc.attr('disabled', true);
        }
    });

    function deleteRow(e, table, name) {
        e.preventDefault();
        const row = $(this).closest('tr');
        row.remove();

        const primaryChecked = row.find('[type=radio]').first().is(':checked');
        if (primaryChecked) {
            table.find('[type=radio]').first().prop('checked', true);
        }

        reorderRows(table, name);
    }

    tableContact.on('click', '.btn-delete', function (e) {
        deleteRow.apply(this, [e, tableContact, 'contacts']);
    });
    tableBank.on('click', '.btn-delete', function (e) {
        deleteRow.apply(this, [e, tableBank, 'banks']);
    });

    function switchRadio() {
        $(this).closest('table').find('[type=radio]').not(this).prop('checked', false);
    }

    tableContact.on('change', '[type=radio]', switchRadio);
    tableBank.on('change', '[type=radio]', switchRadio);

    btnAddContact.on('click', function (e) {
        e.preventDefault();
        const lastRow = tableContact.find('tbody tr').length;

        const templateRow = `
            <tr>
                <td>${lastRow + 1}</td>
                <td>
                    <select class="custom-select" name="contacts[][type]">
                        <option value="WORK">WORK</option>
                        <option value="MOBILE">MOBILE</option>
                        <option value="PRIVATE">PRIVATE</option>
                        <option value="WHATSAPP">WHATSAPP</option>
                        <option value="OTHER">OTHER</option>
                    </select>
                </td>
                <td>
                    <select class="custom-select" name="contacts[][title]">
                        <option value="MR">MR</option>
                        <option value="MRS">MRS</option>
                    </select>
                </td>
                <td><input type="text" class="form-control" name="contacts[][name]" placeholder="Contact name" required></td>
                <td>
                    <select class="custom-select" name="contacts[][position]">
                        <option value="FINANCE">FINANCE</option>
                        <option value="MARKETING">MARKETING</option>
                        <option value="MANAGEMENT">MANAGEMENT</option>
                        <option value="OWNER">OWNER</option>
                        <option value="OTHER">OTHER</option>
                    </select>
                </td>
                <td><input type="tel" class="form-control" name="contacts[][contact]" placeholder="Contact number"></td>
                <td><input type="email" class="form-control" name="contacts[][email]" placeholder="Email address"></td>
                <td class="text-md-center">
                    <div class="form-check d-inline-block mt-0 mb-3 mx-auto">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="contacts[][is_primary]" 
                                id="contact_${lastRow + 1}" value="1" ${(lastRow === 0 ? 'checked' : '')}>
                            <i class="input-helper"></i>
                        </label>
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                        <i class="mdi mdi-trash-can-outline"></i>
                    </button>
                </td>
            </tr>
        `;
        tableContact.find('tbody').first().append(templateRow);
        reorderRows();
    });

    btnAddBank.on('click', function (e) {
        e.preventDefault();
        const lastRow = tableBank.find('tbody tr').length;

        const templateRow = `
            <tr>
                <td>${lastRow + 1}</td>
                <td><input type="text" class="form-control" name="banks[][bank]" placeholder="Bank name" required></td>
                <td><input type="text" class="form-control" name="banks[][account_name]" placeholder="Account name"></td>
                <td><input type="text" class="form-control" name="banks[][account_number]" placeholder="Account number"></td>
                <td class="text-md-center">
                    <div class="form-check d-inline-block mt-0 mb-3 mx-auto">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="banks[][is_primary]" 
                                id="contact_${lastRow + 1}" value="1" ${(lastRow === 0 ? 'checked' : '')}>
                            <i class="input-helper"></i>
                        </label>
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                        <i class="mdi mdi-trash-can-outline"></i>
                    </button>
                </td>
            </tr>
        `;
        tableBank.find('tbody').first().append(templateRow);
        reorderRows(tableBank, 'banks');
    });

    function reorderRows(table, name) {
        $(table).find('tbody tr').each(function (index) {
            // recount row number
            $(this).children('td').first().html((index + 1).toString());

            // reorder index of inputs
            $(this).find('[name]').each(function () {
                const pattern = new RegExp(name + "[([0-9]*\\)?]", "i");
                const attributeName = $(this).attr('name').replace(pattern, name + '[' + index + ']');
                $(this).attr('name', attributeName);
            });
        });
    }



    // vendor offer
    // add new item and save into vendor offer list.
    const formVendorOffer = $('#form-vendor-offer');
    const tableItem = formVendorOffer.find('#table-item');
    const btnAddItem = formVendorOffer.find('#btn-add-item');
    const modalFormOffer = $('#modal-form-offer');
    const formItem = modalFormOffer.find('form');

    let activeRow = null;

    btnAddItem.on('click', function (e) {
        e.preventDefault();

        activeRow = null;

        modalFormOffer.modal({
            backdrop: 'static',
            keyboard: false
        });

        clearItemInputs();
    });

    modalFormOffer.on('submit', function (e) {
        e.preventDefault();

        saveItem({
            item_name: modalFormOffer.find('#item_name').val() || '',
            brand: modalFormOffer.find('#brand').val() || '',
            price: modalFormOffer.find('#price').val() || 'Rp. 0',
            discount: modalFormOffer.find('#discount').val() || 'Rp. 0',
            price_date: modalFormOffer.find('#price_date').val() || '',
            price_until: modalFormOffer.find('#price_until').val() || '',
            description: modalFormOffer.find('#description').val() || '',
        }, activeRow);

        clearItemInputs();
        modalFormOffer.modal('hide');
    });

    tableItem.on('click', '.btn-delete', function (e) {
        e.preventDefault();

        $(this).closest('tr').remove();

        if (tableItem.find('tbody tr').length === 0) {
            tableItem.find('tbody').append(`
                <tr class="row-placeholder">
                    <td colspan="9">No items available</td>
                </tr>
            `);
        }

        reorderItemRows();
    });

    tableItem.on('click', '.btn-edit', function (e) {
        e.preventDefault();

        const row = $(this).closest('tr');
        activeRow = row;

        modalFormOffer.find('#item_name').val(row.find('#item_name').val() || '');
        modalFormOffer.find('#brand').val(row.find('#brand').val() || '');
        modalFormOffer.find('#price').val(formatter.setCurrencyValue(row.find('#price').val(), 'Rp. ') || 'Rp. 0');
        modalFormOffer.find('#discount').val(formatter.setCurrencyValue(row.find('#discount').val(), 'Rp. ') || 'Rp. 0');
        modalFormOffer.find('#price_date').val(row.find('#price_date').val() || '');
        modalFormOffer.find('#price_until').val(row.find('#price_until').val() || '');
        modalFormOffer.find('#description').val(row.find('#description').val());

        modalFormOffer.modal({
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
                    <td class="label-price">${item.price || 'Rp. 0'}</td>
                    <td class="label-discount">${item.discount || 'Rp. 0'}</td>
                    <td class="label-price-date">${item.price_date || '-'}</td>
                    <td class="label-price-until">${item.price_until || '-'}</td>
                    <td class="label-description">${item.description || '-'}</td>
                    <td class="text-right">
                        <input type="hidden" name="items[][item_name]" id="item_name" value="${item.item_name}">
                        <input type="hidden" name="items[][brand]" id="brand" value="${item.brand}">
                        <input type="hidden" name="items[][price]" id="price" value="${formatter.getNumberValue(item.price) || 0}">
                        <input type="hidden" name="items[][discount]" id="discount" value="${formatter.getNumberValue(item.discount) || 0}">
                        <input type="hidden" name="items[][price_date]" id="price_date" value="${item.price_date}">
                        <input type="hidden" name="items[][price_until]" id="price_until" value="${item.price_until}">
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

            reorderItemRows();
        } else {
            row.find('.label-item').text(item.item_name || 'No item');
            row.find('.label-brand').text(item.brand || '-');
            row.find('.label-price').text(item.price || 'Rp. 0');
            row.find('.label-discount').text(item.discount || 'Rp. 0');
            row.find('.label-price-date').text(item.price_date || '-');
            row.find('.label-price-until').text(item.price_until || '-');
            row.find('.label-description').text(item.description || '-');

            row.find('#item_name').val(item.item_name || '');
            row.find('#brand').val(item.brand || '');
            row.find('#price').val(formatter.getNumberValue(item.price));
            row.find('#discount').val(formatter.getNumberValue(item.discount));
            row.find('#price_date').val(item.price_date || '');
            row.find('#price_until').val(item.price_until || '');
            row.find('#description').val(item.description || '');
        }
    }

    function clearItemInputs() {
        modalFormOffer.find('#item_name').val('');
        modalFormOffer.find('#brand').val('');
        modalFormOffer.find('#price').val('Rp. ');
        modalFormOffer.find('#discount').val('Rp. ');
        modalFormOffer.find('#price_date').val('');
        modalFormOffer.find('#price_until').val('');
        modalFormOffer.find('#description').val('');
        formItem.removeClass('was-validated');
    }

    function reorderItemRows() {
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

    const locationTemplate = $('#location-template').html();
    const btnAddLocation = formVendor.find('#btn-add-location');
    let locationWrapper = formVendor.find('#location-wrapper');
    let totalLocation = locationWrapper.children().length;


    btnAddLocation.on('click', function (e) {
        e.preventDefault();
        locationWrapper.append(
            locationTemplate
                .replace(/{{no}}/g, (totalLocation + 1))
                .replace(/{{index}}/g, totalLocation)
        );
        $(".city").select2();
        totalLocation++;
    });
    locationWrapper.on('click', '.btn-remove-location', function (e) {
        e.preventDefault();
        var btnSubmit = $(this).closest('form');
        btnSubmit.find(':submit').attr('disabled', false);

        totalLocation--;

        $(this).closest('.card-location').remove();

    });

    selectCategory.on('change', function () {
        const categoryId = $(this).val();
        selectItemCategory.empty().prop("disabled", true);
        fetch(`${variables.baseUrl}master/vendor_create/ajax-get-item-category?id_category=${categoryId}`)
            .then(result => result.json())
            .then(data => {
                selectItemCategory.prop("disabled", false);
                data.forEach(row => {
                    selectItemCategory.append(
                        $('<option>', {value: row.id}).text(row.item_name)
                    );
                });                
            })
            .catch(console.log);
    });
};