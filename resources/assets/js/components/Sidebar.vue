<template>
    <div class="col-12 col-md-3 col-xl-2 bd-sidebar" style="overflow-y:auto; height: 98vh">
        <gmap-autocomplete placeholder="Enter your location" 
          @place_changed="setPlace">
        </gmap-autocomplete>
        <select class="input-group__input"  v-model="status" @change="changeStatus">
          <option value="">Select Status</option>
          <option value="0">Unvalidated</option>
          <option value="1">Unresolved</option>
      </select>
        <select class="input-group__input" v-model="radius" @change="changeRadius" >
          <option value="">Select Radius</option>
          <option value="10">10 km radius</option>
          <option value="20">20 km radius</option>
          <option value="30">30 km radius</option>
          <option value="40">40 km radius</option>
      </select>
<!--         <input placeholder="Radius" 
        class="radius"
        name="radius" 
        v-model="radius"
        v.on:keydown="changeRadius(key)"
        type="text" /> -->
        <div 
            v-for="(item,i) in results">
            <h6 :key="i"
                @click="focusMarker(i)"
                style="text-decoration: underline">{{item.incident}}</h6>
        </div>
    </div>
</template>

<script>
    export default {
        props:{responder:false,
                userId:''
                },

        data: function() {
            return {
                results:[],
                currentPlace:{},
                radius:20,
                status:0,
            }
        },

        methods: {
            setPlace(place) {
                let center = {
                  lat: place.geometry.location.lat(),
                  lng: place.geometry.location.lng()
                };
                this.currentPlace = center;
                Bus.$emit('place_filter', this.currentPlace);
            },

            respondToReport(index){
                axios.post('/api/respondReport', {report_id:this.results[index].report_id}).then(response=> {
                      let data = response.data.report;
                      console.log("report_id",data);
              });
            },

            focusMarker(index){
                Bus.$emit('marker_result_clicked',index);
                console.log(index);
            },

            changeRadius(){
                Bus.$emit('changed_radius', this.radius);
                console.log("changed radius");
            },

            changeStatus(){
                Bus.$emit('changed_status', this.status);
            }
        },

        created(){
            Bus.$on('reports_fetched',data=>{
                this.results=data.results;
                console.log('event data',data);
            })
        },

        mounted(){
        }
    }
</script>
