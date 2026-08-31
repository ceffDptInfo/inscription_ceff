<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/secretaire-login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-vh-100 d-flex flex-column">
        
        <AppHeader/>

        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
            <div class="col-xl-4">
                
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bolder text-dark mb-2">Accès à la liste des candidats</h2>
                    <p class="text-muted">Veuillez vous connecter</p>
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <input id="email" type="email" class="form-control bg-light px-4 py-3" v-model="form.email" placeholder="E-mail" required autofocus autocomplete="username">
                        <div v-if="form.errors.email" class="text-danger small mt-1">{{ form.errors.email }}</div>
                    </div>

                    <div class="mb-4">
                        <input id="password" type="password" class="form-control bg-light px-4 py-3" v-model="form.password" placeholder="Mot de passe" required autocomplete="current-password">
                        <div v-if="form.errors.password" class="text-danger small mt-1">{{ form.errors.password }}</div>
                        <div class="text-end mt-2">
                            <Link href="/secretaire-reset-password" class="small text-muted text-decoration-none">Mot de passe oublié ?</Link>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark w-100 py-3 fw-bold">
                            Envoyer
                        </button>
                    </div>
                </form>

                <div class="text-center mt-5">
                    <a href="/" class="text-muted small text-decoration-none">Retour à l'accueil</a>
                </div>

            </div>
        </div>
    </div>
</template>