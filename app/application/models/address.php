<?php

class Address extends CI_Model
{
	public function create_shipping_address($user_id, $user_info)
	{
		$create_shipping_address_query  = 'INSERT INTO addresses (user_id, type, first_name, last_name,
										   address_1, address_2, city, state, zip, created_at, updated_at)
										   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';

		$create_shipping_address_values = (array(
			$user_id,
			SHIPPING_ADDRESS_TYPE,
			$user_info['shipping_first_name'],
			$user_info['shipping_last_name'],
			$user_info['shipping_address'],
			$user_info['shipping_address2'],
			$user_info['shipping_city'],
			$user_info['shipping_state'],
			$user_info['shipping_zip']
		));

		$this->db->query($create_shipping_address_query, $create_shipping_address_values);

		return $this->db->insert_id(); 		
	}

	public function create_billing_address($user_id, $user_info)
	{
		$create_billing_address_query  = 'INSERT INTO addresses (user_id, type, first_name, last_name,
										   address_1, address_2, city, state, zip, created_at, updated_at)
										   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())';

		$create_billing_address_values = (array(
			$user_id,
			BILLING_ADDRESS_TYPE,
			$user_info['billing_first_name'],
			$user_info['billing_last_name'],
			$user_info['billing_address'],
			$user_info['billing_address2'],
			$user_info['billing_city'],
			$user_info['billing_state'],
			$user_info['billing_zip']
		));

		$this->db->query($create_billing_address_query, $create_billing_address_values);

		return $this->db->insert_id(); 		
	}
}
