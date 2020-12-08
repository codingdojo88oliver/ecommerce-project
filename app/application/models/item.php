<?php

class Item extends CI_Model
{
	public function get_items()
	{
		$get_items_query = 'SELECT * FROM products ORDER BY id DESC';
		return $this->db->query($get_items_query)->result_array();
	}

	public function get_item($id)
	{
		$get_item_query = 'SELECT * FROM products
				  		   WHERE id = ?
				  		   LIMIT 1';

		$get_item_values = array($id);

		return $this->db->query($get_item_query, $get_item_values)->row_array();
	}

	public function get_similar_items($id)
	{
		$get_similar_items_query = 'SELECT products.*
				  					FROM product_categories product_categories_1
				  					JOIN product_categories product_categories_2 
				  					ON product_categories_1.category_id = product_categories_2.category_id
				  					JOIN products
				  					ON product_categories_2.product_id = products.id
				  					WHERE product_categories_1.product_id = ? 
				  					AND product_categories_2.product_id <> product_categories_1.product_id';

		$get_similar_items_values = array($id);

		return $this->db->query($get_similar_items_query, $get_similar_items_values)->result_array();
	}

	public function get_cart_items($product_ids)
	{
		$get_cart_items_values = implode(', ', $product_ids);
		
		$get_cart_items_query  = "SELECT * FROM products
				  				  WHERE id IN ({$get_cart_items_values})";

		return $this->db->query($get_cart_items_query)->result_array();
	}

	public function add_product($data)
	{
		$add_product_query = 'INSERT INTO products (user_id, name, description, images, price, 
							  inventory_count, created_at, updated_at) 
							  VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())';

		$add_product_values = array(
			$this->session->userdata("user_session")['id'], 
			$data['name'], 
			"<p>" .$data['description'] . "</p>", 
			json_encode($data['file_names']), 
			$data['price'], 
			$data['inventory_count']
		);
		
		$this->db->query($add_product_query, $add_product_values);

		return $this->db->insert_id();
	}

	public function remove_product($id)
	{
		$remove_product_query_1 	= "DELETE FROM product_categories WHERE product_id = ?";
		$remove_product_values_1 	= array($id);

		$this->db->query($remove_product_query_1, $remove_product_values_1);

		$remove_product_query_2 	= "DELETE FROM products WHERE id = ?";
		$remove_product_values_2 	= array($id);

		return $this->db->query($remove_product_query_2, $remove_product_values_1);
	}

	public function update_product($id, $data)
	{
		$update_product_query 	= "UPDATE products
				  				   SET name = ?, description = ?, updated_at = NOW()
				  				   WHERE id = ?;";

		$update_product_values 	= array($data['name'], "<p>" . $data['description'] . "</p>", $id);

		return $this->db->query($update_product_query, $update_product_values);
	}

	public function add_to_cart($data)
	{
		$add_to_cart_query 	= "SELECT * FROM products
				  			   WHERE id = ?
				  			   AND inventory_count >= 1
				  			   LIMIT 1";

		$add_to_cart_values = array($data['id']);

		return $this->db->query($add_to_cart_query, $add_to_cart_values)->row_array();
	}

	public function decrease_item_inventory_count($cart, $stock)
	{
		$new_stock_string = "";

		$new_stock = array();

		foreach ($stock as $id => $count) {
			if(isset($cart[$id])) {
				$new_stock[$id] = $count - $cart[$id];
			}
		}

		foreach($new_stock as $key => $value) {
			$new_stock_string .= "(" . $key . ", " . $value . "), ";
		}

		$new_stock_string = rtrim(trim($new_stock_string), ',');

		$decrease_item_inventory_count_query = "INSERT INTO products (id, inventory_count)
				  								VALUES ". $new_stock_string . " 
				  								ON DUPLICATE KEY UPDATE inventory_count = VALUES(inventory_count);";

		return $this->db->query($decrease_item_inventory_count_query);
	}

}
