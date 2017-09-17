<?php
function getFilesArr( $dir ) {
	global $config;
	$listDir = Array();
	if ( $handle = opendir( $dir ) ) {
		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file == '.' || $file == '..' ) {
				continue;
			}
			$path = $dir . DIRECTORY_SEPARATOR . $file;
			if ( is_file( $path ) ) {
				if ( ! WINDOWS ) {
					$path = str_replace( '\\', '/', $path );
				}
				if ( WINDOWS ) {
					$path = mb_convert_encoding( $path, "utf-8", "windows-1251" );
				}
				$p = str_replace( $config['path'], '', $path );
				$p = str_replace( $config['images'], '', $p );
				$p = substr( $p, 1 );
				$listDir[] = $p;

			} else if ( is_dir( $path ) ) {
				if ( $path != ( $config['images'] . DIRECTORY_SEPARATOR . 'items' ) ) {
					$listDir = array_merge( $listDir, getFilesArr( $path ) );
				}
			}
		}
		closedir( $handle );

		return $listDir;
	}
}

function translit( $str ) {
	$rus = array(
		'А',
		'Б',
		'В',
		'Г',
		'Д',
		'Е',
		'Ё',
		'Ж',
		'З',
		'И',
		'Й',
		'К',
		'Л',
		'М',
		'Н',
		'О',
		'П',
		'Р',
		'С',
		'Т',
		'У',
		'Ф',
		'Х',
		'Ц',
		'Ч',
		'Ш',
		'Щ',
		'Ъ',
		'Ы',
		'Ь',
		'Э',
		'Ю',
		'Я',
		'а',
		'б',
		'в',
		'г',
		'д',
		'е',
		'ё',
		'ж',
		'з',
		'и',
		'й',
		'к',
		'л',
		'м',
		'н',
		'о',
		'п',
		'р',
		'с',
		'т',
		'у',
		'ф',
		'х',
		'ц',
		'ч',
		'ш',
		'щ',
		'ъ',
		'ы',
		'ь',
		'э',
		'ю',
		'я',
		' '
	);
	$lat = array(
		'A',
		'B',
		'V',
		'G',
		'D',
		'E',
		'E',
		'Gh',
		'Z',
		'I',
		'Y',
		'K',
		'L',
		'M',
		'N',
		'O',
		'P',
		'R',
		'S',
		'T',
		'U',
		'F',
		'H',
		'C',
		'Ch',
		'Sh',
		'Sch',
		'Y',
		'Y',
		'Y',
		'E',
		'Yu',
		'Ya',
		'a',
		'b',
		'v',
		'g',
		'd',
		'e',
		'e',
		'gh',
		'z',
		'i',
		'y',
		'k',
		'l',
		'm',
		'n',
		'o',
		'p',
		'r',
		's',
		't',
		'u',
		'f',
		'h',
		'c',
		'ch',
		'sh',
		'sch',
		'y',
		'y',
		'y',
		'e',
		'yu',
		'ya',
		'_'
	);

	return str_replace( $rus, $lat, $str );
}

function array_orderby() {
	$args = func_get_args();
	$data = array_shift( $args );
	foreach ( $args as $n => $field ) {
		if ( is_string( $field ) ) {
			$tmp = array();
			foreach ( $data as $key => $row ) {
				$tmp[ $key ] = $row[ $field ];
			}
			$args[ $n ] = $tmp;
		}
	}
	$args[] = &$data;
	call_user_func_array( 'array_multisort', $args );

	return array_pop( $args );
}


function folder_exist( $folder ) {
	$path = realpath( $folder );

	return ( $path !== false AND is_dir( $path ) ) ? $path : false;
}

function removeDirectory( $dir ) {
	if ( $objs = glob( $dir . "/*" ) ) {
		foreach ( $objs as $obj ) {
			is_dir( $obj ) ? removeDirectory( $obj ) : unlink( $obj );
		}
	}
	if ( folder_exist( $dir ) ) {
		rmdir( $dir );
	}
}


function getAB( $key ) {
	$key = strtoupper( $key );
	$num = - 1;
	foreach ( range( 'A', 'Z' ) as $i ) {
		$num ++;
		if ( $key == $i ) {
			return $num;
		}
	}
}