<script setup>
import { useForm } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    annexes: {
        type: Array,
        default: () => []
    },
    documents_manquants: {
        type: Array,
        default: () => []
    }
});

const form = useForm({});

const submit = () => {
    if (props.documents_manquants.length === 0) {
        form.post('/soumission-finale', {
            preserveScroll: true
        });
    }
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
                        
                        <div v-if="documents_manquants.length > 0" class="alert alert-warning mb-4 border-warning">
                            <h5 class="alert-heading fw-bold mb-3 text-warning-emphasis">
                                Documents manquants
                            </h5>
                            <p class="mb-2 text-warning-emphasis">
                                Afin de finaliser votre inscription, vous devez retourner sur les pages précédentes et téléverser les documents obligatoires suivants :
                            </p>
                            <ul class="mb-0 text-warning-emphasis fw-medium">
                                <li v-for="(doc, index) in documents_manquants" :key="index">
                                    {{ doc }}
                                </li>
                            </ul>
                        </div>

                        <div class="mb-4 mb-md-5">
                            <h4 class="mb-4 fw-bold fs-5 fs-md-4">Voici un récapitulatif de vos annexes</h4>
                            
                            <div class="d-flex flex-column gap-3 mb-4">
                                <div v-for="(annexe, index) in annexes" :key="index" class="d-flex flex-column flex-md-row align-items-md-center gap-2 gap-md-4">
                                    <div class="border rounded px-3 py-2 bg-white text-secondary text-truncate text-center border-secondary" style="min-width: 150px; max-width: 250px;">
                                        {{ annexe.nom_fichier }}
                                    </div>
                                    <div class="text-dark fw-medium">
                                        {{ annexe.description }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="d-flex flex-column flex-md-row justify-content-md-end gap-3 mt-4">
                            <a href="/choix-apprentissage" class="btn btn-light border px-4 py-2 fw-bold rounded-3 text-center">Précédent</a>
                            <button 
                                type="submit" 
                                class="btn text-white px-5 py-2 fw-bold rounded-3" 
                                style="background-color: #16a34a;" 
                                :disabled="form.processing || documents_manquants.length > 0"
                                :class="{ 'opacity-50': documents_manquants.length > 0 }"
                            >
                                Soumettre
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>