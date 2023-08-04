<?php 
if(count($_FILES) > 0 || count($_POST) > 0) {
    include_once('header.inc.php');
    
    if(isset($_POST['pwd'])) {
        $res['status'] = "err";
        
        $json_filename = $_SESSION['file'];
        $json = file_get_contents($json_filename);
        $petri = json_decode($json, true);
        
        if (hashPwd($_POST['pwd']) == getUserPwdHash()) {
            $res['status'] = "ok";
            $_SESSION['pwd'] = hashPwd($_POST['pwd']);
        }

        echo json_encode($res);
    } else {
        //generate unique file name
        $fileName = time().'_'.basename($_FILES["file"]["name"]);
        
        //file upload path
        $targetDir = __DIR__ . "/uploads/";
        $targetFilePath = $targetDir . $fileName;
        
        
        //allow certain file formats
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        $allowTypes = array('json');
        
        if (in_array($fileType, $allowTypes)){
            //upload file to server
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                $response['status'] = 'ok';
                $_SESSION['file'] = $targetFilePath;
                $response['file'] = $_SESSION['file'];
            } else {
                $response['status'] = 'err';
            }
        } else{
            $response['status'] = 'type_err';
        }

       echo json_encode($response);
    }
    exit();
}
?>
<div class="form-wrapper">
	<div class="form-div">
        <form method="POST" enctype="multipart/form-data" id="form1">
        	<div class="info">Let's import your mighty Petri net and watch the magic unfold!</div>
            <div class="button">Select the JSON file</div>
			<input type="file" style="width:0px; opacity:0" />
        </form>
        
        <form method="post" id="form2" style="display:none">
        	<div class="success">Great! Your Petri net was successfully imported!</div>
        	<div class="info">Your digital fingerprint is the key to unlocking endless possibilities.<br>Show us your STAMP and take control!</div>
            <div class="button">Select the STAMP file</div>
			<input type="file" style="width:0px; opacity:0" />
        </form>
    </div>
    
    <div class="modal">
   		<img src="img/loader.svg" width="80px" height="80px" alt="Loading" />
    </div>
</div>

<div class="message" style="display:none; position:fixed; bottom:0; left:0; width:100%; padding: 10px; text-align:center; background:rgba(0,0,0,0.8); color:white; font-size:0.8em"></div>

<script>
$(document).ready(function () {
	$("form > .info").click(function(){
        $(this).parent('form').find('input[type="file"]').click();
    });

	$(document).on('drop dragover', function(e) {
		showMessage("Please select your files by clicking on the button above.");
		e.preventDefault();
	});

	$('.button').click(function(){
		$(this).parent('form').find('input[type=file]').click();
	});
    
	$('#form1 input[type="file"]').change(function(event){
        event.preventDefault();

        file = event.target.files[0];
        
        const data = new FormData();

        if (!file.name.match('json')) {
            showMessage("Please select a JSON file.");
        } else if(file.size > 1.5e7) {
            showMessage("Maximum file size: 10 MB.");
        } else{
        	$('.modal').css({ "opacity": 1, zIndex: 0 })
        	
            //append the uploadable file to FormData object
            data.append('file', file, file.name);
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload.php', true);
            xhr.send(data);
            xhr.onload = function () {
                //get response and show the uploading status
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.status == 'ok') {

                	$('#form1').fadeOut().promise().done(function() {
                        $('#form2').fadeIn();
                    });

                } else if (response.status == 'type_err') {
                    showMessage("Please select a JSON file.");
                } else {
                    showMessage("An error occurred. Please try again.");
                }
                $('.modal').css({ "opacity": 0, zIndex: -1 });
            };
        }

    });



	$('#form2 input[type="file"]').change(function(event){
        event.preventDefault();
        file = event.target.files[0];
        var data = new FormData();

        if (file.size > 400){
            showMessage("Maximum file size: 400 bytes.");
        } else{
        	$('.modal').fadeIn();
        	
            data.append('file', file, file.name);

            var xhr = new XMLHttpRequest();
            
            xhr.open('POST', 'uploadStamp.php', true);
            xhr.send(data);
            xhr.onload = function () {
                var response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.status == 'ok'){

					<?php if(!$mdpRequired) {?>
						loadProcessing();
					<?php } else { ?>
    					pwdProcess();
        			<?php } ?>
        			
                } else{
                    showMessage("An error occurred. Please try again.");
                }

                $('.modal').fadeOut();
            };
        }
        
    });
});

function pwdProcess() {
	$('#form1, #form2').remove();
	$('.form-div').html('<div style="text-align:center; padding:15px">Awaiting password</div>');
	
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
				if (res.status == "ok") {
					$('.modal-layer').fadeOut(500);
					$('.modal-win').toggle('clip');
					$('.bt-ok').unbind("click");
					$('.modal-win input[type=password]').val("").unbind('keyup');
					loadProcessing();
				} else {
					$('.result').html("Incorrect password!");
				}
			
			});
		}
	});
	
	$('.modal-win input[type=password]').focus().bind('keyup', function(key){
		if (key.which === 13)
			$('.bt-ok').trigger("click");
	});
}
</script>

<style>
.form-wrapper {
    position: relative;
    padding: 12px;
    padding-bottom: 40px;
}
form {
    text-align:center;
}
.modal {
    display: flex;
    background: rgba(255,255,255,0.95);
    position: absolute;
    left:0;
    top:0;
    width: 100%;
    height: 100%;
    text-align:center;
    align-items: center;
    justify-content: center;
    z-index: -1;
    opacity: 0;
    transition: all .6s linear;
}
</style>