<template>
    <PatientData />
    <PatientOrders :orders="orders" @reload-orders="loadPatientOrders"/>
</template>

<script setup lang="ts">


import PatientData from "@/components/PatientData.vue";
import PatientOrders from "@/components/PatientOrders.vue";
import {getPatientOrders} from "@/api/patients";
import {onMounted, ref} from "vue";
import {Order} from "@/types/order";
import {usePatientStore} from "@/stores/patient";

const orders = ref<Order[]>([]);
const patientStore = usePatientStore()

const loadPatientOrders = () => {
    getPatientOrders()
        .then((r) => {
            orders.value = [...r.data.data.orders];
            patientStore.setPatient(r.data.data.patient);
        })
}

onMounted(() => {
    loadPatientOrders()
})
</script>
