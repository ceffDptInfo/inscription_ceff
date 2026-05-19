<script setup>
import { useForm } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const form = useForm({
    email: '',
});

const submit = () => {
    form.post('/secretaire-reset-password');
};
</script>

<template>
    <div class="min-vh-100 d-flex flex-column">
        <AppHeader/>
        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
            <div class="col-xl-4">
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bolder text-dark mb-2">Mot de passe oublié</h2>
                    <p class="text-muted">Entrez votre e-mail pour recevoir un lien de réinitialisation</p>
                </div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <input type="email" class="form-control bg-light px-4 py-3" v-model="form.email" placeholder="E-mail" required autofocus>
                        <div v-if="form.errors.email" class="text-danger small mt-1">{{ form.errors.email }}</div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-3 fw-bold" :disabled="form.processing">
                        Envoyer le lien
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>