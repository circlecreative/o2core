<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 7:10 PM
 */

namespace O2System\Core\HTTP\Header\Fields;

/**
 * HTTP Header Request Fields
 *
 * HTTP header fields are components of the header section of
 * request and response messages in the Hypertext Transfer Protocol (HTTP).
 * They define the operating parameters of an HTTP transaction.
 *
 * @see https://en.wikipedia.org/wiki/List_of_HTTP_header_fields
 *
 * @package O2System\Core\HTTP\Header\Fields
 * @since 4.0.0
 */
interface Request
{
	/**
	 * Accept
	 *
	 * Content-Types that are acceptable for the response
	 *
	 * @see https://en.wikipedia.org/wiki/Content_negotiation
	 *
	 * @example Accept: text/plain
	 * @status  Permanent
	 *
	 * @var string
	 */
	const ACCEPT = 'Accept';

	// ------------------------------------------------------------------------

	/**
	 * ------------------------------------------------------------------------
	 * Common Non-Standard Request Fields
	 * ------------------------------------------------------------------------
	 */

	/**
	 * X-Requested-With
	 *
	 * Mainly used to identify Ajax requests.
	 * Most JavaScript frameworks send this field with value of XMLHttpRequest
	 *
	 * @see https://en.wikipedia.org/wiki/Ajax_(programming)
	 * @see https://en.wikipedia.org/wiki/JavaScript_framework
	 *
	 * @example X-Requested-With: XMLHttpRequest
	 * @status  Custom
	 *
	 * @var string
	 */
	const X_REQUEST_WITH = 'X-Requested-With';
}