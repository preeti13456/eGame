<?php $__env->startSection('content'); ?>
<div class="container">
	<?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>Your Ratings</h2>
		</div>
		<div class="panel-body">
			<div class="row top-space">
				<div class="col-md-2 sale-data-2"> <!-- We define the size per screen (mobile, medium and long) -->
					<span><?php echo e($total); ?></span>
					Total Ratings
				</div>
			</div>
			<br>
			<?php if($ratings->count()): ?>
				<table class="table table-bordered table-striped">
					<thead style="background-color:#3f51b5; color:white">
						<tr>
							<th>Score</th>
							<th>Game</th>
							<th>Comments</th>
							<th>Rating Date</th>
						</tr>
					</thead>
					<tbody>
						<?php $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td>
									<!--<?php echo e($starScore = $rating->score); ?>-->
							        <div class="placeholder" style="position:relative; color: #ffffff;">
							            <i class="fa fa-star"></i>
							            <i class="fa fa-star"></i>
							            <i class="fa fa-star"></i>
							            <i class="fa fa-star"></i>
							            <i class="fa fa-star"></i>
							            <i class="fa fa-star"></i>
							            <span class="small" style="color: lightgray;">(<?php echo e($starScore); ?>)</span>
							        </div>
							        <div class="overlay" style="position:relative; top: -22px;">
							            <?php while($starScore>0): ?>
							                <?php if($starScore >0.5): ?>
							                    <i class="fa fa-star checked"></i>
							                <?php else: ?>
							                    <i class="fa fa-star-half checked"></i>
							                <?php endif; ?>
							                <!--<?php echo e($starScore--); ?>-->
							            <?php endwhile; ?>
							        </div>
							    </td>
	 							<td><?php echo e($articlesCollection->where('id', $rating->article_id)->first()->name); ?></td>
								<td><?php echo e($rating->comment); ?></td>
								<td><?php echo e($rating->created_at); ?></td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
				</table>
			<?php endif; ?>
			<?php echo e($ratings->links()); ?>

			<div class='form-group text-center'>
				<a href="<?php echo e(route('account')); ?>" class="btn btn-info" >Back to your Account</a>
			</div>
		</div>	
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>