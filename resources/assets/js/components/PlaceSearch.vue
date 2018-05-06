<template>
  <div class="input-group__input">
    <gmap-autocomplete placeholder="Please enter location of incident." classname="form-control" class='autocomplete' style="min-width:400px; border-radius: 2px;" @place_changed="getPlace" v-model="town">
    </gmap-autocomplete>
  </div>
</template>

<script>
  export default {
    data () {   
    	return{
    		center:{},
    		zoom: 12,
            town: null
    	}
    },

    methods: {
    	getPlace(place){
    		let center = {
    			lat: place.geometry.location.lat(),
    			lng: place.geometry.location.lng()
    		};
    		this.center = center; 
    		Bus.$emit('place_filter_changed',center);
    	},

    }
 }
</script>