<?php

/**
 * a DI class for controller methods, uses reflection
 * to match classname/argname to callbacks used to
 * provide the arguments
 * @author MichaelFairhurst
 */
class Injector {

	private $bindings = array();

	public function configure() {
		$load_new_scope = function(Injector $injector) {
			require CONFIG_DIR . 'injector.php';
		};

		$load_new_scope($this);
	}

	/**
	 * Your rule must begin with a classname. After that classname
	 * you can provide a single space and then an argument. The argument
	 * can either be plain, such as $my_arg_name, or it can be a forward
	 * slash delimited regexp.
	 *
	 * @example
	 *   ->bind('MyArg $my_arg_name', function() { return new MyArg; });
	 *   ->bind('MyArg $/my_?arg_?name/i', array($myobj, 'getMyArg'));
	 *
	 * The factory should be callable, probably an anonymous function
	 * or a class method. It will be invoked for each injection of the
	 * instance.
	 *
	 * @param string $rule
	 * @param callable $factory
	 */
	function bind($rule, callable $factory) {
		@list($classname, $pnameregex) = explode(' ', $rule, 2);

		// @todo we should really throw an exception for misspelled classnames
		// but that would require having every bound class loaded, even ones
		// never used. Maybe make it enable-able?

		$binding = new StdClass;
		$binding->classname = strtolower($classname);
		$binding->pnameregex = preg_match('/^\$[a-z_]*$/i', $pnameregex) ? '/^' . substr($pnameregex, 1) . '$/' : substr($pnameregex, 1);
		$binding->factory = $factory;
		$this->bindings[] = $binding;
	}

	/**
	 * Give me a ReflectionParameter and you'll get either the injected
	 * factory or a function that returns a new instance of the args type
	 *
	 * @param ReflectionParameter $parameter
	 * @return closure
	 *
	 * @throws InjectorMisconfiguratioException if the class doesn't exist
	 * @throws InjectorMisconfiguratioException if the class isnt bound and require constructor args
	 */
	public function getFactoryForParameter(ReflectionParameter $parameter) {
		$class = $this->getReflectionClassFromParameter($parameter);
		$classname = $class->getName();
		$argname = $parameter->getName();

		$injection = $this->getInjectedFactoryForClassnameArgname($classname, $argname);

		return $injection ? $injection : $this->getNoInjectionFactoryForClass($class);
	}

	/**
	 * @parameter ReflectionParameter $parameter
	 * @return ReflectionClass|null
	 * @throws InjectorMisconfiguratioException if the class doesn't exist
	 * @throws InjectorMisconfiguratioException if the class isnt bound and require constructor args
	 */
	private function getReflectionClassFromParameter(ReflectionParameter $parameter) {
		try {
			$class = $parameter->getClass();
		} catch(ReflectionException $e) {
			throw new InjectorMisconfigurationException('Type-hinted classname does not exist', $e);
		}

		return $class;
	}

	/**
	 * Give me a ReflectionParameter and you will get the result of invoking its
	 * bound factory, or a just a new instance if new factory is bound
	 * @param ReflectionParameter
	 * @return mixed
	 */
	public function getInstanceForParameter(ReflectionParameter $parameter) {
		$factory = $this->getFactoryForParameter($parameter);

		return $factory();
	}

	/**
	 * Give me a classname argname and you will get the result of invoking the
	 * first matching binding. If no bindings match, just get a new instance
	 * @param string $classname
	 * @param string $argname = null
	 * @return mixed
 	 */
	public function getInstanceForBinding($classname, $argname = null) {
		$injection = $this->getInjectedFactoryForClassnameArgname($classname, $argname);

		if($injection) return $injection();
	
		$otherwise = $this->getNoInjectionFactoryForClass(new ReflectionClass($classname));
		return $otherwise();
	}

	/**
	 * provide an object and a method name, all classes will be dependency injected
	 * and scalar arguments must be provided in $args
	 * @param object $object
	 * @param string $methodname
	 * @param array $args
	 * @return mixed
	 */
	public function invokeWithInjections($object, $methodname, array $args) {
		$method = new ReflectionMethod($object, $methodname);
		$invokeargs = array();

		foreach($method->getParameters() as $parameter) {
			$class = $this->getReflectionClassFromParameter($parameter);
			if($class) {
				$invokeargs[] = $this->getInstanceForParameter($parameter);
			} else if($args) {
				$invokeargs[] = array_shift($args);
			} else if($parameter->isOptional()) {
				$invokeargs[] = $parameter->getDefaultValue();
			} else {
				throw new InvalidArgumentException("Not enough arguments provider to method $method");
			}
		}

		return $method->invokeArgs($object, $invokeargs);
	}

	/**
	 * This ensures that its reasonable to construct a class without a factory
	 * @param ReflectionClass $class
	 * @return closure
	 */ 
	private function getNoInjectionFactoryForClass($class) {
		$constructor = $class->getConstructor();
		
		if($constructor && count(
							array_filter(
								$constructor->getParameters(),
								function(ReflectionParameter $rp) {
									return !$rp->isOptional();
								}
							)
						)
		) {
			throw new InjectorMisconfigurationException("Class {$class->getName()} with non-optional constructor parameters requires a binding to be injected");
		}

		return function() use($class) {
			return $class->newInstanceArgs(array()); // newInstance() generates warnings in our tests
		};
	}

	/**
	 * Get the first factory bound to the classname argname combination
	 * @return Closure|null
	 */
	private function getInjectedFactoryForClassnameArgname($classname, $argname) {
		foreach($this->bindings as $binding) {
			if($binding->classname == strtolower($classname)) {
				if($binding->pnameregex && !preg_match($binding->pnameregex, $argname))
					continue;

				return $binding->factory;
			}
		}
	}
}
