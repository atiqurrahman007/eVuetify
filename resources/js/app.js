
require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import {routes} from'./routes';
import vuetify from'./vuetify';
import 'babel-polyfill'




Vue.use(VueRouter)
Vue.component('main-app', require('./components/App.vue').default);

const router = new VueRouter({
	routes,
	mode:'history'
})

 new Vue({
    el: '#app',
    router,
    vuetify
  
});
