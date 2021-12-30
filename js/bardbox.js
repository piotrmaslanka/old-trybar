function dropbox(obj, id) {
	var f = '<div class="select_opt" onclick="hideTip()">Zamknij</div>';
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 1)">1</div>';
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 2)">2</div>';	
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 3)">3</div>';	
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 4)">4</div>';	
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 5)">5</div>';	
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 6)">6</div>';	
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 7)">7</div>';	
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 8)">8</div>';	
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 9)">9</div>';	
	f += '<div class="select_opt" onclick="sel_dispatch('+id+', 10)">10</div>';	
	return tooltip(f, '' ,'width:55')
}

function sel_dispatch(id, value) {
	alert('Wpis nr '+id+' otrzymal ocene '+value);
	hideTip();
}