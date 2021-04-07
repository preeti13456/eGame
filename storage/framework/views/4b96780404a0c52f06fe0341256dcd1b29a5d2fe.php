<?php echo Form::open(['url' => '/in_shopping_carts', 'method' => 'POST', 'class' => 'inline-block']); ?>

	<input type="hidden" name="article_id" value="<?php echo e($article['id']); ?>">
	<input type="submit" value="Add to cart" class="btn btn-info">
<?php echo Form::close(); ?>