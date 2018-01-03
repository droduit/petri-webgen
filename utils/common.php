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
    $userDep = is_null($dependencies) ? array() : $dependencies;
    
    $js = array();
    array_push($js, 'jquery-3.2.1.min.js');
    array_push($js, 'jquery-ui.min.js');
    if(isset($userDep['js']))
		$js = array_merge($js, $userDep['js']);
    
    $css = array();
    array_push($css, 'reset.css');
    array_push($css, 'jquery-ui.min.css');
    array_push($css, 'jquery-ui.structure.min.css');
    array_push($css, 'jquery-ui.theme.min.css');
    if(isset($userDep['css']))
		$css = array_merge($css, $userDep['css']);
    
	if(isset($userDep['libraries'])) {
		if(in_array("bootstrap", $userDep['libraries'])) {
			array_push($css, 'bootstrap/bootstrap.min.css');
			array_push($css, 'bootstrap/bootstrap-grid.min.css');
			array_push($css, 'bootstrap/bootstrap-reboot.min.css');
			array_push($js, 'bootstrap/bootstrap.min.js');
			array_push($js, 'bootstrap/bootstrap.bundle.min.js');
		}
	}
	
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
		'<script>var isInView = $("iframe[petri]", parent.document).length > 0;</script>'.
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
 * @return Le dossier dans lequel on met les fichiers générés
 */
function getDirGeneration() {
	global $debug_mode;
	return $debug_mode ? OUTPUT_DIR : OUTPUT_DIR.'/petri_'.crc32(session_id());
}

/**
 * Copy a file, or recursively copy a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function copyr($source, $dest) {
	global $dependencies;
    $userDep = is_null($dependencies) ? array() : $dependencies;
	
	$bootstrap = false;
	if(isset($userDep['libraries'])) {
		$bootstrap = in_array("bootstrap", $userDep['libraries']);
	}
	
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }
    
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }

	
	
    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..' || (!$bootstrap && $entry=='bootstrap') ) {
            continue;
        }

        // Deep copy directories
        copyr("$source/$entry", "$dest/$entry");
    }

    // Clean up
    $dir->close();
    return true;
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

function getStampFile() { 
    if(isset($_SESSION['stamp']))
        return $_SESSION['stamp'];
    return STAMP_FILE;
}
/**
 * @param (String) $filename : Nom du fichier json dont on veut le hash
 * @return empreinte du fichier json spécifié
 */
function getStampOf($filename, $hashedPwd=NULL) {
	return hash_hmac_file('sha512', $filename,
	    is_null($hashedPwd) ? getUserPwdHash() : $hashedPwd);
}
/**
 * Vérifie que le fichier stamp contienne les informations requises
 */
function checkStampSanity() {
    $tab = explode('|', file_get_contents(getStampFile()));
    $cond1 = count($tab) == 2;
    $cond2 = strlen(trim($tab[0])) == 128 && strlen(trim($tab[1])) == 128;
    return $cond1 && $cond2;
}
/**
 * @return (String) le mot de passe hashé entré par l'utilisateur
 */
function getUserPwdHash() {
    $content = file_get_contents(getStampFile());
    return trim(explode("|", $content)[1]);
}
/**
 * @return (String) l'emprunte enregistrée du fichier json de base
 */
function getSavedStamp() {
    $content = file_get_contents(getStampFile());
    return trim(explode('|', $content)[0]);
}
/**
 * Hash les mots de passes avec une fonction de hashage définie
 */
function hashPwd($pwd) {
    return hash('sha512', $pwd);  
}
/**
 * Génère un nouveau fichier stamp (uniquement a but de debug)
 * @param $filename Nom du fichier JSON
 * @param $pwd Mot de passe non crypté de l'utilisateur
 */
function genStamp($filename, $pwd) {
    $hashedPwd = hashPwd($pwd);
    createFile(STAMP_FILE, getStampOf($filename, $hashedPwd)."|".$hashedPwd);
}

function isJSON($filename) {
    $fileparts = pathinfo($filename);
    return $fileparts['extension'] == "json";
}

function getJSONErrorMessage() {
	switch (json_last_error()) {
		case JSON_ERROR_DEPTH:
			return 'Maximum stack depth exceeded';
		break;
		case JSON_ERROR_STATE_MISMATCH:
			return 'Underflow or the modes mismatch';
		break;
		case JSON_ERROR_CTRL_CHAR:
			return 'Unexpected control character found';
		break;
		case JSON_ERROR_SYNTAX:
			return 'Syntax error, malformed JSON';
		break;
		case JSON_ERROR_UTF8:
			return 'Malformed UTF-8 characters, possibly incorrectly encoded';
		break;
		default:
			return 'Unknown error';
		break;
	}
}
?>
