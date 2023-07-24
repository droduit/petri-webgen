$(function(){
	const debug_mode = $('script[debug_mode]').attr("debug_mode") == 1;
	const mdpRequired = $('script[debug_mode]').attr("mdpRequired") == 1;

	correctImg();
	
	$('.success, .error').css("display","none").fadeIn();

	if (debug_mode) {
		$('.gen-stamp').on('click', function(){
			toggleModal();
			const jsonfilename = $(this).attr("json_filename");
			
			$('.bt-ok').unbind("click").bind('click', function(){
				const val = $('input#pwd').val();
				if(val.length > 0) {
					$.post('index.php', {
						genStamp:1,
						json_filename: jsonfilename,
						pwd: val
					}, function(html){
						$('input#pwd').val("");
						$('.result').html(html);
						if(html != '-1') {
							setTimeout(function(){ toggleModal(); }, 1000);
						}
					});
				}
			});
		});
	}
	
	$('body').on('click', 'a[type]', function(){
		const type = $(this).attr("type");
		$('.titleCat').html(type);
		$('ul.files a').fadeOut("fast", function(){
			setTimeout(function(){
				$('a[typefile="'+type+'"], .bt-back, .titleCat').fadeIn("fast");
			}, 200);
		});
		return false;
	});
	
	$('body').on('click', '.bt-back', function(){
		$('ul.files a, .bt-back, .titleCat').fadeOut("fast", function(){
			setTimeout(function(){
				$('a[type]').fadeIn();
			}, 200);
		});
	});
	
	$('body').on('click', '.button.export', function(){
		var debug_mode = $('script[debug_mode]').attr("debug_mode");
		$.post('create-zip.php', { debug: debug_mode }, function(res){
			var response = JSON.parse(res);
			if(response.status == "ok") {
				document.location = response.file;
			} else {
				showMessage('Problème lors de la création de l\'archive. Réésayer!');
			}
		});
	});
	
});

function showMessage(message) {
	$('.message').html(message).slideDown();
	setTimeout(function(){ $('.message').slideUp(); }, 2000);
}

function loadProcessing() {
	var debug_mode = $('script[debug_mode]').attr("debug_mode");
	var mdpRequired = $('script[debug_mode]').attr("mdpRequired");
	
	$('#loading').css("display","block");
	$.post('processing.php', {
		debug: debug_mode,
		mdpNeeded: mdpRequired
	}, function(html){
		$('.content').fadeOut("fast", function(){
			$('.content').html(html).fadeIn();
			correctImg();
		});
	}).fail(function(jqXHR, err){
		$('.content').fadeOut("fast", function(){
			$('.content').html('<div class="error">'+err+'</div>').fadeIn();
		});
	});
}

function correctImg() {
	$('img[align-auto]').each(function(){
		$(this).css("position","absolute")
		.after('<div style="margin-right: 2px; width:'+$(this).css("width")+'; display:inline-block;"></div>')
		.removeAttr("align-auto");
	});
}

function toggleModal() {
	$('.result').html("");
	$('.modal-layer').fadeToggle(500);
	$('.modal-win').toggle('clip');
	$('.modal-win input[type=password]').val("").unbind('keyup').focus().bind('keyup', function(key){
		switch(key.which) {
		case 13: // enter
			$('.bt-ok').trigger("click");
			break;
		case 27: // esc
			toggleModal();
			break;
			
			default:
		}
	});
	
	$('.modal-layer').unbind("click").bind('click', function(){
		toggleModal();
	});
}