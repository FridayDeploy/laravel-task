<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
            <v-col cols="12" sm="8" md="6">
                <v-card class="pa-4" elevation="6" style="margin-top: 20px">
                    <v-card-title class="d-flex justify-space-between align-center mb-5">
                        <span class="text-h6">Patient Data</span>
                        <LogoutButton />
                    </v-card-title>

                    <v-card-text>
                        <v-row v-if="patientStore.patient" dense>
                            <v-col cols="4" class="text-center">
                                <span class="font-weight-bold">Full Name:</span><br />
                                <span>{{ patientStore.patientFullName }}</span>
                            </v-col>

                            <v-col cols="4" class="text-center">
                                <span class="font-weight-bold">Sex:</span><br />
                                <span>{{ patientStore.patient.sex }}</span>
                            </v-col>

                            <v-col cols="4" class="text-center">
                                <span class="font-weight-bold">Date of Birth:</span><br />
                                <span>{{ patientStore.patient.birthDate }}</span>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">

import {usePatientStore} from "@/stores/patient";
import LogoutButton from "@/components/LogoutButton.vue";

const patientStore = usePatientStore();
</script>

