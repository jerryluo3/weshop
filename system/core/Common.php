<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		CodeIgniter
 * @subpackage	CodeIgniter
 * @category	Common Functions
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/
 */

// ------------------------------------------------------------------------

if ( ! function_exists('is_php'))
{
	/**
	 * Determines if the current version of PHP is equal to or greater than the supplied value
	 *
	 * @param	string
	 * @return	bool	TRUE if the current version is $version or higher
	 */
	function is_php($version)
	{
		static $_is_php;
		$version = (string) $version;

		if ( ! isset($_is_php[$version]))
		{
			$_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
		}

		return $_is_php[$version];
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_really_writable'))
{
	/**
	 * Tests for file writability
	 *
	 * is_writable() returns TRUE on Windows servers when you really can't write to
	 * the file, based on the read-only attribute. is_writable() is also unreliable
	 * on Unix servers if safe_mode is on.
	 *
	 * @link	https://bugs.php.net/bug.php?id=54709
	 * @param	string
	 * @return	bool
	 */
	function is_really_writable($file)
	{
		// If we're on a Unix server with safe_mode off we call is_writable
		if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') OR ! ini_get('safe_mode')))
		{
			return is_writable($file);
		}

		/* For Windows servers and safe_mode "on" installations we'll actually
		 * write a file then read it. Bah...
		 */
		if (is_dir($file))
		{
			$file = rtrim($file, '/').'/'.md5(mt_rand());
			if (($fp = @fopen($file, 'ab')) === FALSE)
			{
				return FALSE;
			}

			fclose($fp);
			@chmod($file, 0777);
			@unlink($file);
			return TRUE;
		}
		elseif ( ! is_file($file) OR ($fp = @fopen($file, 'ab')) === FALSE)
		{
			return FALSE;
		}

		fclose($fp);
		return TRUE;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('load_class'))
{
	/**
	 * Class registry
	 *
	 * This function acts as a singleton. If the requested class does not
	 * exist it is instantiated and set to a static variable. If it has
	 * previously been instantiated the variable is returned.
	 *
	 * @param	string	the class name being requested
	 * @param	string	the directory where the class should be found
	 * @param	string	an optional argument to pass to the class constructor
	 * @return	object
	 */
	function &load_class($class, $directory = 'libraries', $param = NULL)
	{
		static $_classes = array();

		// Does the class exist? If so, we're done...
		if (isset($_classes[$class]))
		{
			return $_classes[$class];
		}

		$name = FALSE;

		// Look for the class first in the local application/libraries folder
		// then in the native system/libraries folder
		foreach (array(APPPATH, BASEPATH) as $path)
		{
			if (file_exists($path.$directory.'/'.$class.'.php'))
			{
				$name = 'CI_'.$class;

				if (class_exists($name, FALSE) === FALSE)
				{
					require_once($path.$directory.'/'.$class.'.php');
				}

				break;
			}
		}

		// Is the request a class extension? If so we load it too
		if (file_exists(APPPATH.$directory.'/'.config_item('subclass_prefix').$class.'.php'))
		{
			$name = config_item('subclass_prefix').$class;

			if (class_exists($name, FALSE) === FALSE)
			{
				require_once(APPPATH.$directory.'/'.$name.'.php');
			}
		}

		// Did we find the class?
		if ($name === FALSE)
		{
			// Note: We use exit() rather than show_error() in order to avoid a
			// self-referencing loop with the Exceptions class
			set_status_header(503);
			echo 'Unable to locate the specified class: '.$class.'.php';
			exit(5); // EXIT_UNK_CLASS
		}

		// Keep track of what we just loaded
		is_loaded($class);

		$_classes[$class] = isset($param)
			? new $name($param)
			: new $name();
		return $_classes[$class];
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_loaded'))
{
	/**
	 * Keeps track of which libraries have been loaded. This function is
	 * called by the load_class() function above
	 *
	 * @param	string
	 * @return	array
	 */
	function &is_loaded($class = '')
	{
		static $_is_loaded = array();

		if ($class !== '')
		{
			$_is_loaded[strtolower($class)] = $class;
		}

		return $_is_loaded;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_config'))
{
	/**
	 * Loads the main config.php file
	 *
	 * This function lets us grab the config file even if the Config class
	 * hasn't been instantiated yet
	 *
	 * @param	array
	 * @return	array
	 */
	function &get_config(Array $replace = array())
	{
		static $config;

		if (empty($config))
		{
			$file_path = APPPATH.'config/config.php';
			$found = FALSE;
			if (file_exists($file_path))
			{
				$found = TRUE;
				require($file_path);
			}

			// Is the config file in the environment folder?
			if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/config.php'))
			{
				require($file_path);
			}
			elseif ( ! $found)
			{
				set_status_header(503);
				echo 'The configuration file does not exist.';
				exit(3); // EXIT_CONFIG
			}

			// Does the $config array exist in the file?
			if ( ! isset($config) OR ! is_array($config))
			{
				set_status_header(503);
				echo 'Your config file does not appear to be formatted correctly.';
				exit(3); // EXIT_CONFIG
			}
		}

		// Are any values being dynamically added or replaced?
		foreach ($replace as $key => $val)
		{
			$config[$key] = $val;
		}

		return $config;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('config_item'))
{
	/**
	 * Returns the specified config item
	 *
	 * @param	string
	 * @return	mixed
	 */
	function config_item($item)
	{
		static $_config;

		if (empty($_config))
		{
			// references cannot be directly assigned to static variables, so we use an array
			$_config[0] =& get_config();
		}

		return isset($_config[0][$item]) ? $_config[0][$item] : NULL;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_mimes'))
{
	/**
	 * Returns the MIME types array from config/mimes.php
	 *
	 * @return	array
	 */
	function &get_mimes()
	{
		static $_mimes;

		if (empty($_mimes))
		{
			if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/mimes.php'))
			{
				$_mimes = include(APPPATH.'config/'.ENVIRONMENT.'/mimes.php');
			}
			elseif (file_exists(APPPATH.'config/mimes.php'))
			{
				$_mimes = include(APPPATH.'config/mimes.php');
			}
			else
			{
				$_mimes = array();
			}
		}

		return $_mimes;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_https'))
{
	/**
	 * Is HTTPS?
	 *
	 * Determines if the application is accessed via an encrypted
	 * (HTTPS) connection.
	 *
	 * @return	bool
	 */
	function is_https()
	{
		if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
		{
			return TRUE;
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		{
			return TRUE;
		}
		elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
		{
			return TRUE;
		}

		return FALSE;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('is_cli'))
{

	/**
	 * Is CLI?
	 *
	 * Test to see if a request was made from the command line.
	 *
	 * @return 	bool
	 */
	function is_cli()
	{
		return (PHP_SAPI === 'cli' OR defined('STDIN'));
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('show_error'))
{
	/**
	 * Error Handler
	 *
	 * This function lets us invoke the exception class and
	 * display errors using the standard error template located
	 * in application/views/errors/error_general.php
	 * This function will send the error page directly to the
	 * browser and exit.
	 *
	 * @param	string
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
	{
		$status_code = abs($status_code);
		if ($status_code < 100)
		{
			$exit_status = $status_code + 9; // 9 is EXIT__AUTO_MIN
			if ($exit_status > 125) // 125 is EXIT__AUTO_MAX
			{
				$exit_status = 1; // EXIT_ERROR
			}

			$status_code = 500;
		}
		else
		{
			$exit_status = 1; // EXIT_ERROR
		}

		$_error =& load_class('Exceptions', 'core');
		echo $_error->show_error($heading, $message, 'error_general', $status_code);
		exit($exit_status);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('show_404'))
{
	/**
	 * 404 Page Handler
	 *
	 * This function is similar to the show_error() function above
	 * However, instead of the standard error template it displays
	 * 404 errors.
	 *
	 * @param	string
	 * @param	bool
	 * @return	void
	 */
	function show_404($page = '', $log_error = TRUE)
	{
		$_error =& load_class('Exceptions', 'core');
		$_error->show_404($page, $log_error);
		exit(4); // EXIT_UNKNOWN_FILE
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('log_message'))
{
	/**
	 * Error Logging Interface
	 *
	 * We use this as a simple mechanism to access the logging
	 * class and send messages to be logged.
	 *
	 * @param	string	the error level: 'error', 'debug' or 'info'
	 * @param	string	the error message
	 * @return	void
	 */
	function log_message($level, $message)
	{
		static $_log;

		if ($_log === NULL)
		{
			// references cannot be directly assigned to static variables, so we use an array
			$_log[0] =& load_class('Log', 'core');
		}

		$_log[0]->write_log($level, $message);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('set_status_header'))
{
	/**
	 * Set HTTP Status Header
	 *
	 * @param	int	the status code
	 * @param	string
	 * @return	void
	 */
	function set_status_header($code = 200, $text = '')
	{
		if (is_cli())
		{
			return;
		}

		if (empty($code) OR ! is_numeric($code))
		{
			show_error('Status codes must be numeric', 500);
		}

		if (empty($text))
		{
			is_int($code) OR $code = (int) $code;
			$stati = array(
				100	=> 'Continue',
				101	=> 'Switching Protocols',

				200	=> 'OK',
				201	=> 'Created',
				202	=> 'Accepted',
				203	=> 'Non-Authoritative Information',
				204	=> 'No Content',
				205	=> 'Reset Content',
				206	=> 'Partial Content',

				300	=> 'Multiple Choices',
				301	=> 'Moved Permanently',
				302	=> 'Found',
				303	=> 'See Other',
				304	=> 'Not Modified',
				305	=> 'Use Proxy',
				307	=> 'Temporary Redirect',

				400	=> 'Bad Request',
				401	=> 'Unauthorized',
				402	=> 'Payment Required',
				403	=> 'Forbidden',
				404	=> 'Not Found',
				405	=> 'Method Not Allowed',
				406	=> 'Not Acceptable',
				407	=> 'Proxy Authentication Required',
				408	=> 'Request Timeout',
				409	=> 'Conflict',
				410	=> 'Gone',
				411	=> 'Length Required',
				412	=> 'Precondition Failed',
				413	=> 'Request Entity Too Large',
				414	=> 'Request-URI Too Long',
				415	=> 'Unsupported Media Type',
				416	=> 'Requested Range Not Satisfiable',
				417	=> 'Expectation Failed',
				422	=> 'Unprocessable Entity',

				500	=> 'Internal Server Error',
				501	=> 'Not Implemented',
				502	=> 'Bad Gateway',
				503	=> 'Service Unavailable',
				504	=> 'Gateway Timeout',
				505	=> 'HTTP Version Not Supported'
			);

			if (isset($stati[$code]))
			{
				$text = $stati[$code];
			}
			else
			{
				show_error('No status text available. Please check your status code number or supply your own message text.', 500);
			}
		}

		if (strpos(PHP_SAPI, 'cgi') === 0)
		{
			header('Status: '.$code.' '.$text, TRUE);
		}
		else
		{
			$server_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
			header($server_protocol.' '.$code.' '.$text, TRUE, $code);
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_error_handler'))
{
	/**
	 * Error Handler
	 *
	 * This is the custom error handler that is declared at the (relative)
	 * top of CodeIgniter.php. The main reason we use this is to permit
	 * PHP errors to be logged in our own log files since the user may
	 * not have access to server logs. Since this function effectively
	 * intercepts PHP errors, however, we also need to display errors
	 * based on the current error_reporting level.
	 * We do that with the use of a PHP error template.
	 *
	 * @param	int	$severity
	 * @param	string	$message
	 * @param	string	$filepath
	 * @param	int	$line
	 * @return	void
	 */
	function _error_handler($severity, $message, $filepath, $line)
	{
		$is_error = (((E_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $severity) === $severity);

		// When an error occurred, set the status header to '500 Internal Server Error'
		// to indicate to the client something went wrong.
		// This can't be done within the $_error->show_php_error method because
		// it is only called when the display_errors flag is set (which isn't usually
		// the case in a production environment) or when errors are ignored because
		// they are above the error_reporting threshold.
		if ($is_error)
		{
			set_status_header(500);
		}

		// Should we ignore the error? We'll get the current error_reporting
		// level and add its bits with the severity bits to find out.
		if (($severity & error_reporting()) !== $severity)
		{
			return;
		}

		$_error =& load_class('Exceptions', 'core');
		$_error->log_exception($severity, $message, $filepath, $line);

		// Should we display the error?
		if (str_ireplace(array('off', 'none', 'no', 'false', 'null'), '', ini_get('display_errors')))
		{
			$_error->show_php_error($severity, $message, $filepath, $line);
		}

		// If the error is fatal, the execution of the script should be stopped because
		// errors can't be recovered from. Halting the script conforms with PHP's
		// default error handling. See http://www.php.net/manual/en/errorfunc.constants.php
		if ($is_error)
		{
			exit(1); // EXIT_ERROR
		}
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_exception_handler'))
{
	/**
	 * Exception Handler
	 *
	 * Sends uncaught exceptions to the logger and displays them
	 * only if display_errors is On so that they don't show up in
	 * production environments.
	 *
	 * @param	Exception	$exception
	 * @return	void
	 */
	function _exception_handler($exception)
	{
		$_error =& load_class('Exceptions', 'core');
		$_error->log_exception('error', 'Exception: '.$exception->getMessage(), $exception->getFile(), $exception->getLine());

		// Should we display the error?
		if (str_ireplace(array('off', 'none', 'no', 'false', 'null'), '', ini_get('display_errors')))
		{
			$_error->show_exception($exception);
		}

		exit(1); // EXIT_ERROR
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_shutdown_handler'))
{
	/**
	 * Shutdown Handler
	 *
	 * This is the shutdown handler that is declared at the top
	 * of CodeIgniter.php. The main reason we use this is to simulate
	 * a complete custom exception handler.
	 *
	 * E_STRICT is purposively neglected because such events may have
	 * been caught. Duplication or none? None is preferred for now.
	 *
	 * @link	http://insomanic.me.uk/post/229851073/php-trick-catching-fatal-errors-e-error-with-a
	 * @return	void
	 */
	function _shutdown_handler()
	{
		$last_error = error_get_last();
		if (isset($last_error) &&
			($last_error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING)))
		{
			_error_handler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('remove_invisible_characters'))
{
	/**
	 * Remove Invisible Characters
	 *
	 * This prevents sandwiching null characters
	 * between ascii characters, like Java\0script.
	 *
	 * @param	string
	 * @param	bool
	 * @return	string
	 */
	function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();

		// every control character except newline (dec 10),
		// carriage return (dec 13) and horizontal tab (dec 09)
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('html_escape'))
{
	/**
	 * Returns HTML escaped variable.
	 *
	 * @param	mixed	$var		The input string or array of strings to be escaped.
	 * @param	bool	$double_encode	$double_encode set to FALSE prevents escaping twice.
	 * @return	mixed			The escaped string or array of strings as a result.
	 */
	function html_escape($var, $double_encode = TRUE)
	{
		if (empty($var))
		{
			return $var;
		}

		if (is_array($var))
		{
			foreach (array_keys($var) as $key)
			{
				$var[$key] = html_escape($var[$key], $double_encode);
			}

			return $var;
		}

		return htmlspecialchars($var, ENT_QUOTES, config_item('charset'), $double_encode);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_stringify_attributes'))
{
	/**
	 * Stringify attributes for use in HTML tags.
	 *
	 * Helper function used to convert a string, array, or object
	 * of attributes to a string.
	 *
	 * @param	mixed	string, array, object
	 * @param	bool
	 * @return	string
	 */
	function _stringify_attributes($attributes, $js = FALSE)
	{
		$atts = NULL;

		if (empty($attributes))
		{
			return $atts;
		}

		if (is_string($attributes))
		{
			return ' '.$attributes;
		}

		$attributes = (array) $attributes;

		foreach ($attributes as $key => $val)
		{
			$atts .= ($js) ? $key.'='.$val.',' : ' '.$key.'="'.$val.'"';
		}

		return rtrim($atts, ',');
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('function_usable'))
{
	/**
	 * Function usable
	 *
	 * Executes a function_exists() check, and if the Suhosin PHP
	 * extension is loaded - checks whether the function that is
	 * checked might be disabled in there as well.
	 *
	 * This is useful as function_exists() will return FALSE for
	 * functions disabled via the *disable_functions* php.ini
	 * setting, but not for *suhosin.executor.func.blacklist* and
	 * *suhosin.executor.disable_eval*. These settings will just
	 * terminate script execution if a disabled function is executed.
	 *
	 * The above described behavior turned out to be a bug in Suhosin,
	 * but even though a fix was commited for 0.9.34 on 2012-02-12,
	 * that version is yet to be released. This function will therefore
	 * be just temporary, but would probably be kept for a few years.
	 *
	 * @link	http://www.hardened-php.net/suhosin/
	 * @param	string	$function_name	Function to check for
	 * @return	bool	TRUE if the function exists and is safe to call,
	 *			FALSE otherwise.
	 */
	function function_usable($function_name)
	{
		static $_suhosin_func_blacklist;

		if (function_exists($function_name))
		{
			if ( ! isset($_suhosin_func_blacklist))
			{
				$_suhosin_func_blacklist = extension_loaded('suhosin')
					? explode(',', trim(ini_get('suhosin.executor.func.blacklist')))
					: array();
			}

			return ! in_array($function_name, $_suhosin_func_blacklist, TRUE);
		}

		return FALSE;
	}
}


/**
 * 下面是自定义函数
 */
/**
 * 格式化打印函数
 * @param  [type] $arr [数组]
 * @return [type]      [description]
 */
function p($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}
// admin/category/index
/**
 * 成功提示函数
 * @param  [type] $url [跳转地址]
 * @param  [type] $msg [提示信息]
 * @return [type]      [description]
 */
function success($url, $msg){
	header('Content-Type:text/html;charset=utf-8');
	$url = site_url($url);
	echo "<script type='text/javascript'>alert('$msg');location.href='$url'</script>";
	die;
}

/**
 * 错误提示函数
 * @param  [type] $msg [提示信息]
 * @return [type]      [description]
 */
function error($msg){
	header('Content-Type:text/html;charset=utf-8');
	echo "<script type='text/javascript'>alert('$msg');window.history.back();</script>";
	die;
}


/**
 * 打印常量
 */
function print_const(){
	$const = get_defined_constants(TRUE);
	p($const['user']);	
}

/**
*获取权限下的栏目ID
*/
function get_purview_cids(){
	$cids_arr = $_SESSION['user_purview'];
	if(empty($cids_arr)) return array();
	foreach($cids_arr as $k=>$ca){
		if(substr($ca,0,7) != "content"){
			unset($cids_arr[$k]);	
		}else{
			$cids_arr[$k] = substr($ca,10);
		}
	}
	$cids = array_unique($cids_arr);
	//$cids = implode(",",$cids_arr);	
	return $cids;
}


/*
*获取省份中文名称
*/
function get_province_name($province){
	if(empty($province)) return '';
	switch($province){
		case 1:$str = '北京';break;
		case 2:$str = '天津';break;
		case 3:$str = '河北';break;
		case 4:$str = '山西';break;
		case 5:$str = '内蒙古';break;
		case 6:$str = '辽宁';break;
		case 7:$str = '吉林';break;
		case 8:$str = '黑龙江';break;
		case 9:$str = '上海';break;
		case 10:$str = '江苏';break;
		case 11:$str = '浙江';break;
		case 12:$str = '安徽';break;
		case 13:$str = '福建';break;
		case 14:$str = '江西';break;
		case 15:$str = '山东';break;
		case 16:$str = '河南';break;
		case 17:$str = '湖北';break;
		case 18:$str = '湖南';break;
		case 19:$str = '广东';break;
		case 20:$str = '广西';break;
		case 21:$str = '海南';break;
		case 22:$str = '陕西';break;
		case 23:$str = '甘肃';break;
		case 24:$str = '四川';break;
		case 25:$str = '贵州';break;
		case 26:$str = '云南';break;
		case 27:$str = '西藏';break;
		case 28:$str = '青海';break;
		case 29:$str = '宁夏';break;
		case 30:$str = '新疆';break;
		case 31:$str = '香港';break;
		case 32:$str = '澳门';break;
		case 33:$str = '台湾';break;
		case 34:$str = '国外';break;
	}	
	return $str;
}



function getstate_words($state = 0){
	
	switch($state){
		case 0:$str = '待审';break;
		case -1:$str = '退回修改';break;
		case -2:$str = '审核不通过';break;
		case -3:$str = '清退封存';break;
		case 1:$str = '通过';break;
		case 2:$str = '二审';break;
		case 3:$str = '三审';break;
		case 4:$str = '四审';break;
		case 5:$str = '五审';break;
		case 6:$str = '六审';break;
		case 7:$str = '七审';break;
		case 8:$str = '八审';break;
		default:$str = '待审';break;
	}
	return $str;	
}

function get_check_grade_name($c){
	switch($c){
		case 1:$str='一级审核';break;
		case 2:$str='二级审核';break;
		case 3:$str='三级审核';break;
		case 4:$str='四级审核';break;
		case 5:$str='五级审核';break;
		default:$str='一级审核';break;	
	}
	return $str;	
}

function getmessage_type($cid){
	
	switch($cid){
		case 0:$str = '系统消息';break;
		case 1:$str = '内容审核';break;
		case 2:$str = '企业审核';break;
		case 3:$str = '推送审核';break;
		case 4:$str = '企业更新';break;
		default:$str = '系统消息';break;
	}
	return $str;	
}

function get_checkpur_cids($type='content',$pur,$usergroup){
	$subname = $type.'check';
	if($usergroup == 1 ){//系统管理员
		return '';
	}
	$arr = array();
	$grade = array();
	if(!empty($pur)){
		
		foreach($pur as $k=>$v){
			if(substr($v,0,strlen($subname)) == $subname){
				$s = substr($v,strlen($subname));
				if($type == 'content'){//内容审核
					$s_a = explode('_',$s);
					$grade[] = $s_a[0]-1;//审核等级
					$arr[] = $s_a[1];	
				}else{
					$grade[] = $s-1;	
				}
			}
		}
		
		$data['grade'] = $grade;
		$data['arr'] = $arr;
		return json_encode($data);
	}else{//没有设置权限
		return -1;	
	}
}


//获取导航条子菜单
function getSubMenuList($fclass,$k,$li=0,$n){
	if(!is_array($fclass) || empty($fclass) || empty($k)){
		return '';	
	}else{
		$son_arr = !empty($fclass[$k]['cat_son']) ? explode(',',$fclass[$k]['cat_son']) : array();
		$str = '';
		foreach($son_arr as $a=>$m1){
			if(isset($fclass[$m1])){
				if($li > 0){
					if($n > 0){
						if($a < $n){
							$str .= '<a href="list-'.$m1.'.html" title="'.$fclass[$m1]['cat_name'].'">'.$fclass[$m1]['cat_name'].'</a>';	
						}	
					}else{
						$str .= '<a href="list-'.$m1.'.html" title="'.$fclass[$m1]['cat_name'].'">'.$fclass[$m1]['cat_name'].'</a>';
					}
					
				}else{
					$str .= '<li><a href="list-'.$m1.'.html" title="'.$fclass[$m1]['cat_name'].'">'.$fclass[$m1]['cat_name'].'</a></li>';	
				}
				
			}
		}
		return $str;
	}
}

//获取一级菜单对的文件名
function getMainMenuName($n){
	switch($n){
		case 1:$str = 'zaohuijingying';break;
		case 2:$str = 'shouxianpeixun';break;
		case 3:$str = 'yingxiaojiqiao';break;
		case 4:$str = 'jingyingguanli';break;
		case 5:$str = 'zengyuanzhuanti';break;
		case 6:$str = 'shuominghui';break;
		case 7:$str = 'xianzhongfenxi';break;
		case 8:$str = 'baoxianruanjian';break;
		case 9:$str = 'baoxianshipin';break;
		default:$str = 'zaohuijingying';break;	
	}	
	return $str;
}


//获取文件类型
function getFileType($t){
	switch($t){
		case 2:$str = 'PPT';break;
		case 3:$str = 'WORD';break;
		case 4:$str = 'EXCEL';break;
		case 5:$str = '其它';break;
		default:$str = 'PPT';break;	
	}	
	return $str;	
}

//获取会员类型
function get_member_type($str){
	switch($str){
		case "0":
			$temp_str = "普通会员";
			break;
		case "1":
			$temp_str = "高级会员";
			break;
		default:
			$temp_str = "普通会员";
			break;
	}
	return $temp_str;
}
//根据支付金额计算高级会员的有效天数
function get_vip_date($ptype){
	switch($ptype){
		case "1":
			$temp_str = "365";
			break;
		case "2":
			$temp_str = "730";
			break;
		case "3":
			$temp_str = "1095";
			break;
		case "4":
			$temp_str = "30";
			break;
		default:
			$temp_str = "0";
			break;
	}	
	return $temp_str;
}

//隐藏用户名
function hidename($user_name){
    $strlen     = mb_strlen($user_name, 'utf-8');
    $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
    $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
    return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
}

//替换HTML字符，用于htmlspecialchars之后出现的&ldquo;&rdquo;&lsquo;&rsquo;&nbsp;等
function get_html_replace($str){
	preg_match_all("/&(.*?);/",$str,$str_array);
	$str_array = array_unique($str_array[0]);
	foreach($str_array as $key => $value){
		$str = str_replace($value,"",$str);
	}
	
	//print_r($img_array);
	$str = str_replace("nbsp;","",$str);
	$str = str_replace("<p>","",$str);
	$str = str_replace("</p>","",$str);
	$str = str_replace("  ","",$str);
	return $str;
}

function number2words($k){
	$arr = array(1=>'one',2=>'two',3=>'three',4=>'four',5=>'five',6=>'six',7=>'seven',8=>'eight',9=>'nine',10=>'ten');
	return $arr[$k];
}

//截取没有样式内容
function getSubstr($str,$len){
	return utf_substr(strip_tags(htmlspecialchars_decode($str)),$len);	
}


//订单状态
function getOrderStatus($s){
	//订单状态（0待付款1待发货（已支付）2已发货（待收货）3已确认（待评价）4已完成6退货7换货8取消订单9定制待提货）
	switch($s){
		case 0:$str = '待付款';break;
		case 1:$str = '待发货';break;
		case 2:$str = '已发货';break;
		case 3:$str = '已完成';break;
		case 4:$str = '售后';break;
	}	
	return $str;
}


//用户表密码加密
function userPassHash($hash_method,$str,$hash_key){
	return hash_hmac($hash_method, $str, $hash_key);
}

//短信发送返回状态
function getSnsResultStatus($v){
	switch($v){
		case -1:$str = '提交接口错误';break;
		case -3:$str = '用户名或密码错误';break;
		case -4:$str = '短信内容和备案的模板不一样';break;
		case -5:$str = '签名不正确';break;
		case -7:$str = '余额不足';break;
		case -8:$str = '通道错误';break;
		case -9:$str = '无效号码';break;
		case -10:$str = '签名内容不符合长度';break;
		case -11:$str = '用户有效期过期';break;
		case -12:$str = '黑名单';break;
		case -13:$str = '语音验证码的 Amount 参数必须是整形字符串';break;
		case -14:$str = '语音验证码的内容只能为数字';break;
		case -15:$str = '语音验证码的内容最长为 6 位';break;
		case -16:$str = '余额请求过于频繁，5 秒才能取余额一次';break;
		case -17:$str = '非法 IP';break;
		case -23:$str = '解密失败';break;
		default:$str = '其它错误';break;
	}	
	return $str;
}

