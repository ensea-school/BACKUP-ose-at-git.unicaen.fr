import unicaenVue from 'unicaen-vue';
import path from 'path'

// https://vitejs.dev/config/
// unicaenVue.defineConfig surcharge la config avec des paramétrages par défaut,
// puis retourne vite.defineConfig
export default unicaenVue.defineConfig({
    plugins: [
        // placez ici des plugins complémentaires à ceux par défaut
    ],
    root: 'front',
    build: {
        // output dir for production build
        outDir: path.resolve(__dirname, 'public/dist'),
        // On vide le répertoire avant de rebuilder
        emptyOutDir: true,
    },
    server: {
        port: 5133
    },
});