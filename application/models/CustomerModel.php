<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerModel extends App_Model
{
    protected $table = 'ref_people';

    public static $TYPE_CUSTOMER = 'CUSTOMER';

     /**
     * CustomerModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->config->item('sso_enable')) {
            $this->table = env('DB_WAREHOUSE_DATABASE') . '.ref_people';
        }
    }

     /**
     * Get people base query.
     *
     * @param null $branchId
     * @param bool $withTrashed
     * @return CI_DB_query_builder
     */
    public function getBaseQuery( $withTrashed = false)
    {
        
        $BaseQuery = $this->db
            ->select('*')
            ->from($this->table)
            ->where('ref_people.type', self::$TYPE_CUSTOMER)
            ->order_by('id');

        if (!$withTrashed) {
            $BaseQuery->where('ref_people.is_deleted', false);
        }

        return $BaseQuery;
    }
}
