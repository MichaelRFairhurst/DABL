<?php

class InjectorTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		$this->injector = new Injector;
	}

	function getMockReflectionParameter($type, $name) {
		$parameter = $this->getMockBuilder('ReflectionParameter')->disableOriginalConstructor()->getMock();
		$class = $this->getMockReflectionClass($type);

		$parameter->expects($this->any())
			->method('getClass')
			->will($this->returnValue($class));

		$parameter->expects($this->any())
			->method('getName')
			->will($this->returnValue($name));

		return $parameter;
	}

	function getMockReflectionMethod($name, array $parameters) {
		$method = $this->getMockBuilder('ReflectionMethod')->disableOriginalConstructor()->getMock();

		$method->expects($this->any())
			->method('getName')
			->will($this->returnValue($name));

		$method->expects($this->any())
			->method('getParameters')
			->will($this->returnValue($parameters));

		return $method;
	}

	function getMockReflectionClass($type) {
		$class = $this->getMockBuilder('ReflectionClass')->disableOriginalConstructor()->getMock();

		$class->expects($this->any())
			->method('getName')
			->will($this->returnValue($type));

		return $class;
	}

	function testClassNotFoundExceptionIsWrapped() {
		$parameter = $this->getMockBuilder('ReflectionParameter')->disableOriginalConstructor()->getMock();
		$exception = new ReflectionException();

		$parameter->expects($this->once())
			->method('getClass')
			->will($this->throwException($exception));

		try {
			$this->injector->getFactoryForParameter($parameter);
		} catch(InjectorMisconfigurationException $e) {
			$this->assertSame($exception, $e->getPrevious());
			return;
		} catch(ReflectionException $e) {
			$this->fail('Reflection exception was not wrapped');
		}

		$this->fail('Expected to get a runtime exception, got no exception at all');
	}

	function testNoMatchesForClassWithConstructorArgumentsThrowsException() {
		$constructor = $this->getMockReflectionMethod('anything', array($this->getMockReflectionParameter('anything', 'anything')));
		$parameter = $this->getMockReflectionParameter('anything', 'anything');
		$parameter->getClass()
						->expects($this->once())
						->method('getConstructor')
						->will($this->returnValue($constructor));

		$this->setExpectedException('InjectorMisconfigurationException');
		$this->injector->getFactoryForParameter($parameter);
	}

	function testBindClassnameToFactoryMatchesFactory() {
		$parameter = $this->getMockReflectionParameter('MyClass', 'anything');

		$factory = function() {};
		$this->injector->bind('MyClass', $factory);

		$this->assertSame($factory, $this->injector->getFactoryForParameter($parameter));
	}

	function testBindClassnameAndBadArgnameNotMatchesFactory() {
		$parameter = $this->getMockReflectionParameter('MyClass', 'argname');

		$factory = function() {};
		$this->injector->bind('MyClass $arg____name', $factory);

		$this->assertNotSame($factory, $this->injector->getFactoryForParameter($parameter));
	}

	function testBindClassnameAndBadArgRegexNotMatchesFactory() {
		$parameter = $this->getMockReflectionParameter('MyClass', 'argname');

		$factory = function() {};
		$this->injector->bind('MyClass $/a.G[An]{400}mE/', $factory);

		$this->assertNotSame($factory, $this->injector->getFactoryForParameter($parameter));
	}

	function testBindClassnameAndArgnameToFactoryMatchesFactory() {
		$parameter = $this->getMockReflectionParameter('MyClass', 'argname');

		$factory = function() {};
		$this->injector->bind('MyClass $argname', $factory);

		$this->assertSame($factory, $this->injector->getFactoryForParameter($parameter));
	}

	function testBindClassnameAndArgRegexToFactoryMatchesFactory() {
		$parameter = $this->getMockReflectionParameter('MyClass', 'argname');

		$factory = function() {};
		$this->injector->bind('MyClass $/a.G[An]{2}mE/i', $factory);

		$this->assertSame($factory, $this->injector->getFactoryForParameter($parameter));
	}

	function testGetInstanceNoBindingGetsNewInstanceManualCall() {
		$this->assertInstanceOf('InjectorTest', $this->injector->getInstanceForBinding('InjectorTest'));
	}

	function testGetInstanceWithBindingGetsNewInstanceManualCall() {
		$this->injector->bind('InjectorTest', function() { return 'abcd'; });
		$this->assertEquals('abcd', $this->injector->getInstanceForBinding('InjectorTest'));
	}

	function testGetInstanceNoBindingGetsNewInstanceReflectionCall() {
		$parameter = $this->getMockReflectionParameter('InjectorTest', 'anything');
		$parameter->getClass()->expects($this->once())
							->method('newInstanceArgs')
							->with(array())
							->will($this->returnValue('abcd'));

		$this->assertEquals('abcd', $this->injector->getInstanceForParameter($parameter));
	}

	function testGetInstanceWithBindingGetsNewInstanceReflectionCall() {
		$this->injector->bind('InjectorTest', function() { return 'abcd'; });
		$parameter = $this->getMockReflectionParameter('InjectorTest', 'anything');
		$this->assertEquals('abcd', $this->injector->getInstanceForParameter($parameter));
	}

	/**
	 * This is used for our final test. Bind to StdClass args, provide scalar args, and it
	 * makes assertions that you've bound it correctly and it was provided correctly
	 */
	function myTestMethodWithInjections(StdClass $firstbinding, StdClass $secondbinding, $scalar_one, $scalar_two, $optional = 'optional') {
		$this->assertEquals('firstbinding', $firstbinding->check);
		$this->assertEquals('secondbinding', $secondbinding->check);
		$this->assertEquals('scalar_one', $scalar_one);
		$this->assertEquals('scalar_two', $scalar_two);
		$this->assertEquals('optional', $optional);
	}

	function testRunAllArgsProvided() {
		$this->injector->bind('StdClass $/first/', function() { $r = new StdClass; $r->check = 'firstbinding'; return $r; });
		$this->injector->bind('StdClass', function() { $r = new StdClass; $r->check = 'secondbinding'; return $r; });

		$this->injector->invokeWithInjections($this, 'myTestMethodWithInjections', array('scalar_one', 'scalar_two', 'optional'));
	}

	function testRunOptionalArgNotProvided() {
		$this->injector->bind('StdClass $/first/', function() { $r = new StdClass; $r->check = 'firstbinding'; return $r; });
		$this->injector->bind('StdClass', function() { $r = new StdClass; $r->check = 'secondbinding'; return $r; });

		$this->injector->invokeWithInjections($this, 'myTestMethodWithInjections', array('scalar_one', 'scalar_two'));
	}

	function testRunNotEnoughArgsThrowsInvalidArgumentException() {
		$this->setExpectedException('InvalidArgumentException');
		$this->injector->invokeWithInjections($this, 'myTestMethodWithInjections', array('scalar_one'));
	}
}
