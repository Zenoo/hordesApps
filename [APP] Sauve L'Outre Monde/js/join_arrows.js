$('document').ready(function(){
	$('#arrowright').click(function(){
		
		var commNumber = parseInt($('#comms img').attr('src').match(/\d+/));
		
		//Fin
		if(commNumber == 4){
			$('#comms img').attr('src', 'img/comm'+(commNumber+1)+'.png');
			$('#arrowright').hide();
			$('#certifbtn').show();
		}
		//Début
		else if(commNumber == 1){
			$('#comms img').attr('src', 'img/comm'+(commNumber+1)+'.png');
			$('#arrowleft').show();
		}
		else{
			$('#comms img').attr('src', 'img/comm'+(commNumber+1)+'.png');
		}
	});
	
	$('#arrowleft').click(function(){
		
		var commNumber = parseInt($('#comms img').attr('src').match(/\d+/));
		
		//Fin
		if(commNumber == 2){
			$('#comms img').attr('src', 'img/comm'+(commNumber-1)+'.png');
			$('#arrowleft').hide();
		}
		//Début
		else if(commNumber == 5){
			$('#comms img').attr('src', 'img/comm'+(commNumber-1)+'.png');
			$('#arrowright').show();
			$('#certifbtn').hide();
		}
		else{
			$('#comms img').attr('src', 'img/comm'+(commNumber-1)+'.png');
		}
	});
	
	$('#certifbtn').click(function(){
		var pseudo = $('#certifname').text();
		console.log('test '+pseudo);
		$.ajax({
			type: 'POST',
			url: 'adhere.php',
			data: {name:pseudo}
		});
		window.open('http://zenoo.fr/uploaded/hordes/comm.pdf', '_blank');
		
		$('#joincatch').hide();
		$('#join').hide();
		$('#comms img').hide();
		$('#certifbtn').hide();
		$('#arrowleft').hide();
		$('#arrowright').hide();
		
		$('#certif').show();
		$('#certifname').show();
		
	});
});