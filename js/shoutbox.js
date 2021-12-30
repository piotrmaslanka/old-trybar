function shoutbox_load(data, textStatus, jqXHR) {
	var html = '';
	
	var classType = 'A';
	
	for (i in data) {
		var row = data[i];
		
		html += '<div class="shout class'+classType+'">';
		html += '<div class="shout title"><span class="shout_when">'+row[1].substr(11, 5)+'</span> ';
		html += '<span class="shout_who">';
		if (row[4]) html += '<span style="color: #ed8c16;">&bull;</span>';		
		html += '<a href="czyjs_profil.php?id='+row[0]+'">'+row[3]+'</a></span></div>';
		html += row[2].replace('\\\'', "'").replace('\\\'', "'").replace('\\\\','\\');
		html += '</div>';
		
		classType = (classType == 'A' ? 'B' : 'A');		
	}
	
	$("#shoutbox_scroller").html(html);
	
	var shoutbox_content_h = $("#shoutbox_content").height();
	var shoutbox_scroller_h = $("#shoutbox_scroller").height();
	
	if (shoutbox_scroller_h <= shoutbox_content_h)	
		$("#shoutbox_scroller").css('top', '0px');
	else
		$("#shoutbox_scroller").css('top', '-'+(shoutbox_scroller_h - shoutbox_content_h)+'px');
}

function shoutbox_call(content) {	
	var url = 'wishmaster.php';
	
	if (content == null)
		$.ajax({dataType:'json', url:'wishmaster.php', success:shoutbox_load});
	else
		$.ajax({dataType:'json', url:'wishmaster.php', success:shoutbox_load, type:'POST', data:{content:content}});
}


function shoutbox_setup() {
	setInterval("shoutbox_call()", 5000);
	var sbe = $("#shoutbox_entry");
	sbe.keyup(function(e) {
		if(e.keyCode == 13) {
			shoutbox_call(sbe.val());
			sbe.val('');
		}
	});	
	shoutbox_call();
	
	
	var h = $("#content_area_content_rightmenu").height();
	
	$("#shoutbox_content").css('height', ''+(h-20)+'px');
}

window.onload = shoutbox_setup;