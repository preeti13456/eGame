<?php $__env->startSection('content'); ?>
<div class="container">
	<div class="row">
		<?php if(session('success')): ?>
			<div class="alert alert-success">
				<?php echo e(session('success')); ?>

			</div>
		<?php endif; ?>
		<div class="panel panel-default">
			<div class="panel-heading">
    			<h2>Your Account</h2>
    		</div>
			<div class="panel-body">
				<div class="row	top-space">
					<div class="col-md-4 sale-data"> <!-- We define the size per screen (mobile, medium and long) -->
						<span> <?php echo e($totalOrders); ?> </span>
						Shoppings
					</div>

					<div class="col-md-4 sale-data">
						<span><?php echo e($totalRatings); ?></span>
						Ratings
					</div>

					<div class="col-md-4 sale-data-2">
						<span><?php echo e(sizeof($comments)); ?></span>
						Comments
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-4" style="margin:60px; margin-left: 200px;">
						<h4><strong>First name:  </strong><?php echo e($user->first_name); ?></h4>
						<h4><strong>Last name:  </strong><?php echo e($user->last_name); ?><h4>
						<h4><strong>Email:  </strong><?php echo e($user->email); ?><h4>
						<h4><strong>Address:  </strong><?php echo e($user->address); ?><h4>
						<h4><strong>City:  </strong><?php echo e($user->city); ?><h4>
						<h4><strong>Postal Code:  </strong><?php echo e($user->postal_code); ?><h4>
						<h4><strong>Telephone:  </strong><?php echo e($user->telephone); ?><h4>
						<h4><strong>Register date:  </strong><?php echo e($user->created_at); ?><h4>
					</div>
					<div class="col-md-4" style="margin-top: 50px">
						<div class='form-group text-center'>
							<div class="row">
								<a class="btn btn-info" href="<?php echo e(route('user_ratings')); ?>" style="background-color:Orange">Your Ratings</a>
							</div>
							<div class="row">
								<a class="btn btn-info" href="<?php echo e(route('user_orders')); ?>" >Your Orders</a>
							</div>
							<div class="row">
								<a class="btn btn-primary" href="<?php echo e(action('UserController@editProfile', $user->id)); ?>">Edit Profile</a>
							</div>
							<div class="row">
								<form action="<?php echo e(action('UserController@destroy', $user->id)); ?>" method="post" style="text-align:center;">
				                   <?php echo e(csrf_field()); ?>

				                   <input name="_method" type="hidden" value="DELETE">
				                   <a class="btn btn-danger" type="submit" onclick="return confirm('DANGER! You will delete your account completely, this action cannot be undone, are you sure about this?')">Delete Account</a>
				                </form>
							</div>	
			            </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>