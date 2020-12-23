<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items_Library {

	public function __construct()
	{
	    $this->CI =& get_instance();
	}

	/*

		DOCU: A method to count products based on $data. Where data can be the pagination offset and limit values.
		Owner: Oliver
	*/
	public function get_items_count($data = NULL)
	{
		$this->CI->load->model('Item');

		if($data == NULL) {
			$products = $this->CI->Item->items_count();
		}
		else if(isset($data['name'])) {
			$products = $this->CI->Item->items_count($data);
		}

		return $products['count'];
	}
}