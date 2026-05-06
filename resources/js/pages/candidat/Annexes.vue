<script setup>
import { ref } from 'vue';
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
const showModal = ref(false);

const openModal = () => {
    if (props.documents_manquants.length === 0) {
        showModal.value = true;
    }
};

const closeModal = () => {
    showModal.value = false;
};

const confirmSubmit = () => {
    showModal.value = false;
    form.post('/form', {
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
                    <form @submit.prevent="openModal">
                        
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
                                
                                <div v-if="annexes.length === 0" class="text-muted fst-italic">
                                    Aucune annexe n'a été ajoutée.
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

        <div v-if="showModal" class="modal fade show d-block"  style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pb-0">
                        <h5 class="modal-title fw-bold">Confirmation de soumission</h5>
                        <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-0">Êtes-vous sûr de vouloir soumettre votre candidature ?</p>
                        <p class="text-danger mt-2 fw-medium mb-0">Attention : Une fois soumise, vous n'aurez plus accès à votre dossier pour le modifier.</p>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light border fw-medium" @click="closeModal">Annuler</button>
                        <button type="button" class="btn text-white fw-bold" style="background-color: #16a34a;" @click="confirmSubmit" :disabled="form.processing">
                            Confirmer l'envoi
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>