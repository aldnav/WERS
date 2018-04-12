

$(document).ready(function(){

	initMap();
});
	var myLatLng, map, service;	


	function initMap(){

		var myLatLng = {lat:10.30,lng:123.90};

		map = new google.maps.Map(document.getElementById('map'), {
				zoom: 13,
				center: myLatLng
			});
  		
//		searchIncidents(myLatLng.lat,myLatLng.lng);
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

		console.log([latval, lngval]);
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
		    map: map		   
		});

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
			
