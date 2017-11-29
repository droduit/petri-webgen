<?php 
$debug_mode = true;
include_once('header.inc.php');
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
					
				<?php if(!$debug_mode) {?>
				<img src="img/loader.svg" class="loader">
				<div style="text-align:center; margin-bottom: 15px; color: #999">Processing...</div>
    			<?php } else {
    				include_once('processing.php');
    			}?>
    		</div>
		</div>
		
		<div class="copyright">Dominique Roduit - EPFL &copy; <?= date('Y') ?></div>
		
		
		<?php if($debug && isset($_GET['reload'])) { ?>
			<script>$(function(){ document.location.href="generated/<?= $_GET['reload'] ?>.html"; });</script>
		<?php } ?>
		
	</body>
	
</html>
