<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Search
 * @property RequisitionModel $requisition
 * @property PurchaseOfferModel $purchaseOffer
 * @property PurchaseOrderModel $purchaseOrder
 */
class Search extends App_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('RequisitionModel', 'requisition');
        $this->load->model('PurchaseOfferModel', 'purchaseOffer');
        $this->load->model('PurchaseOrderModel', 'purchaseOrder');
    }

    /**
     * Show result search page.
     */
    public function index()
    {
        $q = get_url_param('q');
        $requisitions = $this->requisition->search($q, 5);
        $purchaseOffers = $this->purchaseOffer->search($q, 5);
        $purchaseOrders = $this->purchaseOrder->search($q, 5);

        $this->render('search/index', compact('q', 'requisitions', 'purchaseOffers', 'purchaseOrders'));
    }
}
