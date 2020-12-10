<?php		if($key % 5 == 0) { ?>
				<div class="row">
					<div class="products column column-78">
<?php 		} ?>
						<div>
							<a href="/products/show/<?= $product['id'] ?>"><img class="" src="<?= json_decode($product['images'])[0] ?>"></a>
							<p><small><?= $product['name'] ?></small></p>
							<p class="float-right"><strong>$<?= $product['price'] ?></strong></p>
						</div>
<?php 		if(($key != 0 || count($products) == 1) && ($key % 4 == 0 || $key + 1 == count($products))) { ?>
					</div>
				</div>
<?php 		} ?>