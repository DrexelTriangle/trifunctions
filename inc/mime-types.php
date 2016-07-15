<?php

function custom_upload_mimes ( $mime_types ) {
	$mime_types = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
		'bmp' => 'image/bmp'
	);
	return $mime_types;
}


add_filter('upload_mimes', 'custom_upload_mimes');