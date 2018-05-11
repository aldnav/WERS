<template>
  <transition name="modal">
    <div style="height:100%;">
      <div class="form-horizontal">
           <p v-if="errors.length">
            <b>Please correct the following error(s):</b>
            <div v-for="error in errors" class="alert alert-danger" role="alert">
              {{ error }}
            </div>
          </p>
          <input type="hidden" name="_token" id="csrf-token" :value="csrf" />
            <div class="form-group row">
              <div class="col-sm-6">
                <label>Name/Organization</label>
                <input id="name" placeholder="Name/Organization" name="name" class="form-control"   v-model="user.name" required>
              </div>
              <div class="col-sm-6">
                <label>Email</label>
                <input id="email" placeholder="Email" name="email" class="form-control"   v-model="user.email" required>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-sm-6">
                <label>Location</label>&nbsp;&nbsp;<i class="fas fa-info-circle" title="Leave blank to set location on map"></i>
                <gmap-autocomplete class="form-control" style="width: 100%;" placeholder="  Enter your location" ref="autocomplete" @place_changed="setPlace" :component-restrictions="{country:['PH']}">
                </gmap-autocomplete>
              </div>
              <div class="col-sm-6">
                <label>Contact Number</label>
                <input id="contact_number" placeholder="Contact Number" name="contact_number" class="form-control"   v-model="user.contact_number">
              </div>
            </div>
            <hr></hr>
             <button  type="submit" class="btn btn-primary" v-on:click="updateUser">Update</button> 
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
          </div>
      </div>
  </transition>
</template>
 
<script>
    export default {
         props:{
          lat:0,
          lng:0,
          email:'',
          name:'',
          contactNumber:'',
          userId:'' 
         },
         data() {
            return{
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                errors:[],
                markers:[],
                user:{
                  lat:parseFloat(this.lat),
                  lng:parseFloat(this.lng),
                  contact_number:this.contactNumber,
                  email:this.email,
                  name:this.name,
                  userId:this.userId,
                },
                zoom:18,
                center: {
                  lat:parseFloat(this.lat),
                  lng:parseFloat(this.lng),
                },
                submitted:false

            };
        },   
        
        //marker change listeners
        created(){
//         this.fetchContactNo();
          this.$on('marker_dragged', place=>{
            this.user.lng = place.lng();
            this.user.lat = place.lat();
            
          });

          this.$on('marker_changed', place=>{
            this.user.lng = place.lng;
            this.user.lat = place.lat;
            console.log("places:");

          });

        },

        methods: {
          addMarker(markerLatLng){

            this.markers.push({
              position:markerLatLng,
              //icon:"/star-red.png"
            });
          },

          setPlace(place) {
            let center = {
              lat: place.geometry.location.lat(),
              lng: place.geometry.location.lng()
            };
            console.log("center:", center);
            this.center=center;
            this.markers = [];
            this.$emit('marker_changed', center);
            this.addMarker(center);
          },

          getCoordinates: function(e) {
            this.markers = [];
            this.addMarker(e.latLng); 
            this.center=e.latLng;
            this.$emit('marker_dragged',this.center);
          },

          updateUser: function(event) {
            if(this.user.name && this.user.email){
              axios.put('/users/'+this.user.userId+'/update', this.user)
              .then(response => {
                    this.$swal({text:"Profile updated!", icon:"success", timer:1000}),
                        setTimeout(function(){
                            window.location=response.data.redirect;
                        }, 1500);
                    console.log(response.data);
                    Bus.$emit('currentloc_changed',this.center);
                })
                .catch(response => { console.log(response) });  
            } else {
              this.errors = [];
              if(!this.user.name) this.errors.push("Name must not be empty");
              if(!this.report.lat) this.errors.push("Name must not be empty");
              event.preventDefault();
            }          
          },
        }

    };
</script>