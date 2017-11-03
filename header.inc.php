<?php
$debug = true;

// Dependencies
require_once('utils/const.php');
require_once('utils/common.php');
require_once('class/event.php');
require_once('class/scene.php');
require_once('class/sprite.php');
require_once('class/transition.php');

if(!file_exists(OUTPUT_DIR)) {
    mkdir(OUTPUT_DIR, 0777, true);
}
?>