<?php

class Category extends CI_Model
{
	/*
		DOCU: A function that gets all the categories from the database joined with products
		TODO: Create a get_categories() method that only gets all categories instead of it having been
		joined with products
		Owner: Oliver
	*/	
	public function get_categories()
	{
		$get_categories_query = 'SELECT categories.*, count(products.id) as product_count FROM categories
				  			     INNER JOIN product_categories
				  				 ON categories.id = product_categories.category_id
				  				 INNER JOIN products
				  				 ON product_categories.product_id = products.id
				  				 GROUP BY categories.id';

		return $this->db->query($get_categories_query)->result_array();
	}

	/*
		DOCU: This function gets a category using id
		Owner: Oliver
	*/
	public function get_category($id)
	{
		$get_categories_query  = 'SELECT * FROM categories
								  WHERE id = ? LIMIT 1;';

		$get_categories_values = (array($id));

		return $this->db->query($get_categories_query, $get_categories_values)->row_array();
	}

	/*
		DOCU: This function gets products using the category id.
		TODO: Move this to Product model instead.
		Owner: Oliver
	*/
	public function get_products($category_id)
	{
		$get_products_query = 'SELECT products.* FROM products
				  			   INNER JOIN product_categories
				  			   ON products.id = product_categories.product_id
				  			   WHERE product_categories.category_id = ?';

		$get_products_values = array($category_id);

		return $this->db->query($get_products_query, $get_products_values)->result_array();
	}

	/*
		DOCU: A function that will query the database to get all category ids a certain product belongs to.
		Owner: Oliver
	*/
	public function get_category_ids($product_id)
	{
		$get_category_ids_query = 'SELECT category_id FROM product_categories
				  				   WHERE product_categories.product_id = ?';

		$get_category_ids_values = array($product_id);

		return $this->db->query($get_category_ids_query, $get_category_ids_values)->result_array();
	}

	/*
		DOCU: This is the function that allows ADMINS to create a new category
		TODO: Have a validation to ONLY allow ADMINS for this method
		Owner: Oliver
	*/
	public function add_category($name)
	{
        $add_category_query = "INSERT INTO categories (name, created_at, updated_at)
                         	   VALUES (?, NOW(), NOW())";

        $add_category_values = (array($name));
        
        $this->db->query($insert_query, $values);

        return $this->db->insert_id();		
	}

}
