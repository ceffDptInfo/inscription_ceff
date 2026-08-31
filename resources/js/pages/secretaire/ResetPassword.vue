<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/secretaire-reset-password/update', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-vh-100 d-flex flex-column">
        <AppHeader/>
        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
            <div class="col-xl-4">
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bolder text-dark mb-2">Nouveau mot de passe</h2>
                </div>
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <input type="email" class="form-control bg-light px-4 py-3" v-model="form.email" readonly>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control bg-light px-4 py-3" v-model="form.password" placeholder="Nouveau mot de passe" required autofocus>
                        <div v-if="form.errors.password" class="text-danger small mt-1">{{ form.errors.password }}</div>
                    </div>
                    <div class="mb-4">
                        <input type="password" class="form-control bg-light px-4 py-3" v-model="form.password_confirmation" placeholder="Confirmer le mot de passe" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-3 fw-bold" :disabled="form.processing">
                        Réinitialiser
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>