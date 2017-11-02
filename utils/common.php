<?php
function getListDir($dir) {
	$f = array();

	if($dir = opendir($dir)) {
		while(false !== ($file = readdir($dir))) {
			if($file != '.' && $file != '..')
				$f[] = $file;
		}
	}
	return $f;
}

function debug($o) {
	echo '<hr><pre>';
	print_r($o);
	echo'</pre><hr><br>';
}

function getSpriteName($id) {
	return 's'.$id;
}

function exist($array, $attr, $otherwise) {
	return isset($array[$attr]) ?  $array[$attr] : $otherwise;
}

function getHeader($scene) {
    global $debug;
	$attrs = $scene->getArray();
	return '<!DOCTYPE HTML>
	<html lang="'.exist($attrs, 'lang', "fr-FR").'">
	<head>
		<title>'.exist($attrs, 'title', $attrs['id']).'</title>
		<meta charset="UTF-8">
		'.( exist($attrs, 'mobile', true) ? '<meta name="viewport" content="width=device-width,user-scalable=0">' : '').'
		<script src="https://code.jquery.com/jquery-1.11.3.js"></script>'.
		($debug ? '<script src="../js/debug.js"></script><link rel="stylesheet" href="../css/debug.css" type="text/css">' : '').
		'<style>body { font-family: sans-serif; } a { text-decoration: none; color: black; }</style>
		'.$scene->getCSS()
		 .$scene->getJS().'
	</head>
	<body>';
}

function getFooter() {
	global $debug;
	return '</body></html>';
}

function createFile($content, $name) {
	$file = fopen(OUTPUT_DIR.$name.'.html', 'w');
	fwrite($file, $content);
	fclose($file);
}

?>