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
	
	var input = document.getElementById('search-box');
	var searchBox = new google.maps.places.SearchBox(input);
	//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input); //deleted this to transfer search-box to nav-bar

	// Bias the SearchBox results towards current map's viewport.
	map.addListener('bounds_changed', function() {
		searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	// Listen for the event fired when the user selects a prediction and retrieve
	// more details for that place.
	searchBox.addListener('places_changed', function() {
			var places = [];
			places = searchBox.getPlaces();

			if (places.length == 0) {
				return;
			}
			// // Clear out the old markers.
			for (var i = 0; i < markers.length; i++) {
	          markers[i].setMap(null);
	        }
			markers=[];

			console.log(markers.length);


			// For each place, get the icon, name and location.
			var bounds = new google.maps.LatLngBounds();
			places.forEach(function(place) {
			if (!place.geometry) {
				console.log("Returned place contains no geometry");
				return;
			}
			console.log(places.length);

			if (place.geometry.viewport) {
				// Only geocodes have viewport.
				bounds.union(place.geometry.viewport);
			} else {
				bounds.extend(place.geometry.location);
			}
			});

			var marker = new google.maps.Marker({
				map: map,
				draggable: true,
				title: places[0].name,
				position: places[0].geometry.location
			});
			markers.push(marker);
			marker.addListener('dragend', handleDragEvent);
			changeLatLng(places[0].geometry.location);

			map.fitBounds(bounds);
	});


}

function handleDragEvent(event){
	document.getElementById('lat').value = event.latLng.lat();
	document.getElementById('lng').value = event.latLng.lng();
}

function changeLatLng(position){
	var latElem = document.getElementById('lat');
	var lngElem = document.getElementById('lng');

	latElem.value = position.lat();
	lngElem.value = position.lng();

}

function geoLocationInit(){
	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(success, fail);
		initMap();
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
	marker.addListener('dragend', handleDragEvent);
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
	marker.addListener('dragend', handleDragEvent);
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

module.exports = {
	initMap: initMap,
	geoLocationInit: geoLocationInit
};