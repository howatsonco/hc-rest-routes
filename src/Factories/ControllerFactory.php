<?php

namespace HC\RestRoutes\Factories;

use HC\RestRoutes\Controller;
use HC\RestRoutes\Traits\Singleton;

/**
 * Singleton that registers and instantiates Controller classes.
 */
final class ControllerFactory
{
	use Singleton;

	/**
	 * controllers array.
	 */
	public $controllers = array();

	/**
	 * constructor.
	 */
	public function __construct()
	{
		do_action("hcrr_controllers_init", $this);
	}

	/**
	 * Registers a controller subclass.
	 *
	 * @param string|Controller $controller Either the name of a `Controller` subclass or an instance of a `Controller` subclass.
	 */
	public function register($controller)
	{
		if ($controller instanceof Controller) {
			$this->controllers[spl_object_hash($controller)] = $controller;
		} else {
			$this->controllers[$controller] = new $controller();
		}
	}

	/**
	 * Un-registers a controller subclass.
	 *
	 * @param string|Controller $controller Either the name of a `Controller` subclass or an instance of a `Controller` subclass.
	 */
	public function unregister($controller)
	{
		if ($controller instanceof Controller) {
			unset($this->controllers[spl_object_hash($controller)]);
		} else {
			unset($this->controllers[$controller]);
		}
	}

	/**
	 * Returns the registered Controller object for the given controller.
	 *
	 * @param string $id Controller ID.
	 * @return Controller|null
	 */
	public function getController($id)
	{
		if ('' === $id) {
			return null;
		}

		return $this->controllers[$id];
	}
}
