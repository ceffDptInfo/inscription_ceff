<script setup>
import { Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    candidat: Object
});
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        <AppHeader :showLogout="true" logoutRoute="/secretaire-logout" />

        <div class="container mt-4">
            <Link href="/liste-candidats" class="text-dark text-decoration-none mb-4 d-inline-block fs-5">
                <i class="bi bi-chevron-left"></i> Retour
            </Link>

            <div class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="h5 mb-0 me-3">1. Données personnelles</h3>
                    <Link :href="`/candidats/edit/${candidat.id}/donnees-personnelles`" class="text-dark">
                        <i class="bi bi-pencil-square"></i>
                    </Link>
                </div>
                <div class="bg-white shadow-sm rounded overflow-hidden">
                    <table class="table mb-0 border">
                        <tbody>
                            <tr>
                                <th class="bg-light w-25 fw-normal px-3 py-2 border-end">Nom</th>
                                <td class="px-3 py-2">{{ candidat.donnees_personnelles?.nom }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Prénom</th>
                                <td class="px-3 py-2">{{ candidat.donnees_personnelles?.prenom }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Adresse</th>
                                <td class="px-3 py-2">
                                    {{ candidat.donnees_personnelles?.rue_et_num }}, {{ candidat.donnees_personnelles?.npa_localite }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Date de naissance</th>
                                <td class="px-3 py-2">{{ new Date(candidat.donnees_personnelles?.date_naissance).toLocaleDateString('fr-FR') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Langue maternelle</th>
                                <td class="px-3 py-2">{{ candidat.donnees_personnelles?.langue_maternelle }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">No AVS</th>
                                <td class="px-3 py-2">{{ candidat.donnees_personnelles?.no_avs }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Tel fixe / portable</th>
                                <td class="px-3 py-2">
                                    {{ candidat.donnees_personnelles?.tel_fixe || '-' }} / {{ candidat.donnees_personnelles?.tel_portable || '-'}}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Genre</th>
                                <td class="px-3 py-2">{{ candidat.donnees_personnelles?.genre }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Email privé</th>
                                <td class="px-3 py-2">{{ candidat.donnees_personnelles?.email_prive }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Nationalité</th>
                                <td class="px-3 py-2">{{ candidat.donnees_personnelles?.nationalite }}</td>
                            </tr>
                            
                            <template v-if="candidat.donnees_personnelles?.nationalite !== 'Suisse'">
                                <tr>
                                    <th class="bg-light fw-normal px-3 py-2 border-end">Pays d'origine</th>
                                    <td class="px-3 py-2">{{ candidat.donnees_personnelles?.pays_origine }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light fw-normal px-3 py-2 border-end">Type de permis</th>
                                    <td class="px-3 py-2">{{ candidat.donnees_personnelles?.type_permis }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light fw-normal px-3 py-2 border-end">Validité du permis</th>
                                    <td class="px-3 py-2">{{ new Date(candidat.donnees_personnelles?.validite_permis).toLocaleDateString('fr-FR') }}</td>
                                </tr>
                            </template>

                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Remarques</th>
                                <td class="px-3 py-2">{{ candidat.donnees_personnelles?.remarques }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-5">
                <h4 class="h6 mb-3">1.1. Représentants legaux</h4>
                <div v-for="rep in candidat.representants_legaux" :key="rep.id" class="mb-4">
                    <p class="fw-bold mb-2">{{ rep.type_lien }} :</p>
                    <div class="bg-white shadow-sm rounded overflow-hidden">
                        <table class="table mb-0 border">
                            <tbody>
                                <tr>
                                    <th class="bg-light w-25 fw-normal px-3 py-2 border-end">Nom Prénom</th>
                                    <td class="px-3 py-2">{{ rep.nom }} {{ rep.prenom }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light fw-normal px-3 py-2 border-end">Adresse</th>
                                    <td class="px-3 py-2">{{ rep.rue_et_num }}, {{ rep.npa_localite }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light fw-normal px-3 py-2 border-end">Tél fixe / portable</th>
                                    <td class="px-3 py-2">{{ rep.tel_fixe || "-"}} / {{ rep.tel_portable || "-" }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light fw-normal px-3 py-2 border-end">Email privé</th>
                                    <td class="px-3 py-2">{{ rep.email }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="h5 mb-0 me-3">2. Parcours avant admission</h3>
                    <Link :href="`/candidats/edit/${candidat.id}/parcours-scolaire`" class="text-dark">
                        <i class="bi bi-pencil-square"></i>
                    </Link>
                </div>
                <div class="bg-white shadow-sm rounded overflow-hidden">
                    <table class="table mb-0 border">
                        <tbody>
                            <template v-if="candidat.parcours_scolaire?.type_parcours === 'Canton de Berne'">
                                <tr>
                                <th class="bg-light w-25 fw-normal px-3 py-2 border-end">Nom de l'école / lieu</th>
                                <td colspan="3" class="px-3 py-2">{{ candidat.parcours_scolaire?.nom_ecole }}, {{ candidat.parcours_scolaire?.lieu_ecole }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light w-25 fw-normal px-3 py-2 border-end">Niveau français</th>
                                <td colspan="3" class="px-3 py-2">{{ candidat.parcours_scolaire?.niveau_francais }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light w-25 fw-normal px-3 py-2 border-end">Niveau allemand</th>
                                <td colspan="3" class="px-3 py-2">{{ candidat.parcours_scolaire?.niveau_allemand }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light w-25 fw-normal px-3 py-2 border-end">Niveau mathématique</th>
                                <td colspan="3" class="px-3 py-2">{{ candidat.parcours_scolaire?.niveau_math }}</td>
                            </tr>
                            </template>
                            <template v-if="candidat.parcours_scolaire?.type_parcours === 'Autre / Etranger'">
                                <tr>
                                <th class="bg-light w-25 fw-normal px-3 py-2 border-end">Nom de l'école / lieu</th>
                                <td colspan="3" class="px-3 py-2">{{ candidat.parcours_scolaire?.nom_ecole }}, {{ candidat.parcours_scolaire?.lieu_ecole }}</td>
                            </tr>
                            </template>
                            <template v-if="candidat.parcours_scolaire?.type_parcours === 'Autre activite'">
                                <tr>
                                <th class="bg-light w-25 fw-normal px-3 py-2 border-end">Description de l'activité</th>
                                <td colspan="3" class="px-3 py-2">{{ candidat.parcours_scolaire?.description_activite }}</td>
                            </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="h5 mb-0 me-3">3. Intérêts professionels</h3>
                    <Link :href="`/candidats/edit/${candidat.id}/stages`" class="text-dark">
                        <i class="bi bi-pencil-square"></i>
                    </Link>
                </div>
                
                <h4 class="h6 mb-3 mt-4">3.1. Stages effectués</h4>
                <div class="bg-white shadow-sm rounded overflow-hidden">
                    <table class="table mb-0 border">
                        <thead class="bg-light">
                            <tr>
                                <th class="fw-normal px-3 py-2 border-end w-25">Métier</th>
                                <th class="fw-normal px-3 py-2 border-end w-25">Entreprise</th>
                                <th class="fw-normal px-3 py-2 border-end w-25">Lieu</th>
                                <th class="fw-normal px-3 py-2">Durée</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="stage in candidat.stages" :key="stage.id">
                                <td class="px-3 py-2 border-end">{{ stage.metier }}</td>
                                <td class="px-3 py-2 border-end">{{ stage.entreprise }}</td>
                                <td class="px-3 py-2 border-end">{{ stage.lieu }}</td>
                                <td class="px-3 py-2">{{ stage.duree }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="h6 mb-3 mt-4">3.2. Autres inscriptions déposées</h4>
                <div class="bg-white shadow-sm rounded overflow-hidden">
                    <table class="table mb-0 border">
                        <thead class="bg-light">
                            <tr>
                                <th class="fw-normal px-3 py-2 border-end w-75">Ecoles / Entreprises (gymnase, apprentissage, etc.)</th>
                                <th class="fw-normal px-3 py-2">Lieu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="inscription in candidat.autres_inscriptions" :key="inscription.id">
                                <td class="px-3 py-2 border-end">{{ inscription.etablissement }}</td>
                                <td class="px-3 py-2">{{ inscription.lieu }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="h5 mb-0 me-3">4. Informations :</h3>
                    <Link :href="`/candidats/edit/${candidat.id}/informations`" class="text-dark">
                        <i class="bi bi-pencil-square"></i>
                    </Link>
                </div>
                <div class="bg-white shadow-sm rounded overflow-hidden">
                    <table class="table mb-0 border">
                        <tbody>
                            <tr>
                                <th class="bg-light w-25 fw-normal px-3 py-2 border-end">Mesures compensatoires</th>
                                <td class="px-3 py-2">{{ candidat.compensations?.reponse === 1 ? 'oui' : 'non' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">Autorisation hors canton</th>
                                <td class="px-3 py-2">{{ candidat.autorisation_hors_canton?.reponse === 1 ? 'oui' : 'non' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="h5 mb-0 me-3">5. Inscription pour un apprentissage de :</h3>
                    <Link :href="`/candidats/edit/${candidat.id}/choix-apprentissage`" class="text-dark">
                        <i class="bi bi-pencil-square"></i>
                    </Link>
                </div>
                <div class="bg-white shadow-sm rounded overflow-hidden">
                    <table class="table mb-0 border">
                        <tbody>
                            <tr>
                                <th class="bg-light w-25 fw-normal px-3 py-2 border-end">1er choix</th>
                                <td class="px-3 py-2">{{ candidat.choix_apprentissage?.premier_choix }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-normal px-3 py-2 border-end">2eme choix</th>
                                <td class="px-3 py-2">{{ candidat.choix_apprentissage?.deuxieme_choix }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</template>