<?php
global $wpdb;
$sql = "TRUNCATE TABLE " . gallery_bank_albums();
$wpdb->query($sql);

$sql = "TRUNCATE TABLE " . gallery_bank_pics();
$wpdb->query($sql);

include_once (GALLERY_BK_PLUGIN_DIR . "/lib/include_settings.php");

?>