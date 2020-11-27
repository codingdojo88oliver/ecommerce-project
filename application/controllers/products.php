<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Item');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
	}

	public function create()
	{
		$config['upload_path'] = './assets/images/uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '1280';
		$config['max_height']  = '720';
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('images'))
		{
			$get_data = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('error', $get_data['error']);
		}
		else
		{
			$this->load->library("form_validation");
			$this->form_validation->set_rules("name", "Name", "trim|required");
			$this->form_validation->set_rules("description", "Description", "trim|required");
			$this->form_validation->set_rules("inventory_count", "Quantity", "trim|required");
			$this->form_validation->set_rules("price", "Price", "trim|required");
			$this->form_validation->set_rules("categories", "Categories", "trim|required");

			if($this->form_validation->run() === FALSE)
			{
				$this->session->set_flashdata("error", validation_errors());
			}
			else
			{
				$get_data = array('upload_data' => $this->upload->data());
				$product_info = $this->input->post();
				$product_info['file_name'] = $get_data['upload_data']['file_name'];
				$this->Item->add_product($product_info);
				$this->session->set_flashdata('message', 'Product Successfully Added!');
			}
		}

		redirect(base_url('admin/products'));
	}

	public function remove($id)
	{
		$this->Item->remove_product($id);
		$this->session->set_flashdata('messages', 'Successfully deleted a product!');
		redirect(base_url('admin/products'));
	}

	public function update($id)
	{
		$this->Item->update_product($id, $this->input->post());
		$this->session->set_flashdata('messages', 'Successfully updated a product!');
		redirect(base_url('admin/products'));
	}
}

//end of main controlle