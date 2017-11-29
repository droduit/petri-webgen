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
    global $debug_mode;
	$attrs = $scene->getArray();
	return '<!DOCTYPE HTML>
	<html lang="'.exist($attrs, 'lang', "fr-FR").'">
	<head>
		<title>'.exist($attrs, 'title', $attrs['id']).'</title>
		<meta charset="UTF-8">
		'.( exist($attrs, 'mobile', true) ? '<meta name="viewport" content="width=device-width,user-scalable=0">' : '').'
		<script src="https://code.jquery.com/jquery-1.11.3.js"></script>'.
		($debug_mode ? '<script src="../js/debug.js"></script><link rel="stylesheet" href="../css/debug.css" type="text/css">' : '').
		'<style>body { font-family: sans-serif; } a { text-decoration: none; color: black; } .clear { clear:both }</style>
		'.$scene->getCSS()
		 .$scene->getJS().'
	</head>
	<body>';
}

function getFooter() {
    global $debug_mode;
	return '</body></html>';
}

function createFile($path, $content) {
	$file = fopen($path, 'w');
	fwrite($file, $content);
	fclose($file);
}

function getFrame($scene) {
    $position = '';
    
    $currentPos = $scene->getPosition();
    $keys = array_keys($currentPos);
    
    if(!in_array('width', $keys)) {
        $currentPos['width'] = 0;
        $scene->setPosition($currentPos);
    }
    
    if(in_array('x', $keys) || in_array('y', $keys) ||
        in_array('bottom', $keys) || in_array('right', $keys) ||
        in_array('top', $keys) || in_array('left', $keys)){
        
        if(!array_key_exists('position', $currentPos)) {
            $currentPos['position'] = "absolute";
        }
        if(array_key_exists('x', $currentPos)) {
            $currentPos['left'] = addPx($currentPos['x']);
            unset($currentPos['x']);  
        }
        if(array_key_exists('y', $currentPos)) {
            $currentPos['top'] = addPx($currentPos['y']);
            unset($currentPos['y']);
        }
        $scene->setPosition($currentPos);
    }
    
    
    foreach($scene->getPosition() as $k => $v) {
        if($k == "width" || $k == "height") {
            if($v == 0) $v = "100%";
            else $v = addPx($v);
        }
        
        $position .= $k.":".$v.";";
    }
    
    $c = '<iframe scene_id="'.$scene->getId().'" frameborder="0" style="z-index:0; '.$position.'" src="'.str_replace(OUTPUT_DIR."/", '', SCENE_DIR)."/scene_".$scene->getId().'.html'.'"></iframe>';
    return $c;
}

function addPx($arg) {
    if(!preg_match('#^([0-9]{1,})(px|dp|%)$#i', $arg)) {
        return $arg."px";
    }
    return $arg;
}

?>