<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" ng-app="Main">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Testception: <?=$controllerclass;?></title>
		<link type="text/css" rel="stylesheet" href="<?php echo site_url('css/style.css', true) ?>" />
		<link type="text/css" rel="stylesheet" href="<?php echo site_url('css/themes/light-green/jquery-ui-1.8.custom.css', true) ?>" />
		<script language="Javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script language="Javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
		<script language="Javascript" type="text/javascript" src="<?php echo site_url('js/global.js', true) ?>"></script>
	</head>
	<body>

		<div class="ui-tabs ui-widget ui-widget-header">
			<h3>Welcome To Testception.... Current level: <?=$controllerclass;?></h3>
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix">
				<?php $selector = "ui-tabs-selected ui-tabs-active ui-state-active"?>
				<li class="ui-state-default ui-corner-top <?php if(!$action) echo $selector; ?>">
					<a href="/">Home</a>
				</li>
				<li class="ui-state-default ui-corner-top <?php if($action == 'run') echo $selector; ?>">
					<a href="<?='/run/suite/' . $controllerclass . 'Test';?>">Test <?php echo $controllerclass; ?></a>
				</li>
				<li class="ui-state-default ui-corner-top <?php if($action == 'source') echo $selector; ?>">
					<a href="<?='/file/controller/' . $controllerclass;?>"><?php echo $controllerclass; ?> Source</a>
				</li>
				<li class="ui-state-default ui-corner-top <?php if($action == 'testsource') echo $selector; ?>">
					<a href="<?='/file/test/' . $controllerclass . 'Test';?>"><?php echo $controllerclass; ?> Test Source</a>
				</li>
			<?php if(@$meta_controllerclass && $meta_controllerclass != $controllerclass): ?>
				<li class="meta-divider">Meta:</li>
				<li class="ui-state-default ui-corner-top">
					<a href="<?='/run/suite/' . $meta_controllerclass . 'Test';?>">Test <?php echo $meta_controllerclass; ?></a>
				</li>
				<li class="ui-state-default ui-corner-top">
					<a href="<?='/file/controller/' . $meta_controllerclass;?>"><?php echo $meta_controllerclass; ?> Source</a>
				</li>
				<li class="ui-state-default ui-corner-top">
					<a href="<?='/file/test/' . $meta_controllerclass . 'Test';?>"><?php echo $meta_controllerclass; ?> Test Source</a>
				</li>
			<?php endif; ?>
			</ul>
		</div>

		<div class="content-wrapper ui-widget">
			<?php View::load('messages', compact('messages')) ?>

			<div class="content">
				<?php View::load($content_view, $params) ?>
			</div>
		</div>

	</body>
</html>
