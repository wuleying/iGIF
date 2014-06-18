
<div class="margin12">

	<?php if (empty($gifs)) : ?>
		
	<?php else: ?>
		<?php foreach ($gifs as $gif) : ?>
			<div class="col-sm-6 col-md-2">
				<div class="thumbnail">
					<img data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;" src="">
					<div class="caption">
						<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
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