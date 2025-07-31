import * as api from "@/api/api";

export const getPatientOrders = () => {
    return axios.get(api.PREFIX + `results`)
}
