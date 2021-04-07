<div class="container text-center">
	<div class="card product text-left">
		<?php if(Auth::check() && Auth::user()->role == 'Admin'): ?> 
			<div class="absolute actions">
				<form action="<?php echo e(action('ArticleController@destroy', $article->id)); ?>" method="post">
                	<?php echo e(csrf_field()); ?>

                    <a class="btn btn-primary btn-xs" href="<?php echo e(action('ArticleController@edit', $article->id)); ?>" >
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
                  	<input name="_method" type="hidden" value="DELETE">
                  	<button class="btn btn-danger btn-xs" type="submit" onclick="return confirm('Are you sure to delete?')"><span class="glyphicon glyphicon-trash"></span></button>
              	</form>
			</div>
		<?php endif; ?>

		<h1><?php echo e($article->name); ?></h1>
		<div class="row">
			<div class="col-sm-6 col-xs-12">
				<?php if($article->extension): ?>
					<img src='<?php echo e(url("/articles/images/$article->id.$article->extension")); ?>' class="product-avatar">
				<?php endif; ?>
			</div>
			<div class="col-sm-6 col-xs-12">
				<p>
					<strong>Price:</strong><p><?php echo e($article->price); ?> €</p>
					<strong>Platform:</strong><p><?php echo e($article->platform); ?></p>
					<strong>Gender:</strong><p><?php echo e($article->gender); ?></p>
					<strong>Release Date:</strong><p><?php echo e($article->release_date); ?></p>
					<strong>Players Number:</strong><p><?php echo e($article->players_num); ?></p>
					<strong>Assessment:</strong>
					<p>
						<?php  $rating = $article->assessment;  ?>
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
				</p>
				<?php if($article->quantity > 0): ?>
					<?php echo $__env->make('in_shopping_cart.form', compact('article'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<?php else: ?>
					<span style="color:red">We are so sorry!, this product is actually out of stock =(</span>
				<?php endif; ?>
			</div>
		</div>
		<div class="row" style="position: relative; margin: 15px;">
			<strong>Description:</strong>
			<p><?php echo e($article->description); ?></p>
		</div>
	</div>
	<br><br>
	<?php echo $__env->make('recommended_article.similar_articles', compact('articles'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<br><br>
	<?php if(count($reviews)): ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2>Opinions and comments for other users...</h2>
			</div>
			<br>
			<?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="row">
					<div class="col-sm-2">
						<strong><?php echo e($users->where('id', $review->user_id)->first()->first_name); ?></strong>
					</div>
					<div class="col-sm-3">
						<?php echo e($review->created_at); ?>

					</div>	
					<div class="col-sm-2">
						<?php  $rating = $review->score;  ?> 
						<div class="placeholder" style="position: absolute;color: lightgray;">
				            <i class="fa fa-star"></i>
				            <i class="fa fa-star"></i>
				            <i class="fa fa-star"></i>
				            <i class="fa fa-star"></i>
				            <i class="fa fa-star"></i>
				            <span class="small">(<?php echo e($rating); ?>)</span>
				        </div>
				        <div class="overlay" style="position: absolute;top: -2px;">
				            <?php while($rating>0): ?>
				                <?php if($rating >0.5): ?>
				                    <i class="fa fa-star checked"></i>
				                <?php else: ?>
				                    <i class="fa fa-star-half checked"></i>
				                <?php endif; ?>
				                <?php  $rating--;  ?>
				            <?php endwhile; ?>
				        </div>
					</div>
					<div class="col-sm-4">
						<?php echo e($review->comment); ?>

					</div>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<br></br>
		</div>
	<?php endif; ?>
</div>