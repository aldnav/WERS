<template>
    <div class="report-form">
        <input type="hidden" name="_token" id="csrf-token" :value="csrf" />
          <div class="form-inline">  
             <input type="hidden" id="id" name="id">
            <select id="incident_id" name="incident_id" v-model="report.incident_id">
               <option  v-for="incident in incidents" v-bind:value="incident.id" v-text="incident.name"></option>
            </select>
          </div>
          <div class=form-group>
            <textarea id="body" name="body" class="controls" placeholder="What is this report about? (e.g. Fire on bldg...)" v-model="report.body"></textarea>  
          </div>
          <div class=form-group>
              <input id="owner_id" name="owner_id" userId="user-id" class="controls" hidden="true" v-model="report.owner_id">  
          </div>
            <div class=form-group>
              <input id="lat" name="lat" class="controls" v-model="report.lat">  
            </div>
            <div class=form-group>
              <input id="lng" name="lng" class="controls" v-model="report.lng">  
          </div>
          
           <button  type="submit" class="btn btn-success" @click="$emit('close')">Send Report</button> 
    </div>
</template>
 
<script>
    export default {
          props:{
            userId:Number,
          },

         data() {
            return{
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                selected:null,
                incidents:[],
                report:{
                  incident_id:0,
                  owner_id:this.userId,
                  body:"",
                  lat:this.$root.$data.mapLat,
                  lng:this.$root.$data.mapLng
                },
                submitted:false
            };
        },   
        
        mounted: function(){
          this.fetchIncidents();
        },

        created(){

          this.$on('close', this.sendReport());
        },

        methods: {
          fetchIncidents: function() {
            axios.get('/api/incidentTypes').then(response => {this.incidents = response.data.incidents});
            console.log(this.incidents);
          },

          sendReport: function(){
            axios.post('/api/saveOrUpdate', this.report);
          }

        }
    };
</script>