$(function(){
	correctImg();
	
	$('.success, .error').css("display","none").fadeIn();
	
	$.post('processing.php', {}, function(html){
		$('.content').fadeOut("fast", function(){
			$('.content').html(html).fadeIn();
			correctImg();
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