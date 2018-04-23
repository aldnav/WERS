
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

window.Bus = new Vue;

Vue.use(VueSweetalert2);
Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyA7a-pVRxc_cx00QNTiPWQZW50qxiqZGO0',
        libraries: 'places', 
    }
});

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


const app = new Vue({
    el: '#app',
    data:{
    	showModal:false,
    	mapLat:0,
    	mapLng:0
    },
    created(){
      Bus.$on('marker_changed', place=>{
        this.mapLng = place.lng;
        this.mapLat = place.lat;
      });

      Bus.$on('marker_dragged', place=>{
        this.mapLng = place.lng();
        this.mapLat = place.lat();
      });
    },
});
