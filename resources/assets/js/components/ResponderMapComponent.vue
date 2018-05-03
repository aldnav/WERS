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
      <info-content :address='address' :content="infoContent" :reporter="reporter" :contact-number="contactNumber" :report-date="report_date"></info-content>
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
        mounted() {
            this.initMap();
        },

        data(){
          return {
            center: {
              lat:14.680000,
              lng:121.010000
            },
            zoom:12,
            markers:[],
            position:'',
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
            }
          }
        },

        methods: {
          initMap() {
            this.center = {lat:14.680000,lng:121.040000};
            this.zoom = 12;
            this.position= this.center;
          },

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
              axios.post('/api/recent-reports',{lat: this.center.lat, lng: this.center.lng}).then(response=> {
                      let data = response.data;
                      console.log(response);
                      this.markers = data.markers;
                      Bus.$emit('reports_fetched',data);
              });
          },

          addIcon(){
            for(item in this.markers)
            {
              if(item.incident == 1)
                item.icon = "/icons/fire.png";
              else
                item.icon = "/icons/road accident.png";
            }
          }

        },

         created(){
            this.fetchReports();

            Bus.$on('reports_fetched',data=>{
              this.markers=data.markers;
              if(this.markers.length>0){
                  this.center=data.markers[0].position;
              }
              console.log('event data',data);
            })

            Bus.$on('marker_result_clicked', index=> {
              let targetMarker=this.markers[index];
              this.center=targetMarker.position;
              this.zoom=20;
              this.toggleInfoWindow(targetMarker,index)
            })
         },
    };
</script>