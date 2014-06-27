<div class="main-box clearfix">
	<div class="pull-left image-list">
		<?php if (empty($images)) : ?>
			<div class="alert alert-info text-center">无数据</div>
		<?php else: ?>
			<?php foreach ($images as $imageid => $image) : ?>
				<div class="author">
					<a href="<?php echo base_url(); ?>people/<?php echo ($users[$image['userid']]->urltoken) ? $users[$image['userid']]->urltoken : $users[$image['userid']]->userid ?>">
						<?php echo $users[$image['userid']]->username; ?>
					</a>

					<span>
						发表于 <?php echo unix_to_human($image['dateline']); ?>
					</span>
				</div>
				<div class="thumbnail">
					<h3><?php echo $image['description']; ?></h3>
					<a href="<?php echo base_url(); ?>view/<?php echo $imageid; ?>"><img src="<?php echo $image['path']; ?>" /></a>
					<div class="caption">
						<p class="text-right">
							<a href="#" id="share" class="btn btn-success" role="赞" onclick="">赞</a>
							<a href="#" id="share" class="btn btn-success" role="分享" onclick="">分享</a>
							<a href="#" id="favorite" class="btn btn-success" role="收藏" onclick="">收藏</a>
						</p>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<div class="text-center clearfix">
			<?php echo $pagination; ?>
		</div>
	</div>
	<div class="pull-right">
	</div>
</div>

<?php $this->load->view('layout/footer'); ?>