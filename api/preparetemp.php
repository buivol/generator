<?php
require_once 'app.php';
require_once '../libs/PHPExcel/IOFactory.php';

$fl = false;

\PHPExcel_Settings::setZipClass( \PHPExcel_Settings::PCLZIP );

$marks              = array();
$presentation_count = 0;

$marks['count'] = 0;
$marks['items'] = array();
foreach ( $settings['config']['marks'] as $mark ) {
	$abs_path     = $config['path'] . $mark['path'];
	$abs_path_win = $abs_path;
	if ( ! WINDOWS ) {
		$abs_path_win = str_replace( '\\', '/', $abs_path_win );
	}
	if ( WINDOWS ) {
		$abs_path_win = iconv( 'UTF-8', 'windows-1251', $abs_path_win );
	}
	if ( file_exists( $abs_path_win ) && $mark['active'] == 'true' ) {
		$m                 = array();
		$m['name']         = $mark['name'];
		$m['cbase']        = $mark['cbase'];
		$m['cbaseval']     = $mark['cbaseval'];
		$m['cprice']       = $mark['cprice'];
		$m['cpriceval']    = $mark['cpriceval'];
		$m['cpreorder']    = $mark['cpreorder'];
		$m['cpreorderval'] = $mark['cpreorderval'];
		$m['main']         = $mark['main'];
		$m['self']         = $mark['self'];
		if ( ! isset( $mark['cut'] ) ) {
			$m['cut'] = 'false';
		} else {
			$m['cut'] = $mark['cut'];
		}
		if ( $m['self'] == 'true' ) {
			$presentation_count ++;
		}
		if ( $mark['pastedesc'] == 'true' ) {
			$m['desc'] = $mark['desc'];
		}
		if ( strlen( $mark['image'] ) > 1 ) {
			$abs_path_i     = $config['images'] . $mark['image'];
			$abs_path_win_i = $abs_path_i;
			if ( ! WINDOWS ) {
				$abs_path_win_i = str_replace( '\\', '/', $abs_path_win_i );
			}
			if ( WINDOWS ) {
				$abs_path_win_i = iconv( 'UTF-8', 'windows-1251', $abs_path_i );
			}
			if ( file_exists( $abs_path_win_i ) ) {
				$m['image'] = $abs_path_i;
				$ip         = substr( basename( $abs_path_i ), 0, 1 );
				if ( preg_match( "/^[\d\+]+$/", $ip ) ) {
					$m['image_priority'] = $ip;
					$ip2                 = substr( basename( $abs_path_i ), 1, 1 );
					if ( preg_match( "/^[\d\+]+$/", $ip2 ) ) {
						$m['image_priority'] = $ip . $ip2;
					} else {
						$m['image_priority'] = 2;
					}
				} else {
					$m['image_priority'] = 2;
				}

			}
		}
		if ( strlen( $mark['header'] ) > 1 ) {
			$abs_path_i     = $config['images'] . $mark['header'];
			$abs_path_win_i = $abs_path_i;
			if ( ! WINDOWS ) {
				$abs_path_win_i = str_replace( '\\', '/', $abs_path_win_i );
			}
			if ( WINDOWS ) {
				$abs_path_win_i = iconv( 'UTF-8', 'windows-1251', $abs_path_win_i );
			}
			if ( file_exists( $abs_path_win_i ) ) {
				$m['header'] = $abs_path_i;
			}
		} else {
			// Нет картинки
		}
		$xls = PHPExcel_IOFactory::load( $abs_path_win );
		$xls->setActiveSheetIndex( 0 );
		$sheet      = $xls->getActiveSheet();
		$cc         = $mark['code']; // Колонка кода товара
		$lc         = $mark['col']; // Колонка логики
		$true_logic = $mark['val']; // Правильное значение
		$pf         = $mark['pfix']; // Колонка фиксированной цены
		$pn         = $mark['pnew']; // Колонка новой цены
		$pb         = $mark['pbase']; // Колонка базовой цены
		$m['count'] = 0;
		$m['items'] = array();
		for ( $i = 1; $i <= $sheet->getHighestRow(); $i ++ ) {
			$logic = $sheet->getCell( $lc . $i )->getCalculatedValue();
			// echo "($lc$i) $true_logic == $logic\r\n";
			if ( $logic == $true_logic ) {
				// Логика совпала
				$code = $sheet->getCell( $cc . $i )->getCalculatedValue();
				if ( strlen( $cc ) > 0 ) {
					// Код существует
					$mm         = array();
					$mm['code'] = $code;
					// Вычисляем цену
					$pfix  = $sheet->getCell( $pf . $i )->getCalculatedValue();
					$pnew  = $sheet->getCell( $pn . $i )->getCalculatedValue();
					$pbase = $sheet->getCell( $pb . $i )->getCalculatedValue();
					$pr    = '-1';
					$pa    = 'error';
					if ( strlen( $pfix ) > 0 ) {
						$pr = $pfix;
						$pa = 'fix';
					} else if ( strlen( $pnew ) > 0 ) {
						$pr = $pnew;
						$pa = 'new';
					} else {
						$pr = $pbase;
						$pa = 'base';
					}
					$mm['excelprice']     = $pr;
					$mm['excelprice_col'] = $pa;
					// добавляем позицию в отметку
					$m['count'] ++;
					$m['items'][] = $mm;
				}
			}
		}

		$marks['count'] ++;
		$marks['items'][] = $m;
		unset( $xls, $sheet );
	}
}


$presentations = array();

foreach ( $settings['config']['groups'] as $group ) {
	if ( $group['generate'] == "true" ) {
		$presentation_count ++;
		$p         = array();
		$id_array  = array();
		$grp_array = explode( ',', $group['id'] );
		$grps      = array();
		$groups    = array();
		foreach ( $grp_array as $grpname ) {
			foreach ( $settings['config']['grp'] as $grp ) {
				if ( $grp['name'] == $grpname ) {
					$groups[ $grp['name'] ]          = $grp;
					$groups[ $grp['name'] ]['items'] = array();
					$ids                             = $grp['ids'];
					$ids                             = explode( ',', $ids );
					foreach ( $ids as $o ) {
						$id_array[] = $o;
						$grps[ $o ] = $grp['name'];
					}
				}
			}
		}
		$id_array  = array_unique( $id_array );
		$p['id']   = $group['id'];
		$p['name'] = $group['name'];
		if ( isset( $group['sheets'] ) ) {
			$p['sheets'] = $group['sheets'];
		} else {
			$p['sheets'] = 'false';
		}
		if ( isset( $group['pk'] ) ) {
			$p['pk'] = $group['pk'];
		} else {
			$p['pk'] = 'false';
		}
		if ( isset( $group['p1'] ) ) {
			$p['p1'] = $group['p1'];
		} else {
			$p['p1'] = 'false';
		}
		if ( isset( $group['p5'] ) ) {
			$p['p5'] = $group['p5'];
		} else {
			$p['p5'] = 'false';
		}
		if ( isset( $group['ptype'] ) ) {
			$p['ptype'] = $group['ptype'];
		} else {
			$p['ptype'] = 0;
		}
		$p['path'] = $config['excel'] . $group['path'] . '.' . $settings['config']['files']['extension'];
		if ( $p['id'] == 'all' ) {
			$p['main'] = 'true';
		} else {
			$p['main'] = 'false';
		}
		$p['addtomain'] = $group['main'];
		if ( strlen( $group['image'] ) > 1 ) {
			$abs_path_i     = $config['images'] . $group['image'];
			$abs_path_win_i = $abs_path_i;
			if ( ! WINDOWS ) {
				$abs_path_win_i = str_replace( '\\', '/', $abs_path_win_i );
			}
			if ( WINDOWS ) {
				$abs_path_win_i = iconv( 'UTF-8', 'windows-1251', $abs_path_win_i );
			}
			if ( file_exists( $abs_path_win_i ) ) {
				$p['image'] = $abs_path_i;
			}
		}
		if ( $p['main'] == 'false' ) {
			$paths = explode( ',', $group['price'] );
			foreach ( $paths as $path ) {
				$abs_path     = $config['path'] . $path;
				$abs_path_win = $abs_path;
				if ( stripos( $path, '://' ) !== false ) {
					$abs_path_win = '../temp/' . md5( $path ) . '.txt';
				} else {
					if ( ! WINDOWS ) {
						$abs_path_win = str_replace( '\\', '/', $abs_path_win );
					}
					if ( WINDOWS ) {
						$abs_path_win = iconv( 'UTF-8', 'windows-1251', $abs_path_win );
					}
				}
				if ( file_exists( $abs_path_win ) ) {
					$content   = iconv( 'windows-1251', 'UTF-8', file_get_contents( $abs_path_win ) );
					$positions = explode( "\n", $content );
					foreach ( $positions as $position ) {
						$pos       = trim( $position );
						$details   = explode( ';', $pos );
						$groupname = false;

						if ( count( $details ) <= 2 ) {
							$groupname = $details[0];
						} else if ( $details[2] == '' ) {
							$groupname = $details[0];
						}

						if ( $groupname ) {
							// фабрика
						} else {
							$group_id = trim( $details[1] );
							if ( in_array( $group_id, $id_array ) || in_array( 'allgroups', $id_array ) ) {
								$gd            = $grps[ $group_id ];
								$item          = array();
								$item['code']  = trim( $details[0] );
								$item['group'] = trim( $details[1] );
								$item['name']  = trim( $details[2] );
								$item['art']   = trim( $details[3] );
								$item['vup']   = trim( $details[4] );
								$item['price'] = trim( $details[6] );
								$item['count'] = trim( $details[7] );
								$item['desc']  = trim( $details[11] );
								if ( isset( $details[12] ) ) {
									$item['ean'] = trim( $details[12] );
								} else {
									$item['ean'] = '';
								}
								if ( isset( $details[15] ) ) {
									$item['zakup'] = trim( $details[15] );
								} else {
									$item['zakup'] = false;
								}
								if ( isset( $details[16] ) ) {
									$item['log'] = trim( $details[16] );
								} else {
									$item['log'] = false;
								}
								if ( isset( $details[17] ) ) {
									$item['spec1'] = trim( $details[17] );
								} else {
									$item['spec1'] = false;
								}
								if ( isset( $details[18] ) ) {
									$item['spec2'] = trim( $details[18] );
								} else {
									$item['spec2'] = false;
								}

								if ( stripos( $pos, '[legal]' ) !== false ) {
									// не добавлять в пк
									$item['legal'] = true;
								} else {
									$item['legal'] = false;
								}
								if ( $p['ptype'] == 1 && $item['spec1'] && $item['spec2'] ) {
									if ( in_array( 'allgroups', $id_array ) ) {
										$gd = $grps['allgroups'];
									}
									$groups[ $gd ]['items'][] = $item;
								} else if ( $p['ptype'] != 1 ) {
									if ( in_array( 'allgroups', $id_array ) ) {
										$gd = $grps['allgroups'];
									}
									$groups[ $gd ]['items'][] = $item;
								}

							}
						}
					}
				}
			}
		}
		// Сортировка
		$groups_b = $groups;
		foreach ( $groups_b as $group_id => $group ) {
			$groups[ $group_id ]['items'] = array_orderby( $group['items'], 'art', SORT_ASC );
		}
		unset( $groups_b );

		$p['groups']     = $groups;
		$presentations[] = $p;
	}
}

$temp_file = array( 'presentation_count' => $presentation_count, 'marks' => $marks, 'presentation' => $presentations );

$temp_file_json = json_encode( $temp_file );

file_put_contents( dirname( __DIR__ ) . '/temp/prepared.json', $temp_file_json );

print_result( array( 'count' => $presentation_count ) );

/*
	0 - код товара
	1 - группа товара
	2 - наименование товара - обязательно для презентации
	3 - артикул товара - обязательно для презентации
	4 - кол-во в упаковке - обязательно для презентации
	5 - индекс значков (1 - новинка; 10 - новинка100)
	6 - цена - обязательно для презентации
	7 - кол-во на остатке - обязательно для презентации
	8 - вес
	9 - объем
	10 - адрес хранения
	11 - дополнительное описание товара
	12 - штрихкод
	13 - ключ для вывода товара по коду
	14 - бренд товара

*/