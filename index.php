<?php 
include_once('header.inc.php');
$debug_mode = true;

if(count($_POST) == 0) {
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Petri modelisation to website converter</title>
		<meta charset="UTF-8">
		<script src="js/jquery-3.2.1.min.js"></script>
		<script src="js/common.js" debug_mode="<?= $debug_mode ? 1 : 0 ?>"></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	
	<body>

		<div class="wrapper">
			<div class="title">Petri <img src="img/arrow-r.svg" width="16px" align-auto> Website</div>
			
			<div class="content">
				<?php
				if(!file_exists(STAMP_FILE)) {
				    echo '<div class="error">Le fichier <b>'.STAMP_FILE.'</b> n\'existe pas</b></div>';
				} 
  
			    if(!checkStampSanity()) { ?>	
					<div class="error">Le fichier <b><?= STAMP_FILE ?></b> est malformé ou a été modifié</div>
				<?php } ?>
				
				<?php if(!$debug_mode) {?>
				<img src="img/loader.svg" class="loader">
				<div style="text-align:center; margin-bottom: 15px; color: #999">Processing...</div>
    			<?php } else {
    				include_once('processing.php');
    			} ?>
    		</div>
		</div>

		<?php if($debug_mode) { ?>
			<div class="debug-message">Debug mode</div>
			<div class="debug-tools">
				<div class="item gen-stamp" json_filename="<?= $json_filename ?>">Generate new stamp</div>
			</div>
		<?php } ?>
		
		<div class="copyright">Dominique Roduit - EPFL &copy; <?= date('Y') ?></div>
		
		
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
