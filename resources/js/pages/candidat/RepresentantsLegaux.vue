<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppHeader from '@/components/AppHeader.vue';

type TypeLien = 'Pere' | 'Mere' | 'Autre';

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

const props = withDefaults(defineProps<{
    reps?: Partial<RepresentantLegal>[];
}>(), {
    reps: () => [],
});

const showSecondRep = ref(props.reps.length > 1);

const form = useForm<{
    rep1: RepresentantLegal;
    rep2: RepresentantLegal;
    has_second_rep: boolean;
}>({
    rep1: {
        type_lien: props.reps[0]?.type_lien ?? 'Pere',
        nom: props.reps[0]?.nom ?? '',
        prenom: props.reps[0]?.prenom ?? '',
        rue_et_num: props.reps[0]?.rue_et_num ?? '',
        npa_localite: props.reps[0]?.npa_localite ?? '',
        tel_fixe: props.reps[0]?.tel_fixe ?? '',
        tel_portable: props.reps[0]?.tel_portable ?? '',
        email: props.reps[0]?.email ?? '',
    },
    rep2: {
        type_lien: props.reps[1]?.type_lien ?? 'Mere',
        nom: props.reps[1]?.nom ?? '',
        prenom: props.reps[1]?.prenom ?? '',
        rue_et_num: props.reps[1]?.rue_et_num ?? '',
        npa_localite: props.reps[1]?.npa_localite ?? '',
        tel_fixe: props.reps[1]?.tel_fixe ?? '',
        tel_portable: props.reps[1]?.tel_portable ?? '',
        email: props.reps[1]?.email ?? '',
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
    form.post('/representants-legaux', {
        preserveScroll: true
    });
};
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        
        <AppHeader :showLogout="true" />

        <div class="container mt-4 mt-md-5">
            <h2 class="fs-4 fs-md-2">Représentants légaux</h2>

            <div class="card shadow-sm border-0 mt-3 mt-md-4">
                <div class="card-body p-3 p-md-4">
                    
                    <form @submit.prevent="submit">
                        <div class="row gx-lg-5">
                            
                            <div class="col-lg-6 d-flex flex-column">
                                <h4 class="mb-4 fs-5 fs-md-4">Premier représentant</h4>
                                
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
                                    <h4 class="mb-0 fs-5 fs-md-4">Deuxième représentant</h4>
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

                        <div class="d-flex flex-column flex-md-row justify-content-md-end gap-3 mt-4">
                            <Link href="/donnees-personnelles" class="btn btn-light border px-4 py-2 fw-bold rounded-3 text-center">Précédent</Link>
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold rounded-3" style="background-color: #38bdf8;">
                                Suivant
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>
