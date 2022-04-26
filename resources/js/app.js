require('./bootstrap');
import Vue from 'vue';
import vuetify from './vuetify'
import router from "./router";
import api from "./api";

import Dashboard from "./components/Dashboard";

window.EventBus = new Vue();

const app = new Vue({
    el: "#app",
    vuetify,
    router,
    render: h => h(Dashboard)
});






