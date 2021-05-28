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
                    ])
                ->join('ref_lecturers','ref_lecturers.id = skripsis.id_lecturer','left')
                ->join('ref_students','ref_students.id = skripsis.id_student','left');
    }

}
