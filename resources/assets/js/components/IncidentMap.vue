<template>

  <GmapMap :center="center"
    :zoom="zoom" ref = "map">
    <gmap-marker ref="marker"
      :key ="index"
      v-for="(m, index) in markers"
      :position="m.position"
      :clickable="true"
      :draggable="true"
      @dragend="getCoordinates"> </gmap-marker>
  </GmapMap>
</template>
<script>
    export default {
        mounted() {
            this.initMap();
            this.geoLocationInit();
        },

        // components:{
        //   'info-content':InfoContent
        // },

        data(){
          return {
            center: {
              lat:10.30,
              lng:123.90
            },
            zoom:10,
            markers:[],
            position:''
              // InfoContent:'',
              // infoWindowsPos: {
              //   lat: 0,
              //   lng: 0
              // },
              // infoWinOpen:false,
              // currentMidx:null,
              // infoOptions: {
              //   pixelOffset: {
              //     width: 0,
              //     height: -35
              //   }
              // }
          }
        },

        methods: {
          initMap() {
            this.center = {lat:11.92,lng:122.63};
            this.zoom = 5.5;
          },

          geoLocationInit (){
            if(navigator.geolocation){
              navigator.geolocation.getCurrentPosition((position)=> {
                this.center = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                };
                this.zoom = 12;
                Bus.$emit('marker_changed',this.center);
                
              });
              console.log("successful geolocation.");
              //initMap();
            } else {
              alert("Browser not supported");
            }
          },

          getCoordinates: function(e) {
            this.position=e.latLng;
            this.markers = [];
            this.addMarker(e.latLng); 
            Bus.$emit('marker_dragged',e.latLng);
          },

          addMarker(markerLatLng){

            this.markers.push({
              position:markerLatLng,

              //icon:"/star-red.png"
            });
          },

        },


        created(){
          Bus.$on('marker_changed', place=>{
            //this.markers=place.markers;
            // if(this.markers.length>0){
            //     this.center=data.markers[0].position;
            // }
            this.zoom=12;
            this.center = place;
            this.markers = [];
            this.addMarker(place);    
          });
        }

    };
</script>