<div class="body container-main">
<div class="row">
		<div class="col-sm-4 col-xs-12">
			<div class="container-main">
				<div class="pull-right">
					<i class="fa fa-heart fa-3x">&nbsp</i>
				</div>
				<div class="">
				<img class ="img-responsive center-block" src="<?php  echo $product_data['pimage'];?>"  alt="" />
				</div>
			</div>
		</div><!--/col-md-4-->
		
		<div class="col-sm-8 col-xs-12">
			<div class="container-main ">
				<div class="productdetail">
				<br>
					<?php $pname = $product_data['pname']; $srt = str_replace("_"," ","$pname");?>
					<p class="text-left"><strong><?php echo strtoupper ($srt); ?></strong></p>
					<h2 class="text-left">Rs. <?php  echo $product_data['pprice'];?></h2>
					<br>
					<h3 class="text-left">Product category : <?php  echo strtoupper($product_data['sub']);?></h3>
					<br>
					<div class="row">
                        <div class="col-xs-12">
                        <a href="#" class="btn btn-default add-to-carts">Buy Now</a>
                        <a href="#" class="btn btn-default add-to-carts"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                        </div>
                    </div> 	
                    <br>
                    
				</div>
			</div><!--contaier-main-->
		</div><!--/col-md-9-->
</div><!--row-->
<div class="row">
		<div class="col-md-12">
			<div class="container-main">
			<h2 class="title text-center">Description</h2>
			<h3><?php  echo $product_data['pdesc'];?></h3>
			</div><!--contaier-main-->
		</div><!--/col-md-9-->
</div><!--row-->
<div class="row">
		<div class="col-md-12">
			<div class="container-main">
			<?php include 'html/php/includes/similar.php' ?>
			</div><!--contaier-main-->
		</div><!--/col-md-9-->
</div><!--row-->
</div><!--body-->