import formatter from '../components/formatter';
import variables from "../components/variables";
import setTableViewport from "../scripts/table-responsive";

export default function () {
    const modalOrder = $('#modal-order');

    let defaultTotalPrice = 0;
    let defaultOfferDiscountGoods = 0;
    let defaultOfferDiscountServices = 0;
    let defaultPriceServices = 0;

    $('.btn-order').on('click', function (e) {
        e.preventDefault();

        const url = $(this).attr('href');
        const vendor = $(this).closest('.row-requisition').data('vendor');
        const isPkp = $(this).closest('.row-requisition').data('is-pkp');
        const requestTitle = $(this).closest('.row-requisition').data('request-title');
        const offerDiscountGoods = $(this).closest('.row-requisition').data('offer-discount-goods');
        const offerDiscountServices = $(this).closest('.row-requisition').data('offer-discount-services');
        const totalPriceServices = $(this).closest('.row-requisition').data('total-price-services');
        const totalPriceBeforeOfferDiscount = $(this).closest('.row-requisition').data('total-price-before-offer-discount');
        const totalPrice = $(this).closest('.row-requisition').data('total-price');
        const previewUrl = $(this).closest('.row-requisition').data('preview-url');

        defaultTotalPrice = Number(totalPriceBeforeOfferDiscount);
        defaultOfferDiscountGoods = Number(offerDiscountGoods);
        defaultOfferDiscountServices = Number(offerDiscountServices);
        defaultPriceServices = Number(totalPriceServices);

        modalOrder.find('form').attr('action', url);

        modalOrder.find('#vendor-title').text(vendor);
        modalOrder.find('#requisition-title').text(requestTitle);
        modalOrder.find('#label-total-services').text('Rp. ' + formatter.numberFormat(totalPriceServices));
        modalOrder.find('#label-offer-discount').text('Rp. ' + formatter.numberFormat(offerDiscountServices));
        modalOrder.find('#btn-preview').data('url', previewUrl);
        modalOrder.find('#total').val('Rp. ' + formatter.numberFormat(totalPrice));
        modalOrder.find('#offer_discount_goods').val('Rp. ' + formatter.numberFormat(offerDiscountGoods));
        modalOrder.find('#offer_discount_services').val('Rp. ' + formatter.numberFormat(offerDiscountServices));

        if (isPkp == 1) {
            modalOrder.find('#tax_nominal').prop('checked', false);
            modalOrder.find('#tax_percentage').prop('checked', true);
            modalOrder.find('#tax').val(10);
        } else {
            modalOrder.find('#tax_nominal').prop('checked', false);
            modalOrder.find('#tax_percentage').prop('checked', false);
            modalOrder.find('#tax').val(0);
        }

        modalOrder.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    modalOrder.on('show.bs.modal', () => {
        calculateOrder();
        modalOrder.find('[name=tax_basis_label]').val('').prop('readonly', true);
        modalOrder.find('[name=tax_basis]').val('').prop('readonly', true);
        modalOrder.find('[for=tax_basis]').text('Tax Basis');
        if (defaultPriceServices <= 0) {
            modalOrder.find('[name=tax_basis_type]').prop('checked', false).prop('disabled', true);
        }
    });

    function calculateOrder() {
        const deliveryCost = formatter.getNumberValue(modalOrder.find('[name=delivery_cost]').val());
        let miscExpense = formatter.getNumberValue(modalOrder.find('[name=miscellaneous_expense]').val());

        const miscExpenseSign = modalOrder.find('[name=miscellaneous_expense_sign]').val();
        if (miscExpenseSign === '-') {
            miscExpense *= -1;
        }

        let roundingGoods = formatter.getNumberValue(modalOrder.find('[name=rounding_goods]').val());
        let roundingServices = formatter.getNumberValue(modalOrder.find('[name=rounding_services]').val());

        const roundingGoodsSign = modalOrder.find('[name=rounding_goods_sign]').val();
        if (roundingGoodsSign === '-') {
            roundingGoods *= -1;
        }
        const roundingServicesSign = modalOrder.find('[name=rounding_services_sign]').val();
        if (roundingServicesSign === '-') {
            roundingServices *= -1;
        }

        const offerDiscountGoods = formatter.getNumberValue(modalOrder.find('[name=offer_discount_goods]').val());
        const offerDiscountServices = formatter.getNumberValue(modalOrder.find('[name=offer_discount_services]').val());

        const totalBeforeVAT = (defaultTotalPrice - offerDiscountGoods - offerDiscountServices) + roundingGoods + roundingServices;
        const taxType = modalOrder.find('[name=tax_type]:checked').val();
        let tax = formatter.getNumberValue(modalOrder.find('[name=tax]').val());
        let taxAmount = 0;
        if (taxType === 'percentage') {
            taxAmount = Math.floor(tax / 100 * totalBeforeVAT);
        } else {
            taxAmount = tax;
        }

        const totalServicePriceAfterDiscount = defaultPriceServices - offerDiscountServices;
        const taxBasisType = modalOrder.find('[name=tax_basis_type]:checked').val();
        let taxBasis = formatter.getNumberValue(modalOrder.find('[name=tax_basis]').val());
        let taxBasisAmount = 0;
        if (taxBasisType === 'percentage') {
            if (totalServicePriceAfterDiscount > 0) {
                taxBasisAmount = Math.floor(taxBasis / 100 * totalServicePriceAfterDiscount);
            } else {
                taxBasisAmount = 0;
            }
        } else {
            taxBasisAmount = formatter.getNumberValue(taxBasis);
        }

        if (taxBasisType) {
            if (defaultPriceServices <= 0) {
                modalOrder.find('[name=tax_basis_label]').val('').prop('readonly', true);
                modalOrder.find('[name=tax_basis]').val('').prop('readonly', true);
                modalOrder.find('[name=tax_basis_type]').prop('checked', false).prop('disabled', true);
            } else {
                modalOrder.find('[name=tax_basis_label]').prop('readonly', false);
                modalOrder.find('[name=tax_basis]').prop('readonly', false);
                modalOrder.find('[name=tax_basis_type]').prop('disabled', false);
            }
        }

        modalOrder.find('#total').val('Rp. ' + formatter.numberFormat(totalBeforeVAT + taxAmount + deliveryCost + miscExpense - taxBasisAmount));

        return {
            offerDiscountGoods, offerDiscountServices,
            taxType, tax, taxAmount,
            roundingGoods, roundingServices,
            taxBasisType, taxBasis, taxBasisAmount,
            deliveryCost, miscExpense
        };
    }

    modalOrder.find('[name=offer_discount_goods], [name=offer_discount_services], [name=delivery_cost], [name=miscellaneous_expense], [name=miscellaneous_expense_sign], [name=tax], [name=rounding_goods], [name=rounding_services], [name=tax_basis], [name=rounding_goods_sign], [name=rounding_services_sign]').on('input change', function () {
        calculateOrder();
        const offerDiscountServices = formatter.getNumberValue(modalOrder.find('[name=offer_discount_services]').val());
        modalOrder.find('#label-offer-discount').text('Rp. ' + formatter.numberFormat(offerDiscountServices));
    });

    modalOrder.find('[name=tax_type]').on('change', function () {
        const taxField = modalOrder.find('[name=tax]');
        const taxLabel = modalOrder.find('[for=tax]');
        if ($(this).val() === 'percentage') {
            taxField.attr('type', 'number').attr('min', 0).removeClass('currency').val('10');
            taxLabel.text('Tax Percent (VAT %)');
        } else {
            taxField.attr('type', 'text').removeAttr('min').addClass('currency').val('Rp. 0');
            taxLabel.text('Tax Amount (VAT)');
        }
        calculateOrder();
    });

    modalOrder.find('[name=tax_basis_type]').on('change', function () {
        const taxBasisField = modalOrder.find('[name=tax_basis]');
        const taxBasisLabel = modalOrder.find('[for=tax_basis]');
        const totalServicePriceAfterDiscount = defaultPriceServices - defaultOfferDiscountServices;
        if ($(this).val() === 'percentage') {
            taxBasisField.prop('disabled', totalServicePriceAfterDiscount <= 0)
				.addClass('numeric').removeClass('currency').val('0');
            taxBasisLabel.text('Tax Basis (Percent %)');
        } else {
            taxBasisField.prop('disabled', totalServicePriceAfterDiscount <= 0)
				.removeClass('numeric').addClass('currency').val('Rp. 0');
            taxBasisLabel.text('Tax Basis Amount');
        }
        calculateOrder();
    });

    modalOrder.find('#btn-preview').on('click', function () {
        const orderData = calculateOrder();
        const noPurchaseOrder = modalOrder.find('[name=no_purchase]').val();
        const purchaseDate = modalOrder.find('[name=date]').val();
        const taxBasisLabel = modalOrder.find('[name=tax_basis_label]').val();
        const miscLabel = modalOrder.find('[name=miscellaneous_label]').val();

        const notes = $("input[name='note[]']").map(function () {
            return $(this).val();
        }).get();
        const note = notes.join('||');

        // const queryString = $('#form-order').serialize();
        const queryString = `no_purchase=${noPurchaseOrder}&purchase_date=${purchaseDate}&offer_discount_goods=${orderData.offerDiscountGoods}&offer_discount_services=${orderData.offerDiscountServices}&tax_type=${orderData.taxType}&tax=${orderData.tax}&rounding_goods=${orderData.roundingGoods}&rounding_services=${orderData.roundingServices}&tax_basis_type=${orderData.taxBasisType}&tax_basis=${orderData.taxBasis}&tax_basis_label=${taxBasisLabel}&delivery_cost=${orderData.deliveryCost}&miscellaneous_expense=${orderData.miscExpense}&miscellaneous_label=${miscLabel}&description=${note}`;
        $(this).attr('href', $(this).data('url') + `?${queryString}`);
    });

    // Dynamic note order
    modalOrder.find('.btn-add-note').on('click', function () {
        if ($('#note-wrapper tr').length < 5) {
            const lastRow = modalOrder.find('#note-wrapper tr').length;
            const templateRow = `
                <tr>
                    <td style="width:10px;">${lastRow + 1}</td>
                    <td>
                        <input type="text" class="form-control" id="note[]" name="note[]" maxlength="300" 
                            placeholder="Purchase note ${lastRow + 1}">
                    </td>
                    <td style="width: 30px">
                        <button class="btn btn-danger btn-sm btn-delete">
                            <i class="mdi mdi-trash-can-outline"></i>
                        </button>
                    </td>
                </tr>
            `;
            modalOrder.find('#note-wrapper').append(templateRow);
            orderNote();
        } else {
            alert('Maximum 5 item list of notes');
        }
    });

    $('#note-wrapper').on('click', '.btn-delete', function () {
        $(this).closest('tr').remove();
        orderNote();
    });

    function orderNote() {
        $('#note-wrapper').find('tr').each(function (index, element) {
            $(element).find('td').first().text(index + 1);
            $(element).find('input').attr('placeholder', 'Purchase note ' + (index + 1))
        });
    }


    const tableOrder = $('#table-order');
    const modalAskConfirmation = $('#modal-ask-confirmation');
    const modalCompleteOrder = $('#modal-complete-order');
    const modalNotifyRequester = $('#modal-notify-requester');

    $('body').on('click', '.btn-ask-confirmation', function (e) {
        e.preventDefault();

        const url = $(this).attr('href');
        const requestTitle = $(this).closest('.row-order').data('request-title');
        const requester = $(this).closest('.row-order').data('requester');
        const noPurchase = $(this).closest('.row-order').data('no-purchase');
        const receivingNote = $(this).closest('.row-order').data('receiving-note');

        modalAskConfirmation.find('form').attr('action', url);
        modalAskConfirmation.find('#request-title').text(requestTitle);
        modalAskConfirmation.find('#requester').text(requester);
        modalAskConfirmation.find('#no-purchase').text(noPurchase);
        if(receivingNote) {
            modalAskConfirmation.find('#receiving-note').text('RECEIVING NOTE: ' + receivingNote);
        } else {
            modalAskConfirmation.find('#receiving-note').text('Make sure the items / services have delivered to requester');
        }

        modalAskConfirmation.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    tableOrder.on('click', '.btn-complete-order', function (e) {
        e.preventDefault();

        const url = $(this).attr('href');
        const requestTitle = $(this).closest('.row-order').data('request-title');

        modalCompleteOrder.find('form').attr('action', url);
        modalCompleteOrder.find('#request-title').text(requestTitle);

        modalCompleteOrder.modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    tableOrder.on('click', '.btn-notify-requester', function (e) {
        e.preventDefault();

        const url = $(this).attr('href');
        const email = $(this).closest('.row-order').data('email');
        const requester = $(this).closest('.row-order').data('requester');
        const requestTitle = $(this).closest('.row-order').data('request-title');

        modalNotifyRequester.find('form').attr('action', url);
        modalNotifyRequester.find('#request-title').text(requestTitle);
        modalNotifyRequester.find('#requester-name').text(requester);
        modalNotifyRequester.find('#requester-email').text(email || 'No email (required)');

        modalNotifyRequester.modal({
            backdrop: 'static',
            keyboard: false
        });
    });


    const controlTemplate = $('#control-order-template').html();
    const queryString = window.location.search.slice(1);
    tableOrder.DataTable({
        language: {
            processing: "Loading...",
            searchPlaceholder: "Search order data"
        },
        pageLength: 25,
        processing: true,
        serverSide: true,
        ajax: {
            url: variables.baseUrl + 'purchasing/order/data?' + queryString,
            data: function (data) {
                data['order'].forEach(function (items, index) {
                    data['order'][index]['column'] = data['columns'][items.column]['name'];
                });
            }
        },
        columns: [
            {name: 'no', data: 'no', class: 'text-md-center'},
            {name: 'no_purchase', data: 'no_purchase'},
            {name: 'request_title', data: 'request_title'},
            {name: 'vendor', data: 'vendor'},
            {name: 'top', data: 'top'},
            {name: 'lead_time', data: 'lead_time'},
            {name: 'date', data: 'date'},
            {name: 'total_price', data: 'total_price'},
            {name: 'status', data: 'status'},
            {name: 'document_status', data: 'document_status'},
            {name: 'id', data: 'id', class: 'text-md-right'},
        ],
        columnDefs: [{
            targets: 2,
            render: function (data, type, full) {
                return `
                    <a href="${variables.baseUrl}requisition/view/${full.id_requisition}">
                        ${full.request_title}
                    </a>
                `;
            }
        }, {
            targets: 3,
            render: function (data, type, full) {
                return `
                    <a href="${variables.baseUrl}purchasing/offer/view/${full.id_purchase_offer}">
                        ${full.vendor}
                    </a>
                `;
            }
        }, {
            targets: 'type-date',
            render: function (data) {
                return moment(data).format('D MMMM YYYY');
            }
        }, {
            targets: 'type-numeric',
            render: function (data) {
                return formatter.numberFormat(data);
            }
        }, {
            targets: 'type-currency',
            render: function (data) {
                return formatter.setCurrencyValue(data, 'Rp. ', ',', '.');
            }
        }, {
            targets: 'type-status',
            render: function (data, type, full) {
                let status = 'secondary';
                if (data === 'SELECTED') {
                    status = 'info';
                } else if (data === 'ORDERED') {
                    status = 'warning'
                } else if (data === 'ASK CONFIRMATION') {
                    status = 'warning'
                } else if (data === 'CONFIRMED') {
                    status = 'primary'
                } else if (data === 'UNCONFIRMED') {
                    status = 'danger'
                } else if (data === 'COMPLETED') {
                    status = 'success'
                }
                if (data === 'ASK CONFIRMATION') {
					data = 'ASK';
				}

                let additionalInfo = '';
                if (full.rate_type === 'ADJUSTABLE') {
                	additionalInfo = '<span class="badge badge-danger">ADJUSTABLE</span>'
				}

                return `
                    <span class="badge badge-${status}">
                        ${data}
                    </span>
                    ${additionalInfo}
                `;
            }
        }, {
            targets: 'type-document-status',
            render: function (data, type, full) {
                let statusPO = 'secondary';
                if (data === 'CANCELED') {
					statusPO = 'danger';
				} else if (data === 'DRAFT') {
					statusPO = 'warning'
				} else if (data === 'PUBLISHED') {
					statusPO = 'success'
                }
                if(full.payment_handover_doc == null){
                    return `
                        <span class="badge badge-${statusPO}">
                            ${data}
                        </span>
                    `;
                }else{
                    return `
                        <span class="badge badge-${statusPO}">
                            ${data}
                        </span>
                        <span class="badge badge-info">
                            HANDOVER
                        </span>
                    `;

                }
            }
        }, {
            targets: 'type-action',
            data: 'id',
            render: function (data, type, full) {
                let control = controlTemplate
                    .replace(/{{id}}/g, full.id)
                    .replace(/{{no_purchase}}/g, full.no_purchase)
                    .replace(/{{employee_email}}/g, full.employee_email)
                    .replace(/{{employee_name}}/g, full.employee_name)
                    .replace(/{{request_title}}/g, full.request_title)
                    .replace(/{{receiving_note}}/g, full.receiving_note)
                    .replace(/{{vendor}}/g, full.vendor);

                control = $.parseHTML(control);
                if (['ORDERED', 'UNCONFIRMED'].indexOf(full.status) === -1 || full.rate_type === 'ADJUSTABLE') {
                    $(control).find('.btn-ask-confirmation').remove();
                }
                if (!(full.status === 'CONFIRMED' || (full.status === 'ORDERED' && full.rate_type === 'ADJUSTABLE'))) {
                    $(control).find('.btn-complete-order').remove();
                }
                if (full.document_status !== 'PUBLISHED') {
                	if (full.document_status === 'DRAFT') {
						$(control).find('.label-order').text('Draft');
					}
                	$(control).find('.btn-print-handover').remove();
                	$(control).find('.btn-view-handover').remove();
                	$(control).find('.btn-ask-confirmation').remove();
                	$(control).find('.btn-complete-order').remove();
                	$(control).find('.btn-notify-requester').remove();
				} else {
					$(control).find('.btn-item-completion').remove();
				}

                return $('<div />').append($(control).clone()).html();
            }
        }, {
            targets: '_all',
            render: function (data) {
                return $.trim(data) === '' ? '-' : data;
            }
        }],
        drawCallback: function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-sm');
            setTableViewport();
            setTimeout(function () {
                tableOrder.removeAttr('style');
            }, 300);
        }
    });

    const modalDraft = $('#modal-draft');
    $('.btn-draft').on('click', function (e) {
    	e.preventDefault();

		const purchaseOfferId = $(this).closest('.row-requisition').data('id-purchase-offer');
		const label = $(this).closest('.row-requisition').data('request-title');
		const url = $(this).attr('href');

		modalDraft.find('form').attr('action', url);
		modalDraft.find('.draft-label').text(label);
		modalDraft.find('.btn-preview-email').attr('data-href', modalDraft.find('.btn-preview-email').data('url') + '/' + purchaseOfferId);

		modalDraft.modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	modalDraft.find('.btn-preview-email').on('click', function () {
		const message = modalDraft.find('#message').val();
		const queryString = `message=${message}`;

		$(this).attr('href', $(this).data('href') + `?${queryString}`);
	});

};
