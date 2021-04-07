<?php $__env->startSection('title', 'Articles eGame'); ?>
<?php $__env->startSection('content'); ?>
	<?php if(session('error')): ?>
        <div class="custom-alerts alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <?php echo e(session('error')); ?>

        </div>
        <?php Session::forget('error');?>
    <?php endif; ?>
	<div class="text-center products-container">
		<div class="row">
			<?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="card product text-left fixed">
				<h1><a href="<?php echo e(action('ArticleController@show', $article['id'])); ?>"><?php echo e($article['name']); ?></a></h1>
					<div class="row">
						<div class="col-sm-5 col-xs-8">
							<?php if($article['extension']): ?>
								<a href="<?php echo e(action('ArticleController@show', $article['id'])); ?>">
									<img src='<?php echo e(url("/articles/images/$article[id].$article[extension]")); ?>' class="product-avatar">
								</a>
							<?php endif; ?>
						</div>
						<div class="col-sm-5 col-xs-8">
							<p>
								<strong>Price:</strong><p><?php echo e($article['price']); ?></p>
								<strong>Platform:</strong><p><?php echo e($article['platform']); ?></p>
								<strong>Assessment:</strong>
								<p>
									<?php  $rating = $article['assessment'];  ?>
									<div class="placeholder" style="color: lightgray;">
							            <i class="fa fa-star"></i>
							            <i class="fa fa-star"></i>
							            <i class="fa fa-star"></i>
							            <i class="fa fa-star"></i>
							            <i class="fa fa-star"></i>
							            <span class="small">(<?php echo e($rating); ?>)</span>
							        </div>
							        <div class="overlay" style="position: relative;top: -22px;">
							            <?php while($rating>0): ?>
							                <?php if($rating >0.5): ?>
							                    <i class="fa fa-star checked"></i>
							                <?php else: ?>
							                    <i class="fa fa-star-half checked"></i>
							                <?php endif; ?>
							                <?php  $rating--;  ?>
							            <?php endwhile; ?>
							        </div>
								</p>
							<?php if($article['quantity'] > 0): ?>
								<?php echo $__env->make('in_shopping_cart.in_shopping_cart', compact('article'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
							<?php else: ?>
								<span style="color:red">We are so sorry!, this product is actually out of stock =(</span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
		<div>
		<?php echo e($articles->links()); ?>

		</div>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>