<?php

class Item extends CI_Model
{
	/*
		DOCU: The function that gets the items from the database. This is implemented with pagination and search in mind.
		This function gets invoked on 3 ways:
			1. When user loads the page
			2. When a pagination number gets clicked
			3. When a search feature is used
		The results will vary depending on which instance is invoked.
		Owner: Oliver
	*/
	public function get_items($item_data = NULL)
	{
		$get_items_query = "SELECT * FROM products LIMIT ? OFFSET ?";

		if($item_data == NULL) {
			$where = array(PRODUCTS_LIMIT, 0);

		}

		else if(isset($item_data['page_number']) && isset($item_data['search']))
		{
			var_dump($item_data); die();
			if($item_data['search'] == "") {
				$where = array(PRODUCTS_LIMIT, $item_data['page_number']);
			}
			else
			{
				if(is_array($item_data['search']))
				{
					$get_items_query = "SELECT * FROM products WHERE name LIKE ? LIMIT ? OFFSET ?";
					$where = array('%'.$item_data['search'].'%', PRODUCTS_LIMIT, $item_data['page_number']);
				}
			}
		}

		else if(isset($item_data['name']))
		{
			$get_items_query = "SELECT * FROM products WHERE name LIKE ? LIMIT ?";
			$where = array('%'.$item_data['name'].'%', PRODUCTS_LIMIT);
		}

		return $this->db->query($get_items_query, $where)->result_array();	
	}

	/*
		DOCU: This is the function that counts the number of items we selected from the database. If function gets called on 3 instances:
			1. When user loads the page
			2. When a pagination number gets clicked
			3. When a search feature is used
		The count result will vary depending on which instance is triggered.
		Owner: Oliver
	*/
	public function items_count($data = NULL)
	{
		if($data == NULL)
		{
			$get_items_count_query = "SELECT count(id) as count FROM products";
			$where_query = "";
		}
		else if(isset($data['name']))
		{
			$get_items_count_query = "SELECT count(id) as count FROM products WHERE name LIKE ?";
			$where_query = '%'.$data['name'].'%';
		}

		return $this->db->query($get_items_count_query, $where_query)->row_array();	
	}

	/*
		DOCU: This is the function that grabs a single product from the database using a product_id
		Owner: Oliver
	*/
	public function get_item($id)
	{
		$get_item_query = 'SELECT * FROM products
				  		   WHERE id = ?
				  		   LIMIT 1';

		$get_item_values = array($id);

		return $this->db->query($get_item_query, $get_item_values)->row_array();
	}

	/*
		DOCU: A function that requires a product_id to get all similar products from the database
		Owner: Oliver
	*/
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

	/*
		DOCU: This function requires product_ids extracted from the session cart to get the products from the database.
		Owner: Oliver
	*/
	public function get_cart_items($product_ids)
	{
		$get_cart_items_values = implode(', ', $product_ids);
		
		$get_cart_items_query  = "SELECT * FROM products
				  				  WHERE id IN ({$get_cart_items_values})";

		return $this->db->query($get_cart_items_query)->result_array();
	}

	/*
		DOCU: This is the function that adds a product to the database that is only accessible by ADMINS
		TODO: Make sure to add a validation to only allow admins
		Owner: Oliver
	*/
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

	/*
		DOCU: This is the function that removes a product from the database that is only accessible by ADMINS
		TODO: Make sure to add a validation to only allow admins
		Owner: Oliver
	*/
	public function remove_product($id)
	{
		$remove_product_query_1 	= "DELETE FROM product_categories WHERE product_id = ?";
		$remove_product_values_1 	= array($id);

		$this->db->query($remove_product_query_1, $remove_product_values_1);

		$remove_product_query_2 	= "DELETE FROM products WHERE id = ?";
		$remove_product_values_2 	= array($id);

		return $this->db->query($remove_product_query_2, $remove_product_values_1);
	}

	/*
		DOCU: This is the function that updates a product's name and description that is only accessible by ADMINS
		TODO: Make sure to add a validation to only allow admins
		Owner: Oliver
	*/
	public function update_product($id, $data)
	{
		$update_product_query 	= "UPDATE products
				  				   SET name = ?, description = ?, updated_at = NOW()
				  				   WHERE id = ?;";

		$update_product_values 	= array($data['name'], "<p>" . $data['description'] . "</p>", $id);

		return $this->db->query($update_product_query, $update_product_values);
	}

	/*
		DOCU: This is the function that checks whether the product that is being added to cart is present in the database
		and if it has inventory count greater than or equal to 1.
		Owner: Oliver
	*/
	public function add_to_cart($data)
	{
		$add_to_cart_query 	= "SELECT * FROM products
				  			   WHERE id = ?
				  			   AND inventory_count >= 1
				  			   LIMIT 1";

		$add_to_cart_values = array($data['id']);

		return $this->db->query($add_to_cart_query, $add_to_cart_values)->row_array();
	}

	/*
		DOCU: This is the function that will UPDATE the products table. This will be called during a customer checkout.
		Once a valid checkout occurs, it will check the quantity for each items bought and deduct that to the 
		product inventory.
		TODO: This method uses ON DUPLICATE KEY UPDATE instead of just directly updating the product, change the query to UPDATE only.
		Owner: Oliver
	*/
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
