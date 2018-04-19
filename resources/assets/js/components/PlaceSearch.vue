<template>
  <div class="input-group__input">
    <gmap-autocomplete placeholder="Please enter location of incident." classname="form-control mr-sm-2" class='autocomplete' style="min-width:400px; border-radius: 5px;" @place_changed="getPlace">
    </gmap-autocomplete>
  </div>
</template>

<script>
  export default {
    data () {   
    	return{
    		center:this.center,
    		zoom: 12
    	}
    },

    methods: {
    	getPlace(place){
    		let center = {
    			lat: place.geometry.location.lat(),
    			lng: place.geometry.location.lng()
    		};
    		this.center = center; 
    		Bus.$emit('marker_changed',center);
    	}
    }
 }
</script>