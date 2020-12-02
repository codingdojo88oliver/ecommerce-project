<!DOCTYPE html>
<html>
<head>
	<title>eCommerce Capstone Project</title>
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

	<!-- Google Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">

	<!-- CSS Reset -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">

	<!-- Milligram CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

	<link rel="stylesheet" type="text/css" href="/../assets/css/style.css">

	<!-- You should properly set the path from the main file. -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

	<!-- Font Awesome -->
	<script src="https://use.fontawesome.com/323a4b1822.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.delete-item').on('click', function(){
			    if (confirm('Are you sure?')) {
			    	$(this).parent().parent().remove()
			    }				
			})
		});
	</script>

</head>
<body>
	<header>
		<section class="container">
			<div class="row">
				<div class="column"><h4>eCommerce Dojo</h4></div>
			</div>
		</section>
	</header>
	<div class="container">
		<div class="row">
			<div class="column">
				<table>
					<thead>
						<tr>
							<th>Item</th>
							<th>Price</th>
							<th>Quantity</th>
							<th>Total</th>
							<th colspan="2">Action</th>
						</tr>
					</thead>
					<tbody>
<?php 				foreach($products as $product) { ?>
						<tr>
							<td class="edit-name"><?= $product['name'] ?></td>
							<td class="edit-price">$<?= $product['price'] ?></td>
							<td>
								<input type="number" name="" value="<?= $cart[intval($product['id'])] ?>" class="quantity">
							</td>
							<td>$<?= $product['price'] * $cart[intval($product['id'])] ?></td>
							<td>
								<i class="delete-item fa fa-trash-o" aria-hidden="true"></i>
							</td>
						</tr>
<?php 				} ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="column" id="summary">
				<p><strong>Total: $<?= $total ?></strong></p>
				<form>
					<fieldset>
						<a class="button button-clear button-large float-right" href="/categories">continue shopping</a>
					</fieldset>
				</form>						
			</div>					
		</div>

		<div class="row">
			<div class="column column-25">
				<form action="/carts/checkout" method="POST" id="payment-form">
					<fieldset>
						<h4>Shipping Information</h4>
						<input type="text" placeholder="First Name" id="shipping-first-name">
						<input type="text" placeholder="Last Name" id="shipping-last-name">
						<input type="text" placeholder="Address" id="shipping-address">
						<input type="text" placeholder="Address 2" id="shipping-address2">
						<input type="text" placeholder="City" id="shipping-city">
						<input type="text" placeholder="State" id="shipping-state">
						<input type="text" placeholder="Zip Code" id="shipping-zip">
						<h4>Billing Information</h4>
						<div>
							<input type="checkbox" id="confirmField">
							<label class="label-inline" for="confirmField">Same as Shipping</label>
						</div>
						<input type="text" placeholder="First Name" id="billing-first-name">
						<input type="text" placeholder="Last Name" id="billing-last-name">
						<input type="text" placeholder="Address" id="billing-address">
						<input type="text" placeholder="Address 2" id="billing-address2">
						<input type="text" placeholder="City" id="billing-city">
						<input type="text" placeholder="State" id="billing-state">
						<input type="text" placeholder="Zip Code" id="billing-zip">
						<button type="submit" >Checkout</button>
					</fieldset>
				</form>				
			</div>
		</div>
	</div>
</body>
     
<script src="https://checkout.stripe.com/checkout.js"></script>
  
<script type="text/javascript">


	$('#payment-form').on('submit', function(){
		pay(<?= $total ?>);

		return false;
	});

	function pay(amount) {
		var handler = StripeCheckout.configure({
			key: '<?= $this->config->item('stripe_publishable_key') ?>',
			locale: 'auto',
			token: function (token) {
				// You can access the token ID with `token.id`.
				// Get the token ID to your server-side code for use.
				console.log('Token Created!!');
				console.log(token)

				$.post('<?= base_url('carts/checkout'); ?>', { stripe_token_id: token.id, amount: amount }, function(response){
					//redirect
				}, "json"); 
			}
		});

		handler.open({
			name: 'Demo Site',
			description: '2 widgets',
			amount: <?= $total ?> * 100
		});
	}
</script>
</html>