<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 6:48 PM
 */

namespace O2System;

if ( ! defined( 'CORE_PATH' ) )
{
	define( 'CORE_PATH', __DIR__ . DIRECTORY_SEPARATOR );
}

require_once( CORE_PATH . 'Helpers/' . 'Common.php' );
require_once( CORE_PATH . 'Helpers/' . 'Inflector.php' );

class Core
{
	use Core\Interfaces\SingletonInterface;
	use Core\Interfaces\StorageInterface;

	public static $version = '2.0.0';
	public static $config;
	public static $language;
	public static $logger;

	// ------------------------------------------------------------------------

	/**
	 * Core Class Constructor
	 *
	 * @access public
	 */
	public function __reconstruct()
	{
		foreach ( [ 'Loader', 'Config', 'Language', 'Exceptions', 'Logger' ] as $class )
		{
			$class_name  = $class;
			$object_name = strtolower( $class );

			if ( isset( $this->_object_maps[ $object_name ] ) )
			{
				$object_name = $this->_object_maps[ $object_name ];
			}

			if ( class_exists( 'O2System', FALSE ) )
			{
				$class_name = '\O2System\\' . $class;
			}

			if ( class_exists( $class_name ) )
			{
				if ( in_array( $class, [ 'Config', 'Language' ] ) )
				{
					static::${$object_name} = new $class_name();
				}
				else
				{
					$this->{$object_name} = new $class_name();

					if ( in_array( $class, [ 'Loader', 'Exceptions' ] ) )
					{
						$this->{$object_name}->registerHandler();
					}
				}
			}
		}
	}
}