<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {

	protected $view_data = array();
	protected $user_session = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Item');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->view_data['user_session'] = $this->user_session = $this->session->userdata("user_session");
	}

	public function index()
	{
		$this->view_data['products'] = $this->Item->get_items();
		$this->load->view('products', $this->view_data);
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

	public function show($id)
	{
		$this->view_data['cart_count'] = 0;
		
		if($this->session->userdata('cart')) {
			$this->view_data['cart_count'] = 0;
			foreach($this->session->userdata('cart') as $quantity) {
				$this->view_data['cart_count'] += $quantity;
			}
		}

		$this->view_data['product'] = $this->Item->get_item($id);

		$this->view_data['similar_items'] = $this->Item->get_similar_items($id);

		$this->load->view('product-show', $this->view_data);
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