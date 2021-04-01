<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RequisitionStatusModel extends App_Model
{
    protected $table = 'requisition_statuses';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()
            ->select([
                'prv_users.name AS creator_name'
            ])
            ->join(UserModel::$tableUser, 'prv_users.id=requisition_statuses.created_by', 'left');
    }
}