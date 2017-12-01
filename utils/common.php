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
	if(is_null($o))
	    $o = "NULL";
	
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

function getDefaultDependencies() {
    $path = DEPENDENCIES_DIR;
    
    global $dependencies;
    $userDep = $dependencies;
    
    $js = array();
    array_push($js, 'jquery-3.2.1.min.js');
    array_push($js, 'jquery-ui.min.js');
    $js = array_merge($js, $userDep['js']);
    
    $css = array();
    array_push($css, 'reset.css');
    array_push($css, 'jquery-ui.min.css');
    array_push($css, 'jquery-ui.structure.min.css');
    array_push($css, 'jquery-ui.theme.min.css');
    $css = array_merge($css, $userDep['css']);
    
    $depHTML = "";
    foreach($js as $src) {
        $depHTML .= '<script src="'.$path.'/js/'.$src.'"></script>';
    }
    foreach($css as $src) {
        $depHTML .= '<link rel="stylesheet" href="'.$path.'/css/'.$src.'" type="text/css">';
    }
    return $depHTML;
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
		'.( exist($attrs, 'mobile', true) ? '<meta name="viewport" content="width=device-width,user-scalable=0">' : '')
		.getDefaultDependencies().
		'<script>var isInView = $("iframe[petri]", parent.document).size() > 0;</script>'.
		($debug_mode ? '<script src="../js/debug.js"></script><link rel="stylesheet" href="../css/debug.css" type="text/css">' : '').
		$scene->getCSS()
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
		<meta name="viewport" content="width=device-width,user-scalable=0">'
		.getDefaultDependencies().
		($debug_mode ? '<script src="../js/debug.js"></script><link rel="stylesheet" href="../css/debug.css" type="text/css">' : '').
		'<style>body { margin:0; font-family: sans-serif; } a { text-decoration: none; color: black; } .clear { clear:both }</style>
	</head>
	<body>';
}

/**
 * @param ($index['type'], $index['id']) : Type et id de l'index
 * @return Contenu de la page index
 */
function getContentIndex($index) {
    global $debug_mode; 

    $filename = ($index['type'] == 'scene') ? getSceneFilename($index['id']) : getViewFilename($index['id']);
    
    return '<!DOCTYPE HTML>
	<html>
	<head>
		<title>Chargement...</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,user-scalable=0">'
		.getDefaultDependencies().
		($debug_mode ? '<script src="../js/debug.js"></script><link rel="stylesheet" href="../css/debug.css" type="text/css">' : '').
		'<style>body { margin:0; font-family: sans-serif; } a { text-decoration: none; color: black; } .clear { clear:both }</style>
        <script>
        $(function(){
            $.post("'.$filename.'", {index:1}, function(html){
                $("body").html(html);
            }); 
        });
        </script>
	</head>
    	<body>
            <div style="text-align:center; margin-top: 250px">Chargement ...</div>
        </body>
    </html>';
}

/**
 * @return Footer d'une page HTML générée
 */
function getFooter() {
    global $debug_mode;
	return '</body></html>';
}

/**
 * Créé un fichier sur le disque à l'emplacement $path, avec le contenu $content
 * @param $path Emplacement du fichier
 * @param $content Contenu du fichier
 */
function createFile($path, $content) {
	$file = fopen($path, 'w');
	fwrite($file, $content);
	fclose($file);
}

/**
 * Formatte une chaine pour être utilisée en CSS.
 * Si l'argument passé ne se termine pas par px, dp ou %,
 * alors la valeur par défaut sera exprimée en px
 * @param (String out int) $arg : Argument css à formatter
 * @return Argument exprimé en pixels si pas de mesure précisée
 */
function addPx($arg) {
    if(!preg_match('#^([0-9]{1,})(px|dp|%)$#i', $arg)) {
        return $arg."px";
    }
    return $arg;
}

/**
 * @param (String) $sceneId : Id de la scene dont on veut le nom de fichier
 * @return nom de fichier pour la scène passée en paramètre
 */
function getSceneFilename($sceneId) {
    return "scene_".$sceneId.'.html';
}

/**
 * @param (String) $viewId : Id de la vue dont on veut le nom de fichier
 * @return nom de fichier pour la vue passée en paramètre
 */
function getViewFilename($viewId) {
    return "view_".$viewId.'.html';
}

?>