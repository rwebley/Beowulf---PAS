function FullScreenControl() {
}
FullScreenControl.prototype = new GControl();

FullScreenControl.prototype.initialize = function(map) {
  var container = document.createElement('div');
  var switchDiv = document.createElement('div');
  this.setButtonStyle_(switchDiv);
  container.appendChild(switchDiv);
  switchDiv.appendChild(document.createTextNode('Fullscreen'));
  GEvent.addDomListener(switchDiv, 'click', function() {
	  	var mapNode = 'mapofresults';
		var aW = 700;
		var aH = 500;

		var winW = 0, winH = 0;
		if (parseInt(navigator.appVersion)>3) {
			if (navigator.appName=="Netscape") {
				winW = window.innerWidth;
				winH = window.innerHeight;
			}
			if (navigator.appName.indexOf("Microsoft")!=-1) {
				winW = document.body.offsetWidth;
				winH = document.body.offsetHeight;
			}
		}
		
		if(""+$(mapNode.id).getAttribute("w") +"x"+$(mapNode.id).getAttribute("h") != ""+aW+"x"+aH) {
			$($(mapNode.id).getAttribute("c")).insertBefore($(mapNode.id), $($(mapNode.id).getAttribute("c")).firstChild);
			$(mapNode.id).style.width = $(mapNode.id).getAttribute("w")+"px";
			$(mapNode.id).style.height = $(mapNode.id).getAttribute("h")+"px";
			$(mapNode.id).style.position = "relative";
			$(mapNode.id).style.left = "0px";
			$(mapNode.id).style.top = "0px";
			map.checkResize();
			this.innerHTML='Fullscreen';
		} else {
			var objBody = document.getElementsByTagName("body").item(0);
			objBody.insertBefore($(mapNode.id), objBody.firstChild);
			$(mapNode.id).style.position = "absolute";
			$(mapNode.id).style.zIndex = 999;
			$(mapNode.id).style.width = "100%";
			$(mapNode.id).style.height = "100%";
			$(mapNode.id).style.left = "0px";
			$(mapNode.id).style.top = "0px";
			$(mapNode.id).scrollTo();
			map.checkResize();
			this.innerHTML='Normal';
		}
		



	});

  map.getContainer().appendChild(container);
  return container;
}

// By default, the control will appear in the top left corner of the
// map with 7 pixels of padding.
FullScreenControl.prototype.getDefaultPosition = function() {
  return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7, 100));
}

FullScreenControl.prototype.setButtonStyle_ = function(button) {
  button.style.color = "#000000";
  button.style.backgroundColor = "white";
  button.style.font = "small Arial";
  button.style.border = "2px outset black";
  button.style.padding = "0px";
  button.style.textAlign = "center";
  button.style.width = "5em";
  button.style.cursor = "pointer";
}