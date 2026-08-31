<script setup lang="ts">
import { useForm, Link} from '@inertiajs/vue3';
import { ref } from 'vue';
import AppHeader from '@/components/AppHeader.vue';

type TypeLien = 'Pere' | 'Mere' | 'Autre';

interface DonneesPersonnelles {
    nom?: string;
    prenom?: string;
    rue_et_num?: string;
    npa_localite?: string;
    date_naissance?: string;
    langue_maternelle?: string;
    no_avs?: string;
    tel_fixe?: string;
    tel_portable?: string;
    email_prive?: string;
    genre?: string;
    nationalite?: string;
    pays_origine?: string;
    type_permis?: string;
    validite_permis?: string;
    remarques?: string;
}

interface RepresentantLegal {
    type_lien: TypeLien;
    nom: string;
    prenom: string;
    rue_et_num: string;
    npa_localite: string;
    tel_fixe: string;
    tel_portable: string;
    email: string;
}

interface Candidat {
    id: number;
    donnees_personnelles?: DonneesPersonnelles;
    representants_legaux?: Partial<RepresentantLegal>[];
}

const props = defineProps<{
    candidat: Candidat;
}>();

const dp = props.candidat?.donnees_personnelles || {};
const reps = props.candidat?.representants_legaux || [];

const showSecondRep = ref(reps.length > 1);

const form = useForm<{
    nom: string;
    prenom: string;
    rue_et_num: string;
    npa_localite: string;
    date_naissance: string;
    langue_maternelle: string;
    no_avs: string;
    tel_fixe: string;
    tel_portable: string;
    email_prive: string;
    genre: string;
    nationalite: string;
    pays_origine: string;
    type_permis: string;
    validite_permis: string;
    remarques: string;
    rep1: RepresentantLegal;
    rep2: RepresentantLegal;
    has_second_rep: boolean;
}>({
    nom: dp.nom ?? '',
    prenom: dp.prenom ?? '',
    rue_et_num: dp.rue_et_num ?? '',
    npa_localite: dp.npa_localite ?? '',
    date_naissance: dp.date_naissance ?? '',
    langue_maternelle: dp.langue_maternelle ?? '',
    no_avs: dp.no_avs ?? '',
    tel_fixe: dp.tel_fixe ?? '',
    tel_portable: dp.tel_portable ?? '',
    email_prive: dp.email_prive ?? '',
    genre: dp.genre ?? '',
    nationalite: dp.nationalite ?? 'Suisse',
    pays_origine: dp.pays_origine ?? '',
    type_permis: dp.type_permis ?? '',
    validite_permis: dp.validite_permis ?? '',
    remarques: dp.remarques ?? '',
    rep1: {
        type_lien: reps[0]?.type_lien ?? 'Pere',
        nom: reps[0]?.nom ?? '',
        prenom: reps[0]?.prenom ?? '',
        rue_et_num: reps[0]?.rue_et_num ?? '',
        npa_localite: reps[0]?.npa_localite ?? '',
        tel_fixe: reps[0]?.tel_fixe ?? '',
        tel_portable: reps[0]?.tel_portable ?? '',
        email: reps[0]?.email ?? '',
    },
    rep2: {
        type_lien: reps[1]?.type_lien ?? 'Mere',
        nom: reps[1]?.nom ?? '',
        prenom: reps[1]?.prenom ?? '',
        rue_et_num: reps[1]?.rue_et_num ?? '',
        npa_localite: reps[1]?.npa_localite ?? '',
        tel_fixe: reps[1]?.tel_fixe ?? '',
        tel_portable: reps[1]?.tel_portable ?? '',
        email: reps[1]?.email ?? '',
    },
    has_second_rep: showSecondRep.value
});

const toggleSecondRep = () => {
    showSecondRep.value = true;
    form.has_second_rep = true;
};

const removeSecondRep = () => {
    showSecondRep.value = false;
    form.has_second_rep = false;
    form.rep2 = {
        type_lien: 'Mere',
        nom: '',
        prenom: '',
        rue_et_num: '',
        npa_localite: '',
        tel_fixe: '',
        tel_portable: '',
        email: ''
    };
};

const submit = () => {
    form.put(`/candidats/edit/${props.candidat.id}/donnees-personnelles`, {
        preserveScroll: true
    });
};
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        
        <AppHeader :showLogout="true" logoutRoute="/secretaire-logout" />

        <div class="container mt-4 mt-md-5">
            <Link :href="`/candidat-details/${candidat.id}`" class="text-dark text-decoration-none mb-4 d-inline-block fs-5">
                <i class="bi bi-chevron-left"></i> Retour
            </Link>

            <h2 class="fs-4 fs-md-2">Modification des données personnelles</h2>

            <div class="card shadow-sm border-0 mt-3 mt-md-4">
                <div class="card-body p-3 p-md-4">
                    
                    <form @submit.prevent="submit">
                        <h4 class="mb-4">Données personnelles</h4>
                        <div class="row gx-lg-5">
                            
                            <div class="col-lg-6">
                                
                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Nom</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.nom" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Prénom</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.prenom" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Rue et No</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rue_et_num" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">NPA + Localité</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.npa_localite" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Date de naissance</label>
                                    <div class="col-12 col-md-8">
                                        <input type="date" class="form-control" v-model="form.date_naissance" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Langue maternelle</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.langue_maternelle" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">No AVS</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" placeholder="756.xxxx.xxxx.xx" v-model="form.no_avs" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Tél. fixe</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.tel_fixe">
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Tél. portable</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.tel_portable" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">E-mail privé</label>
                                    <div class="col-12 col-md-8">
                                        <input type="email" class="form-control" v-model="form.email_prive" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 d-flex flex-column">
                                
                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Genre</label>
                                    <div class="col-12 col-md-8">
                                        <select class="form-select bg-light" v-model="form.genre" required>
                                            <option value="" disabled>Sélectionner...</option>
                                            <option value="Homme">Homme</option>
                                            <option value="Femme">Femme</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Nationalité</label>
                                    <div class="col-12 col-md-8">
                                        <select class="form-select" v-model="form.nationalite">
                                            <option value="Suisse">Suisse</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="form.nationalite === 'Autre'" class="mt-3">
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-4 col-form-label fw-medium">Pays d'origine</label>
                                        <div class="col-12 col-md-8">
                                            <input type="text" class="form-control" v-model="form.pays_origine">
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-4 col-form-label fw-medium">Permis de séjour</label>
                                        <div class="col-12 col-md-8">
                                            <select class="form-select" v-model="form.type_permis">
                                                <option value="" disabled>Sélectionner...</option>
                                                <option value="B">Permis B</option>
                                                <option value="C">Permis C</option>
                                                <option value="N">Permis N</option>
                                                <option value="F">Permis F</option>
                                                <option value="L">Permis L</option>
                                                <option value="S">Permis S</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-4 col-form-label fw-medium">Validité permis</label>
                                        <div class="col-12 col-md-8">
                                            <input type="date" class="form-control" v-model="form.validite_permis">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <hr class="my-5 text-muted">
                        <h4 class="mb-4">Représentants légaux</h4>

                        <div class="row gx-lg-5">
                            
                            <div class="col-lg-6 d-flex flex-column">
                                <h5 class="mb-4 fs-6 fw-bold">Premier représentant</h5>
                                
                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Lien</label>
                                    <div class="col-12 col-md-8 d-flex flex-wrap gap-3">
                                        <div class="form-check"><input class="form-check-input" type="radio" v-model="form.rep1.type_lien" value="Pere" id="p1"><label class="form-check-label" for="p1">Père</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio" v-model="form.rep1.type_lien" value="Mere" id="m1"><label class="form-check-label" for="m1">Mère</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio" v-model="form.rep1.type_lien" value="Autre" id="a1"><label class="form-check-label" for="a1">Autre</label></div>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Nom</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep1.nom" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Prénom</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep1.prenom" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Rue et No</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep1.rue_et_num" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">NPA Localité</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep1.npa_localite" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Tél. fixe</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep1.tel_fixe">
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Tél. portable</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep1.tel_portable" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">E-mail privé</label>
                                    <div class="col-12 col-md-8">
                                        <input type="email" class="form-control" v-model="form.rep1.email" required>
                                    </div>
                                </div>

                                <div v-if="!showSecondRep" class="mt-4 mb-4 mb-lg-0">
                                    <button type="button" @click="toggleSecondRep" class="btn text-white px-4 py-2 fw-bold rounded-3 w-100 w-md-auto" style="background-color: #38bdf8;">+ Ajouter un deuxième représentant</button>
                                </div>
                            </div>

                            <div v-if="showSecondRep" class="col-lg-6 d-flex flex-column mt-4 mt-lg-0 border-top border-lg-0 pt-4 pt-lg-0">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
                                    <h5 class="mb-0 fs-6 fw-bold">Deuxième représentant</h5>
                                    <button type="button" @click="removeSecondRep" class="btn btn-outline-danger btn-sm align-self-start align-self-sm-auto">Supprimer</button>
                                </div>
                                
                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Lien</label>
                                    <div class="col-12 col-md-8 d-flex flex-wrap gap-3">
                                        <div class="form-check"><input class="form-check-input" type="radio" v-model="form.rep2.type_lien" value="Pere" id="p2"><label class="form-check-label" for="p2">Père</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio" v-model="form.rep2.type_lien" value="Mere" id="m2"><label class="form-check-label" for="m2">Mère</label></div>
                                        <div class="form-check"><input class="form-check-input" type="radio" v-model="form.rep2.type_lien" value="Autre" id="a2"><label class="form-check-label" for="a2">Autre</label></div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Nom</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep2.nom">
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Prénom</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep2.prenom">
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Rue et No</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep2.rue_et_num">
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">NPA Localité</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep2.npa_localite">
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Tél. fixe</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep2.tel_fixe">
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Tél. portable</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.rep2.tel_portable">
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">E-mail privé</label>
                                    <div class="col-12 col-md-8">
                                        <input type="email" class="form-control" v-model="form.rep2.email">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <hr class="my-4 text-muted">

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="row">
                                    <label class="col-12 col-md-2 col-form-label fw-bold">Remarques générales</label>
                                    <div class="col-12 col-md-10">
                                        <textarea class="form-control" rows="3" v-model="form.remarques"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold rounded-3 w-100 w-md-auto" style="background-color: #38bdf8;" :disabled="form.processing">
                                Enregistrer
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>
