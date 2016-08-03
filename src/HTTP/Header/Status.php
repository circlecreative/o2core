<?php
/**
 * Created by PhpStorm.
 * User: steevenz
 * Date: 03-Aug-16
 * Time: 7:06 PM
 */

namespace O2System\Core\HTTP\Header;

/**
 * Header Status
 *
 * This is a list of Hypertext Transfer Protocol (HTTP) response status codes.
 * It includes codes from IETF internet standards, other IETF RFCs, other specifications,
 * and some additional commonly used codes. The first digit of the status code specifies
 * one of five classes of response; an HTTP client must recognise these five classes at a minimum.
 * The phrases used are the standard wordings, but any human-readable alternative can be provided.
 * Unless otherwise stated, the status code is part of the HTTP/1.1 standard (RFC 7231)
 *
 * The Internet Assigned Numbers Authority (IANA) maintains the official registry of HTTP status codes.
 *
 * Microsoft IIS sometimes uses additional decimal sub-codes to provide more specific information,
 * but not all of those are here.
 *
 * @see https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
 *
 * @package O2System\Core\HTTP\Header
 * @since 4.0.0
 */
interface Status
{
	/**
	 * ------------------------------------------------------------------------
	 *
	 * 1xx Informational
	 *
	 * Request received, continuing process.
	 *
	 * This class of status code indicates a provisional response, consisting only
	 * of the Status-Line and optional headers, and is terminated by an empty line.
	 * Since HTTP/1.0 did not define any 1xx status codes, servers must not send a 1xx response
	 * to an HTTP/1.0 client except under experimental conditions.
	 *
	 * ------------------------------------------------------------------------
	 */

	/**
	 * 100 Continue
	 *
	 * The server has received the request headers and the client should proceed to send
	 * the request body (in the case of a request for which a body needs to be sent; for example, a POST request).
	 * Sending a large request body to a server after a request has been rejected for inappropriate
	 * headers would be inefficient. To have a server check the request's headers, a client must
	 * send Expect: 100-continue as a header in its initial request and receive a 100 Continue status code
	 * in response before sending the body. The response 417 Expectation Failed indicates the request
	 * should not be continued.
	 *
	 * @var int
	 */
	const CONTINUE            = 100;
	const SWITCHING_PROTOCOLS = 101;
	const PROCESSING          = 102; // WebDav; RFC 2518

	/**
	 * ------------------------------------------------------------------------
	 *
	 * 2xx Success
	 *
	 * This class of status codes indicates the action requested by the client
	 * was received, understood, accepted, and processed successfully.
	 *
	 * ------------------------------------------------------------------------
	 */
	const OK                            = 200;
	const CREATED                       = 201;
	const ACCEPTED                      = 202;
	const NON_AUTHORITATIVE_INFORMATION = 203;
	const NO_CONTENT                    = 204;
	const RESET_CONTENT                 = 205;
	const PARTIAL_CONTENT               = 206;

	/**
	 * ------------------------------------------------------------------------
	 *
	 * 3xx Redirection
	 *
	 * @see https://en.wikipedia.org/wiki/URL_redirection
	 *
	 * This class of status code indicates the client must take additional action to complete the request.
	 * Many of these status codes are used in URL redirection.
	 *
	 * A user agent may carry out the additional action with no user interaction only if the method used
	 * in the second request is GET or HEAD. A user agent may automatically redirect a request.
	 * A user agent should detect and intervene to prevent cyclical redirects.
	 *
	 * ------------------------------------------------------------------------
	 */
	const MULTIPLE_CHOICES              = 300;
	const MOVED_PERMANENTLY             = 301;
	const MOVED_TEMPORARILY             = 302;
	const SEE_OTHER                     = 303;
	const NOT_MODIFIED                  = 304;
	const USE_PROXY                     = 305;
	const TEMPORARY_REDIRECT            = 307;

	/**
	 * ------------------------------------------------------------------------
	 *
	 * 4xx Client Error
	 *
	 * The server failed to fulfill an apparently valid request.
	 *
	 * The 4xx class of status code is intended for situations in which the client seems to have erred.
	 * Except when responding to a HEAD request, the server should include an entity containing
	 * an explanation of the error situation, and whether it is a temporary or permanent condition.
	 * These status codes are applicable to any request method. User agents should display
	 * any included entity to the user.
	 *
	 * ------------------------------------------------------------------------
	 */

	/**
	 * 400 Bad Request
	 *
	 * The server cannot or will not process the request due to an apparent client error
	 * (e.g., malformed request syntax, too large size, invalid request message framing,
	 * or deceptive request routing).
	 */
	const BAD_REQUEST                     = 400;
	const UNAUTHORIZED                    = 401;
	const PAYMENT_REQUIRED                = 402;
	const FORBIDDEN                       = 403;
	const NOT_FOUND                       = 404;
	const METHOD_NOT_ALLOWED              = 405;
	const NOT_ACCEPTABLE                  = 406;
	const PROXY_AUTHENTICATION_REQUIRED   = 407;
	const REQUEST_TIMEOUT                 = 408;
	const CONFLICT                        = 409;
	const GONE                            = 410;
	const LENGTH_REQUIRED                 = 411;
	const PRECONDITION_FAILED             = 412;
	const REQUEST_ENTITY_TOO_LARGE        = 413;
	const REQUEST_URI_TOO_LONG            = 414;
	const UNSUPPORTED_MEDIA_TYPE          = 415;
	const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	const EXPECTATION_FAILED              = 417;
	const IM_A_TEAPOT                     = 418; // RFC 2324
	const AUTHENTIFICATION_TIMEOUT        = 419; // not in RFC 2616
	const UNPROCESSABLE_ENTITY            = 422; // WebDAV; RFC 4918
	const LOCKED                          = 423; // WebDAV; RFC 4918
	const FAILED_DEPENDENCY               = 424; // WebDAV
	const UNORDERED_COLLECTION            = 425; // Internet draft
	const UPGRADE_REQUIRED                = 426; // RFC 2817
	const PRECONDITION_REQUIRED           = 428; // RFC 6585
	const TO_MANY_REQUEST                 = 429; // RFC 6585
	const REQUEST_HEADER_FIELDS_TOO_LARGE = 430; // RFC 6585
	const NO_RESPONSE                     = 444; // Nginx
	const RETRY_WITH                      = 449; // Microsoft
	const BLOCKED_BY_PARENTAL_CONTROLS    = 450;
	const REDIRECT                        = 451;
	const REQUEST_HEADER_TOO_LARGE        = 494;
	const CERT_ERROR                      = 495; // Nginx
	const NO_CERT                         = 496; // Nginx
	const HTTP_TO_HTTPS                   = 497; // Nginx
	const CLIENT_CLOSED_REQUEST           = 499; // Nginx

	/**
	 * ------------------------------------------------------------------------
	 *
	 * 5xx Server Error
	 *
	 * The server failed to fulfill an apparently valid request.
	 *
	 * Response status codes beginning with the digit "5" indicate cases in which the server is aware
	 * that it has encountered an error or is otherwise incapable of performing the request.
	 * Except when responding to a HEAD request, the server should include an entity containing an explanation
	 * of the error situation, and indicate whether it is a temporary or permanent condition.
	 * Likewise, user agents should display any included entity to the user. These response codes are
	 * applicable to any request method.
	 *
	 * ------------------------------------------------------------------------
	 */

	/**
	 * 500 Internal Server Error
	 *
	 * A generic error message, given when an unexpected condition was encountered
	 * and no more specific message is suitable.
	 */
	const INTERNAL_SERVER_ERROR             = 500;
	const NOT_IMPLEMENTED                   = 501;
	const BAD_GATEWAY                       = 502;
	const SERVICE_UNAVAILABLE               = 503;
	const GATEWAY_TIMEOUT                   = 504;
	const HTTP_VERSION_NOT_SUPPORTED        = 505;
	const VARIANT_ALSO_NEGOTIATES           = 506; // RFC 2295
	const INSUFFICIENT_STORAGE              = 507; // WebDAV; RFC 4918
	const LOOP_DETECTED                     = 508; // WebDAV; RFC 5842
	const BANDWIDTH_LIMIT_EXCEEDED          = 509;  // Apache bw/limited extension
	const NOT_EXTENDED                      = 510; // RFC 2774
	const NETWORK_AUTHENTIFICATION_REQUIRED = 511; // RFC 6585
	const NETWORK_READ_TIMEOUT_ERROR        = 598;
	const NETWORK_CONNECT_TIMEOUT_ERROR     = 599;

	/**
	 * ------------------------------------------------------------------------
	 *
	 * 700 O2System Framework Error
	 *
	 * A generic error message, given when an unexpected condition was encountered
	 * and no more specific message is suitable.
	 *
	 * ------------------------------------------------------------------------
	 */
	const METHOD_REQUEST_NOT_FOUND = 701; // O2System Framework
	const METHOD_INVALID_PARAMETER = 702;
	const CONFIGURATION_MISSING    = 703;
	const CONFIGURATION_INVALID    = 704;
	const MISSING_LIBRARY          = 705;

	// ------------------------------------------------------------------------
}