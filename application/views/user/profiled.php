<?php $this->load->view('layout/usernav'); ?>

<div class="margin12">
	<form method="post" action="<?php echo base_url('/user/profiled'); ?>">
		<input type="hidden" name="action" value="1" />
		<table class="table">
			<tr>
				<td width="10%" class="text-right"><label for="username">呢称：</label></td>
				<td class="text-left">
					<div class="col-xs-3">
						<input type="text" name="username" id="username" class="form-control" value="<?php echo ($userInfo['username']) ? $userInfo['username'] : set_value('username'); ?>" maxlength="16" placeholder="由2-16个英文字母、数字与汉字组成" />
					</div>
				</td>
			</tr>

			<tr>
				<td class="text-right"><label for="urltoken">个性网址：</label></td>
				<td class="text-left">
					<div class="col-xs-3">
						<?php if (empty($userInfo['urltoken'])) : ?>
							<input type="text" name="urltoken" id="urltoken" class="form-control" value="<?php echo set_value('urltoken'); ?>" autocomplete="off" maxlength="32" placeholder="由4-32个英文字母组成" />
						<?php endif; ?>
						<p><?php echo base_url('/people'); ?>/<strong id="showurltoken"><?php echo ($userInfo['urltoken']) ? $userInfo['urltoken'] : set_value('urltoken'); ?></strong></p>
					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><button type="submit" class="btn btn-primary">提交</button></td>
			</tr>
		</table>
	</form>

	<?php if (validation_errors()) : ?>
		<div class="alert alert-danger">
			<p><strong>错误信息！</strong></p>
			<?php echo validation_errors(); ?>
		</div>
	<?php endif; ?>
</div>

<script>
	$(document).ready(function() {
		$("#urltoken").keyup(function() {
			$("#showurltoken").html($("#urltoken").val());
		});
	});
</script>

<?php $this->load->view('layout/footer'); ?>