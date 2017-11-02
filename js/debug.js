$(function(){
	$('body').append('<div class="debug-bar"><div class="bt home">Home</div><div class="bt reload">Live reload</div></div>')
	.append('<div class="loader-modal"><img src="../img/loader.svg" class="loader"></div>');
	
	$('.home').click(function(){
		window.location = "../";
	});
	$('.reload').click(function(){
		$('.loader-modal').fadeIn(250);
		var pathArray = window.location.pathname.split( '/' );
		$.post('../processing.php', {}, function(html){
			$('.loader-modal').fadeOut('fast', function(){
				window.location = pathArray[pathArray.length-1];
			});
		});		
	});
	
});

