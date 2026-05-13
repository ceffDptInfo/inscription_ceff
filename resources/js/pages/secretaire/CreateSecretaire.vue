<script setup>
import AppHeader from '@/components/AppHeader.vue';
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    password_confirmation: ''
});

const submitForm = () => {
    form.post('/secretaire', {
        onSuccess: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="d-flex flex-column min-vh-100 bg-light">
        <AppHeader :showLogout="true" logoutRoute='/secretaire-logout' />
        
        <!-- Bouton Retour en haut à gauche, en dehors de la carte de connexion -->
        <div class="container pt-4">
            <Link href="/liste-candidats" class="text-secondary text-decoration-none d-inline-flex align-items-center">
                <span class="fs-4 me-2">&larr;</span> Retour
            </Link>
        </div>
        
        <div class="container flex-grow-1 d-flex justify-content-center align-items-center pb-5">
            <div class="w-100 bg-white p-5 rounded shadow-sm" style="max-width: 550px;">

                <div class="text-center">
                    <h1 class="fw-bold mb-3">Créer une secrétaire</h1>
                    <br>

                    <form @submit.prevent="submitForm">

                        <div class="mb-3">
                            <input 
                                type="email" 
                                class="form-control py-3" 
                                style="background-color: #EFEFEF;"
                                placeholder="E-mail" 
                                v-model="form.email" 
                                :disabled="form.processing"
                                required 
                            />
                            <div v-if="form.errors.email" class="text-danger small mt-2 text-start">
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <input 
                                type="password" 
                                class="form-control py-3" 
                                style="background-color: #EFEFEF;"
                                placeholder="Mot de passe" 
                                v-model="form.password" 
                                :disabled="form.processing"
                                required 
                            />
                            <div v-if="form.errors.password" class="text-danger small mt-2 text-start">
                                {{ form.errors.password }}
                            </div>
                        </div>

                        <div class="mb-4">
                            <input 
                                type="password" 
                                class="form-control py-3" 
                                style="background-color: #EFEFEF;"
                                placeholder="Confirmer le mot de passe" 
                                v-model="form.password_confirmation" 
                                :disabled="form.processing"
                                required 
                            />
                        </div>

                        <button 
                            type="submit" 
                            class="btn btn-dark w-100 py-3 fw-bold shadow-none" 
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Création en cours...</span>
                            <span v-else>Créer le compte</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>