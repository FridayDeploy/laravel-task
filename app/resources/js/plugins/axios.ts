import axios from "axios";
import type { Router } from "vue-router";
import type { PatientStore } from "@/stores/patient";

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

export function setupAxiosInterceptors(router: Router, patientStore: PatientStore) {
    axios.interceptors.response.use(
        response => response,
        async (error) => {
            if (error.response?.status === 401) {
                patientStore.logoutPatient();
                await router.push({name: "login"})
            }
            return Promise.reject(error);
        }
    );

    axios.interceptors.request.use(config => {
        const patient: PatientStore = localStorage.getItem('patient')
        if (!patient) {
            return config
        }

        const token = JSON.parse(patient)?.jwt || null
        if(token) {
            config.headers['Authorization'] = `Bearer ${token}`
        }

        return config
    });
}

export default axios
