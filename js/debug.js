$(function(){
	
	$('body').append('<div class="debug-bar"></div>')
	.append('<div class="loader-modal"><img src="../img/loader.svg" class="loader"></div>');
	
	if(typeof isInView == "undefined" || !isInView) {
		$('.debug-bar').append('<div class="bt home">Home</div>');
	}
	$('.debug-bar').append('<div class="bt reload">Live reload</div>');
	$('.debug-bar').append('<div class="varIsInView">IsInView : <span></span></div>');
	
	var textVar = "undefined";
	if(typeof isInView != "undefined") {
		textVar = isInView ? "true": "false";
	}  
	$('.varIsInView span').text(textVar);

	$('.home').click(function(){
		window.location = "../";
	});
	
	$('.reload').click(function(){
		$('.loader-modal').fadeIn(250);
		var pathArray = window.location.pathname.split( '/' );
		$.post('../processing.php', {debug:1}, function(html){
			$('.loader-modal').fadeOut('fast', function(){
				document.location.href = pathArray[pathArray.length-1];
			});
		});		
	});
	
});

