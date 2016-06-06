$(document).ready(function() {
	
	$('.firstCheck').show();
	
	$('.creationPrompt li').click(function(){
		if(!$(this).children().eq(1).is(':visible')){
			$(this).parent().children().children().filter('.content:visible').slideUp();
			$(this).children().eq(1).slideDown();
		}
	});
	
	$('.content').click(function(){
		window.location = 'http://sauveloutremonde.zenoo.fr?p=c&id='+$(this).attr('data-id');
	});
});