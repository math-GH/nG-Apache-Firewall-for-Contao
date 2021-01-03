<?php
/*
	
	7G Firewall : Log Blocked Requests
	
	Version 1.2 2020/09/07 by Jeff Starr
	
	https://perishablepress.com/7g-firewall/
	https://perishablepress.com/7g-firewall-log-blocked-requests/
	
	-
	
	License: GPL v3 or later https://www.gnu.org/licenses/gpl.txt
	
	Overview: Logs HTTP requests blocked by 7G. Recommended for testing/development only.
	
	Requires: Apache, mod_rewrite, PHP >= 5.4.0, 7G Firewall
	
	Usage:
	
	1. Add 7G Firewall to root .htaccess (or Apache config)
	
	2. Configure 7G Firewall for logging (via tutorial)
	
	2. Add 7G_log.php + 7G_log.txt to root web directory
	
	3. Make 7G_log.txt writable and protect via .htaccess
	
	4. Edit the five lines/options below if necessary
	
	Test well & leave feedback @ https://perishablepress.com/contact/
	
	Notes:
	
	In log entries, matching firewall patterns are indicated via brackets like [this]
    
    MODIFIED for Contao CMS. See https://github.com/mathContao/nG-Apache-Firewall-for-Contao
	
*/

define('SEVENGLOGPATH', dirname(__FILE__) .'/');

define('SEVENGSTATUS', 403); // 403 = Forbidden

define('SEVENGLOGFILE', '../../../../../../var/logs/7g_log_'.date('Y-m-d').'.txt');

define('SEVENGUALENGTH', 0); // 0 = all

define('SEVENGEXIT', 'Goodbye');

date_default_timezone_set('UTC+2');



// Do not edit below this line --~

function perishablePress_7G_init() {
	
	if (perishablePress_7G_check()) {
		
		perishablePress_7G_log();
		
		perishablePress_7G_exit();
		
	}
	
}

function perishablePress_7G_vars() {
	
	$date     = date('Y/m/d H:i:s');
	
	$method   = perishablePress_7G_request_method();
	
	$protocol = perishablePress_7G_server_protocol();
	
	$uri      = perishablePress_7G_request_uri();
	
	$string   = perishablePress_7G_query_string();
	
	$address  = perishablePress_7G_ip_address();
	
	$host     = perishablePress_7G_remote_host();
	
	$referrer = perishablePress_7G_http_referrer();
	
	$agent    = perishablePress_7G_user_agent();
	
	return array($date, $method, $protocol, $uri, $string, $address, $host, $referrer, $agent);
	
}

function perishablePress_7G_check() {
	
	$check = isset($_SERVER['REDIRECT_QUERY_STRING']) ? $_SERVER['REDIRECT_QUERY_STRING'] : '';
	
	return ($check === 'log') ? true : false;
	
}

function perishablePress_7G_log() {
	
	list ($date, $method, $protocol, $uri, $string, $address, $host, $referrer, $agent) = perishablePress_7G_vars();
	
	$log = $address .' - '. $date .' - '. $method .' - '. $protocol .' - '. $uri .' - '. $string .' - '. $host .' - '. $referrer .' - '. $agent ."\n";
	
	$log = preg_replace('/(\ )+/', ' ', $log);
	
	$fp = fopen(SEVENGLOGPATH . SEVENGLOGFILE, 'a+') or exit("Error: can't open log file!");
	
	fwrite($fp, $log);
	
	fclose($fp);
	
}

function perishablePress_7G_exit() {
	
	http_response_code(SEVENGSTATUS);
	
	exit(SEVENGEXIT);
	
}

function perishablePress_7G_server_protocol() {
	
	return isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';
	
}

function perishablePress_7G_request_method() {
	
	$string = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_REQUEST_METHOD']) ? $_SERVER['REDIRECT_7G_REQUEST_METHOD'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_query_string() {
	
	$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	
	$query = parse_url($request_uri);
	
	$string = isset($query['query']) ? $query['query'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_QUERY_STRING']) ? $_SERVER['REDIRECT_7G_QUERY_STRING'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_request_uri() {
	
	$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	
	$query = parse_url($request_uri);
	
	$string = isset($query['path']) ? $query['path'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_REQUEST_URI']) ? $_SERVER['REDIRECT_7G_REQUEST_URI'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_user_agent() {
	
	$string = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 
	
	$string = (defined(SEVENGUALENGTH)) ? substr($string, 0, SEVENGUALENGTH) : $string;
	
	$match = isset($_SERVER['REDIRECT_7G_USER_AGENT']) ? $_SERVER['REDIRECT_7G_USER_AGENT'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_ip_address() {
	
	$string = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_IP_ADDRESS']) ? $_SERVER['REDIRECT_7G_IP_ADDRESS'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_remote_host() {
	
	$string = ''; // todo: get host by address
	
	$match = isset($_SERVER['REDIRECT_7G_REMOTE_HOST']) ? $_SERVER['REDIRECT_7G_REMOTE_HOST'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_http_referrer() {
	
	$string = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	
	$match = isset($_SERVER['REDIRECT_7G_HTTP_REFERRER']) ? $_SERVER['REDIRECT_7G_HTTP_REFERRER'] : '';
	
	return perishablePress_7G_get_patterns($string, $match);
	
}

function perishablePress_7G_get_patterns($string, $match) {
	
	$patterns = explode('___', $match);
	
	foreach ($patterns as $pattern) {
		
		$string .= (!empty($pattern)) ? ' ['. $pattern .'] ' : '';
		
	}
	
	$string = preg_replace('/\s+/', ' ', $string);
	
	return $string;
	
}

perishablePress_7G_init();