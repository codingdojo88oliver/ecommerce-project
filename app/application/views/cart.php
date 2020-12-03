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
			$("#remove-item").on("submit", function(){
				var form = $(this);

				$.post(form.attr('action'), form.serialize(), function(data){}, "json");

				return false;
			});

			$('.delete-item').on('click', function(e){
				e.preventDefault();
			    if (confirm('Are you sure?')) {
			    	var form = $(this).parent().parent().find("#remove-item");

			    	$.post(form.attr('action'), form.serialize(), function(data){}, "json");

			    	$(this).parent().parent().remove();
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
							<form id="remove-item" method="POST" action="/carts/remove">
								<input type="hidden" name="product_id" value="<?= $product['id'] ?>">
							</form>
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

<?php 	if($total != 0) { ?>
		<div class="row">
			<div class="column column-25">
				<form action="/carts/checkout" method="POST" id="payment-form">
					<fieldset>
						<!-- hidden inputs -->
						<input type="hidden" name="cart" value=<?= json_encode($cart) ?>>
						<input type="hidden" name="amount" value=<?= $total ?>>

						<h4>Shipping Information</h4>
						<input value="Ghost" type="text" name="shipping_first_name" placeholder="First Name" id="shipping-first-name">
						<input value="Saruman" type="text" name="shipping_last_name" placeholder="Last Name" id="shipping-last-name">
						<input value="242 San Vicente Street" type="text" name="shipping_address" placeholder="Address" id="shipping-address">
						<input value="Barangay Pangabugan" type="text" name="shipping_address2" placeholder="Address 2" id="shipping-address2">
						<input value="Butuan City" type="text" name="shipping_city" placeholder="City" id="shipping-city">
						<input value="ADN" type="text" name="shipping_state" placeholder="State" id="shipping-state">
						<input value="8600" type="text" name="shipping_zip" placeholder="Zip Code" id="shipping-zip">
						<h4>Billing Information</h4>
						<div>
							<input type="checkbox" id="confirmField">
							<label class="label-inline" for="confirmField">Same as Shipping</label>
						</div>
						<input value="Ghost" type="text" name="billing_first_name" placeholder="First Name" id="billing-first-name">
						<input value="Saruman" type="text" name="billing_last_name" placeholder="Last Name" id="billing-last-name">
						<input value="Block 1 Lot 2 Phase 1" type="text" name="billing_address" placeholder="Address" id="billing-address">
						<input value="Emenvil Subdivision" type="text" name="billing_address2" placeholder="Address 2" id="billing-address2">
						<input value="Butuan City" type="text" name="billing_city" placeholder="City" id="billing-city">
						<input value="ADN" type="text" name="billing_state" placeholder="State" id="billing-state">
						<input value="8600" type="text" name="billing_zip" placeholder="Zip Code" id="billing-zip">
						<button type="submit">Checkout</button>
					</fieldset>
				</form>				
			</div>
			<div class="column column-25" id="errors">
				<div></div>
			</div>
		</div>
<?php 	} ?>
	</div>
</body>
     
<script src="https://checkout.stripe.com/checkout.js"></script>
  
<script type="text/javascript">
	var form = $("#payment-form");

	$('#payment-form').on('submit', function(){
		pay(form);

		return false;
	});

	function pay(form) {
		var handler = StripeCheckout.configure({
			key: '<?= $this->config->item('stripe_publishable_key') ?>',
			locale: 'auto',
			token: function (token) {
				// You can access the token ID with `token.id`.
				// Get the token ID to your server-side code for use.
				$.post(form.attr('action'), form.serialize() + "&stripe_token_id=" + token.id + "&email=" + token.email, function(response){
					if(response.success) {
						location.href = response.redirect_url;
					}
					else {
						$("#errors div").html(response.message);
					}
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