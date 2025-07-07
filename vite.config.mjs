import unicaenVue from 'unicaen-vue';
import path from 'path';

/**
 * @see https://vitejs.dev/config/
 *
 * la config transmise ci-dessous est surchargée par UnicaenVue.defineConfig, qui ajoute ses propres éléments
 * puis retourne vite.defineConfig
 */

/** @type {import('vite').UserConfig} */
export default unicaenVue.defineConfig({
    // répertoire où seront placés les fichiers *.vue des composants
    root: 'front',
    build: {
        // Répertoire où seront placés les fichiers issus du build et à ajouter au GIT
        // à mettre en cohérence avec la config côté PHP
        outDir: path.resolve(__dirname, 'public/dist'),
    },
    server: {
        // port par défaut utilisé par Node pour communiquer les éléments en "hot-loading"
        // utile uniquement en mode dev, donc
        host: '0.0.0.0',
        port: 5133
    },
    resolvers: [
        // Liste de resolvers pour faire de l'auto-import
    ],
    logLevel: 'warning',
});