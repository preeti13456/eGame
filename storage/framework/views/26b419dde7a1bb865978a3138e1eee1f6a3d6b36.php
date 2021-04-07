<div class="panel panel-default">
	<div class="panel-heading">
		<h2>Recommendations based on other similar users...</h2>
	</div>
	<br><br>
	<div class="panel-body">
		<div class="carousel">
    		<!-- <?php echo e($count = 0); ?>-->
			<ul class="carousel__thumbnails">
				<?php for($i = 0; $i < sizeof($articlesByCollaborativeFiltering); $i++): ?>
	                <li>
	                    <a href="<?php echo e(action('ArticleController@show', $articlesByCollaborativeFiltering[$count]['id'])); ?>" for="slide-1">
		                    <img src="<?php echo e(url("/articles/images/".$articlesByCollaborativeFiltering[$count]['id'].".".$articlesByCollaborativeFiltering[$count]['extension'])); ?>" alt="">
	                    </a>
	                    <div class="card-body">
	                        <h5 class="card-title"><?php echo e($articlesByCollaborativeFiltering[$count]['name']); ?></h5>
	                        <p class="card-text text-muted">(<?php echo e($articlesByCollaborativeFiltering[$count]['gender']); ?> - <?php echo e($articlesByCollaborativeFiltering[$count]['price']); ?> €)</p>
	                        <p>
								<?php  $rating = $articlesByCollaborativeFiltering[$count]['assessment']  ?>
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

								<input type="hidden" name="article_id" value="<?php echo e($articlesByCollaborativeFiltering[$count]['id']); ?>">
								<button type="submit" class="btn btn-info" style="position: relative;top: -22px;">
									<span class= "glyphicon glyphicon-shopping-cart"></span>
								</button>
							<?php echo Form::close(); ?> 
	                    </div>
	                </li>
		            <!--<?php echo e($count++); ?>-->
	    		<?php endfor; ?>
            </ul>
        </div>
	</div>
</div>