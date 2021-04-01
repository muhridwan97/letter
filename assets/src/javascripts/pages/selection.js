import showConfirm from '../components/confirm';

export default function () {
    const tableComparison = $('#table-comparison');
    const formSelection = $('#form-selection');
    const modalSelectItem = $('#modal-select-item');

    $('#modal-confirm').find('#btn-cancel').css('visibility', 'hidden');

    tableComparison.on('click', '.btn-select', function (e) {
        e.preventDefault();
        const rowOffer = $(this).closest('.row-offer');

        const vendor = rowOffer.data('vendor');
        const url = $(this).attr('href');
        const message = `
            Are you sure want to <span class="text-success">SELECT ALL</span> 
            vendor <strong>${vendor}</strong> for this requisition?
        `;

        showConfirm('Select vendor', message, function (e) {
            e.preventDefault();
            formSelection.attr('action', url);
            formSelection.submit();
        });
    });

    tableComparison.on('click', '.btn-select-item', function (e) {
        e.preventDefault();
        const rowOfferItem = $(this).closest('.row-offer-item');
        const item = rowOfferItem.data('item');
        const unit = rowOfferItem.data('unit');
        const quantity = rowOfferItem.data('quantity');
        const selected = rowOfferItem.data('selected');
        const maxAllowTake = $(this).data('max-allow-take');
        const url = $(this).attr('href');

        modalSelectItem.find('#item_name').val(item);
        modalSelectItem.find('#unit').val(`${quantity} ${unit}`);
        modalSelectItem.find('#quantity_select').val(selected || maxAllowTake || quantity).attr('max', maxAllowTake || quantity);
        modalSelectItem.find('form').attr('action', url);

        modalSelectItem.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    tableComparison.on('click', '.btn-cancel', function (e) {
        e.preventDefault();
        const rowOffer = $(this).closest('.row-offer');

        const vendor = rowOffer.data('vendor');
        const url = $(this).attr('href');
        const message = `
            Are you sure want to <span class="text-danger">CANCEL ALL</span> 
            vendor <strong>${vendor}</strong> for this requisition?
        `;

        showConfirm('Cancel vendor', message, function (e) {
            e.preventDefault();
            formSelection.attr('action', url);
            formSelection.submit();
        });
    });

    $('.btn-finish-selection').on('click', function (e) {
        e.preventDefault();

        const requestTitle = $(this).data('request-title');
        const url = $(this).attr('href');
        const message = `
            Are you sure finish selecting vendor offers for requisition <strong>${requestTitle}</strong>, 
            all unselected vendor will be excluded?
        `;

        showConfirm('Finishing Selection', message, function (e) {
            e.preventDefault();
            formSelection.attr('action', url);
            formSelection.submit();
        });
    });

    const modalAskSelection = $('#modal-ask-selection');
    $('.btn-ask-selection').on('click', function (e) {
        e.preventDefault();

        const row = $(this).closest('.row-requisition');
        modalAskSelection.find('#requisition-title').text(row.data('request-title'));
        modalAskSelection.find('#decision_maker').val(row.data('decision-maker-id')).trigger('change');
        modalAskSelection.find('#email').val(row.data('decision-maker-email'));
        modalAskSelection.find('#note').val(row.data('purchasing-note'));
        modalAskSelection.find('form').attr('action', $(this).attr('href'));

        modalAskSelection.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    const modalReadySelection = $('#modal-ready-selection');
    $('.btn-ready-selection').on('click', function (e) {
        e.preventDefault();

        const row = $(this).closest('.row-requisition');
        modalReadySelection.find('#requisition-title').text(row.data('request-title'));
        modalReadySelection.find('form').attr('action', $(this).attr('href'));

        modalReadySelection.modal({
            backdrop: 'static',
            keyboard: false
        });
    });
};
