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

	<link rel="stylesheet" type="text/css" href="../assets/css/style.css">

	<!-- You should properly set the path from the main file. -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			$(".order-status").on("change", function(){

				var form = $(this).parent().parent();

				$.post(form.attr('action'), form.serialize(), function(data){
					form.find('.message').text(data.message).fadeIn().fadeOut(1000);
				}, "json");
			});
		});
	</script>

</head>
<body>
	<header id="admin">
		<section class="container">
			<div class="row">
				<div class="column" id="admin-navbar">
					<a href="/admin/dashboard" class="button button-clear">Dashboard</a>
					<a href="/admin/orders" class="button button-clear">Orders</a>
					<a href="/admin/products" class="button button-clear">Products</a>
				</div>
				<div class="column column-50 column-offset-25"><a class="button button-clear" href="/admin/logout">Logout</a></div>
			</div>
		</section>
	</header>
	<div class="container">
		<div class="row clearfix">
			<div class="column" id="filter-products">
				<form>
					<fieldset>
						<input type="text" placeholder="Product Name" id="product-name">
						<input class="button button-clear float-right" type="submit" value="search">
					</fieldset>
				</form>						
			</div>

			<div class="column" id="filter-products-by-status">
				<form>
					<fieldset>
						<select id="sort">
							<option value="0-13">Show All</option>
							<option value="14-17">Order in Process</option>
							<option value="14-17">Shipped</option>
							<option value="14-17">Cancelled</option>
						</select>
					</fieldset>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="column">
				<table>
					<thead>
						<tr>
							<th>Order ID</th>
							<th>Name</th>
							<th>Date</th>
							<th>Billing Address Total</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
<?php 					foreach($orders as $order) { ?>
						<tr>
							<td><a href="/orders/show/<?= $order['id'] ?>"><?= $order['id'] ?></a></td>
							<td><?= $order['name'] ?></td>
							<td><?= $order['date'] ?></td>
							<td><?= $order['billing_address'] ?></td>
							<td>
								<form method="POST" action="/orders/update" class="order-update">
									<fieldset>
										<input type="hidden" name="order_id" value="<?= $order['id'] ?>">
										<input type="hidden" name="user_id" value="<?= $order['user_id'] ?>">
										<select name="status" class="order-status">
											<option <?= (ORDER_IN_PROGRESS == $order['status'] ? "selected='selected'" : "" ) ?> value="<?= ORDER_IN_PROGRESS ?>">Order in Process</option>
											<option <?= (ORDER_SHIPPED == $order['status'] ? "selected='selected'" : "" ) ?> value="<?= ORDER_SHIPPED ?>">Shipped</option>
											<option <?= (ORDER_RECEIVED == $order['status'] ? "selected='selected'" : "" ) ?> value="<?= ORDER_RECEIVED ?>">Received</option>
											<option <?= (ORDER_CANCELLED == $order['status'] ? "selected='selected'" : "" ) ?> value="<?= ORDER_CANCELLED ?>">Cancelled</option>
										</select>
										<small class="message"></small>
									</fieldset>
								</form>						
							</td>
						</tr>
<?php 					} ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="column" id="full-pagination">
				<input class="button button-clear" type="submit" value="1">
				<input class="button button-clear" type="submit" value="2">
				<input class="button button-clear" type="submit" value="3">
				<input class="button button-clear" type="submit" value="4">
				<input class="button button-clear" type="submit" value="5">
				<input class="button button-clear" type="submit" value="6">
				<input class="button button-clear" type="submit" value="7">
				<input class="button button-clear" type="submit" value="8">
				<input class="button button-clear" type="submit" value="9">
				<input class="button button-clear" type="submit" value="10">
				<input class="button button-clear" type="submit" value="&#8594;">
			</div>					
		</div>		
	</div>
</body>
</html>