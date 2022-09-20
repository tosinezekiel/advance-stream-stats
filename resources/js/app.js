import './bootstrap';
import '../css/app.css'; 
import router from './router';
import store from "./store";
import setupInterceptors from './services/setupInterceptors';


import Guest from './Layouts/Guest.vue';
import Auth from './Layouts/Auth.vue';
import Header from './Components/Header.vue';

import {createApp} from 'vue'

import App from './App.vue'

const app = createApp(App);


setupInterceptors(store);

app.component('Guest', Guest).component('Auth', Auth).component('Header', Header);

app.use(router).use(store).mount("#app");