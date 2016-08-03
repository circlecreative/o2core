<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 8:06 PM
 */

namespace o2system\vendor\o2system\o2core\src\Exception;

/**
 * Severity
 *
 * @see http://php.net/manual/en/errorfunc.constants.php
 *
 * @package o2system\vendor\o2system\o2core\src\Exception
 */
interface Severity
{
	/**
	 * E_ERROR
	 *
	 * Fatal run-time errors. These indicate errors that can not be recovered from,
	 * such as a memory allocation problem. Execution of the script is halted.
	 *
	 * @var int
	 */
	const E_ERROR             = 1;

	/**
	 * E_WARNING
	 *
	 * Run-time warnings (non-fatal errors). Execution of the script is not halted.
	 *
	 * @var int
	 */
	const E_WARNING           = 2;
	const E_PARSE             = 4;
	const E_NOTICE            = 8;
	const E_CORE_ERROR        = 16;
	const E_CORE_WARNING      = 32;
	const E_COMPILE_ERROR     = 64;
	const E_COMPILE_WARNING   = 128;
	const E_USER_ERROR        = 256;
	const E_USER_WARNING      = 512;
	const E_USER_NOTICE       = 1024;
	const E_STRICT            = 2048;
	const E_RECOVERABLE_ERROR = 4096;
	const E_DEPRECATED        = 8192;
	const E_USER_DEPRECATED   = 16384;
	const E_ALL               = 32767;
}