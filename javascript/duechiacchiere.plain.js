function clear_field(event) {
	var element;
	if (!event) var event = window.event;
	if (event.target) element = event.target;
	else if (event.srcElement) element = event.srcElement;
	if(element.value == element.defaultValue && element.className.indexOf('button') == -1) element.value = '';
}
function reset_field(event) {
	var element;
	if (!event) var event = window.event;
	if (event.target) element = event.target;
	else if (event.srcElement) element = event.srcElement;
	if(element.value == '') element.value = element.defaultValue;
}
function comment_reply(author,parent) {
	var comment_text = document.getElementById('comment-text');
	var comment_parent = document.getElementById('comment_parent');
	comment_form = document.getElementById('comment-form');
	current_comment = document.getElementById('comment-'+parent);
	comment_form.parentNode.removeChild(comment_form);
	current_comment.appendChild(comment_form);
	
	comment_parent.value = parent;
	if ( comment_text.value == comment_text.defaultValue) comment_text.value = "@" + author + ": ";
	else comment_text.value += "\n@" + author + ": ";
	comment_text.focus();
}
function current_scroll_y() {
	if (document.documentElement && document.documentElement.scrollTop){
		return document.documentElement.scrollTop;
	}
	else{
		return document.body.scrollTop;
	}
}
function smooth_top() {
	var y_pos = current_scroll_y();
	if ( y_pos > 2 ) {
		step = Math.round( y_pos / 2 );
		window.scrollBy(0,-step);
		setTimeout('smooth_top()', 25);
	}
	else{
		window.scrollBy(0,-y_pos);		
	}
	return false;
}
function check_location() {
	var y_pos = current_scroll_y();
	var tgn = document.getElementById('toggle-navigation');
	var width = (document.documentElement.clientWidth)?document.documentElement.clientWidth:window.innerWidth;
	back_to_top_button = document.getElementById('back-to-top');

	// Display a "back to top" button, if we are far enough from the top
	if (y_pos >= 300) {
		back_to_top_button.style.display = 'block';
	}
	// or hide this button, if we are too close
	else {
		if (back_to_top_button.style.display == 'block') back_to_top_button.style.display = 'none';
	}
	if (tgn && width <= 768 && (document.getElementById('top-navigation').style.paddingTop == '' || parseInt(document.getElementById('top-navigation').style.paddingTop) > window.pageYOffset)){
		document.getElementById('top-navigation').style.paddingTop = window.pageYOffset+'px';
		document.getElementById('top-navigation').style.height = Math.max(document.getElementById('page-wrapper').offsetHeight-window.pageYOffset, 700)+'px';
	}
	
	if (!tgn && width <= 768){
		var top_icons = document.getElementById('search-form-fieldset');
		var menu_icon_link = document.createElement('a');
		menu_icon_link.setAttribute('href', '#');
		menu_icon_link.setAttribute('id', 'toggle-navigation');
		menu_icon_link.setAttribute('onclick', "tn=document.getElementById('top-navigation');tn.style.height=Math.max(document.getElementById('page-wrapper').offsetHeight-window.pageYOffset, 700)+'px';tn.style.paddingTop=window.pageYOffset+'px';if(tn.style.display=='none'){tn.style.display='block';this.style.backgroundPosition='-75px 0'}else{tn.style.display='none';this.style.backgroundPosition='-50px 0'}return false;");
		menu_icon_link.className = 'icon toggle-navigation full-screen-hidden';
		menu_icon_link.innerHTML = 'Menu';
		document.getElementById('top-navigation').style.display = 'none';
		top_icons.appendChild(menu_icon_link);
	}
	else if (tgn && width > 768){
		tgn.parentNode.removeChild(tgn);
		document.getElementById('top-navigation').style.height = '';
		document.getElementById('top-navigation').style.paddingTop = '';
		document.getElementById('top-navigation').style.display = 'block';
	}

	setTimeout("check_location()", 50);
}
function tabbed_top_navigation(){
	var top_navigation_a = document.getElementById('top-navigation').getElementsByTagName('A');
	var top_navigation_ul = document.getElementById('top-navigation').getElementsByTagName('UL');
	for (var i=0; i<top_navigation_a.length; i++) {
		top_navigation_a[i].onfocus=function(){
			if (typeof this.parentNode.parentNode != 'undefined' && this.parentNode.parentNode.id != 'top-navigation')
				this.parentNode.parentNode.className = 'keyfocus';
		}
		top_navigation_a[i].onblur=function(){
			if (typeof this.parentNode.parentNode != 'undefined' && this.parentNode.parentNode.id != 'top-navigation')
				this.parentNode.parentNode.className = 'keyunfocus';
		}
		top_navigation_a[i].onmouseover=function(){
			for (var i=0; i<top_navigation_ul.length; i++)
				top_navigation_ul[i].className = 'keyunfocus';
			for (var i=0; i<top_navigation_a.length; i++)
				top_navigation_a[i].blur();
		}
	}
}

// ClearType detection
function check_cleartype_font(){
	// IE has screen.fontSmoothingEnabled - sweet!
	if (typeof(screen.fontSmoothingEnabled) != "undefined")
		return screen.fontSmoothingEnabled;
	else try{
		// Create a 35x35 Canvas block.
		var canvasNode = document.createElement('canvas');
		canvasNode.width = "35";
		canvasNode.height = "35"

		// We must put this node into the body, otherwise Safari Windows does not report correctly.
		canvasNode.style.display = 'none';
		document.body.appendChild(canvasNode);
		var ctx = canvasNode.getContext('2d');

		// draw a black letter 'O', 32px Arial.
		ctx.textBaseline = "top";
		ctx.font = "32px Arial";
		ctx.fillStyle = "black";
		ctx.strokeStyle = "black";
		ctx.fillText("O", 0, 0);

		// start at (8,1) and search the canvas from left to right, top to bottom to see if we can find a non-black pixel.  If so we return true.
		for (var j = 8; j <= 32; j++){
			for (var i = 1; i <= 32; i++){
				var imageData = ctx.getImageData(i, j, 1, 1).data;
				var alpha = imageData[3];

				// font-smoothing must be on.
				if (alpha != 255 && alpha != 0) return true;
			}
		}

		// didn't find any non-black pixels - revert font to a standard one and return false.
		document.body.style.fontFamily = "'Trebuchet MS',Arial,Helvetica,sans-serif";
		document.body.style.fontSize = ".95em";
	}
	catch (ex) {
		// Something went wrong (for example, Opera cannot use the canvas fillText() method.  Return null (unknown).
		return null;
	}
}

window.onload = function(e){
	check_cleartype_font();
	check_location();
	tabbed_top_navigation();
	
	for(i in {input:'',textarea:''}) {
		elements = document.getElementsByTagName(i);
		for(j in elements){
			element = elements[j];
			if (element.addEventListener && (element.type=='text' || element.type=='textarea')){
				element.addEventListener('focus', clear_field, false);
				element.addEventListener('blur', reset_field, false);
			}
			else if (element.attachEvent && (element.type=='text' || element.type=='textarea')){
				element.attachEvent('onfocus', clear_field);
				element.attachEvent('onblur', reset_field);
			}
		}
	}
}