/*
//exemple de loading manuel des composants...
const components = [
    'Application/Utilisateur',
    'Application/UI/UHeures',
    'Exemple/MonTest',
    'Intervenant/Recherche',
    'Mission/Liste',
    'Mission/Mission',
    'Mission/Suivi',
    'Mission/SuiviEvent',
    'Paiement/ListeTaux',
    'Paiement/Taux',
];

let vues = {};
for(const c in components){
    let file = './' + components[c] + '.vue';
    vues[file] = await import(file);
}
*/

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