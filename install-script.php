<?php
global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
if (count($wpdb->get_var('SHOW TABLES LIKE "' . gallery_bank_albums() . '"')) == 0)
{
	$sql = 'CREATE TABLE ' . gallery_bank_albums() . '(
	album_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	album_name VARCHAR(100) NOT NULL,
	author VARCHAR(100) NOT NULL,
	album_date DATE,
	description TEXT NOT NULL,
	image_width INTEGER(5) UNSIGNED NOT NULL,
	image_height INTEGER(5) UNSIGNED NOT NULL,
	border_enable BIT NOT NULL,
	border_width INTEGER(5) UNSIGNED NOT NULL,
	border_color VARCHAR(10) NOT NULL,
	images_in_row INTEGER(5) UNSIGNED NOT NULL,
	slideshow BIT NOT NULL,
	slideshow_interval INTEGER(5) UNSIGNED NOT NULL,
	PRIMARY KEY (album_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
	dbDelta($sql);
}
if (count($wpdb->get_var('SHOW TABLES LIKE "' . gallery_bank_pics() . '"')) == 0)
{
	$sql = 'CREATE TABLE ' .gallery_bank_pics() . '(
	pic_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	album_id INTEGER(10) UNSIGNED NOT NULL,
	pic_path TEXT NOT NULL,
	title VARCHAR(100) NOT NULL,
	album_cover BIT NOT NULL,
	description TEXT NOT NULL,
	thumbnail_url TEXT NOT NULL,
	date DATE,
	PRIMARY KEY (pic_id)		 
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
	dbDelta($sql);
}
if (count($wpdb->get_var('SHOW TABLES LIKE "' . gallery_bank_albums() . '"')) != 0)
{
	$check = $wpdb->get_var
	(
		$wpdb->prepare
		(
			"SHOW COLUMNS FROM " . gallery_bank_albums() . " LIKE 'number_of_pics'",""
		)
	);
	if($check != "")
	{
		$wpdb->query
		(
			$wpdb->prepare
			(
				"ALTER TABLE " . gallery_bank_albums() . " DROP number_of_pics",""
			)
		);
	}
}
if (count($wpdb->get_var('SHOW TABLES LIKE "' . gallery_bank_pics() . '"')) != 0)
{
	$check = $wpdb->get_var
	(
		$wpdb->prepare
		(
			"SHOW COLUMNS FROM " . gallery_bank_pics() . " LIKE 'album_cover'",""
		)
	);
	if($check != "album_cover")
	{
		$wpdb->query
		(
			$wpdb->prepare
			(
				"ALTER TABLE " . gallery_bank_pics() . " ADD album_cover BIT NOT NULL",""
			)
		);
	}
}
?>