
require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import {routes} from'./routes';
import vuetify from'./vuetify';
import Vuex from 'vuex';
import 'babel-polyfill';
import Snotify, { SnotifyPosition } from 'vue-snotify';
import VueProgressBar from 'vue-progressbar';
import storeData from './store/index';


Vue.use(Vuex)
Vue.use(VueRouter)
Vue.component('main-app', require('./components/App.vue').default);
// VueRouter
const router = new VueRouter({
	routes,
	mode:'history'
});
// VueX
const store = new Vuex.Store(storeData)
// Snotify
const SnotifyOptions = {
   toast: {
     position: SnotifyPosition.rightTop
   }
 }
 
 Vue.use(Snotify, SnotifyOptions)
// ProgressBar
const ProgressBarOptions = {
   color: '#ffc107',
   failedColor: '#FF0000',
   thickness: '5px',
   transition: {
     speed: '0.2s',
     opacity: '0.6s',
     termination: 300
   },
   autoRevert: true,
   location: 'top',
   inverse: false
 }
 
 Vue.use(VueProgressBar, ProgressBarOptions)



// Send Token 
router.beforeEach((to, from, next) => {
   const token = localStorage.getItem('token') || null
  window.axios.defaults.headers['Authorization'] = "Bearer " + token;
  next();
});


 new Vue({
    el: '#app',
    router,
    vuetify,
    store
  
});
