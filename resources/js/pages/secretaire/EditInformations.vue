<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    candidat: Object
});

const form = useForm({
    compensation_reponse: props.candidat?.compensations?.reponse ? true : false,
    autorisation_reponse: props.candidat?.autorisation_hors_canton?.reponse ? true : false,
});

const submit = () => {
    form.put(`/candidats/edit/${props.candidat.id}/informations`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        
        <AppHeader :showLogout="true" logoutRoute="/secretaire-logout" />

        <div class="container mt-4 mt-md-5">
            
            <Link :href="`/candidat-details/${candidat.id}`" class="text-dark text-decoration-none mb-4 d-inline-block fs-5">
                <i class="bi bi-chevron-left"></i> Retour
            </Link>

            <h2 class="fs-4 fs-md-2">Modification des informations</h2>

            <div class="card shadow-sm border-0 mt-4">
                
                <div class="card-body p-3 p-md-5">
                    <form @submit.prevent="submit">
                        
                        <h4 class="mb-3 fw-bold fs-5 fs-md-4">Compensation des désavantages</h4>
                        
                        <div class="d-flex flex-column flex-md-row align-items-md-center mb-5 gap-3">
                            <span class="me-md-4 fw-medium text-center text-md-start">Disposez vous de mesures compensatoires ?</span>
                            <div class="d-flex gap-4 justify-content-center ms-md-auto">
                                <div class="d-flex flex-column align-items-center">
                                    <label class="mb-2 small">Oui</label>
                                    <input type="radio" class="form-check-input mt-0" :value="true" v-model="form.compensation_reponse">
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <label class="mb-2 small">Non</label>
                                    <input type="radio" class="form-check-input mt-0" :value="false" v-model="form.compensation_reponse">
                                </div>
                            </div>
                        </div>

                        <h4 class="mb-3 mt-5 fw-bold fs-5 fs-md-4">Élèves domiciliés hors du canton de Berne</h4>
                        
                        <div class="d-flex flex-column flex-md-row align-items-md-center mb-5 gap-3">
                            <span class="me-md-4 fw-medium text-center text-md-start">Disposez d'une autorisation afin de suivre une formation hors canton ?</span>
                            <div class="d-flex gap-4 justify-content-center ms-md-auto">
                                <div class="d-flex flex-column align-items-center">
                                    <label class="mb-2 small">Oui</label>
                                    <input type="radio" class="form-check-input mt-0" :value="true" v-model="form.autorisation_reponse">
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <label class="mb-2 small">Non</label>
                                    <input type="radio" class="form-check-input mt-0" :value="false" v-model="form.autorisation_reponse">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold rounded-3 w-100 w-md-auto" style="background-color: #38bdf8;" :disabled="form.processing">
                                Enregistrer
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>