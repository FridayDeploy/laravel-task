import * as api from "@/api/api";

export const login = (fullName: string, dateOfBirth: string) => {
    return axios.post(api.PREFIX + 'login',
        {fullName: fullName, dateOfBirth: dateOfBirth}
    );
}

export const logout = () => {
    return axios.post(api.PREFIX + 'logout');
}
