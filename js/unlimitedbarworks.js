function show_bar(barid)
{
	var i=1;
	for (;;i++)
	{
		x = document.getElementById('bar'+i);
		if (x == null) break;
		x.style.display = 'none';
	}
	document.getElementById('bar'+barid).style.display = 'block';
}

var sw = [null, false, false, false, false, false];

function scroll_rollover_td(swpos)
{
	var delta = parseInt($("img#sw"+swpos).css('left'));
	var isPicked = sw[swpos];
	
	if ((isPicked) && (delta < 40)) $("img#sw"+swpos).css('left', delta+3+'px');
	if ((!isPicked) && (delta > 10)) $("img#sw"+swpos).css('left', delta-3+'px');	
	
	if ((isPicked) && (delta == 40)) return;
	if ((!isPicked) && (delta == 10)) return;
	
	setTimeout("scroll_rollover_td("+swpos+")", 30);
}

function startscroll(x) { sw[x] = true; scroll_rollover_td(x); }
function stopscroll(x) { sw[x] = false; scroll_rollover_td(x); }