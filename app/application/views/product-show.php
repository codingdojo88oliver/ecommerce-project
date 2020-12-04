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

	<link rel="stylesheet" type="text/css" href="/../assets/css/style.css">

	<!-- You should properly set the path from the main file. -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

</head>
<body>
	<header>
		<section class="container">
			<div class="row">
				<div class="column"><h4>eCommerce Dojo</h4></div>
				<div class="column column-50 column-offset-25"><a class="checkout-button" href="/cart">Cart (<?= $cart_count ?>)</a></div></div>
			</div>
		</section>
	</header>
	<div class="container">
		<div class="row">
			<div class="column column-20 product">
				<a href="/categories" class="button button-clear">Go Back</a>
				<h4><?= $product['name'] ?></h4>
				<img src="<?= json_decode($product['images'])[0] ?>">
				<div class="column other-images">
<?php 		foreach(json_decode($product['images']) as $key => $image) {
				if($key != 0) { ?>
					<img class="" src="<?= $image ?>">
<?php 			} 
			} ?>
				</div>	
			</div>
			<div class="column column-78">
				<div class="row">
					<div class="product-description column column-78">
						<p><?= $this->session->flashdata('errors'); ?></p>
						<p><?= $this->session->flashdata('message'); ?></p>
						<?= $product['description'] ?>
					</div>
				</div>
				<div class="row" id="add-product">
					<form action="/carts/add_to_cart" method="POST">
						<fieldset>
							<input type="hidden" name="id" value="<?= $product['id'] ?>">
							<input type="number" name="quantity" id="product-quantity" placeholder="1">
							<input class="button button-clear float-right" type="submit" value="add to cart">
						</fieldset>
					</form>						
				</div>
			</div>
		</div>
		<div class="row">
			<div class="column">
<?php 		if(count($similar_items)) { ?>
				<h5>Similar Items</h5>
				<div class="row">
					<div class="products column">
<?php 				foreach($similar_items as $similar_item) { ?>
						<div>
							<a href="/products/show/<?= $similar_item['id'] ?>"><img class="" src="<?= json_decode($similar_item['images'])[0] ?>"></a>
							<p><small><?= $similar_item['name'] ?></small></p>
							<p class="float-right"><strong>$<?= $similar_item['price'] ?></strong></p>
						</div>
<?php 				} ?>
					</div>
				</div>
<?php 		} ?>
			</div>					
		</div>
	</div>
</body>
</html>