<?php 
// AIzaSyBpUfPv3FP1uoHKg1gxtFFWFbZNlsWNj1U

//require_once ("lib/db.php");

?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <style type="text/css">
           
        </style>
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBpUfPv3FP1uoHKg1gxtFFWFbZNlsWNj1U&sensor=true"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
        
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery.ui.all.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css" media="screen" />
		
		<script type="text/javascript">
                  var map = null, line = null;
				  var newMarkerLatLng = null;
				  var clickedLatLng;
				  var mouseX = mouseY = 0;
				  var selectedMarkerType = "powerline";
				  var activeMarker = null;
				  
				  var markerTypes = {
				  	POWERLINE : "powerline",
				  	LAMP : "lamp"
				  };
				  var markerOptions = {
				  	  powerline : {
					  	 icon : new google.maps.MarkerImage("icons/powerline.png",
						 									new google.maps.Size(10,10),
															new google.maps.Point(0,0),
															new google.maps.Point(5,5)
															),
						// title : "bod elektrickeho vedeni",
					  },
				  	  lamp : {
					  	 icon : "icons/lamp.png",
						 //title : "lampa"
					  }
				  };
				  var lineOptions = {
				  	  powerline : {
						 strokeColor : "#000000",
						 strokeOpacity: 1.0,
						 strokeWeight: 3
					  }
				  };
		
            	  $(function()
				  {
				  		$(".contextMenu").bind('contextmenu', function() { return false; });
            	  		
						initialize(); // map init
						
						$(".markerTypeRadio").bind("click",function()
						{
							selectedMarkerType = $(this).val();
						});
						
				  });			
				  	  
				  function initialize() 
            	  {
                    // map options
					var myOptions = {
                      center: new google.maps.LatLng(49.958426,15.788244),
					  //draggable : false,
                      zoom: 18,
                      mapTypeId: google.maps.MapTypeId.SATELLITE // SATELLITE , TERRAIN 
                    };
                    
					// initialize map obj
					map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
					
					// map events
					google.maps.event.addListener(map, 'click', function(e)
					{
						drawMarker(e);
						
					});
					
					$(map.getDiv()).append($(".contextMenu"));
					$(map.getDiv()).bind("mousemove",function(e){
						var position =  $(map.getDiv()).position();
						mouseX = e.pageX - position.left;
						mouseY = e.pageY - position.top + 20;
					});

					$(".contextMenu").find('a').click( function(e)
					{
						e.preventDefault();
						$(".contextMenu").fadeOut(75);
						var action = $(this).attr('href').substr(1);
					
						switch ( action )
						{
							case 'delete':
								activeMarker.setMap(null);
								break;
					
							case 'addSubTree':
								line = null;
								drawLine(markerTypes.POWERLINE,activeMarker.getPosition());
								break;
						}
					
						return false;
					});	
					
					$(".contextMenu").find('a').hover( function() 
					{
						$(this).parent().addClass('hover');
					}
					,function() 
					{
						$(this).parent().removeClass('hover');
					});	
					
					$.each('click dragstart zoom_changed maptypeid_changed'.split(' '), function(i,name)
					{
						google.maps.event.addListener(map, name, function()
						{ 
							$(".contextMenu").hide();
						});
					});			
					
					// init new polyline					
					initNewLine();
                  }
				  
				  function drawMarker(e)
				  {
					  switch(selectedMarkerType)
					  {
					  		case markerTypes.POWERLINE:

								var marker = new google.maps.Marker({
								    position: e.latLng,
								    map: map,
								    icon: markerOptions[markerTypes.POWERLINE]["icon"],
								    title: markerOptions[markerTypes.POWERLINE]["title"]
								});
								
								drawLine(markerTypes.POWERLINE,e.latLng);
								
								google.maps.event.addListener(marker, 'rightclick', function()
								{
									contextMenu(markerTypes.POWERLINE,marker);
								});
								
							
								break;
							
					  		case markerTypes.LAMP:
								var marker = new google.maps.Marker({
								    position: e.latLng,
								    map: map,
								    icon: markerOptions[markerTypes.LAMP]["icon"],
								    title: markerOptions[markerTypes.LAMP]["title"]
								});
								
								google.maps.event.addListener(marker, 'rightclick', function()
								{
									contextMenu(markerTypes.LAMP,marker);
								});

								break;
							
							default:
								alert("no marker type selected");
					  }					  	  
				  }
				  
				  function drawLine(lineType,latLng)
				  {
				  		if(line == null)
						{
							initNewLine(lineType);
						}
						var path = line.getPath();
						path.push(latLng);
				  }
				  
				  function initNewLine(lineType)
				  {
				  		line = new google.maps.Polyline(lineOptions[lineType]);
						line.setMap(map);
						google.maps.event.addListener(line, 'click', al);
				  }
				  
				  function contextMenu(type,marker)
				  {
				  		activeMarker = marker;
						$(".contextMenu").hide();
					
						var mapDiv = $(map.getDiv());
						
						x = mouseX;
						y = mouseY;
					
						if (x > mapDiv.width() - $(".contextMenu").width()) 
						{
							x -= $(".contextMenu").width();
						}
					
						if (y > mapDiv.height() - $(".contextMenu").height()) 
						{
							y -= $(".contextMenu").height();
						}
						
						$("#"+type+"CM").css({ top: y, left: x }).fadeIn(100);
				  }
				  
				  function al()	
				  {
				  		//alert("huh?");
				  }
            	  
				  
            	  function loadMarkers()
            	  {
					$.getJSON('ajax.php?action=getmarkers', function(data) 
					{
						$.each(data, function(i, item) 
						{
							displayMarker(item);
						});
					});
            	  }
				  
				  function displayMarker(mrk)
				  {
						var location = new google.maps.LatLng(mrk.lat,mrk.lng);
						var infowindow = new google.maps.InfoWindow(
				        { 
							content: mrk.window,
					        size: new google.maps.Size(50,50)
					    });
						var marker = new google.maps.Marker({
						    position: location,
						    map: map,
						    title: mrk.title
						});
						google.maps.event.addListener(marker, 'click', function() 
						{
							infowindow.open(map,marker);
						});				  	
				  }
				  
				  function addMarkerDialog()
				  {
					 $("#newMarker").dialog();
				  }
            	  

            		
            			//loadMarkers();
						/*
						$("#saveMarkerBtn").bind("click",function()
						{
							
							$.post('ajax.php?action=addmarker', 
									{ 
										lat : newMarkerLatLng.lat(), 
										lng : newMarkerLatLng.lng(), 
										title : $("#title").val(), 
										infoWindow : $("#infoWindow").val() 
									}
									, function(data)	
									{
										$("#newMarker").dialog('close');
										displayMarker(
										{ 
											lat : newMarkerLatLng.lat(), 
											lng : newMarkerLatLng.lng(), 
											title : $("#title").val(), 
											window : $("#infoWindow").val() 
										})
										$("#title").val("");
										$("#infoWindow").val("");
									});
						});
            	  });*/
                
        </script>
    </head>
    <body>
    	<h4>&nbsp; Co kreslit? : 
    	Elektrickou sit <input type='radio' class='markerTypeRadio' name='mrkType' value='powerline' checked />
    	Lampy <input type='radio' class='markerTypeRadio' name='mrkType' value='lamp' />
		</h4>
		LT mysi pridava body, PT vyvolava kontextove menu nad objektama
		
		<div style='float:right;display:none;'>
		  <button id='saveBtn'>SAVE</button>
		  <button id='loadBtn'>LOAD</button>
		</div>
		
        <div id="map_canvas" style="width:100%; height:90%"></div>
		<div id="newMarker" style="display:none" title='New marker'>
			<form action="" method="post">
				<table>
					<tr>
						<td>Title</td>
						<td><input type='text' id="title" value="" /></td>
					</tr>
					<tr>
						<td>Info window</td>
						<td><input type='text' id="infoWindow" value="" /></td>
					</tr>
					<tr>
						<td colspan=2><button type='button' id='saveMarkerBtn'>Save marker</button></td>
					</tr>
				</table>
			</form>
		</div>
		
		<ul class="contextMenu" id="powerlineCM">
			<li><a href="#addSubTree">Vest sit odtud</a></li>
		</ul>
		<ul class="contextMenu" id="lampCM">
			<li><a href="#delete">Smaz</a></li>
		</ul>
    </body>
</html>
