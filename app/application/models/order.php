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

		/* If $amount (taken from the Form) and $total (calculated from the Cart) matched, then proceed */
		if($amount == $total) {

			/*
				TODO: Check first if address already exists in the database. 
				If it does, then don't create a new address record.
			*/

			/* Create a billing and a shipping address for the user */
			$this->load->model('Address');
			$shipping_address_id = $this->Address->create_shipping_address($user_id, $user_info);
			$billing_address_id  = $this->Address->create_billing_address($user_id, $user_info);


			/* Create the order */
			$create_order_query = 'INSERT INTO orders (user_id, billing_address_id, shipping_address_id, 
								   stripe_id, cart, status, subtotal, shipping, total, created_at, updated_at) 
					  			   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';

			$create_order_values = (array(
				$user_id,
				$billing_address_id,
				$shipping_address_id,
				$user_info['stripe_token_id'],
				json_encode($cart),
				ORDER_IN_PROGRESS,
	            $total,
	            SHIPPING_FEE,
	            $total
			));
			
			return $this->db->query($create_order_query, $create_order_values);			
		} 
		else {
			return false;
		}
	}

	public function get_orders()
	{
		$get_orders_query = 'SELECT CONCAT(users.first_name, " ", users.last_name) as name, 
				  			 orders.id, orders.user_id, orders.status,
				  			 CONCAT (
				  				addresses.address_1, " ", addresses.address_2, 
				  				" ", addresses.city, " ", addresses.zip
				  			 ) AS billing_address, orders.created_at as order_date
				  			 FROM orders
				  			 INNER JOIN users
				  			 ON users.id = orders.user_id
				  			 INNER JOIN addresses 
				  			 ON orders.billing_address_id = addresses.id
				  			 ORDER BY orders.id DESC';

		return $this->db->query($get_orders_query)->result_array();
	}

	public function get_order($id)
	{
		$get_order_query  = "SELECT orders.*, CONCAT(shipping.first_name, ' ', shipping.last_name) AS shipping_full_name,
							 shipping.address_1 AS shipping_address_1, shipping.address_2 AS shipping_address_2,
							 shipping.city AS shipping_city, shipping.state AS shipping_state, 
							 shipping.zip AS shipping_zip,
							 CONCAT(billing.first_name, ' ', billing.last_name) AS billing_full_name,
							 billing.address_1 AS billing_address_1, billing.address_2 AS billing_address_2,
							 billing.city AS billing_city, billing.state AS billing_state, 
							 billing.zip AS billing_zip
						  	 FROM orders
						  	 JOIN addresses AS shipping
						  	 ON shipping.id = orders.shipping_address_id
						  	 JOIN addresses AS billing
						  	 ON billing.id = orders.billing_address_id
						     WHERE orders.id = ?";
		$get_order_values = array($id);

		return $this->db->query($get_order_query, $get_order_values)->row_array();
	}

	public function update_status($data)
	{
		$update_status_query  = "UPDATE orders SET status = ?
				  			  	WHERE id = ?
				  				AND user_id = ?";

		$update_status_values = (array($data['status'], $data['order_id'], $data['user_id']));
		return $this->db->query($update_status_query, $update_status_values);
	}

}
