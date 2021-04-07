<!-- resources/views/articles/show_article.blade.php -->


<?php $__env->startSection('content'); ?>
	<?php echo $__env->make('article.article', compact('article', 'articles'), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>