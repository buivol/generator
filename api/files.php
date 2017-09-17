<?php

require_once 'app.php';

print_result( array( 'input' => getFilesArr( $config['path'] ), 'images' => getFilesArr( $config['images'] ) ) );
	