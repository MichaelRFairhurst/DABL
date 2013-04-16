<h1>You just ran the test suite!</h1>

<p>
	Your result is:
</p>

<div class="test-type">
	<div class="test-count">Passed: <?=count(@$passed);?></div>
	<?php foreach(array_keys($passed) as $pass): ?>
		<div class="test-name"><?=$pass;?></div>
	<?php endforeach; ?>
</div>

<div class="test-type">
	<div class="test-count">Errors: <?=count(@$errors);?></div>
	<?php foreach($errors as $error): ?>
		<div class="test-name"><?=$error->failedTest()->toString();?></div>
		<div class="test-message"><?=$error->thrownException()->getMessage();?></div>
		<div class="test-trace"><?=$error->thrownException()->getTraceAsString();?></div>
	<?php endforeach; ?>
</div>

<div class="test-type">
	<div class="test-count">Failures: <?=count(@$failures);?></div>
	<?php foreach($failures as $failure): ?>
		<div class="test-name"><?=$failure->failedTest()->toString();?></div>
		<div class="test-message"><?=$failure->thrownException()->getMessage();?></div>
		<div class="test-trace"><?=$failure->thrownException()->getTraceAsString();?></div>
	<?php endforeach; ?>
</div>
