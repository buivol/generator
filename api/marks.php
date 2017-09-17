<?php
require_once 'app.php';
$prepared = file_get_contents( '../temp/prepared.json' );
$prepared = json_decode( $prepared, true );

$prepared_b = $prepared;
$pk_groups  = array();
// Последняя коробка
foreach ( $prepared['presentation'] as $presentation_id => $presentation ) {
	if ( $presentation['pk'] == 'true' ) {
		foreach ( $presentation['groups'] as $group_id => $group ) {
			// Разбор групп
			foreach ( $group['items'] as $item_id => $item ) {
				//разбор товаров
				$min = intval( intval( $item['vup'] ) * 2 ) - 1;
				if ( $min > 0 && $item['count'] <= $min && $item['legal'] == false ) {
					// товара мало, надо в последнюю коробку
					if ( ! isset( $pk_groups[ $group['name'] ] ) ) {
						$pk_groups[ $group['name'] ] = array();
					}
					$pk_groups[ $group['name'] ][ $item['code'] ] = $item;
					// удаляет товар с перезентации
					unset( $prepared_b['presentation'][ $presentation_id ]['groups'][ $group_id ]['items'][ $item_id ] );
				}
			}
		}
	}
}
$pk_groups_b = array();
foreach ( $pk_groups as $group_name => $group ) {
	$grp          = array();
	$grp['name']  = $group_name;
	$grp['title'] = 'true';
	$items        = array();
	foreach ( $group as $item_code => $item ) {
		$items[] = $item;
	}
	$grp['items']  = $items;
	$pk_groups_b[] = $grp;
}
$prepared = $prepared_b;


// очистка марков от ненужных позиций
foreach ( $prepared['marks']['items'] as $markid => $mark ) {
	$_newitems = array();
	foreach ( $mark['items'] as $itemid => $markitem ) {
		foreach ( $prepared['presentation'] as $presentation_id => $presentation ) {
			foreach ( $presentation['groups'] as $group_id => $group ) {
				foreach ( $group['items'] as $item_id => $item ) {
					if ( $markitem['code'] == $item['art'] ) {
						$_newitems[] = $markitem;
					}
				}
			}
		}
	}
	$prepared_b['marks']['items'][ $markid ]['items'] = $_newitems;
}
unset( $prepared );
$prepared = $prepared_b;

// Обработка цен и картинок
foreach ( $prepared['presentation'] as $presentation_id => $presentation ) {
	foreach ( $presentation['groups'] as $group_id => $group ) {
		foreach ( $group['items'] as $item_id => $item ) {
			// цикл товаров
			$delete = false;
			foreach ( $prepared['marks']['items'] as $mark ) {
				// Цикл марков
				if ( $mark['main'] == 'true' ) {
					// Если марк меняет другие презентации
					foreach ( $mark['items'] as $itemmark ) {
						if ( $itemmark['code'] == $item['art'] ) {
							if ( $mark['cut'] == 'true' ) {
								$delete = true;
							}
							// если совпадают артикулы
							if ( isset( $mark['image'] ) ) {
								// Если есть картинка
								if ( ! isset( $item['images'] ) ) {
									$item['images'] = array();
								}
								$item['images'][ $mark['image_priority'] ] = $mark['image'];
							}
							if ( isset( $mark['desc'] ) ) {
								$item['sudesc'] = $mark['desc'];
							}
							// Обработка цен
							$old_price         = $item['price'];
							$item['price_old'] = $old_price;
							// $new_price = number_format($old_price - ($old_price / $mark['percent']), 2, ',', '');
							$item['cbase']          = $mark['cbase'];
							$item['cbaseval']       = $mark['cbaseval'];
							$item['cprice']         = $mark['cprice'];
							$item['cpriceval']      = $mark['cpriceval'];
							$item['cpreorder']      = $mark['cpreorder'];
							$item['cpreorderval']   = $mark['cpreorderval'];
							$item['excelprice']     = $itemmark['excelprice'];
							$item['excelprice_col'] = $itemmark['excelprice_col'];
						}
					}
				}
			}
			if ( $delete ) {
				unset( $prepared_b['presentation'][ $presentation_id ]['groups'][ $group_id ]['items'][ $item_id ] );
			} else {
				$prepared_b['presentation'][ $presentation_id ]['groups'][ $group_id ]['items'][ $item_id ] = $item;
			}
		}
	}
}
$prepared_items = $prepared;
$prepared       = $prepared_b;

// чистка групп
foreach ( $prepared['presentation'] as $x => $presentation ) {
	foreach ( $presentation['groups'] as $k => $group ) {
		if ( count( $group['items'] ) < 1 ) {
			$prepared_b['presentation'][ $x ]['groups'][ $k ]['title'] = 'false';
		}
	}
}

$prepared = $prepared_b;

$xls = array();
// массив с презентациями
// Создание собственной презентации из марков
foreach ( $prepared['marks']['items'] as $mark ) {
	if ( $mark['self'] == 'true' ) {
		l( 'Подготовка к созданию собственной презентации для отметки ' . $mark['name'] );
		$p              = array();
		$p['name']      = $mark['name'];
		$p['sheets']    = 'false';
		$p['ismark']    = 'true';
		$p['p1']        = 'false';
		$p['p5']        = 'false';
		$p['ptype']     = 0;
		$p['path']      = $config['excel'] . $p['name'] . '.' . $settings['config']['files']['extension'];
		$p['addtomain'] = 'false';
		if ( isset( $mark['header'] ) ) {
			$p['header'] = $mark['header'];
		}
		// перебор групп и кодов
		$groups_buffer = array();
		$gid           = - 1;
		$groups        = array();
		$itembuffer    = array();
		foreach ( $mark['items'] as $markitem ) {
			foreach ( $prepared_items['presentation'] as $presentation ) {
				foreach ( $presentation['groups'] as $k => $group ) {
					foreach ( $group['items'] as $item ) {
						if ( $item['art'] == $markitem['code'] && ! in_array( $item['art'], $itembuffer ) ) {
							// коды совпали
							$itembuffer[] = $item['art'];
							$gz           = $group['name'];
							if ( ! array_key_exists( $gz, $groups_buffer ) ) {
								// такой группы ещё нет
								$gid ++;
								$groups_buffer[ $gz ]    = $gid;
								$groups[ $gid ]          = array();
								$groups[ $gid ]['name']  = $group['name'];
								$groups[ $gid ]['title'] = $group['title'];
								$groups[ $gid ]['items'] = array();
								$gd                      = $gid;
							} else {
								$gd = $groups_buffer[ $gz ];
							}
							$itm = $item;
							unset( $itm['images'] );
							if ( isset( $mark['image'] ) ) {
								// Если есть картинка
								if ( ! isset( $itm['images'] ) ) {
									$itm['images'] = array();
								}
								$itm['images'][2] = $mark['image'];
							}

							// Обработка цен
							$old_price        = $item['price'];
							$itm['price_old'] = $old_price;
							// $new_price = number_format($old_price - ($old_price / $mark['percent']), 2, ',', '');
							$itm['cbase']          = $mark['cbase'];
							$itm['cbaseval']       = $mark['cbaseval'];
							$itm['cprice']         = $mark['cprice'];
							$itm['cpriceval']      = $mark['cpriceval'];
							$itm['cpreorder']      = $mark['cpreorder'];
							$itm['cpreorderval']   = $mark['cpreorderval'];
							$itm['excelprice']     = $markitem['excelprice'];
							$itm['excelprice_col'] = $markitem['excelprice_col'];
							if ( isset( $mark['desc'] ) ) {
								$itm['sudesc'] = $mark['desc'];
							}
							$groups[ $gd ]['items'][] = $itm;
						}
					}
				}
			}
		}
		$p['groups'] = $groups;
		$xls[]       = $p;
	}
}

// обработка заранее подготовленных

// print_r($prepared);
foreach ( $prepared['presentation'] as $pres ) {
	if ( $pres['id'] != 'all' && ! is_null( $pres['name'] ) ) {
		$p           = array();
		$p['name']   = $pres['name'];
		$p['sheets'] = $pres['sheets'];
		$p['ismark'] = 'false';
		$p['p1']     = $pres['p1'];
		$p['p5']     = $pres['p5'];
		$p['ptype']  = $pres['ptype'];
		// echo $p['name'].'|'.$pres['name'].'-';
		$p['path']      = $pres['path'];
		$p['addtomain'] = $pres['addtomain'];
		if ( isset( $pres['image'] ) ) {
			$p['header'] = $pres['image'];
		}
		$p['groups'] = $pres['groups'];
		$xls[]       = $p;
	}
}

// добавляем последнюю коробку
if ( count( $pk_groups ) > 1 ) {
	$p              = array();
	$p['name']      = $settings['config']['pk']['name'];
	$p['sheets']    = $settings['config']['pk']['sheets'];
	$p['ismark']    = 'false';
	$p['ispk']      = 'true';
	$p['ptype']     = 0;
	$p['path']      = $config['excel'] . $settings['config']['pk']['name'] . '.' . $settings['config']['files']['extension'];
	$p['addtomain'] = 'false';
	if ( isset( $settings['config']['pk']['header'] ) ) {
		$p['header'] = $config['images'] . $settings['config']['pk']['header'];
	}
	$p['groups'] = $pk_groups_b;
	$xls[]       = $p;
}
// 1000
$gr1000 = array();
foreach ( $xls as $id => $xl ) {
	if ( isset( $xl['p1'] ) && $xl['p1'] == 'true' ) {
		foreach ( $xl['groups'] as $gid => $group ) {
			foreach ( $group['items'] as $item_id => $item ) {
				if ( $item['count'] >= 1000 ) {
					if ( ! isset( $gr1000[ $group['name'] ] ) ) {
						$gr1000[ $group['name'] ] = array();
					}
					$gr1000[ $group['name'] ][] = $item;
				}
			}
		}
	}
}
$gr1000_b = array();
foreach ( $gr1000 as $group_name => $group ) {
	$grp          = array();
	$grp['name']  = $group_name;
	$grp['title'] = 'true';
	$items        = array();
	foreach ( $group as $item_code => $item ) {
		$items[] = $item;
	}
	$grp['items'] = $items;
	$gr1000_b[]   = $grp;
}
if ( count( $gr1000 ) > 0 ) {
	$p              = array();
	$p['name']      = $settings['config']['p1']['name'];
	$p['sheets']    = $settings['config']['p1']['sheets'];
	$p['ismark']    = 'false';
	$p['ptype']     = 0;
	$p['path']      = $config['excel'] . $settings['config']['p1']['name'] . '.' . $settings['config']['files']['extension'];
	$p['addtomain'] = 'false';
	if ( isset( $settings['config']['p1']['header'] ) ) {
		$p['header'] = $config['images'] . $settings['config']['p1']['header'];
	}
	$p['groups'] = $gr1000_b;
	if ( $settings['config']['p1']['active'] == 'true' ) {
		$xls[] = $p;
	}
}
// 5000
$gr5000 = array();
foreach ( $xls as $id => $xl ) {
	if ( isset( $xl['p5'] ) && $xl['p5'] == 'true' ) {
		foreach ( $xl['groups'] as $gid => $group ) {
			foreach ( $group['items'] as $item_id => $item ) {
				if ( $item['count'] >= 5000 ) {
					if ( ! isset( $gr5000[ $group['name'] ] ) ) {
						$gr5000[ $group['name'] ] = array();
					}
					$gr5000[ $group['name'] ][] = $item;
				}
			}
		}
	}
}
$gr5000_b = array();
foreach ( $gr5000 as $group_name => $group ) {
	$grp          = array();
	$grp['name']  = $group_name;
	$grp['title'] = 'true';
	$items        = array();
	foreach ( $group as $item_code => $item ) {
		$items[] = $item;
	}
	$grp['items'] = $items;
	$gr5000_b[]   = $grp;
}
if ( count( $gr5000 ) > 0 ) {
	$p              = array();
	$p['ptype']     = 0;
	$p['name']      = $settings['config']['p5']['name'];
	$p['sheets']    = $settings['config']['p5']['sheets'];
	$p['ismark']    = 'false';
	$p['path']      = $config['excel'] . $settings['config']['p5']['name'] . '.' . $settings['config']['files']['extension'];
	$p['addtomain'] = 'false';
	if ( isset( $settings['config']['p5']['header'] ) ) {
		$p['header'] = $config['images'] . $settings['config']['p5']['header'];
	}
	$p['groups'] = $gr5000_b;
	if ( $settings['config']['p5']['active'] == 'true' ) {
		$xls[] = $p;
	}
}


$temp_file = array( 'xls' => $xls );

$temp_file_json = json_encode( $temp_file );

file_put_contents( dirname( __DIR__ ) . '/temp/xls.json', $temp_file_json );

print_result( array() );