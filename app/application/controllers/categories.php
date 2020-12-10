<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller {

	protected $view_data = array();
	protected $user_session = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Category');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->view_data['user_session'] = $this->user_session = $this->session->userdata("user_session");

		/* 
			DOCU: Since both index and show methods display the Cart count at the top of each pages, 
			we call this simple method to count the total number of items in our cart.
		*/
		$this->view_data['cart_count'] = $this->count_cart_items();

	}

	/*
		DOCU: This is the default page the customers should see when they visit the app. It will list all the products, and by default, we show all the products under all categories.
		Owner: Oliver
	*/
	public function index()
	{
		$this->load->model('Item');

		$this->view_data['categories'] 	= $this->Category->get_categories();
		$this->view_data['products'] 	= $this->Item->get_items();
		$products_count 				= count($this->view_data['products']);
		$this->view_data['pages'] 		= ceil($products_count / PRODUCTS_LIMIT);

		$this->load->view('categories', $this->view_data);
	}

	/* 
		DOCU: A method to count items in the cart. It grabs the cart session. 
		If there's no cart session, we set the cart count to zero.
		Owner: Oliver
	*/
	protected function count_cart_items()
	{
		$cart_count = 0;

		if($this->session->userdata('cart')) {
			foreach($this->session->userdata('cart') as $quantity) {
				$cart_count += $quantity;
			}
		}

		return $cart_count;
	}

	/*
		DOCU: This is the function that will show all the products under a particular category.
		Owner: Oliver
	*/
	public function show($id)
	{
		$this->view_data['categories'] = $this->Category->get_categories();
		$this->view_data['products'] = $this->Category->get_products($id);
		$this->load->view('categories',  $this->view_data);

	}
}

//end of main controlle