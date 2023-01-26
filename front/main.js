import {createApp} from 'vue';

const vues = import.meta.glob('./**/*.vue', {eager: true});

import autoloadComponents from './autoload.js';

/* Chargement de tous les composants */
let componentsPath = "./";

const components = {}
for (const path in vues) {
    let compPath = path.slice(componentsPath.length, -4);
    let compName = compPath.replace('/', '');

    components[compName] = vues[path].default;
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