$('document').ready(function(){
	$('#live').load('live.php');
	setTimeout(function (){
		$('#live').scrollTop($('#live')[0].scrollHeight);
	}, 1000);
	

	window.setInterval(function(){
	  $('#live').load('live.php');
	  $('#live').scrollTop($('#live')[0].scrollHeight);
	}, 5000);
	
	$('#smiley_info img').click(function(){
		var ref = $(this).attr('src');	
		switch(ref){
			case '../resources/forum/smiley/h_smile.gif':
				$('input#textToSend').val($('input#textToSend').val()+' :) ');
				break;
			case '../resources/forum/smiley/h_sad.gif':
				$('input#textToSend').val($('input#textToSend').val()+' :( ');
				break;
			case '../resources/forum/smiley/h_sleep.gif':
				$('input#textToSend').val($('input#textToSend').val()+' zzz ');
				break;
			case '../resources/forum/smiley/h_rage.gif':
				$('input#textToSend').val($('input#textToSend').val()+' grr ');
				break;
			case '../resources/forum/smiley/h_sick.gif':
				$('input#textToSend').val($('input#textToSend').val()+' berk ');
				break;
			case '../resources/forum/smiley/h_pa.gif':
				$('input#textToSend').val($('input#textToSend').val()+' :pa: ');
				break;
			case '../resources/forum/smiley/h_blink.gif':
				$('input#textToSend').val($('input#textToSend').val()+' ;) ');
				break;
			case '../resources/forum/smiley/h_calim.gif':
				$('input#textToSend').val($('input#textToSend').val()+' calim ');
				break;
			case '../resources/forum/smiley/h_bag.gif':
				$('input#textToSend').val($('input#textToSend').val()+' :bag: ');
				break;
			case '../resources/forum/smiley/h_exas.gif':
				$('input#textToSend').val($('input#textToSend').val()+' .... ');
				break;
			case '../resources/forum/smiley/h_middot.gif':
				$('input#textToSend').val($('input#textToSend').val()+' :dot: ');
				break;
			case '../resources/forum/smiley/h_door.gif':
				$('input#textToSend').val($('input#textToSend').val()+' :door: ');
				break;
			case '../resources/forum/smiley/h_surprise.gif':
				$('input#textToSend').val($('input#textToSend').val()+' :o ');
				break;
			case '../resources/forum/smiley/h_lol.gif':
				$('input#textToSend').val($('input#textToSend').val()+' :D ');
				break;
			case '../resources/forum/smiley/h_neutral.gif':
				$('input#textToSend').val($('input#textToSend').val()+' -_- ');
				break;
		}
	});
	
	$('#submit_btn').click(function(e) {
		e.preventDefault();
		var dataString = $('#textToSend').val();
		var dataPseudo = $('#pseudo').val();
		var dataJob = $('#job').val();
		var date = new Date;
		var minutes = date.getMinutes();
		var hour = date.getHours();
		var check = 0;
		var temp = $('#sage');
		if(temp.length == 1){
			if(temp.is(':checked')){
				check = 1;
			}
		}
		$.ajax({
			type: 'POST',
			url: 'process.php',
			data: {message: dataString, pseudo: dataPseudo, job: dataJob, min: minutes, heure: hour, op: check},
			success: function() {
				$('input#textToSend').val('');
				$('#live').load('live.php');
			}
		});
	});
});