<template>
    <div class="col-12 col-md-3 col-xl-2 bd-sidebar" style="overflow-y:auto; height: 98vh">
        <div class="filters">
          <gmap-autocomplete style="width: 100%;" placeholder="Enter your location" ref="autocomplete" 
            @place_changed="setPlace" :component-restrictions="{country:['PH']}">
          </gmap-autocomplete>

          <form class="form-inline">
               <label for="status" class="col-xs-4 control-label">Status&nbsp; </label>
             <select id="status" class="custom-select custom-select-sm"  v-model="status" @change="changeStatus">
              <option value="0">Unvalidated</option>
              <option value="1">Unresolved</option>
              <option value="2">Resolved</option>
              <option value="3">Rejected</option>              
            </select>
          </form>
          <form class="form-inline">
            <label for="sortby" class="col-xs-4 control-label">Sort by</label>
            <select id= "sortby" class="custom-select custom-select-sm" v-model="sortby" @change="changeSort">
              <option value="name">Address</option>
              <option value="report_date">Report Date</option>
            </select>
            <button type="button" class="btn btn-sm js-add-btn" v-if="sortOrder==='desc'" @click="sortOrder='asc'">
               <i class="fas fa-sort-amount-up"></i>
            </button>
            <button type="button" class="btn btn-sm js-add-btn" v-else>
              <i class="fas fa-sort-amount-down" @click="sortOrder='desc'"></i>
            </button>
          </form>
          
    </div>
        <div v-for="(item,i) in sortedResults">
            <h6 :key="i"
                @click="focusMarker(item)"
                style="text-decoration: underline">{{item.name}}</h6>
        </div>
    </div>
</template>

<script>
    export default {
        props:{userId:0
                },

        data: function() {
            return {
                results:[],
                currentPlace:{},
                status:0,
                sortby:'report_date',
                sortOrder:'desc',
                address:''
            }
        },


          /*
          *Unvalidated: all reports with is_validated = 0
          *Unresolved: reports of responder with is_validated=1
          */

        methods: {
            setPlace(place) {
                let center = {
                  lat: place.geometry.location.lat(),
                  lng: place.geometry.location.lng()
                };
                this.currentPlace = center;
                console.log("pl: ",place.formatted_address);
                this.address=place.formatted_address;
                 Bus.$emit('marker_changed', center);
                Bus.$emit('location_added',this.address);
               
            },

            fetchReports:function(){
              axios.post('/api/user-submissions',{status:this.status, id:this.userId}).then(response=> {
                      let data = response.data;
                      this.results = data.markers;
                      console.log("user-submissions",data);
                      //Bus.$emit('reports_fetched',data);
                      Bus.$emit('place_filter', this.currentPlace);
              });
            },
            

            changeSort: function(order){
                this.sortOrder=order;
                Bus.$emit('changed_sort');
            },

            focusMarker(item){
                Bus.$emit('marker_result_clicked',item);
                console.log("item:",item);
            },

            changeStatus(){
                Bus.$emit('changed_status', this.status);
            },
        },

        created(){
            Bus.$on('reports_fetched',data=>{
                this.results=data.results;
                console.log('event data',data);
            })

            Bus.$on('changed_status', data=>{
              this.status=data;
              this.fetchReports();
            })

            Bus.$on('changed_sort',data=>{
              this.fetchReports();
            })

            this.fetchReports();
        },

        computed:{
            sortedResults: function(){
                return this._.orderBy(this.results, this.sortby,this.sortOrder)               
            }
        }
    }
</script>
