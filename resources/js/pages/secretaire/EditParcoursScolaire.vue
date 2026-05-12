<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    candidat: Object
});

const parcours = props.candidat?.parcours_scolaire || {};

const form = useForm({
    type_parcours: parcours.type_parcours ?? 'Canton de Berne',
    nom_ecole: parcours.nom_ecole ?? '',
    lieu_ecole: parcours.lieu_ecole ?? '',
    niveau_francais: parcours.niveau_francais ?? '',
    niveau_math: parcours.niveau_math ?? '',
    niveau_allemand: parcours.niveau_allemand ?? '',
    description_activite: parcours.description_activite ?? '',
});

const submit = () => {
    form.put(`/candidats/edit/${props.candidat.id}/parcours-scolaire`, {
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

            <h2 class="fs-4 fs-md-2">Modification du parcours avant admission</h2>

            <div class="card shadow-sm border-0 mt-3 mt-md-4">
                
                <div class="card-body p-3 p-md-4">
                    <form @submit.prevent="submit">
                        
                        <div class="mb-4 mb-md-5">
                            <p class="mb-3 fw-medium">Où avez-vous suivi votre scolarité secondaire ?</p>
                            <div class="d-flex flex-column flex-md-row gap-3 gap-md-4 mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="form.type_parcours" value="Canton de Berne" id="berne">
                                    <label class="form-check-label" for="berne">Canton de Berne</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="form.type_parcours" value="Autre / Etranger" id="autre">
                                    <label class="form-check-label" for="autre">Autre / Étranger</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="form.type_parcours" value="Autre activite" id="activite">
                                    <label class="form-check-label" for="activite">Autre activité / travail</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4 mb-md-5">
                            <div class="col-12 col-lg-8 col-xl-6">
                                
                                <template v-if="form.type_parcours === 'Canton de Berne' || form.type_parcours === 'Autre / Etranger'">
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-5 col-form-label fw-medium">Nom de l'école</label>
                                        <div class="col-12 col-md-7">
                                            <input type="text" class="form-control" v-model="form.nom_ecole" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-5 col-form-label fw-medium">Lieu de l'école</label>
                                        <div class="col-12 col-md-7">
                                            <input type="text" class="form-control" v-model="form.lieu_ecole" required>
                                        </div>
                                    </div>
                                </template>

                                <template v-if="form.type_parcours === 'Autre activite'">
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-5 col-form-label fw-medium">Description de l'activité</label>
                                        <div class="col-12 col-md-7">
                                            <textarea class="form-control" v-model="form.description_activite" rows="3"></textarea>
                                        </div>
                                    </div>
                                </template>

                            </div>
                        </div>

                        <div class="mb-5" v-if="form.type_parcours === 'Canton de Berne'">
                            <h4 class="mb-4 fs-5 fs-md-4">Niveau du dernier bulletin</h4>
                            
                            <div class="row">
                                <div class="col-12 col-lg-5 col-xl-4">
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-5 col-form-label fw-medium">Français</label>
                                        <div class="col-12 col-md-7">
                                            <select class="form-select" v-model="form.niveau_francais" required>
                                                <option value="" disabled></option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-5 col-form-label fw-medium">Math</label>
                                        <div class="col-12 col-md-7">
                                            <select class="form-select" v-model="form.niveau_math" required>
                                                <option value="" disabled></option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-5 col-form-label fw-medium">Allemand</label>
                                        <div class="col-12 col-md-7">
                                            <select class="form-select" v-model="form.niveau_allemand" required>
                                                <option value="" disabled></option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                        </div>
                                    </div>
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