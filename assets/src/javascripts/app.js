// import global variable
import variables from "./components/variables";
import notification from './components/notification';

// jquery and bootstrap is main library of this app.
try {
    // get jquery ready in global scope
    window.$ = window.jQuery = require('jquery');
    $.ajaxSetup({
        headers: {
            "X-CSRFToken": variables.csrfToken
        }
    });

    // loading library core
    require('bootstrap');
    require('jquery-validation');
    window.moment = require('moment');
    require('daterangepicker');
    require('select2');
    //require('datatables.net');
    require('datatables.net-bs4');

    // loading misc scripts
    require('./scripts/validation');
    require('./scripts/custom-upload-button');
    require('./scripts/table-responsive');
    require('./scripts/currency-value');
    require('./scripts/one-touch-submit');
    require('./scripts/miscellaneous');

    // init notification Pusherjs
    notification();

    // load async page scripts
    if ($('#modal-delete').length) {
        import("./components/delete").then(modalDelete => modalDelete.default());
    }

    if ($('.btn-delete').length) {
        import("./components/delete").then(modalDelete => modalDelete.default());
    }

    if ($('#form-role').length) {
        import("./pages/role").then(role => role.default());
    }

    if ($('#form-vendor').length || $('#form-vendor-offer').length) {
        import("./pages/vendor").then(vendor => vendor.default());
    }

    if ($('#table-requisition').length || $('#form-requisition').length) {
        import("./pages/requisition").then(requisition => requisition.default());
    }

    if ($('#form-set-category').length) {
        import("./pages/item-category").then(itemCategory => itemCategory.default());
    }

    if ($('#table-offer').length || $('#form-offer').length) {
        import("./pages/offer").then(offer => offer.default());
    }

    if ($('#table-comparison').length || $('#table-selection').length) {
        import("./pages/selection").then(selection => selection.default());
    }

    if ($('#table-handover').length) {
        import("./pages/handover").then(handover => handover.default());
    }

    if ($('#table-order').length || $('#form-order').length) {
        import("./pages/order").then(order => order.default());
    }

    if ($('#form-item-completion').length) {
        import("./pages/item-completion").then(itemCompletion => itemCompletion.default());
    }

    if ($('#table-category-period-report').length) {
        import("./pages/category-period").then(categoryPeriod => categoryPeriod.default());
    }

	if ($('.btn-toggle-expand').length || $('.btn-toggle-expand-all').length) {
		import("./pages/section-toggle").then(sectionToggle => sectionToggle.default());
    }
    
    if ($('#table-add-comparison').length) {
        import("./pages/item-comparison").then(itemComparison => itemComparison.default());
    }

    if ($('#table-purchase-order').length) {
        import("./pages/payment-handover").then(paymentHandover => paymentHandover.default());
    }
} catch (e) {
    console.log(e);
}

// include sass (but extracted by webpack into separated css file)
import '../sass/plugins.scss';
import '../sass/app.scss';
