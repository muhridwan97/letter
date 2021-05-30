<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SkripsiModel extends App_Model
{
    protected $table = 'skripsis';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PENDING = 'PENDING';
    const STATUS_REJECTED = 'REJECTED';
    /**
     * Get base query of table.
     *
     * @return CI_DB_query_builder
     */
    public function getBaseQuery()
    {
        return parent::getBaseQuery()
                ->select([
                    'ref_lecturers.name AS nama_pembimbing',
                    'ref_lecturers.no_lecturer',
                    'ref_students.name AS nama_mahasiswa',
                    'ref_students.no_student',
                    'COUNT(DISTINCT logbooks.id) AS total_logbook',
                    ])
                ->join('ref_lecturers','ref_lecturers.id = skripsis.id_lecturer','left')
                ->join('ref_students','ref_students.id = skripsis.id_student','left')
                ->join('logbooks','logbooks.id_skripsi = skripsis.id','left')
                ->group_by('skripsis.id');
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

        if (!empty($filters)) {
            if (key_exists('query', $filters) && $filters['query']) {
                return $baseQuery;
            }

            if (key_exists('search', $filters) && !empty($filters['search'])) {
                foreach ($this->filteredFields as $filteredField) {
                    if ($filteredField == '*') {
                        $fields = $this->db->list_fields($this->table);
                        foreach ($fields as $field) {
                            $baseQuery->or_having($this->table . '.' . $field . ' LIKE', '%' . trim($filters['search']) . '%');
                        }
                    } else {
                        $baseQuery->or_having($filteredField . ' LIKE', '%' . trim($filters['search']) . '%');
                    }
                }
            }

            if (key_exists('status', $filters) && !empty($filters['status'])) {
                if ($this->db->field_exists('status', $this->table)) {
                    $baseQuery->where_in($this->table . '.status', explode(',', $filters['status']));
                }
            }

            if (key_exists('users', $filters) && !empty($filters['users'])) {
                if ($this->db->field_exists('id_user', $this->table)) {
                    $baseQuery->where_in($this->table . '.id_user', $filters['users']);
                }
            }

            if (key_exists('employees', $filters) && !empty($filters['employees'])) {
                if ($this->db->field_exists('id_employee', $this->table)) {
                    $baseQuery->where_in($this->table . '.id_employee', $filters['employees']);
                }
            }

            if (key_exists('date_from', $filters) && !empty($filters['date_from'])) {
                if ($this->db->field_exists('created_at', $this->table)) {
                    $baseQuery->where($this->table . '.created_at>=', format_date($filters['date_from']));
                }
            }

            if (key_exists('date_to', $filters) && !empty($filters['date_to'])) {
                if ($this->db->field_exists('created_at', $this->table)) {
                    $baseQuery->where($this->table . '.created_at<=', format_date($filters['date_to']));
                }
            }

            if (key_exists('dosen', $filters) && !empty($filters['dosen'])) {
                if ($this->db->field_exists('id_lecturer', $this->table)) {
                    $baseQuery->where_in($this->table . '.id_lecturer', $filters['dosen']);
                }
            }
            if (key_exists('mahasiswa', $filters) && !empty($filters['mahasiswa'])) {
                if ($this->db->field_exists('id_student', $this->table)) {
                    $baseQuery->where_in($this->table . '.id_student', $filters['mahasiswa']);
                }
            }

			if (!empty($this->filteredMaps)) {
				foreach ($this->filteredMaps as $filterKey => $filterField) {
					if (is_callable($filterField)) {
						$filterField($baseQuery, $filters);
					} elseif (key_exists($filterKey, $filters) && !empty($filters[$filterKey])) {
						if (is_array($filters[$filterKey])) {
							$baseQuery->where_in($filterField, $filters[$filterKey]);
						} else {
							$baseQuery->where($filterField, $filters[$filterKey]);
						}
					}
				}
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

            //$totalData = $this->db->count_all_results();

            $queryTax = $this->db->get_compiled_select();
            $totalQuery = $this->db->query("SELECT COUNT(*) AS total_record FROM ({$queryTax}) AS report");
            $totalRows = $totalQuery->row_array();
            if (!empty($totalRows)) {
                $totalData = $totalRows['total_record'];
            } else {
                $totalData = 0;
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
