<div class="well well-lg margin12">
	<form method="post" action="<?php echo base_url('/admin/changeuser'); ?>">
		<input type="hidden" name="id" value="<?php echo isset($currentUser->userid) ? $currentUser->userid : 0; ?>" />
		<table class="table">
			<tr>
				<td width="10%" class="text-right"><label for="email">邮箱：</label></td>
				<td class="text-left">
					<div class="col-xs-3">
						<input type="text" id="email" class="form-control" name="email" value="<?php echo isset($currentUser->email) ? $currentUser->email : ''; ?>"  />
					</div>
				</td>
			</tr>

			<tr>
				<td class="text-right"><label for="groupid">用户组：</label></td>
				<td class="text-left">
					<div class="col-xs-3">
						<select id="groupid" class="form-control" name="groupid">
							<option value="0">请选择</option>
							<?php foreach ($groups->result() as $group) : ?>
								<option value="<?php echo $group->groupid; ?>"<?php if (isset($currentUser->groupid) && $group->groupid == $currentUser->groupid) : ?> selected<?php endif; ?>><?php echo $group->groupname; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><button type="submit" class="btn btn-primary">提交</button></td>
			</tr>
		</table>
	</form>
</div>
<?php $this->load->view('layout/footer'); ?>