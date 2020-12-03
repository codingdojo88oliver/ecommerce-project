<?php

class Order extends CI_Model
{

	public function create_order($user_id, $user_info, $cart)
	{
		$amount = $user_info['amount'];
		$total = 0;

		foreach($cart['products'] as $product) {
			$total += $product['quantity'] * $product['price'];
		}

		// if $amount and $total matched, then proceed
		if($amount == $total) {

			$query = 'INSERT INTO orders (user_id, stripe_id, cart, status, billing_full_name, billing_address_1, billing_address_2, 
					  billing_city, billing_state, billing_zip, shipping_full_name, shipping_address_1, shipping_address_2,
					  shipping_city, shipping_state, shipping_zip, subtotal, shipping, total, created_at, updated_at) 
					  VALUES (?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, NOW(), NOW())';

			$values = (array(
				$user_id,
				$user_info['stripe_token_id'],
				json_encode($cart),
				$user_info['billing_first_name'] . " " . $user_info['billing_last_name'],
	            $user_info['billing_address'],
	            $user_info['billing_address2'],
	            $user_info['billing_city'],
	            $user_info['billing_state'],
	            $user_info['billing_zip'],
	            $user_info['shipping_first_name'] . " " . $user_info['shipping_last_name'],
	            $user_info['shipping_address'],
	            $user_info['shipping_address2'],
	            $user_info['shipping_city'],
	            $user_info['shipping_state'],
	            $user_info['shipping_zip'],
	            $total,
	            $total
			));
			
			return $this->db->query($query, $values);			
		} 
		else {
			return false;
		}
	}

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
