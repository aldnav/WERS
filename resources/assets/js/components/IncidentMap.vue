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
    <gmap-info-window :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @closeclick="infoWinOpen=false">
      <info-content :address='address' :content="infoContent" :responder='responder' :is-responder="false" :contact-number="contactNumber" 
      :report-date="report_date"></info-content>
    </gmap-info-window>  

  </GmapMap>
</template>
<script>
    import InfoContent from './InfoContent.vue'
    export default {
        props:['userId', 'userLat', 'userLng'],

        mounted() {
            this.geoLocationInit();
            this.initMap();
        },

        components:{
          'info-content':InfoContent
        },

        data(){
          return {
            center: {
              lat:0,
              lng:0
            },
            zoom:10,
            markers:[],
            position:'',
            infoContent:'',
            infoWindowPos: {
              lat: 0,
              lng: 0
            },
            infoWinOpen:false,
            currentMidx:null,
            infoOptions: {
              pixelOffset: {
                width: 0,
                height: -35
              }
            },
            report_date:'',
            address:'',
            responder:'',
            contactNumber:'',
            geolocated:false

          }
        },

        methods: {
          initMap() {

            if((this.userLat==null || this.userLat==0 ) && (!this.geolocated)) {
              this.center = {lat:11.92,lng:122.63};
              this.zoom = 5.5;
            }
            else {
              this.center.lat=parseFloat(this.userLat);
              this.center.lng=parseFloat(this.userLng);
              this.zoom=18;
            }
          },

          geoLocationInit (){
            if(navigator.geolocation){
              navigator.geolocation.getCurrentPosition(this.success,this.fail);
              // navigator.geolocation.getCurrentPosition((position)=> {
              //   this.center = {
              //     lat: position.coords.latitude,
              //     lng: position.coords.longitude
              //   };
              //   debugger;
              //   this.zoom = 12;
              //   Bus.$emit('marker_changed',this.center);
              //   console.log("successful geolocation.", position);
              // });
              
              // //initMap();
            } else {
              alert("Browser not supported");
            }

          },

          success: function(position){
            let center = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            this.center=center;
            this.zoom=18;
            console.log("center",position);
            this.addMarker(this.center);
            Bus.$emit('location_init',position);
            this.geolocated=true;
          },

          fail: function(error){
            this.geolocated=false;
            console.log("error:", error);
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
            });
          },

          toggleInfoWindow (place) {
            this.infoWindowPos = place.position;
            
            //check if its the same marker that was selected if yes toggle
            this.infoWinOpen = true;
            this.infoContent= place.report;
            this.address=place.name;
            this.responder=place.responder;
            this.report_date=place.report_date;
            this.contactNumber=place.contact_number;
          },

        },


        created(){

          Bus.$on('marker_changed', place=>{
            this.zoom=18;
            this.center = place;
            this.markers = [];
            this.addMarker(place);    
          });

          Bus.$on('currentloc_changed', data=> {
              this.center=data;
          })
          
          Bus.$on('marker_result_clicked', place=>{
            this.toggleInfoWindow(place);
            this.zoom=18;
            this.center=place.position;
          })

          // Bus.$on('location_added', place=>{
          //   //this.markers=place.markers;
          //   // if(this.markers.length>0){
          //   //     this.center=data.markers[0].position;
          //   // }
          //   this.zoom=;
               
          // });
        }

    };
</script>