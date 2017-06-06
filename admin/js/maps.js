// JavaScript Document

/*
map = null;

initial_zoom = 2;

cat_plurals = ['person','organisation','organisations']
cat_plurals['person'] = 'people';
cat_plurals['organisation'] = 'organisations';
cat_plurals['event'] = 'events';
*/

icon = new GIcon();
icon.image = "http://www.google.com/mapfiles/marker.png";
icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
icon.iconSize = new GSize(20, 34);
icon.shadowSize = new GSize(37, 34);
icon.iconAnchor = new GPoint(10, 34);
icon.infoWindowAnchor = new GPoint(8, 20); 

icons = new Array();				
icons['Organisation'] = new GIcon();
icons['Organisation'].image = "http://www.inplaceofwar.net/web_db/i/mapping/organisations.png";
icons['Organisation'].iconSize = new GSize(17, 26);
icons['Organisation'].shadow = "http://www.inplaceofwar.net/web_db/i/mapping/shadow.png";
icons['Organisation'].shadowSize = new GSize(36, 29);
icons['Organisation'].iconAnchor = new GPoint(8, 26);
icons['Organisation'].infoWindowAnchor = new GPoint(20, 14); 

icons['Event'] = new GIcon();
icons['Event'].image = "http://www.inplaceofwar.net/web_db/i/mapping/events.png";
icons['Event'].iconSize = new GSize(17, 26);
icons['Event'].shadow = "http://www.inplaceofwar.net/web_db/i/mapping/shadow.png";
icons['Event'].shadowSize = new GSize(36, 29);
icons['Event'].iconAnchor = new GPoint(8, 26);
icons['Event'].infoWindowAnchor = new GPoint(20, 14);

icons['Person'] = new GIcon();
icons['Person'].image = "http://www.inplaceofwar.net/web_db/i/mapping/people.png";
icons['Person'].iconSize = new GSize(17, 26);
icons['Person'].shadow = "http://www.inplaceofwar.net/web_db/i/mapping/shadow.png";
icons['Person'].shadowSize = new GSize(36, 29);
icons['Person'].iconAnchor = new GPoint(8, 26);
icons['Person'].infoWindowAnchor = new GPoint(20, 14);



function createRequest()
	{
  // create an Ajax Request
  var ajaxRequest;
  
	try
  	{
    ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
  	}    
   catch(e1)
    {
     try
      {
       ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      }
		catch(e2)
      {
			ajaxRequest = new XMLHttpRequest();
      }
		}
	return ajaxRequest;
	}






/*

function loadCat(cat, this_feature)
	{
	
	//if not loaded for this cat - load it now
	if(!markersArray[cat])
		{
		if(!this_feature || this_feature == '')
			{
			var f_string = "map_xml.php?cat="+cat;
			}
		else
			{
			var f_string = "map_xml.php?fmap="+this_feature;
			}
			
		GDownloadUrl(f_string, function(data)
			{
			xmldata = null;
			xmldata = GXml.parse(data);
			markersArray[cat] = [];
			markersArray[cat]['countries'] = [];
			markersArray[cat]['markers'] = [];
			
			//make the country markers
			var country_nodes = xmldata.documentElement.getElementsByTagName("country");
			
			for(var i = 0; i < country_nodes.length; i++)
				{
				var name = country_nodes[i].getAttribute("name");
				var id = country_nodes[i].getAttribute("id");
				var point = new GLatLng(parseFloat(country_nodes[i].getAttribute("lat")),parseFloat(country_nodes[i].getAttribute("lng")));
				
				var html = "";
				
				html += "<h3><img class=\"cat_icon\" src=\"../i/cat_icons_small/"+cat+".png\" />"+ name +"</h3>\n";
				html += "<div class=\"info_scroll\" style=\"bottom:4px; font-size:11px; height:120px; width:238px; margin-bottom:20px;\" >\n";
				html += "<ul>\n";
				
				//make the info box html and do ind markers
				var ind_nodes = country_nodes[i].childNodes;
				for(var j = 0; j < ind_nodes.length; j++)
					{
					var ind_node = false;
					if(ind_nodes[j].nodeType === 1)
							{
							var ind_node = true;
							}
															
					if(ind_node)
						{
						var ind_id = ind_nodes[j].getAttribute("id");
						var ind_lng = ind_nodes[j].getAttribute("lng");
						var ind_lat = ind_nodes[j].getAttribute("lat");
						
						var ellipsis = "";
						var name_length = 34;
						
						if(ind_nodes[j].getAttribute("name").length > name_length)
							{
							var ellipsis = "...";
							}
						
						var ind_name = ind_nodes[j].getAttribute("name").substring(0,name_length)+ellipsis;
						
						if(!ind_nodes[j].firstChild.nodeValue || ind_nodes[j].firstChild.nodeValue == "-")
							{
							var ind_description = "";
							}
						else
							{
							var ind_description = ind_nodes[j].firstChild.nodeValue+"...";
							}
						
						var this_tag = ind_nodes[j].tagName;
						var this_cat = cat_plurals[this_tag];
						
						if(ind_lat != "" && ind_lat != "0.000000")
							{
							var ind_html = "";
							ind_html += "<h4><a href=\"index.php?page=record&amp;cat="+ this_cat +"&amp;id="+ ind_id +"\" target=\"ipow_main\">"+ ind_name +"</a></h4>\n";
							ind_html += "<div class=\"info_scroll\" style=\"font-size:10px; height:100px; width:234px; margin-bottom:4px;\" >"+ ind_description +"</p>\n";
							var ind_point = new GLatLng(parseFloat(ind_lat),parseFloat(ind_lng));
							var ind_marker = createMarker(ind_point,ind_name,ind_html,icons[cat]);
							markersArray[cat]['markers'].push(ind_marker);
							}
							
						//add a link to parent html
						html += "  <li style=\"margin-bottom:1px; height:14px; font-size:11px;\" ><a style=\"font-size:11px; height:14px;\" href=\"index.php?page=record&amp;cat="+ this_cat +"&amp;id="+ ind_id +"\" target=\"ipow_main\" >&raquo; "+ ind_name + "</a></li>\n";
						}
					}
				html += "</ul>\n";
				html += "</div>\n";
				
				var marker = createMarker(point,name,html,icons[cat]);
				markersArray[cat]['countries'].push(marker);
				}
			showCatMarkers(cat);
			
			if(is_feature)
				{
				//set lat, lng and zoom view
				map.setCenter(new GLatLng(ctr_lat, ctr_lng), ctr_zoom);
				}
			});
		
		}
		
	//else just re-show that cat's array's arrays of markers
	else
		{
		showCatMarkers(cat);
		}
	}
*/


	


/*
function mapLoad()
	{
		
		
	if(GBrowserIsCompatible())
		{
		var map_element = document.getElementById("map");
		map = new GMap2(map_element);
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.setCenter(new GLatLng(30,0), initial_zoom, G_HYBRID_MAP);
		map.enableDoubleClickZoom();
		map.enableContinuousZoom();
		mm = new MarkerManager(map);
		markersArray = [];
		
		if(is_feature)
			{
			loadCat(cat, feature_map);
			}
		
		}
		
		
		return true;
	}

*/





function clearMarkers()
{
	map.clearOverlays();
	
	for(var i in cnMarkers)
	{
		thiscat = cnMarkers[i];
		for(var j in thiscat)
		{
			thisMarker = thiscat[j];
			thisMarker.remove();
		}
	}
	
	
}



function createMarker(point,html,icon)
	{
  var marker = new google.maps.Marker(point, {icon:icon});
  google.maps.Event.addListener(marker, "click", function()
		{
		marker.openInfoWindowHtml(html);
		});
	return marker;
	}



function showMarkers(cat)
	{
	var infoWindow = map.getInfoWindow();
	map.getInfoWindow().hide();
	infoWindow.hide();
	
	mm.addMarkers(markers[cat], 1, 8);
	mm.addMarkers(cnMarkers[cat], 1, 17);
	
	mm.refresh();
	}



function buildMarkerHtml(id, title, cat)
{
	var out = "<a href=\"javascript:directMain('" + cat + "','" + id + "')\" >" + title + "</a>";
	return out;
}


function buildCountryMarkersArray(jsonLocs, cat)
{
	var mArray = new Array();
	var m = 0;
	
	for(var i in jsonLocs)
	{
		var cn = jsonLocs[i];
		
		var point = new GLatLng(cn.cnLat, cn.cnLng)
		
		var icon = icons[cat];
		
		var html = "<h3>"+ cn.country +"</h3>";
		
		html += "<div class=\"scroll\" style=\"height:120px;\" ><ul>";
		for(var j in cn.items)
		{
			var loc = cn.items[j];
			html += "<li> "+ buildMarkerHtml(loc.primaryId, loc.title, cat) +"</li>";
		}
		html += "</ul></div>";
		
		mArray[m] = createMarker(point,html,icon);
		m++;
	}
	
	return mArray;
}




function buildMarkersArray(jsonLocs, cat)
{
	var mArray = new Array();
	
	for(var i = 0; i < jsonLocs.length; i++)
	{
		var thisLoc = jsonLocs[i];
		
		var point = new GLatLng(thisLoc.lat, thisLoc.lng)
		var icon = icons[cat];
		var html = buildMarkerHtml(thisLoc.primaryId, thisLoc.title, cat);
		
		mArray[i] = createMarker(point, html, icon);
	}
	return mArray;
}




function loadCountryMarkers(cat)
{
	
	GDownloadUrl('/ajax/getjsoncountries/cat/'+cat, function(response)
		{
			var jsonStr = '(' + response + ')';
			var jsonLocs = eval(jsonStr);
			
			cnMarkers[cat] = buildCountryMarkersArray(jsonLocs, cat);
			
			mm.addMarkers(cnMarkers[cat], 0, 17);
			
			
			}
	);
	
	
}




function loadCatMarkers(cat)
{
	mm.clearMarkers();
	map.clearOverlays();
	
	loadCountryMarkers(cat);
	
	GDownloadUrl('/ajax/getjsonlocations/cat/'+cat, function(response)
		{
			var jsonStr = '(' + response + ')';
			var jsonLocs = eval(jsonStr);
			
			markers[cat] = buildMarkersArray(jsonLocs, cat);
			
			mm.addMarkers(markers[cat], 6, 17);
			}
	);
	
	mm.refresh;
	
	map.savePosition();
	
	var zoomNow = map.getZoom();
	
	map.setZoom(7);
	
	map.returnToSavedPosition();
	
	map.setZoom(zoomNow);
	
}


function createMap()
{
	markers = new Array();
	
	var latlng = new google.maps.LatLng(30,0);
	if(GBrowserIsCompatible()) {
       map = new google.maps.Map2(document.getElementById("map"));
			 map.setUIToDefault();
			 map.disableScrollWheelZoom();
       map.setCenter(latlng, 2);
			mm = new MarkerManager(map);
	}
}



function initializeEditMap(cat, id)
{
	createMap();
	initLoc(cat, id);
}



function initializeMap()
{
	markers = new Array();
	cnMarkers = new Array();
	
	createMap();
}


function placeMarkerAtPoint(point, zoom)
	{
	if(!zoom)
		{
		zoom = 12;
		}
	
	map.clearOverlays();
	marker = new GMarker(point, {draggable:true});
	
	GEvent.addListener(marker, "dragstart", function()
		{
  	//map.closeInfoWindow();
  	});

	GEvent.addListener(marker, "dragend", function()
		{
		this_long = marker.getPoint().lng(); 
		this_lat = marker.getPoint().lat();
		document.getElementById("message-maploc-saved").innerHTML = "Any better? Click save position when you're happy with it.";
		});

	map.addOverlay(marker);
	map.setCenter(point, zoom);
	}



function saveCurrentMarker()
	{
	ajax_connection = createRequest();
	this_cat = document.getElementById("hidden-cat").value;
	this_id = document.getElementById("hidden-id").value;
	this_long = marker.getPoint().lng();
	this_lat = marker.getPoint().lat();
	
	var loc_string = "/edit/savelocation/?cat=" + this_cat + "&id=" + this_id + "&longitude=" + this_long + "&latitude=" + this_lat;
	
	ajax_connection.onreadystatechange = function()
		{
		if(ajax_connection.readyState == 4)
			{
			document.getElementById("message-maploc-saved").innerHTML = ajax_connection.responseText;
			}
		}
	ajax_connection.open('get', loc_string);
  ajax_connection.send(null);	
	
	//window.opener.location.reload();
	
	}



function initLoc(cat, id)
{
	GDownloadUrl('/ajax/getlocation/cat/'+cat+'/id/'+id, function(response)
		{
		var mapLocJson = eval('(' + response + ')');
		
		//alert(mapLocJson.lat);
		
		if(null == mapLocJson.lat || mapLocJson.lat == 0.000000)
		{
			var latlng = new google.maps.LatLng(0,0);
			var initzoom = 2;
			document.getElementById("message-maploc-saved").innerHTML = "There appears not to be location set for this "+cat+". Try using the address lookup to place the marker.";
		}
		else
		{
			var latlng = new google.maps.LatLng(mapLocJson.lat, mapLocJson.lng);
			
		}
		
		placeMarkerAtPoint(latlng, initzoom);
		map.setCenter(latlng);
		});
}


function setLocFromAddress(addr)
{
	if(!addr.length)
	{
		var addr = document.getElementById("map-address-lookup").value
	}
	
	var geocoder = new GClientGeocoder();
	var loc = geocoder.getLatLng(addr , function(point)
			{
				if(point)
				{
					placeMarkerAtPoint(point, 13);
					document.getElementById("message-maploc-saved").innerHTML = "How's that? Drag the marker around to refine the position. Don't forget to save position!";
				}
				else
				{
					document.getElementById("message-maploc-saved").innerHTML = "Can't a find a location for that address - try adding or removing parts.";
				}
			});
	
}

function directMain(cat, id)
{
	window.opener.location.href = "/detail/show/cat/"+cat+"/id/"+id;
}

