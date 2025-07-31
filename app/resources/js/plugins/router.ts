import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from "@/pages/Dashboard.vue";
import Login from "@/pages/Login.vue";
import {usePatientStore} from "@/stores/patient";
import PageNotFound from "@/pages/PageNotFound.vue";

const routes = [
    { path: '/', name: 'main', component: Dashboard },
    { path: '/login', name: 'login', component: Login },
    { path: '/:pathMatch(.*)*', name: '404', component: PageNotFound },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from) => {
    const patient = usePatientStore()

    if(to.name === "login" && patient.isAuthenticated) {
        return { name: "main", replace: true };
    }

    if(to.name !== "login" && !patient.isAuthenticated) {
        return { name: "login", replace: true }
    }

    return true;
})

export default router
