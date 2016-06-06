$('document').ready(function(){
	
	function sleep(milliseconds) {
		var start = new Date().getTime();
			for (var i = 0; i < 1e7; i++) {
			if ((new Date().getTime() - start) > milliseconds){
				break;
			}
		}
	}
	
	function getQueryVariable(variable, url)
	{
		var query = url.substring(url.indexOf("?") + 1);
		var vars = query.split("&");
		for (var i=0;i<vars.length;i++) {
			var pair = vars[i].split("=");
			if(pair[0] == variable){return pair[1];}
		}
		return(false);
	}
	
	$('#triggerChecking').click(function(){
		$('.opbutton').toggle();
	});
	
	$('.opbutton').click(function(e) {
		var url = $(this).parent().attr('onclick');
		url = url.substring(0, url.length - 1);
		var dataX = +getQueryVariable('x', url);
		var dataY = +getQueryVariable('y', url);
		var dataMap = $('#cityID').text();
		dataMap = +dataMap.substring(dataMap.indexOf(" ") + 1);

		var check= 0;
		if($(this).children().first().is(':checked')){
			check = 1;
		}
		
		$.ajax({
			type: 'POST',
			url: 'process.php',
			data: {x: dataX, y: dataY, map: dataMap, checked: check},
			success: function() {
				console.log('x:'+dataX);
				console.log('y:'+dataY);
				console.log('map:'+dataMap);
				console.log('checked:'+check);
				location.reload();
			}
		});
	});
});