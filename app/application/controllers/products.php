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

	/*
		DOCU: This is the method that handles product creation. This is only accessible by the ADMINs.
	*/
	public function create()
	{
		if($this->user_session['role'] == ADMIN) {
			$product_info = $this->input->post();

			/* TODO: Put all the process of uploading the product's images in it's own method */
			$config['upload_path'] = './assets/images/uploads/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']	= '1000';
			$config['max_width']  = '2097';
			$config['max_height']  = '1411';
			$this->load->library('upload', $config);

	        $images = array();
	        $files 	= $_FILES['images'];

	        foreach ($_FILES['images']['name'] as $key => $image) {
				$_FILES['images[]']['name']= $files['name'][$key];
	            $_FILES['images[]']['type']= $files['type'][$key];
	            $_FILES['images[]']['tmp_name']= $files['tmp_name'][$key];
	            $_FILES['images[]']['error']= $files['error'][$key];
	            $_FILES['images[]']['size']= $files['size'][$key];
	            
	            $images[] = "/assets/images/uploads/" . $image;

	            $config['file_name'] = $image;

	            $this->upload->initialize($config);

	            if ($this->upload->do_upload('images[]')) {
	            	$this->upload->data();
	            } else {
					$get_data = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('error', $get_data['error']);
	            }
	        }

	        if($images) {
	        	$product_info['file_names'] = $images;
	        }

	        /* After doing all the image upload processes, we validate the form. */
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
				/* 
					DOCU: If "or Add New Category" input field was filled, we create a new category
					TODO: For now, it ignores the categories dropdown if "or Add New Category" is filled.
					Make sure we don't ignore it and still allow multiple categories for a specific product.
				*/
				if($this->input->post('category')) {
					$this->load->model('Category');
					$category_id = $this->Category->add_category($this->input->post('category'));
				} else {
					$category_id = $this->input->post('categories');
				}

				$product_id = $this->Item->add_product($product_info);

				$this->load->model('Product_Category');
				$this->Product_Category->add_product_category($product_id, $category_id);
				$this->session->set_flashdata('message', 'Product Successfully Added!');
			}


			redirect(base_url('admin/products'));
		} else {
			$this->session->set_flashdata("error", "Unauthorized access!");
			redirect(base_url('admin'));
		}
	}

	/* 
		DOCU: A page where we show a product's detail. We also show the similar items in this page based on the product's
		category_id.
		Owner: Oliver
	*/
	public function show($id)
	{
		/*
			TODO: The code block below is a repeating code to count the cart items. We should have a helper class instead of
			having another method in this class. Right now the Categories controller has a count_cart_items() method that does
			the same thing as the code block below
		*/
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

	/* 
		DOCU: A method to remove a product from the database. Only accessible by the ADMINs.
		Owner: Oliver
	*/
	public function remove($id)
	{
		if($this->user_session['role'] == ADMIN) {
			$this->Item->remove_product($id);
			$this->session->set_flashdata('messages', 'Successfully deleted a product!');
			redirect(base_url('admin/products'));
		} else {
			$this->session->set_flashdata("error", "Unauthorized access!");
			redirect(base_url('admin'));
		}
	}

	/* 
		DOCU: A method to update a product in the database. Only accessible by the ADMINs.
		Owner: Oliver
	*/
	public function update($id)
	{
		if($this->user_session['role'] == ADMIN) {
			$this->Item->update_product($id, $this->input->post());
			$this->session->set_flashdata('messages', 'Successfully updated a product!');
			redirect(base_url('admin/products'));
		} else {
			$this->session->set_flashdata("error", "Unauthorized access!");
			redirect(base_url('admin'));
		}
	}
}

//end of main controlle