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
	}

	public function index()
	{
		$this->view_data['cart_count'] = 0;
		if($this->session->userdata('cart')) {
			$this->view_data['cart_count'] = 0;
			foreach($this->session->userdata('cart') as $quantity) {
				$this->view_data['cart_count'] += $quantity;
			}
		}

		$this->load->model('Item');
		$this->view_data['categories'] = $this->Category->get_categories();
		$this->view_data['products'] = $this->Item->get_items();

		$this->load->view('categories', $this->view_data);
	}

	public function show($id)
	{
		$this->view_data['categories'] = $this->Category->get_categories();
		$this->view_data['products'] = $this->Category->get_products($id);
		// var_dump($this->Category->get_products($id)); die();
		$this->load->view('categories',  $this->view_data);

	}
}

//end of main controlle