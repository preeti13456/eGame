<?php $__env->startSection('title', 'Articles eGame'); ?>
<?php $__env->startSection('content'); ?>
	<?php if(session('error')): ?>
        <div class="custom-alerts alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <?php echo e(session('error')); ?>

        </div>
        <?php Session::forget('error');?>
    <?php endif; ?>
    <div class="container">
    	<?php if(count($articlesByContentBasedFiltering) > 1): ?>
	    	<?php echo $__env->make('recommended_article.purchased_based_recommendations', compact('articlesByContentBasedFiltering'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	    <?php endif; ?>
	   
		<?php if(Auth::user()->role != 'Admin'): ?>
			<div class="panel">
				<div class="panel-heading">
					<h2>Top Ratings</h2>
				</div>
				<div class="panel-body" style="margin: 20px;">
					<div class="carousel">
						<!-- <?php echo e($count = 1); ?>-->
						<ul class="carousel__thumbnails">
							<?php for($i = 0; $i < sizeof($bestRated); $i++): ?>
								<span><?php echo e($count++); ?>)</span>
				                <li>
				                    <a href="<?php echo e(action('ArticleController@show', $bestRated[$i]['id'])); ?>" for="slide-$i">
					                    <img style="height: 180px; width: auto; border-top: 1px solid #ccc; background-color: #f7f7f7" src="<?php echo e(url("/articles/images/".$bestRated[$i]['id'].".".$bestRated[$i]['extension'])); ?>" alt="">
				                    </a>
				                    <div class="card-body">
				                        <h5 class="card-title"><?php echo e($bestRated[$i]['name']); ?></h5>
				                        <p class="card-text text-muted">(<?php echo e($bestRated[$i]['gender']); ?> - <?php echo e($bestRated[$i]['price']); ?> €)</p>
				                        <p>
											<?php  $rating = $bestRated[$i]['assessment']  ?>
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
									            <h5 class="card-title">(Votes: <?php echo e($ratings->where('article_id', $bestRated[$i]['id'])->count()); ?>)</h5>
									        </div>  
										</p>
				                        <?php echo Form::open(['url' => '/in_shopping_carts', 'method' => 'POST', 'class' => 'inline-block']); ?>

											<input type="hidden" name="article_id" value="<?php echo e($bestRated[$i]['id']); ?>">
											<button type="submit" class="btn btn-info" style="position: relative;top: -22px;">
												<span class= "glyphicon glyphicon-shopping-cart"></span>
											</button>
										<?php echo Form::close(); ?>  
				                    </div>
				                </li>
				    		<?php endfor; ?>
				        </ul>
			    	</div>
				</div>
			</div>
			<div class="panel">
				<div class="panel-heading">
					<h2>Top Sales</h2>
				</div>
				<div class="panel-body" style="margin: 20px;">
					<div class="carousel">
						<!-- <?php echo e($count = 1); ?>-->
						<ul class="carousel__thumbnails">
							<?php $__currentLoopData = $bestSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bestSeller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php if($count < 7): ?>
									<span><?php echo e($count++); ?>)</span>
					                <li>
					                    <a href="<?php echo e(action('ArticleController@show', $bestSeller['id'])); ?>" for="slide-1">
						                    <img style="height: 180px; width: auto; border-top: 1px solid #ccc; background-color: #f7f7f7" src="<?php echo e(url("/articles/images/".$bestSeller['id'].".".$bestSeller['extension'])); ?>" alt="">
					                    </a>
					                    <div class="card-body">
					                        <h5 class="card-title"><?php echo e($bestSeller['name']); ?></h5>
					                        <h5 class="card-title">(Purchases: <?php echo e($bestSeller['purchasesNum']); ?>)</h5>
					                        <p class="card-text text-muted">(<?php echo e($bestSeller['gender']); ?> - <?php echo e($bestSeller['price']); ?> €)</p>
					                        <p>
												<?php  $rating = $bestSeller['assessment'];  ?>
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
					                        <?php echo Form::open(['url' => '/in_shopping_carts', 'method' => 'POST', 'class' => 'inline-block']); ?>

												<input type="hidden" name="article_id" value="<?php echo e($bestSeller['id']); ?>">
												<button type="submit" class="btn btn-info" style="position: relative;top: -22px;">
													<span class= "glyphicon glyphicon-shopping-cart"></span>
												</button>
											<?php echo Form::close(); ?>  
					                    </div>
					                </li>
				                <?php endif; ?>
				    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				        </ul>
			    	</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>