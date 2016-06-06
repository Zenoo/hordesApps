$( document ).ready(function() {
	
	var byPicto = null;
	
	$('ul.t1').children().each(function(){
		var pictos = $('.userTable tbody tr:eq(1) td ul', this).detach();
		
		var items = pictos.children().get();
		items.sort(function(a,b){
		  var keyA = +$(a).attr('data-nb');
		  var keyB = +$(b).attr('data-nb');
		  if (keyA < keyB) return 1;
		  if (keyA > keyB) return -1;
		  return 0;
		});
		$.each(items, function(i, li){
		  pictos.append(li);
		}); 
		$('.userTable tbody tr:eq(1) td', this).append(pictos);
	});
	
	$('ul.t1').show('slow');

	$('#btnSubmit').click(function(e) {
		e.preventDefault();
		var toSort = $('#tech_title').children().first().attr('src');
		var tmp = toSort.match("_(.*).gif");
		toSort=tmp[1];

		$('ul.t1').slideUp('slow', function(){
			byPicto = $('ul.t1').detach();
			
			var items = byPicto.children().get();
			items.sort(function(a,b){
			  var keyA = +$(a).find('li[data-id="'+toSort+'"]').attr('data-nb');
			  if(isNaN(keyA)) keyA = 0;
			  var keyB = +$(b).find('li[data-id="'+toSort+'"]').attr('data-nb');
			  if(isNaN(keyB)) keyB = 0;
			  

			  if (keyA < keyB) return 1;
			  if (keyA > keyB) return -1;
			  return 0;
			});
			$.each(items, function(i, li){
			  byPicto.append(li);
			});
			
			byPicto.hide().insertAfter('#overDiv').show('slow');
		});
	});
	
	/*
	$('.home').click(function(){
		$('#page').slideUp('slow', function(){
			$('#page').remove();
			$(defaultPage).hide().insertAfter('#banner').show('slow');
		});
	});*/
});