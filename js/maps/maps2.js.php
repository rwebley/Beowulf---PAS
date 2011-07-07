/*
function load () {
var map = document.getElementById("map");
if (GBrowserIsCompatible()) {
var gmap = new GMap2(map);
var mgrOptions = { borderPadding: 50, maxZoom: 15, trackMarkers: true };
var mgr = new MarkerManager(map, mgrOptions);
ggeox
gmap.addControl( new GSmallMapControl() );
gmap.addControl( new GMapTypeControl()) ;
gmap.addControl( new GOverviewMapControl(new GSize(100,100)) );
gmap.setCenter( new GLatLng(54.7,-4), 5 );

} else {
alert("Sorry, your browser cannot handle the true power of Google Maps");
}
}
*/
  /*  var iconBlue = new GIcon(); 
    iconBlue.image = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
    iconBlue.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
    iconBlue.iconSize = new GSize(12, 20);
    iconBlue.shadowSize = new GSize(22, 20);
    iconBlue.iconAnchor = new GPoint(6, 20);
    iconBlue.infoWindowAnchor = new GPoint(5, 1);

    var iconRed = new GIcon(); 
    iconRed.image = 'http://labs.google.com/ridefinder/images/mm_20_red.png';
    iconRed.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
    iconRed.iconSize = new GSize(12, 20);
    iconRed.shadowSize = new GSize(22, 20);
    iconRed.iconAnchor = new GPoint(6, 20);
    iconRed.infoWindowAnchor = new GPoint(5, 1);

    var customIcons = [];
    customIcons["coin"] = iconBlue;
    customIcons["Coin"] = iconBlue;
    customIcons["brooch"] = iconRed;

    function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
		var mm = new GMarkerManager(map); 
        map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		//map.addControl (new GHierarchicalMapTypeControl());
		map.addMapType(G_PHYSICAL_MAP);
        map.setCenter(new GLatLng(54.7,-4), 5);
		map.enableContinuousZoom();
		map.addControl(new GScaleControl());
        GDownloadUrl("http://localhost/redev/beowulf/ajax/mappingdata/id/<?php $this->partialLoop('partials/workflowstatus.phtml', $this->staffs) ?>?>/limit/500", function(data) {
          var xml = GXml.parse(data);
          var markers = xml.documentElement.getElementsByTagName("marker");
          for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute("name");
            var broadperiod = markers[i].getAttribute("broadperiod");
           	var type = markers[i].getAttribute("type");
            var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")),
                                    parseFloat(markers[i].getAttribute("lng")));
            var marker = createMarker(point, name, broadperiod, type);
            map.addOverlay(marker);
          }
        });
      }
    }

    function createMarker(point, name, broadperiod, type) {
      var marker = new GMarker(point, customIcons[type]);
      var html = "<b>" + name + "</b> <br/>" + broadperiod + type;
      GEvent.addListener(marker, 'click', function() {
        marker.openInfoWindowHtml(html);
      });
      return marker;
    }
    //</XMLCDATA>**/
function load () {	
if (GBrowserIsCompatible()) {

      // display the loading message
   // Display the map, with some controls and set the initial location 
       

        var map = new GMap2(document.getElementById("map"));
		var rings = this.rings;
		var point  = '51.519,-0.1265';
	    map.addControl(new GLargeMapControl());
		map.addControl(new GMenuMapTypeControl());
		//map.addControl (new GHierarchicalMapTypeControl());
		map.addMapType(G_PHYSICAL_MAP);
        map.setCenter(new GLatLng(<?php ;?>), 9 , G_SATELLITE_MAP);
		map.enableContinuousZoom();
		map.addControl(new GScaleControl());
	    
		
		

      var n=0;

      var icon = new GIcon();
      icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png";
      icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
      icon.iconSize = new GSize(12, 20);
      icon.shadowSize = new GSize(22, 20);
      icon.iconAnchor = new GPoint(6, 20);
      icon.infoWindowAnchor = new GPoint(5, 1);      

      iconblue = new GIcon(icon,"http://labs.google.com/ridefinder/images/mm_20_blue.png"); 
      icongreen = new GIcon(icon,"http://labs.google.com/ridefinder/images/mm_20_green.png"); 
      iconyellow = new GIcon(icon,"http://labs.google.com/ridefinder/images/mm_20_yellow.png"); 


      function createMarker(point,name,html,icon) {
        var marker = new GMarker(point, {icon:icon});
        GEvent.addListener(marker, "click", function() {
          marker.openInfoWindowHtml(html);
        });
        return marker;
      }

      // new strategy - read the XML first, THEN create the map


      // read the markers from the XML
      GDownloadUrl("http://localhost/redev/beowulf/ajax/mappingdata/id/3/limit/300", function (doc) {
        var gmarkersA = [];      
        var gmarkersB = [];      
        var xmlDoc = GXml.parse(doc);
        var markers = xmlDoc.documentElement.getElementsByTagName("marker");

          
        for (var i = 0; i < markers.length; i++) {
          // obtain the attribues of each marker
          var lat = parseFloat(markers[i].getAttribute("lat"));
          var lng = parseFloat(markers[i].getAttribute("lng"));
          var point = new GLatLng(lat,lng);
          var name = markers[i].getAttribute("name");
          var type = markers[i].getAttribute("type");
          var broadperiod = markers[i].getAttribute("broadperiod");

          // split the markers into four arrays, with different GIcons
          if (type == 'coin') {
             var marker = createMarker(point,name,broadperiod+"<br>Type: "+type,icon);
             gmarkersA.push(marker);
          }
          else  {
             var marker = createMarker(point,name,broadperiod+"<br>Type: "+type,iconblue);
             gmarkersB.push(marker);
          }
        }

     
        var mm = new GMarkerManager(map, {borderPadding:1});

        mm.addMarkers(gmarkersA,0,17);
        mm.addMarkers(gmarkersB,10,17);
        mm.refresh();
		
      });
	  var point = new GLatLng(51.519,-0.1265);
	  rings = new BdccRangeRings((point), "#FFFFFF",3,0.5,null,17);
        map.addOverlay(rings);
		////
		
		
		
		
		
		
		////
		
    }

    // display a warning if the browser was not compatible
    else {
      alert("Sorry, the Google Maps API is not compatible with this browser");
    }
  
    // This Javascript is based on code provided by the
    // Blackpool Community Church Javascript Team
    // http://www.commchurch.freeserve.co.uk/   
    // http://econym.googlepages.com/index.htm
    
    //</XMLCDATA>
	}

window.onload = load;
window.onunload = GUnload;