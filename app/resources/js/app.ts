import '../css/app.css';

import pinia from './plugins/pinia'
import vuetify from './plugins/vuetify';
import axios, {setupAxiosInterceptors} from './plugins/axios'
import router from './plugins/router'
import {createApp, h, onMounted} from 'vue';
import App from "@/pages/App.vue";
import {usePatientStore} from "@/stores/patient";


const app = createApp({ render: () => h(App) })
    .use(vuetify)
    .use(pinia)
    .use(router)
    .mount('#app')

window.axios = axios;
const patientStore = usePatientStore()
setupAxiosInterceptors(router, patientStore)
