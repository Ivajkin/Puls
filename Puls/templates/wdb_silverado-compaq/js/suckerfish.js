sfHover = function() {
	var sfEls = document.getElementById("wdb_toolbar").getElementsByTagName("li");
	for (var i=0; i<sfEls.length; ++i) {
		sfEls[i].onmouseover=function() {
			clearTimeout(this.timer);
			if(this.className.indexOf(" sfhover") == -1)
				this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.timer = setTimeout(sfHoverOut.bind(this), 20);
		}
	}
}
function sfHoverOut() {
	clearTimeout(this.timer);
	this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
}
if (window.attachEvent) window.attachEvent("onload", sfHover);