<?php
require_once 'app.php';

log_clear();

if ( ! folder_exist( '../temp' ) ) {
	mkdir( '../temp', 0777 );
}

$pathschecked = array();
foreach ( $settings['config']['groups'] as $group ) {
	$paths = explode( ',', $group['price'] );
	foreach ( $paths as $path ) {
		if ( stripos( $path, '://' ) !== false ) {
			l( "Для прайса презентации " . $group['name'] . ' будет создан временный файл ' . md5( $path ) . '.txt' );
			$abs_path_win = '../temp/' . md5( $path ) . '.txt';
		} else {
			$abs_path       = $config['path'] . $path;
			$pathschecked[] = $abs_path;
		}

		if ( stripos( $path, '://' ) !== false ) {
			$abs_path_win = '../temp/' . md5( $path ) . '.txt';
			if ( ! $content = file_get_contents( $path ) ) {
				$abs_path = $path;
				l( "Для прайса презентации " . $group['name'] . ' не удалось скачать данные из источника ' . $path );
			} else {
				file_put_contents( $abs_path_win, $content );
				l( "Для прайса презентации " . $group['name'] . ' данные успешно загружены из удалённого источника' );
			}
		} else {
			$abs_path_win = $abs_path;
		}
		if ( ! WINDOWS ) {
			$abs_path_win = str_replace( '\\', '/', $abs_path_win );
		}
		if ( WINDOWS ) {
			$abs_path_win = iconv( 'UTF-8', 'windows-1251', $abs_path_win );
		}
		if ( ! file_exists( $abs_path_win ) ) {
			$status   = 'error';
			$errors[] = $abs_path;
		}
	}
}

$pathschecked = array_unique( $pathschecked );

print_result( array( 'checked' => $pathschecked ) );