<?php
	
	require_once 'app.php';
	require_once '../libs/PHPExcel.php';
	
	$validLocale = PHPExcel_Settings::setLocale('ru');
	$xls_f = file_get_contents('../temp/xls.json');
	$xls_f = json_decode($xls_f,true);


	$_border_big_style = array('style' => PHPExcel_Style_Border::BORDER_THICK,
				'color' => array(
				'	rgb' => '808080'
				));
	$_border_big = array(
        	'top' =>$_border_big_style,
        	'left'=>$_border_big_style,
        	'bottom'=>$_border_big_style,
        	'right'=>$_border_big_style
        );

	$_border_medium_style = array('style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				'color' => array(
				'	rgb' => '808080'
				));
	$_border_medium = array(
        	'top' =>$_border_medium_style,
        	'left'=>$_border_medium_style,
        	'bottom'=>$_border_medium_style,
        	'right'=>$_border_medium_style
        );
	$_border_small_style = array('style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array(
				'	rgb' => '808080'
				));
	$_border_small = array(
        	'top' =>$_border_small_style,
        	'left'=>$_border_small_style,
        	'bottom'=>$_border_small_style,
        	'right'=>$_border_small_style
        );

	function createHeader(&$sheet, $title, $headerImage, $ptype = 0){
		global $_border_medium;
		global $_border_small;
		global $config;
		global $settings;
		$sheet->getColumnDimension('A')->setWidth(10+0.71);
		$sheet->getColumnDimension('B')->setWidth(22+0.71+0.73);
		$sheet->getColumnDimension('C')->setWidth(33+0.71);
		$sheet->getColumnDimension('D')->setWidth(14.9+0.71);
		$sheet->getColumnDimension('E')->setWidth(10+0.71);
		$sheet->getColumnDimension('F')->setWidth(10+0.71);
		$sheet->getColumnDimension('G')->setWidth(11+0.71);
		$sheet->getColumnDimension('H')->setWidth(13+0.71);
		$sheet->getColumnDimension('I')->setWidth(12.5+0.71);
		$sheet->getColumnDimension('J')->setWidth(15+0.71);
		$sheet->getColumnDimension('K')->setWidth(18.4+0.71);
		$sheet->getColumnDimension('L')->setWidth(18+0.71);
		$sheet->getRowDimension(1)->setRowHeight(12.75);
		$sheet->getRowDimension(2)->setRowHeight(13.50);
		$sheet->getRowDimension(3)->setRowHeight(13.50);
		$sheet->getRowDimension(4)->setRowHeight(60);
		$sheet->getRowDimension(5)->setRowHeight(13.50);
		$sheet->getRowDimension(6)->setRowHeight(13.50);
		$sheet->getRowDimension(7)->setRowHeight(27);
		if($ptype == 0){
			$sheet->setCellValue('A7', 'Код')
	            ->setCellValue('B7', 'Артикул')
	            ->setCellValue('C7', 'Фото')
	            ->setCellValue('D7', 'Описание')
	            ->setCellValue('E7', 'Кол-во в коробке')
	            ->setCellValue('F7', 'Базовая цена')
	            ->setCellValue('G7', 'Цена')
	            ->setCellValue('H7', 'Цена по предоплате')
	            ->setCellValue('I7', 'Кол-во на остатке')
	            ->setCellValue('J7', 'Заявка шт.')
	            ->setCellValue('K7', 'Сумма')
	            ->setCellValue('L7', 'Штрихкод');
        } else if($ptype == 1){
        	$sheet->setCellValue('A7', 'Код')
	            ->setCellValue('B7', 'Артикул')
	            ->setCellValue('C7', 'Фото')
	            ->setCellValue('D7', 'Описание')
	            ->setCellValue('E7', 'Кол-во в коробке')
	            ->setCellValue('F7', 'Базовая цена')
	            ->setCellValue('G7', 'Цена наличные')
	            ->setCellValue('H7', 'Цена безнал')
	            ->setCellValue('I7', 'Кол-во на остатке')
	            ->setCellValue('J7', 'Заявка шт.')
	            ->setCellValue('K7', 'Сумма')
	            ->setCellValue('L7', 'Штрихкод');
        } else if($ptype == 2){
        	$sheet->setCellValue('A7', 'Код')
	            ->setCellValue('B7', 'Артикул')
	            ->setCellValue('C7', 'Фото')
	            ->setCellValue('D7', 'Описание')
	            ->setCellValue('E7', 'Кол-во в коробке')
	            ->setCellValue('F7', 'Базовая цена')
	            ->setCellValue('G7', 'Цена')
	            ->setCellValue('H7', 'Цена по предоплате')
	            ->setCellValue('I7', 'Кол-во на остатке')
	            ->setCellValue('J7', 'Заявка шт.')
	            ->setCellValue('K7', 'Сумма')
	            ->setCellValue('L7', 'Штрихкод')
	            ->setCellValue('M7', 'Цена $')
	            ->setCellValue('N7', 'ЛОГ');
	            $sheet->getColumnDimension('M')->setWidth(13+0.71);
				$sheet->getColumnDimension('N')->setWidth(12.5+0.71);
				$sheet->getStyle("M7")->applyFromArray(array('borders' => $_border_medium));
	    		$sheet->getStyle("N7")->applyFromArray(array('borders' => $_border_medium));
	    		$sheet->getStyle('M7')->getFill()->applyFromArray(array(
			        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        'startcolor' => array(
			             'rgb' => $settings['config']['presentation']['colorheader']
			        )
			    ));
			    $sheet->getStyle('N7')->getFill()->applyFromArray(array(
			        'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        'startcolor' => array(
			             'rgb' => $settings['config']['presentation']['colorheader']
			        )
			    ));
        }
		
	    $sheet->getStyle("A7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("B7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("C7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("D7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("E7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("F7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("G7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("H7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("I7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("J7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("K7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->getStyle("L7")->applyFromArray(array('borders' => $_border_medium));
	    $sheet->setTitle($title);
	    $sheet->getStyle('A2:K7')->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
	             'rgb' => $settings['config']['presentation']['colorheader']
	        )
	    ));
	    $sheet->getStyle('L7')->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
	             'rgb' => $settings['config']['presentation']['colorheader']
	        )
	    ));

	    
	    $sheet->getStyle("A1:N7")->applyFromArray(array(
	    	'alignment'=>array(
				'wrap'       	=> true,
			),
			'font' => array(
				'name' => $settings['config']['presentation']['fontheadername'],
				'size' => $settings['config']['presentation']['fontheadersize']
			)
	    ));
	    $sheet->getStyle("A7:N7")->applyFromArray(array(
	    	'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
	            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
	            'wrap' => true,
	            'shrinkToFit'	=> false
	        ),
	        'format' => array(
	        	'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT
	        ),
			'font' => array(
				'name' => $settings['config']['presentation']['fontheadername'],
				'size' => $settings['config']['presentation']['fontheadersize'],
				'bold' => true
			)
	    ));
	    if($ptype == 1){
	    	$size = 11;
	    } else {
	    	$size = 16;
	    }
	    $sheet->getStyle("G7")->applyFromArray(array(
	    	'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	            'wrap' => true,
	            'shrinkToFit'	=> false
	        ),
			'font' => array(
				'bold' => true,
				'size' => $size,
				'color' => array('rgb' => $settings['config']['presentation']['colorcena']),
			)
	    ));

		$imagePath = $config['images'].$settings['config']['presentation']['logo'];
		if(!WINDOWS) $imagePath = str_replace('\\', '/', $imagePath);
		if (WINDOWS) $imagePath = iconv('UTF-8', 'windows-1251', $imagePath);
				
		if (file_exists($imagePath)) {
			$logo = new PHPExcel_Worksheet_Drawing();
			$logo->setPath($imagePath);
			$logo->setCoordinates("A2");				
			$logo->setOffsetX(0);
			$logo->setOffsetY(0);
			$logo->setHeight(150);	
			$logo->setWorksheet($sheet);
		} 

		$sheet->getStyle("A2:K6")->applyFromArray(array(
			'borders' => $_border_medium,
			
		));
		$generated = date("l d F Y H:i:s");
		$style = array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        'startcolor' => array(
			             'rgb' => 'FFFFFF'
			        )
		        ),
		        'borders' => $_border_small,
		        'numberformat' => array('code'=> PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00)
		    );
		$sheet->setCellValue('J2','ПРЕДОПЛАТА');
		$sheet->setCellValue('J3','0');
		$sheet->getStyle('J3')->applyFromArray($style);
		$sheet->setCellValue('J5','0');
		$sheet->getStyle('J5')->applyFromArray($style);
		$sheet->setCellValue('K3','0');
		$sheet->getStyle('K3')->applyFromArray($style);
		$sheet->setCellValue('K2','ИТОГО:');
		$sheet->setCellValue('J4','СКИДКА:');
		$sheet->setCellValue('C2','Обновлено: '.$generated);
		$style = array(
		        'alignment'=>array(
					'wrap'       	=> false,
					'shrinkToFit'	=> false
				)
		    );
		$sheet->mergeCells("C4:H6");
		$sheet->setCellValue('C4',$settings['config']['presentation']['header']);
		$sheet->getStyle('C2')->applyFromArray($style);
		$style = array(
		        'alignment'=>array(
					'wrap'       	=> true,
					'shrinkToFit'	=> false
				)
		    );

		$sheet->getStyle('C4')->applyFromArray($style);

		if(isset($headerImage)){
			$imagePath = $headerImage;		
			if (file_exists($imagePath)) {
				$logo = new PHPExcel_Worksheet_Drawing();
				if(!WINDOWS) $imagePath = str_replace('\\', '/', $imagePath);
				if (WINDOWS) $imagePath = iconv('UTF-8', 'windows-1251', $imagePath);
				$logo->setPath($imagePath);
				$logo->setCoordinates("C2");				
				$logo->setOffsetX(0);
				$logo->setOffsetY(0);
				$logo->setResizeProportional(false);
				$logo->setHeight(150);	
				$logo->setWidth(750);
				$logo->setWorksheet($sheet);
			} 
		}
	}

	foreach ($xls_f['xls'] as $p) {
		if(isset($p['name']) && $p['name'] != null){
		$xls = new PHPExcel();
		$xls->getProperties()->setCreator($settings['config']['presentation']['creator'])
								 ->setLastModifiedBy($settings['config']['presentation']['creator'])
								 ->setTitle($p['name'])
								 ->setSubject("Презентация ".$p['name'])
								 ->setDescription("Презентация ".$p['name'])
								 ->setKeywords($p['name'])
								 ->setCategory($p['name']);
		if(!isset($p['sheets']) || $p['sheets'] == 'false' || $p['sheets'] == false){
			$sh = false;
			$title = $p['name'];
		} else {
			$sh = true;
			$title = '';
		}
		
		
		if(!$sh){
			$sheet = $xls->getActiveSheet();
			if(!isset($p['header'])) $img = ''; else $img = $p['header'];
			createHeader($sheet, $title, $img, $p['ptype']);
			$i = 7;
		} else {
			$firstpage = true;
			$sheetIndex = 1;
		}


	    foreach ($p['groups'] as $group) {
	    	if($sh){
	    		if(!$firstpage){
	    			$sheet = $xls->createSheet($sheetIndex++);
	    		} else {
	    			$sheet = $xls->getActiveSheet();
	    			$firstpage = false;
	    		}
				createHeader($sheet, mb_substr($group['name'],0,30), $p['header'], $p['ptype']);
				$i = 7;
			}
	    	else if($settings['config']['presentation']['grouped'] == "true" && $group['title'] == 'true'){
	    		// Заголовок группы
	    		$i++;
		    	$sheet->mergeCells("A".$i.":L".$i);
		    	$sheet->getRowDimension($i)->setRowHeight(20.25);
				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			        ),
			        'borders' => $_border_big,
			        'font' => array(
			        	'name' => 'Arial',
						'bold' => true,
						'size' => 16,
						'color' => array('rgb' => $settings['config']['presentation']['colorgrouptext']),
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => $settings['config']['presentation']['colortitle']
				        )
				    )
			        
			    );
	    		$sheet->getStyle("A".$i.":L".$i)->applyFromArray($style);
				$sheet->setCellValue('A'.$i,$group['name']);
	    	}
	    	
			foreach ($group['items'] as $item) {
				// Контент
				$i++;
				$sheet->getRowDimension($i)->setRowHeight(130);
				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT
			        ),
			        'font' => array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => false,
						'size' => $settings['config']['presentation']['fontcolsize'],
						'color' => array('rgb' => '000000')
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => 'FFFFFF'
				        )
				    )
			        
		   		 );
				$sheet->getStyle('A'.$i)->applyFromArray($style);
				$sheet->setCellValue('A'.$i,$item['code']);

				if(isset($item['images'][1])){
					$imgpath = $item['images'][1];
					if(!WINDOWS) $imgpath  = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("A".$i);	
					$objDrawing->setWidth(70);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
				}

				

				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT
			        ),
			        'font' => array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => false,
						'size' => $settings['config']['presentation']['fontcolsize'],
						'color' => array('rgb' => '000000')
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => 'FFFFFF'
				        )
				    )
			        
		   		 );
				$sheet->getStyle('B'.$i)->applyFromArray($style);

				$sheet->setCellValue('B'.$i,$item['art']);

				if(isset($item['images'][2])){
					$imgpath = $item['images'][2];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("B".$i);	
					$objDrawing->setWidth(100);		
					$objDrawing->setOffsetX(30);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
				}

				
				$imgpath = $config['images'].'items/small/'.str_replace('/', '-', $item['art'].'.jpg');
				$imgpath = str_replace(' ', '_', $imgpath);
				if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
				$ip2 = $imgpath;
				if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
				
				$imageurl = 'http://gen.psfarfor.ru/showpict.php?pictname='.$item['art'];

				$err = false;
				$ip = iconv('windows-1251', 'UTF-8', $ip2);
				if($ip !== $imgpath){
					$newname = $config['images'].'items/small/'.md5($ip).'.jpg';
					l('Найдены русские буквы в имени файла ('.$ip.') Будет создана копия файла с другим именем');
					if(!file_exists($newname)){
						if (!copy($imgpath, $newname)){
						    l('Не удалось создать файл '.$newname.' Картинка не будет отрисована');
						    $err = false;
						} else {
							$imgpath = $newname;
							l('Файл создан '.$newname);
						}
					} else {
						$imgpath = $newname;
						l('Копия не потребовалась');
					}
				} else {
					// l($ip . ' - русских букв нет');
				}
				if(file_exists($imgpath)&&!$err){
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("C".$i);				
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(5);
					$objDrawing->setHeight(150);
					$objDrawing->setWorksheet($sheet);
				}
					

				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
			            'wrap' => false,
			            'shrinkToFit'	=> true
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT
			        ),
			        'font' => array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => true,
						'size' => $settings['config']['presentation']['fontcolsize'],
						'color' => array('rgb' => 'B06D60')
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => 'FFFFFF'
				        )
				    )
			        
		   		 );
				$sheet->getStyle('C'.$i)->applyFromArray($style);


				$sheet->getCell('C'.$i)->getHyperlink()->setUrl($imageurl);
				$sheet->setCellValue('C'.$i,'Нажмите чтобы увеличить');

				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_JUSTIFY,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT
			        ),
			        'font' => array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => false,
						'size' => $settings['config']['presentation']['fontcolsize'],
						'color' => array('rgb' => '000000')
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => 'FFFFFF'
				        )
				    )
			        
		   		 );

				$desc = $item['desc'];
				if(isset($item['sudesc'])){
					$htmlHelper = new \PHPExcel_Helper_HTML();
					$html = "<p><b><font face=\"".$settings['config']['presentation']['fontcolname']."\" color=\"#".$settings['config']['presentation']['opfontcolor']."\" size=\"".$settings['config']['presentation']['opfontsize']."\">".$item['sudesc']."</font></b></p><font face=\"".$settings['config']['presentation']['fontcolname']."\" color=\"#000000\" size=\"".$settings['config']['presentation']['fontcolsize']."\">".$desc."</font>";
					$desc = $htmlHelper->toRichTextObject(mb_convert_encoding(html_entity_decode($html),'HTML-ENTITIES', 'UTF-8'));
				}
				$sheet->setCellValue('D'.$i,$desc);
				$sheet->getStyle('D'.$i)->applyFromArray($style);

				if(isset($item['images'][4])){
					$imgpath = $item['images'][4];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("D".$i);	
					$objDrawing->setWidth(70);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
				}


				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
			        ),
			        'font' => array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => false,
						'size' => $settings['config']['presentation']['fontcolsize'],
						'color' => array('rgb' => '000000')
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => 'FFFFFF'
				        )
				    )
			        
		   		 );
				$sheet->getStyle('E'.$i)->applyFromArray($style);

				$sheet->setCellValueExplicit('E'.$i,$item['vup'],PHPExcel_Cell_DataType::TYPE_NUMERIC);

				if(isset($item['images'][5])){
					$imgpath = $item['images'][5];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("E".$i);	
					$objDrawing->setWidth(70);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
				}
				if($p['ismark']=='true'){
					$y = 'fo';
				} else {
					$y = 'f';
				}
				if($settings['config']['presentation']['col'.$y.'bold'] == 'true'){
					$bold = true;
				} else {
					$bold = false;
				}
				$font = array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => $bold,
						'size' => $settings['config']['presentation']['col'.$y.'size'],
						'color' => array('rgb' => $settings['config']['presentation']['col'.$y.'text'])
				);

				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
			        ),
			        'font' => $font,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => $settings['config']['presentation']['col'.$y.'back']
				        )
				    )
			        
		   		 );
				$sheet->getStyle('F'.$i)->applyFromArray($style);

				if(isset($p['ispk'])){
					// Последняя коробка
					$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->setCellValue('F'.$i,$item['price']);
				} else if($p['ptype'] == 1){
					// Спец цена
					$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->setCellValue('F'.$i, $item['price']);

				} else if(isset($item['price_old'])){
					// Необходимо изменить цену
					// Базовая цена
					if($item['cbase'] == 'default'){
						// Брать из Price
						$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue('F'.$i,$item['price']);
					} else if($item['cbase'] == 'minus') {
						// Price минус %
						$new_price = number_format($item['price_old'] - ($item['price_old'] / $item['cbaseval']), 2, ',', '');
						$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue('F'.$i,$new_price);
					}  else if($item['cbase'] == 'excel') {
						// Брать из Excel
						$new_price = number_format($item['excelprice'], 2, ',', '');
						$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue('F'.$i,$new_price);
					} else {
						// Если что то непонятное в cbase
						$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValue('F'.$i, $item['price']);
					}
				} else {
					// стоковый вариант
					$sheet->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->setCellValue('F'.$i, $item['price']);
				}


				if(isset($item['images'][6])){
					$imgpath = $item['images'][6];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("F".$i);	
					$objDrawing->setWidth(70);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
				}

				if($p['ismark']=='true'){
					$y = 'go';
				} else {
					$y = 'g';
				}
				if($settings['config']['presentation']['col'.$y.'bold'] == 'true'){
					$bold = true;
				} else {
					$bold = false;
				}
				$font = array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => $bold,
						'size' => $settings['config']['presentation']['col'.$y.'size'],
						'color' => array('rgb' => $settings['config']['presentation']['col'.$y.'text'])
				);
				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
			        ),
			        'font' => $font,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => $settings['config']['presentation']['col'.$y.'back']
				        )
				    )
			        
		   		 );
				$sheet->getStyle('G'.$i)->applyFromArray($style);

				if(isset($p['ispk'])){
					// Последняя коробка
					$sheet->setCellValueExplicit('G'.$i,'=(F'.$i.'*40)/100',PHPExcel_Cell_DataType::TYPE_FORMULA);
				} else if($p['ptype'] == 1){
					// Спец цена
					$sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->setCellValue('G'.$i, $item['spec1']);

				} else if(isset($item['price_old'])){
					// Необходимо изменить цену
					// Ваша цена
					if($item['cprice'] == 'default'){
						// Из базовой
						$sheet->setCellValueExplicit('G'.$i,'=F'.$i.'-(F'.$i.'*J5/100)',PHPExcel_Cell_DataType::TYPE_FORMULA);
					} else if($item['cprice'] == 'fixbase') {
						// Фикс из базовой
						$sheet->setCellValueExplicit('G'.$i,'=F'.$i,PHPExcel_Cell_DataType::TYPE_FORMULA);
					} else if($item['cprice'] == 'minus') {
						// Базовая минус %
						$sheet->setCellValueExplicit('G'.$i,'=((F'.$i.'-(F'.$i.'*J5/100))*(100-'.$item['cpriceval'].'))/100',PHPExcel_Cell_DataType::TYPE_FORMULA);
					} else if($item['cprice'] == 'fixminus') {
						// Фикс базовая минус %
						$sheet->setCellValueExplicit('G'.$i,'=(F'.$i.'*(100-'.$item['cpriceval'].'))/100',PHPExcel_Cell_DataType::TYPE_FORMULA);
					} else if($item['cprice'] == 'excel') {
						// Фикс из Excel
						$new_price = number_format($item['excelprice'], 2, ',', '');
						$sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#,##0');
						$sheet->setCellValueExplicit('G'.$i,$new_price);
					} else {
						// Если что то непонятное в cprice
						$sheet->setCellValueExplicit('G'.$i,'=F'.$i.'-(F'.$i.'*J5/100)',PHPExcel_Cell_DataType::TYPE_FORMULA);
					}
				} else {
					// Стоковый вариант
					$sheet->setCellValueExplicit('G'.$i,'=F'.$i.'-(F'.$i.'*J5/100)',PHPExcel_Cell_DataType::TYPE_FORMULA);
				}

				if(isset($item['images'][7])){
					$imgpath = $item['images'][7];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("G".$i);	
					$objDrawing->setWidth(70);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
					unset($objDrawing);
				}

				if($p['ismark']=='true'){
					$y = 'ho';
				} else {
					$y = 'h';
				}
				if($settings['config']['presentation']['col'.$y.'bold'] == 'true'){
					$bold = true;
				} else {
					$bold = false;
				}
				$font = array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => $bold,
						'size' => $settings['config']['presentation']['col'.$y.'size'],
						'color' => array('rgb' => $settings['config']['presentation']['col'.$y.'text'])
				);

				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
			        ),
			        'font' => $font,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => $settings['config']['presentation']['col'.$y.'back']
				        )
				    )
			        
		   		 );
				$sheet->getStyle('H'.$i)->applyFromArray($style);

				if(isset($p['ispk'])){
					// Последняя коробка
					$sheet->setCellValueExplicit('H'.$i,'=G'.$i,PHPExcel_Cell_DataType::TYPE_FORMULA);
				} else if($p['ptype'] == 1){
					// Спец цена
					$sheet->getStyle('H'.$i)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->setCellValue('H'.$i, $item['spec2']);

				} else if(isset($item['price_old'])){
					// Необходимо изменить цену
					// Предоплата
					if($item['cpreorder'] == 'default'){
						// Стандартная формула
						$sheet->setCellValueExplicit('H'.$i,'=F'.$i.'-(F'.$i.'*J3/100)',PHPExcel_Cell_DataType::TYPE_FORMULA);
					} else if($item['cpreorder'] == 'fixprice') {
						// Фикс из Ваша цена
						$sheet->setCellValueExplicit('H'.$i,'=G'.$i,PHPExcel_Cell_DataType::TYPE_FORMULA);
					} else if($item['cpreorder'] == 'fixminus') {
						// Фикс Ваша цена минус %
						$sheet->setCellValueExplicit('H'.$i,'=(G'.$i.'*(100-'.$item['cpreorderval'].'))/100',PHPExcel_Cell_DataType::TYPE_FORMULA);
					} else {
						// Если что то непонятное в cpreorder
						$sheet->setCellValueExplicit('H'.$i,'=F'.$i.'-(F'.$i.'*J3/100)',PHPExcel_Cell_DataType::TYPE_FORMULA);
					}
				} else {
					// Стоковый вариант
					$sheet->setCellValueExplicit('H'.$i,'=F'.$i.'-(F'.$i.'*J3/100)',PHPExcel_Cell_DataType::TYPE_FORMULA);
				}

				if(isset($item['images'][8])){
					$imgpath = $item['images'][8];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("H".$i);	
					$objDrawing->setWidth(70);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
					unset($objDrawing);
				}

				if($p['ismark']=='true'){
					$y = 'io';
				} else {
					$y = 'i';
				}
				if($settings['config']['presentation']['col'.$y.'bold'] == 'true'){
					$bold = true;
				} else {
					$bold = false;
				}
				$font = array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => $bold,
						'size' => $settings['config']['presentation']['col'.$y.'size'],
						'color' => array('rgb' => $settings['config']['presentation']['col'.$y.'text'])
				);

				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
			        ),
			        'font' => $font,
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => $settings['config']['presentation']['col'.$y.'back']
				        )
				    )
			        
		   		 );
				$sheet->getStyle('I'.$i)->applyFromArray($style);

				$sheet->setCellValue('I'.$i,$item['count'].' шт.');

				if(isset($item['images'][9])){
					$imgpath = $item['images'][9];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("I".$i);	
					$objDrawing->setWidth(70);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
					unset($objDrawing);
				}

				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
			        ),
			        'font' => array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => true,
						'size' => $settings['config']['presentation']['fontcolsize'],
						'color' => array('rgb' => '000000')
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => 'FFFFFF'
				        )
				    )
			        
		   		 );
				$sheet->getStyle('J'.$i)->applyFromArray($style);

				$sheet->setCellValue('J'.$i,0);

				if(isset($item['images'][10])){
					$imgpath = $item['images'][10];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("J".$i);	
					$objDrawing->setWidth(70);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
				}

				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
			        ),
			        'font' => array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => false,
						'size' => $settings['config']['presentation']['fontcolsize'],
						'color' => array('rgb' => '000000')
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => 'FFFFFF'
				        )
				    )
			        
		   		 );
				$sheet->getStyle('K'.$i)->applyFromArray($style);

				$sheet->setCellValue('K'.$i,'=G'.$i.'*J'.$i);

				if(isset($item['images'][11])){
					$imgpath = $item['images'][11];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates("K".$i);	
					$objDrawing->setWidth(90);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
				}

				$style = array(
			        'alignment' => array(
			            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
			            'wrap' => true,
			            'shrinkToFit'	=> false
			        ),
			        'borders' => $_border_small,
			        'numberformat' => array(
			        	'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT
			        ),
			        'font' => array(
			        	'name' => $settings['config']['presentation']['fontcolname'],
						'bold' => false,
						'size' => $settings['config']['presentation']['fontcolsize'],
						'color' => array('rgb' => '000000')
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
			        	'startcolor' => array(
				             'rgb' => 'FFFFFF'
				        )
				    )
			        
		   		 );
				$sheet->getStyle('L'.$i)->applyFromArray($style);
				$sheet->setCellValueExplicit('L'.$i,$item['ean'],PHPExcel_Cell_DataType::TYPE_STRING);

				if(isset($item['images'][12])){
					$imgpath = $item['images'][12];
					if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
					if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					$objDrawing->setPath($imgpath);
					$objDrawing->setCoordinates('L'.$i);	
					$objDrawing->setWidth(90);		
					$objDrawing->setOffsetX(5);
					$objDrawing->setOffsetY(40);
					$objDrawing->setWorksheet($sheet);
				}

				if($p['ptype'] == 2){
					$style = array(
				        'alignment' => array(
				            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
				            'wrap' => true,
				            'shrinkToFit'	=> false
				        ),
				        'borders' => $_border_small,
				        'numberformat' => array(
				        	'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00
				        ),
				        'font' => array(
				        	'name' => $settings['config']['presentation']['fontcolname'],
							'bold' => true,
							'size' => $settings['config']['presentation']['fontcolsize'],
							'color' => array('rgb' => '000000')
						),
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
				        	'startcolor' => array(
					             'rgb' => 'FFFFFF'
					        )
					    )
				        
			   		 );
					$sheet->getStyle('M'.$i)->applyFromArray($style);
					$sheet->getStyle('M'.$i)->getNumberFormat()->setFormatCode('#,##0');

					$sheet->setCellValue('M'.$i, $item['zakup']);

					if(isset($item['images'][13])){
						$imgpath = $item['images'][13];
						if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
						if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('');
						$objDrawing->setDescription('');
						$objDrawing->setPath($imgpath);
						$objDrawing->setCoordinates("M".$i);	
						$objDrawing->setWidth(70);		
						$objDrawing->setOffsetX(5);
						$objDrawing->setOffsetY(40);
						$objDrawing->setWorksheet($sheet);
					}
					$sheet->getStyle('N'.$i)->applyFromArray($style);
					$sheet->getStyle('N'.$i)->getNumberFormat()->setFormatCode('#,##0');

					$sheet->setCellValue('N'.$i, $item['log']);

					if(isset($item['images'][14])){
						$imgpath = $item['images'][14];
						if(!WINDOWS) $imgpath = str_replace('\\', '/', $imgpath);
						if (WINDOWS) $imgpath = iconv('UTF-8', 'windows-1251', $imgpath);
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('');
						$objDrawing->setDescription('');
						$objDrawing->setPath($imgpath);
						$objDrawing->setCoordinates("N".$i);	
						$objDrawing->setWidth(70);		
						$objDrawing->setOffsetX(5);
						$objDrawing->setOffsetY(40);
						$objDrawing->setWorksheet($sheet);
					}
				}

			}
	    }

	    $sheet->setCellValue('K3','=SUM(K8:K'.$i.')');
	    $sheet->freezePane('N8');
	    $xls->setActiveSheetIndex(0);
	    $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
	    $path = translit($p['path']);
	    try {
	    	$objWriter->save($path);
	    	l('Файл сохранён '.$path);
	    } catch (Exception $e) {
	    	$status = 'error';
	    	l('Не удалось сохранить файл '.$path. ' Возможно он защищен от записи или открыт в Excel');
	    }
	    unset($objWriter);
	    $objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel5');
	    $path = substr($path,0,-1);
	    try {
	    	$objWriter->save($path);
	    	l('Файл сохранён '.$path);
	    } catch (Exception $e) {
	    	$status = 'error';
	    	l('Не удалось сохранить файл '.$path. ' Возможно он защищен от записи или открыт в Excel');
	    }
		unset($xls, $sheet, $objWriter);
	}}

	print_result(array());

