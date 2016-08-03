<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 6:57 PM
 */

namespace O2System\Core\SPL;


/**
 * Class ArrayObject
 *
 * @package O2System\Glob
 */
class ArrayObject extends \ArrayObject
{
	/**
	 * ArrayObject constructor.
	 *
	 * @param array $array
	 * @param int   $option
	 */
	public function __construct( $array = [ ], $option = \ArrayObject::ARRAY_AS_PROPS )
	{
		parent::__construct( $array, $option );
	}

	// ------------------------------------------------------------------------

	public function __isset( $index )
	{
		return $this->offsetExists( $index );
	}

	public function offsetExists( $index )
	{
		if ( parent::offsetExists( $index ) === TRUE )
		{
			$offset = parent::offsetGet( $index );

			if ( is_null( $offset ) )
			{
				return FALSE;
			}
			elseif ( $offset instanceof ArrayObject )
			{
				if ( $offset->isEmpty() )
				{
					$this->offsetUnset( $index );

					return FALSE;
				}
			}
			elseif ( is_array( $offset ) )
			{
				if ( count( $offset ) == 0 )
				{
					return FALSE;
				}
			}
		}

		return parent::offsetExists( $index );
	}

	/**
	 * __set
	 *
	 * ArrayObject __set Magic Method
	 *
	 * @param $index
	 * @param $value
	 */
	public function __set( $index, $value )
	{
		if ( is_array( $value ) )
		{
			parent::offsetSet( $index, $this->___setObject( $value ) );
		}
		else
		{
			parent::offsetSet( $index, $value );
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * ArrayObject __get Magic method
	 *
	 * @param $property
	 *
	 * @return mixed
	 */
	public function __get( $property )
	{
		return $this->offsetExists( $property ) ? $this->offsetGet( $property ) : NULL;
	}

	// ------------------------------------------------------------------------

	/**
	 * __call
	 *
	 * ArrayObject __call Magic method
	 *
	 * @param       $method
	 * @param array $args
	 *
	 * @return array|mixed|object
	 */
	public function __call( $method, $args = [ ] )
	{
		if ( method_exists( $this, $method ) )
		{
			return call_user_func_array( [ $this, $method ], $args );
		}
		elseif ( $this->offsetExists( $method ) )
		{
			if ( empty( $args ) )
			{
				return $this->offsetGet( $method );
			}

			// Let's get the registry values
			$registry = $this->offsetGet( $method );

			// List arguments
			@list( $index, $action ) = $args;

			if ( isset( $registry->{$index} ) )
			{
				$value = $registry->{$index};
			}
			elseif ( isset( $registry[ $index ] ) )
			{
				$value = $registry[ $index ];
			}

			if ( isset( $action ) )
			{
				if ( is_callable( $action ) )
				{
					return $action( $value );
				}
				elseif ( function_exists( $action ) )
				{
					$value = is_object( $value ) ? get_object_vars( $value ) : $value;

					return array_map( $action, $value );
				}
				elseif ( in_array( $action, [ 'array', 'object', 'keys', 'values' ] ) )
				{
					switch ( $action )
					{
						default:
						case 'array':
							$value = ( is_array( $value ) ? $value : (array) $value );
							break;
						case 'object':
							$value = ( is_object( $value ) ? $value : (object) $value );
							break;
						case 'keys':
							$value = is_object( $value ) ? get_object_vars( $value ) : $value;
							$value = array_keys( $value );
							break;
						case 'values':
							$value = is_object( $value ) ? get_object_vars( $value ) : $value;
							$value = array_values( $value );
							break;
					}

					if ( isset( $args[ 2 ] ) )
					{
						if ( is_callable( $args[ 2 ] ) )
						{
							return $args[ 2 ]( $value );
						}
						elseif ( function_exists( $args[ 2 ] ) )
						{
							return array_map( $args[ 2 ], $value );
						}
					}
					else
					{
						return $value;
					}
				}
				elseif ( in_array( $action, [ 'json', 'serialize', 'flatten', 'flatten_keys', 'flatten_values' ] ) )
				{
					switch ( $action )
					{
						default:
						case 'json':
							return json_encode( $value );
							break;
						case 'serialize':
							return serialize( $value );
							break;
						case 'flatten':
							$value = is_object( $value ) ? get_object_vars( $value ) : $value;
							$glue  = isset( $args[ 2 ] ) ? $args[ 2 ] : ', ';

							foreach ( $value as $key => $val )
							{
								if ( is_bool( $val ) )
								{
									$val = $val === TRUE ? 'true' : 'false';
								}

								if ( is_numeric( $key ) )
								{
									$result[] = $val;
								}
								elseif ( is_string( $key ) )
								{
									if ( is_array( $val ) )
									{
										$val = implode( $glue, $val );
									}

									$result[] = $key . ' : ' . $val;
								}
							}

							return implode( $glue, $result );

							break;
						case 'flatten_keys':
							$value = is_object( $value ) ? get_object_vars( $value ) : $value;
							$glue  = isset( $args[ 2 ] ) ? $args[ 2 ] : ', ';

							return implode( $glue, array_keys( $value ) );
							break;
						case 'flatten_values':
							$value = is_object( $value ) ? get_object_vars( $value ) : $value;
							$glue  = isset( $args[ 2 ] ) ? $args[ 2 ] : ', ';

							foreach ( array_values( $value ) as $val )
							{
								if ( is_bool( $val ) )
								{
									$val = $val === TRUE ? 'true' : 'false';
								}

								$result[] = $val;
							}

							return implode( $glue, $result );
							break;
					}
				}
			}
			elseif ( isset( $value ) )
			{
				return $value;
			}
			else
			{
				return $registry;
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * __callStatic
	 *
	 * ArrayObject __callStatic Magic method
	 *
	 * @param $method
	 * @param $args
	 *
	 * @return array|mixed|object
	 */
	public static function __callStatic( $method, $args )
	{
		return self::__call( $method, $args );
	}

	// ------------------------------------------------------------------------

	/**
	 * __isEmpty
	 *
	 * Validate ArrayObject is empty
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return (bool) ( $this->count() == 0 ) ? TRUE : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * __toString
	 *
	 * Returning JSON Encode array copy of storage ArrayObject
	 *
	 * @return string
	 */
	public function __toString()
	{
		return json_encode( $this->getArrayCopy() );
	}

	// ------------------------------------------------------------------------

	/**
	 * __toArray
	 *
	 * Returning array copy of storage ArrayObject
	 *
	 * @return string
	 */
	public function __toArray()
	{
		return $this->getArrayCopy();
	}

	private function ___setObject( array $array )
	{
		$ArrayObject = [ ];

		if ( is_string( key( $array ) ) )
		{
			$ArrayObject = new ArrayObject();
		}

		foreach ( $array as $key => $value )
		{
			if ( is_array( $value ) )
			{
				$ArrayObject[ $key ] = $this->___setObject( $value );
			}
			else
			{
				$ArrayObject[ $key ] = $value;
			}
		}

		return $ArrayObject;
	}
}