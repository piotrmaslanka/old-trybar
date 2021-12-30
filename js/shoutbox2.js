function shoutbox_load(data, textStatus, jqXHR) {
	var html = '';
	
	var classType = 'content';
	
	for (i in data) {
		var row = data[i];
		
		html += '<div class="'+classType+'">';
		html += '<div class="header"><span class="n1">'+row[1].substr(11, 5)+'</span> ';
		html += '<span class="n2">';
		if (row[4]) html += '<span style="color: #ed8c16;">&bull;';		
		html += '<a style="color: #ed8c16; text-decoration: none;" href="czyjs_profil.php?id='+row[0]+'">'+row[3]+'</a></span></span></div><div class="content">';
		html += row[2].replace('\\\'', "'").replace('\\\'', "'").replace('\\\\','\\');
		html += '</div></div>';
		
		classType = (classType == 'content' ? 'content b' : 'content');		
	}
	
	$("#shoutbox_content").html(html);
	
	var shoutbox_content_h = $("#shoutbox .init").height();
	var shoutbox_scroller_h = $("#shoutbox_content").height();
	
	if (shoutbox_scroller_h <= shoutbox_content_h)	
		$("#shoutbox_content").css('top', '0px');
	else
		$("#shoutbox_content").css('top', '-'+(shoutbox_scroller_h - shoutbox_content_h)+'px');
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
	var sbe = $("#shoutbox input");
	sbe.keyup(function(e) {
		if(e.keyCode == 13) {
			shoutbox_call(sbe.val());
			sbe.val('');
		}
	});	
	shoutbox_call();
}

window.onload = shoutbox_setup;