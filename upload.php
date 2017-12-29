<?php 
if(count($_FILES) == 0 && count($_POST) == 0) {
?>
<div class="form-wrapper">
	<div class="form-div">
        <form method="POST" enctype="multipart/form-data" id="form1">
        	<div style="margin-bottom:8px; color:#555" class="info">1. Sélectionnez votre réseau de Pétri au format JSON</div>
            <div class="button">Choisissez un fichier</div>
			<input type="file" style="width:0px; opacity:0" />
        </form>
        
        <form method="post" id="form2" style="display:none">
        	<div class="success">Réseau de Pétri importé avec succès !</div>
        	<div style="margin-bottom:8px; color:#555" class="info">2. Sélectionnez l'emprunte numérique STAMP</div>
            <div class="button">Choisissez un fichier</div>
			<input type="file" style="width:0px; opacity:0" />
        </form>
    </div>
    
    <div class="modal">
   		<img src="img/loader.svg" style="width:40px; margin-top: 7px">
    </div>
</div>

<div class="res"></div>

<script>
$(document).ready(function () {
	$("#form1 > .info").click(function(){
        $('#form1 input[type="file"]').click();
    });

	$(document).on('drop dragover', function(e) {
		$('#form1 .info').html("Le drag and drop n'est pas pris en charge.<br>Sélectionne le fichier à partir du bouton ci-dessous");
		e.preventDefault();
	});

	$('.button').click(function(){
		$(this).parent('form').find('input[type=file]').click();
	});
    
	$('#form1 input[type="file"]').change(function(event){
        event.preventDefault();

        file = event.target.files[0];
        
        var data = new FormData();        

		console.log("file", file);

        if(!file.name.match('json')) {              
            $("#form1 .info").html("Veuillez sélectionner un fichier JSON");
        }else if(file.size > 1.5e7){
            $("#form1 .info").html("Taille maximale de fichier : 10 MB");
        }else{
        	$('.modal').fadeIn();
        	
            //append the uploadable file to FormData object
            data.append('file', file, file.name);
            
            //create a new XMLHttpRequest
            var xhr = new XMLHttpRequest();     
            
            //post file data for upload
            xhr.open('POST', 'upload.php', true);  
            xhr.send(data);
            xhr.onload = function () {
                //get response and show the uploading status
                var response = JSON.parse(xhr.responseText);
                if(xhr.status === 200 && response.status == 'ok'){

                	$('#form1').fadeOut();
                	$('#form2').fadeIn();

                }else if(response.status == 'type_err'){
                    $("#form1 .info").html("Veuillez sélectionner un fichier JSON");
                }else{
                    $("#form1 .info").html("Un problème s'est produit. Veuillez réésayer");
                }
                
                $('.modal').fadeOut();
            };
        }
        
    });



	$('#form2 input[type="file"]').change(function(event){
        event.preventDefault();
        file = event.target.files[0];
        var data = new FormData();        

        if(file.size > 400){
            $("#form2 .info").html("Taille maximale de fichier : 400 octets");
        }else{
        	$('.modal').fadeIn();
        	
            data.append('file', file, file.name);

            var xhr = new XMLHttpRequest();     
            
            xhr.open('POST', 'uploadStamp.php', true);  
            xhr.send(data);
            xhr.onload = function () {
                var response = JSON.parse(xhr.responseText);
                if(xhr.status === 200 && response.status == 'ok'){

					<?php if(!$mdpRequired) {?>
						loadProcessing();
					<?php } else { ?>
    					pwdProcess();
        			<?php } ?>
        			
                } else{
                    $("#form2 .info").html("Un problème s'est produit. Veuillez réésayer");
                }

                $('.modal').fadeOut();
            };
        }
        
    });
});

function pwdProcess() {
	$('.modal-layer').fadeIn(500);
	$('.modal-win').toggle('clip');

	$('.bt-ok').bind('click', function(){
		var val = $('input#pwd').val();
		if(val.length > 0) {
			$.post('upload.php', {
				pwd: val
			}, function(response){
				var res = JSON.parse(response);
				$('input#pwd').val("");
				if(res.status == "ok") {
					$('.modal-layer').fadeOut(500);
					$('.modal-win').toggle('clip');
					$('.bt-ok').unbind("click");
					$('.modal-win input[type=password]').val("").unbind('keyup');
					loadProcessing();
				} else {
					$('.result').html("Mot de passe faux!");
				}
			
			});
		}
	});
	
	$('.modal-win input[type=password]').focus().bind('keyup', function(key){
		if(key.which == 13) 
			$('.bt-ok').trigger("click");
	});
}
</script>

<style>
.form-wrapper {
    position: relative;
    padding: 12px;
}
form {
    text-align:center;
}
.modal {
    display:none;
    background: rgba(255,255,255,0.95);
    position: absolute;
    left:0;
    top:0;
    width: 100%;
    height: 100%;
    text-align:center;
}
</style>

<?php
} else {
    include_once('header.inc.php');
    
    if(isset($_POST['pwd'])) {
        $res['status'] = "err";
        
        $json_filename = $_SESSION['file'];
        $json = file_get_contents($json_filename);  
        $petri = json_decode($json, true);
        
        if(hashPwd($_POST['pwd']) == getUserPwdHash()) {
            $res['status'] = "ok";
            $_SESSION['pwd'] = hashPwd($_POST['pwd']);
        }

        echo json_encode($res);
    } else {
        //generate unique file name
        $fileName = time().'_'.basename($_FILES["file"]["name"]);
        
        //file upload path
        $targetDir = "uploads/";
        $targetFilePath = $targetDir . $fileName;
        
        
        //allow certain file formats
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        $allowTypes = array('json');
        
        if(in_array($fileType, $allowTypes)){
            //upload file to server
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                $response['status'] = 'ok';
                $_SESSION['file'] = $targetFilePath;
                $response['file'] = $_SESSION['file'];
            } else{
                $response['status'] = 'err';
            }
        }else{
            $response['status'] = 'type_err';
        }

       echo json_encode($response);
    }
}
?>