var xhr;
function TSLoad(elementid,frompage,timeable,data,onsuccess){
	timeable = typeof timeable !== 'undefined' ? timeable : true;
	
	
	if(xhr) xhr.abort();
	if(timeable){
		setTimeout(function(){TSLoad(elementid,frompage,false,data,onsuccess)}, 10);
	}else{
		if($("#"+elementid).length>0){
			var typ = "GET";
			if(data!= null && data.length > 0 ){
				typ = "POST";
			}
			xhr = $.ajax({
			type: typ,
			data: data,
			url: frompage,
			success: function(data) {
			  $("#"+elementid).html(data);
			  if(typeof onsuccess !== 'undefined'){
				onsuccess();
			  }
			}
		  });/**/
		}else{
			alert("Action has not been performed! Element '"+elementid+"' not found. Error 0x19049284");
		}
	}
	return false;//do not execute a href click
}


function showFilterBox(tableid, colid, filterboxpath){
	if($("#filterbox_"+colid).css("display") == "none"){
		TSLoad("filterbox_"+colid,filterboxpath,timeable=true,data=null,function(){	
			$("#filterbox_"+colid).css({ 
				"position": "absolute",
				"top": ($("#filter_"+colid).position().top+30) + "px",
				"left": $("#filter_"+colid).position().left + "px"
			});
			$("#filterbox_"+colid).fadeIn();
		});
	}else{
		$("#filterbox_"+colid).hide();
	}
	return false;//do not execute a href click
}
function showMenuBox(tableid, menuboxpath){
	if($("#menuboxbox_"+tableid).css("display") == "none"){
		TSLoad("menuboxbox_"+tableid,menuboxpath,timeable=true,data=null,function(){
		$("#menuboxbox_"+tableid).css({
				"position": "absolute",
				"top": ($("#menuboxicon_"+tableid).position().top+30) + "px",
				"right": (Math.round($(window).width()-$("#menuboxicon_"+tableid).position().left)-30) + "px"
			});
			$("#menuboxbox_"+tableid).fadeIn();
		});
		
	}else{
		$("#menuboxbox_"+tableid).hide();
	}
	return false;//do not execute a href click
}

function applyFilterBox(tableid, colid, filterboxpath){
	var data = $("#form_"+tableid).serialize();
	TSLoad("filterbox_"+tableid+"_"+colid,filterboxpath,true,data);
	$("#filterbox_"+tableid+"_"+colid).toggle();
	return false;//do not execute a href click
}
