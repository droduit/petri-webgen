$(function(){
	var debug_mode = $('script[debug_mode]').attr("debug_mode") == 1;

	correctImg();
	
	$('.success, .error').css("display","none").fadeIn();

	if(!debug_mode) {
		$.post('processing.php', {}, function(html){
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
	
	$('body').on('click', 'a[type]', function(){
		var type = $(this).attr("type");
		$('.titleCat').html(type+"s");
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
	
});

function correctImg() {
	$('img[align-auto]').each(function(){
		$(this).css("position","absolute")
		.after('<div style="margin-right: 2px; width:'+$(this).css("width")+'; display:inline-block;"></div>')
		.removeAttr("align-auto");
	});
}