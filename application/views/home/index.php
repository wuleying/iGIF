<div class="margin12">
	<?php if (empty($images)) : ?>
		<div class="alert alert-info text-center">无数据</div>
	<?php else: ?>
		<?php foreach ($images as $imageid => $image) : ?>
			<div class="col-sm-5 col-md-2" id="image-<?php echo $imageid; ?>">
				<div class="thumbnail">
					<a href="<?php echo base_url(); ?>view/<?php echo $imageid; ?>"><img style="width: 300px; height: 200px;" src="<?php echo $image['path']; ?>" /></a>
					<div class="caption">
						<p>
							上传：
							<a href="<?php echo base_url(); ?>people/<?php echo ($users[$image['userid']]->urltoken) ? $users[$image['userid']]->urltoken : $users[$image['userid']]->userid ?>">
								<?php echo $users[$image['userid']]->username; ?>
							</a>
							<span class="pull-right">
								<?php echo unix_to_human($image['dateline']); ?>
							</span>
						</p>
						<p class="text-right">
							<a href="#" id="share" class="btn btn-success" role="赞" onclick="">赞</a>
							<a href="#" id="share" class="btn btn-success" role="分享" onclick="">分享</a>
							<a href="#" id="favorite" class="btn btn-success" role="收藏" onclick="">收藏</a>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<?php $this->load->view('layout/footer'); ?>