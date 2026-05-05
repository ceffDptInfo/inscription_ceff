<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    stages: {
        type: Array,
        default: () => []
    }
});

const defaultStages = props.stages.length > 0 
    ? props.stages 
    : [{ metier: '', entreprise: '', lieu: '', duree: '' }];

const form = useForm({
    stages: defaultStages
});

const addStage = () => {
    form.stages.push({ metier: '', entreprise: '', lieu: '', duree: '' });
};

const submit = () => {
    form.post('/stages');
};
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        
        <AppHeader :showLogout="true" />

        <div class="container mt-4 mt-md-5">
            
            <h2 class="fs-4 fs-md-2">Intérêts professionnels</h2>

            <div class="card shadow-sm border-0 mt-3 mt-md-4">
                
                <div class="card-body p-3 p-md-4">
                    <form @submit.prevent="submit">
                        
                        <h4 class="mb-4 fw-bold fs-5 fs-md-4">Stages</h4>

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

                        <hr class="my-4 text-muted">

                        <div class="d-flex flex-column flex-md-row justify-content-md-end gap-3 mt-4">
                            <a href="/parcours-scolaire" class="btn btn-light border px-4 py-2 fw-bold rounded-3 text-center">Précédent</a>
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold rounded-3" style="background-color: #38bdf8;">
                                Suivant
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>