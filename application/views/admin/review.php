
<div class="margin12">

	<?php if (empty($gifs)) : ?>

	<?php else: ?>
		<?php foreach ($gifs as $gifid => $gif) : ?>
			<div class="col-sm-6 col-md-2">
				<div class="thumbnail">
					<a href="<?php echo base_url();?>view/<?php echo $gifid; ?>"><img  alt="300x200" style="width: 300px; height: 200px;" src="<?php echo $gif['path']; ?>" /></a>
					<div class="caption">
						<p>
							<?php echo $gif['description']; ?>
						</p>
						<p class="text-right">
							<a href="#" class="btn btn-success" role="通过">通过</a>
							<a href="#" class="btn btn-danger" role="拒绝">拒绝</a>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<?php $this->load->view('layout/footer'); ?>