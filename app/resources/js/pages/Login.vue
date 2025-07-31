<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
            <v-col cols="12" sm="8" md="4">
                <v-card class="pa-4" elevation="6" style="margin-top: 150px">
                    <v-card-title class="text-h5 text-center">Sign in</v-card-title>
                    <v-card-text>
                        <v-form @submit.prevent="handleLogin">
                            <v-text-field
                                v-model="fullName"
                                label="Full Name ex. JohnSmith"
                                outlined
                                required
                            />
                            <v-text-field
                                v-model="dateOfBirth"
                                label="Date of birth"
                                outlined
                                required
                            />

                            <div v-for="error in errors" class="text-caption text-red">
                                {{ error }}
                            </div>

                            <v-btn :disabled="loading" type="submit" color="primary" block class="mt-4">
                                Check Your Results
                            </v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import {ref} from "vue";
import {login} from "@/api/auth";
import {useRouter} from "vue-router";
import {usePatientStore} from "@/stores/patient";

const fullName = ref('');
const dateOfBirth = ref('');
const errors = ref([]);
const loading = ref(false);
const router = useRouter();
const patientStore = usePatientStore();

const handleLogin = () => {
    loading.value = true;
    clearError();

    login(fullName.value, dateOfBirth.value)
        .then((r) => {
            patientStore.authenticatePatient(
                r.data.data.token
            )
            router.push({name: 'main'});
        })
        .catch((e) => {
            displayErrors(
                Object.values(e.response.data.errors).flat()
            )
        })
        .finally(() => {
            loading.value = false;
        })
}

const clearError = (): void => {
    errors.value = [];
}

const displayErrors = (newErrors): void => {
    errors.value = [...newErrors];
}

</script>
