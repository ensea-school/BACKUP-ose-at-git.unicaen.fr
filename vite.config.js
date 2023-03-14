import {defineConfig, splitVendorChunkPlugin} from 'vite'
import vue from '@vitejs/plugin-vue'
import liveReload from 'vite-plugin-live-reload'
import Components from 'unplugin-vue-components/vite'
import { BootstrapVueNextResolver } from 'unplugin-vue-components/resolvers'
import path from 'path'

/* Directives de configuration */

const config = {
    root: 'front',
    entry: 'front/main.js',
    outDir: 'public/dist',
    port: 5133,
    liveReloadPaths: [
        'public/js/**/*.js',
        'public/css/**/*.css'
    ]
}


/* Configuration de Vite */

// absolute paths for hot-loading
let liveReloadPaths = [];
for (p in config.liveReloadPaths) {
    liveReloadPaths[p] = path.resolve(__dirname, config.liveReloadPaths[p]);
}

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        vue(),
        Components({
            resolvers: [BootstrapVueNextResolver()]
        }),
        liveReload(liveReloadPaths),
        splitVendorChunkPlugin()
    ],
    root: config.root,
    build: {
        // output dir for production build
        outDir: path.resolve(__dirname, config.outDir),
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: path.resolve(__dirname, config.entry),
        }
    },
    server: {
        strictPort: true,
        port: config.port
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': path.resolve(__dirname, config.root)
        }
    }
});