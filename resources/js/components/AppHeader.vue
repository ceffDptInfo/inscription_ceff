<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    showLogout: {
        type: Boolean,
        default: false,
    }
});
</script>

<template>
    <header class="text-white py-4 px-4 shadow-sm d-flex justify-content-center align-items-center position-relative" style="background-color: #4A4A4A;">
        <h1 class="h3 fw-bold mb-0 text-center">Portail Inscription CEFF</h1>

        <Link 
            v-if="showLogout"
            href="/candidat-logout"
            method="post"
            as="button"
            class="btn btn-danger rounded-circle d-flex justify-content-center align-items-center position-absolute end-0 me-4"
            style="width: 45px; height: 45px;"
            title="Déconnexion"
        >
            <i class="bi bi-box-arrow-right fs-5"></i>
        </Link>
    </header>
</template>