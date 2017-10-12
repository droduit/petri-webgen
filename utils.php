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

function exist($array, $attr, $otherwise) {
	return in_array($attr, $array) ?  $array[$attr] : $otherwise;
}

function getHeader($attrs) {
	
	return '<!DOCTYPE HTML>
	<html lang="'.exist($attrs, 'lang', "fr-FR").'">
	<head>
		<title>'.exist($attrs, 'title', $attrs['id']).'</title>
		<meta charset="UTF-8">
		'.( exist($attrs, 'mobile', true) ? '<meta name="viewport" content="width=device-width,user-scalable=0">' : '').'
		<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
		<style>body { font-family: sans-serif; } a { text-decoration: none; color: black; }</style>
	</head>
	<body>';
}

function getFooter($p="") {
	global $debug;
	return ''.
	($debug ?'<div style="padding: 6px; position: fixed; bottom:0; left:0; width: 100%; background:#eee; text-align: center; box-sizing: border-box"><a href="../index.php?reload='.$p.'">Regénérer à partir du fichier XML</a></div>' : '')
	.'</body></html>';
}

function createFile($content, $name) {
	$file = fopen('generated/'.$name.'.html', 'w');
	fwrite($file, $content);
	fclose($file);
}

?>