<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HeavyEquipmentEntryPermitModel extends App_Model
{
    protected $table = 'heavy_equipment_entry_permits';

    public function __construct()
    {
        if ($this->config->item('sso_enable')) {
            $this->table = env('DB_WAREHOUSE_DATABASE') . '.heavy_equipment_entry_permits';
        }
    }
    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery();
    }
    /**
     * Get data by custom condition.
     *
     * @param $id
     * @param bool $resultRow
     * @param bool $withTrashed
     * @return array|int
     */
    public function getByIdRequisition($id, $resultRow = false, $withTrashed = false)
    {
        $baseQuery = $this->getBaseQuery()->order_by($this->table . '.id', 'desc');

        $baseQuery->where($this->table . '.id_requisition',$id);

        if (!$withTrashed && $this->db->field_exists('is_deleted', $this->table)) {
            $baseQuery->where($this->table . '.is_deleted', false);
        }

        if($resultRow === 'COUNT') {
            return $baseQuery->count_all_results();
        } else if ($resultRow) {
            return $baseQuery->get()->row_array();
        }

        return $baseQuery->get()->result_array();
    }
}