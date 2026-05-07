<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    inscriptions: {
        type: Array,
        default: () => []
    }
});

const defaultInscriptions = props.inscriptions.length > 0 
    ? props.inscriptions 
    : [{ etablissement: '', lieu: '' }];

const form = useForm({
    inscriptions: defaultInscriptions
});

const addInscription = () => {
    form.inscriptions.push({ etablissement: '', lieu: '' });
};

const submit = () => {
    form.post('/autres-inscriptions');
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
                        
                        <h4 class="mb-4 fw-bold fs-5 fs-md-4">Autres inscriptions déposées</h4>

                        <div class="border rounded-3 mb-4">
                            <div class="row g-0 bg-light border-bottom py-2 px-3 fw-medium text-dark rounded-top d-none d-md-flex">
                                <div class="col-8">Ecoles / Entreprises (gymnase, apprentissage, etc.)</div>
                                <div class="col-4">Lieu</div>
                            </div>

                            <div v-for="(inscription, index) in form.inscriptions" :key="index" class="row g-0 py-3 py-md-2 px-3 align-items-center" :class="{ 'border-bottom': index !== form.inscriptions.length - 1 }">
                                <div class="col-12 col-md-8 pe-md-3 mb-3 mb-md-0">
                                    <label class="d-md-none small text-muted fw-bold mb-1">Ecoles / Entreprises</label>
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
                            <Link href="/stages" class="btn btn-light border px-4 py-2 fw-bold rounded-3 text-center">Précédent</Link>
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