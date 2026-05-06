<script setup>
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    candidats: Array
});

const statuts = [
    'Nouveau Candidat',
    'Candidature incomplète',
    'Candidature complète',
    'Candidature retirée',
    'Convocation envoyée'
];

const updateStatut = (id, nouveauStatut) => {
    router.put(`/liste-candidats/${id}/statut`, { statut: nouveauStatut }, {
        preserveScroll: true
    });
};

const deleteCandidat = (id) => {
    if (confirm('Voulez-vous vraiment supprimer ce candidat ainsi que tous ses informations / fichiers joints ?')) {
        router.delete(`/candidat/${id}`);
    }
};
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        <AppHeader :showLogout="true" logoutRoute='/secretaire-logout' />

        <div class="container mt-4 mt-md-5">
            <div class="table-responsive bg-white shadow-sm rounded">
                <table class="table align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th scope="col" class="py-3 px-4 fw-normal">Nom Prénom</th>
                            <th scope="col" class="py-3 px-4 fw-normal">E-Mail</th>
                            <th scope="col" class="py-3 px-4 fw-normal">Status</th>
                            <th scope="col" class="py-3 px-4 fw-normal text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="candidat in candidats" :key="candidat.id" class="border-bottom">
                            <td class="py-3 px-4">
                                {{ candidat.donnees_personnelles?.nom }} {{ candidat.donnees_personnelles?.prenom }}
                            </td>
                            <td class="py-3 px-4">{{ candidat.email }}</td>
                            <td class="py-3 px-4">
                                <select 
                                    class="form-select text-white border-0 shadow-sm" 
                                    style="background-color: #4361ee; width: max-content; cursor: pointer;"
                                    v-model="candidat.statut" 
                                    @change="updateStatut(candidat.id, candidat.statut)">
                                    <option v-for="statut in statuts" :key="statut" :value="statut" class="bg-white text-dark">
                                        {{ statut }}
                                    </option>
                                </select>
                            </td>
                            <td class="py-3 px-4 text-end">
                                <Link :href="`/candidat-details/${candidat.id}`" class="text-dark text-decoration-underline me-3">
                                    Visualiser
                                </Link>
                                <a :href="`mailto:${candidat.email}`" class="text-dark text-decoration-underline me-3">
                                    Mail
                                </a>
                                <button @click="deleteCandidat(candidat.id)" class="btn btn-link text-danger text-decoration-underline p-0 border-0 align-baseline">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>