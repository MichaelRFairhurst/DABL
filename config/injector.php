<?php

/**
 * Bind Your Injector Here
 *
 * @var Injector $injector
 *
 * Class arguments to your controller actions will be provided by the injector. To
 * provide subclasses or interface implementations, you can ->bind() a rule to an
 * anonymous method that instantiates the class.
 *
 * It is recommended that different versions of your app are managed by different
 * classes provided by a different injector configuration.
 *
 * Rules begin with a classname:
 * 		$injector->bind('MyEmailer', $factory);
 * 		$injector->bind('MyConfig', $factory);
 * Rules can match argument names:
 *		$injector->bind('MyEmailer $immediate_emailer', $factory);
 *		$injector->bind('MyEmailer $queue_emailer', $factory);
 * Rule argument matchers can be regex:
 *		$injector->bind('MyLogger $/live/i', $factory);
 *		$injector->bind('MyLogger $/dev/i', $factory);
 * Factories just need to be callable:
 *		$injector->bind('MyEmailer', function() { return new DevEmailer; });
 *		$injector->bind('MyConfig', array(new Config, 'getInstance'));
 *
 * @example
 *  $injector->bind('StdClass $mytest', function() {
 *  	$bound = new StdClass;
 *  	$bound->see = 'it worked!';
 *  	return $bound;
 *  });
 */
