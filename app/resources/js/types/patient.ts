export interface Patient {
    id: number,
    name: string,
    surname: string,
    sex: string,
    birthDate: string,
}

export interface PatientStore {
    patient: Patient|null,
    jwt: string|null
}
