<template>
    <v-btn @click="handleLogout" color="error" variant="outlined">Logout</v-btn>
</template>

<script setup lang="ts">
import {logout} from "@/api/auth";
import {useRouter} from "vue-router";
import {usePatientStore} from "@/stores/patient";

const router = useRouter()
const patientStore = usePatientStore()

const handleLogout = () => {
    logout()
        .then(() => {
            patientStore.logoutPatient();
            router.push({name: "login"})
        })
}
</script>

<style scoped>

</style>
