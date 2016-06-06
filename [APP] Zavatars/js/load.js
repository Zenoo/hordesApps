$( document ).ready(function() {
	var defaultPage = null;var currentPage = 1;var creatorList= null;var creatorSearchList = [];var cSLPage = [];var creationsList = null;var requestList = null;var requestSearchList = [];var rSLPage = [];var currentPage2 = null;
	
	$("#contentHolder").mCustomScrollbar();
	
	function creatorListLoader(page){
		console.log('currentPage:'+currentPage);
		console.log('totalPages'+Math.ceil(Object.keys(creatorSearchList).length/10));
		creatorSearchList = [];cSLPage = [];
		var reas;
		if($('#minimumCrea').val() !== '' && $.isNumeric($('#minimumCrea').val())) reas = $('#minimumCrea').val();
		else reas = 0;
		var creatorName;
		if($('#creatorSearch').val() !== null) creatorName=$('#creatorSearch').val();
		else creatorName = '';
		var isCreator;
		if($('#isCreator').is(':checked')) isCreator = 1;
		else isCreator = 0;
		
		var j = 0;
		$.each(creatorList, function(i, item){
			if ((item.name).toLowerCase().indexOf(creatorName.toLowerCase()) >= 0 && item.totalCreations>=reas && item.creator>=isCreator){
				creatorSearchList.push(item);
				j++;
				if(j>=(page-1)*10 && j<page*10){
					cSLPage.push(item);
				}
			}
		});
		$('.creatorLine').remove();
		var temp ='';
		$.each(cSLPage, function(i, item){
			var tmp1;
			if(item.description.length > 50) tmp1 =($.trim(item.description).substring(0, 50).split(" ").slice(0, -1).join(" ") + "...");
			else tmp1 = item.description;
			temp+='<tr class="creatorLine" data-id="'+item.twinId+'"><td></td>\
					<td><img src="http:'+item.picture+'"></td>\
					<td>'+item.name+'</td>\
					<td>'+item.totalCreations+'</td>\
					<td>'+tmp1+'</td></tr>';
		});
		$(temp).insertAfter('.headers');
	}
	
	function creationsListLoader(page){
		console.log('currentPage:'+page);
		console.log('totalPages'+Math.ceil(Object.keys(creationsList).length/10));
		
		$('.creation').remove();
		var temp ='';
		$.each(creationsList, function(i, item){
			if(i<(currentPage-1)*21) return true;
			if(i>=currentPage*21) return false;
			temp+='<li class="creation" data-id="'+item.id+'"><img src="img/upl/'+item.creatorId+'/'+item.id+'.'+item.extension+'"></li>';
		});
		$(temp).prependTo('.creationsList');
	}
	
	function requestListLoader(page){
		console.log('currentPage:'+currentPage);
		console.log('totalPages'+Math.ceil(Object.keys(requestSearchList).length/10));
		requestSearchList = [];rSLPage = [];
		var requester;
		if($('#requesterSearch').val() !== '') requester = $('#requesterSearch').val();
		else requester = '';
		var onlyUnassigned;
		if($('#notAssigned').is(':checked')) onlyUnassigned=0;
		else onlyUnassigned = 1;
		var onlyUnfinished;
		if($('#notFinished').is(':checked')) onlyUnfinished=0;
		else onlyUnfinished = 1;
		
		var j = 0;
		$.each(requestList, function(i, item){
			if ((item.name).toLowerCase().indexOf(requester.toLowerCase()) >= 0 && item.completed<=onlyUnfinished){
				if(onlyUnassigned == 0){
					if(item.creators == '' || item.creators === null){
						requestSearchList.push(item);
						j++;
						if(j>=(page-1)*10 && j<page*10){
							rSLPage.push(item);
						}
					}
				}
				else{
					requestSearchList.push(item);
					j++;
					if(j>=(page-1)*10 && j<page*10){
						rSLPage.push(item);
					}
				}

			}
		});
		$('.requestLine').remove();
		var temp ='';
		$.each(rSLPage, function(i, item){
			var adding=' ';
			if(+item.completed == 1) adding+='crossed';
			var tmp1;
			if(item.title.length > 50) tmp1 = ($.trim(item.title).substring(0, 50).split(" ").slice(0, -1).join(" ") + "...");
			else tmp1 = item.title;
			var tmp2;
			if(item.description.length > 80) tmp2 = ($.trim(item.description).substring(0, 80).split(" ").slice(0, -1).join(" ") + "...");
			else tmp2 = item.description;
			temp+='<tr class="requestLine'+adding+'" data-id="'+item.id+'"><td></td>\
					<td>'+item.name+'</td>\
					<td>'+tmp1+'</td>\
					<td>'+tmp2+'</td></tr>';
		});
		$(temp).insertAfter('.headers');
	}
	
	function creatorFullListLoader(){
		currentPage=1;
		
		creatorSearchList = [];cSLPage = [];
		var reas;
		if($('#minimumCrea').val() !== '' && $.isNumeric($('#minimumCrea').val())) reas = $('#minimumCrea').val();
		else reas = 0;
		var creatorName;
		if($('#creatorSearch').val() !== null) creatorName=$('#creatorSearch').val();
		else creatorName = '';
		var isCreator;
		if($('#isCreator').is(':checked')) isCreator = 1;
		else isCreator = 0;
		
		
		var j = 0;
		$.each(creatorList, function(i, item){
			if ((item.name).toLowerCase().indexOf(creatorName.toLowerCase()) >= 0 && item.totalCreations>=reas && item.creator>=isCreator){
				creatorSearchList.push(item);
				j++;
				if(j>=(currentPage-1)*10 && j<currentPage*10){
					cSLPage.push(item);
				}
			}
		});
		$('.creatorLine').remove();
		var temp ='';
		$.each(cSLPage, function(i, item){
			var tmp1;
			if(item.description.length > 50) tmp1=($.trim(item.description).substring(0, 50).split(" ").slice(0, -1).join(" ") + "...");
			else tmp1 = item.description;
			temp+='<tr class="creatorLine" data-id="'+item.twinId+'"><td></td>\
					<td><img src="http:'+item.picture+'"></td>\
					<td>'+item.name+'</td>\
					<td>'+item.totalCreations+'</td>\
					<td>'+($.trim(item.description).substring(0, 50).split(" ").slice(0, -1).join(" ") + "...")+'</td></tr>';
		});
		$(temp).insertAfter('.headers');
		
		var pagination='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
		for(var i = currentPage-3;i<currentPage;i++){
			if(i>0){
				pagination+='<span class="others">'+i+'</span> ';
			}
		}
		pagination+='<span class="currentPage">'+currentPage+'</span> ';
		for(var i = currentPage+1;i<=currentPage+3;i++){
			var count = Math.ceil(Object.keys(creatorSearchList).length/10);
			if(i<=count){
				pagination+='<span class="others">'+i+'</span> ';
			}
		}
		pagination+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
		$('.navigation').children().first().html(pagination);
		$('.bottom').children().first().html(pagination);
		paginUpdate();
	}
	
	function requestFullListLoader(){
		currentPage=1;
		
		requestSearchList = [];rSLPage = [];
		var requester;
		if($('#requesterSearch').val() !== '') requester = $('#requesterSearch').val();
		else requester = '';
		var onlyUnassigned;
		if($('#notAssigned').is(':checked')) onlyUnassigned=0;
		else onlyUnassigned = 1;
		var onlyUnfinished;
		if($('#notFinished').is(':checked')) onlyUnfinished=0;
		else onlyUnfinished = 1;
		
		var j = 0;
		$.each(requestList, function(i, item){
			if ((item.name).toLowerCase().indexOf(requester.toLowerCase()) >= 0 && item.completed<=onlyUnfinished){
				if(onlyUnassigned == 0){
					if(item.creators == '' || item.creators === null){
						requestSearchList.push(item);
						j++;
						if(j>=(currentPage-1)*10 && j<currentPage*10){
							rSLPage.push(item);
						}
					}
				}
				else{
					requestSearchList.push(item);
					j++;
					if(j>=(currentPage-1)*10 && j<currentPage*10){
						rSLPage.push(item);
					}
				}

			}
		});
		$('.requestLine').remove();
		var temp ='';
		$.each(rSLPage, function(i, item){
			var adding=' ';
			if(+item.completed == 1) adding+='crossed';
			var tmp1;
			if(item.title.length > 50) tmp1 = ($.trim(item.title).substring(0, 50).split(" ").slice(0, -1).join(" ") + "...");
			else tmp1 = item.title;
			var tmp2;
			if(item.description.length > 80) tmp2 = ($.trim(item.description).substring(0, 80).split(" ").slice(0, -1).join(" ") + "...");
			else tmp2 = item.description;
			temp+='<tr class="requestLine'+adding+'" data-id="'+item.id+'"><td></td>\
					<td>'+item.name+'</td>\
					<td>'+tmp1+'</td>\
					<td>'+tmp2+'</td></tr>';
		});
		$(temp).insertAfter('.headers');
		
		var pagination='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
		for(var i = currentPage-3;i<currentPage;i++){
			if(i>0){
				pagination+='<span class="others">'+i+'</span> ';
			}
		}
		pagination+='<span class="currentPage">'+currentPage+'</span> ';
		for(var i = currentPage+1;i<=currentPage+3;i++){
			var count = Math.ceil(Object.keys(requestSearchList).length/10);
			if(i<=count){
				pagination+='<span class="others">'+i+'</span> ';
			}
		}
		pagination+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
		$('.navigation').children().first().html(pagination);
		$('.bottom').children().first().html(pagination);
		paginUpdateRequest();
	}
	
	function paginUpdateCallBack(that){
		if(that.is('.backFull')){
				currentPage=1;
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(creatorSearchList).length/10);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.back')){
				if(currentPage>1) currentPage--;
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(creatorSearchList).length/10);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.next')){
				if(currentPage < Math.ceil(Object.keys(creatorSearchList).length/10)) currentPage++;
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(creatorSearchList).length/10);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.nextFull')){
				var count = Math.ceil(Object.keys(creatorSearchList).length/10);
				if(count == 0) count=1;
				currentPage=count;
				
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.others')){
				currentPage=+that.text();
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(creatorSearchList).length/10);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			creatorListLoader(currentPage);
			paginUpdate();
	}
	
	function paginUpdateCallBackCreations(that){
		if(that.is('.backFull')){
				currentPage=1;
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(creationsList).length/21);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.back')){
				if(currentPage>1) currentPage--;
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(creationsList).length/21);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.next')){
				if(currentPage < Math.ceil(Object.keys(creationsList).length/21)) currentPage++;
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(creationsList).length/21);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.nextFull')){
				var count = Math.ceil(Object.keys(creationsList).length/21);
				if(count==0) count=1;
				currentPage=count;
				
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.others')){
				currentPage=+that.text();
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(creationsList).length/21);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			creationsListLoader(currentPage);
			paginUpdateCreations();
	}
	
	function paginUpdateCallBackRequest(that){
		if(that.is('.backFull')){
				currentPage=1;
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(requestList).length/10);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.back')){
				if(currentPage>1) currentPage--;
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(requestList).length/10);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.next')){
				if(currentPage < Math.ceil(Object.keys(requestList).length/10)) currentPage++;
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(requestList).length/10);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.nextFull')){
				var count = Math.ceil(Object.keys(requestList).length/10);
				if(count == 0) count=1;
				currentPage=count;
				
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			else if(that.is('.others')){
				currentPage=+that.text();
				var tmp='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
				for(var i = currentPage-3;i<currentPage;i++){
					if(i>0){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="currentPage">'+currentPage+'</span> ';
				for(var i = currentPage+1;i<=currentPage+3;i++){
					var count = Math.ceil(Object.keys(requestList).length/10);
					if(i<=count){
						tmp+='<span class="others">'+i+'</span> ';
					}
				}
				tmp+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
				
				$('.navigation').children().first().html(tmp);
				$('.bottom').children().first().html(tmp);
			}
			requestListLoader(currentPage);
			paginUpdateRequest();
	}
	
	function paginUpdate(){
		$('.navigation').children().first().children().on('click',function(){
			paginUpdateCallBack($(this));
		});
		$('.bottom').children().first().children().on('click',function(){
			paginUpdateCallBack($(this));
		});
	}
	
	function paginUpdateCreations(){
		$('.navigation').children().first().children().on('click',function(){
			paginUpdateCallBackCreations($(this));
		});
		$('.bottom').children().first().children().on('click',function(){
			paginUpdateCallBackCreations($(this));
		});
	}
	
	function paginUpdateRequest(){
		$('.navigation').children().first().children().on('click',function(){
			paginUpdateCallBackRequest($(this));
		});
		$('.bottom').children().first().children().on('click',function(){
			paginUpdateCallBackRequest($(this));
		});
	}
	
	$('#content').on('click', 'tr.creatorLine', function(e){
		if(!$('#contentHolder').is(':animated')){
			if($('#content').is('[style]')) $('#content').removeAttr('style');
			$('#contentHolder').slideUp('slow', function(){
				$('.selected').removeClass('selected');
				$('#contentHolder').detach();
			
				$('#wait').show();
				$.post( "users.php", {userRequest:+$(e.target).parent().attr('data-id')}, function( data ) {
					var userList=$.parseJSON(data);
					var userInfo;
					$.each(userList, function(i, item){
						if(item.twinId == +$(e.target).parent().attr('data-id')){
							userInfo=item;
							return true;
						}
					});
					$.post( "creations.php", {creatorId:+$(e.target).parent().attr('data-id')}, function( data ) {
						creationsList=$.parseJSON(data);
						currentPage=1;
						var creationsToAdd='';
						$.each(creationsList, function(i, item){
							if(i<(currentPage-1)*21) return true;
							if(i>=currentPage*21) return false;
							creationsToAdd+='<li class="creation" data-id="'+item.id+'"><img src="img/upl/'+item.creatorId+'/'+item.id+'.'+item.extension+'"></li>';
						});
						
						var pagination='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
						for(var i = currentPage-3;i<currentPage;i++){
							if(i>0){
								pagination+='<span class="others">'+i+'</span> ';
							}
						}
						pagination+='<span class="currentPage">'+currentPage+'</span> ';
						for(var i = currentPage+1;i<=currentPage+3;i++){
							var count = Math.ceil(Object.keys(creationsList).length/21);
							if(i<=count){
								pagination+='<span class="others">'+i+'</span> ';
							}
						}
						pagination+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
						
						var date = new Date(userInfo.updated*1000);
						var dString = ('0'+date.getDate()).slice(-2)+'/'+('0'+(date.getMonth()+1)).slice(-2)+'/'+date.getFullYear()+' à '+date.getHours()+'h'+date.getMinutes();
						
						var profile= '\
							<div id="contentHolder" class="added">\
							<div class="separator"><p>'+userInfo.name+'</p></div>\
							<img style="margin-top: 10px;margin-left:20px;border: 1px solid black;max-width:80px;max-height:80px;" src="http:'+userInfo.picture+'">\
							<div class="leftBordered" style="position: absolute;top: 55px;left: 130px;">\
								<p>Créations : '+userInfo.totalCreations+'</p>\
								<br />\
								<p>Réputation : '+userInfo.reput+'</p>\
								<br />\
								<p>Dernière connexion : Le '+dString+'</p>\
							</div>\
							<div class="separator"><p>Créations</p></div>\
							<div class="navigation"><p>'+pagination+'</p></div>\
							<ul class="creationsList">'+creationsToAdd+'</ul>\
							<div class="navigation bottom"><p>'+pagination+'</p></div>\
							</div>';
						
						$('#wait').hide();
						$(profile).hide().prependTo('#content').show('slow', function(){
							$("#contentHolder").mCustomScrollbar();
							$('#content').css('margin-top','16px');
						});
						
						paginUpdateCreations();
					});
					
				});
			});
		}
	});
	
	function bbDecode(bbcode){
		$('body').append('<textarea id="tmpBBCode"></textarea>');
		$('#tmpBBCode').wysibb();
		$('#tmpBBCode').parent().parent().hide();
		$('div.wysibb-toolbar-btn.mswitch').trigger('click');
		$('#tmpBBCode').val(bbcode);
		$('div.wysibb-toolbar-btn.mswitch.on').trigger('click');
		
		var res = $('div.wysibb-text-editor.wysibb-body').html();
		$('#tmpBBCode').parent().parent().remove();
		
		return res;
	}
	
	$('#content').on('click', 'tr.requestLine', function(e){
		if(!$('#contentHolder').is(':animated')){
			var requestIdTmp = +$(this).attr('data-id');
			var isCompleted = $(this).is('.crossed');
			if($('#tempTable').length > 0) $('#tempTable').remove();
			$('#hiddenInfo').attr('data-request-id', requestIdTmp);
			if($('#content').is('[style]')) $('#content').removeAttr('style');
			$('#contentHolder').slideUp('slow', function(){
				$('.selected').removeClass('selected');
				$('#contentHolder').detach();
			
				$('#wait').show();
				$.post( "conv.php", {requestId:requestIdTmp}, function( data ) {
					var messageList=$.parseJSON(data);
					
					$.post( "users.php", {userRequest:-1}, function( data2 ) {
						var usersTmp=$.parseJSON(data2);
						
						$.post( "creations.php", {creationId:-1}, function( data3 ) {
							var creationTmp=$.parseJSON(data3);
							
							var msgAdd='';
							$.each(messageList, function(i, item){
								
								var userPicTmp, userNameTmp, userIdTmp;
								$.each(usersTmp, function(j, itemTmp){
									if(+itemTmp.twinId == +item.senderId){
										userPicTmp = itemTmp.picture;
										userNameTmp = itemTmp.name;
										userIdTmp = itemTmp.twinId;
										return false;
									}
								});
								
								
								var adaptedTime ='';
								var currentDate = Math.floor($.now()/1000);
								if((currentDate - item.timestamp)/60 < 60){ //-1h
									if((currentDate - item.timestamp)/60 <= 0) adaptedTime += 'Il y a un instant';
									else adaptedTime+='Il y a '+Math.floor((currentDate - item.timestamp)/60)+' min';
								}
								else if(((currentDate - item.timestamp)/60)/60 < 24){//-24h
									adaptedTime+='Il y a '+Math.floor(((currentDate - item.timestamp)/60)/60)+' h '+(Math.floor((currentDate - item.timestamp)/60)%60)+' min';
								}
								else{
									var date = new Date(item.timestamp*1000);
									adaptedTime += 'Le '+('0'+date.getDate()).slice(-2)+'/'+('0'+(date.getMonth()+1)).slice(-2)+'/'+date.getFullYear()+' à '+date.getHours()+'h'+date.getMinutes();
								
								}
								
								//Creation
								if(item.creationId > 0){
									
									var creaExtTmp,creaAccTmp;
									$.each(creationTmp, function(j, itemTmp){
										if(+itemTmp.id == +item.creationId){
											creaExtTmp = itemTmp.extension;
											creaAccTmp = itemTmp.accepted;
											return false;
										}
									});
									
									//Self
									if(+item.senderId == +$('#hiddenInfo').attr('data-id')){
										msgAdd+='\
										<div class="convMsgMe avatCont">\
											<div class="convAvatRight">\
												<table>\
													<tbody>\
														<tr>\
															<td>\
																<img src="http:'+userPicTmp+'" alt="'+userNameTmp+'" title="">\
															</td>\
														</tr>\
													</tbody>\
												</table>\
											</div>\
											<div class="convArrowRight"></div>\
											<div>\
												<div class="convSpeaker">\
													<span data-id="'+$('#hiddenInfo').attr('data-id')+'">'+userNameTmp+'</span>\
												</div>\
												<div><img class="resultAvat" data-id="'+item.creationId+'" src="img/upl/'+item.senderId+'/'+item.creationId+'.'+creaExtTmp+'"></div>\
											</div>\
											<div class="convDate">'+adaptedTime+'</div>\
										</div>';
									}
									else{
										var extraAdd='';
										if(+$('#hiddenInfo').attr('data-id') == messageList[messageList.length-1].senderId && +creaAccTmp == 0) extraAdd+='<div class="acceptAv">Accepter</div>';
										msgAdd+='\
										<div class="convMsgOther">\
											<div class="convAvatLeft">\
												<table>\
													<tbody>\
														<tr>\
															<td>\
																<img src="http:'+userPicTmp+'" alt="'+userNameTmp+'" title="">\
															</td>\
														</tr>\
													</tbody>\
												</table>\
											</div>\
											<div class="convArrowLeft"></div>\
											<div>\
												<div class="convSpeaker">\
													<span data-id="'+userIdTmp+'">'+userNameTmp+'</span>\
												</div>\
												<div>\
													<img class="resultAvat" data-id="'+item.creationId+'" src="img/upl/'+item.senderId+'/'+item.creationId+'.'+creaExtTmp+'">'+extraAdd+'\
												</div>\
											</div>\
											<div class="convDate">'+adaptedTime+'</div>\
										</div>';
									}
									
								}
								else{
									//Self
									if(item.senderId == +$('#hiddenInfo').attr('data-id')){
										msgAdd+='\
										<div class="convMsgMe">\
											<div class="convAvatRight">\
												<table>\
													<tbody>\
														<tr>\
															<td>\
																<img src="http:'+userPicTmp+'" alt="'+userNameTmp+'" title="">\
															</td>\
														</tr>\
													</tbody>\
												</table>\
											</div>\
											<div class="convArrowRight"></div>\
											<div>\
												<div class="convSpeaker">\
													<span data-id="'+$('#hiddenInfo').attr('data-id')+'">'+userNameTmp+'</span>\
												</div>\
												<div><p>'+bbDecode(item.text)+'</p></div>\
											</div>\
											<div class="convDate">'+adaptedTime+'</div>\
										</div>';
									}
									else{
										msgAdd+='\
										<div class="convMsgOther">\
											<div class="convAvatLeft">\
												<table>\
													<tbody>\
														<tr>\
															<td>\
																<img src="http:'+userPicTmp+'" alt="'+userNameTmp+'" title="">\
															</td>\
														</tr>\
													</tbody>\
												</table>\
											</div>\
											<div class="convArrowLeft"></div>\
											<div>\
												<div class="convSpeaker">\
													<span data-id="'+userIdTmp+'">'+userNameTmp+'</span>\
												</div>\
												<div><p>'+bbDecode(item.text)+'</p></div>\
											</div>\
											<div class="convDate">'+adaptedTime+'</div>\
										</div>';
									}
								}
								
							});
							
							$.post( "request.php", {requestId:+requestIdTmp}, function( data4 ) {
								console.log('request GOT');
								var addMore='';
								var creatorsArray = $.parseJSON(data4)[0].creators.split(',').map(Number);
								
								if(!isCompleted){
									//User = requester/creator
									if((+$('#hiddenInfo').attr('data-id') == messageList[messageList.length-1].senderId || jQuery.inArray(+$('#hiddenInfo').attr('data-id'), creatorsArray) !== -1) && $.parseJSON(data4)[0].completed == 0){
										addMore+='<textarea id="requestAnswer" rows="10" cols="80"></textarea><input id="requestAnswerValidate" type="button" value="Valider">';
										if(+$('#hiddenInfo').attr('data-id') == messageList[messageList.length-1].senderId) addMore+='<input id="cancelRequest" type="button" value="Annuler la demande">';
										if(jQuery.inArray(+$('#hiddenInfo').attr('data-id'), creatorsArray) !== -1) addMore+='<input id="cancelCreator" type="button" value="Se retirer de la demande"><input id="addCreation" type="button" value="Poster un avatar">';
									}
									else if(+$('#hiddenInfo').attr('data-id') != messageList[messageList.length-1].senderId){
										addMore+='<input id="becomeCreator" type="button" value="Prendre en charge la demande">';
										if(+$('#hiddenInfo').attr('data-id') == 4647 || +$('#hiddenInfo').attr('data-id') == 2468) addMore+='<input id="deleteRequest" type="button" value="Supprimer la demande">';
									}
									
									$('#content').undelegate('input#addCreation', 'click');
									$('#content').on('click', 'input#addCreation', function(e){
										var requestId = +$('#hiddenInfo').attr('data-request-id');
										var creatorIdTmp = +$('#hiddenInfo').attr('data-id');
										
										var form ='\
										<form action="upload.php" enctype="multipart/form-data" id="form" method="post" name="form">\
											<div class="separator"><p>Upload</p></div>\
											<div id="upload" class="leftBordered">\
												Avatar (80x80) : <input type="hidden" name="MAX_FILE_SIZE" value="500000" />\
												<input type="hidden" name="creatorId" value="'+$('#hiddenInfo').attr('data-id')+'" />\
												<input type="hidden" name="requestId" value="'+requestId+'" />\
														<input id="file" style="margin-top:5px;" name="file" type="file">\
											</div>\
											<input id="submit" name="submit" type="submit" value="Envoyer">\
										</form>';
										
										$('#contentHolder').css('margin-top','-22px');
										$('.wysibb').slideUp('slow', function(){
											$('#requestAnswerValidate').hide();
											$('#addCreation').hide();
											$('#cancelCreator').hide()
											$('.wysibb').remove();
											$(form).hide().prependTo('#contentHolder').show('slow', function(){
												$('#form').on('submit', function(e){
													e.preventDefault();

													var $form = $(this);
													var formdata = (window.FormData) ? new FormData($form[0]) : null;
													var data = (formdata !== null) ? formdata : $form.serialize();

													$('#wait').show();
													$.ajax({
														url: $form.attr('action'),
														type: $form.attr('method'),
														contentType: false,
														processData: false, 
														dataType: 'text',
														data: data
													}).done(function(response){
														$('#wait').hide();
														if(response == 'Transfer completed'){
															$('<table id="tempTable" style="display:none;"><tr class="requestLine" data-id="'+requestId+'"></tr></table>').appendTo('#content');
															$('#tempTable tr.requestLine').trigger('click');
														}
														else{
															$('#error').remove();
															$('#form').append('<p id="error">Error : '+response+'</p>');
														}
													});

												});
											});
										});
										
									});
								}
								
								$('#wait').hide();
								$('<div id="contentHolder" class="added">'+addMore+msgAdd+'</div>').hide().prependTo('#content').show('slow', function(){
									$("#contentHolder").mCustomScrollbar();
									$('#content').css('margin-top','16px');
									if($("#requestAnswer").length > 0){
										
										$("#requestAnswer").wysibb({buttons: "bold,italic,underline,|,img,link,|,bullist,|, quote,code"});
										
										$('#requestAnswerValidate').click(function(){
											if($("#requestAnswer").bbcode() != ''){
												$.post( "conv.php", {messageId:messageList.length,requestId:+requestIdTmp,senderId:+$('#hiddenInfo').attr('data-id'),creationId:0,text:$("#requestAnswer").bbcode()}, function( data ) {
													var verif = $.parseJSON(data);
													if(Object.keys(verif).length >= 1){
														$('<table id="tempTable" style="display:none;"><tr class="requestLine" data-id="'+requestIdTmp+'"></tr></table>').appendTo('#content');
														$('#tempTable tr.requestLine').trigger('click');
													}
													else{
														$('#error').remove();
														$('<p id="error">Une erreur est survenue, merci de réessayer.</p>').insertAfter('#requestAnswerValidate');
													}
												});
											}
											else{
												$('#error').remove();
												$('<p id="error">Erreur, message vide.</p>').insertAfter('#requestAnswerValidate');
											}
										});
									}
								});
							});
							
						});
						
					});
					
				});
			});
		}
	});
	
	$('#content').on('click', 'div.convSpeaker', function(e){
		var creatorClicId = +$(this).children().first().attr('data-id');
		if(!$('#contentHolder').is(':animated')){
			if($('#content').is('[style]')) $('#content').removeAttr('style');
			$('#contentHolder').slideUp('slow', function(){
				$('.selected').removeClass('selected');
				$('#contentHolder').detach();
			
				$('#wait').show();
				$.post( "users.php", {userRequest:creatorClicId}, function( data ) {
					var userList=$.parseJSON(data);
					var userInfo;
					$.each(userList, function(i, item){
						if(item.twinId == creatorClicId){
							userInfo=item;
							return true;
						}
					});
					$.post( "creations.php", {creatorId:creatorClicId}, function( data ) {
						creationsList=$.parseJSON(data);
						currentPage=1;
						var creationsToAdd='';
						$.each(creationsList, function(i, item){
							if(i<(currentPage-1)*21) return true;
							if(i>=currentPage*21) return false;
							creationsToAdd+='<li class="creation" data-id="'+item.id+'"><img src="img/upl/'+item.creatorId+'/'+item.id+'.'+item.extension+'"></li>';
						});
						
						var pagination='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
						for(var i = currentPage-3;i<currentPage;i++){
							if(i>0){
								pagination+='<span class="others">'+i+'</span> ';
							}
						}
						pagination+='<span class="currentPage">'+currentPage+'</span> ';
						for(var i = currentPage+1;i<=currentPage+3;i++){
							var count = Math.ceil(Object.keys(creationsList).length/21);
							if(i<=count){
								pagination+='<span class="others">'+i+'</span> ';
							}
						}
						pagination+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
						
						var date = new Date(userInfo.updated*1000);
						var dString = ('0'+date.getDate()).slice(-2)+'/'+('0'+(date.getMonth()+1)).slice(-2)+'/'+date.getFullYear()+' à '+date.getHours()+'h'+date.getMinutes();
						
						var profile= '\
							<div id="contentHolder" class="added">\
							<div class="separator"><p>'+userInfo.name+'</p></div>\
							<img style="margin-top: 10px;margin-left:20px;border: 1px solid black;max-width:80px;max-height:80px;" src="http:'+userInfo.picture+'">\
							<div class="leftBordered" style="position: absolute;top: 55px;left: 130px;">\
								<p>Créations : '+userInfo.totalCreations+'</p>\
								<br />\
								<p>Réputation : '+userInfo.reput+'</p>\
								<br />\
								<p>Dernière connexion : Le '+dString+'</p>\
							</div>\
							<div class="separator"><p>Créations</p></div>\
							<div class="navigation"><p>'+pagination+'</p></div>\
							<ul class="creationsList">'+creationsToAdd+'</ul>\
							<div class="navigation bottom"><p>'+pagination+'</p></div>\
							</div>';
						
						$('#wait').hide();
						$(profile).hide().prependTo('#content').show('slow', function(){
							$("#contentHolder").mCustomScrollbar();
							$('#content').css('margin-top','16px');
						});
						
						paginUpdateCreations();
					});
					
				});
			});
		}
	});
	
	$('#content').on('click', 'div.acceptAv', function(e){
		var creaId = +$(this).prev().attr('data-id');
		var creatorIdTmp = +$(this).parent().prev().children().first().attr('data-id');
		$('#wait').show();
		$.post( "request.php", {acceptAv:1,creationId:creaId,reqId:+$('#hiddenInfo').attr('data-request-id')}, function( data ) {
			$.post( "users.php", {updateCreas:1,userId:creatorIdTmp}, function( data ) {
				$('#wait').hide();
				$('<table id="tempTable" style="display:none;"><tr class="requestLine" data-id="'+$('#hiddenInfo').attr('data-request-id')+'"></tr></table>').appendTo('#content');
				$('#tempTable tr.requestLine').trigger('click');
			});
		});
	});
	
	$('#content').on('click','input#deleteRequest', function(e){
		var reqId = +$('#hiddenInfo').attr('data-request-id');
		
		$('#wait').show();
		$.post( "request.php", {deleteReq:1,reqId:reqId}, function( data ) {
			$('#wait').hide();
			$('#requestList').trigger('click');
		});
	});
	
	$('#content').on('click','input#becomeCreator', function(e){
		var userId = +$('#hiddenInfo').attr('data-id');
		var reqId = +$('#hiddenInfo').attr('data-request-id');
		
		$('#wait').show();
		$.post( "request.php", {becomeCrea:1,creatorId:userId,reqId:reqId}, function( data ) {
			$('#wait').hide();
			$('<table id="tempTable" style="display:none;"><tr class="requestLine" data-id="'+$('#hiddenInfo').attr('data-request-id')+'"></tr></table>').appendTo('#content');
			$('#tempTable tr.requestLine').trigger('click');
		});
	});
	
	$('#content').on('click','input#cancelCreator', function(e){
		var userId = +$('#hiddenInfo').attr('data-id');
		var reqId = +$('#hiddenInfo').attr('data-request-id');
		
		$('#wait').show();
		$.post( "request.php", {cancelCrea:1,creatorId:userId,reqId:reqId}, function( data ) {
			$('#wait').hide();
			$('<table id="tempTable" style="display:none;"><tr class="requestLine" data-id="'+$('#hiddenInfo').attr('data-request-id')+'"></tr></table>').appendTo('#content');
			$('#tempTable tr.requestLine').trigger('click');
		});
	});
	
	$('#content').on('click','input#cancelRequest', function(e){
		var reqId = +$('#hiddenInfo').attr('data-request-id');
		
		$('#wait').show();
		$.post( "request.php", {cancelReq:1,reqId:reqId}, function( data ) {
			$('#wait').hide();
			$('#request').trigger('click');
		});
	});
	
	$('#content').on('click','p#addAvat', function(e){
		var form ='\
		<form action="upload.php" enctype="multipart/form-data" id="form" method="post" name="form">\
			<div class="separator"><p>Upload</p></div>\
			<div id="upload" class="leftBordered">\
				Avatar (80x80) : <input type="hidden" name="MAX_FILE_SIZE" value="500000" />\
				<input type="hidden" name="creatorId" value="'+$('#hiddenInfo').attr('data-id')+'" />\
				<input type="hidden" name="onlyProfile" value="1" />\
				<input type="hidden" name="profilePrompt" value="1" />\
						<input id="file" style="margin-top:5px;" name="file" type="file">\
			</div>\
			<input id="submit" name="submit" type="submit" value="Envoyer">\
		</form>';
		
		$('#contentHolder').css('margin-top','-22px');
		$('#addAvat').hide();
		$(form).hide().insertAfter('#addAvat').show('slow', function(){
			$('#form').on('submit', function(e){
				e.preventDefault();

				var $form = $(this);
				var formdata = (window.FormData) ? new FormData($form[0]) : null;
				var data = (formdata !== null) ? formdata : $form.serialize();

				$('#wait').show();
				$.ajax({
					url: $form.attr('action'),
					type: $form.attr('method'),
					contentType: false,
					processData: false, 
					dataType: 'text',
					data: data
				}).done(function(response){
					$('#wait').hide();
					if(response == 'Transfer completed'){
						$('#profile').removeClass('selected');
						$('#profile').trigger('click');
					}
					else{
						$('#error').remove();
						$('#form').append('<p id="error">Error : '+response+'</p>');
					}
				});

			});
		});
	});
	
	$('#content').on('click', 'a', function() {
		window.open($(this).attr('href'));
		return false;
	});
	
	$('#creators').click(function(){
		if(!$(this).is('.selected')  && !$('#contentHolder').is(':animated')){
			if($('#content').is('[style]')) $('#content').removeAttr('style');
			$('#contentHolder').slideUp('slow', function(){
				$('.selected').removeClass('selected');
				$('#creators').addClass('selected');
				if (defaultPage == null) defaultPage = $('#contentHolder').detach();
				else $('#contentHolder').detach();
				
				$('#wait').show();
				$.post( "users.php", {userRequest:-1}, function( data ) {
					creatorList=$.parseJSON(data);
					creatorSearchList=creatorList;

					var toAdd='';var countCreators=0;
					$.each(creatorList, function(i, item){
						if(i<(currentPage-1)*10 || item.creator == 0) return true;
						if(i>=currentPage*10) return false;
						var tmp1;
						if(item.description.length > 50) tmp1 = ($.trim(item.description).substring(0, 50).split(" ").slice(0, -1).join(" ") + "...");
						else tmp1 = item.description;
						toAdd+='<tr class="creatorLine" data-id="'+item.twinId+'"><td></td>\
								<td><img src="http:'+item.picture+'"></td>\
								<td>'+item.name+'</td>\
								<td>'+item.totalCreations+'</td>\
								<td>'+tmp1+'</td></tr>';
						countCreators++;
					});
					
					var pagination='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
					for(var i = currentPage-3;i<currentPage;i++){
						if(i>0){
							pagination+='<span class="others">'+i+'</span> ';
						}
					}
					pagination+='<span class="currentPage">'+currentPage+'</span> ';
					for(var i = currentPage+1;i<=currentPage+3;i++){
						var count = Math.ceil(countCreators/10);
						if(i<=count){
							pagination+='<span class="others">'+i+'</span> ';
						}
					}
					pagination+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
					

					var creators= '\
					<div id="contentHolder" class="added">\
					<p class="infos">Vous pouvez consulter les fiches et galeries des personnes ayant atteint le grade de créateur.<br />\
					Une fonction de recherche vous permet de trouver la personne qu\'il vous faut.</p>\
					<div class="separator"><p>Recherche</p></div>\
					<div class="leftBordered">\
						<p>Nom du créateur :</p>\
						<input id="creatorSearch">\
						<p>Nombre de réalisations minimum</p>\
						<input id="minimumCrea">\
						<p style="margin-left:-30px;cursor:pointer;"><input id="isCreator" type="checkbox" checked="checked"><span> Créateurs confirmés uniquement</span></p>\
					</div>\
					<div class="separator"><p>Liste</p></div>\
					<div class="navigation"><p>'+pagination+'</p></div>\
					<table class="list">\
					<tr class="headers"><th></th><th>Avatar</th><th>Nom</th><th>Réas</th><th>Description</th></tr>\
					'+toAdd+'\
					</table>\
					<div class="navigation bottom"><p>'+pagination+'</p></div>\
					</div>';
					$('#wait').hide();
					$(creators).hide().prependTo('#content').show('slow', function(){
						$("#contentHolder").mCustomScrollbar();
						$('#content').css('margin-top','16px');
					});
					
					$('#creatorSearch').unbind('keyup focusout');
                    $('#creatorSearch').keyup(function(e){
						creatorFullListLoader();
                    });
                    $('#creatorSearch').focusout(function(e){
						creatorFullListLoader();
                    });
					
					$('#minimumCrea').unbind('keyup focusout');
                    $('#minimumCrea').keyup(function(e){
						creatorFullListLoader();
                    });
                    $('#minimumCrea').focusout(function(e){
						creatorFullListLoader();
                    });
					
					$('#isCreator').unbind('change');
                    $('#isCreator').change(function() {
						creatorFullListLoader();
                    });
					
					$('#isCreator').parent().children().eq(1).unbind('click');
                    $('#isCreator').parent().children().eq(1).on('click',function(){$('#isCreator').prop( "checked", !$('#isCreator').prop("checked")).trigger('change') ;});
					
					creatorFullListLoader();
					paginUpdate();
				});
			});
		}
	});
	
	$('#request').click(function(){
		if(!$(this).is('.selected')  && !$('#contentHolder').is(':animated')){
			if($('#content').is('[style]')) $('#content').removeAttr('style');
			$('#contentHolder').slideUp('slow', function(){
				$('.selected').removeClass('selected');
				$('#request').addClass('selected');
				if (defaultPage == null) defaultPage = $('#contentHolder').detach();
				else $('#contentHolder').detach();
				
				$('#wait').show();
				$.post( "request.php", {checkAlreadyRequested:1,userId:+$('#hiddenInfo').attr('data-id')}, function( data ) {
					var alCheck=$.parseJSON(data);
					
					if(Object.keys(alCheck).length > 0){
						$('#wait').hide();
						$('<table id="tempTable" style="display:none;"><tr class="requestLine" data-id="'+alCheck[0].id+'"></tr></table>').appendTo('#content');
						$('<div id="contentHolder"></div>').prependTo('#content');
						$('#tempTable tr.requestLine').trigger('click');
					}
					else{
						var request ='\
						<div id="contentHolder" class="added">\
							<p class="infos">Expliquez votre demande en indiquant toutes les idées qui vous traversent l\'esprit et que vous désirez retrouver sur votre avatar.<br />\
							Les demandes les plus originales sauront sans nul doute attirer un créateur qui vous proposera alors ses services pour la réalisation de votre demande. Dès lors, vous aurez une conversation avec ce dernier afin d\'affiner les détails de l\'avatar et de déterminer s\'il vous convient ou pas. Il suffira enfin de valider votre création lorsque le résultat vous satisfera.</p>\
							<div class="separator"><p>Demande</p></div>\
							<div class="leftBordered">\
								<p>Titre :</p>\
								<input id="requestTitle">\
								<p>Description :</p>\
								<textarea id="requestDescription" rows="10" cols="80"></textarea><br />\
								<input id="requestValidate" type="button" value="Valider">\
							</div>\
						</div>';
						
						$('#wait').hide();
						$(request).hide().prependTo('#content').show('slow', function(){
							$("#contentHolder").mCustomScrollbar();
							$('#content').css('margin-top','16px');
						});
						
						$("#requestDescription").wysibb({buttons: "bold,italic,underline,|,img,link,|,bullist,|, quote,code"});
						
						$('#requestValidate').click(function(){
							if($('#requestTitle').val() != '' && $('#requestDescription').bbcode() != ''){
								$.post( "request.php", {newRequest:1,twinId:+$('#hiddenInfo').attr('data-id'),name:$('#hiddenInfo').attr('data-name'),title:$('#requestTitle').val(),desc:$('#requestDescription').bbcode()}, function( data ) {
									var verif = $.parseJSON(data);
									if(Object.keys(verif).length >= 1){
										$('#requestList').trigger('click');
									}
									else{
										$('#error').remove();
										$('<p id="error">Une erreur est survenue, merci de réessayer.</p>').insertAfter('#requestValidate');
									}
								});
							}
							else{
								$('#error').remove();
								$('<p id="error">Erreur, merci de remplir tous les champs.</p>').insertAfter('#requestValidate');
							}
						});
					}
					
				});
				
			});
		}
	});
	
	$('#requestList').click(function(){
		if(!$(this).is('.selected')  && !$('#contentHolder').is(':animated')){
			if($('#content').is('[style]')) $('#content').removeAttr('style');
			$('#contentHolder').slideUp('slow', function(){
				$('.selected').removeClass('selected');
				$('#requestList').addClass('selected');
				if (defaultPage == null) defaultPage = $('#contentHolder').detach();
				else $('#contentHolder').detach();
				
				$('#wait').show();
				$.post( "request.php", {userRequest:-1}, function( data ) {
					requestList=$.parseJSON(data);
					currentPage=1;
					var pagination='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
					for(var i = currentPage-3;i<currentPage;i++){
						if(i>0){
							pagination+='<span class="others">'+i+'</span> ';
						}
					}
					pagination+='<span class="currentPage">'+currentPage+'</span> ';
					for(var i = currentPage+1;i<=currentPage+3;i++){
						var count = Math.ceil(Object.keys(requestList).length/10);
						if(i<=count){
							pagination+='<span class="others">'+i+'</span> ';
						}
					}
					pagination+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
					
					var toAdd='';
					var j = 0;
					$.each(requestList, function(i, item){
						if(j<(currentPage-1)*10){
							j++;
							return true;
						}
						if(j>=currentPage*10) return false;
						if(+item.completed == 1 || item.creators != ''){
							j++;
							return true;
						}
						var adding=' ';
						if(+item.completed == 1) adding+='crossed';
						var tmp1;
						if(item.title.length > 50) tmp1 = ($.trim(item.title).substring(0, 50).split(" ").slice(0, -1).join(" ") + "...");
						else tmp1 = item.title;
						var tmp2;
						if(item.description.length > 80) tmp2 = ($.trim(item.description).substring(0, 80).split(" ").slice(0, -1).join(" ") + "...");
						else tmp2 = item.description;
						toAdd+='<tr class="requestLine'+adding+'" style="height:30px;" data-id="'+item.id+'"><td></td>\
								<td>'+item.name+'</td>\
								<td>'+tmp1+'</td>\
								<td>'+tmp2.replace(/\[(\w+)[^w]*?](.*?)\[\/\1]/g, '$2')+'</td></tr>';
						j++;
					});

					var requests= '\
					<div id="contentHolder" class="added">\
					<p class="infos">Voici la liste de toutes les demandes posées à ce jour.</p>\
					<div class="separator"><p>Demandes</p></div>\
					<div class="leftBordered">\
						<p>Nom du demandeur :</p>\
						<input id="requesterSearch">\
						<p style="margin-left:-30px;cursor:pointer;"><input id="notAssigned" type="checkbox" checked="checked"><span> Demandes non assignées uniquement</span></p>\
						<p style="margin-left:-30px;cursor:pointer;"><input id="notFinished" type="checkbox" checked="checked"><span> Demandes non complétées uniquement</span></p>\
					</div>\
					<div class="separator"><p>Liste</p></div>\
					<div class="navigation"><p>'+pagination+'</p></div>\
					<table class="requestList">\
					<tr class="headers"><th></th><th>Nom</th><th>Titre</th><th>Demande</th></tr>\
					'+toAdd+'\
					</table>\
					<div class="navigation bottom"><p>'+pagination+'</p></div>\
					</div>';
					$('#wait').hide();
					$(requests).hide().prependTo('#content').show('slow', function(){
						$("#contentHolder").mCustomScrollbar();
						$('#content').css('margin-top','16px');
					});
					
					$('#requesterSearch').unbind('keyup focusout');
                    $('#requesterSearch').keyup(function(e){
						requestFullListLoader();
                    });
                    $('#requesterSearch').focusout(function(e){
						requestFullListLoader();
                    });
					
					$('#notAssigned').unbind('change');
                    $('#notAssigned').change(function() {
						requestFullListLoader();
                    });
					$('#notAssigned').parent().children().eq(1).unbind('click');
                    $('#notAssigned').parent().children().eq(1).on('click',function(){$('#notAssigned').prop( "checked", !$('#notAssigned').prop("checked")).trigger('change') ;});
					
					$('#notFinished').unbind('change');
                    $('#notFinished').change(function() {
						requestFullListLoader();
                    });
					$('#notFinished').parent().children().eq(1).unbind('click');
                    $('#notFinished').parent().children().eq(1).on('click',function(){$('#notFinished').prop( "checked", !$('#notFinished').prop("checked")).trigger('change') ;});
					
					requestFullListLoader();
					paginUpdateRequest();
				});
			});
		}
	});
	
	$('#profile').click(function(){
		if(!$(this).is('.selected') && !$('#contentHolder').is(':animated')){
			if($('#content').is('[style]')) $('#content').removeAttr('style');
			$('#contentHolder').slideUp('slow', function(){
				$('.selected').removeClass('selected');
				$('#profile').addClass('selected');
				if (defaultPage == null) defaultPage = $('#contentHolder').detach();
				else $('#contentHolder').detach();
				
				$('#wait').show();
				$.post( "users.php", {userRequest:+$('#hiddenInfo').attr('data-id')}, function( data ) {
					var userList=$.parseJSON(data);
					var userInfo;
					$.each(userList, function(i, item){
						if(item.twinId == +$('#hiddenInfo').attr('data-id')){
							userInfo=item;
							return false;
						}
					});
					
					$.post( "creations.php", {creatorId:+$('#hiddenInfo').attr('data-id')}, function( data ) {
						$.post( "request.php", {userRequest:+$('#hiddenInfo').attr('data-id'),onlyProfile:1}, function( data2 ) {
							creationsList=$.parseJSON(data);
							requestList=$.parseJSON(data2);
							
							currentPage=1;
							var creationsToAdd='';
							$.each(creationsList, function(i, item){
								if(i<(currentPage-1)*21) return true;
								if(i>=currentPage*21) return false;
								creationsToAdd+='<li class="creation" data-id="'+item.id+'"><img src="img/upl/'+item.creatorId+'/'+item.id+'.'+item.extension+'"></li>';
							});
							
							currentPage2=1;
							var requestToAdd='';
							$.each(requestList, function(i, item){
								if(i<(currentPage-1)*10) return true;
								if(i>=currentPage*10) return false;
								var adding=' ';
								if(+item.completed == 1) adding+='crossed';
								var tmp1;
								if(item.title.length > 50) tmp1 = ($.trim(item.title).substring(0, 50).split(" ").slice(0, -1).join(" ") + "...");
								else tmp1 = item.title;
								var tmp2;
								if(item.description.length > 80) tmp2 = ($.trim(item.description).substring(0, 80).split(" ").slice(0, -1).join(" ") + "...");
								else tmp2 = item.description;
								requestToAdd+='<tr class="requestLine'+adding+'" style="height:30px;" data-id="'+item.id+'"><td></td>\
										<td>'+item.name+'</td>\
										<td>'+tmp1+'</td>\
										<td>'+tmp2+'</td></tr>';
							});
							
							var pagination='Pages : <span class="backFull">&lt;&lt;</span> <span class="back">&lt;</span> ';
							for(var i = currentPage-3;i<currentPage;i++){
								if(i>0){
									pagination+='<span class="others">'+i+'</span> ';
								}
							}
							pagination+='<span class="currentPage">'+currentPage+'</span> ';
							for(var i = currentPage+1;i<=currentPage+3;i++){
								var count = Math.ceil(Object.keys(creationsList).length/21);
								if(i<=count){
									pagination+='<span class="others">'+i+'</span> ';
								}
							}
							pagination+='<span class="next">&gt;</span> <span class="nextFull">&gt;&gt;</span>';
							
							var date = new Date(userInfo.updated*1000);
							var dString = ('0'+date.getDate()).slice(-2)+'/'+('0'+(date.getMonth()+1)).slice(-2)+'/'+date.getFullYear()+' à '+('0'+(date.getHours()+1)).slice(-2)+'h'+('0'+(date.getMinutes()+1)).slice(-2);
							
							var profile= '\
								<div id="contentHolder" class="added">\
								<div class="separator"><p>'+userInfo.name+'</p></div>\
								<img style="margin-top: 10px;margin-left:20px;border: 1px solid black;max-width:80px;max-height:80px;" src="http:'+userInfo.picture+'">\
								<div class="leftBordered" style="position: absolute;top: 50px;left: 130px;">\
									<p>Créations : '+userInfo.totalCreations+'</p>\
									<br />\
									<p>Réputation : '+userInfo.reput+'</p>\
									<br />\
									<p>Dernière connexion : Le '+dString+'</p>\
								</div>\
								<div class="separator"><p>Créations</p></div>\
								<div class="navigation"><p>'+pagination+'</p></div>\
								<ul class="creationsList">'+creationsToAdd+'</ul>\
								<div class="navigation bottom"><p>'+pagination+'</p></div>\
								<p id="addAvat" style="margin-top: -28px;cursor:pointer;">Ajouter un avatar</p>\
								<div class="separator"><p>Demandes en cours</p></div>\
								<table class="requestList">\
									<tr class="headers"><th></th><th>Nom</th><th>Titre</th><th>Demande</th></tr>\
									'+requestToAdd+'</table>\
								<div id="bottomLoader"></div>\
								</div>';
							
							$('#wait').hide();
							$(profile).hide().prependTo('#content').show('slow', function(){
								$("#contentHolder").mCustomScrollbar();
								$('#content').css('margin-top','16px');
							});
							
							paginUpdateCreations();
						});
					});
					
				});
			});
		}
	});
	
	$('#home').click(function(){
		if(!$(this).is('.selected')  && !$('#contentHolder').is(':animated')){
			if($('#content').is('[style]')) $('#content').removeAttr('style');
			//$('#contentHolder').css('margin-top','0px');
			$('#contentHolder').slideUp('slow', function(){
				$('#contentHolder').css('margin-top','0px');
				$('.selected').removeClass('selected');
				$('#home').addClass('selected');
				$('#contentHolder').remove();
				$(defaultPage).hide().appendTo('#content').show('slow');
				$("#contentHolder").mCustomScrollbar();
			});
		}
	});
});