<?php

class Category extends CI_Model
{
	public function get_categories()
	{
		$query = 'SELECT categories.*, count(products.id) as product_count FROM categories
				  INNER JOIN product_categories
				  ON categories.id = product_categories.category_id
				  INNER JOIN products
				  ON product_categories.product_id = products.id
				  GROUP BY categories.id';

		return $this->db->query($query)->result_array();
	}

	public function get_products($category_id)
	{
		$query = 'SELECT products.* FROM products
				  INNER JOIN product_categories
				  ON products.id = product_categories.product_id
				  WHERE product_categories.category_id = ?';

		$values = array($category_id);

		return $this->db->query($query, $values)->result_array();
	}

	public function get_category_ids($product_id)
	{
		$query = 'SELECT category_id FROM product_categories
				  WHERE product_categories.product_id = ?';

		$values = array($product_id);

		return $this->db->query($query, $values)->result_array();
	}

}
