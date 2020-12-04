<?php

class Product_Category extends CI_Model
{
	public function add_product_category($product_id, $category_id)
	{
        $insert_query = "INSERT INTO product_categories (product_id, category_id, created_at, updated_at)
                         VALUES (?, ?,  NOW(), NOW())";
                         
        $values = (array($product_id, $category_id));
        
        $this->db->query($insert_query, $values);

        return $this->db->insert_id();		
	}

}
