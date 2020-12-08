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


	/* 
	    DOCU: This function will simply load the cart for a user.
	    The cart is session based but it needs to query the 'products' table to get the latest product information.
	    This page will still be viewable even if the cart is empty. Only that the product list will be empty,
	    and the total will be 0.
	    Owner: Oliver
	*/	
	public function index()
	{
		$this->load->model('Item');
		$this->view_data['products'] = array();
		$product_ids = array();
		$total = 0;
		$this->view_data['cart'] = $cart = $this->session->userdata('cart');

		if($cart) {
			foreach($cart as $product_id => $quantity) {
				$product_ids[] = $product_id;
			}

			$this->view_data['products'] = $this->Item->get_cart_items($product_ids);	

			foreach($this->view_data['products'] as $product) {
				$total += $product['price'] * $cart[intval($product['id'])];
			}	
		}
		
		$this->view_data['total'] = $total;
		$this->load->view('cart', $this->view_data);
	}


	/* 
	    DOCU: This function will update the cart's product quantity.
	    If you increase/decrease quantity for a particular order, the session 'cart' will be updated.
	    This function is triggered via a $.post request and will respond with a JSON object.
	    Owner: Oliver
	*/	
	public function update()
	{
		$cart = $this->session->userdata('cart');

		$cart[$this->input->post('product_id')] = $this->input->post('quantity');

		$this->session->set_userdata('cart', $cart);

		$data = array("success" => true, "total" => 1);

		echo json_encode($data);		
	}


	/* 
	    DOCU: This function will remove an item from a cart.
	    Since our cart is just session based, all we have to do is to update the 'cart' session.
	    This function is triggered via a $.post request and will respond with a JSON object.
	    Owner: Oliver
	*/
	public function remove()
	{
		$cart = $this->session->userdata('cart');
		
		unset($cart[$this->input->post('product_id')]);
		
		$this->session->set_userdata('cart', $cart);

		$data = array("success" => true);

		echo json_encode($data);
	}

	/* 
	    DOCU: This function will add an item (along with the quantity) to the cart.
	    When we add an item to the cart, we will store that item (along with the quantity) to the 'cart' session.
	    It has a simple form validation that requires the user to put in a quantity greater than 0.
	    If there's an error or if it was a successful request, the page will just redirect back to 
	    the particular product's page.
	    Owner: Oliver
	*/
	public function add_to_cart()
	{
		$id = $this->input->post('id');
		$this->form_validation->set_rules("quantity", "Quantity", "greater_than[0]|required");

		/* 
			DOCU: If the user puts in 0 or values lesser than 0, we show an error. 
			Else, we check if the product exists in the database   
		*/
		if($this->form_validation->run() === FALSE)
		{
			$this->session->set_flashdata("errors", validation_errors());
		}
		else
		{
			$this->load->model('Item'); 
			
			$product = $this->Item->add_to_cart($this->input->post());

			/* 
				DOCU: If the product exists, then we set a session called 'cart' and store 
				in the product_id and the quantity the user wants to buy for that product. 
				Example cart format for a user who added id = 1 for 10 pieces:
					['1' => '10']
				Else, we show an error
			*/
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

	/* 
	    DOCU: This function will process the checkout. It will refer to the 'cart' session and will do some
	    validations to confirm if the cart is valid for checkout. We will also require the user to put in 
	    his/her billing and shipping information before we can process the checkout. We then ask for CC information.
	    Once we click Pay, it will check if all the required inputs are set. If it finds an error, it will redirect back to checkout page and will show an error.
	    Owner: Oliver
	*/
	public function checkout()
	{
		$this->form_validation->set_rules("cart", "Cart", "required");
		$this->form_validation->set_rules("amount", "Amount", "greater_than[0]|required");
		$this->form_validation->set_rules("shipping_first_name", "Shipping First Name", "required");
		$this->form_validation->set_rules("shipping_last_name", "Shipping Last Name", "required");
		$this->form_validation->set_rules("shipping_address", "Shipping Address", "required");
		$this->form_validation->set_rules("shipping_address2", "Shipping Address 2", "required");
		$this->form_validation->set_rules("shipping_city", "Shipping City", "required");
		$this->form_validation->set_rules("shipping_state", "Shipping State", "required");
		$this->form_validation->set_rules("shipping_zip", "Shipping Zip", "required");

		$this->form_validation->set_rules("billing_first_name", "Billing First Name", "required");
		$this->form_validation->set_rules("billing_last_name", "Billing Last Name", "required");
		$this->form_validation->set_rules("billing_address", "Billing Address", "required");
		$this->form_validation->set_rules("billing_address2", "Billing Address 2", "required");
		$this->form_validation->set_rules("billing_city", "Billing City", "required");
		$this->form_validation->set_rules("billing_state", "Billing State", "required");
		$this->form_validation->set_rules("billing_zip", "Billing Zip", "required");

		/* 
			DOCU: If there's an error with the billing and shipping information, we show an error. 
			Else, we check if the products still exist in the database 
		*/
		if($this->form_validation->run() === FALSE)
		{
			$data =  array('success' => false, 'message' => validation_errors());
		}
		else
		{
			$product_ids = array();
			$total = 0;
			$cart = json_decode($this->input->post('cart'), true);

			foreach($cart as $product_id => $quantity) {
				$product_ids[] = $product_id;
			}
			$this->load->model('Item');
			$products = $this->Item->get_cart_items($product_ids);	


			/* If the products in the cart are no longer available from the products in our database, we show an error. */
			if(empty($products)) {
				$data =  array('success' => false, 'message' => "Products in your cart are no longer available.");
			} 
			else {

				$for_checkout_ids 		= array();
				$not_available_items 	= "";

				foreach($products as $product) {
					if(isset($cart[$product['id']]) && $product['inventory_count'] >= $cart[$product['id']]) {
						$for_checkout_ids[$product['id']] = $product['inventory_count'];
					} else {
						$not_available_items .= $product['name'] . " ";
					}
				}

				/* 
					DOCU: If we have enough stocks for the product, we proceed with the checkout. If there are items that
					are out of stock or just doesn't have the quantity needed, then we take note of those products.
				*/

				if($not_available_items == "") {

					try {

						$this->load->model('User');
						$user = $this->User->get_customer($this->input->post('email'));

						/* If this is a first time purchase, we create a new customer record and grab that new customer's id. */
						if( ! $user) {
							$user_id = $this->User->add_customer($this->input->post());
						} 
						else {
							$user_id = $user['id'];
						}

						/* We format the cart in a particular way and insert it as JSON to our orders table. */
						$cart_formatted = $this->format_cart($cart, $products);

						$this->load->model('Order');

						$order_result = $this->Order->create_order($user_id, $this->input->post(), $cart_formatted);

						/*
							DOCU: There's a possibility that a user will try to manipulate the total value in the views page.
							In order to make sure we have accurate total value, we will compare the total in the view, with the
							ACTUAL total of an item (quantity * price). If they don't match, we show an error.
						*/
						if($order_result === false) {
							$data = array('success' => false, 'message' => "<p>Please double check these items: <strong>" . $not_available_items . "</strong>. Stock may no longer be available or your specified quantity may be greater than the stocks available..</p>");
						} 
						/*
							DOCU: We proceed with the payment process by triggering a Stripe payment. We also query our products
							table again to decrease our inventory count for each product based on the number of items purchased.
						*/
						else {
							$this->Item->decrease_item_inventory_count($cart, $for_checkout_ids);

					        require_once('application/libraries/stripe-php/init.php');

					        \Stripe\Stripe::setApiKey($this->config->item('stripe_api_key'));
					     
					        \Stripe\Charge::create ([
					                "amount" => $this->input->post('amount') * 100,
					                "currency" => $this->config->item('stripe_currency'),
					                "source" => $this->input->post('stripe_token_id'),
					                "description" => "Test puchase successful!" 
					        ]);

					        /* We then clear the user's cart session */
					        $this->session->unset_userdata('cart');
					        $this->session->sess_destroy();

					        /* And show them a success message. Then redirect them to a success page. */
					        $data = array('success' => true, 'message'=> "Successfully made an order!", 'redirect_url' => base_url('orders/success'));
						}
					} catch (Exception $e) {
						$data = array('success' => false, 'message' => "<p>". $e.getMessage() ."</p>");						
					}
								            
				} else {				
					$data = array('success' => false, 'message' => "<p>Please double check these items: <strong>" . $not_available_items . "</strong>. Stock may no longer be available or your specified quantity may be greater than the stocks available..</p>");
				}
			}

		}
 
        echo json_encode($data);
	}

	/*
		DOCU: This is a function to format the cart in preparation for it to be inserted as a JSON object in the orders table.
		The cart object will simply contain the product's id, name, price, quantity and total amount.
		Owner: Oliver
	*/
	protected function format_cart($cart, $products)
	{
		$cart_formatted = [];
		foreach($products as $product) {
			if(isset($cart[$product['id']])) {
				$cart_formatted["products"][] = [
					"id" 		=> $product['id'],
					"name" 		=> $product['name'],
					"price" 	=> $product['price'],
					"quantity" 	=> $cart[$product['id']],
					"total" 	=> $product['price'] * $cart[$product['id']]
				];
			}
		}

		return $cart_formatted;
	}
}

//end of main controlle