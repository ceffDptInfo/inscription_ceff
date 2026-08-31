<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

interface Donnees {
    nom?: string;
    prenom?: string;
    rue_et_num?: string;
    npa?: string;
    localite?: string;
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

const props = defineProps<{
    donnees?: Donnees;
    fichier_permis?: string;
    fichier_photo?: string;
}>();

const form = useForm<{
    nom: string;
    prenom: string;
    rue_et_num: string;
    npa: string;
    localite: string;
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
    document_permis: File | null;
    photo_portrait: File | null;
    remarques: string;
}>({
    nom: props.donnees?.nom ?? '',
    prenom: props.donnees?.prenom ?? '',
    rue_et_num: props.donnees?.rue_et_num ?? '',
    npa: props.donnees?.npa ?? '',
    localite: props.donnees?.localite ?? '',
    date_naissance: props.donnees?.date_naissance ?? '',
    langue_maternelle: props.donnees?.langue_maternelle ?? '',
    no_avs: props.donnees?.no_avs ?? '',
    tel_fixe: props.donnees?.tel_fixe ?? '',
    tel_portable: props.donnees?.tel_portable ?? '',
    email_prive: props.donnees?.email_prive ?? '',
    genre: props.donnees?.genre ?? '',
    nationalite: props.donnees?.nationalite ?? 'Suisse',
    pays_origine: props.donnees?.pays_origine ?? '',
    type_permis: props.donnees?.type_permis ?? '',
    validite_permis: props.donnees?.validite_permis ?? '',
    document_permis: null,
    photo_portrait: null,
    remarques: props.donnees?.remarques ?? ''
});

const submit = () => {
    form.post('/donnees-personnelles', {
        forceFormData: true, 
    });
};

const setDocumentPermis = (event: Event) => {
    const target = event.target as HTMLInputElement;
    form.document_permis = target.files?.[0] ?? null;
};

const setPhotoPortrait = (event: Event) => {
    const target = event.target as HTMLInputElement;
    form.photo_portrait = target.files?.[0] ?? null;
};
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        
        <AppHeader :showLogout="true" />

        <div class="container mt-4 mt-md-5">
            <h2 class="fs-4 fs-md-2">Données personnelles</h2>

            <div class="card shadow-sm border-0 mt-3 mt-md-4">
                <div class="card-body p-3 p-md-4">
                    
                    <form @submit.prevent="submit">
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
                                    <label class="col-12 col-md-4 col-form-label fw-medium">NPA</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.npa" required>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Localité</label>
                                    <div class="col-12 col-md-8">
                                        <input type="text" class="form-control" v-model="form.localite" required>
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
                                        <input type="text" class="form-control" placeholder="756.xxxx.xxxx.xx" v-model="form.no_avs" pattern="^756\.\d{4}\.\d{4}\.\d{2}$" title="Le numéro AVS doit être au format 756.XXXX.XXXX.XX" required>
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

                                    <div class="row mb-3 align-items-center">
                                        <label class="col-12 col-md-4 col-form-label fw-medium">Copie du permis</label>
                                        <div class="col-12 col-md-8">
                                            <label for="document_permis" class="btn btn-outline-secondary w-100 d-block text-truncate mb-0 px-4">
                                                {{ form.document_permis ? form.document_permis.name : (props.fichier_permis || '+') }}
                                            </label>
                                            <input type="file" id="document_permis" class="d-none" @input="setDocumentPermis">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center mt-lg-auto">
                                    <label class="col-12 col-md-4 col-form-label fw-medium">Photo portrait</label>
                                    <div class="col-12 col-md-8">
                                        <label for="photo_portrait" class="btn btn-outline-secondary w-100 d-block text-truncate mb-0 px-4">
                                            {{ form.photo_portrait ? form.photo_portrait.name : (props.fichier_photo || '+') }}
                                        </label>
                                        <input type="file" id="photo_portrait" class="d-none" accept="image/*" @input="setPhotoPortrait">
                                    </div>
                                </div>

                            </div>

                        </div>

                        <hr class="my-4 text-muted">

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="row">
                                    <label class="col-12 col-md-2 col-form-label fw-bold">Remarques</label>
                                    <div class="col-12 col-md-10">
                                        <textarea class="form-control" rows="3" v-model="form.remarques"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold rounded-3 w-100 w-md-auto" style="background-color: #38bdf8;" :disabled="form.processing">
                                Suivant
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>