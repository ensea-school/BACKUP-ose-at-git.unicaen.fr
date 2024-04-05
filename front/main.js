const vues = import.meta.glob('./**/*.vue', {eager: true});

import autoloadComponents from './autoload.js';
import vueApp from 'unicaen-vue/js/Client/main'

const options = {
    autoloads: autoloadComponents,
    // beforeMount: (app) => {
    //     console.log('coucou');
    //     console.log(app);
    // }
};

vueApp.init(vues, options);