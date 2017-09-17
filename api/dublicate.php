<?php
require_once 'app.php';

$count_good = 0;
$count_bad  = '0';

$buffer = '';

foreach ( $settings['config']['links'] as $k => $link ) {
	$p = $link['path'];
	if ( $link['active'] != 'false' ) {
		$abs_path_win = $config['path'] . $p;
		if ( ! WINDOWS ) {
			$abs_path_win = str_replace( '\\', '/', $abs_path_win );
		}
		if ( WINDOWS ) {
			$abs_path_win = iconv( 'UTF-8', 'windows-1251', $abs_path_win );
		}
		if ( ! file_exists( $abs_path_win ) ) {
			l( "Ссылка $p пропущена (файл не найден)" );
			$count_bad ++;
		} else {
			$count_good ++;
			$content = iconv( 'windows-1251', 'UTF-8', file_get_contents( $abs_path_win ) );
			//Обработка последней коробки
			if ( $link['pk'] == 'true' ) {
				$content = str_replace( "\r\n", ";[legal]\r\n", $content );
			}

			if ( $count_good == 1 ) {
				$buffer = $content;
			} else {
				$buffer .= "\n" . $content;
			}
		}
	} else {
		l( "Ссылка $p пропущена (не активна)" );
		$count_bad ++;
	}
}

// Буфер набран, обработка товаров

$tovcount  = 0;
$fab       = 0;
$dub       = 0;
$tovarr    = array();
$dellines  = array();
$positions = explode( "\n", $buffer );

$positions_buffer = $positions;

foreach ( $positions as $line => $position ) {
	$pos       = trim( $position );
	$details   = explode( ';', $pos );
	$groupname = false;

	if ( count( $details ) < 2 ) {
		$groupname = $details[0];
	} else if ( $details[2] == '' ) {
		$groupname = $details[0];
	}

	if ( $groupname ) {
		$fab ++;
	} else {
		$tovcount ++;
		$art = trim( $details[3] );
		if ( ! isset( $tovarr[ $art ] ) ) {
			$tovarr[ $art ] = $line;
		} else {
			$dub ++;
			$c                          = trim( $details[7] );
			$oline                      = $tovarr[ $art ];
			$dellines[]                 = $line;
			$opos                       = trim( $positions_buffer[ $oline ] );
			$odetails                   = explode( ';', $opos );
			$cc                         = trim( $odetails[7] );
			$sum                        = $c + $cc;
			$odetails[7]                = $sum;
			$npos                       = implode( ';', $odetails );
			$positions_buffer[ $oline ] = $npos;
			l( "Найден дубликат арт. $art с кол-вом $c шт. Будет добавлен в существующую позицию (было $cc шт станет $sum шт)" );
		}
	}
}

// Удаляем ненужные линии, сохраняем буфер в файл
$result_buffer = array();
$fl            = true;
foreach ( $positions_buffer as $line => $text ) {
	if ( ! in_array( $line, $dellines ) ) {
		if ( $fl ) {
			$result_buffer = $text;
			$fl            = false;
		} else {
			$result_buffer .= "\n" . $text;
		}
	}
}

$result_buffer = iconv( 'UTF-8', 'windows-1251', $result_buffer );
$fname         = $config['path'] . $settings['config']['files']['linker'];
if ( ! WINDOWS ) {
	$fname = str_replace( '\\', '/', $fname );
}
if ( WINDOWS ) {
	$fname = iconv( 'UTF-8', 'windows-1251', $fname );
}
if ( file_exists( $fname ) ) {
	unset( $fname );
}
file_put_contents( $fname, $result_buffer );

l( "Дубли: обработано файлов $count_good пропущено $count_bad позиций $tovcount групп $fab дубликатов $dub" );


print_result( array() );