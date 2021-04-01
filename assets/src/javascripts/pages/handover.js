import variables from "../components/variables";

export default function () {
    /**
     * url: /purchasing/handover/{index?}
     * Validating purchase order to confirm the item and handover process
     * we check data-validate attribute to detect which action we need to be performed.
     */
    const tableHandover = $('#table-handover');
    const modalConfirmOrder = $('#modal-confirm-order');
    const modalValidation = $('#modal-validate');

    tableHandover.on('click', '.btn-confirm', function (e) {
        e.preventDefault();

        const url = $(this).attr('href');
        const requestTitle = $(this).closest('.row-handover').data('request-title');
        const requester = $(this).closest('.row-handover').data('requester');
        const noPurchase = $(this).closest('.row-handover').data('no-purchase');
        const receivedDate = $(this).closest('.row-handover').data('received-date');

        modalConfirmOrder.find('form').attr('action', url);
        modalConfirmOrder.find('#request-title').text(requestTitle);
        modalConfirmOrder.find('#requester').text(requester);
        modalConfirmOrder.find('#no-purchase').text(noPurchase);
        modalConfirmOrder.find('#received_date').val(receivedDate);

        modalConfirmOrder.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    tableHandover.on('click', '.btn-validate', function (e) {
        e.preventDefault();

        const label = $(this).closest('.row-handover').data('no-purchase');
        const url = $(this).attr('href');
        const validate = $(this).data('validate');

        modalValidation.find('button[type=submit]').removeClass('btn-danger').removeClass('btn-success');

        modalValidation.find('.modal-title').text('Reject Handover');
        modalValidation.find('button[type=submit]').text('UNCONFIRMED').addClass('btn-danger');

        modalValidation.find('form').attr('action', url);
        modalValidation.find('.validate-label').text(validate + ' ' + label);

        modalValidation.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

};