<?php

$os_string = php_uname( 's' );

if ( strpos( strtoupper( $os_string ), 'WIN' ) !== false ) {
	define( 'WINDOWS', true );

} else {
	define( 'WINDOWS', false );
}
$config = array();


// Системные настройки генератора


//путь к папке выгрузка
$config['path'] = '/var/www/html/public/input/';
//путь к папке с картинками
$config['images']     = '/var/www/html/public/images/';
$config['httpimages'] = '/public/images/';
//путь к папке с готовыми файлами excel
$config['excel'] = '/var/www/html/public/result/';