<?php

class Product_Category extends CI_Model
{
	public function add_product_category($product_id, $category_id)
	{
        $add_product_category_query = "INSERT INTO product_categories (product_id, category_id, created_at, updated_at)
                         			   VALUES (?, ?,  NOW(), NOW())";
                         
        $add_product_category_values = (array($product_id, $category_id));
        
        $this->db->query($add_product_category_query, $add_product_category_values);

        return $this->db->insert_id();		
	}

}
