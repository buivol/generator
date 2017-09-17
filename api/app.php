<?php
set_time_limit( 9999 );
setlocale( LC_CTYPE, 'ru_RU.UTF-8' );
$callStartTime = microtime( true );
require_once '../config.php';
require_once '../helpers.php';
$settings    = file_get_contents( '../saved.dat' );
$settings    = json_decode( $settings, true );
$status      = 'ok';
$errors      = array();
$systemerror = array();
$log         = array();
ini_set( 'display_errors', 0 );
ini_set( 'display_startup_errors', 1 );
error_reporting( 0 );
set_error_handler( 'err_handler' );
function err_handler( $errno, $errmsg, $filename, $linenum ) {
	global $systemerror;
	$date     = date( 'Y-m-d H:i:s (T)' );
	$filename = str_replace( $_SERVER['DOCUMENT_ROOT'], '', $filename );
	$f        = fopen( 'errors.txt', 'a' );
	if ( ! empty( $f ) ) {
		$err = "$date: $errmsg = $filename = $linenum\r\n";
		fwrite( $f, $err );
		fclose( $f );
	}
	$systemerror[] = "$errmsg = $filename = $linenum";
}

function l( $message ) {
	global $log;
	$date           = date( 'Y-m-d H:i:s' );
	$log[]          = $message;
	$log_code       = md5( $message . $date ) . md5( rand() );
	$log_msg        = array( 't' => $message, 'c' => $log_code );
	$content        = file_get_contents( 'log.json' );
	$log_enc        = json_decode( $content, true );
	$log_enc['m'][] = $log_msg;
	$log_enc['l']   = $date;
	$log_cont       = json_encode( $log_enc );
	$f              = fopen( 'log.json', 'w' );
	if ( ! empty( $f ) ) {
		fwrite( $f, $log_cont );
		fclose( $f );
	}
}

function log_clear() {
	$date = date( 'Y-m-d H:i:s' );
	$f    = fopen( 'log.json', 'w' );
	if ( ! empty( $f ) ) {
		fwrite( $f, json_encode( array( 'l' => $date, 'm' => array() ) ) );
		fclose( $f );
	}
}

function print_result( $array ) {
	global $status;
	global $callStartTime;
	global $errors;
	global $systemerror;
	global $log;
	$callEndTime      = microtime( true );
	$callTime         = $callEndTime - $callStartTime;
	$result           = $array;
	$result['status'] = $status;
	$result['time']   = $callTime;
	$result['errors'] = array_unique( $errors );
	$result['system'] = $systemerror;
	$result['peek']   = memory_get_peak_usage( true );
	echo json_encode( $result );
}