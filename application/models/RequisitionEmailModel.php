<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RequisitionEmailModel extends App_Model
{
    protected $table = 'requisition_emails';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                'ref_vendors.vendor',
                'prv_users.name AS sender_name'
            ])
            ->join(UserModel::$tableUser, 'prv_users.id = requisition_emails.sent_by')
            ->join('ref_vendors', 'ref_vendors.id = requisition_emails.id_vendor');
    }
}