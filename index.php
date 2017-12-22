<?php 
include_once('header.inc.php');
$debug_mode = false;
$mdpRequired = true;

if(isset($_POST['newUpload']) || $debug_mode) {
    unset($_SESSION['file']);
    unset($_SESSION['pwd']);
    unset($_SESSION['stamp']);
}

if(count($_POST) == 0) {?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Petri modelisation to website converter</title>
		<meta charset="UTF-8">
		<script src="js/jquery-3.2.1.min.js"></script>
		<script src="js/common.js" mdpRequired="<?= $mdpRequired ? 1 : 0 ?>" debug_mode="<?= $debug_mode ? 1 : 0 ?>"></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	
	<body>

		<div class="wrapper">
			<div class="title">Petri <img src="img/arrow-r.svg" width="16px" align-auto> Website</div>
			
			<div class="content">
				
				<div id="loading" style="display:none">
    				<img src="img/loader.svg" class="loader">
    				<div style="text-align:center; margin-bottom: 15px; color: #999">Processing...</div>
        		</div>
        		
        		<?php
        		if(!isset($_SESSION['pwd']) && !$debug_mode) {
				    include_once('upload.php');
				} else {
    				if($debug_mode) {
        				include_once('processing.php');
        			} else { ?>
        			<script>$(function(){ loadProcessing(); });</script>
        			<?php }?>
        			
        		<?php 	
			    }?>

    		</div>
		</div>
		
		<div class="copyright">Dominique Roduit - EPFL &copy; <?= date('Y') ?></div>
		
		
		<?php if($debug_mode) { ?>
		<div class="debug-area">
			<div class="debug-message">Debug mode</div>
			<div class="debug-tools">
				<div class="item gen-stamp" json_filename="<?= $json_filename ?>">Generate new stamp</div>
			</div>
		</div>
		<?php } ?>
		
		<div class="modal-layer"></div>
		
		<div class="modal-win">
			<div class="content">
				<div style="text-align:center">
				Veuillez entrer le mot de passe<br>
				<input type="password" id="pwd" style="width:90%; text-align:center" />
				<div class="result"></div>
				</div>
			</div>
			<div class="bt-ok">Ok</div>
		</div>
	</body>
	
</html>
<?php
} else {
    if(isset($_POST['genStamp'])) {
        if(isJSON($_POST['json_filename'])) {
            genStamp($_POST['json_filename'], $_POST['pwd']);
            echo 'New stamp succesfuly created';
        } else {
            echo "Le fichier à parser n'est pas un fichier JSON";
        }
    }
}
?>
