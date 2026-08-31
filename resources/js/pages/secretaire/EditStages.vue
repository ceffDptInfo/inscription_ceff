<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

interface Stage {
    metier: string;
    entreprise: string;
    lieu: string;
    duree: string;
}

interface Inscription {
    etablissement: string;
    lieu: string;
}

interface Candidat {
    id: number;
    stages?: Stage[];
    autres_inscriptions?: Inscription[];
}

const props = defineProps<{
    candidat: Candidat;
}>();

const defaultStages: Stage[] = props.candidat.stages?.length
    ? props.candidat.stages
    : [{ metier: '', entreprise: '', lieu: '', duree: '' }];

const defaultInscriptions: Inscription[] = props.candidat.autres_inscriptions?.length
    ? props.candidat.autres_inscriptions
    : [{ etablissement: '', lieu: '' }];

const form = useForm<{
    stages: Stage[];
    inscriptions: Inscription[];
}>({
    stages: defaultStages,
    inscriptions: defaultInscriptions
});

const addStage = () => {
    form.stages.push({ metier: '', entreprise: '', lieu: '', duree: '' });
};

const addInscription = () => {
    form.inscriptions.push({ etablissement: '', lieu: '' });
};

const submit = () => {
    form.put(`/candidats/edit/${props.candidat.id}/stages`, {
        preserveScroll: true
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

            <h2 class="fs-4 fs-md-2">Modification des intérêts professionnels</h2>

            <div class="card shadow-sm border-0 mt-3 mt-md-4">
                
                <div class="card-body p-3 p-md-4">
                    <form @submit.prevent="submit">
                        
                        <h4 class="mb-4 fw-bold fs-5 fs-md-4">Stages effectués</h4>

                        <div class="border rounded-3 mb-4">
                            <div class="row g-0 bg-light border-bottom py-2 px-3 fw-medium text-dark rounded-top d-none d-md-flex">
                                <div class="col-3">Métier</div>
                                <div class="col-3">Entreprise</div>
                                <div class="col-3">Lieu</div>
                                <div class="col-3">Durée</div>
                            </div>

                            <div v-for="(stage, index) in form.stages" :key="index" class="row g-0 py-3 py-md-2 px-3 align-items-center" :class="{ 'border-bottom': index !== form.stages.length - 1 }">
                                <div class="col-12 col-md-3 pe-md-2 mb-3 mb-md-0">
                                    <label class="d-md-none small text-muted fw-bold mb-1">Métier</label>
                                    <input type="text" class="form-control" v-model="stage.metier">
                                </div>
                                <div class="col-12 col-md-3 pe-md-2 mb-3 mb-md-0">
                                    <label class="d-md-none small text-muted fw-bold mb-1">Entreprise</label>
                                    <input type="text" class="form-control" v-model="stage.entreprise">
                                </div>
                                <div class="col-12 col-md-3 pe-md-2 mb-3 mb-md-0">
                                    <label class="d-md-none small text-muted fw-bold mb-1">Lieu</label>
                                    <input type="text" class="form-control" v-model="stage.lieu">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="d-md-none small text-muted fw-bold mb-1">Durée</label>
                                    <input type="text" class="form-control" v-model="stage.duree">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-5">
                            <button type="button" class="btn text-white px-4 py-2 fw-bold rounded-3" style="background-color: #38bdf8;" @click="addStage">
                                + Ajouter
                            </button>
                        </div>

                        <hr class="my-5 text-muted">

                        <h4 class="mb-4 fw-bold fs-5 fs-md-4">Autres inscriptions déposées</h4>

                        <div class="border rounded-3 mb-4">
                            <div class="row g-0 bg-light border-bottom py-2 px-3 fw-medium text-dark rounded-top d-none d-md-flex">
                                <div class="col-8">Écoles / Entreprises (gymnase, apprentissage, etc.)</div>
                                <div class="col-4">Lieu</div>
                            </div>

                            <div v-for="(inscription, index) in form.inscriptions" :key="index" class="row g-0 py-3 py-md-2 px-3 align-items-center" :class="{ 'border-bottom': index !== form.inscriptions.length - 1 }">
                                <div class="col-12 col-md-8 pe-md-3 mb-3 mb-md-0">
                                    <label class="d-md-none small text-muted fw-bold mb-1">Écoles / Entreprises</label>
                                    <input type="text" class="form-control" v-model="inscription.etablissement">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="d-md-none small text-muted fw-bold mb-1">Lieu</label>
                                    <input type="text" class="form-control" v-model="inscription.lieu">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mb-5">
                            <button type="button" class="btn text-white px-4 py-2 fw-bold rounded-3" style="background-color: #38bdf8;" @click="addInscription">
                                + Ajouter
                            </button>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="d-flex flex-column flex-md-row justify-content-md-end gap-3 mt-4">
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold rounded-3" style="background-color: #38bdf8;" :disabled="form.processing">
                                Enregistrer
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>
