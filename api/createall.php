<?php
	require_once 'app.php';
	require_once '../libs/PHPExcel.php';

	\PHPExcel_Settings::setZipClass(\PHPExcel_Settings::PCLZIP);

	error_reporting(0);
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);

	$xls_f = file_get_contents('../temp/xls.json');
	$xls_f = json_decode($xls_f,true);
	$g = $settings['config']['groups'][0];

	$xls = new PHPExcel();
	$xls->getProperties()->setCreator($settings['config']['presentation']['creator'])
								 ->setLastModifiedBy($settings['config']['presentation']['creator'])
								 ->setTitle($g['name'])
								 ->setSubject("Презентация")
								 ->setDescription("Презентация")
								 ->setKeywords("Общая")
								 ->setCategory("Общая");

	$on = false;
	foreach ($xls_f['xls'] as $p) {
		if($p['addtomain'] == 'true'){
			$on = true;
			$pt = $p['path'];
			if(!WINDOWS) $pt = str_replace('\\', '/', $pt);
			if (WINDOWS) $pt = iconv('UTF-8', 'windows-1251', $pt);
			$file_tmpl = PHPExcel_IOFactory::load($pt);
			$file_tmpl->setActiveSheetIndex(0);        
			$xls->addExternalSheet($file_tmpl->getActiveSheet());
			$file_tmpl->__destruct();
			unset($file_tmpl);
		}
	}
	if ($on) $xls->removeSheetByIndex(0);
	$xls->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
	$path = $config['excel'] . $g['path'] . '.' . $settings['config']['files']['extension'];
	// if (WINDOWS) $path = iconv('UTF-8', 'windows-1251', $path);
	$path = translit($path);
    try {
    	$objWriter->save($path);
    	l('Файл сохранён '.$path);
    } catch (Exception $e) {
    	$status = 'error';
    	l('Не удалось сохранить файл '.$path. ' Возможно он защищен от записи или открыт в Excel');
    }
    $path = substr($path, 0, -1);
    $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
    try {
    	$objWriter->save($path);
    	l('Файл сохранён '.$path);
    } catch (Exception $e) {
    	$status = 'error';
    	l('Не удалось сохранить файл '.$path. ' Возможно он защищен от записи или открыт в Excel');
    }
	print_result(array());

