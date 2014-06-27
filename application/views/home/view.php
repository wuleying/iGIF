<div class="margin12 clearfix text-center">
	<?php if (IMAGE_STATUS_PENDING == $image['status']) : ?>
		<div class="alert alert-warning">图片审核中</div>
	<?php endif; ?>
	<h3><?php echo $image['description']; ?></h3>
	<div><img src="<?php echo $this->string->imageCacheUrl($image['path'], FALSE); ?>"></div>
</div>
<?php $this->load->view('layout/footer'); ?>