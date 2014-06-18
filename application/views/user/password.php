<?php $this->load->view('layout/usernav'); ?>
<div class="margin12">
	<form method="post" action="<?php echo base_url('/user/password'); ?>">
		<input type="hidden" name="action" value="1" />
		<table class="table">
			<tr>
				<td width="10%" class="text-right"><label for="oldpassword">旧密码：</label></td>
				<td class="text-left">
					<div class="col-xs-3">
						<input type="password" name="oldpassword" id="oldpassword" class="form-control" value="" maxlength="16" placeholder="6-16位英文字母、数字与特殊符号组成" />
					</div>
				</td>
			</tr>
			<tr>
				<td width="10%" class="text-right"><label for="newpassword">新密码：</label></td>
				<td class="text-left">
					<div class="col-xs-3">
						<input type="password" name="newpassword" id="newpassword" class="form-control" value="" maxlength="16" placeholder="6-16位英文字母、数字与特殊符号组成" />
					</div>
				</td>
			</tr>
			<tr>
				<td width="10%" class="text-right"><label for="repassword">重复密码：</label></td>
				<td class="text-left">
					<div class="col-xs-3">
						<input type="password" name="repassword" id="repassword" class="form-control" value="" maxlength="16" placeholder="6-16位英文字母、数字与特殊符号组成" />
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
<?php $this->load->view('layout/footer'); ?>