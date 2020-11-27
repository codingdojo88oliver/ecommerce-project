<?php

class Category extends CI_Model
{
	public function get_categories()
	{
		$query = 'SELECT * FROM categories';
		return $this->db->query($query)->result_array();
	}

}
