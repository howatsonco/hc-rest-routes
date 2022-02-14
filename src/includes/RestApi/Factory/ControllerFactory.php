<?php

namespace HC\RestRoutes\RestApi\Factory;

use HC\RestRoutes\RestApi\Controllers\RestController;
use HC\RestRoutes\Traits\Singleton;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

/**
 * Singleton that registers and instantiates Controller classes.
 */
final class ControllerFactory {
	use Singleton;

	/**
	 * controllers array.
	 */
	public $controllers = array();

	/**
	 * constructor.
	 */
	public function __construct() {
		do_action("hcrr_controllers_init", $this);
	}

	/**
	 * Registers a controller subclass.
	 *
	 * @param string|RestController $controller Either the name of a `RestController` subclass or an instance of a `RestController` subclass.
	 */
	public function register( $controller ) {
		if ( $controller instanceof RestController ) {
			$this->controllers[ spl_object_hash( $controller ) ] = $controller;
		} else {
			$this->controllers[ $controller ] = new $controller();
		}
	}

	/**
	 * Un-registers a controller subclass.
	 *
	 * @param string|RestController $controller Either the name of a `RestController` subclass or an instance of a `RestController` subclass.
	 */
	public function unregister( $controller ) {
		if ( $controller instanceof RestController ) {
			unset( $this->controllers[ spl_object_hash( $controller ) ] );
		} else {
			unset( $this->controllers[ $controller ] );
		}
	}

	/**
	 * Returns the registered RestController object for the given controller.
	 *
	 * @param string $id Controller ID.
	 * @return RestController|null
	 */
	public function getController( $id ) {
		$key = $this->getControllerKey( $id );
		if ( '' === $key ) {
			return null;
		}

		return $this->controllers[ $key ];
	}

	/**
	 * Returns the registered key for the given controller.
	 *
	 * @param string $id Controller ID.
	 * @return string
	 */
	public function getControllerKey( $id ) {
		foreach ( $this->controllers as $key => $controller ) {
			if ( $controller->id === $id ) {
				return $key;
			}
		}

		return '';
	}
}
