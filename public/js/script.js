

$(document).ready(function(){
	geoLocationInit();
	//initMap();
});
	var myLatLng, map, service;	


	function initMap(){

		var myLatLng = {lat:10.30,lng:123.90};

		map = new google.maps.Map(document.getElementById('map'), {
				zoom: 6,
				center: myLatLng
			});

		/*to improve:
			the following functions should be invoked depending on 
			the user of the system.
			initSearchbox for reporters
			searchIncidents for emergency responders*/
		initSearchbox();

	}

/*		add searchbox*/
	function initSearchbox(){
		
		var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        var incidentAddr;
/*        map.addListener('click', function(incidentAddr) {
        	console.log(incidentAddr);
        	var marker = new google.maps.Marker({
			    position: incidentAddr.latLng,
			    map: map,
			    draggable:true
			    title: incidentAddr.name
			});
        });
*/
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
	          var places = [];
	          places = searchBox.getPlaces();
          	  incidentAddr=places[0];          	  

	          if (places.length == 0) {
	            return;
	          }
	          // Clear out the old markers.
	          markers.forEach(function(marker) {
	            marker.setMap(null);
	          });

			  console.log(markers.length);

		        markers.push(new google.maps.Marker({
	              map: map,
	              draggable: true,
	              title: places[0].name,
	              position: places[0].geometry.location
	            }));

	          // For each place, get the icon, name and location.
	          var bounds = new google.maps.LatLngBounds();
	          places.forEach(function(place) {
	            if (!place.geometry) {
	              console.log("Returned place contains no geometry");
	              return;
	            }


	            if (place.geometry.viewport) {
	              // Only geocodes have viewport.
	              bounds.union(place.geometry.viewport);
	            } else {
	              bounds.extend(place.geometry.location);
	            }
	          });
	          map.fitBounds(bounds);
	    });


	}

	function geoLocationInit(){
		if(navigator.geolocation){
			navigator.geolocation.getCurrentPosition(success, fail);
		} else {
			alert("Browser not supported");
		}
	}

	function success(position){
		var latval = position.coords.latitude;
		var lngval = position.coords.longitude;
		console.log("successfully geolocate");

		myLatLng = new google.maps.LatLng(latval,lngval);
		createMap();
	}

	function fail(error){
		switch(error.code){
			case error.PERMISSION_DENIED:
				alert("User denied the request for Geolocation.");
				break;
			case error.POSITION_UNAVAILABLE:
				alert("Location information is unavailable.");
				break;
			case error.TIMEOUT:
				alert("The request to get user location timed out.");
				break;
			case error.UNKNOWN_ERROR:
				alert("Unknown error.");
				break;
		}

		initMap();
	}

	//Create Map
	function createMap(){
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: 12,
			center: myLatLng,
			mapTypeId: 'terrain'
  		});

		var marker = new google.maps.Marker({
		    position: myLatLng,
		    draggable:true,
		    map: map		   
		});
		initSearchbox();
	}

	//Create marker
	function createMarker (latLng, name) {

		var marker = new google.maps.Marker({
		    position: latLng,
		    map: map,
		    title: name,
		   	icon: "/flag2.ico"
		});
	}


	function searchIncidents(latitude,longitude){
		$.post('http://localhost:8000/api/searchIncidents',{lat:latitude,lng:longitude},function(match){
			console.log(match);
			$.each(match,function(i,val){
				var glatval=val.lat;
				var glngval=val.lng;
				var gdesc=val.description; 
				var GlatLng = new google.maps.LatLng(glatval,glngval);
				createMarker(GlatLng,gdesc); 
				//console.log(val.description);
			});
		});
	}
