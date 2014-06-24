
<div class="margin12">

	<script type="text/javascript">
		function review(imageid, status)
		{
			if (<?php echo IMAGE_STATUS_FAILED ?> == status)
			{
				if (!confirm("您确定要执行？"))
				{
					return FALSE;
				}
			}
			$("#image-" + imageid).fadeOut("slow");
			return false;
		}
	</script>


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
							<a href="#" id="reviewed" class="btn btn-success" role="通过" onclick="return review(<?php echo $imageid; ?>, <?php echo IMAGE_STATUS_REVIEWED; ?>);">通过</a>
							<a href="#" id="failed" class="btn btn-warning" role="拒绝" onclick="return review(<?php echo $imageid; ?>, <?php echo IMAGE_STATUS_FAILED; ?>);">拒绝</a>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<?php $this->load->view('layout/footer'); ?>