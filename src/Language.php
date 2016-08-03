<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 8:31 PM
 */

namespace O2System\Core;


/**
 * Class Language
 *
 * @package O2System\Glob
 */
class Language extends SPL\ArrayObject
{
	/**
	 * Active Language
	 *
	 * @type string
	 */
	public $active = 'en';

	/**
	 * Array of Language Paths
	 *
	 * @type array
	 */
	protected $_paths = [ ];

	/**
	 * List of loaded language files
	 *
	 * @access  protected
	 *
	 * @var array
	 */
	protected $_is_loaded = [ ];

	/**
	 * Language Package Info
	 *
	 * @access  public
	 * @type    array
	 */
	protected $_info = [ ];

	// ------------------------------------------------------------------------

	/**
	 * Class Constructor
	 *
	 * @access  public
	 */
	public function __construct()
	{
		$this->addPath( CORE_PATH );

		parent::__construct();
	}

	// ------------------------------------------------------------------------

	/**
	 * Add Paths
	 *
	 * @param $paths
	 *
	 * @return $this
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
	 * @return $this
	 */
	public function addPath( $path )
	{
		$path = str_replace( '/', DIRECTORY_SEPARATOR, $path );

		if ( is_dir( rtrim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'Languages' ) )
		{
			$this->_paths[] = rtrim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'Languages' . DIRECTORY_SEPARATOR;
		}
		elseif ( is_dir( rtrim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'languages' ) )
		{
			$this->_paths[] = rtrim( $path, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Active
	 *
	 * @param   string $code
	 *
	 * @access  public
	 */
	public function setActive( $code )
	{
		$this->active = $code;
	}

	// ------------------------------------------------------------------------

	/**
	 * Load a language file
	 *
	 * @access    public
	 *
	 * @param    mixed     the name of the language file to be loaded. Can be an array
	 * @param    string    the language (english, etc.)
	 * @param    bool      return loaded array of translations
	 * @param    bool      add suffix to $langfile
	 * @param    string    alternative path to look for language file
	 *
	 * @return    mixed
	 */
	public function load( $file, $code = NULL )
	{
		$code = is_null( $code ) ? $this->active : $code;

		if ( is_file( $file ) )
		{
			$this->_loadFile( $file );
		}
		else
		{
			$file = strtolower( $file );

			foreach ( $this->_paths as $package_path )
			{
				$filepaths = [
					$package_path . $code . DIRECTORY_SEPARATOR . $file . '.ini',
					$package_path . $file . '_' . $code . '.ini',
					$package_path . $file . '-' . $code . '.ini',
					$package_path . $file . '.ini',
				];

				foreach ( $filepaths as $filepath )
				{
					$this->_loadFile( $filepath );
				}
			}
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Load File
	 *
	 * @param $filepath
	 */
	protected function _loadFile( $filepath )
	{
		if ( is_file( $filepath ) AND ! in_array( $filepath, $this->_is_loaded ) )
		{
			$lines = parse_ini_file( $filepath, TRUE, INI_SCANNER_RAW );

			if ( ! empty( $lines ) )
			{

				$this->_is_loaded[ pathinfo( $filepath, PATHINFO_FILENAME ) ] = $filepath;

				foreach ( $lines as $key => $line )
				{
					$this->offsetSet( $key, $line );
				}
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch a single line of text from the language array
	 *
	 * @access    public
	 *
	 * @param    string $line the language line
	 *
	 * @return    string
	 */
	public function line( $line = '' )
	{
		$line = strtoupper( $line );

		return ( $line == '' || ! $this->offsetExists( $line ) ) ? NULL : $this->offsetGet( $line );
	}
}