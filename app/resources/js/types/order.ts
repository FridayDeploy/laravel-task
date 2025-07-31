import {Patient} from "@/types/patient";
import {Result} from '@/types/result'

export interface OrderResource {
    patient: Patient
    orders: Order[],
}

export interface Order {
    orderId: number,
    results: Result[]
}
