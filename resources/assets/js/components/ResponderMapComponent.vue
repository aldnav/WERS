<template>

  <GmapMap :center="center"
    :zoom="zoom" ref = "map">
    <gmap-cluster>
    
      <gmap-marker ref="marker"
        :key ="index"
        v-for="(m, index) in markers"
        :position="m.position"
        :clickable="true"
        :icon.path="{url:'/icons/'+ m.incident + '.png'}"
        :draggable="false"
        @click="toggleInfoWindow(m,index)">
      </gmap-marker>
    
    </gmap-cluster>

    <gmap-info-window :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @closeclick="infoWinOpen=false">
      <info-content :address='address' :is-responder="true" :content="infoContent" :reporter="reporter" :contact-number="contactNumber" :report-date="report_date"></info-content>
    </gmap-info-window>  
  </GmapMap>
</template>
<script>

import InfoContent from './InfoContent.vue'
import GmapCluster from 'vue2-google-maps/dist/components/cluster' // replace src with dist if you have Babel issues
 
Vue.component('GmapCluster', GmapCluster)

export default {
      components:{
          'info-content':InfoContent
      },

      props:['userId'],

        // mounted() {
        //      this.geoLocationInit();
        // },

        data(){
          return {
            center: {
              lat:0,
              lng:0
            },
            zoom:18,
            markers:[],
            infoContent: '',
            infoWindowPos: {
                lat: 0,
                lng: 0
            },
            reporter:'',
            report_date:'',
            contactNumber:'',
            incidentIcon:'',
            address:'',
            infoWinOpen: false,
            currentMidx: null,
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35
                }
            },
            radius:20,
            status:0,
          }
        },

        methods: {

          toggleInfoWindow (marker, idx) {

            this.infoWindowPos = marker.position;
            this.infoContent = marker.report;
            this.incident = "/icons/"+ marker.incident+".png";
            this.address = marker.name;
            this.contactNumber = marker.contact_number;
            this.reporter = marker.reporter;
            this.report_date = marker.report_date;
            //check if its the same marker that was selected if yes toggle
            if (this.currentMidx == idx) {
                this.infoWinOpen = !this.infoWinOpen;
            }
            //if different marker set infowindow to open and reset current marker index
            else {
                this.infoWinOpen = true;
                this.currentMidx = idx;
            }
          },
          fetchReports(){
              axios.post('/api/recent-reports',{place: this.center, radius:this.radius, status:this.status, id:this.userId}).then(response=> {
                      let data = response.data;
                      this.markers = data.markers;
                      Bus.$emit('reports_fetched',data);
              });
          },
        },

         created(){
            if(navigator.geolocation){
              navigator.geolocation.getCurrentPosition((position)=> {
                this.center = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                };
                this.zoom = 12;
                Bus.$emit('marker_changed',this.center);
                this.fetchReports();
              });
              console.log("successful geolocation.");
              //initMap();
            } else {
              this.fetchReports();
              alert("Browser not supported");
            }
            
            Bus.$on('reports_fetched',data=>{
              this.markers=data.markers;
              if(this.markers.length>0){
                  this.center=data.markers[0].position;
              }
              console.log('event data',data);
            })

            Bus.$on('place_filter',place=>{
              this.center=place;
              this.fetchReports();
            })

            Bus.$on('marker_result_clicked', item=> {
              let targetMarker= _.find(this.markers, (o) => o.id === item.report_id);
              this.center=targetMarker.position;
              this.zoom=20;
              this.toggleInfoWindow(targetMarker,targetMarker.id);
            })

            Bus.$on('changed_radius', data=> {
              this.radius = data;
              this.fetchReports();
            })

            Bus.$on('changed_status', data=> {
              this.status = data;
              this.fetchReports();
            })

            Bus.$on('respond_to_report', index=> {
              //this.respondToReport(index);
              Bus.$emit('selectReport', this.markers[index]);
            })
            
             Bus.$on('reports_changed', data=>{
              this.fetchReports();
            })

             this.$on('bounds_changed', data=>{
              this.fetchReports();
             })
         },
    };
</script>