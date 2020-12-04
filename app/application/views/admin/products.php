<!DOCTYPE html>
<html>
<head>
	<title>eCommerce Capstone Project</title>
	<!-- Google Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">

	<!-- CSS Reset -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">

	<!-- Milligram CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

	<link rel="stylesheet" type="text/css" href="../assets/css/style.css">

	<!-- You should properly set the path from the main file. -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

	<!-- Bootstrap -->
	<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>

	<script type="text/javascript">
		
		$(document).ready(function(){
			$('.edit-button').on('click', function() {
				$("#edit-name").val($(this).data('name'));
				$("#edit-description").val($(this).data('description'));
				$("#edit-product-form").attr('action', '/products/update/' + $(this).data('id'));
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
			<div class="column" id="search-product">
				<form>
					<fieldset>
						<input type="text" placeholder="Product Name" id="product-name">
						<input class="button button-clear float-right" type="submit" value="search">
					</fieldset>
				</form>						
			</div>

			<div class="column" id="filter-products-by-status">
				<button class="button-primary float-right" type="button" data-toggle="modal" data-target="#add-new-product"> Add New Product </button>
			</div>
			<!-- Add New Product Modal -->
			<div id="add-new-product" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Add New Product</h4>
						</div>
						<div class="modal-body">
							<form method="post" action="/products/create" enctype='multipart/form-data'>
								<fieldset>
									<label for="name">Name</label>
									<input type="text" placeholder="Hat" id="name" name="name">
									<label for="description">Description</label>
									<textarea placeholder="Cool hat" id="description" name="description"></textarea>
									<label for="quantity">Quantity</label>
									<input type="number" placeholder="1" id="quantity" name="inventory_count">
									<label for="price">Price</label>
									<input type="number" placeholder="10.00" id="price" name="price">
									<label for="categories">Categories</label>
									<select id="categories" name="categories">
<?php 								foreach($categories as $category) { ?>
										<option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
<?php 								} ?>									
									</select>
									<label for="category">or Add new Category</label>
									<input type="text" placeholder="New Category" id="category" name="category">
									<label for="image">Images</label>
									<input type="file" name="images" value="Upload" multiple>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<input class="button-primary" type="submit" value="Save">
									</div>	
								</fieldset>
							</form>
						</div>

					</div>

				</div>
			</div>

			<!-- Edit Product Modal -->
			<div id="edit-product" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Edit Tshirt</h4>
						</div>
						<div class="modal-body">
							<form id="edit-product-form" method="POST" enctype='multipart/form-data'>
							<fieldset>
								<label for="edit-name">Name</label>
								<input type="text" value="Tshirt" id="edit-name" name="name">
								<label for="edit-description">Description</label>
								<textarea id="edit-description" name="description">Some Cool Description</textarea>
								<label for="edit-categories">Categories</label>
								<select id="edit-categories" name="categories">
<?php 							foreach($categories as $category) { ?>
									<option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
<?php 							} ?>									
								</select>
								<label for="edit-category">or Add new Category</label>
								<input type="text" placeholder="New Category" id="edit-category" name="category">
								<label for="edit-image">Images</label>
								<input type="file" name="images" value="Upload" multiple id="edit-image">			<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<input class="button-primary" type="submit" value="Save">
								</div>				
							</fieldset>
							</form>
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="row">
			<div class="column">
				<p><?= $this->session->flashdata('error'); ?></p>
			</div>
		</div>
		<div class="row">
			<div class="column">
				<table id="admin-products-list">
					<thead>
						<tr>
							<th>Picture</th>
							<th>ID</th>
							<th>Name</th>
							<th>Inventory Count</th>
							<th>Qunatity Sold</th>
							<th colspan="2">Action</th>
						</tr>
					</thead>
					<tbody>
<?php 					foreach($products as $product) { ?>
						<tr>
							<td><img src="<?= json_decode($product['images'])[0] ?>"></td>
							<td><a href="/products/show/<?= $product['id'] ?>"><?= $product['id'] ?></a></td>
							<td><?= $product['name'] ?></td>
							<td><?= $product['inventory_count'] ?></td>
							<td>123</td>
							<td>
								<input class="button button-clear float-right edit-button" type="submit" value="edit" data-toggle="modal" data-target="#edit-product" data-name="<?= $product['name'] ?>" data-description="<?= $product['description'] ?>" data-id="<?= $product['id'] ?>">
							</td>
							<td>
								<form onsubmit="return confirm('Do you really want to delete this product?');" method="POST" action="/products/remove/<?= $product['id'] ?>">
									<input class="button button-clear float-right" type="submit" value="delete">
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