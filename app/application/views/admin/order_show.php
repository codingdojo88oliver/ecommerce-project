<!DOCTYPE html>
<html>
<head>
	<title>eCommerce Capstone Project</title>
	<!-- Google Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">

	<!-- CSS Reset -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">

	<!-- Milligram CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

	<link rel="stylesheet" type="text/css" href="../../assets/css/style.css">

	<!-- You should properly set the path from the main file. -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

</head>
<body>
	<header id="admin">
		<section class="container">
			<div class="row">
				<div class="column" id="admin-navbar">
					<input class="button button-clear" type="submit" value="Dashboard">
					<input class="button button-clear" type="submit" value="Orders">
					<input class="button button-clear" type="submit" value="Products">
				</div>
				<div class="column column-50 column-offset-25">Logout</div>
			</div>
		</section>
	</header>
	<div class="container">
		<div class="row">
			<div class="column column-20 product">
				<h5>Order ID: <?= $order['id'] ?></h5>
				<h6>Customer Shipping Info:</h6>
				<address>
					Name: <?= $order['shipping_full_name'] ?> <br>
					Address: <?= $order['shipping_address_1'] ?>, <?= $order['shipping_address_2'] ?> <br>
					City: <?= $order['shipping_city'] ?> <br>
					State: <?= $order['shipping_state'] ?> <br>
					Zip: <?= $order['shipping_zip'] ?> <br>
				</address>
				<br> <br>
				<h6>Customer Billing Info:</h6>
				<address>
					Name: <?= $order['billing_full_name'] ?> <br>
					Address: <?= $order['billing_address_1'] ?>, <?= $order['billing_address_2'] ?> <br>
					City: <?= $order['billing_city'] ?> <br>
					State: <?= $order['billing_state'] ?> <br>
					Zip: <?= $order['billing_zip'] ?> <br>			
				</address>
			</div>
			<div class="column column-78">
				<div class="row">
					<div class="column">
						<table id="admin-order-details">
							<thead>
								<tr>
									<th>ID</th>
									<th>Item</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
<?php 							foreach($products as $product) { ?>
								<tr>
									<td><?= $product->id ?></td>
									<td><?= $product->name ?></td>
									<td>$<?= $product->price ?></td>
									<td><?= $product->quantity ?></td>
									<td>$<?= $product->total ?></td>
								</tr>
<?php 							} ?>
							</tbody>
						</table>						
					</div>
				</div>
				<div class="row">
					<div class="column column-20">
						<p>Status: <span><?= $order['status'] ?></span></p>
					</div>
					<div class="column column-offset-60 column-20 total-summary">
						<p>Subtotal: <span>$<?= $order['subtotal'] ?></span></p>
						<p>Shipping: <span>$<?= $order['shipping'] ?>0.00</span></p>
						<p>Total: <span>$<?= $order['total'] ?></span></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>