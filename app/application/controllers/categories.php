<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller {

	protected $view_data = array();
	protected $user_session = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Category');
		$this->load->helper(array('form', 'url'));
		$this->view_data['user_session'] = $this->user_session = $this->session->userdata("user_session");

		/* 
			DOCU: Since both index and show methods display the Cart count at the top of each pages, 
			we call this simple method to count the total number of items in our cart.
		*/
		$this->load->library('items_library');

		$this->view_data['cart_count'] = $this->items_library->count_cart_items();

	}

	/*
		DOCU: This is the default page the customers should see when they visit the app. It will list all the products, and by default, we show all the products under all categories.
		Owner: Oliver
	*/
	public function index()
	{
		$this->load->library('items_library');

		$this->load->model('Item');
		$this->view_data['categories'] 			= $this->Category->get_categories();
		$this->view_data['products'] 			= $this->Item->get_items();
		$products_count 						= $this->items_library->get_items_count();
		$this->view_data['pages'] 				= ceil($products_count / PRODUCTS_LIMIT);
		$this->view_data['selected_category'] 	= 'All';
		$this->load->view('categories', $this->view_data);
	}

	/*
		DOCU: This is the function that will show all the products under a particular category.
		Owner: Oliver
	*/
	public function show($id)
	{
		$this->load->library('items_library');

		$this->view_data['categories'] 			= $this->Category->get_categories();
		$this->view_data['products'] 			= $this->Category->get_products($id);
		$this->view_data['selected_category'] 	= $this->Category->get_category($id)['name'];
		$products_count 						= $this->items->get_items_count();
		$this->view_data['pages'] 				= ceil($products_count / PRODUCTS_LIMIT);
		$this->load->view('categories',  $this->view_data);

	}
}

//end of main controlle