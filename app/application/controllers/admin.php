<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	protected $view_data = array();
	protected $user_session = NULL;
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->view_data['user_session'] = $this->user_session = $this->session->userdata("user_session");
	}
	
	public function index()
	{
		if($this->user_session['role'] == 0) {		
			$this->load->view("login");
		} else {
			redirect(base_url('admin/orders'));
		}
	}
	
	public function login()
	{		
		$this->load->library("form_validation");
		$this->form_validation->set_rules("email", "Email", "trim|valid_email|required");
		$this->form_validation->set_rules("password", "Password", "trim|min_length[5]|required");
		
		if($this->form_validation->run() === FALSE)
		{
			$this->session->set_flashdata("errors", validation_errors());
			redirect(base_url('admin'));
		}
		else
		{
			$this->load->model("User");							   
			$get_user = $this->User->get_admin($this->input->post());

			if (! empty($get_user))
			{
				$this->session->set_userdata("user_session", $get_user);
				redirect(base_url("admin/orders"));
			}
			else
			{
				$this->session->set_flashdata("errors", "Invalid email and/or password");
				redirect(base_url('admin'));
			}
		}
	}
	
	public function orders()
	{

		if($this->user_session['role'] == 1) {
			$this->load->model('Order');
			$orders = $this->Order->get_orders();
			foreach ($orders as $key => $order) {
				$this->view_data['orders'][] = [
					'id' 				=> $order['id'],
					'user_id' 			=> $order['user_id'],
					'name' 				=> $order['name'],
					'date' 				=> $order['order_date'],
					'billing_address'   => $order['billing_address'],
					'status' 			=> $order['status'],
				];
			}

			$this->load->view('admin/orders', $this->view_data);
		} else {
			redirect(base_url('admin'));
		}
	}
	
	public function products()
	{

		if($this->user_session['role'] == 1) {
			$this->load->model('Item');
			$this->load->model('Category');

			$this->view_data['products'] 	= $this->Item->get_items();
			$this->view_data['categories'] 	= $this->Category->get_categories();
			
			$this->load->view('admin/products', $this->view_data);
		} else {
			redirect(base_url('admin'));
		}
	}

	public function logout()
	{
		$user_session_data = $this->session->all_userdata();
		
		foreach($user_session_data as $key)
		{
			$this->session->unset_userdata($key);
		}
		
		$this->session->sess_destroy();
		redirect(base_url('admin/login'));
	}
	
}

//* End of file