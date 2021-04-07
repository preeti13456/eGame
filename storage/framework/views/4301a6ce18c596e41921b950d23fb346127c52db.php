<?php $__env->startSection('content'); ?>
	<div class= "container">
		<div class="big-padding text-center blue-grey white-text">
			<h2>Your shopping cart</h2>
		</div>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Article</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php for($i = 0; $i < $articles->count(); $i++): ?>
					<tr>
						<td><?php echo e($articles[$i]->name); ?></td>
						<td><?php echo e($articles[$i]->price); ?></td>
						<td><?php echo e($in_shopping_carts[$i]->quantity); ?></td>
						<input type="hidden" name="total" value="<?php echo e($total = $total + $in_shopping_carts[$i]->quantity * $articles[$i]->price); ?>">
						<td>
							<form action="<?php echo e(action('InShoppingCartController@destroy', $in_shopping_carts[$i]->id)); ?>" method="post">
		                		<?php echo e(csrf_field()); ?>

		                  		<input name="_method" type="hidden" value="DELETE">
		                  		<button class="btn btn-danger btn-xs" type="submit"><span class="glyphicon glyphicon-trash"></span></button>
		              		</form>
						</td>
					</tr>
				<?php endfor; ?>
				<tr>
					<th>Total</th>
					<td><?php echo e($total); ?></td>
					<td><?php echo e($in_shopping_carts->sum('quantity')); ?></td>	<!--We show the sum of the quantities of all the articles -->
				</tr>
			</tbody>
		</table>
		<div class='text-right'>
			<a href="<?php echo e(route('delivery')); ?>" class="btn btn-success">
	            Checkout
	        </a>
	        <a href="<?php echo e(route('home')); ?>" class="btn btn-info">Continues shopping</a>
		</div>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>