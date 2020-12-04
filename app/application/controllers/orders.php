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

	public function show($id)
	{
		$this->view_data['order'] = $this->Order->get_order($id);

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
	}

	public function success()
	{
		$this->load->view('success');
	}

	public function update()
	{
		if($this->Order->update_status($this->input->post())) {
			$data = array("success" => true, "message" => "Saved!");
		} else {
			$data = array("success" => false, "message" => "Oops! Something went wrong!");
		}

		echo json_encode($data);
	}
}

//end of order controller