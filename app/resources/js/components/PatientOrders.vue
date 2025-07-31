<template>
    <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
            <v-col cols="12" sm="8" md="6">
                <v-card class="pa-4" elevation="6" style="margin-top: 20px">
                    <v-card-title class="d-flex justify-space-between align-center mb-5">
                        <span class="text-h6">Orders</span>
                        <v-btn @click="emitReloadOrders" color="primary" variant="outlined">Reload</v-btn>
                    </v-card-title>

                    <v-card-text>
                        <v-row
                            dense
                            v-for="order in props.orders"
                            :key="order.orderId"
                            class="mb-10"
                        >
                            <v-col cols="12" class="mb-2">
                                <span class="font-weight-bold mr-1">Order ID:</span>
                                <span>{{ order.orderId }}</span>
                            </v-col>

                            <v-col
                                v-for="(result, index) in order.results"
                                :key="index"
                                cols="12"
                                class="mb-4"
                            >
                                <v-row dense>
                                    <v-col cols="4" class="text-center">
                                        <span class="font-weight-bold">Test Name:</span><br />
                                        <span>{{ result.name }}</span>
                                    </v-col>

                                    <v-col cols="4" class="text-center">
                                        <span class="font-weight-bold">Test Value:</span><br />
                                        <span>{{ result.value }}</span>
                                    </v-col>

                                    <v-col cols="4" class="text-center">
                                        <span class="font-weight-bold">Reference:</span><br />
                                        <span v-html="formatReference(result.reference)"></span>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>

                        <div v-if="props.orders.length === 0" class="text-center grey--text">
                            No orders available.
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">

import {ref} from 'vue'
import {Order} from "@/types/order";

const props = defineProps<{
    orders: Order[]
}>()
const emit = defineEmits<{
    (e: 'reloadOrders'): void
}>()

const orders = ref<Order[]>([]);

const formatReference = (text: string): string => {
    return text.replace(/\\X0A\\/g, '<br>')
}

const emitReloadOrders = () => {
    emit('reloadOrders')
}

</script>

