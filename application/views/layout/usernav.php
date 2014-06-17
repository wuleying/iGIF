<div class="margin12">
	<ul class="nav nav-tabs">
		<li<?php if ('profiled' == $this->router->method): ?> class="active"<?php endif; ?>><a href="<?php echo base_url('/user/profiled'); ?>">账号</a></li>
		<li<?php if ('password' == $this->router->method): ?> class="active"<?php endif; ?>><a href="<?php echo base_url('/user/password'); ?>">密码</a></li>
	</ul>
</div>