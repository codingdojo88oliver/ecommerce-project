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
								<form>
									<fieldset>
										<select id="sort">
											<option value="14-17">Order in Process</option>
											<option value="14-17">Shipped</option>
											<option value="14-17">Cancelled</option>
											<option value="0-13">Shipped</option>
										</select>
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