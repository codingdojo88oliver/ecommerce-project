<?php

class Order extends CI_Model
{
	public function get_orders()
	{
		$query = 'SELECT CONCAT(users.first_name, " ", users.last_name) as name, orders.id, 
				  CONCAT (
				  	orders.billing_address_1, " ", orders.billing_address_2, 
				  	" ", orders.billing_city, " ", orders.billing_zip
				  ) AS billing_address, orders.created_at as order_date
				  FROM orders
				  INNER JOIN users
				  ON users.id = orders.user_id 
				  ORDER BY orders.id DESC';

		return $this->db->query($query)->result_array();
	}

	public function get_order($id)
	{
		$query = "SELECT * FROM orders WHERE orders.id = ?";
		$values = array($id);
		return $this->db->query($query, $values)->row_array();
	}

}
