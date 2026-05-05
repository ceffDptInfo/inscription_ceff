<script setup>
import { useForm } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    annexes: {
        type: Array,
        default: () => []
    }
});

const form = useForm({});

const submit = () => {
    form.post('/soumission-finale', {
        preserveScroll: true
    });
};
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        
        <AppHeader :showLogout="true" />

        <div class="container mt-4 mt-md-5">
            
            <h2 class="fs-4 fs-md-2">Annexes</h2>

            <div class="card shadow-sm border-0 mt-3 mt-md-4">
                
                <div class="card-body p-3 p-md-4">
                    <form @submit.prevent="submit">
                        
                        <div class="mb-4 mb-md-5">
                            <h4 class="mb-4 fw-bold fs-5 fs-md-4">Voici un récapitulatif de vos annexes</h4>
                            
                            <div class="d-flex flex-column gap-3 mb-4">
                                <div v-for="(annexe, index) in annexes" :key="index" class="d-flex flex-column flex-md-row align-items-md-center gap-2 gap-md-4">
                                    <div class="border rounded px-3 py-1 bg-white text-secondary text-truncate text-center" style="min-width: 150px; max-width: 200px;">
                                        {{ annexe.nom_fichier }}
                                    </div>
                                    <div class="text-dark fw-medium">
                                        {{ annexe.description }}
                                    </div>
                                </div>
                                
                                <div v-if="annexes.length === 0" class="text-muted fst-italic">
                                    Aucune annexe n'a été ajoutée.
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="d-flex flex-column flex-md-row justify-content-md-end gap-3 mt-4">
                            <a href="/informations" class="btn btn-light border px-4 py-2 fw-bold rounded-3 text-center">Précédent</a>
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold rounded-3" style="background-color: #16a34a;" :disabled="form.processing">
                                Soumettre
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>