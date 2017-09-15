<?php
	require_once 'app.php';
	if($settings['config']['debug']!='true'){
		removeDirectory('../temp');
	}
	print_result(array());