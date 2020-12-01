<?php

class Item extends CI_Model
{
	public function get_items()
	{
		$query = 'SELECT * FROM products ORDER BY id DESC';
		return $this->db->query($query)->result_array();
	}

	public function get_item($id)
	{
		$query = 'SELECT * FROM products
				  WHERE id = ?
				  LIMIT 1';

		$values = array($id);

		return $this->db->query($query, $values)->row_array();
	}

	public function get_similar_items($id)
	{
		$query = 'SELECT p.*
				  FROM product_categories p_c1
				  JOIN product_categories p_c2 
				  ON p_c1.category_id = p_c2.category_id
				  JOIN products p 
				  ON p_c2.product_id = p.id
				  WHERE p_c1.product_id = ? 
				  AND p_c2.product_id <> p_c1.product_id';

		$values = array($id);

		return $this->db->query($query, $values)->result_array();
	}

	public function add_product($data)
	{
		$query = 'INSERT INTO products (user_id, name, description, images, price, inventory_count, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())';

		$images = json_encode(["/assets/images/uploads/{$data['file_name']}"]);

		$values = array($this->session->userdata("user_session")['id'], $data['name'], $data['description'], $images, $data['price'], $data['inventory_count']);
		
		return $this->db->query($query, $values);
	}

	public function remove_product($id)
	{
		$query = "DELETE FROM products WHERE id = ?";
		$values = array($id);
		return $this->db->query($query, $values);
	}

	public function update_product($id, $data)
	{
		$query = "UPDATE products
				  SET name = ?, description = ?, updated_at = NOW()
				  WHERE id = ?;";

		$values = array($data['name'], $data['description'], $id);

		return $this->db->query($query, $values);
	}

}
