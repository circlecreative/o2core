<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 8:06 PM
 */

namespace O2System\Core\Exception;


class Handler
{
	protected $_ob_level = 0;
	protected $_paths    = [ ];

	protected $_debug_ips   = [ ];
	protected $_environment = 'development';

	// ------------------------------------------------------------------------

	/**
	 * ExceptionHandler constructor.
	 *
	 * @access  public
	 */
	public function __construct()
	{
		$this->_ob_level = ob_get_level();

		$this->addPath( CORE_PATH );

		// Register Exception View and Language Path
		if ( class_exists( 'O2System', FALSE ) )
		{
			$this->addPaths( [ SYSTEMPATH, APPSPATH ] );
			\O2System::$language->addPath( SYSTEMPATH )->load( 'exception' );
		}
		else
		{
			\O2System\Glob::$language->load( 'exception' );
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Debug Ips
	 *
	 * @param      $ips
	 * @param null $user_ip_address
	 */
	public function setDebugIps( $ips, $user_ip_address = NULL )
	{
		if ( is_array( $ips ) )
		{
			$this->_debug_ips = $ips;
		}
		elseif ( is_string( $ips ) )
		{
			$this->_debug_ips = array_map( 'trim', explode( ',', $ips ) );
		}

		if ( class_exists( 'O2System', FALSE ) )
		{
			$this->setEnvironment( ENVIRONMENT );
		}

		if ( isset( $user_ip_address ) )
		{
			$user_ip_address = $user_ip_address === '::1' ? '127.0.0.1' : $user_ip_address;

			if ( in_array( $user_ip_address, $this->_debug_ips ) )
			{
				$this->setEnvironment( 'development' );
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Environment
	 *
	 * @param $environment
	 */
	public function setEnvironment( $environment )
	{
		switch ( $environment )
		{
			default:
			case 'development':
				error_reporting( -1 );
				ini_set( 'display_errors', 1 );
				$this->_environment = 'development';
				break;
			case 'testing':
			case 'production':
				ini_set( 'display_errors', 0 );
				error_reporting( E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED );
				$this->_environment = $environment;
				break;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Environment
	 *
	 * @return string
	 */
	public function getEnvironment()
	{
		return $this->_environment;
	}

	/**
	 * Register Handler
	 *
	 * @access  public
	 */
	public function registerHandler()
	{
		set_exception_handler( [ $this, 'showException' ] );
		register_shutdown_function( [ $this, 'shutdown' ] );
		set_error_handler( [ $this, 'showError' ] );
	}

	// ------------------------------------------------------------------------

	/**
	 * Add Paths
	 *
	 * @param $paths
	 *
	 * @access  public
	 * @return  $this
	 */
	public function addPaths( $paths )
	{
		foreach ( $paths as $path )
		{
			$this->addPath( $path );
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Add Path
	 *
	 * @param $path
	 *
	 * @access  public
	 * @return  $this
	 */
	public function addPath( $path )
	{
		$paths = [
			rtrim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR,
			rtrim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR,
		];

		foreach ( $paths as $path )
		{
			if ( is_dir( $path ) AND ! in_array( $path, $this->_paths ) )
			{
				$this->_paths[] = $path;
				break;
			}
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Status Code
	 *
	 * @param   $code
	 *
	 * @access  public
	 */
	public function setStatusCode( $code )
	{
		HttpHeaderStatus::setHeader( $code );
	}

	// ------------------------------------------------------------------------

	/**
	 * Log Exception
	 *
	 * @param $severity
	 * @param $message
	 * @param $file
	 * @param $line
	 */
	public function logException( $severity, $message, $file, $line )
	{
		$messages[] = '[ ' . ExceptionSeverity::getSeverity( $severity ) . ' ] ';
		$messages[] = $message;
		$messages[] = ' - (' . $line . ')';
		$messages[] = $file;

		if ( class_exists( 'O2System', FALSE ) )
		{
			\O2System::Log()->error( implode( ' ', $messages ) );
		}
		else
		{
			\O2System\Glob::Logger()->error( implode( ' ', $messages ) );
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Show Exception
	 *
	 * @param \Exception $exception
	 */
	public function showException( \Exception $exception )
	{
		$paths = array_reverse( $this->_paths );

		$filename = empty( $exception->view_exception ) ? 'exception.php' : $exception->view_exception;

		foreach ( $paths as $path )
		{
			$path = $path . 'errors' . DIRECTORY_SEPARATOR;
			$path = is_cli() ? $path . 'cli' . DIRECTORY_SEPARATOR : $path . 'html' . DIRECTORY_SEPARATOR;

			if ( is_file( $path . $filename ) )
			{
				$view = $path . $filename;
				break;
			}
		}

		if ( class_exists( 'O2System', FALSE ) )
		{
			if ( \O2System::Exceptions()->getEnvironment() === 'production' )
			{
				if ( \O2System::$request->uri->hasSegment( 'error' ) === FALSE )
				{
					redirect( 'error/500', 'refresh' );
				}
			}

			if ( ! isset( $exception->library ) )
			{
				$exception->library = [
					'name'        => 'O2System Framework',
					'description' => 'Open Source PHP Framework',
					'version'     => SYSTEM_VERSION,
				];
			}

			if ( ! isset( $exception->header ) )
			{
				\O2System::$language->load( 'exception' );
				$common_description     = \O2System::$language->line( 'EXCEPTIONDESCRIPTION_COMMON' );
				$exception->header      = \O2System::$language->line( 'EXCEPTIONHEADER_' . get_class_name( $exception ) );
				$exception->description = \O2System::$language->line( 'EXCEPTIONDESCRIPTION_' . get_class_name( $exception ) );
			}
		}
		else
		{
			if ( ! isset( $exception->library ) )
			{

				$exception->library = [
					'name'        => 'O2System Glob (O2Glob)',
					'description' => 'Open Source Mini PHP Core Framework',
					'version'     => '1.0',
				];
			}

			if ( ! isset( $exception->header ) )
			{
				\O2System\Glob::$language->load( 'exception' );
				$common_description     = \O2System::$language->line( 'EXCEPTIONDESCRIPTION_COMMON' );
				$exception->header      = \O2System::$language->line( 'EXCEPTIONHEADER_' . get_class_name( $exception ) );
				$exception->description = \O2System::$language->line( 'EXCEPTIONDESCRIPTION_' . get_class_name( $exception ) );
			}
		}

		if ( empty( $exception->header ) )
		{
			$exception->header = get_class( $exception );
		}

		if ( empty( $exception->description ) )
		{
			if ( isset( $common_description ) )
			{
				$exception->description = sprintf( $common_description, get_class( $exception ) );
			}
		}

		$backtrace = new Trace( $exception->getTrace() );

		if ( ob_get_level() > $this->_ob_level + 1 )
		{
			ob_end_flush();
		}

		ob_start();
		include( $view );
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}

	// ------------------------------------------------------------------------

	/**
	 * Show Error
	 *
	 * This is the custom error handler that is declared at the (relative)
	 *  The main reason we use this is to permit
	 * PHP errors to be logged in our own log files since the user may
	 * not have access to server logs. Since this function effectively
	 * intercepts PHP errors, however, we also need to display errors
	 * based on the current error_reporting level.
	 * We do that with the use of a PHP error template.
	 *
	 * @param    int    $severity
	 * @param    string $message
	 * @param    string $filepath
	 * @param    int    $line
	 *
	 * @return    void
	 */
	public function showError( $severity, $message, $filepath, $line )
	{
		$is_error = ( ( ( E_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR ) & $severity ) === $severity );

		// When an error occurred, set the status header to '500 Internal Server Error'
		// to indicate to the client something went wrong.
		// This can't be done within the $this->showPhpError method because
		// it is only called when the display_errors flag is set (which isn't usually
		// the case in a production environment) or when errors are ignored because
		// they are above the error_reporting threshold.
		if ( $is_error )
		{
			$this->setStatusCode( HttpHeaderStatus::INTERNAL_SERVER_ERROR );
		}

		// Should we ignore the error? We'll get the current error_reporting
		// level and add its bits with the severity bits to find out.
		if ( ( $severity & error_reporting() ) !== $severity )
		{
			return;
		}

		$this->logException( $severity, $message, $filepath, $line );

		// Should we display the error?
		if ( str_ireplace( [ 'off', 'none', 'no', 'false', 'null' ], '', ini_get( 'display_errors' ) ) )
		{
			$this->showPhpError( $severity, $message, $filepath, $line );
		}

		// If the error is fatal, the execution of the script should be stopped because
		// errors can't be recovered from. Halting the script conforms with PHP's
		// default error handling. See http://www.php.net/manual/en/errorfunc.constants.php
		if ( $is_error )
		{
			exit( 1 ); // EXIT_ERROR
		}

		$this->showPhpError( $severity, $message, $filepath, $line );
	}

	// ------------------------------------------------------------------------

	/**
	 * Show 404
	 *
	 * @param string    $page
	 * @param bool|TRUE $log_error
	 */
	public function show404( $page = '', $log_error = TRUE )
	{
		HttpHeaderStatus::setHeader( HttpHeaderStatus::NOT_FOUND );

		$heading = HttpHeaderStatus::getHeader( HttpHeaderStatus::NOT_FOUND );
		$message = HttpHeaderStatus::getDescription( HttpHeaderStatus::NOT_FOUND );

		print_out( 'test' );

		// By default we log this, but allow a dev to skip it
		if ( $log_error )
		{
			$this->logException( E_ERROR, $heading . ': ' . $page );
		}

		$paths = array_reverse( $this->_paths );

		foreach ( $paths as $path )
		{
			$path = $path . 'errors' . DIRECTORY_SEPARATOR;
			$path = is_cli() ? $path . 'cli' . DIRECTORY_SEPARATOR : $path . 'html' . DIRECTORY_SEPARATOR;

			if ( is_file( $path . 'error.php' ) )
			{
				$view = $path . 'error.php';
			}
		}

		if ( ob_get_level() > $this->_ob_level + 1 )
		{
			ob_end_flush();
		}

		ob_start();
		include( $view );
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;

		exit( 4 ); // EXIT_UNKNOWN_FILE
	}

	// ------------------------------------------------------------------------

	/**
	 * Show PHP Error
	 *
	 * @param $severity
	 * @param $message
	 * @param $filepath
	 * @param $line
	 */
	public function showPhpError( $severity, $message, $filepath, $line )
	{
		$paths = array_reverse( $this->_paths );

		foreach ( $paths as $path )
		{
			$path = $path . 'errors' . DIRECTORY_SEPARATOR;
			$path = is_cli() ? $path . 'cli' . DIRECTORY_SEPARATOR : $path . 'html' . DIRECTORY_SEPARATOR;

			if ( is_file( $path . 'error.php' ) )
			{
				$view = $path . 'error.php';
			}
		}

		$severity = ExceptionSeverity::getSeverity( $severity );

		$backtrace = new Trace( debug_backtrace() );

		if ( ob_get_level() > $this->_ob_level + 1 )
		{
			ob_end_flush();
		}

		ob_start();
		include( $view );
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}

	// ------------------------------------------------------------------------

	/**
	 * Shutdown Handler
	 *
	 * This is the shutdown handler to simulate
	 * a complete custom exception handler.
	 *
	 * E_STRICT is purposivly neglected because such events may have
	 * been caught. Duplication or none? None is preferred for now.
	 *
	 * @link    http://insomanic.me.uk/post/229851073/php-trick-catching-fatal-errors-e-error-with-a
	 * @return    void
	 */
	public function shutdown()
	{
		$last_error = error_get_last();

		if ( isset( $last_error ) &&
			( $last_error[ 'type' ] & ( E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING ) )
		)
		{
			$this->showError( $last_error[ 'type' ], $last_error[ 'message' ], $last_error[ 'file' ], $last_error[ 'line' ] );
		}
	}
}