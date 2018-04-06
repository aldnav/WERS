

$(document).ready(function(){

		//Navigate to current position.
	 initMap();
//	geoLocationInit();
	function initMap(){

		 var myLatLng = {lat:10.30,lng:123.90};

		// var options = {
		// 	zoom:8,
		// 	center:myLatLng
		// }

		var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 13,
		center: myLatLng
	});
  		
		

	  map.addListener('rightclick', function(e) {
	    addMarker(e.latLng, map);
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
		console.log(position);
		var latval = position.coords.latitude;
		var lngval = position.coords.longitude;

		myLatLng = new google.maps.LatLng(latval,lngval);
		createMap(myLatLng);
//		nearbySearch(myLatLng, "Store")
		
	}

	function fail(){
		alert("it fails");
	}

	//Create Map
	function createMap(myLatLng){
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: 12,
			center: myLatLng,
			mapTypeId: 'terrain'
  		});

		var marker = new google.maps.Marker({
		    position: myLatLng,
		    map: map		   
		});

		map.addListener('rightclick', function(e) {
	    	addMarker(e.latLng, map);
	  	});

	}


	//add marker
	function addMarker(latLng, map){
		console.log(latLng);
		var marker = new google.maps.Marker({
		    position: latLng,
		    map: map
		});
	}


	//Create marker
	function createMarker (place) {
		var marker = new google.maps.Marker({
		    position: place.geometry.location,
		    map: map,
		    title: place.name,
		   	icon: "/public/flag2.ico"
		});
	}


	//Nearby search 
	function nearbySearch(myLatLng, type){
		var request = {
		location: myLatLng,
		radius:'500',
		types:[type]
		}

		function callback(results, status) {
			console.log(results); 
			if (status == google.maps.places.PlacesServiceStatus.OK) {
				for (var i = 0; i < results.length; i++) {
				  var place = results[i];
				  createMarker(place);
				}
			}
		}

		service = new google.maps.places.PlacesService(map);
		service.nearbySearch(request, callback);

	};
	
	// initMap();
	// function initMap() {
	//   var map = new google.maps.Map(document.getElementById('map'), {
	//     zoom: 4,
	//     center: {lat: -25.363882, lng: 131.044922 }
	//   });

	//   map.addListener('rightclick', function(e) {
	//     placeMarkerAndPanTo(e.latLng, map);
	//   });
	// }

	// function placeMarkerAndPanTo(latLng, map) {
	//   var marker = new google.maps.Marker({
	//     position: latLng,
	//     map: map
	//   });
	//   map.panTo(latLng);
	// }
		
});