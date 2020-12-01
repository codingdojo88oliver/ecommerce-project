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
				<div class="column column-50 column-offset-25"><a class="checkout-button" href="checkout.html">Cart (5)</a></div></div>
			</div>
		</section>
	</header>
	<div class="container">
		<div class="row">
			<div class="column column-20 product">
				<input class="button button-clear" type="submit" value="Go Back">
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
						<?= $product['description'] ?>
					</div>
				</div>
				<div class="row" id="add-product">
					<form action="checkout.html">
						<fieldset>
							<input type="number" id="product-quantity" value="1">
							<input class="button button-clear float-right" type="submit" value="add to cart">
						</fieldset>
					</form>						
				</div>
			</div>
		</div>
		<div class="row">
			<div class="column">
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
			</div>					
		</div>
	</div>
</body>
</html>