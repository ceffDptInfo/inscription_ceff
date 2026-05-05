<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppHeader from '@/components/AppHeader.vue';

const props = defineProps({
    premier_choix: String,
    deuxieme_choix: String
});

const form = useForm({
    premier_choix: props.premier_choix ?? '',
    deuxieme_choix: props.deuxieme_choix ?? ''
});

const catalogue = [
    {
        categorie: '2 ans AFP',
        metiers: [
            'Praticien en mécanique'
        ]
    },
    {
        categorie: '3 ans CFC',
        metiers: [
            'Mécanicien de production',
            'Monteur automaticien',
            'Opérateur en informatique'
        ]
    },
    {
        categorie: '4 ans CFC',
        metiers: [
            'Automaticien',
            'Dessinateur constructeur industriel',
            'Dessinateur en constructions microtechnique',
            'Électronicien',
            'Électronicien en multimédia',
            'Informaticien',
            'Micromécanicien',
            'Polymécanicien',
            'Qualiticien en microtechnique'
        ]
    }
];

const submit = () => {
    form.post('/choix-apprentissage');
};
</script>

<template>
    <div class="min-vh-100 bg-light pb-5">
        
        <AppHeader :showLogout="true" />

        <div class="container mt-4 mt-md-5">
            
            <h2 class="fs-4 fs-md-2">Inscription pour apprentissage de</h2>

            <div class="card shadow-sm border-0 mt-4">
                
                <div class="card-body p-3 p-md-5">
                    <form @submit.prevent="submit">
                        
                        <div class="row mb-4 d-none d-md-flex">
                            <div class="col-md-6"></div>
                            <div class="col-md-3 text-center fw-medium">1er choix</div>
                            <div class="col-md-3 text-center fw-medium">2eme choix</div>
                        </div>

                        <div v-for="section in catalogue" :key="section.categorie" class="mb-4 mb-md-5">
                            <h4 class="fw-bold mb-3">{{ section.categorie }}</h4>
                            
                            <div v-for="metier in section.metiers" :key="metier" class="row align-items-center mb-4 mb-md-2 border-bottom border-light pb-2 pb-md-0">
                                <div class="col-12 col-md-6 text-dark small mb-2 mb-md-0 fw-bold fw-md-normal">
                                    {{ metier }}
                                </div>
                                <div class="col-6 col-md-3 d-flex flex-column flex-md-row justify-content-center align-items-center">
                                    <span class="d-md-none small text-muted mb-2">1er choix</span>
                                    <input type="radio" class="form-check-input border-secondary border-2 m-0" :value="metier" v-model="form.premier_choix">
                                </div>
                                <div class="col-6 col-md-3 d-flex flex-column flex-md-row justify-content-center align-items-center">
                                    <span class="d-md-none small text-muted mb-2">2ème choix</span>
                                    <input type="radio" class="form-check-input border-secondary border-2 m-0" :value="metier" v-model="form.deuxieme_choix">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <div class="d-flex flex-column flex-md-row justify-content-end gap-3 mt-4">
                            <Link href="/informations" class="btn btn-light border px-4 py-2 fw-bold rounded-3 text-center">Précédent</Link>
                            <button type="submit" class="btn text-white px-5 py-2 fw-bold rounded-3" style="background-color: #38bdf8;" :disabled="form.processing">
                                Suivant
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>