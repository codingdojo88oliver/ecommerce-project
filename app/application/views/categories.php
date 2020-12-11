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
	<script type="text/javascript">
		$(document).ready(function(){
			/* Listens to click events for the pagination numbers */
			$("#full-pagination").on("click", '.page_number', function(){
				var page = $(this).attr('data-page');
				var search = $(this).attr('data-search');
				$("#pagination-form #page").val(page);
				$("#pagination-form #search").val(search);
				$("#pagination-form").submit();
			});

			/* Submits a hidden pagination form */
			$("#pagination-form").on("submit", function(){
				var form = $(this);

				$.post(form.attr('action'), form.serialize(), function(data){
					$("#products-list").html(data.html);
				}, "json");

				return false;	
			});

			$("#filter-products").on("submit", "#search", function(){
				var form = $(this);
				$.post(form.attr('action'), form.serialize(), function(data){
					$("#products-list").html(data.html);
					$("#full-pagination").html(data.pagination);
				}, "json");
				return false;
			});
		})	
	</script>
</head>
<body>
	<header>
		<section class="container">
			<div class="row">
				<div class="column"><h4>eCommerce Dojo</h4></div>
				<div class="column column-50 column-offset-25"><a class="checkout-button" href="/cart">Cart (<?= $cart_count ?>)</a></div>
			</div>
		</section>
	</header>
	<div class="container">
		<div class="row">
			<div class="column column-20">
				<!-- Description list -->
				<dl>
					<dt>Categories</dt>
<?php 			foreach ($categories as $key => $category) { ?>
					<dd><a href="/categories/show/<?= $category['id'] ?>"><?= $category['name'] ?> (<?= $category['product_count'] ?>)</a></dd>
<?php 			} ?>
					<dd><a href="/categories">Show All</a></dd>
				</dl>				
			</div>
			<div class="column column-78">
				<div class="row clearfix">
					<h5 class="column"><?= $selected_category ?></h5>
					<div class="column" id="simple-pagination">
						<input class="button button-clear" type="submit" value="first">
						<input class="button button-clear" type="submit" value="prev">
						<input class="button button-clear" type="submit" value="2">
						<input class="button button-clear" type="submit" value="next">
					</div>
				</div>
				<div class="row clearfix">
					<div class="column" id="filter-products">
						<form id="search" method="post" action="/products/get_items">
							<fieldset>
								<input type="text" placeholder="Product Name" id="product-name" name="name">
								<input class="button button-clear float-right" type="submit" value="search">
							</fieldset>
						</form>						
					</div>

					<div class="column" id="sort-products">
						<form>
							<fieldset>
								<label for="sort">Sort By</label>
								<select id="sort">
									<option value="0-13">Price</option>
									<option value="14-17">Most Popular</option>
								</select>
							</fieldset>
						</form>
					</div>
				</div>
				<div id="products-list">
<?php 		foreach ($products as $key => $product) {
				if($key % 5 == 0) { ?>
					<div class="row">
						<div class="products column column-78">
<?php 		} ?>
							<div>
								<a href="/products/show/<?= $product['id'] ?>"><img class="" src="<?= json_decode($product['images'])[0] ?>"></a>
								<p><small><?= $product['name'] ?></small></p>
								<p class="float-right"><strong>$<?= $product['price'] ?></strong></p>
							</div>
<?php 			if(($key != 0 || count($products) == 1) && ($key % 4 == 0 || $key + 1 == count($products))) { ?>
						</div>
					</div>
<?php 			}  
			} ?>
				</div>
				<div class="row">
					<div class="column" id="full-pagination">
<?php 				foreach (range(1, $pages) as $page) 
					{ ?>
						<button class="button button-clear page_number" data-page="<?= $page; ?>" data-search=""><?= $page; ?></button>
<?php 				} ?>
					</div>
					<form id="pagination-form" method="POST" action="/products/get_items">
						<input type="hidden" name="page_number" id="page">
						<input type="hidden" name="search" id="search">
					</form>				
				</div>
			</div>
		</div>
	</div>
</body>
</html>