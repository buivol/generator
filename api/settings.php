<?php

if ( $_GET['action'] == 'load' ) {
	$settings       = file_get_contents( '../saved.dat' );
	$settings_array = json_decode( $settings, true );
	$settings       = json_encode( $settings_array );
	die( $settings );
} else if ( $_GET['action'] == 'save' ) {
	$settings = json_encode( $_POST );
	echo $settings;
	file_put_contents( '../saved.dat', $settings );
}