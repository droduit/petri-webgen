<?php
/**
 * Fourni une série de methodes communément utilisées.
 * et d'une importance assez importante pour le fonctionnement du générateur.
 * 
 * @author Dominique Roduit
 */
// ----------------------------------------------------------------------------

/**
 * Liste les fichiers contenus dans le répertoire spécifié
 * @return Une liste des nom des fichiers
 */
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

/**
 * Affiche des informations lisibles humainement à propos de l'objet passé
 * à des fins de debuggage.
 * Tout type d'objet peut être passé.
 */
function debug($o) {
	if(is_bool($o)) 
	    $o = $o ? "true" : "false";
    echo '<hr><pre>';
	print_r($o);
	echo'</pre><hr><br>';
}

/*
 * @deprecated
 * @return Le nom du sprite selon son Identifiant
 */
function getSpriteName($id) {
	return 's'.$id;
}

/**
 * @param $array Tableau key => value
 * @param $attr Clé dont on veut vérifier l'existence
 * @param $otherwise Valeur retournée si la valeur correspondante à la clé n'existe pas dans le tableau
 * @return La valeur correspondant à la clé $attr si elle existe dans le tableau $array, ou $otherwise sinon  
 */
function exist($array, $attr, $otherwise) {
	return isset($array[$attr]) ?  $array[$attr] : $otherwise;
}

/**
 *  @return Header par défaut pour les Scènes.
 * Le titre de la scene, la langue spécifiée par défaut, des meta pour mobile, 
 * et les styles et javascript de la scene sont tous inclus ici.
 * @param (Scene) $scene : La scene pour laquelle la page est créé
 */
function getHeaderScene($scene) {
    global $debug_mode;
	$attrs = $scene->getArray();
	return '<!DOCTYPE HTML>
	<html lang="'.exist($attrs, 'lang', "fr-FR").'">
	<head>
		<title>'.exist($attrs, 'title', $attrs['id']).'</title>
		<meta charset="UTF-8">
		'.( exist($attrs, 'mobile', true) ? '<meta name="viewport" content="width=device-width,user-scalable=0">' : '').'
		<script src="https://code.jquery.com/jquery-1.11.3.js"></script>'.
		'<script>var isInView = $("iframe[petri]", parent.document).size() > 0;</script>'.
		($debug_mode ? '<script src="../js/debug.js"></script><link rel="stylesheet" href="../css/debug.css" type="text/css">' : '').
		'<style>body { font-family: sans-serif; } a { text-decoration: none; color: black; } .clear { clear:both }</style>
		'.$scene->getCSS()
		 .$scene->getJS().'
	</head>
	<body>';
}

/**
 * @param (String) $title : Le titre de la page générée pour la vue
 * @return Le header par défaut pour les vues.
 */
function getHeaderView($title) {
    global $debug_mode;
    return '<!DOCTYPE HTML>
	<html>
	<head>
		<title>'.$title.'</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,user-scalable=0">
		<script src="https://code.jquery.com/jquery-1.11.3.js"></script>'.
		($debug_mode ? '<script src="../js/debug.js"></script><link rel="stylesheet" href="../css/debug.css" type="text/css">' : '').
		'<style>body { margin:0; font-family: sans-serif; } a { text-decoration: none; color: black; } .clear { clear:both }</style>
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


function addPx($arg) {
    if(!preg_match('#^([0-9]{1,})(px|dp|%)$#i', $arg)) {
        return $arg."px";
    }
    return $arg;
}

function getSceneFilename($sceneId) {
    return "scene_".$sceneId.'.html';
}

function getViewFilename($viewId) {
    return "view_".$viewId.'.html';
}

?>