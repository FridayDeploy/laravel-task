import { defineStore } from 'pinia'
import {Patient, PatientStore} from "@/types/patient";

export const usePatientStore = defineStore('patient', {
    persist: true,

    state: () => {
        return {
            patient: null as Patient | null,
            jwt: null as string | null,
        }<PatientStore>
    },

    actions: {
        authenticatePatient(jwt: string): void {
            this.jwt = jwt;
        },

        setPatient(patient: Patient): void {
            this.patient = patient;
        },

        logoutPatient(): void {
            this.patient = null;
            this.jwt = null;
        }
    },

    getters: {
        isAuthenticated(): boolean {
            return !!this.jwt
        },

        patientFullName(): string {
            return this.patient ? this.patient.name + " " + this.patient.surname : "";
        }
    }
})
