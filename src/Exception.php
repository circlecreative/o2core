<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 8:25 PM
 */

namespace O2System\Core;


class Exception extends \Exception
{
	public    $header      = NULL;
	public    $description = NULL;
	protected $_args       = NULL;

	// ------------------------------------------------------------------------

	public function __construct( $message, $code = 0, \Exception $previous = NULL )
	{
		if ( class_exists( 'O2System', FALSE ) )
		{
			\O2System::$language->load( 'exception' );
			$lang_message      = \O2System::$language->line( $message );
			$this->header      = \O2System::$language->line( 'E_HEADER_' . get_class_name( $this ) );
			$this->description = \O2System::$language->line( 'E_DESCRIPTION_' . get_class_name( $this ) );
		}
		else
		{
			\O2System\Core::$language->load( 'exception' );
			$lang_message      = \O2System\Core::$language->line( $message );
			$this->header      = \O2System::$language->line( 'E_HEADER_' . get_class_name( $this ) );
			$this->description = \O2System::$language->line( 'E_DESCRIPTION_' . get_class_name( $this ) );
		}

		if ( empty( $this->header ) )
		{
			$this->header = get_class( $this );
		}

		$message = empty( $lang_message ) ? $message : $lang_message;

		if ( isset( $this->_args ) )
		{
			$args = array_values( $this->_args );
			array_unshift( $args, $message );

			$message = call_user_func_array( 'sprintf', $args );
		}

		$message = empty( $message ) ? '(null)' : $message;

		parent::__construct( $message, $code );

		if ( ! is_null( $previous ) )
		{
			$this->previous = $previous;
		}

		$this->_setPath();
	}

	// ------------------------------------------------------------------------

	protected function _setPath()
	{
		$class_realpath = ( new \ReflectionClass( get_called_class() ) )->getFileName();

		if ( class_exists( 'O2System', FALSE ) )
		{
			\O2System::Exceptions()->addPath( dirname( $class_realpath ) );
		}
		else
		{
			\O2System\Core::Exceptions()->addPath( dirname( $class_realpath ) );
		}
	}
}