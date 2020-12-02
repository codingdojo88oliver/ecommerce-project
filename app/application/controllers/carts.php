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
        require_once('application/libraries/stripe-php/init.php');

        \Stripe\Stripe::setApiKey($this->config->item('stripe_api_key'));
     
        \Stripe\Charge::create ([
                "amount" => 100 * 100,
                "currency" => $this->config->item('stripe_currency'),
                "source" => $this->input->post('stripe_token_id'),
                "description" => "Test payment from itsolutionstuff.com." 
        ]);
            
        $this->session->set_flashdata('success', 'Payment made successfully.');
             
		$data = array('success' => true, 'data'=> $stripe, 'redirect_url' => base_url('success'));
 
        echo json_encode($data);
	}
}

//end of main controlle