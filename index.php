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
		<?php if(!$debug_mode) {?><script src="js/common.js"></script><?php } ?>
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
		
		<!-- ------------------------------ 
		3 <div style="border:1px solid #ddd; border-radius: 8px; padding: 6px; margin:20px auto; float:left">
		4	<div style="float:left; margin-right: 20px">
		6		<img width="115" src="https://scontent-frx5-1.xx.fbcdn.net/v/t1.0-9/22045584_10214332095521962_2816500085493263649_n.jpg?oh=43eef7581d707362c13306aeac10e41a&oe=5A7A2AA9" />
			</div>
		5	<div style="float: left">
		7		<h2>Dominique Roduit</h2>
		8		<h5>EPFL</h5>
			</div>
			<div style="clear:both"></div>
		</div>
		
		<div style="clear:both"></div>
		 ------------------------------ -->
		
		<?php if($debug && isset($_GET['reload'])) { ?>
			<script>$(function(){ document.location.href="generated/<?= $_GET['reload'] ?>.html"; });</script>
		<?php } ?>
		
	</body>
	
</html>