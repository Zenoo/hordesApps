$('document').ready(function(){
	
	var expe=false;
	var attr,lastPosX,lastPosY;
	var lastPos = 'reset';
	var tempTable = new Array([]);
	var expPos=0;
	var listed=false;
	var listDone=false;
	var storedData;
	
	$('.mapCase').click(function(){
		//window.location.replace('http://map.zenoo.fr/zone.php?x='+$(this).attr('data-x')+'&y='+$(this).attr('data-y'));
		$('#pageCache').show();
		$('#pageCache').css('z-index','1000');
		$('body').css('overflow','hidden');
		$('#caseContentEdit').click(function(){
			$('#pageCache').hide();
			$('#pageCache').removeAttr('style');
			$('body').removeAttr('style');
		});
	});
	
	var cityX = +$('.mapCase[data-city]').attr('data-x');
	var cityY = +$('.mapCase[data-city]').attr('data-y');
	
	function isEven(num) { 
		return (num % 2) == 0;
	}
	
	$('.mapCase').each(function(){
		var currentX=+$(this).attr('data-x');
		var currentY=+$(this).attr('data-y');
		
		var realX = currentX-cityX;
		var realY = currentY-cityY;
		
		// ********* 11 KM *********
		
		//Inside radius
		if(Math.sqrt((realX*realX)+(realY*realY))<11.5){
			var tempX,tempY;
			
			//Left border
			if($(this).prev().length){
				tempX = ($(this).prev().attr('data-x')) - cityX;
				tempY = ($(this).prev().attr('data-y')) - cityY;
				if(Math.sqrt((tempX*tempX)+(tempY*tempY))>11.5){
					$(this).addClass('leftborderBlue');
				}
			}
			
			//Right border
			if($(this).next().length){
				tempX = ($(this).next().attr('data-x')) - cityX;
				tempY = ($(this).next().attr('data-y')) - cityY;
				if(Math.sqrt((tempX*tempX)+(tempY*tempY))>11.5){
					$(this).addClass('rightborderBlue');
				}
			}
			
			//Top border
			if($(this).parent().prev().children().length){
				tempX = ($(this).parent().prev().children().eq($(this).index()).attr('data-x')) - cityX;
				tempY = ($(this).parent().prev().children().eq($(this).index()).attr('data-y')) - cityY;
				if(Math.sqrt((tempX*tempX)+(tempY*tempY))>11.5){
					$(this).addClass('topborderBlue');
				}
			}
			
			//Bot border
			if($(this).parent().next().children().length){
				tempX = ($(this).parent().next().children().eq($(this).index()).attr('data-x')) - cityX;
				tempY = ($(this).parent().next().children().eq($(this).index()).attr('data-y')) - cityY;
				if(Math.sqrt((tempX*tempX)+(tempY*tempY))>11.5){
					$(this).addClass('botborderBlue');
				}
			}
		}
		
		// ********* 18 KM *********
		
		//Outside radius
		if(Math.sqrt((realX*realX)+(realY*realY))>17.5){
			var tempAttr,tempX,tempY;
			
			//Left border
			if($(this).prev().length){
				tempX = ($(this).prev().attr('data-x')) - cityX;
				tempY = ($(this).prev().attr('data-y')) - cityY;
				if(Math.sqrt((tempX*tempX)+(tempY*tempY))<17.5){
					$(this).addClass('leftborderRed');
				}
			}
			
			//Right border
			if($(this).next().length){
				tempX = ($(this).next().attr('data-x')) - cityX;
				tempY = ($(this).next().attr('data-y')) - cityY;
				if(Math.sqrt((tempX*tempX)+(tempY*tempY))<17.5){
					$(this).addClass('rightborderRed');
				}
			}
			
			//Top border
			if($(this).parent().prev().children().length){
				tempX = ($(this).parent().prev().children().eq($(this).index()).attr('data-x')) - cityX;
				tempY = ($(this).parent().prev().children().eq($(this).index()).attr('data-y')) - cityY;
				if(Math.sqrt((tempX*tempX)+(tempY*tempY))<17.5){
					$(this).addClass('topborderRed');
				}
			}
			
			//Bot border
			if($(this).parent().next().children().length){
				tempX = ($(this).parent().next().children().eq($(this).index()).attr('data-x')) - cityX;
				tempY = ($(this).parent().next().children().eq($(this).index()).attr('data-y')) - cityY;
				if(Math.sqrt((tempX*tempX)+(tempY*tempY))<17.5){
					$(this).addClass('botborderRed');
				}
			}
		}
		
		
		// ********* DIRECTIONS *********
		
		//City borders
		if(realX==1 && realY==0) $(this).addClass('leftborder');
		if(realX==-1 && realY==0) $(this).addClass('rightborder');
		if(realX==0 && realY==1) $(this).addClass('topborder');
		if(realX==0 && realY==-1) $(this).addClass('botborder');
		
		if(realX > 0){
			// E/NE
			if(isEven(realX)){
				if(realX == (cityY-currentY)*2){
					$(this).addClass('leftborder');
					$(this).addClass('topborder');
				}
			}
			else{
				if(realX-1 == (cityY-currentY)*2){
					$(this).addClass('topborder');
				}
			}
			
			// E/SE
			if(isEven(realX)){
				if(realX == (cityY-currentY)*-2){
					$(this).addClass('leftborder');
					$(this).addClass('botborder');
				}
			}
			else{
				if(realX-1 == (cityY-currentY)*-2){
					$(this).addClass('botborder');
				}
			}
			
		}
		else{
			// W/NW
			if(isEven(realX)){
				if(realX == (cityY-currentY)*-2){
					$(this).addClass('topborder');
					$(this).addClass('rightborder');
				}
			}
			else{
				if(realX+1 == (cityY-currentY)*-2){
					$(this).addClass('topborder');
				}
			}
			
			// W/SW
			if(isEven(realX)){
				if(realX == (cityY-currentY)*2){
					$(this).addClass('botborder');
					$(this).addClass('rightborder');
				}
			}
			else{
				if(realX+1 == (cityY-currentY)*2){
					$(this).addClass('botborder');
				}
			}
		}
		
		if(realY < 0){
			// N/NE
			if(isEven(realY)){
				if(realY == (cityX-currentX)*2){
					$(this).addClass('rightborder');
					$(this).addClass('botborder');
				}
			}
			else{
				if(realY+1 == (cityX-currentX)*2){
					$(this).addClass('rightborder');
				}
			}
			
			// N/NW
			if(isEven(realY)){
				if(realY == (cityX-currentX)*-2){
					$(this).addClass('leftborder');
					$(this).addClass('botborder');
				}
			}
			else{
				if(realY+1 == (cityX-currentX)*-2){
					$(this).addClass('leftborder');
				}
			}
			
		}
		else{
			// S/SW
			if(isEven(realY)){
				if(realY == (cityX-currentX)*2){
					$(this).addClass('topborder');
					$(this).addClass('leftborder');
				}
			}
			else{
				if(realY-1 == (cityX-currentX)*2){
					$(this).addClass('leftborder');
				}
			}
			
			// S/SE
			if(isEven(realY)){
				if(realY == (cityX-currentX)*-2){
					$(this).addClass('topborder');
					$(this).addClass('rightborder');
				}
			}
			else{
				if(realY-1 == (cityX-currentX)*-2){
					$(this).addClass('rightborder');
				}
			}
		}
		
		
		
		
		
		
	});
	
	// ******* EXPE *******
	function setTileHud(matched){
		//var matched = typeof matched !== 'undefined' ? matched : $(".mapCase[data-exp!=''][data-exp]");
		var found=false;
		for(var i=0;i<matched.length+1;i++){
			var currentId = $(matched[i]).attr('data-expid');
			var comp1,comp2,comp1exp,comp2exp;
			var matchedExp = +$(matched[i]).attr('data-exp');
			
			//*****
			//*x.X*
			//*****
			comp1 = $(matched[i]).prev();
			comp1exp = +comp1.attr('data-exp');
			if(comp1.is("[data-exp!=''][data-exp]") && comp1.is("[data-expid='"+currentId+"']") && ((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) || (comp1exp == matchedExp+1 || comp1exp == matchedExp+2))) {
				//x.X
				//..x
				comp2 = $(matched[i]).parent().parent().children().eq($(matched[i]).parent().index()+1).children().eq($(matched[i]).index());
				comp2exp = +comp2.attr('data-exp');
				if(comp2.is("[data-exp!=''][data-exp]") && comp2.is("[data-expid='"+currentId+"']") && ((comp2exp == matchedExp-1 || comp2exp == matchedExp-2) || (comp2exp == matchedExp+1 || comp2exp == matchedExp+2))){
					
					//1.2  OR 3.2
					//..3     ..1
					if(((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) && (comp2exp == matchedExp+1 || comp2exp == matchedExp+2)) || ((comp1exp == matchedExp+1 || comp1exp == matchedExp+2) && (comp2exp == matchedExp-1 || comp2exp == matchedExp-2))){
						found=true;
						//Building
						if ($(matched[i]).is('[data-build]')) {
							$(matched[i]).css('background-image','url(../resources/mapHud/A_0011.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
						}
						//Empty
						else{
							$(matched[i]).css('background-image','url(../resources/mapHud/A_0011.png)');
						}
					}
				}
				
				//..x
				//x.X
				comp2 = $(matched[i]).parent().parent().children().eq($(matched[i]).parent().index()-1).children().eq($(matched[i]).index());
				comp2exp = +comp2.attr('data-exp');
				if(comp2.is("[data-exp!=''][data-exp]") && comp2.is("[data-expid='"+currentId+"']") && ((comp2exp == matchedExp-1 || comp2exp == matchedExp-2) || (comp2exp == matchedExp+1 || comp2exp == matchedExp+2))){
					
					//..3  OR ..1
					//1.2     3.2
					if(((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) && (comp2exp == matchedExp+1 || comp2exp == matchedExp+2)) || ((comp1exp == matchedExp+1 || comp1exp == matchedExp+2) && (comp2exp == matchedExp-1 || comp2exp == matchedExp-2))){
						found=true;
						//Building
						if ($(matched[i]).is('[data-build]')) {
							$(matched[i]).css('background-image','url(../resources/mapHud/A_1001.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
						}
						//Empty
						else{
							$(matched[i]).css('background-image','url(../resources/mapHud/A_1001.png)');
						}
					}
				}
				
				//x.X.x
				comp2 = $(matched[i]).next();
				comp2exp = +comp2.attr('data-exp');
				if(comp2.is("[data-exp!=''][data-exp]") && comp2.is("[data-expid='"+currentId+"']") && ((comp2exp == matchedExp-1 || comp2exp == matchedExp-2) || (comp2exp == matchedExp+1 || comp2exp == matchedExp+2))){
					
					//123 OR 321
					if(((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) && (comp2exp == matchedExp+1 || comp2exp == matchedExp+2)) || ((comp1exp == matchedExp+1 || comp1exp == matchedExp+2) && (comp2exp == matchedExp-1 || comp2exp == matchedExp-2))){
						found=true;
						//Building
						if ($(matched[i]).is('[data-build]')) {
							$(matched[i]).css('background-image','url(../resources/mapHud/A_0101.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
						}
						//Empty
						else{
							$(matched[i]).css('background-image','url(../resources/mapHud/A_0101.png)');
						}
					}
				}
				
				//x.X
				if(!found){
					//Building
					if ($(matched[i]).is('[data-build]')) {
						$(matched[i]).css('background-image','url(../resources/mapHud/A_0001.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
					}
					//Empty
					else{
						$(matched[i]).css('background-image','url(../resources/mapHud/A_0001.png)');
					}
				}
				
			}
			
			else{
				
				//*****
				//*..X*
				//*..x*
				//*****
				comp1=$(matched[i]).parent().parent().children().eq($(matched[i]).parent().index()+1).children().eq($(matched[i]).index());
				comp1exp = +comp1.attr('data-exp');
				found=false;
				if(comp1.is("[data-exp!=''][data-exp]") && comp1.is("[data-expid='"+currentId+"']") && ((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) || (comp1exp == matchedExp+1 || comp1exp == matchedExp+2))) {
					//X.x
					//x..
					comp2 = $(matched[i]).next();
					comp2exp = +comp2.attr('data-exp');
					if(comp2.is("[data-exp!=''][data-exp]") && comp2.is("[data-expid='"+currentId+"']") && ((comp2exp == matchedExp-1 || comp2exp == matchedExp-2) || (comp2exp == matchedExp+1 || comp2exp == matchedExp+2))){
						
						//2.3  OR 2.1
						//1..     3..
						if(((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) && (comp2exp == matchedExp+1 || comp2exp == matchedExp+2)) || ((comp1exp == matchedExp+1 || comp1exp == matchedExp+2) && (comp2exp == matchedExp-1 || comp2exp == matchedExp-2))){
							found=true;
							//Building
							if ($(matched[i]).is('[data-build]')) {
								$(matched[i]).css('background-image','url(../resources/mapHud/A_0110.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
							}
							//Empty
							else{
								$(matched[i]).css('background-image','url(../resources/mapHud/A_0110.png)');
							}
						}
					}
					
					//..x
					//..X
					//..x
					comp2 = $(matched[i]).parent().parent().children().eq($(matched[i]).parent().index()-1).children().eq($(matched[i]).index());
					comp2exp = +comp2.attr('data-exp');
					if(comp2.is("[data-exp!=''][data-exp]") && comp2.is("[data-expid='"+currentId+"']") && ((comp2exp == matchedExp-1 || comp2exp == matchedExp-2) || (comp2exp == matchedExp+1 || comp2exp == matchedExp+2))){
						
						//..3  OR ..1
						//..2     ..2
						//..1     ..3
						if(((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) && (comp2exp == matchedExp+1 || comp2exp == matchedExp+2)) || ((comp1exp == matchedExp+1 || comp1exp == matchedExp+2) && (comp2exp == matchedExp-1 || comp2exp == matchedExp-2))){
							found=true;
							//Building
							if ($(matched[i]).is('[data-build]')) {
								$(matched[i]).css('background-image','url(../resources/mapHud/A_1010.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
							}
							//Empty
							else{
								$(matched[i]).css('background-image','url(../resources/mapHud/A_1010.png)');
							}
						}
					}
					
					//..X
					//..x
					if(!found){
						//Building
						if ($(matched[i]).is('[data-build]')) {
							$(matched[i]).css('background-image','url(../resources/mapHud/A_0010.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
						}
						//Empty
						else{
							$(matched[i]).css('background-image','url(../resources/mapHud/A_0010.png)');
						}
					}
					
				}
				
				else{
					
					//*****
					//*X.x*
					//*****
					comp1=$(matched[i]).next();
					comp1exp = +comp1.attr('data-exp');
					found=false;
					if(comp1.is("[data-exp!=''][data-exp]") && comp1.is("[data-expid='"+currentId+"']") && ((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) || (comp1exp == matchedExp+1 || comp1exp == matchedExp+2))) {
					
						//x..
						//X.x
						comp2 = $(matched[i]).parent().parent().children().eq($(matched[i]).parent().index()-1).children().eq($(matched[i]).index());
						comp2exp = +comp2.attr('data-exp');
						if(comp2.is("[data-exp!=''][data-exp]") && comp2.is("[data-expid='"+currentId+"']") && ((comp2exp == matchedExp-1 || comp2exp == matchedExp-2) || (comp2exp == matchedExp+1 || comp2exp == matchedExp+2))){
							
							//1..  OR 3..
							//2.3     2.1
							if(((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) && (comp2exp == matchedExp+1 || comp2exp == matchedExp+2)) || ((comp1exp == matchedExp+1 || comp1exp == matchedExp+2) && (comp2exp == matchedExp-1 || comp2exp == matchedExp-2))){
								found=true;
								//Building
								if ($(matched[i]).is('[data-build]')) {
									$(matched[i]).css('background-image','url(../resources/mapHud/A_1100.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
								}
								//Empty
								else{
									$(matched[i]).css('background-image','url(../resources/mapHud/A_1100.png)');
								}
							}
						}
						
						//X.x
						if(!found){
							//Building
							if ($(matched[i]).is('[data-build]')) {
								$(matched[i]).css('background-image','url(../resources/mapHud/A_0100.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
							}
							//Empty
							else{
								$(matched[i]).css('background-image','url(../resources/mapHud/A_0100.png)');
							}
						}
						
					}
					
					else{
						
						//*****
						//*x..*
						//*X..*
						//*****
						comp1=$(matched[i]).parent().parent().children().eq($(matched[i]).parent().index()-1).children().eq($(matched[i]).index());
						comp1exp = +comp1.attr('data-exp');
						found=false;
						if(comp1.is("[data-exp!=''][data-exp]") && comp1.is("[data-expid='"+currentId+"']") && ((comp1exp == matchedExp-1 || comp1exp == matchedExp-2) || (comp1exp == matchedExp+1 || comp1exp == matchedExp+2))) {
							
							//x..
							//X..
							if(!found){
								//Building
								if ($(matched[i]).is('[data-build]')) {
									$(matched[i]).css('background-image','url(../resources/mapHud/A_1000.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
								}
								//Empty
								else{
									$(matched[i]).css('background-image','url(../resources/mapHud/A_1000.png)');
								}
							}
							
						}
						
						else{
							//*****
							//*...*
							//*.X.*
							//*...*
							//*****
							//Building
							if ($(matched[i]).is('[data-build]')) {
								$(matched[i]).css('background-image','url(../resources/mapHud/A_0000.png), url(../resources/mapHud/'+$(matched[i]).attr('data-build')+'.png)');
							}
							//Empty
							else{
								$(matched[i]).css('background-image','url(../resources/mapHud/A_0000.png)');
							}
						}
					}
				}
			}
		}
	}
	
	function drawRoadTile(mapCase, exp_id){
		var exp_id = typeof exp_id !== 'undefined' ? exp_id : -1;
		
		//Building
		attr = mapCase.attr('data-build');
		if (typeof attr !== typeof undefined && attr !== false) {
			if(mapCase.css('background-image').indexOf('expe.png') == -1){
				var backg=mapCase.css('background-image');
				mapCase.css('background-image','url(../resources/mapHud/expe.png), '+backg);
				mapCase.attr('data-exp',expPos);
				mapCase.attr('data-expid',exp_id);
				expPos++;
			}
		}
		//Empty
		else{
			mapCase.css('background-image','url(../resources/mapHud/expe.png)');
			mapCase.attr('data-exp',expPos);
			mapCase.attr('data-expid',exp_id);
			expPos++;
		}
		
		lastPosX=+mapCase.attr('data-x');
		lastPosY=+mapCase.attr('data-y');
		
		if (lastPos != 'reset') setTileHud(lastPos);
		setTileHud(mapCase);
		
		lastPos=mapCase;
	}
	
	function drawRoadLine(end, exp_id){
		var exp_id = typeof exp_id !== 'undefined' ? exp_id : -1;
		/*
		??
		... <-
		??
		*/
		if(lastPosX == +end.attr('data-x')){
			/*
			End
			... <-
			Start
			*/
			if(lastPosY > +end.attr('data-y')){
				for(i = lastPosY;i>+end.attr('data-y')+1;i--){
					drawRoadTile(end.parent().parent().children().eq(i).children().eq(end.index()), exp_id);
				}
			}
			
			/*
			Start
			... <-
			End
			*/
			else{
				for(i = lastPosY+2;i<+end.attr('data-y')+1;i++){
					drawRoadTile(end.parent().parent().children().eq(i).children().eq(end.index()), exp_id);
				}
			}
		}
		
		/*
		?? ... ??
			^
		*/
		else if(lastPosY == +end.attr('data-y')){
			/*
			Start ... End
				   ^
			*/
			if(lastPosX < +end.attr('data-x')){
				for(i = lastPosX+2;i<+end.attr('data-x')+1;i++){
					drawRoadTile(end.parent().children().eq(i), exp_id);
				}
			}
			
			/*
			End ... Start
				 ^
			*/
			else{
				for(i = lastPosX;i>+end.attr('data-x')+1;i--){
					drawRoadTile(end.parent().children().eq(i), exp_id);
				}
			}
		}
		
		//Not aligned
		else if(lastPosX !== undefined && lastPosX !== null){
			drawRoadLine(end.parent().parent().children().eq(lastPosY+1).children().eq(end.index()), exp_id);
			drawRoadLine(end, exp_id);
		}
		
		drawRoadTile(end, exp_id);
		
	}
	
	function resetMapCase(){
		$('.mapCase').each(function(){
				
			//Check modified
			attr = $(this).attr('data-exp');
			if (typeof attr !== typeof undefined && attr !== false) {
				
				//Building
				attr = $(this).attr('data-build');
				if (typeof attr !== typeof undefined && attr !== false) {
					$(this).css('background-image','url(../resources/mapHud/'+$(this).attr('data-build')+'.png)');
				}
				//Empty
				else{
					$(this).css('background-image','none');
				}
				$(this).attr('data-exp', '');
				$(this).attr('data-expid', '');
			}
			
		});
	}
	
	$('div#expe').click(function(e){
		//Reset
		if(expe){
			$('#valid').hide();
			$('.mapCase').unbind("click").click(function(){
				//window.location.replace('http://map.zenoo.fr/zone.php?x='+$(this).attr('data-x')+'&y='+$(this).attr('data-y'));
				$('#pageCache').show();
				$('#pageCache').css('z-index','1000');
				$('body').css('overflow','hidden');
				$('#caseContentEdit').click(function(){
					$('#pageCache').hide();
					$('#pageCache').removeAttr('style');
					$('body').removeAttr('style');
				});
			});
			
			resetMapCase();
			
			expe=false;
			$(this).css('background','black');
			lastPosX = null;
			lastPosY = null;
			lastPos = 'reset';
			expPos=0;
		}
		//Trace
		else{
			$('#valid').show();
			$('#valid').click(function(){
				var tabX ='';
				var tabY = '';
				var repeater = $(".mapCase[data-expid='-1'][data-exp!=''][data-exp]").length;
				for(i=0;i<repeater;i++){
					if($(".mapCase[data-expid='-1'][data-exp='"+i+"']").attr('data-x') == undefined){
						repeater++;
					}
					else{
						tabX+=$(".mapCase[data-expid='-1'][data-exp='"+i+"']").attr('data-x')+',';
						tabY+=$(".mapCase[data-expid='-1'][data-exp='"+i+"']").attr('data-y')+',';
					}
				}
				$.post( "tracing.php", { 'tabX': tabX, 'tabY': tabY, 'mapId' : $('#cityID').attr('data-id'), 'name' : $('#cityID').attr('data-name')})
					.done(function(){
						resetMapCase();
						lastPosX = null;
						lastPosY = null;
						lastPos = 'reset';
						expPos=0;
						listDone=false;
						$('#expeList p').remove();
						console.log('Posting done.');
					})
					.fail(function(){
						console.log('Posting failed.');
					});
			});
			lastPosX = null;
			lastPosY = null;
			lastPos = 'reset';
			expPos=0;
			expe=true;
			$(this).css('background','red');
			$('.mapCase').unbind("click").click(function(){
				//Traceroute
				drawRoadLine($(this));
			});
		}
	});
	
	//EXPE LISTING
	$('#expeList span').click(function(){
		
		//Show list
		if(!listed){
			//List never shown
			if(!listDone){
				$.post( "tracing.php", { 'mapId': +$('#cityID').attr('data-id') })
					.done(function(JSONdata){
						storedData = jQuery.parseJSON(JSONdata);
						for(var i=0;i<storedData.length;i++){
							var expeLength = ((storedData[i].x).match(/,/g) || []).length;
							$('#expeList').append('<p data-id="'+i+'">'+expeLength+' <img src="../resources/icons/small_pa.gif"> N°'+storedData[i].id+' by '+storedData[i].pseudo+'</p>');
						}
						listed=true;
						listDone=true;
					});
			}
			
			//List already shown
			else{
				$('#expeList p').toggle();
				listed=true;
			}
		}
		
		//Hide list
		else{
			$('#expeList p').toggle();
			resetMapCase();
			lastPosX = null;
			lastPosY = null;
			lastPos = 'reset';
			expPos=0;
			listDone=true;
			listed=false;
		}
	});
	
	//EXPE PRINTING
	$('#expeList').on('click', 'p', function(){ //Dyn click hack
		
		//If expe mode -> reset+cancel
		if(expe){
			$('#valid').hide();
			$('.mapCase').unbind("click").click(function(){
				//window.location.replace('http://map.zenoo.fr/zone.php?x='+$(this).attr('data-x')+'&y='+$(this).attr('data-y'));
				$('#pageCache').show();
				$('#pageCache').css('z-index','1000');
				$('body').css('overflow','hidden');
				$('#caseContentEdit').click(function(){
					$('#pageCache').hide();
					$('#pageCache').removeAttr('style');
					$('body').removeAttr('style');
				});
			});
			
			resetMapCase();
			
			expe=false;
			$(this).css('background','black');
			lastPosX = null;
			lastPosY = null;
			lastPos = 'reset';
			expPos=0;
		}
		
		var currentExpe = storedData[+$(this).attr('data-id')];
		var expeLength = ((currentExpe.x).match(/,/g) || []).length;
		var tabX=currentExpe.x.split(',');
		var tabY=currentExpe.y.split(',');
		for(var i=0;i<expeLength;i++){
			drawRoadTile($(".mapCase[data-x='"+tabX[i]+"'][data-y='"+tabY[i]+"']"), currentExpe.id);
		}
		
	});
	
	
	
});