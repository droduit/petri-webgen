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
	
	
});

function correctImg() {
	$('img[align-auto]').each(function(){
		$(this).css("position","absolute")
		.after('<div style="margin-right: 2px; width:'+$(this).css("width")+'; display:inline-block;"></div>')
		.removeAttr("align-auto");
	});
}