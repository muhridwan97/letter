<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendorModel extends App_Model
{
    protected $table = 'ref_vendors';

    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    protected function getBaseQuery()
    {
        $this->addFilteredField([
            'ref_categories.category',
            'ref_item_categories.item_name',
        ]);

        return parent::getBaseQuery()
            ->select([
                'vendor_contacts.name AS contact_person',
                'vendor_contacts.contact',
                'vendor_contacts.email',
                'vendor_banks.bank',
                'vendor_banks.account_name',
                'vendor_banks.account_number',
                'COUNT(DISTINCT purchase_offers.id) AS total_offer',
                'COUNT(DISTINCT purchase_orders.id) AS total_selected',
                'AVG(purchase_orders.rating) AS average_rating',
                'GROUP_CONCAT(ref_categories.category) AS categories',
                'MAX(ref_vendor_locations.city) AS new_city', // TODO: which city should show up? group by problem!
                'MAX(ref_vendor_locations.address) AS new_address',
                'MAX(ref_vendor_locations.office_phone) AS new_office_phone',
                "GROUP_CONCAT(DISTINCT ref_vendor_locations.city ORDER BY ref_vendor_locations.id ASC SEPARATOR ', ') AS cities",
                "GROUP_CONCAT(DISTINCT ref_cities.province ORDER BY ref_vendor_locations.id SEPARATOR ', ') AS provinces ",
                "GROUP_CONCAT(DISTINCT ref_vendor_support_cities.city ORDER BY ref_vendor_support_cities.id ASC SEPARATOR ', ') AS support_cities",
            ])
            ->join('(
                SELECT DISTINCT id_vendor, name, contact, email 
                FROM ref_vendor_contacts 
                WHERE is_primary = 1
                ) AS vendor_contacts', 'vendor_contacts.id_vendor = ref_vendors.id', 'left')
            ->join('(
                SELECT DISTINCT id_vendor, bank, account_name, account_number 
                FROM ref_vendor_banks 
                WHERE is_primary = 1
                ) AS vendor_banks', 'vendor_banks.id_vendor = ref_vendors.id', 'left')
            ->join('(
                SELECT id, id_vendor 
                FROM purchase_offers WHERE is_deleted = 0
                ) AS purchase_offers', 'purchase_offers.id_vendor = ref_vendors.id', 'left')
            ->join('(
                SELECT id, id_purchase_offer, rating 
                FROM purchase_orders WHERE is_deleted = 0
                ) AS purchase_orders', 'purchase_orders.id_purchase_offer = purchase_offers.id', 'left')
            ->join('ref_vendor_categories', 'ref_vendor_categories.id_vendor = ref_vendors.id', 'left')
            ->join('ref_categories', 'ref_categories.id = ref_vendor_categories.id_category', 'left')
            ->join('ref_vendor_item_categories', 'ref_vendor_item_categories.id_vendor = ref_vendors.id', 'left')
            ->join("ref_vendor_locations",'ref_vendor_locations.id_vendor = ref_vendors.id', 'left')
            ->join("ref_cities",'ref_cities.city = ref_vendor_locations.city', 'left')
            ->join('ref_item_categories', 'ref_item_categories.id = ref_vendor_item_categories.id_item_category', 'left')
            ->join("ref_vendor_support_cities",'ref_vendor_support_cities.id_vendor = ref_vendors.id', 'left')
            ->group_by('ref_vendors.id, vendor_contacts.name, vendor_contacts.contact, vendor_contacts.email, vendor_banks.bank, vendor_banks.account_name, vendor_banks.account_number');
    }

    /**
     * Get all data model.
     *
     * @param array $filters
     * @param bool $withTrashed
     * @return mixed
     */
    public function getAll($filters = [], $withTrashed = false)
    {
        $this->db->start_cache();

        $baseQuery = $this->getBaseQuery();

        if (!$withTrashed && $this->db->field_exists('is_deleted', $this->table)) {
            $baseQuery->where($this->table . '.is_deleted', false);
        }

        if (key_exists('is_active', $filters) && !empty($filters['is_active'])) {
            if ($filters['is_active']=='no') {
                $baseQuery->where($this->table . '.is_active=0', null);
            }else{
                $baseQuery->where($this->table . '.is_active', $filters['is_active']);
            }
        }

        if (!empty($filters)) {
            if (key_exists('query', $filters) && $filters['query']) {
                return $baseQuery;
            }

            if (key_exists('search', $filters) && !empty($filters['search'])) {
                $baseQuery->group_start();
                foreach ($this->filteredFields as $filteredField) {
                    if ($filteredField == '*') {
                        $fields = $this->db->list_fields($this->table);
                        foreach ($fields as $field) {
                            $baseQuery->or_like($this->table . '.' . $field, trim($filters['search']));
                        }
                    } else {
                        $baseQuery->or_like($filteredField, trim($filters['search']));
                    }
                }
                $baseQuery->group_end();
            }

            if (key_exists('rating', $filters)) {
                if ($filters['rating'] === '0') {
                    $baseQuery->having('average_rating', null);
                } else if ($filters['rating'] > 0) {
                    $baseQuery->having('average_rating', $filters['rating']);
                }
            }

            if (key_exists('activity', $filters) && !empty($filters['activity'])) {
                if ($filters['activity'] == 'ACTIVE') {
                    $baseQuery->having('total_selected>', 0);
                } else if ($filters['activity'] == 'INACTIVE') {
                    $baseQuery->having('total_selected', 0);
                }
            }

            if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
                $baseQuery->where($this->table . '.created_at>=', format_date($filters['date_from']));
            }

            if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
                $baseQuery->where($this->table . '.created_at<=', format_date($filters['date_to']));
            }

            if (key_exists('cities', $filters) && !empty($filters['cities'])) {
                $baseQuery->group_start();
                $baseQuery->where_in('ref_vendor_locations.city' ,$filters['cities']);
                $baseQuery->or_where_in('ref_vendor_support_cities.city' ,$filters['cities']);
                $baseQuery->group_end();
            }

            if (key_exists('provinces', $filters) && !empty($filters['provinces'])) {
                $baseQuery->where_in('ref_cities.province' ,$filters['provinces']);
            }

            if (key_exists('categories', $filters) && !empty($filters['categories'])) {
                $baseQuery->where_in('ref_categories.category' ,$filters['categories']);
            }

            if (key_exists('item_categories', $filters) && !empty($filters['item_categories'])) {
                $baseQuery->where_in('ref_item_categories.item_name' ,$filters['item_categories']);
            }

            if (key_exists('item_category', $filters) && !empty($filters['item_category'])) {
                $baseQuery->group_start();
                $baseQuery->or_like('ref_item_categories.item_name', trim($filters['item_category']));
                $baseQuery->group_end();
            }
        }
        $this->db->stop_cache();

        if (key_exists('per_page', $filters) && !empty($filters['per_page'])) {
            $perPage = $filters['per_page'];
        } else {
            $perPage = 25;
        }

        if (key_exists('page', $filters) && !empty($filters['page'])) {
            $currentPage = $filters['page'];

            $totalData = $this->db->count_all_results();

            if (key_exists('sort_by', $filters) && !empty($filters['sort_by'])) {
                if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
                    $baseQuery->order_by($filters['sort_by'], $filters['order_method']);
                } else {
                    $baseQuery->order_by($filters['sort_by'], 'asc');
                }
            } else {
                $baseQuery->order_by($this->table . '.' . $this->id, 'desc');
            }
            $pageData = $baseQuery->limit($perPage, ($currentPage - 1) * $perPage)->get()->result_array();

            $this->db->flush_cache();

            return [
                '_paging' => true,
                'total_data' => $totalData,
                'total_page_data' => count($pageData),
                'total_page' => ceil($totalData / $perPage),
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'data' => $pageData
            ];
        }

        if (key_exists('sort_by', $filters) && !empty($filters['sort_by'])) {
            if (key_exists('order_method', $filters) && !empty($filters['order_method'])) {
                $baseQuery->order_by($filters['sort_by'], $filters['order_method']);
            } else {
                $baseQuery->order_by($filters['sort_by'], 'asc');
            }
        } else {
            $baseQuery->order_by($this->table . '.' . $this->id, 'desc');
        }

        $data = $baseQuery->get()->result_array();

        $this->db->flush_cache();

        return $data;
    }
}
