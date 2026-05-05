import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        const noLayoutPages = [
            'Home',
            'secretaire/SecretaireLogin',
            'candidat/CandidatLogin',
            'candidat/DonneesPersonnelles',
            'candidat/Annexes',
            'candidat/AutresInscriptions',
            'candidat/ChoixApprentissage',
            'candidat/Informations',
            'candidat/ParcoursScolaire',
            'candidat/RepresentantsLegaux',
            'candidat/Stages',
        ];

        if (noLayoutPages.includes(name)) {
            return null;
        }

        switch (true) {
            case name === 'Welcome':
                return null;
            case name.startsWith('auth/'):
                return AuthLayout;
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];
            default:
                return AppLayout;
        }
    },
    progress: {
        color: '#4B5563',
    },
});

initializeTheme();
initializeFlashToast();