<script setup lang="ts">

import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppHeader from '@/components/AppHeader.vue';

const form = useForm({
    email: '',
});

const mailSent = ref(false);

const submitForm = () => {
    form.post('/send-link', {
        onSuccess: () => {
            mailSent.value = true;
        }
    });
};
</script>

<template>
    <div class="d-flex flex-column min-vh-100">
        <AppHeader/>
        
        <div class="container flex-grow-1 d-flex justify-content-center align-items-center">
            <div class="w-100" style="max-width: 550px;">

                <div class="text-center">
                    <h1 class="fw-bold mb-3">Accès au formulaire d'inscription</h1>
                    <p class="text-secondary mb-5">
                        Veuillez indiquer votre adresse e-mail afin qu'un<br>
                        lien d'accès au formulaire vous soit envoyé.
                    </p>

                    <div v-if="mailSent" class="alert alert-success mb-4 text-start">
                        Un e-mail contenant le lien d'accès vous a été envoyé avec succès. Veuillez vérifier votre boîte de réception.
                    </div>
                    
                    <form @submit.prevent="submitForm">
                        <div class="mb-3">
                            <input 
                                type="email" 
                                class="form-control py-3" 
                                style="background-color: #EFEFEF;"
                                placeholder="E-mail" 
                                v-model="form.email" 
                                :disabled="form.processing || mailSent"
                                required 
                            />
                            <div v-if="form.errors.email" class="text-danger small mt-2 text-start">
                                {{ form.errors.email }}
                            </div>
                        </div>
                        <button 
                            type="submit" 
                            class="btn btn-dark w-100 py-3 fw-bold shadow-none" 
                            :disabled="form.processing || mailSent"
                        >
                            <span v-if="form.processing">Envoi en cours...</span>
                            <span v-else>Envoyer</span>
                        </button>
                    </form>
                    <div class="text-center mt-5">
                        <a href="/" class="text-muted small text-decoration-none">Retour à l'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>