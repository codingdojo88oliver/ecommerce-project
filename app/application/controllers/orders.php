<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {

	protected $view_data = array();
	protected $user_session = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Order');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->view_data['user_session'] = $this->user_session = $this->session->userdata("user_session");
	}

	/*
		DOCU: A method that will show an order's detail. Only ADMIN's can view this page.
		It will show an "unauthorized access" error when a customer tries to access this page
		Owner: Oliver
	*/
	public function show($id)
	{
		if($this->user_session['role'] == ADMIN) {
			$this->view_data['order'] = $this->Order->get_order($id);

			// shorten this.
			switch ($this->view_data['order']['status']) {
				case ORDER_IN_PROGRESS:
					$this->view_data['order']['status'] = 'Order in Progress';
					break;
				case ORDER_SHIPPED:
					$this->view_data['order']['status'] = 'Order Shipped';
					break;
				case ORDER_RECEIVED:
					$this->view_data['order']['status'] = 'Order Received';
					break;
				case ORDER_CANCELLED:
					$this->view_data['order']['status'] = 'Order Cancelled';
					break;
				
				default:
					# code...
					break;
			}

			$cart = json_decode($this->view_data['order']['cart']);
			
			$this->view_data['products'] = $cart->products;

			$this->load->view('admin/order_show', $this->view_data);
		} else {
			$this->session->set_flashdata("error", "Unauthorized access!");
			redirect(base_url('admin'));
		}
	}

	public function success()
	{
		$this->load->view('success');
	}

	/*
		DOCU: A function that is accessed via a $.post request. It will update the status of an order from Order in progress,
		to order shipped, etc. This is only accessible to ADMINS. Other user's will be shown an "unauthorized access"
		error when they attempt to visit this page.
		Owner: Oliver

	*/
	public function update()
	{
		if($this->user_session['role'] == ADMIN) {
			if($this->Order->update_status($this->input->post())) {
				$data = array("success" => true, "message" => "Saved!");
			} else {
				$data = array("success" => false, "message" => "Oops! Something went wrong!");
			}

			echo json_encode($data);

		} else {
			$this->session->set_flashdata("error", "Unauthorized access!");
			redirect(base_url('admin'));
		}
	}
}

//end of order controller