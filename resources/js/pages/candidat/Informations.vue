<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    compensation: Boolean,
    autorisation: Boolean,
    fichier_compensation: String,
    fichier_autorisation: String,
});

const form = useForm({
    compensation_reponse: props.compensation ?? false,
    document_compensation: null,
    autorisation_reponse: props.autorisation ?? false,
    document_autorisation: null,
});

const submit = () => {
    form.post('/informations', {
        forceFormData: true,
    });
};
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        
        <AppHeader :showLogout="true" />

        <div class="container mt-4 mt-md-5">
            
            <h2 class="fs-4 fs-md-2">Information</h2>

            <div class="card shadow-sm border-0 mt-4">
                
                <div class="card-body p-3 p-md-5">
                    <form @submit.prevent="submit">
                        
                        <h4 class="mb-3 fw-bold fs-5 fs-md-4">Compensation des désavantages</h4>
                        
                        <p class="mb-4 mb-md-5 text-dark">
                            Si vous disposez de mesures compensatoires dans votre établissement actuel, merci d'en joindre une copie à votre dossier. 
                            Ces documents seront transmis à notre coordinatrice, qui vérifiera leur validité afin qu'ils puissent être pris en considération si vous devez passer un examen d'admission.
                        </p>

                        <div class="d-flex flex-column flex-md-row align-items-md-center mb-3 gap-3">
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

                        <div v-if="form.compensation_reponse" class="row align-items-center p-3 ">
                            <label class="col-md-5 col-form-label fw-medium">
                                Joindre le document justificatif
                            </label>
                            <div class="col-12 col-md-7">
                                <label for="document_compensation" class="btn btn-outline-secondary w-100 d-block text-truncate mb-0 px-4 bg-white">
                                    {{ form.document_compensation ? form.document_compensation.name : (fichier_compensation || '+') }}
                                </label>
                                <input type="file" id="document_compensation" class="d-none" @input="form.document_compensation = $event.target.files[0]">
                            </div>
                        </div>
                        <div v-else class="mb-4 mb-md-5"></div>


                        <h4 class="mb-3 mt-5 fw-bold fs-5 fs-md-4">Élèves domiciliés hors du canton de berne</h4>
                        
                        <p class="mb-4 mb-md-5 text-dark">
                            Suivre une formation en dehors de son canton de domicile constitue une exception soumise à certaines conditions. Les candidats qui remplissent les critères d'admission doivent adresser une demande motivée à leur canton de domicile afin d'obtenir une autorisation intercantonale pour la prise en charge des frais d'écolage
                        </p>

                        <div class="d-flex flex-column flex-md-row align-items-md-center mb-3 gap-3">
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

                        <div v-if="form.autorisation_reponse" class="row align-items-center p-3">
                            <label class="col-md-5 col-form-label fw-medium">
                                Joindre l'autorisation intercantonale
                            </label>
                            <div class="col-12 col-md-7">
                                <label for="document_autorisation" class="btn btn-outline-secondary w-100 d-block text-truncate mb-0 px-4 bg-white">
                                    {{ form.document_autorisation ? form.document_autorisation.name : (fichier_autorisation || '+') }}
                                </label>
                                <input type="file" id="document_autorisation" class="d-none" @input="form.document_autorisation = $event.target.files[0]">
                            </div>
                        </div>
                        <div v-else class="mb-4 mb-md-5"></div>


                        <div class="d-grid gap-3 d-md-flex justify-content-md-center mb-4 mb-md-5 mt-4">
                            <a href="https://www.jura.ch/DFCS/SFO/Formations-hors-canton.html" target="_blank" class="btn btn-outline-dark px-4 py-3 fw-bold rounded-3 shadow-sm">
                                Jura.ch
                            </a>
                            <a href="https://www.ne.ch/autorites/DFDS/SFPO/formations/Pages/Formations-hors-canton.aspx" target="_blank" class="btn btn-outline-dark px-4 py-3 fw-bold rounded-3 shadow-sm">
                                ne.ch
                            </a>
                            <a href="https://www.bkd.be.ch/content/dam/bkd/dokumente/fr/dienstleistungen/frequentation-ecole-hors-canton/frequentation-ecole-hors-canton/frequentation-ecole-professionnelle-initiale-hors-canton-a-plein-temps/Formulaire-AEPr.pdf" target="_blank" class="btn btn-outline-dark px-4 py-3 fw-bold rounded-3 shadow-sm">
                                Autres cantons
                            </a>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="d-flex flex-column flex-md-row justify-content-md-end gap-3 mt-4">
                            <Link href="/autres-inscriptions" class="btn btn-light border px-4 py-2 fw-bold rounded-3 text-center">Précédent</Link>
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold rounded-3" style="background-color: #38bdf8;" :disabled="form.processing">
                                Suivant
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>