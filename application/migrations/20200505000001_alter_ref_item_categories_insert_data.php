<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Alter_ref_item_categories_insert_data
 * @property CI_DB_query_builder $db
 */
class Migration_Alter_ref_item_categories_insert_data extends CI_Migration
{
    public function up()
    {   

        $this->load->model('CategoryModel', 'category');
        $this->load->model('ItemCategoryModel', 'itemCategory');
        $getCategory = $this->category->getBy(['category' => "OPS RENT, HEAVY EQUIPMENT"], true);
        if(!empty($getCategory)){
            $getCrane = $this->itemCategory->getBy(['item_name' => "CRANE"], true);
            $getForklift = $this->itemCategory->getBy(['item_name' => "FORKLIFT"], true);

            if(empty($getCrane) || empty($getForklift)){
                for($i = 1; $i <= 2; $i++){
                    $code = $this->itemCategory->getItemCode();

                    $tipe = $i == 1 ? 'CRANE' : 'FORKLIFT';
                    $this->db->query("
                        INSERT INTO ref_item_categories (id_category, item_code, item_name, description, is_reserved)
                        VALUES (".$getCategory['id'].",'".$code."', '".$tipe."', '-', 1)
                    ");
                }
            }
        }else{
            $this->db->trans_start();

            $this->category->create([
                'category' => "OPS RENT, HEAVY EQUIPMENT",
                'description' => "-"
            ]);

            $categoryId = $this->db->insert_id();
            $getCrane = $this->itemCategory->getBy(['item_name' => "CRANE"], true);
            $getForklift = $this->itemCategory->getBy(['item_name' => "FORKLIFT"], true);

            if(empty($getCrane) || empty($getForklift)){
                for($i = 1; $i <= 2; $i++){
                    $code = $this->itemCategory->getItemCode();

                    $tipe = $i == 1 ? 'CRANE' : 'FORKLIFT';
                    $this->db->query("
                        INSERT INTO ref_item_categories (id_category, item_code, item_name, description, is_reserved)
                        VALUES (".$categoryId.",'".$code."', '".$tipe."', '-', 1)
                    ");
                }
            }

            $this->db->trans_complete();
        }
      

        echo 'Migrate Migration_Alter_ref_item_categories_insert_data<br>';
    }

    public function down()
    {
        $this->db->query("
            DELETE FROM ref_item_categories
        ");

        echo 'Rollback Migration_Alter_ref_item_categories_insert_data<br>';
    }
}