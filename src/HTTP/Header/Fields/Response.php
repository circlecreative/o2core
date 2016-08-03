<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 7:31 PM
 */

namespace O2System\Core\HTTP\Header\Fields;

/**
 * HTTP Header Response Fields
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
interface Response
{
	/**
	 * Access-Control-Allow-Origin
	 *
	 * Specifying which web sites can participate in cross-origin resource sharing
	 *
	 * @see https://en.wikipedia.org/wiki/Cross-origin_resource_sharing
	 *
	 * @example Access-Control-Allow-Origin: *
	 * @status  Provisional
	 */
	const ACCESS_CONTROL_ALLOW_ORIGIN = 'Access-Control-Allow-Origin';

	// ------------------------------------------------------------------------

	/**
	 * ------------------------------------------------------------------------
	 * Common Non-Standard Response Fields
	 * ------------------------------------------------------------------------
	 */

	/**
	 * X-XSS-Protection
	 *
	 * Cross-site scripting (XSS) filter
	 *
	 * @see https://en.wikipedia.org/wiki/Content_Security_Policy
	 *
	 * @example X-XSS-Protection: 1; mode=block
	 * @status  Custom
	 */
	const X_XSS_PROTECTION = 'X-Requested-With';
}