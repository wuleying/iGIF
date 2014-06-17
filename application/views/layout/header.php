<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="description" content=""/>
		<title><?php echo $title; ?></title>
		<link rel="stylesheet" href="<?php echo base_url('/css/bootstrap.min.css'); ?>" type="text/css" media="screen,print" />
		<link rel="stylesheet" href="<?php echo base_url('/css/common.css'); ?>" type="text/css" media="screen,print" />
		<script src="<?php echo base_url('/js/jquery.min.js'); ?>"></script>
		<script src="<?php echo base_url('/js/bootstrap.min.js'); ?>"></script>
	</head>
	<body>

		<div>
			<nav class="navbar navbar-default navbar-static-top" role="navigation">
				<div class="navbar-header">
					<a class="navbar-brand" href="<?php echo base_url(); ?>">iGIF</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav  navbar-left">
						<li<?php if ('home' == $this->router->class && 'index' == $this->router->method): ?> class="active"<?php endif; ?>><a href="<?php echo base_url(); ?>">最新</a></li>
						<li<?php if ('home' == $this->router->class && 'hot' == $this->router->method): ?> class="active"<?php endif; ?>><a href="<?php echo base_url('/hot'); ?>">热门</a></li>
						<?php if (isset($userInfo) && !empty($userInfo)) : ?>
							<li<?php if ('user' == $this->router->class && 'add' == $this->router->method): ?> class="active"<?php endif; ?>><a href="<?php echo base_url('/add'); ?>">上传</a></li>
							<li class="dropdown">

								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $userInfo['email']; ?><b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="<?php echo base_url('/user/uploads'); ?>">我的上传</a></li>
									<li><a href="<?php echo base_url('/user/favorites'); ?>">我的收藏</a></li>
									<li><a href="<?php echo base_url('/user/profiled'); ?>">个人资料</a></li>
									<li class="divider"></li>
									<li><a href="<?php echo base_url('/logout'); ?>">退出</a></li>
								</ul>
							</li>
						<?php else: ?>
							<li<?php if ('register' == $this->router->class): ?> class="active"<?php endif; ?>><a href="<?php echo base_url('/register'); ?>">注册</a></li>
							<li<?php if ('signin' == $this->router->class): ?> class="active"<?php endif; ?>><a href="<?php echo base_url('/signin'); ?>">登录</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</nav>