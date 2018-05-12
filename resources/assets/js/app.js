
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
//let Map = require('./script');

window.Vue = require('vue');
//window.geoLocationInit = Map.geoLocationInit;
 
import * as VueGoogleMaps from 'vue2-google-maps';
import VueSweetalert2 from 'vue-sweetalert2';
import sort from 'vuejs-sort';
import lodash from 'lodash';
import VueSocketio from 'vue-socket.io';
import Pusher from 'pusher-js';
Pusher.logToConsole = true;

window.Bus = new Vue;

Vue.use(VueSweetalert2);
Vue.use(VueGoogleMaps, {
    load: {
        key: process.env.GMAPS || 'AIzaSyC-mUjFEzHGa0MK-tOQwEwDq8_pwddb1WI',
        libraries: 'places', 
    }
});
Vue.prototype._=lodash;
// Vue.use(VueSocketio, 'http://localhost:8890');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('sidebar', require('./components/Sidebar.vue'));
//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('report-form', require('./components/ReportForm.vue'));
Vue.component('incident-map', require('./components/IncidentMap.vue'));
Vue.component('place-search', require('./components/PlaceSearch.vue'));
Vue.component('modal', require('./components/ModalComponent.vue'));
Vue.component('responder-map', require('./components/ResponderMapComponent.vue'));
Vue.component('stat', require('./components/Quickstats.vue'));
Vue.component('report-list', require('./components/ReportList.vue'));
Vue.component('report-validate-detail', require('./components/ReportValidateDetail.vue'));
let notifComponent = Vue.component('notifications', require('./components/Notifications.vue'));
Vue.component('reporter-sidebar', require('./components/ReporterSidebar.vue'));
Vue.component('user-update', require('./components/UserUpdate.vue'));


window.INCIDENTS = ['FIRE', 'FLOOD', 'ROAD ACCIDENT', 'LANDSLIDE'];

var uid;
try {
    uid = document.querySelector('meta[name="wers-id"]').getAttribute('content');
    document.querySelector('meta[name="wers-id"]').remove();
} catch (error) {
    uid = null;
}

const app = new Vue({
    el: '#app',
    data:{
        showModal:false,
        showReports: false,
        showSpecificReport: false,
        showNotifications: false,
    	mapLat:0,
    	mapLng:0,
        formatAddress:null,
        selectedReport: null,
        unreadNotifCount: 0
    },

    sockets:{
        connect: function(){
            console.log('socket connected')
        },
        'notification': function(data){
            console.log('notification:',  data);
            if (data.obj.owner_id != uid)
                return;
            // every notif has an 'event' from Laravel's app service provider
            if (data.event == 'created') {
                this.unreadNotifCount += 1;
            }
            Bus.$emit('notifications:' + data.event, data.obj);
        }
    },

    created(){
      Bus.$on('marker_changed', place=>{
        this.mapLng = place.lng;
        this.mapLat = place.lat;
        console.log('to format place',place.formatted_address);
        this.formatAddress=place.formatted_address;

      });

      Bus.$on('marker_dragged', place=>{
        let center = {
          lat: place.lat(),
          lng: place.lng()
        };
        this.mapLng = place.lng();
        this.mapLat = place.lat();
        var google_maps_geocoder = new google.maps.Geocoder();
          
        google_maps_geocoder.geocode(
              { 'latLng': center }, 
              function( results, status ) {
                  console.log("results");
                  app.set_address(results[0].formatted_address);  
              }
          )
      });

      Bus.$on('location_init', place=>{
        let center = {
          lat: place.coords.latitude,
          lng: place.coords.longitude
        };

        this.mapLat=center.lat;
        this.mapLng=center.lng;
        var google_maps_geocoder = new google.maps.Geocoder();
          
        google_maps_geocoder.geocode(
          { 'latLng': center }, 
          function( results, status ) {
              console.log("results");
              app.set_address(results[0].formatted_address);  
          }
        )

      });

      Bus.$on('location_changed', place=>{
        this.formatAddress=place.formatted_address;
      })

      Bus.$on('location_added', place=>{
        this.formatAddress=place;
      })


      Bus.$on('selectReport', o=> {
          console.log("Report:",o);
          this.selectedReport = o;
          this.showReports = false;
          this.showSpecificReport = true;
      });

      Bus.$on('dismiss', flag=> {
          if (flag)
            this[flag] = false;
          else {
              this.showModal = false;
              this.showSpecificReport = false;
              this.showReports = false;
          }
      });

      this.getNotificationsCount();

      Bus.$on('notifRead', yeah=> {
        this.unreadNotifCount--;
      });
      
    },

    methods:{
        set_address: function(address){
            this.formatAddress=address;
        },

        selectReport: function(o) {
            console.log(o);
        },

        getNotificationsCount() {
            axios.get('/notifications/unread/count')
                .then(response => {
                    this.unreadNotifCount = response.data.result;
                    // if (this.unreadNotifCount > 0) {
                    //     $('.js-notifications').addClass('unreads');
                    // } else {
                    //     $('.js-notifications').removeClass('unreads');
                    // }
                });
        }
    },

    watch: {
        // noqa
        showReports: function(newVal, oldVal) {
        },

        showModal: function(newVal, oldVal) {
            newVal && this.showReports ? this.showReports = false : null;
        },

        unreadNotifCount: function(newVal, oldVal) {
            if (newVal > 0) {
                $('.js-notifications').addClass('unreads');
            } else {
                $('.js-notifications').removeClass('unreads');
            }
        }
    }
});


let pusher = new Pusher('3b1cc74f234a6626b808', {
    cluster: 'ap1',
    encrypted: true
});

let channel = pusher.subscribe(`notif-${uid}`);
channel.bind('notification', function(data) {
    alert(data.message);
});

// const app = new Vue({
//     el: '#app'
// });


$('#navbarDropdown').dropdown();
