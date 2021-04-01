
export default function () {
    const modalDelete = $('#modal-delete');
    const tableAddComparison =  $('#table-add-comparison');
    tableAddComparison.on('click', '.btn-delete', function (e) {
        e.preventDefault();
        const label = $(this).data('request-title');
        const url = $(this).attr('href');

        modalDelete.find('button[type=submit]').removeClass('btn-danger').removeClass('btn-success');
        
        modalDelete.find('.modal-title').text('Delete Requisition');
        modalDelete.find('button[type=submit]').text('Delete').addClass('btn-success');
        

        modalDelete.find('form').attr('action', url);
        modalDelete.find('.delete-label').text(label);

        modalDelete.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

};