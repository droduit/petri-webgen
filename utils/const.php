<?php
// Dossier dans lequel sont placés les fichiers générés
define("OUTPUT_DIR", "generated");
define("SCENE_DIR", OUTPUT_DIR);
// Dossier contenant les dépendances (chemin relatif depuis l'emplacement des fichiers générés)
define("DEPENDENCIES_DIR", "dependencies");
// Emplacement du fichier STAMP par défaut pour le debug
define("STAMP_FILE", "demo/debug/stamp");

$SPRITE_ATTR_TO_FETCH = array('attr', 'style', 'hover', 'eventProperties');
$CSS_STYLES = array('style', 'hover');
?>