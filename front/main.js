import {createApp} from 'vue';

const styles = import.meta.glob('./styles/**/*.css', {eager: true});
const scripts = import.meta.glob('./scripts/**/*.js', {eager: true});
const modules = import.meta.glob('./components/**/*.vue', {eager: true});

import autoloadComponents from './components/autoload.js';

/* Chargement de tous les composants */
let componentsPath = "./components/";

const components = {}
for (const path in modules) {
    let compPath = path.slice(componentsPath.length, -4);
    let compName = compPath.replace('/', '');

    components[compName] = modules[path].default;
}

// instantiate the Vue apps
// Note our lookup is a wrapping div with .vue-app class
for (const el of document.getElementsByClassName('vue-app')) {
    let app = createApp({
        template: el.innerHTML,
        components: components
    });

    // autoload de tous les composants déclarés
    for (const alias in autoloadComponents) {
        let compName = autoloadComponents[alias].replace('/', '');
        app.component(alias, components[compName]);
    }
    app.mount(el);
}