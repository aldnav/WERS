<template>
  <transition name="modal">
    <div class="form-horizontal">
         <p v-if="errors.length">
          <b>Please correct the following error(s):</b>
          <div v-for="error in errors" class="alert alert-danger" role="alert">
            {{ error }}
          </div>
        </p>
        <!-- <div style="z-index: 9999; width:100%"> -->
        
        <!-- <place-search></place-search> -->
        <!-- <div class="input-group__input">
          <gmap-autocomplete placeholder="Please enter location of incident." classname="form-control" class='autocomplete' style="min-width:400px; border-radius: 2px;" @place_changed="getPlace">
          </gmap-autocomplete>
        </div>
        </div> -->
        <input type="hidden" name="_token" id="csrf-token" :value="csrf" />
        <div class="form-group">
            <textarea id="body" name="body" class="form-control" placeholder="What is this report about? (e.g. Fire on bldg...)" v-model="report.body" style="width:100%"></textarea>  
        </div>
          <div class="form-group row">
            <div class="col-sm-6">
              <label>Nature of Incident</label>
               <div class="dropdown theme-dropdown clearfix">
                <select id="incident_id" class="form-control right-align" name="incident_id" v-model="report.incident_id">
                  <option disabled value="null">Nature of Incident</option>
                  <option  v-for="incident in incidents" v-bind:value="incident.id" v-text="incident.name"></option>
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <label>Location</label>&nbsp;&nbsp;<i class="fas fa-info-circle" title="Leave blank to set location on map"></i>
              <input ref="autocomplete" 
              placeholder="Search" 
              class="search-location form-control"
               v-model="report.address"
              type="text" />
            </div>
          </div>
          <div class="form-group">
              <input id="owner_id" name="owner_id" :userId="userId" class="controls" hidden="true" v-model="report.owner_id">  
          </div>

          <div class="form-group row">
            <label class="col-sm-4">Contact Number</label>
            <div class="col-sm-8">
              <input id="contact_number" placeholder="Contact Number" name="contact_number" class="form-control"   v-model="report.contact_number" required>
            </div>
          </div>
          <hr></hr>
           <button  type="submit" class="btn btn-primary" v-on:click="sendReport">Send Report</button> 

          </div>
    </div>
  </transition>
</template>
 
<script>
    export default {
          props:{
            userId:'',
            contactNumber:String
          },

         data() {
            return{
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                selected:null,
                incidents:[],
                errors:[],
                report:{
                  incident_id:null,
                  owner_id:this.userId,
                  body:"",
                  //initial value would be the geolocation of user
                  lat:this.$root.$data.mapLat,    
                  lng:this.$root.$data.mapLng,
                  status:0,
                  contact_number:this.contactNumber,
                  address:this.$root.$data.formatAddress
                },
                submitted:false
            };
        },   
        
        mounted: function(){
          this.fetchIncidents();
          this.autocomplete = new google.maps.places.Autocomplete(
            (this.$refs.autocomplete),
            {types: ['geocode']}
          );

          this.autocomplete.setComponentRestrictions(
            {'country': 'ph'});

          this.autocomplete.addListener('place_changed', () => {
            let place = this.autocomplete.getPlace();
            let ac = place.address_components;
            let city = ac;
            let center = {
              lat: place.geometry.location.lat(),
              lng: place.geometry.location.lng()
            };
            this.center = center; 
            this.report.address=place.formatted_address;
            Bus.$emit('marker_changed',center);
            Bus.$emit('location_changed',place);
          });

        },

        //marker change listeners
        created(){
//         this.fetchContactNo();
          Bus.$on('marker_dragged', place=>{
            this.report.lng = place.lng();
            this.report.lat = place.lat();
            console.log("places:");
            console.log(place);
            this.report.address=this.$root.$data.formatAddress;
          });

          Bus.$on('marker_changed', place=>{
            this.report.lng = place.lng;
            this.report.lat = place.lat;
            console.log("places:");
            console.log(place.formatted_address);

          });

        },

        methods: {
          fetchIncidents: function() {
            axios.get('/api/incidentTypes').then(response => {this.incidents = response.data.incidents});
            console.log(this.incidents);
          },

          // fetchContactNo: function() {
          //   axios.get('/userContact').then(response => {this.report.contact_number = response.data.result});
          //     console.log("Contact number",this.report.contact_number);
          // },


          //validate, save and show success message
          sendReport: function(event){
            if(this.report.lat && this.report.body && this.report.incident_id && this.report.contact_number){ 
              axios.post('/api/saveOrUpdate', this.report);
              this.$swal({text:"Report submitted!",icon:"success", timer:1000,});;  
                this.$emit('close');
              Bus.$emit('changed_sort');
            } else {
              this.errors = [];
              if(!this.report.body) this.errors.push("Fill in details of incident.");
              if(!this.report.lat) this.errors.push("Specify the location of incident.");
              if(!this.report.incident_id) this.errors.push("Specify type of incident.");
              if(!this.report.contact_number) this.errors.push("Contact number is required.");
              event.preventDefault();
            }
            
          },

        }
    };
</script>