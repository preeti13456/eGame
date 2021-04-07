<?php $__env->startSection('content'); ?>
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2>Select a payment method</h2>
			</div>
			<div class="panel-body">
				<div class="row" style="margin-top: 15px;">
					<div class="col-md-6">
			            <div class="panel panel-default" style="margin-left: 10px;">
			                <?php if($message = Session::get('success')): ?>
			                <div class="custom-alerts alert alert-success fade in">
			                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
			                    <?php echo $message; ?>

			                </div>
			                <?php Session::forget('success');?>
			                <?php endif; ?>
			                <?php if($message = Session::get('error')): ?>
			                <div class="custom-alerts alert alert-danger fade in">
			                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
			                    <?php echo $message; ?>

			                </div>
			                <?php Session::forget('error');?>
			                <?php endif; ?>
			                <div class="panel-heading text-center"><h4>Pay with Credit Card</h4></div>
			                <div class="panel-body">
			                    <form class="form-horizontal" method="POST" id="payment-form" role="form" action="<?php echo URL::route('stripform'); ?>" >
			                        <?php echo e(csrf_field()); ?>

			                        <div class="form-group<?php echo e($errors->has('card_no') ? ' has-error' : ''); ?>">
			                            <label for="card_no" class="col-md-5 control-label">Card Number</label>
			                            <div class="col-md-5">
			                                <input id="card_no" type="text" class="form-control" name="card_no" value="<?php echo e(old('card_no')); ?>" autofocus>
			                                <?php if($errors->has('card_no')): ?>
			                                    <span class="help-block">
			                                        <strong><?php echo e($errors->first('card_no')); ?></strong>
			                                    </span>
			                                <?php endif; ?>
			                            </div>
			                        </div>
			                        <div class="form-group<?php echo e($errors->has('ccExpiryMonth') ? ' has-error' : ''); ?>">
			                            <label for="ccExpiryMonth" class="col-md-5 control-label">Expiry Month</label>
			                            <div class="col-md-2">
			                                <?php echo Form::selectMonth('ccExpiryMonth'); ?>

			                                <?php if($errors->has('ccExpiryMonth')): ?>
			                                    <span class="help-block">
			                                        <strong><?php echo e($errors->first('ccExpiryMonth')); ?></strong>
			                                    </span>
			                                <?php endif; ?>
			                            </div>
			                        </div>
			                        <div class="form-group<?php echo e($errors->has('ccExpiryYear') ? ' has-error' : ''); ?>">
			                            <label for="ccExpiryYear" class="col-md-5 control-label">Expiry Year</label>
			                            <div class="col-md-2">
			                                <?php echo Form::selectYear('ccExpiryYear', 2019, 2030); ?>

			                                <?php if($errors->has('ccExpiryYear')): ?>
			                                    <span class="help-block">
			                                        <strong><?php echo e($errors->first('ccExpiryYear')); ?></strong>
			                                    </span>
			                                <?php endif; ?>
			                            </div>
			                        </div>
			                        <div class="form-group<?php echo e($errors->has('cvvNumber') ? ' has-error' : ''); ?>">
			                            <label for="cvvNumber" class="col-md-5 control-label">CVV Number</label>
			                            <div class="col-md-2">
			                                <input id="cvvNumber" type="text" class="form-control" name="cvvNumber" value="<?php echo e(old('cvvNumber')); ?>" autofocus>
			                                <?php if($errors->has('cvvNumber')): ?>
			                                    <span class="help-block">
			                                        <strong><?php echo e($errors->first('cvvNumber')); ?></strong>
			                                    </span>
			                                <?php endif; ?>
			                            </div>
			                        </div>
			                        <?php echo e(Form::hidden('amount', $order->total)); ?>

			                        <div class="form-group">
			                            <div class="col-md-6 col-md-offset-4">
			                                <script class="stripe-button"></script>
			                            </div>
			                        </div>
			                    </form>
			                </div>
			            </div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-default" style="margin-right: 10px;">
							<div class="panel-heading text-center"><h4>Pay with PayPal</h4></div>
							<div class="panel-body text-center" style="height: 247px;">
								<?php echo Form::open(['url' => '/cart', 'method' => 'POST', 'class' => 'inline-block']); ?>

								    <button name="PayPalButton" type='submit' class="btn btn-link">
								    	<img src='<?php echo e(url("/articles/images/paypal-smart-payment-button-for-simple-membership.png")); ?>'>
								    </button>
								<?php echo Form::close(); ?>

							</div>
						</div>
					</div>
				</div>
				<div class="text-center">
					<form action="<?php echo e(action('OrderController@cancelOrder', $order->id)); ?>" method="post">
	            		<?php echo e(csrf_field()); ?>

	              		<input type="submit" value="Cancel Order" class="btn btn-danger">
	          		</form>
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>