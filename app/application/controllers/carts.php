<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carts extends CI_Controller {

	protected $view_data = array();
	protected $user_session = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation', 'user_agent');
		$this->load->helper(array('form', 'url'));
		$this->view_data['user_session'] = $this->user_session = $this->session->userdata("user_session");
	}

	public function index()
	{
		$this->load->model('Item');
		$product_ids = array();
		$total = 0;
		$this->view_data['cart'] = $cart = $this->session->userdata('cart');

		foreach($cart as $product_id => $quantity) {
			$product_ids[] = $product_id;
		}

		$this->view_data['products'] = $this->Item->get_cart_items($product_ids);	

		foreach($this->view_data['products'] as $product) {
			$total += $product['price'] * $cart[intval($product['id'])];
		}

		$this->view_data['total'] = $total;

		$this->load->view('cart', $this->view_data);
	}

	public function add_to_cart()
	{
		$id = $this->input->post('id');
		$this->form_validation->set_rules("quantity", "Quantity", "greater_than[0]|required");

		if($this->form_validation->run() === FALSE)
		{
			$this->session->set_flashdata("errors", validation_errors());
		}
		else
		{
			$this->load->model('Item'); 
			
			$product = $this->Item->add_to_cart($this->input->post());

			if($product) {

				$cart = $this->session->userdata('cart');
				$quantity = $this->session->userdata('cart')[$id];
				$quantity += $this->input->post('quantity');
				$cart[$id] = $quantity;
				$this->session->set_userdata('cart', $cart);

				$this->session->set_flashdata('message', $product['name'] . ' successfully added to cart!');
			} else {
				$this->session->set_flashdata('message', $product['name'] . ' not available');
			}
		}

		redirect(base_url('products/show/' . $id));

	}

	public function checkout()
	{
		$this->form_validation->set_rules("cart", "Cart", "required");
		$this->form_validation->set_rules("amount", "Amount", "greater_than[0]|required");
		$this->form_validation->set_rules("shipping_first_name", "Shipping First Name", "required");
		$this->form_validation->set_rules("shipping_last_name", "Shipping Last Name", "required");
		$this->form_validation->set_rules("shipping_address", "Shipping Address", "required");
		$this->form_validation->set_rules("shipping_address2", "Shipping Address 2", "required");
		$this->form_validation->set_rules("shipping_city", "Shipping City", "required");
		$this->form_validation->set_rules("shipping_zip", "Shipping Zip", "required");

		$this->form_validation->set_rules("billing_first_name", "Billing First Name", "required");
		$this->form_validation->set_rules("billing_last_name", "Billing Last Name", "required");
		$this->form_validation->set_rules("billing_address", "Billing Address", "required");
		$this->form_validation->set_rules("billing_address2", "Billing Address 2", "required");
		$this->form_validation->set_rules("billing_city", "Billing City", "required");
		$this->form_validation->set_rules("billing_zip", "Billing Zip", "required");

		if($this->form_validation->run() === FALSE)
		{
			$data =  array('success' => false, 'message' => validation_errors());
		}
		else
		{

			// check if products still exist in database and if quantity is still greater than the customer's quantity.

			$product_ids = array();
			$total = 0;
			$cart = $this->input->post('cart');

			foreach($cart as $product_id => $quantity) {
				$product_ids[] = $product_id;
			}

			$this->view_data['products'] = $this->Item->get_cart_items($product_ids);	

	        require_once('application/libraries/stripe-php/init.php');

	        \Stripe\Stripe::setApiKey($this->config->item('stripe_api_key'));
	     
	        \Stripe\Charge::create ([
	                "amount" => $this->input->post('amount') * 100,
	                "currency" => $this->config->item('stripe_currency'),
	                "source" => $this->input->post('stripe_token_id'),
	                "description" => "Test payment from itsolutionstuff.com." 
	        ]);
	            
	        $this->session->set_flashdata('success', 'Payment made successfully.');
	             
			$data = array('success' => true, 'data'=> $stripe, 'redirect_url' => base_url('success'));

		}
 
        echo json_encode($data);
	}
}

//end of main controlle